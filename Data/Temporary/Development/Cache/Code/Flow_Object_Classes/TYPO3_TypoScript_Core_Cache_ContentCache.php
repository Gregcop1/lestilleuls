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
use TYPO3\TypoScript\Exception;
use \Doctrine\ORM\Proxy\Proxy;

/**
 * A wrapper around a TYPO3 Flow cache which provides additional functionality for caching partial content (segments)
 * rendered by the TypoScript Runtime.
 *
 * The cache build process generally follows these steps:
 *
 *  - render the whole document as usual (for example a page) but insert special markers before and after the rendered segments
 *  - parse the rendered document and extract segments by the previously added markers
 *
 * This results in two artifacts:
 *
 *  - an array of content segments which are later stored as cache entries (if they may be cached)
 *  - a string called "output" which is the originally rendered output but without the markers
 *
 * We use non-visible ASCII characters as markers / tokens in order to minimize potential conflicts with the actual content.
 *
 * Note: If you choose a different cache backend for this content cache, make sure that it is one implementing
 *       TaggableBackendInterface.
 *
 * @Flow\Scope("singleton")
 */
class ContentCache_Original {

	const CACHE_SEGMENT_START_TOKEN = "\x02";
	const CACHE_SEGMENT_END_TOKEN = "\x03";
	const CACHE_SEGMENT_SEPARATOR_TOKEN = "\x1f";

	const CACHE_PLACEHOLDER_REGEX = "/\x02(?P<identifier>[a-f0-9]+)\x03/";
	const EVAL_PLACEHOLDER_REGEX = "/\x02(?P<command>[^\x02\x1f\x03]+)\x1f(?P<context>[^\x02\x1f\x03]+)\x03/";

	const MAXIMUM_NESTING_LEVEL = 32;

	/**
	 * A cache entry tag that will be used by default to flush an entry on "every" change - whatever that means to
	 * the application.
	 */
	const TAG_EVERYTHING = 'Everything';

	const SEGMENT_TYPE_CACHED = 'cached';
	const SEGMENT_TYPE_UNCACHED = 'uncached';

	/**
	 * @Flow\Inject
	 * @var CacheSegmentParser
	 */
	protected $parser;

	/**
	 * @var \TYPO3\Flow\Cache\Frontend\StringFrontend
	 * @Flow\Inject
	 */
	protected $cache;

	/**
	 * @var \TYPO3\Flow\Property\PropertyMapper
	 * @Flow\Inject
	 */
	protected $propertyMapper;

	/**
	 * Takes the given content and adds markers for later use as a cached content segment.
	 *
	 * This function will add a start and an end token to the beginning and end of the content and generate a cache
	 * identifier based on the current TypoScript path and additional values which were defined in the TypoScript
	 * configuration by the site integrator.
	 *
	 * The whole cache segment (START TOKEN + IDENTIFIER + SEPARATOR TOKEN + original content + END TOKEN) is returned
	 * as a string.
	 *
	 * This method is called by the TypoScript Runtime while rendering a TypoScript object.
	 *
	 * @param string $content The (partial) content which should potentially be cached later on
	 * @param string $typoScriptPath The TypoScript path that rendered the content, for example "page<TYPO3.Neos.NodeTypes:Page>/body<Acme.Demo:DefaultPageTemplate>/parts/breadcrumbMenu"
	 * @param array $cacheIdentifierValues The values (simple type or implementing CacheAwareInterface) that should be used to create a cache identifier, will be sorted by keys for consistent ordering
	 * @param array $tags Tags to add to the cache entry
	 * @param integer $lifetime Lifetime of the cache segment in seconds. NULL for the default lifetime and 0 for unlimited lifetime.
	 * @return string The original content, but with additional markers and a cache identifier added
	 */
	public function createCacheSegment($content, $typoScriptPath, $cacheIdentifierValues, array $tags = array(), $lifetime = NULL) {
		$cacheIdentifier = $this->renderContentCacheEntryIdentifier($typoScriptPath, $cacheIdentifierValues);
		$metadata = implode(',', $tags);
		if ($lifetime !== NULL) {
			$metadata .= ';' . $lifetime;
		}
		return self::CACHE_SEGMENT_START_TOKEN . $cacheIdentifier . self::CACHE_SEGMENT_SEPARATOR_TOKEN . $metadata . self::CACHE_SEGMENT_SEPARATOR_TOKEN . $content . self::CACHE_SEGMENT_END_TOKEN;
	}

