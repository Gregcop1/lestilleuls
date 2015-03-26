<?php 
namespace TYPO3\TYPO3CR\Domain\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3CR".               *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Utility\Arrays;
use TYPO3\Flow\Utility\Now;
use TYPO3\TYPO3CR\Domain\Model\ContentDimension;
use TYPO3\TYPO3CR\Domain\Repository\ContentDimensionRepository;
use TYPO3\TYPO3CR\Exception\InvalidNodeContextException;

/**
 * The ContextFactory makes sure you don't create context instances with
 * the same properties twice. Calling create() with the same parameters
 * a second time will return the _same_ Context instance again.
 * Refer to 'ContextFactoryInterface' instead of 'ContextFactory' when
 * injecting this factory into your own class.
 *
 * @Flow\Scope("singleton")
 */
class ContextFactory_Original implements ContextFactoryInterface {

	/**
	 * @var array<\TYPO3\TYPO3CR\Domain\Service\Context>
	 */
	protected $contextInstances = array();

	/**
	 * The context implementation this factory will create
	 *
	 * @var string
	 */
	protected $contextImplementation = 'TYPO3\TYPO3CR\Domain\Service\Context';

	/**
	 * @Flow\Inject
	 * @var ContentDimensionRepository
	 */
	protected $contentDimensionRepository;

	/**
	 * @Flow\Inject(lazy=FALSE)
	 * @var Now
	 */
	protected $now;

	/**
	 * Create the context from the given properties. If a context with those properties was already
	 * created before then the existing one is returned.
	 *
	 * The context properties to give depend on the implementation of the context object, for the
	 * TYPO3\TYPO3CR\Domain\Service\Context it should look like this:
	 *
	 * array(
	 *        'workspaceName' => 'live',
	 *        'currentDateTime' => new \TYPO3\Flow\Utility\Now(),
	 *        'dimensions' => array(...),
	 *        'targetDimensions' => array('language' => 'de', 'persona' => 'Lisa'),
	 *        'invisibleContentShown' => FALSE,
	 *        'removedContentShown' => FALSE,
	 *        'inaccessibleContentShown' => FALSE
	 * )
	 *
	 * This array also shows the defaults that get used if you don't provide a certain property.
	 *
	 * @param array $contextProperties
	 * @return \TYPO3\TYPO3CR\Domain\Service\Context
	 * @api
	 */
	public function create(array $contextProperties = array()) {
		$contextProperties = $this->mergeContextPropertiesWithDefaults($contextProperties);
		$contextIdentifier = $this->getIdentifier($contextProperties);
		if (!isset($this->contextInstances[$contextIdentifier])) {
			$this->validateContextProperties($contextProperties);
			$context = $this->buildContextInstance($contextProperties);
			$this->contextInstances[$contextIdentifier] = $context;
		}

		return $this->contextInstances[$contextIdentifier];
	}

	/**
	 * Creates the actual Context instance.
	 * This needs to be overridden if the Builder is extended.
	 *
	 * @param array $contextProperties
	 * @return \TYPO3\TYPO3CR\Domain\Service\Context
	 */
	protected function buildContextInstance(array $contextProperties) {
		$contextProperties = $this->removeDeprecatedProperties($contextProperties);
		return new \TYPO3\TYPO3CR\Domain\Service\Context($contextProperties['workspaceName'], $contextProperties['currentDateTime'], $contextProperties['dimensions'], $contextProperties['targetDimensions'], $contextProperties['invisibleContentShown'], $contextProperties['removedContentShown'], $contextProperties['inaccessibleContentShown']);
	}

