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

use TYPO3\TypoScript\Exception;

/**
 * A parser which extracts cache segments by searching for start and end markers in the given content.
 */
class CacheSegmentParser_Original {

	/**
	 * @var string
	 */
	protected $output;

	/**
	 * @var array
	 */
	protected $cacheEntries;

	/**
	 * Parses the given content and extracts segments by searching for start end end markers. Those segments can later
	 * be retrieved via getCacheSegments() and stored in a cache.
	 *
	 * This method also prepares a cleaned up output which can be retrieved later. See getOutput() for more information.
	 *
	 * @param string $content The content to process, ie. the rendered content with some segment markers already in place
	 * @return string The outer content with placeholders instead of the actual content segments
	 * @throws \TYPO3\TypoScript\Exception
	 */
	public function extractRenderedSegments($content) {
		$this->output = '';
		$this->cacheEntries = array();
		$parts = array(array('content' => ''));

		$currentPosition = 0;
		$level = 0;
		$nextStartPosition = strpos($content, ContentCache::CACHE_SEGMENT_START_TOKEN, $currentPosition);
		$nextEndPosition = strpos($content, ContentCache::CACHE_SEGMENT_END_TOKEN, $currentPosition);

		while (TRUE) {

			// Nothing else to do, all segments are parsed
			if ($nextStartPosition === FALSE && $nextEndPosition === FALSE) {
				$part = substr($content, $currentPosition);
				$parts[0]['content'] .= $part;
				$this->output .= $part;
				break;
			}

			// A cache segment is started and no end token can be found
			if ($nextStartPosition !== FALSE && $nextEndPosition === FALSE) {
				throw new Exception(sprintf('No cache segment end token can be found after position %d', $currentPosition), 1391853500);
			}

			if ($level === 0 && $nextEndPosition !== FALSE && ($nextStartPosition === FALSE || $nextEndPosition < $nextStartPosition)) {
				throw new Exception(sprintf('Exceeding segment end token after position %d', $currentPosition), 1391853689);
			}

			// Either no other segment start was found or we encountered an segment end before the next start
			if ($nextStartPosition === FALSE || $nextEndPosition < $nextStartPosition) {

				// Add everything until end to current level
				$part = substr($content, $currentPosition, $nextEndPosition - $currentPosition);
				$parts[$level]['content'] .= $part;
				$currentLevelPart = &$parts[$level];
				$identifier = $currentLevelPart['identifier'];
				$this->output .= $part;

				if ($currentLevelPart['type'] === ContentCache::SEGMENT_TYPE_CACHED) {
					$this->cacheEntries[$identifier] = $parts[$level];
				}

				// The end marker ends the current level
				unset($parts[$level]);
				$level--;

				if ($currentLevelPart['type'] === ContentCache::SEGMENT_TYPE_UNCACHED) {
					$parts[$level]['content'] .= ContentCache::CACHE_SEGMENT_START_TOKEN . $identifier . ContentCache::CACHE_SEGMENT_SEPARATOR_TOKEN . $currentLevelPart['context'] . ContentCache::CACHE_SEGMENT_END_TOKEN;
				} else {
					$parts[$level]['content'] .= ContentCache::CACHE_SEGMENT_START_TOKEN . $identifier . ContentCache::CACHE_SEGMENT_END_TOKEN;
				}

				$currentPosition = $nextEndPosition + 1;

				$nextEndPosition = strpos($content, ContentCache::CACHE_SEGMENT_END_TOKEN, $currentPosition);
			} else {

				// Push everything until now to the current stack value
				$part = substr($content, $currentPosition, $nextStartPosition - $currentPosition);
				$parts[$level]['content'] .= $part;
				$this->output .= $part;

				// Found opening marker, increase level
				$level++;
				$parts[$level] = array('content' => '');

				$currentPosition = $nextStartPosition + 1;

				$nextStartPosition = strpos($content, ContentCache::CACHE_SEGMENT_START_TOKEN, $currentPosition);

				$nextIdentifierSeparatorPosition = strpos($content, ContentCache::CACHE_SEGMENT_SEPARATOR_TOKEN, $currentPosition);
				$nextSecondIdentifierSeparatorPosition = strpos($content, ContentCache::CACHE_SEGMENT_SEPARATOR_TOKEN, $nextIdentifierSeparatorPosition + 1);

				if ($nextIdentifierSeparatorPosition === FALSE || $nextSecondIdentifierSeparatorPosition === FALSE
					|| $nextStartPosition !== FALSE && $nextStartPosition < $nextIdentifierSeparatorPosition
					|| $nextEndPosition !== FALSE && $nextEndPosition < $nextIdentifierSeparatorPosition
					|| $nextStartPosition !== FALSE && $nextStartPosition < $nextSecondIdentifierSeparatorPosition
					|| $nextEndPosition !== FALSE && $nextEndPosition < $nextSecondIdentifierSeparatorPosition) {
					throw new Exception(sprintf('Missing segment separator token after position %d', $currentPosition), 1391855139);
				}

				$identifier = substr($content, $currentPosition, $nextIdentifierSeparatorPosition - $currentPosition);
				$contextOrMetadata = substr($content, $nextIdentifierSeparatorPosition + 1, $nextSecondIdentifierSeparatorPosition - $nextIdentifierSeparatorPosition - 1);

				$parts[$level]['identifier'] = $identifier;
				if (strpos($identifier, 'eval=') === 0) {
					$parts[$level]['type'] = ContentCache::SEGMENT_TYPE_UNCACHED;
					$parts[$level]['context'] = $contextOrMetadata;
				} else {
					$parts[$level]['type'] = ContentCache::SEGMENT_TYPE_CACHED;
					$parts[$level]['metadata'] = $contextOrMetadata;
				}

				$currentPosition = $nextSecondIdentifierSeparatorPosition + 1;

				$nextStartPosition = strpos($content, ContentCache::CACHE_SEGMENT_START_TOKEN, $currentPosition);
			}
		};

		return $parts[0]['content'];
	}

	/**
	 * Returns the fully intact content as originally given to extractRenderedSegments() but without the markers. This
	 * content is suitable for being used as output for the user.
	 *
	 * @return string
	 */
	public function getOutput() {
		return $this->output;
	}

	/**
	 * Returns an array with extracted content segments, including the type (if they can be cached or not) and tags to
	 * be used for their entries when the segments are stored in a persistent cache.
	 *
	 * @return array
	 */
	public function getCacheSegments() {
		return $this->cacheEntries;
	}

}
namespace TYPO3\TypoScript\Core\Cache;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * A parser which extracts cache segments by searching for start and end markers in the given content.
 */
class CacheSegmentParser extends CacheSegmentParser_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


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
			}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __sleep() {
		$result = NULL;
		$this->Flow_Object_PropertiesToSerialize = array();
	$reflectionService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Reflection\ReflectionService');
	$reflectedClass = new \ReflectionClass('TYPO3\TypoScript\Core\Cache\CacheSegmentParser');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\TypoScript\Core\Cache\CacheSegmentParser', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\TypoScript\Core\Cache\CacheSegmentParser', $propertyName, 'var');
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