	/**
	 * Similar to createCacheSegment() creates a content segment with markers added, but in contrast to that function
	 * this method is used for rendering a segment which is not supposed to be cached.
	 *
	 * This method is called by the TypoScript Runtime while rendering a TypoScript object.
	 *
	 * @param string $content The content rendered by the TypoScript Runtime
	 * @param string $typoScriptPath The TypoScript path that rendered the content, for example "page<TYPO3.Neos.NodeTypes:Page>/body<Acme.Demo:DefaultPageTemplate>/parts/breadcrumbMenu"
	 * @param array $contextVariables TypoScript context variables which are needed to correctly render the specified TypoScript object
	 * @return string The original content, but with additional markers added
	 */
	public function createUncachedSegment($content, $typoScriptPath, array $contextVariables) {
		$serializedContext = $this->serializeContext($contextVariables);
		return self::CACHE_SEGMENT_START_TOKEN . 'eval=' . $typoScriptPath . self::CACHE_SEGMENT_SEPARATOR_TOKEN . $serializedContext . self::CACHE_SEGMENT_SEPARATOR_TOKEN . $content . self::CACHE_SEGMENT_END_TOKEN;
	}

	/**
	 * Renders an identifier for a content cache entry
	 *
	 * @param string $typoScriptPath
	 * @param array $cacheIdentifierValues
	 * @return string An MD5 hash built from the typoScriptPath and certain elements of the given identifier values
	 * @throws \TYPO3\TypoScript\Exception\CacheException If an invalid entry identifier value is given
	 */
	protected function renderContentCacheEntryIdentifier($typoScriptPath, array $cacheIdentifierValues) {
		ksort($cacheIdentifierValues);

		$identifierSource = '';
		foreach ($cacheIdentifierValues as $key => $value) {
			if ($value instanceof CacheAwareInterface) {
				$identifierSource .= $key . '=' . $value->getCacheEntryIdentifier() . '&';
			} elseif (is_string($value) || is_bool($value) || is_integer($value)) {
				$identifierSource .= $key . '=' . $value . '&';
			} elseif ($value !== NULL) {
				throw new Exception\CacheException(sprintf('Invalid cache entry identifier @cache.entryIdentifier.%s for path "%s". A entry identifier value must be a string or implement CacheAwareInterface.', $key, $typoScriptPath), 1395846615);
			}
		}

		return md5($typoScriptPath . '@' . rtrim($identifierSource, '&'));
	}

	/**
	 * Takes a string of content which includes cache segment markers, extracts the marked segments, writes those
	 * segments which can be cached to the actual cache and returns the cleaned up original content without markers.
	 *
	 * This method is called by the TypoScript Runtime while rendering a TypoScript object.
	 *
	 * @param string $content The content with an outer cache segment
	 * @param boolean $storeCacheEntries Whether to store extracted cache segments in the cache
	 * @return string The (pure) content without cache segment markers
	 */
	public function processCacheSegments($content, $storeCacheEntries = TRUE) {
		$this->parser->extractRenderedSegments($content);

		if ($storeCacheEntries) {
			$segments = $this->parser->getCacheSegments();

			foreach ($segments as $segment) {
				$metadata = explode(';', $segment['metadata']);
				$tagsValue = $metadata[0] === '' ? array() : ($metadata[0] === '*' ? FALSE : explode(',', $metadata[0]));
					// FALSE means we do not need to store the cache entry again (because it was previously fetched)
				if ($tagsValue !== FALSE) {
					$lifetime = isset($metadata[1]) ? (integer)$metadata[1] : NULL;
					$this->cache->set($segment['identifier'], $segment['content'], $this->sanitizeTags($tagsValue), $lifetime);
				}
			}
		}

		return $this->parser->getOutput();
	}

