<?php 
namespace TYPO3\Flow\Resource;

/*                                                                        *
 * This script belongs to the TYPO3 Flow framework.                       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Error;
use TYPO3\Flow\Property\PropertyMappingConfigurationInterface;
use TYPO3\Flow\Property\TypeConverter\AbstractTypeConverter;
use TYPO3\Flow\Utility\Files;

/**
 * An type converter for converting strings, array and uploaded files to Resource objects.
 *
 * Has two big working modes:
 *
 * 1. File Uploads by PHP
 *
 *    In this case, the input array is expected to be a fresh file upload following the native PHP handling. The
 *    temporary upload file is then imported through the resource manager.
 *
 *    To enable the handling of files that have already been uploaded earlier, the special fields ['submittedFile'],
 *    ['submittedFile']['filename'] and ['submittedFile']['resourcePointer'] are checked. If set, they are used to
 *    fetch a file that has already been uploaded even if no file has been actually uploaded in the current request.
 *
 *
 * 2. Strings / arbitrary Arrays
 *
 *    If the source

 *    - is an array and contains the key '__identity'
 *
 *    the converter will find an existing resource with the given identity or continue and assign the given identity if
 *    CONFIGURATION_IDENTITY_CREATION_ALLOWED is set.
 *
 *    - is a string looking like a SHA1 (40 characters [0-9a-f]) or
 *    - is an array and contains the key 'hash' with a value looking like a SHA1 (40 characters [0-9a-f])
 *
 *    the converter will look up an existing Resource(Pointer) with that hash and return it if found. If that fails,
 *    the converter will try to import a file named like that hash from the configured CONFIGURATION_RESOURCE_LOAD_PATH.
 *
 *    If no hash is given in an array source but the key 'data' is set, the content of that key is assumed a binary string
 *    and a Resource representing this content is created and returned.
 *
 *    The imported Resource will be given a 'filename' if set in the source array in both cases (import from file or data).

 *
 * @Flow\Scope("singleton")
 */
class ResourceTypeConverter_Original extends AbstractTypeConverter {

	/**
	 * @var string
	 */
	const CONFIGURATION_RESOURCE_LOAD_PATH = 'resourceLoadPath';

	/**
	 * @var integer
	 */
	const CONFIGURATION_IDENTITY_CREATION_ALLOWED = 1;

	/**
	 * @var array<string>
	 */
	protected $sourceTypes = array('string', 'array');

	/**
	 * @var string
	 */
	protected $targetType = 'TYPO3\Flow\Resource\Resource';

	/**
	 * @var integer
	 */
	protected $priority = 1;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Resource\ResourceManager
	 */
	protected $resourceManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 */
	protected $persistenceManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Log\SystemLoggerInterface
	 */
	protected $systemLogger;

	/**
	 * @var array
	 */
	protected $convertedResources = array();

	/**
	 * Converts the given string or array to a Resource object.
	 *
	 * This method expects an array input and assumes the resource to be a
	 * fresh file upload and imports the temporary upload file through the
	 * resource manager.
	 *
	 * @param array $source The upload info (expected keys: error, name, tmp_name)
	 * @param string $targetType
	 * @param array $convertedChildProperties
	 * @param PropertyMappingConfigurationInterface $configuration
	 * @return Resource|Error if the input format is not supported or could not be converted for other reasons
	 */
	public function convertFrom($source, $targetType, array $convertedChildProperties = array(), PropertyMappingConfigurationInterface $configuration = NULL) {
		if (is_string($source)) {
			$source = array('hash' => $source);
		}

		// $source is ALWAYS an array at this point
		if (isset($source['error']) || isset($source['submittedFile'])) {
			return $this->handleFileUploads($source);
		} elseif (isset($source['hash']) || isset($source['data'])) {
			return $this->handleHashAndData($source, $configuration);
		}
	}

