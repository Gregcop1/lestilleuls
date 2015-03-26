<?php 
namespace TYPO3\Neos\Domain\Service;

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
use TYPO3\Flow\Utility\Arrays;
use TYPO3\Neos\Domain\Model\Domain;
use TYPO3\Neos\Domain\Model\Site;
use TYPO3\TYPO3CR\Domain\Service\ContextFactory;
use TYPO3\TYPO3CR\Exception\InvalidNodeContextException;

/**
 * ContentContextFactory which ensures contexts stay unique. Make sure to
 * get ContextFactoryInterface injected instead of this class.
 *
 * See \TYPO3\TYPO3CR\Domain\Service\ContextFactory->build for detailed
 * explanations about the usage.
 *
 * @Flow\Scope("singleton")
 */
class ContentContextFactory_Original extends ContextFactory {

	/**
	 * The context implementation this factory will create
	 *
	 * @var string
	 */
	protected $contextImplementation = 'TYPO3\Neos\Domain\Service\ContentContext';

	/**
	 * Creates the actual Context instance.
	 * This needs to be overridden if the Builder is extended.
	 *
	 * @param array $contextProperties
	 * @return ContentContext
	 */
	protected function buildContextInstance(array $contextProperties) {
		$contextProperties = $this->removeDeprecatedProperties($contextProperties);

		return new ContentContext(
			$contextProperties['workspaceName'],
			$contextProperties['currentDateTime'],
			$contextProperties['dimensions'],
			$contextProperties['targetDimensions'],
			$contextProperties['invisibleContentShown'],
			$contextProperties['removedContentShown'],
			$contextProperties['inaccessibleContentShown'],
			$contextProperties['currentSite'],
			$contextProperties['currentDomain']
		);
	}

	/**
	 * Merges the given context properties with sane defaults for the context implementation.
	 *
	 * @param array $contextProperties
	 * @return array
	 */
	protected function mergeContextPropertiesWithDefaults(array $contextProperties) {
		$contextProperties = $this->removeDeprecatedProperties($contextProperties);

		$defaultContextProperties = array (
			'workspaceName' => 'live',
			'currentDateTime' => $this->now,
			'dimensions' => array(),
			'targetDimensions' => array(),
			'invisibleContentShown' => FALSE,
			'removedContentShown' => FALSE,
			'inaccessibleContentShown' => FALSE,
			'currentSite' => NULL,
			'currentDomain' => NULL
		);

		$mergedProperties = Arrays::arrayMergeRecursiveOverrule($defaultContextProperties, $contextProperties, TRUE);

		$this->mergeDimensionValues($contextProperties, $mergedProperties);
		$this->mergeTargetDimensionContextProperties($contextProperties, $mergedProperties, $defaultContextProperties);

		return $mergedProperties;
	}

	/**
	 * This creates the actual identifier and needs to be overridden by builders extending this.
	 *
	 * @param array $contextProperties
	 * @return string
	 */
	protected function getIdentifierSource(array $contextProperties) {
		ksort($contextProperties);
		$identifierSource = $this->contextImplementation;
		foreach ($contextProperties as $propertyName => $propertyValue) {
			if ($propertyName === 'dimensions') {
				$stringParts = array();
				foreach ($propertyValue as $dimensionName => $dimensionValues) {
					$stringParts[] = $dimensionName . '=' . implode(',', $dimensionValues);
				}
				$stringValue = implode('&', $stringParts);
			} elseif ($propertyName === 'targetDimensions') {
				$stringParts = array();
				foreach ($propertyValue as $dimensionName => $dimensionValue) {
					$stringParts[] = $dimensionName . '=' . $dimensionValue;
				}
				$stringValue = implode('&', $stringParts);
			} elseif ($propertyValue instanceof \DateTime) {
				$stringValue = $propertyValue->getTimestamp();
			} elseif ($propertyValue instanceof Site) {
				$stringValue = $propertyValue->getNodeName();
			} elseif ($propertyValue instanceof Domain) {
				$stringValue = $propertyValue->getHostPattern();
			} else {
				$stringValue = (string)$propertyValue;
			}
			$identifierSource .= ':' . $stringValue;
		}

		return $identifierSource;
	}

	/**
	 * @param array $contextProperties
	 * @return void
	 * @throws InvalidNodeContextException
	 */
	protected function validateContextProperties($contextProperties) {
		parent::validateContextProperties($contextProperties);

		if (isset($contextProperties['currentSite'])) {
			if (!$contextProperties['currentSite'] instanceof Site) {
				throw new InvalidNodeContextException('You tried to set currentSite in the context and did not provide a \\TYPO3\Neos\\Domain\\Model\\Site object as value.', 1373145297);
			}
		}
		if (isset($contextProperties['currentDomain'])) {
			if (!$contextProperties['currentDomain'] instanceof Domain) {
				throw new InvalidNodeContextException('You tried to set currentDomain in the context and did not provide a \\TYPO3\Neos\\Domain\\Model\\Domain object as value.', 1373145384);
			}
		}
	}

}
namespace TYPO3\Neos\Domain\Service;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * ContentContextFactory which ensures contexts stay unique. Make sure to
 * get ContextFactoryInterface injected instead of this class.
 * 
 * See \TYPO3\TYPO3CR\Domain\Service\ContextFactory->build for detailed
 * explanations about the usage.
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class ContentContextFactory extends ContentContextFactory_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\Neos\Domain\Service\ContentContextFactory') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Domain\Service\ContentContextFactory', $this);
		if (get_class($this) === 'TYPO3\Neos\Domain\Service\ContentContextFactory') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface', $this);
		if ('TYPO3\Neos\Domain\Service\ContentContextFactory' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\Neos\Domain\Service\ContentContextFactory') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Domain\Service\ContentContextFactory', $this);
		if (get_class($this) === 'TYPO3\Neos\Domain\Service\ContentContextFactory') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface', $this);

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
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\Domain\Service\ContentContextFactory');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\Domain\Service\ContentContextFactory', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\Domain\Service\ContentContextFactory', $propertyName, 'var');
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
		$contentDimensionRepository_reference = &$this->contentDimensionRepository;
		$this->contentDimensionRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Repository\ContentDimensionRepository');
		if ($this->contentDimensionRepository === NULL) {
			$this->contentDimensionRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('c4ce8954e3d47ef3fdb068b6c07c9ebb', $contentDimensionRepository_reference);
			if ($this->contentDimensionRepository === NULL) {
				$this->contentDimensionRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('c4ce8954e3d47ef3fdb068b6c07c9ebb',  $contentDimensionRepository_reference, 'TYPO3\TYPO3CR\Domain\Repository\ContentDimensionRepository', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Repository\ContentDimensionRepository'); });
			}
		}
		$this->now = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Utility\Now');
$this->Flow_Injected_Properties = array (
  0 => 'contentDimensionRepository',
  1 => 'now',
);
	}
}
#