	/**
	 * Tries to retrieve the specified content segment from the cache – further nested inline segments are retrieved
	 * as well and segments which were not cacheable are rendered.
	 *
	 * @param \Closure $uncachedCommandCallback A callback to process commands in uncached segments
	 * @param string $typoScriptPath TypoScript path identifying the TypoScript object to retrieve from the content cache
	 * @param array $cacheIdentifierValues Further values which play into the cache identifier hash, must be the same as the ones specified while the cache entry was written
	 * @param boolean $addCacheSegmentMarkersToPlaceholders If cache segment markers should be added – this makes sense if the cached segment is about to be included in a not-yet-cached segment
	 * @return string|boolean The segment with replaced cache placeholders, or FALSE if a segment was missing in the cache
	 * @throws \TYPO3\TypoScript\Exception
	 */
	public function getCachedSegment($uncachedCommandCallback, $typoScriptPath, $cacheIdentifierValues, $addCacheSegmentMarkersToPlaceholders = FALSE) {
		$cacheIdentifier = $this->renderContentCacheEntryIdentifier($typoScriptPath, $cacheIdentifierValues);
		$content = $this->cache->get($cacheIdentifier);

		if ($content === FALSE) {
			return FALSE;
		}

		$i = 0;
		do {
			$replaced = $this->replaceCachePlaceholders($content, $addCacheSegmentMarkersToPlaceholders);
			if ($replaced === FALSE) {
				return FALSE;
			}
			if ($i > self::MAXIMUM_NESTING_LEVEL) {
				throw new Exception('Maximum cache segment level reached', 1391873620);
			}
			$i++;
		} while ($replaced > 0);

		$this->replaceUncachedPlaceholders($uncachedCommandCallback, $content);

		if ($addCacheSegmentMarkersToPlaceholders) {
			return self::CACHE_SEGMENT_START_TOKEN . $cacheIdentifier . self::CACHE_SEGMENT_SEPARATOR_TOKEN . '*' . self::CACHE_SEGMENT_SEPARATOR_TOKEN . $content . self::CACHE_SEGMENT_END_TOKEN;
		} else {
			return $content;
		}
	}

	/**
	 * Find cache placeholders in a cached segment and return the identifiers
	 *
	 * @param string $content
	 * @param boolean $addCacheSegmentMarkersToPlaceholders
	 * @return integer|boolean Number of replaced placeholders or FALSE if a placeholder couldn't be found
	 */
	public function replaceCachePlaceholders(&$content, $addCacheSegmentMarkersToPlaceholders) {
		$cache = $this->cache;
		$foundMissingIdentifier = FALSE;
		$content = preg_replace_callback(self::CACHE_PLACEHOLDER_REGEX, function($match) use ($cache, &$foundMissingIdentifier, $addCacheSegmentMarkersToPlaceholders) {
			$identifier = $match['identifier'];
			$entry = $cache->get($identifier);
			if ($entry !== FALSE) {
				if ($addCacheSegmentMarkersToPlaceholders) {
					return ContentCache::CACHE_SEGMENT_START_TOKEN . $identifier . ContentCache::CACHE_SEGMENT_SEPARATOR_TOKEN . '*' . ContentCache::CACHE_SEGMENT_SEPARATOR_TOKEN . $entry . ContentCache::CACHE_SEGMENT_END_TOKEN;
				} else {
					return $entry;
				}
			} else {
				$foundMissingIdentifier = TRUE;
				return '';
			}
		}, $content, -1, $count);
		if ($foundMissingIdentifier)  {
			return FALSE;
		}
		return $count;
	}

	/**
	 * Replace segments which are marked as not-cacheable by their actual content by invoking the TypoScript Runtime.
	 *
	 * @param \Closure $uncachedCommandCallback
	 * @param string $content The content potentially containing not cacheable segments marked by the respective tokens
	 * @return string The original content, but with uncached segments replaced by the actual content
	 */
	protected function replaceUncachedPlaceholders(\Closure $uncachedCommandCallback, &$content) {
		$propertyMapper = $this->propertyMapper;
		$content = preg_replace_callback(self::EVAL_PLACEHOLDER_REGEX, function($match) use ($uncachedCommandCallback, $propertyMapper) {
			$command = $match['command'];
			$contextString = $match['context'];

			$unserializedContext = array();
			$serializedContextArray = json_decode($contextString, TRUE);
			foreach ($serializedContextArray as $variableName => $typeAndValue) {
				$value = $propertyMapper->convert($typeAndValue['value'], $typeAndValue['type']);
				$unserializedContext[$variableName] = $value;
			}

			return $uncachedCommandCallback($command, $unserializedContext);
		}, $content);
	}

	/**
	 * Generates a string from the given array of context variables
	 *
	 * @param array $contextVariables
	 * @return string
	 * @throws \InvalidArgumentException
	 */
	protected function serializeContext(array $contextVariables) {
		$serializedContextArray = array();
		foreach ($contextVariables as $variableName => $contextValue) {
			// TODO This relies on a converter being available from the context value type to string
			if ($contextValue !== NULL) {
				$serializedContextArray[$variableName]['type'] = $this->getTypeForContextValue($contextValue);
				$serializedContextArray[$variableName]['value'] = $this->propertyMapper->convert($contextValue, 'string');
			}
		}
		$serializedContext = json_encode($serializedContextArray);
		return $serializedContext;
	}