	/**
	 * @param array $source
	 * @return Resource|Error|NULL
	 */
	protected function handleFileUploads(array $source) {
		if (!isset($source['error']) || $source['error'] === \UPLOAD_ERR_NO_FILE) {
			if (isset($source['submittedFile']) && isset($source['submittedFile']['filename']) && isset($source['submittedFile']['resourcePointer'])) {
				$resourcePointer = $this->persistenceManager->getObjectByIdentifier($source['submittedFile']['resourcePointer'], 'TYPO3\Flow\Resource\ResourcePointer');
				if ($resourcePointer) {
					$resource = new Resource();
					$resource->setFilename($source['submittedFile']['filename']);
					$resource->setResourcePointer($resourcePointer);
					return $resource;
				}
			}
			return NULL;
		}

		if ($source['error'] !== \UPLOAD_ERR_OK) {
			switch ($source['error']) {
				case \UPLOAD_ERR_INI_SIZE:
				case \UPLOAD_ERR_FORM_SIZE:
				case \UPLOAD_ERR_PARTIAL:
					return new Error(Files::getUploadErrorMessage($source['error']), 1264440823);
				default:
					$this->systemLogger->log(sprintf('A server error occurred while converting an uploaded resource: "%s"', Files::getUploadErrorMessage($source['error'])), LOG_ERR);
					return new Error('An error occurred while uploading. Please try again or contact the administrator if the problem remains', 1340193849);
			}
		}

		if (isset($this->convertedResources[$source['tmp_name']])) {
			return $this->convertedResources[$source['tmp_name']];
		}

		$resource = $this->resourceManager->importUploadedResource($source);
		if ($resource === FALSE) {
			return new Error('The resource manager could not create a Resource instance.', 1264517906);
		} else {
			$this->convertedResources[$source['tmp_name']] = $resource;
			return $resource;
		}
	}

	/**
	 * @param array $source
	 * @param PropertyMappingConfigurationInterface $configuration
	 * @return Resource|Error
	 */
	protected function handleHashAndData(array $source, PropertyMappingConfigurationInterface $configuration = NULL) {
		$hash = NULL;
		$resource = FALSE;
		$givenResourceIdentity = NULL;

		if (isset($source['__identity'])) {
			$givenResourceIdentity = $source['__identity'];
			unset($source['__identity']);
			$resource = $this->persistenceManager->getObjectByIdentifier($givenResourceIdentity, 'TYPO3\Flow\Resource\Resource');
			if ($resource instanceof \TYPO3\Flow\Resource\Resource) {
				return $resource;
			}

			if ($configuration->getConfigurationValue('TYPO3\Flow\Resource\ResourceTypeConverter', self::CONFIGURATION_IDENTITY_CREATION_ALLOWED) !== TRUE) {
				throw new \TYPO3\Flow\Property\Exception\InvalidPropertyMappingConfigurationException('Creation of resource objects with identity not allowed. To enable this, you need to set the PropertyMappingConfiguration Value "CONFIGURATION_IDENTITY_CREATION_ALLOWED" to TRUE');
			}
		}

		if (isset($source['hash']) && preg_match('/[0-9a-f]{40}/', $source['hash'])) {
			$hash = $source['hash'];
		}

		if ($hash !== NULL) {
			$resourcePointer = $this->persistenceManager->getObjectByIdentifier($hash, 'TYPO3\Flow\Resource\ResourcePointer');
			if ($resourcePointer) {
				$resource = new Resource();
				$resource->setFilename($source['filename']);
				$resource->setResourcePointer($resourcePointer);
			}
		}

		if ($resource === NULL) {
			if (isset($source['data'])) {
				$resource = $this->resourceManager->createResourceFromContent($source['data'], $source['filename']);
			} elseif ($hash !== NULL) {
				$resource = $this->resourceManager->importResource($configuration->getConfigurationValue('TYPO3\Flow\Resource\ResourceTypeConverter', self::CONFIGURATION_RESOURCE_LOAD_PATH) . '/' . $hash);
				if (is_array($source) && isset($source['filename'])) {
					$resource->setFilename($source['filename']);
				}
			}
		}

		if ($resource instanceof \TYPO3\Flow\Resource\Resource) {
			if ($givenResourceIdentity !== NULL) {
				$this->setIdentity($resource, $givenResourceIdentity);
			}
			return $resource;
		} else {
			return new Error('The resource manager could not create a Resource instance.', 1404312901);
		}
	}

