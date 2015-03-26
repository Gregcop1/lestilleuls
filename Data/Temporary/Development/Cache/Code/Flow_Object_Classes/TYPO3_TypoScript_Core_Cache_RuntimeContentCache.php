<?php 
namespace TYPO3\TypoScript\Core\Cache;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.TypoScript".      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cache\CacheAwareInterface;
use TYPO3\TypoScript\Core\Runtime;
use TYPO3\TypoScript\Exception;

/**
 * Integrate the ContentCache into the TypoScript Runtime
 *
 * Holds cache related runtime state.
 */
class RuntimeContentCache_Original {

	/**
	 * @var Runtime
	 */
	protected $runtime;

	/**
	 * @var boolean
	 */
	protected $enableContentCache = FALSE;

	/**
	 * @var boolean
	 */
	protected $inCacheEntryPoint = NULL;

	/**
	 * @var boolean
	 */
	protected $addCacheSegmentMarkersToPlaceholders = FALSE;

	/**
	 * Stack of cached segment metadata (lifetime)
	 *
	 * @var array
	 */
	protected $cacheMetadata = array();

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TypoScript\Core\Cache\ContentCache
	 */
	protected $contentCache;

	/**
	 * @param Runtime $runtime
	 */
	public function __construct(Runtime $runtime) {
		$this->runtime = $runtime;
	}

	/**
	 * Enter an evaluation
	 *
	 * Needs to be called right before evaluation of a path starts to check the cache mode and set internal state
	 * like the cache entry point.
	 *
	 * @param array $configuration
	 * @param string $typoScriptPath
	 * @return array An evaluate context array that needs to be passed to subsequent calls to pass the current state
	 * @throws \TYPO3\TypoScript\Exception
	 */
	public function enter(array $configuration, $typoScriptPath) {
		$cacheForPathEnabled = isset($configuration['mode']) && $configuration['mode'] === 'cached';
		$cacheForPathDisabled = isset($configuration['mode']) && $configuration['mode'] === 'uncached';

		if ($cacheForPathDisabled && (!isset($configuration['context']) || $configuration['context'] === array())) {
			throw new Exception(sprintf('Missing @cache.context configuration for path "%s". An uncached segment must have one or more context variable names configured.', $typoScriptPath), 1395922119);
		}

		$currentPathIsEntryPoint = FALSE;
		if ($this->enableContentCache && $cacheForPathEnabled) {
			if ($this->inCacheEntryPoint === NULL) {
				$this->inCacheEntryPoint = TRUE;
				$currentPathIsEntryPoint = TRUE;
			}
		}

		return array(
			'configuration' => $configuration,
			'typoScriptPath' => $typoScriptPath,
			'cacheForPathEnabled' => $cacheForPathEnabled,
			'cacheForPathDisabled' => $cacheForPathDisabled,
			'currentPathIsEntryPoint' => $currentPathIsEntryPoint
		);
	}

	/**
	 * Check for cached evaluation and or collect metadata for evaluation
	 *
	 * Try to get a cached segment for the current path and return that with all uncached segments evaluated if it
	 * exists. Otherwise metadata for the cache lifetime is collected (if configured) for nested evaluations (to find the
	 * minimum maximumLifetime).
	 *
	 * @param array $evaluateContext The current evaluation context
	 * @param object $tsObject The current TypoScript object (for "this" in evaluations)
	 * @return array Cache hit state as boolean and value as mixed
	 */
	public function preEvaluate(array &$evaluateContext, $tsObject) {
		if ($this->enableContentCache) {
			if ($evaluateContext['cacheForPathEnabled']) {
				$evaluateContext['cacheIdentifierValues'] = $this->buildCacheIdentifierValues($evaluateContext['configuration'], $evaluateContext['typoScriptPath'], $tsObject);

				$self = $this;
				$segment = $this->contentCache->getCachedSegment(function ($command, $unserializedContext) use ($self) {
					if (strpos($command, 'eval=') === 0) {
						$path = substr($command, 5);
						$result = $self->evaluateUncached($path, $unserializedContext);
						return $result;
					} else {
						throw new Exception(sprintf('Unknown uncached command "%s"', $command), 1392837596);
					}
				}, $evaluateContext['typoScriptPath'], $evaluateContext['cacheIdentifierValues'], $this->addCacheSegmentMarkersToPlaceholders);
				if ($segment !== FALSE) {
					return array(TRUE, $segment);
				} else {
					$this->addCacheSegmentMarkersToPlaceholders = TRUE;
				}

				$this->cacheMetadata[] = array(
					'lifetime' => NULL
				);
			}

			if (isset($evaluateContext['configuration']['maximumLifetime'])) {
				$maximumLifetime = $this->runtime->evaluate($evaluateContext['typoScriptPath'] . '/__meta/cache/maximumLifetime', $tsObject);

				if ($maximumLifetime !== NULL && $this->cacheMetadata !== array()) {
					$cacheMetadata = &$this->cacheMetadata[count($this->cacheMetadata) - 1];
					$cacheMetadata['lifetime'] = $cacheMetadata['lifetime'] !== NULL ? min($cacheMetadata['lifetime'], $maximumLifetime) : $maximumLifetime;
				}
			}
		}
		return array(FALSE, NULL);
	}