	/**
	 * Merges the given context properties with sane defaults for the context implementation.
	 *
	 * @param array $contextProperties
	 * @return array
	 */
	protected function mergeContextPropertiesWithDefaults(array $contextProperties) {
		$contextProperties = $this->removeDeprecatedProperties($contextProperties);

		$defaultContextProperties = array(
			'workspaceName' => 'live',
			'currentDateTime' => $this->now,
			'dimensions' => array(),
			'targetDimensions' => array(),
			'invisibleContentShown' => FALSE,
			'removedContentShown' => FALSE,
			'inaccessibleContentShown' => FALSE
		);

		$mergedProperties = Arrays::arrayMergeRecursiveOverrule($defaultContextProperties, $contextProperties, TRUE);

		$this->mergeDimensionValues($contextProperties, $mergedProperties);
		$this->mergeTargetDimensionContextProperties($contextProperties, $mergedProperties, $defaultContextProperties);

		return $mergedProperties;
	}

	/**
	 * Provides a way to identify a context to prevent duplicate context objects.
	 *
	 * @param array $contextProperties
	 * @return string
	 */
	protected function getIdentifier(array $contextProperties) {
		return md5($this->getIdentifierSource($contextProperties));
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
			} else {
				$stringValue = $propertyValue instanceof \DateTime ? $propertyValue->getTimestamp() : (string)$propertyValue;
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
		if (isset($contextProperties['workspaceName'])) {
			if (!is_string($contextProperties['workspaceName']) || $contextProperties['workspaceName'] === '') {
				throw new InvalidNodeContextException('You tried to set a workspaceName in the context that was either no string or an empty string.', 1373144966);
			}
		}
		if (isset($contextProperties['invisibleContentShown'])) {
			if (!is_bool($contextProperties['invisibleContentShown'])) {
				throw new InvalidNodeContextException('You tried to set invisibleContentShown in the context and did not provide a boolean value.', 1373145239);
			}
		}
		if (isset($contextProperties['removedContentShown'])) {
			if (!is_bool($contextProperties['removedContentShown'])) {
				throw new InvalidNodeContextException('You tried to set removedContentShown in the context and did not provide a boolean value.', 1373145239);
			}
		}
		if (isset($contextProperties['inaccessibleContentShown'])) {
			if (!is_bool($contextProperties['inaccessibleContentShown'])) {
				throw new InvalidNodeContextException('You tried to set inaccessibleContentShown in the context and did not provide a boolean value.', 1373145239);
			}
		}
		if (isset($contextProperties['currentDateTime'])) {
			if (!$contextProperties['currentDateTime'] instanceof \DateTime) {
				throw new InvalidNodeContextException('You tried to set currentDateTime in the context and did not provide a DateTime object as value.', 1373145297);
			}
		}

		$dimensions = $this->getAvailableDimensions();
		/** @var ContentDimension $dimension */
		foreach ($dimensions as $dimension) {
			if (!isset($contextProperties['dimensions'][$dimension->getIdentifier()])
				|| !is_array($contextProperties['dimensions'][$dimension->getIdentifier()])
				|| $contextProperties['dimensions'][$dimension->getIdentifier()] === array()
			) {
				throw new InvalidNodeContextException(sprintf('You have to set a non-empty array with one or more values for content dimension "%s" in the context', $dimension->getIdentifier()), 1390300646);
			}
		}

		foreach ($contextProperties['targetDimensions'] as $dimensionName => $dimensionValue) {
			if (!isset($contextProperties['dimensions'][$dimensionName])) {
				throw new InvalidNodeContextException(sprintf('Failed creating a %s because the specified target dimension "%s" does not exist', $this->contextImplementation, $dimensionName), 1391340781);
			}
			if (!in_array($dimensionValue, $contextProperties['dimensions'][$dimensionName])) {
				throw new InvalidNodeContextException(sprintf('Failed creating a %s because the specified target dimension value %s for dimension %s is not in the list of dimension values (%s)', $this->contextImplementation, $dimensionValue, $dimensionName, implode(', ', $contextProperties['dimensions'][$dimensionName])), 1391340741);
			}
		}
	}

	/**
	 * Removes context properties which have been previously allowed but are not supported
	 * anymore and should be silently ignored
	 *
	 * @param array $contextProperties
	 * @return array
	 */
	protected function removeDeprecatedProperties(array $contextProperties) {
		if (isset($contextProperties['locale'])) {
			unset($contextProperties['locale']);
		}
		return $contextProperties;
	}

