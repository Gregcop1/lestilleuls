<?php 
namespace TYPO3\Neos\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Neos".            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Object\ObjectManagerInterface;
use TYPO3\Flow\Persistence\PersistenceManagerInterface;
use TYPO3\Flow\Reflection\ObjectAccess;
use TYPO3\Flow\Security\Authorization\AccessDecisionManagerInterface;
use TYPO3\Neos\Domain\Service\ContentContext;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

/**
 * The content element wrapping service adds the necessary markup around
 * a content element such that it can be edited using the Content Module
 * of the Neos Backend.
 *
 * @Flow\Scope("singleton")
 */
class ContentElementWrappingService_Original {

	/**
	 * @Flow\Inject
	 * @var ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @Flow\Inject
	 * @var PersistenceManagerInterface
	 */
	protected $persistenceManager;

	/**
	 * @Flow\Inject
	 * @var AccessDecisionManagerInterface
	 */
	protected $accessDecisionManager;

	/**
	 * @Flow\Inject
	 * @var HtmlAugmenter
	 */
	protected $htmlAugmenter;

	/**
	 * Wrap the $content identified by $node with the needed markup for the backend.
	 *
	 * @param NodeInterface $node
	 * @param string $typoScriptPath
	 * @param string $content
	 * @param boolean $renderCurrentDocumentMetadata When this flag is set we will render the global metadata for the current document
	 * @return string
	 */
	public function wrapContentObject(NodeInterface $node, $typoScriptPath, $content, $renderCurrentDocumentMetadata = FALSE) {
		/** @var $contentContext ContentContext */
		$contentContext = $node->getContext();
		if ($contentContext->getWorkspaceName() === 'live' || !$this->accessDecisionManager->hasAccessToResource('TYPO3_Neos_Backend_GeneralAccess')) {
			return $content;
		}
		$nodeType = $node->getNodeType();
		$attributes = array();
		$attributes['typeof'] = 'typo3:' . $nodeType->getName();
		$attributes['about'] = $node->getContextPath();

		$classNames = array();
		if ($renderCurrentDocumentMetadata === TRUE) {
			$attributes['data-neos-site-name'] = $contentContext->getCurrentSite()->getName();
			$attributes['data-neos-site-node-context-path'] = $contentContext->getCurrentSiteNode()->getContextPath();
			// Add the workspace of the TYPO3CR context to the attributes
			$attributes['data-neos-context-workspace-name'] = $contentContext->getWorkspaceName();
			$attributes['data-neos-context-dimensions'] = json_encode($contentContext->getDimensions());
		} else {
			if ($node->isRemoved()) {
				$classNames[] = 'neos-contentelement-removed';
			}

			if ($node->isHidden()) {
				$classNames[] = 'neos-contentelement-hidden';
			}

			if ($nodeType->isOfType('TYPO3.Neos:ContentCollection')) {
				$attributes['rel'] = 'typo3:content-collection';
			} else {
				$classNames[] = 'neos-contentelement';
			}

			$uiConfiguration = $nodeType->hasConfiguration('ui') ? $nodeType->getConfiguration('ui') : array();
			if ((isset($uiConfiguration['inlineEditable']) && $uiConfiguration['inlineEditable'] !== TRUE) || (!isset($uiConfiguration['inlineEditable']) && !$this->hasInlineEditableProperties($node))) {
				$classNames[] = 'neos-not-inline-editable';
			}

			$attributes['tabindex'] = 0;
		}

		if (!$node->dimensionsAreMatchingTargetDimensionValues()) {
			$classNames[] = 'neos-contentelement-shine-through';
		}

		if (count($classNames) > 0) {
			$attributes['class'] = implode(' ', $classNames);
		}

		// Add the actual workspace of the node, the node identifier and the TypoScript path to the attributes
		$attributes['data-node-_identifier'] = $node->getIdentifier();
		$attributes['data-node-__workspace-name'] = $node->getWorkspace()->getName();
		$attributes['data-node-__typoscript-path'] = $typoScriptPath;

		// these properties are needed together with the current NodeType to evaluate Node Type Constraints
		// TODO: this can probably be greatly cleaned up once we do not use CreateJS or VIE anymore.
		if ($node->getParent()) {
			$attributes['data-node-__parent-node-type'] = $node->getParent()->getNodeType()->getName();
		}

		if ($node->isAutoCreated()) {
			$attributes['data-node-_name'] = $node->getName();
		}

		if ($node->getParent() && $node->getParent()->isAutoCreated()) {
			// we shall only add these properties if the parent is actually auto-created; as the Node-Type-Switcher in the UI relies on that.
			$attributes['data-node-__parent-node-name'] = $node->getParent()->getName();
			$attributes['data-node-__grandparent-node-type'] = $node->getParent()->getParent()->getNodeType()->getName();
		}

		$attributes = $this->addNodePropertyAttributes($node, $attributes);

		return $this->htmlAugmenter->addAttributes($content, $attributes);
	}