	/**
	 * Post process output for caching information
	 *
	 * The content cache stores cache segments with markers inside the generated content. This method creates cache
	 * segments and will process the final outer result (currentPathIsEntryPoint) to remove all cache markers and
	 * store cache entries.
	 *
	 * @param array $evaluateContext The current evaluation context
	 * @param object $tsObject The current TypoScript object (for "this" in evaluations)
	 * @param mixed $output The generated output after caching information was removed
	 * @return mixed The post-processed output with cache segment markers or cleaned for the entry point
	 */
	public function postProcess(array $evaluateContext, $tsObject, $output) {
		if ($this->enableContentCache && $evaluateContext['cacheForPathEnabled']) {
			$cacheTags = $this->buildCacheTags($evaluateContext['configuration'], $evaluateContext['typoScriptPath'], $tsObject);
			$cacheMetadata = array_pop($this->cacheMetadata);
			$output = $this->contentCache->createCacheSegment($output, $evaluateContext['typoScriptPath'], $evaluateContext['cacheIdentifierValues'], $cacheTags, $cacheMetadata['lifetime']);
		} elseif ($this->enableContentCache && $evaluateContext['cacheForPathDisabled'] && $this->inCacheEntryPoint) {
			$contextArray = $this->runtime->getCurrentContext();
			if (isset($evaluateContext['configuration']['context'])) {
				$contextVariables = array();
				foreach ($evaluateContext['configuration']['context'] as $contextVariableName) {
					$contextVariables[$contextVariableName] = $contextArray[$contextVariableName];
				}
			} else {
				$contextVariables = $contextArray;
			}
			$output = $this->contentCache->createUncachedSegment($output, $evaluateContext['typoScriptPath'], $contextVariables);
		}

		if ($evaluateContext['cacheForPathEnabled'] && $evaluateContext['currentPathIsEntryPoint']) {
			$output = $this->contentCache->processCacheSegments($output, $this->enableContentCache);
			$this->inCacheEntryPoint = NULL;
			$this->addCacheSegmentMarkersToPlaceholders = FALSE;
		}

		return $output;
	}

	/**
	 * Leave the evaluation of a path
	 *
	 * Has to be called in the same function calling enter() for every return path.
	 *
	 * @param array $evaluateContext The current evaluation context
	 * @return void
	 */
	public function leave(array $evaluateContext) {
		if ($evaluateContext['currentPathIsEntryPoint']) {
			$this->inCacheEntryPoint = NULL;
		}
	}

	/**
	 * Evaluate a TypoScript path with a given context without content caching
	 *
	 * This is used to render uncached segments "out of band" in getCachedSegment of ContentCache.
	 *
	 * @param string $path
	 * @param array $contextArray
	 * @return mixed
	 *
	 * TODO Find another way of disabling the cache (especially to allow cached content inside uncached content)
	 */
	public function evaluateUncached($path, array $contextArray) {
		$previousEnableContentCache = $this->enableContentCache;
		$this->enableContentCache = FALSE;
		$this->runtime->pushContextArray($contextArray);
		$result = $this->runtime->evaluate($path);
		$this->runtime->popContext();
		$this->enableContentCache = $previousEnableContentCache;
		return $result;
	}

