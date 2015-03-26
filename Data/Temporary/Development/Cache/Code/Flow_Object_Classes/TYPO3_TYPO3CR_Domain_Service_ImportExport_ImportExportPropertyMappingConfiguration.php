<?php 
namespace TYPO3\TYPO3CR\Domain\Service\ImportExport;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.TYPO3CR".         *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */


use TYPO3\Flow\Property\PropertyMappingConfigurationInterface;
use TYPO3\Flow\Property\TypeConverter\ArrayConverter;
use TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter;
use TYPO3\Flow\Property\TypeConverter\StringConverter;
use TYPO3\Flow\Resource\ResourceTypeConverter;

/**
 * Property mapping configuration which is used for import / export:
 *
 * - works for all levels of the PropertyMapping (recursively)
 * - sets the correct export and import configuration for the type converters
 */
class ImportExportPropertyMappingConfiguration_Original implements PropertyMappingConfigurationInterface {

	/**
	 * @var string the resource-load-save-path, or NULL if it does not exist.
	 */
	protected $resourceLoadSavePath;

	/**
	 * @param $resourceLoadSavePath
	 */
	public function __construct($resourceLoadSavePath) {
		$this->resourceLoadSavePath = $resourceLoadSavePath;
	}

	/**
	 * The sub-configuration to be used is the current one.
	 *
	 * @param string $propertyName
	 * @return PropertyMappingConfigurationInterface the property mapping configuration for the given $propertyName.
	 * @api
	 */
	public function getConfigurationFor($propertyName) {
		return $this;
	}

	/**
	 * @param string $typeConverterClassName
	 * @param string $key
	 * @return mixed configuration value for the specific $typeConverterClassName. Can be used by Type Converters to fetch converter-specific configuration
	 * @api
	 */
	public function getConfigurationValue($typeConverterClassName, $key) {
		// needed in EXPORT
		if ($typeConverterClassName === 'TYPO3\Flow\Property\TypeConverter\StringConverter' && $key === StringConverter::CONFIGURATION_ARRAY_FORMAT) {
			return StringConverter::ARRAY_FORMAT_JSON;
		}

		if ($typeConverterClassName === 'TYPO3\Flow\Property\TypeConverter\ArrayConverter' && $key === ArrayConverter::CONFIGURATION_RESOURCE_EXPORT_TYPE) {
			return ArrayConverter::RESOURCE_EXPORT_TYPE_FILE;
		}

		if ($typeConverterClassName === 'TYPO3\Flow\Property\TypeConverter\ArrayConverter' && $key === ArrayConverter::CONFIGURATION_RESOURCE_SAVE_PATH) {
			return $this->resourceLoadSavePath;
		}


		// needed in IMPORT
		if ($typeConverterClassName === 'TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter' && $key === PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED) {
			return TRUE;
		}

		if ($typeConverterClassName === 'TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter' && $key === PersistentObjectConverter::CONFIGURATION_IDENTITY_CREATION_ALLOWED) {
			return TRUE;
		}

		if ($typeConverterClassName === 'TYPO3\Flow\Property\TypeConverter\ArrayConverter' && $key === ArrayConverter::CONFIGURATION_STRING_FORMAT) {
			return ArrayConverter::STRING_FORMAT_JSON;
		}

		if ($typeConverterClassName === 'TYPO3\Flow\Resource\ResourceTypeConverter' && $key === ResourceTypeConverter::CONFIGURATION_IDENTITY_CREATION_ALLOWED) {
			return TRUE;
		}

		if ($typeConverterClassName === 'TYPO3\Flow\Resource\ResourceTypeConverter' && $key === ResourceTypeConverter::CONFIGURATION_RESOURCE_LOAD_PATH) {
			return $this->resourceLoadSavePath;
		}

		// fallback
		return NULL;
	}


	// starting from here, we just implement the interface in the "default" way without modifying things
	/**
	 * @param string $propertyName
	 * @return boolean TRUE if the given propertyName should be mapped, FALSE otherwise.
	 * @api
	 */
	public function shouldMap($propertyName) {
		return TRUE;
	}

	/**
	 * Check if the given $propertyName should be skipped during mapping.
	 *
	 * @param string $propertyName
	 * @return boolean
	 * @api
	 */
	public function shouldSkip($propertyName) {
		return FALSE;
	}

	/**
	 * Whether unknown (unconfigured) properties should be skipped during
	 * mapping, instead if causing an error.
	 *
	 * @return boolean
	 * @api
	 */
	public function shouldSkipUnknownProperties() {
		return FALSE;
	}

	/**
	 * Maps the given $sourcePropertyName to a target property name.
	 * Can be used to rename properties from source to target.
	 *
	 * @param string $sourcePropertyName
	 * @return string property name of target
	 * @api
	 */
	public function getTargetPropertyName($sourcePropertyName) {
		return $sourcePropertyName;
	}

	/**
	 * This method can be used to explicitely force a TypeConverter to be used for this Configuration.
	 *
	 * @return \TYPO3\Flow\Property\TypeConverterInterface The type converter to be used for this particular PropertyMappingConfiguration, or NULL if the system-wide configured type converter should be used.
	 * @api
	 */
	public function getTypeConverter() {
		return NULL;
	}
}namespace TYPO3\TYPO3CR\Domain\Service\ImportExport;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * Property mapping configuration which is used for import / export:
 * 
 * - works for all levels of the PropertyMapping (recursively)
 * - sets the correct export and import configuration for the type converters
 */
class ImportExportPropertyMappingConfiguration extends ImportExportPropertyMappingConfiguration_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 * @param $resourceLoadSavePath
	 */
	public function __construct() {
		$arguments = func_get_args();

		if (!array_key_exists(0, $arguments)) $arguments[0] = NULL;
		if (!array_key_exists(0, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $resourceLoadSavePath in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		call_user_func_array('parent::__construct', $arguments);
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
			}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __sleep() {
		$result = NULL;
		$this->Flow_Object_PropertiesToSerialize = array();
	$reflectionService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Reflection\ReflectionService');
	$reflectedClass = new \ReflectionClass('TYPO3\TYPO3CR\Domain\Service\ImportExport\ImportExportPropertyMappingConfiguration');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\TYPO3CR\Domain\Service\ImportExport\ImportExportPropertyMappingConfiguration', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\TYPO3CR\Domain\Service\ImportExport\ImportExportPropertyMappingConfiguration', $propertyName, 'var');
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