	/**
	 * TODO: Adapt to Flow change https://review.typo3.org/#/c/33138/
	 *
	 * @param mixed $contextValue
	 * @return string
	 */
	protected function getTypeForContextValue($contextValue) {
		if (is_object($contextValue)) {
			if ($contextValue instanceof Proxy) {
				$type = get_parent_class($contextValue);
			} else {
				$type = get_class($contextValue);
			}
		} else {
			$type = gettype($contextValue);
		}
		return $type;
	}

	/**
	 * Flush content cache entries by tag
	 *
	 * @param string $tag A tag value that was assigned to a cache entry in TypoScript, for example "Everything", "Node_[…]", "NodeType_[…]", "DescendantOf_[…]" whereas "…" is the node identifier or node type respectively
	 * @return integer The number of cache entries which actually have been flushed
	 */
	public function flushByTag($tag) {
		return $this->cache->flushByTag($this->sanitizeTag($tag));
	}

	/**
	 * Flush all content cache entries
	 *
	 * @return void
	 */
	public function flush() {
		$this->cache->flush();
	}

	/**
	 * Sanitizes the given tag for use with the cache framework
	 *
	 * @param string $tag A tag which possibly contains non-allowed characters, for example "NodeType_TYPO3.Neos.NodeTypes:Page"
	 * @return string A cleaned up tag, for example "NodeType_TYPO3_Neos-Page"
	 */
	protected function sanitizeTag($tag) {
		return strtr($tag, '.:', '_-');
	}

	/**
	 * Sanitizes multiple tags with sanitizeTag()
	 *
	 * @param array $tags Multiple tags
	 * @return array The sanitized tags
	 */
	protected function sanitizeTags(array $tags) {
		foreach ($tags as $key => $value) {
			$tags[$key] = $this->sanitizeTag($value);
		}
		return $tags;
	}
}
namespace TYPO3\TypoScript\Core\Cache;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * A wrapper around a TYPO3 Flow cache which provides additional functionality for caching partial content (segments)
 * rendered by the TypoScript Runtime.
 * 
 * The cache build process generally follows these steps:
 * 
 *  - render the whole document as usual (for example a page) but insert special markers before and after the rendered segments
 *  - parse the rendered document and extract segments by the previously added markers
 * 
 * This results in two artifacts:
 * 
 *  - an array of content segments which are later stored as cache entries (if they may be cached)
 *  - a string called "output" which is the originally rendered output but without the markers
 * 
 * We use non-visible ASCII characters as markers / tokens in order to minimize potential conflicts with the actual content.
 * 
 * Note: If you choose a different cache backend for this content cache, make sure that it is one implementing
 *       TaggableBackendInterface.
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class ContentCache extends ContentCache_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\TypoScript\Core\Cache\ContentCache') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\TypoScript\Core\Cache\ContentCache', $this);
		if ('TYPO3\TypoScript\Core\Cache\ContentCache' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\TypoScript\Core\Cache\ContentCache') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\TypoScript\Core\Cache\ContentCache', $this);

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
	$reflectedClass = new \ReflectionClass('TYPO3\TypoScript\Core\Cache\ContentCache');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\TypoScript\Core\Cache\ContentCache', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\TypoScript\Core\Cache\ContentCache', $propertyName, 'var');
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
		$cache_reference = &$this->cache;
		$this->cache = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('');
		if ($this->cache === NULL) {
			$this->cache = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('77bb857a5b21996cfe13e3a983c27992', $cache_reference);
			if ($this->cache === NULL) {
				$this->cache = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('77bb857a5b21996cfe13e3a983c27992',  $cache_reference, '', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Cache\CacheManager')->getCache('TYPO3_TypoScript_Content'); });
			}
		}
		$this->parser = new \TYPO3\TypoScript\Core\Cache\CacheSegmentParser();
		$propertyMapper_reference = &$this->propertyMapper;
		$this->propertyMapper = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Property\PropertyMapper');
		if ($this->propertyMapper === NULL) {
			$this->propertyMapper = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('d727d5722bb68256b2c0c712d1adda00', $propertyMapper_reference);
			if ($this->propertyMapper === NULL) {
				$this->propertyMapper = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('d727d5722bb68256b2c0c712d1adda00',  $propertyMapper_reference, 'TYPO3\Flow\Property\PropertyMapper', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Property\PropertyMapper'); });
			}
		}
$this->Flow_Injected_Properties = array (
  0 => 'cache',
  1 => 'parser',
  2 => 'propertyMapper',
);
	}
}
#