	/**
	 * Builds an array of additional key / values which must go into the calculation of the cache entry identifier for
	 * a cached content segment.
	 *
	 * @param array $configuration
	 * @param string $typoScriptPath
	 * @param object $tsObject The actual TypoScript object
	 * @return array
	 */
	protected function buildCacheIdentifierValues($configuration, $typoScriptPath, $tsObject) {
		$cacheIdentifierValues = array();
		if (isset($configuration['entryIdentifier'])) {
			foreach ($configuration['entryIdentifier'] as $identifierKey => $identifierValue) {
				$cacheIdentifierValues[$identifierKey] = $this->runtime->evaluate($typoScriptPath . '/__meta/cache/entryIdentifier/' . $identifierKey, $tsObject);
			}
		} else {
			foreach ($this->runtime->getCurrentContext() as $key => $value) {
				if (is_string($value) || is_bool($value) || is_integer($value) || $value instanceof CacheAwareInterface) {
					$cacheIdentifierValues[$key] = $value;
				}
			}
		}
		return $cacheIdentifierValues;
	}

	/**
	 * Builds an array of string which must be used as tags for the cache entry identifier of a specific cached content segment.
	 *
	 * @param array $configuration
	 * @param string $typoScriptPath
	 * @param object $tsObject The actual TypoScript object
	 * @return array
	 */
	protected function buildCacheTags($configuration, $typoScriptPath, $tsObject) {
		$cacheTags = array();
		if (isset($configuration['entryTags'])) {
			foreach ($configuration['entryTags'] as $tagKey => $tagValue) {
				$tagValue = $this->runtime->evaluate($typoScriptPath . '/__meta/cache/entryTags/' . $tagKey, $tsObject);
				if ((string)$tagValue !== '') {
					$cacheTags[] = $tagValue;
				}
			}
		} else {
			$cacheTags = array(ContentCache::TAG_EVERYTHING);
		}
		return $cacheTags;
	}

	/**
	 * @param boolean $enableContentCache
	 * @return void
	 */
	public function setEnableContentCache($enableContentCache) {
		$this->enableContentCache = $enableContentCache;
	}

	/**
	 * @return boolean
	 */
	public function getEnableContentCache() {
		return $this->enableContentCache;
	}

}
namespace TYPO3\TypoScript\Core\Cache;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * Integrate the ContentCache into the TypoScript Runtime
 * 
 * Holds cache related runtime state.
 */
class RuntimeContentCache extends RuntimeContentCache_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 * @param Runtime $runtime
	 */
	public function __construct() {
		$arguments = func_get_args();

		if (!array_key_exists(0, $arguments)) $arguments[0] = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TypoScript\Core\Runtime');
		if (!array_key_exists(0, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $runtime in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		call_user_func_array('parent::__construct', $arguments);
		if ('TYPO3\TypoScript\Core\Cache\RuntimeContentCache' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {

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
	$reflectedClass = new \ReflectionClass('TYPO3\TypoScript\Core\Cache\RuntimeContentCache');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\TypoScript\Core\Cache\RuntimeContentCache', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\TypoScript\Core\Cache\RuntimeContentCache', $propertyName, 'var');
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
		$contentCache_reference = &$this->contentCache;
		$this->contentCache = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TypoScript\Core\Cache\ContentCache');
		if ($this->contentCache === NULL) {
			$this->contentCache = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('47de6efd5fde41cfd6e087327f547a00', $contentCache_reference);
			if ($this->contentCache === NULL) {
				$this->contentCache = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('47de6efd5fde41cfd6e087327f547a00',  $contentCache_reference, 'TYPO3\TypoScript\Core\Cache\ContentCache', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TypoScript\Core\Cache\ContentCache'); });
			}
		}
$this->Flow_Injected_Properties = array (
  0 => 'contentCache',
);
	}
}
#