	/**
	 * Adds node properties to the given $attributes collection and returns the extended array
	 *
	 * @param NodeInterface $node
	 * @param array $attributes
	 * @return array the merged attributes
	 */
	public function addNodePropertyAttributes(NodeInterface $node, array $attributes) {
		foreach ($node->getNodeType()->getProperties() as $propertyName => $propertyConfiguration) {
			if (substr($propertyName, 0, 2) === '__') {
				// skip fully-private properties
				continue;
			}
			/** @var $contentContext ContentContext */
			$contentContext = $node->getContext();
			if ($propertyName === '_name' && $node === $contentContext->getCurrentSiteNode()) {
				// skip the node name of the site node
				continue;
			}
			$dataType = isset($propertyConfiguration['type']) ? $propertyConfiguration['type'] : 'string';
			$dasherizedPropertyName = $this->dasherize($propertyName);
			$attributes['data-node-' . $dasherizedPropertyName] = $this->getNodeProperty($node, $propertyName, $dataType);
			if ($dataType !== 'string') {
				$prefixedDataType = $dataType === 'jsonEncoded' ? 'typo3:jsonEncoded' : 'xsd:' . $dataType;
				$attributes['data-nodedatatype-' . $dasherizedPropertyName] = $prefixedDataType;
			}
		}
		return $attributes;
	}

