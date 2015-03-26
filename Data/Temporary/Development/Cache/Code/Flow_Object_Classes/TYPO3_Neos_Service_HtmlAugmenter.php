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
use TYPO3\Neos\Exception;

/**
 * A tool that can augment HTML for example by adding arbitrary attributes.
 * This is used in order to add meta data arguments to content elements in the Backend.
 *
 * @Flow\Scope("singleton")
 */
class HtmlAugmenter_Original {

	/**
	 * Adds the given $attributes to the $html by augmenting the root element.
	 * Attributes are merged with the existing root element's attributes.
	 * If no unique root node can be determined, a wrapping tag is added with all the given attributes. The name of this
	 * tag can be specified with $fallbackTagName.
	 *
	 * @param string $html
	 * @param array $attributes
	 * @param string $fallbackTagName
	 * @return string
	 */
	public function addAttributes($html, array $attributes, $fallbackTagName = 'div') {
		if ($attributes === array()) {
			return $html;
		}
		$rootElement = $this->getHtmlRootElement($html);
		if ($rootElement === NULL) {
			return sprintf('<%s%s>%s</%s>', $fallbackTagName, $this->renderAttributes($attributes), $html, $fallbackTagName);
		}
		$this->mergeAttributes($rootElement, $attributes);
		return preg_replace('/<(' . $rootElement->nodeName . ')\b[^>]*>/xi', '<$1' . $this->renderAttributes($attributes) . '>', $html, 1, $numberOfReplacements);
	}

	/**
	 * Detects a unique root tag in the given $html string and returns its DOMNode representation - or NULL if no unique root element could be found
	 *
	 * @param string $html
	 * @return \DOMNode
	 */
	protected function getHtmlRootElement($html) {
		if (trim($html) === '') {
			return NULL;
		}
		$domDocument = new \DOMDocument('1.0', 'UTF-8');
		// ignore parsing errors
		$useInternalErrorsBackup = libxml_use_internal_errors(TRUE);
		$domDocument->loadHTML($html);
		$xPath = new \DOMXPath($domDocument);
		$rootElement = $xPath->query('//html/body/*');
		if ($useInternalErrorsBackup !== TRUE) {
			libxml_use_internal_errors($useInternalErrorsBackup);
		}
		if ($rootElement === FALSE || $rootElement->length !== 1) {
			return NULL;
		}
		return $rootElement->item(0);
	}

	/**
	 * Merges the attributes of $element with the given $newAttributes
	 * If an attribute exists in both collections, it is merged to "<new attribute> <old attribute>" (if both values differ)
	 *
	 * @param \DOMNode $element
	 * @param array $newAttributes
	 * @return void
	 */
	protected function mergeAttributes(\DOMNode $element, array &$newAttributes) {
		/** @var $attribute \DOMAttr */
		foreach ($element->attributes as $attribute) {
			$oldAttributeValue = $attribute->hasChildNodes() ? $attribute->value : NULL;
			$newAttributeValue = isset($newAttributes[$attribute->name]) ? $newAttributes[$attribute->name] : NULL;
			$mergedAttributes = array();
			if ($newAttributeValue !== NULL && $newAttributeValue !== $oldAttributeValue) {
				$mergedAttributes[] = $newAttributeValue;
			}
			if ($oldAttributeValue !== NULL) {
				$mergedAttributes[] = $oldAttributeValue;
			}
			$newAttributes[$attribute->name] = $mergedAttributes !== array() ? implode(' ', $mergedAttributes) : NULL;
		}
	}

	/**
	 * Renders the given key/value pair to a valid attribute string in the format <key1>="<value1>" <key2>="<value2>"...
	 *
	 * @param array $attributes The attributes to render in the format array('<attributeKey>' => '<attributeValue>', ...)
	 * @return string
	 * @throws Exception
	 */
	protected function renderAttributes(array $attributes) {
		$renderedAttributes = '';
		foreach ($attributes as $attributeName => $attributeValue) {
			$encodedAttributeName = htmlspecialchars($attributeName, ENT_COMPAT, 'UTF-8', FALSE);
			if ($attributeValue === NULL) {
				$renderedAttributes .= ' ' . $encodedAttributeName;
			} else {
				if (is_array($attributeValue) || (is_object($attributeValue) && !method_exists($attributeValue, '__toString'))) {
					throw new Exception(sprintf('Only attributes with string values can be rendered, attribute %s is of type %s', $attributeName, gettype($attributeValue)));
				}

				$encodedAttributeValue = htmlspecialchars((string)$attributeValue, ENT_COMPAT, 'UTF-8', FALSE);
				$renderedAttributes .= ' ' . $encodedAttributeName . '="' . $encodedAttributeValue . '"';
			}
		}
		return $renderedAttributes;
	}
}namespace TYPO3\Neos\Service;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * A tool that can augment HTML for example by adding arbitrary attributes.
 * This is used in order to add meta data arguments to content elements in the Backend.
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class HtmlAugmenter extends HtmlAugmenter_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\Neos\Service\HtmlAugmenter') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Service\HtmlAugmenter', $this);
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\Neos\Service\HtmlAugmenter') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Service\HtmlAugmenter', $this);

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
			}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __sleep() {
		$result = NULL;
		$this->Flow_Object_PropertiesToSerialize = array();
	$reflectionService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Reflection\ReflectionService');
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\Service\HtmlAugmenter');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\Service\HtmlAugmenter', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\Service\HtmlAugmenter', $propertyName, 'var');
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
}
#