	/**
	 * @return array<\TYPO3\TYPO3CR\Domain\Model\ContentDimension>
	 */
	protected function getAvailableDimensions() {
		return $this->contentDimensionRepository->findAll();
	}

	/**
	 * Reset instances (internal)
	 */
	public function reset() {
		$this->contextInstances = array();
	}

	/**
	 * @param array $contextProperties
	 * @param array $mergedProperties
	 * @param array $defaultContextProperties
	 * @return mixed
	 */
	protected function mergeTargetDimensionContextProperties(array $contextProperties, &$mergedProperties, $defaultContextProperties) {
			// Use first value of each dimension as default target dimension value
		$defaultContextProperties['targetDimensions'] = array_map(function ($values) {
			return reset($values);
		}, $mergedProperties['dimensions']);
		if (!isset($contextProperties['targetDimensions'])) {
			$contextProperties['targetDimensions'] = array();
		}
		$mergedProperties['targetDimensions'] = Arrays::arrayMergeRecursiveOverrule($defaultContextProperties['targetDimensions'], $contextProperties['targetDimensions']);
	}

	/**
	 * @param array $contextProperties
	 * @param array $mergedProperties
	 */
	protected function mergeDimensionValues(array $contextProperties, array &$mergedProperties) {
		$dimensions = $this->getAvailableDimensions();
		foreach ($dimensions as $dimension) {
			/** @var ContentDimension $dimension */
			$identifier = $dimension->getIdentifier();
			$values = array($dimension->getDefault());
			if (isset($contextProperties['dimensions'][$identifier])) {
				if (!is_array($contextProperties['dimensions'][$identifier])) {
					throw new InvalidNodeContextException(sprintf('The given dimension fallback chain for "%s" should be an array of string, but "%s" was given.', $identifier, gettype($contextProperties['dimensions'][$identifier])), 1407417930);
				}
				$values = Arrays::arrayMergeRecursiveOverrule($values, $contextProperties['dimensions'][$identifier]);
			}
			$mergedProperties['dimensions'][$identifier] = $values;
		}
	}

	/**
	 * Helper method which parses the "dimension" part of the context, i.e.
	 * "locales=de_DE,mul_ZZ&...." into an *array* of dimension values.
	 *
	 * Is needed at both the RoutePartHandler and the ObjectConverter; that's why
	 * it's placed here.
	 *
	 * @param string $dimensionPartOfContext
	 * @return array
	 */
	public function parseDimensionValueStringToArray($dimensionPartOfContext) {
		parse_str($dimensionPartOfContext, $dimensions);
		$dimensions = array_map(function ($commaSeparatedValues) { return explode(',', $commaSeparatedValues); }, $dimensions);

		return $dimensions;
	}

	/**
	 * Internal method to flush first-level node caches of all contexts
	 *
	 * @return void
	 */
	public function flushFirstLevelNodeCaches() {
		foreach ($this->contextInstances as $context) {
			$context->getFirstLevelNodeCache()->flush();
		}
	}

}
namespace TYPO3\TYPO3CR\Domain\Service;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * The ContextFactory makes sure you don't create context instances with
 * the same properties twice. Calling create() with the same parameters
 * a second time will return the _same_ Context instance again.
 * Refer to 'ContextFactoryInterface' instead of 'ContextFactory' when
 * injecting this factory into your own class.
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class ContextFactory extends ContextFactory_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\TYPO3CR\Domain\Service\ContextFactory') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\TYPO3CR\Domain\Service\ContextFactory', $this);
		if ('TYPO3\TYPO3CR\Domain\Service\ContextFactory' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\TYPO3CR\Domain\Service\ContextFactory') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\TYPO3CR\Domain\Service\ContextFactory', $this);

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
	$reflectedClass = new \ReflectionClass('TYPO3\TYPO3CR\Domain\Service\ContextFactory');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\TYPO3CR\Domain\Service\ContextFactory', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\TYPO3CR\Domain\Service\ContextFactory', $propertyName, 'var');
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