	/**
	 * TODO This implementation is directly linked to the inspector editors, since they need the actual values
	 *
	 * @param NodeInterface $node
	 * @param string $propertyName
	 * @param string $dataType
	 * @return string
	 */
	protected function getNodeProperty(NodeInterface $node, $propertyName, &$dataType) {
		if (substr($propertyName, 0, 1) === '_') {
			$propertyValue = ObjectAccess::getProperty($node, substr($propertyName, 1));
		} else {
			$propertyValue = $node->getProperty($propertyName);
		}

		// Enforce an integer value for integer properties as otherwise javascript will give NaN and VIE converts it to an array containing 16 times 'NaN'
		if ($dataType === 'integer') {
			$propertyValue = (integer)$propertyValue;
		}

		// Serialize boolean values to String
		if ($dataType === 'boolean') {
			return $propertyValue ? 'true' : 'false';
		}

		// Serialize array values to String
		if ($dataType === 'array') {
			return $propertyValue ? json_encode($propertyValue, JSON_UNESCAPED_UNICODE) : NULL;
		}

		// Serialize date values to String
		if ($dataType === 'date') {
			if (!$propertyValue instanceof \DateTime) {
				return '';
			}
			$value = clone $propertyValue;
			return $value->setTimezone(new \DateTimeZone('UTC'))->format(\DateTime::W3C);
		}

		// Serialize node references to node identifiers
		if ($dataType === 'references') {
			$nodeIdentifiers = array();
			if (is_array($propertyValue)) {
				/** @var $subNode NodeInterface */
				foreach ($propertyValue as $subNode) {
					$nodeIdentifiers[] = $subNode->getIdentifier();
				}
			}
			return json_encode($nodeIdentifiers);
		}

		// Serialize node reference to node identifier
		if ($dataType === 'reference') {
			if ($propertyValue instanceof NodeInterface) {
				return $propertyValue->getIdentifier();
			} else {
				return '';
			}
		}

		// Serialize ImageVariant to JSON
		if ($propertyValue instanceof \TYPO3\Media\Domain\Model\ImageVariant) {
			$gettableProperties = ObjectAccess::getGettableProperties($propertyValue);
			$convertedProperties = array();
			foreach ($gettableProperties as $key => $value) {
				if (is_object($value)) {
					$entityIdentifier = $this->persistenceManager->getIdentifierByObject($value);
					if ($entityIdentifier !== NULL) {
						$value = $entityIdentifier;
					}
				}
				$convertedProperties[$key] = $value;
			}
			return json_encode($convertedProperties);
		}

		// Serialize an Asset to JSON (the NodeConverter expects JSON for object type properties)
		if ($dataType === ltrim('TYPO3\Media\Domain\Model\Asset', '\\') && $propertyValue !== NULL) {
			if ($propertyValue instanceof \TYPO3\Media\Domain\Model\Asset) {
				return json_encode($this->persistenceManager->getIdentifierByObject($propertyValue));
			}
		}

		// Serialize an array of Assets to JSON
		if (is_array($propertyValue)) {
			$parsedType = \TYPO3\Flow\Utility\TypeHandling::parseType($dataType);
			if ($parsedType['elementType'] === ltrim('TYPO3\Media\Domain\Model\Asset', '\\')) {
				$convertedValues = array();
				foreach ($propertyValue as $singlePropertyValue) {
					if ($singlePropertyValue instanceof \TYPO3\Media\Domain\Model\Asset) {
						$convertedValues[] = $this->persistenceManager->getIdentifierByObject($singlePropertyValue);
					}
				}
				return json_encode($convertedValues);
			}
		}
		return $propertyValue === NULL ? '' : $propertyValue;
	}