	/**
	 * Set the given $identity on the created $object.
	 *
	 * @param object $object
	 * @param string|array $identity
	 * @return void
	 * @todo set identity properly if it is composite or custom property
	 */
	protected function setIdentity($object, $identity) {
		\TYPO3\Flow\Reflection\ObjectAccess::setProperty($object, 'Persistence_Object_Identifier', $identity, TRUE);
	}

}
namespace TYPO3\Flow\Resource;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * An type converter for converting strings, array and uploaded files to Resource objects.
 * 
 * Has two big working modes:
 * 
 * 1. File Uploads by PHP
 * 
 *    In this case, the input array is expected to be a fresh file upload following the native PHP handling. The
 *    temporary upload file is then imported through the resource manager.
 * 
 *    To enable the handling of files that have already been uploaded earlier, the special fields ['submittedFile'],
 *    ['submittedFile']['filename'] and ['submittedFile']['resourcePointer'] are checked. If set, they are used to
 *    fetch a file that has already been uploaded even if no file has been actually uploaded in the current request.
 * 
 * 
 * 2. Strings / arbitrary Arrays
 * 
 *    If the source
 * 
 *    - is an array and contains the key '__identity'
 * 
 *    the converter will find an existing resource with the given identity or continue and assign the given identity if
 *    CONFIGURATION_IDENTITY_CREATION_ALLOWED is set.
 * 
 *    - is a string looking like a SHA1 (40 characters [0-9a-f]) or
 *    - is an array and contains the key 'hash' with a value looking like a SHA1 (40 characters [0-9a-f])
 * 
 *    the converter will look up an existing Resource(Pointer) with that hash and return it if found. If that fails,
 *    the converter will try to import a file named like that hash from the configured CONFIGURATION_RESOURCE_LOAD_PATH.
 * 
 *    If no hash is given in an array source but the key 'data' is set, the content of that key is assumed a binary string
 *    and a Resource representing this content is created and returned.
 * 
 *    The imported Resource will be given a 'filename' if set in the source array in both cases (import from file or data).
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class ResourceTypeConverter extends ResourceTypeConverter_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\Flow\Resource\ResourceTypeConverter') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Flow\Resource\ResourceTypeConverter', $this);
		if ('TYPO3\Flow\Resource\ResourceTypeConverter' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\Flow\Resource\ResourceTypeConverter') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Flow\Resource\ResourceTypeConverter', $this);

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
	$reflectedClass = new \ReflectionClass('TYPO3\Flow\Resource\ResourceTypeConverter');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Flow\Resource\ResourceTypeConverter', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Flow\Resource\ResourceTypeConverter', $propertyName, 'var');
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
		$resourceManager_reference = &$this->resourceManager;
		$this->resourceManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Resource\ResourceManager');
		if ($this->resourceManager === NULL) {
			$this->resourceManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('3b3239258e396ed88334e6f7199a1678', $resourceManager_reference);
			if ($this->resourceManager === NULL) {
				$this->resourceManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('3b3239258e396ed88334e6f7199a1678',  $resourceManager_reference, 'TYPO3\Flow\Resource\ResourceManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Resource\ResourceManager'); });
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
		$systemLogger_reference = &$this->systemLogger;
		$this->systemLogger = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Log\SystemLoggerInterface');
		if ($this->systemLogger === NULL) {
			$this->systemLogger = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('6d57d95a1c3cd7528e3e6ea15012dac8', $systemLogger_reference);
			if ($this->systemLogger === NULL) {
				$this->systemLogger = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('6d57d95a1c3cd7528e3e6ea15012dac8',  $systemLogger_reference, 'TYPO3\Flow\Log\SystemLoggerInterface', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Log\SystemLoggerInterface'); });
			}
		}
$this->Flow_Injected_Properties = array (
  0 => 'resourceManager',
  1 => 'persistenceManager',
  2 => 'systemLogger',
);
	}
}
#