	/**
	 * @param NodeInterface $node
	 * @return boolean
	 */
	protected function hasInlineEditableProperties(NodeInterface $node) {
		foreach (array_values($node->getNodeType()->getProperties()) as $propertyConfiguration) {
			if (isset($propertyConfiguration['ui']['inlineEditable']) && $propertyConfiguration['ui']['inlineEditable'] === TRUE) {
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * Converts camelCased strings to lower cased and non-camel-cased strings
	 *
	 * @param string $value
	 * @return string
	 */
	protected function dasherize($value) {
		return strtolower(trim(preg_replace('/[A-Z]/', '-$0', $value), '-'));
	}

}
namespace TYPO3\Neos\Service;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * The content element wrapping service adds the necessary markup around
 * a content element such that it can be edited using the Content Module
 * of the Neos Backend.
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class ContentElementWrappingService extends ContentElementWrappingService_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\Neos\Service\ContentElementWrappingService') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Service\ContentElementWrappingService', $this);
		if ('TYPO3\Neos\Service\ContentElementWrappingService' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\Neos\Service\ContentElementWrappingService') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Service\ContentElementWrappingService', $this);

	if (property_exists($this, 'Flow_Persistence_RelatedEntities') && is_array($this->Flow_Persistence_RelatedEntities)) {
		$persistenceManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Persistence\PersistenceManagerInterface');
		foreach ($this->Flow_Persistence_RelatedEntities as $entityInformation) {
			$entity = $persistenceManager->getObjectByIdentifier($entityInformation['identifier'], $entityInformation['entityType'], TRUE);
			if (isset($entityInformation['entityPath'])) {
				$this->$entityInformation['propertyName'] = \TYPO3\Flow\Utility\Arrays::setValueByPath($this->$entityInformation['propertyName'], $entityInformation['entityPath'], $entity);
			} else {
				$this->$entityInformation['propertyName'] = $entity;
			}
		}
		unset($this->Flow_Persistence_RelatedEntities);
	}
				$this->Flow_Proxy_injectProperties();
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __sleep() {
		$result = NULL;
		$this->Flow_Object_PropertiesToSerialize = array();
	$reflectionService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Reflection\ReflectionService');
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\Service\ContentElementWrappingService');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\Service\ContentElementWrappingService', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
		if (is_array($this->$propertyName) || (is_object($this->$propertyName) && ($this->$propertyName instanceof \ArrayObject || $this->$propertyName instanceof \SplObjectStorage ||$this->$propertyName instanceof \Doctrine\Common\Collections\Collection))) {
			if (count($this->$propertyName) > 0) {
				foreach ($this->$propertyName as $key => $value) {
					$this->searchForEntitiesAndStoreIdentifierArray((string)$key, $value, $propertyName);
				}
			}
		}
		if (is_object($this->$propertyName) && !$this->$propertyName instanceof \Doctrine\Common\Collections\Collection) {
			if ($this->$propertyName instanceof \Doctrine\ORM\Proxy\Proxy) {
				$className = get_parent_class($this->$propertyName);
			} else {
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\Service\ContentElementWrappingService', $propertyName, 'var');
				if (count($varTagValues) > 0) {
					$className = trim($varTagValues[0], '\\');
				}
				if (\TYPO3\Flow\Core\Bootstrap::$staticObjectManager->isRegistered($className) === FALSE) {
					$className = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getObjectNameByClassName(get_class($this->$propertyName));
				}
			}
			if ($this->$propertyName instanceof \TYPO3\Flow\Persistence\Aspect\PersistenceMagicInterface && !\TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Persistence\PersistenceManagerInterface')->isNewObject($this->$propertyName) || $this->$propertyName instanceof \Doctrine\ORM\Proxy\Proxy) {
				if (!property_exists($this, 'Flow_Persistence_RelatedEntities') || !is_array($this->Flow_Persistence_RelatedEntities)) {
					$this->Flow_Persistence_RelatedEntities = array();
					$this->Flow_Object_PropertiesToSerialize[] = 'Flow_Persistence_RelatedEntities';
				}
				$identifier = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Persistence\PersistenceManagerInterface')->getIdentifierByObject($this->$propertyName);
				if (!$identifier && $this->$propertyName instanceof \Doctrine\ORM\Proxy\Proxy) {
					$identifier = current(\TYPO3\Flow\Reflection\ObjectAccess::getProperty($this->$propertyName, '_identifier', TRUE));
				}
				$this->Flow_Persistence_RelatedEntities[$propertyName] = array(
					'propertyName' => $propertyName,
					'entityType' => $className,
					'identifier' => $identifier
				);
				continue;
			}
			if ($className !== FALSE && (\TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getScope($className) === \TYPO3\Flow\Object\Configuration\Configuration::SCOPE_SINGLETON || $className === 'TYPO3\Flow\Object\DependencyInjection\DependencyProxy')) {
				continue;
			}
		}
		$this->Flow_Object_PropertiesToSerialize[] = $propertyName;
	}
	$result = $this->Flow_Object_PropertiesToSerialize;
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 private function searchForEntitiesAndStoreIdentifierArray($path, $propertyValue, $originalPropertyName) {

		if (is_array($propertyValue) || (is_object($propertyValue) && ($propertyValue instanceof \ArrayObject || $propertyValue instanceof \SplObjectStorage))) {
			foreach ($propertyValue as $key => $value) {
				$this->searchForEntitiesAndStoreIdentifierArray($path . '.' . $key, $value, $originalPropertyName);
			}
		} elseif ($propertyValue instanceof \TYPO3\Flow\Persistence\Aspect\PersistenceMagicInterface && !\TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Persistence\PersistenceManagerInterface')->isNewObject($propertyValue) || $propertyValue instanceof \Doctrine\ORM\Proxy\Proxy) {
			if (!property_exists($this, 'Flow_Persistence_RelatedEntities') || !is_array($this->Flow_Persistence_RelatedEntities)) {
				$this->Flow_Persistence_RelatedEntities = array();
				$this->Flow_Object_PropertiesToSerialize[] = 'Flow_Persistence_RelatedEntities';
			}
			if ($propertyValue instanceof \Doctrine\ORM\Proxy\Proxy) {
				$className = get_parent_class($propertyValue);
			} else {
				$className = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getObjectNameByClassName(get_class($propertyValue));
			}
			$identifier = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Persistence\PersistenceManagerInterface')->getIdentifierByObject($propertyValue);
			if (!$identifier && $propertyValue instanceof \Doctrine\ORM\Proxy\Proxy) {
				$identifier = current(\TYPO3\Flow\Reflection\ObjectAccess::getProperty($propertyValue, '_identifier', TRUE));
			}
			$this->Flow_Persistence_RelatedEntities[$originalPropertyName . '.' . $path] = array(
				'propertyName' => $originalPropertyName,
				'entityType' => $className,
				'identifier' => $identifier,
				'entityPath' => $path
			);
			$this->$originalPropertyName = \TYPO3\Flow\Utility\Arrays::setValueByPath($this->$originalPropertyName, $path, NULL);
		}
			}

	/**
	 * Autogenerated Proxy Method
	 */
	 private function Flow_Proxy_injectProperties() {
		$objectManager_reference = &$this->objectManager;
		$this->objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Object\ObjectManagerInterface');
		if ($this->objectManager === NULL) {
			$this->objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('0c3c44be7be16f2a287f1fb2d068dde4', $objectManager_reference);
			if ($this->objectManager === NULL) {
				$this->objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('0c3c44be7be16f2a287f1fb2d068dde4',  $objectManager_reference, 'TYPO3\Flow\Object\ObjectManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Object\ObjectManagerInterface'); });
			}
		}
		$persistenceManager_reference = &$this->persistenceManager;
		$this->persistenceManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Persistence\PersistenceManagerInterface');
		if ($this->persistenceManager === NULL) {
			$this->persistenceManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('f1bc82ad47156d95485678e33f27c110', $persistenceManager_reference);
			if ($this->persistenceManager === NULL) {
				$this->persistenceManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('f1bc82ad47156d95485678e33f27c110',  $persistenceManager_reference, 'TYPO3\Flow\Persistence\Doctrine\PersistenceManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Persistence\PersistenceManagerInterface'); });
			}
		}
		$accessDecisionManager_reference = &$this->accessDecisionManager;
		$this->accessDecisionManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Security\Authorization\AccessDecisionManagerInterface');
		if ($this->accessDecisionManager === NULL) {
			$this->accessDecisionManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('10ee96a39938f84232ff1e0f033d2850', $accessDecisionManager_reference);
			if ($this->accessDecisionManager === NULL) {
				$this->accessDecisionManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('10ee96a39938f84232ff1e0f033d2850',  $accessDecisionManager_reference, 'TYPO3\Flow\Security\Authorization\AccessDecisionVoterManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Security\Authorization\AccessDecisionManagerInterface'); });
			}
		}
		$htmlAugmenter_reference = &$this->htmlAugmenter;
		$this->htmlAugmenter = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Neos\Service\HtmlAugmenter');
		if ($this->htmlAugmenter === NULL) {
			$this->htmlAugmenter = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('a0d9320c255816ab9906181b5038dc15', $htmlAugmenter_reference);
			if ($this->htmlAugmenter === NULL) {
				$this->htmlAugmenter = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('a0d9320c255816ab9906181b5038dc15',  $htmlAugmenter_reference, 'TYPO3\Neos\Service\HtmlAugmenter', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Neos\Service\HtmlAugmenter'); });
			}
		}
$this->Flow_Injected_Properties = array (
  0 => 'objectManager',
  1 => 'persistenceManager',
  2 => 'accessDecisionManager',
  3 => 'htmlAugmenter',
);
	}
}
#