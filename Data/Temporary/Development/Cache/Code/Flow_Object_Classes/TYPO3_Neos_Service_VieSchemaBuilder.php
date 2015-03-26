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
use TYPO3\TYPO3CR\Domain\Model\NodeType;

/**
 * Generate a schema in JSON format for the VIE dataTypes validation, necessary
 * when using nodes as semantic types.
 *
 * Example schema: http://schema.rdfs.org/all.json
 *
 * @Flow\Scope("singleton")
 */
class VieSchemaBuilder_Original {

	/**
	 * @var \TYPO3\TYPO3CR\Domain\Service\NodeTypeManager
	 * @Flow\Inject
	 */
	protected $nodeTypeManager;

	/**
	 * @var array
	 */
	protected $properties = array();

	/**
	 * @var array
	 */
	protected $types = array();

	/**
	 * @var object
	 */
	protected $configuration;

	/**
	 * @var array
	 */
	protected $superTypeConfiguration = array();

	/**
	 * Converts the nodes types to a fully structured array
	 * in the same structure as the schema to be created.
	 *
	 * The schema also includes abstract node types for the full inheritance information in VIE.
	 *
	 * @return object
	 */
	public function generateVieSchema() {
		if ($this->configuration !== NULL) {
			return $this->configuration;
		}

		$nodeTypes = $this->nodeTypeManager->getNodeTypes();
		foreach ($nodeTypes as $nodeTypeName => $nodeType) {
			$this->readNodeTypeConfiguration($nodeTypeName, $nodeType);
		}

		unset($this->types['typo3:unstructured']);

		foreach ($this->types as $nodeTypeName => $nodeTypeDefinition) {
			$this->types[$nodeTypeName]->subtypes = $this->getAllSubtypes($nodeTypeName);
			$this->types[$nodeTypeName]->ancestors = $this->getAllAncestors($nodeTypeName);

			$this->removeUndeclaredTypes($this->types[$nodeTypeName]->supertypes);
			$this->removeUndeclaredTypes($this->types[$nodeTypeName]->ancestors);
		}

		foreach ($this->properties as $property => $propertyConfiguration) {
			if (isset($propertyConfiguration->domains) && is_array($propertyConfiguration->domains)) {
				foreach ($propertyConfiguration->domains as $domain) {
					if (preg_match('/TYPO3\.Neos\.NodeTypes:.*Column/', $domain)) {
						$this->properties[$property]->ranges = array_keys($this->types);
					}
				}
			}
		}

			// Convert the TYPO3.Neos:ContentCollection element to support content-collection
			// TODO Move to node type definition
		if (isset($this->types['typo3:TYPO3.Neos:ContentCollection'])) {
			$this->addProperty('typo3:TYPO3.Neos:ContentCollection', 'typo3:content-collection', array());
			$this->types['typo3:TYPO3.Neos:ContentCollection']->specific_properties[] = 'typo3:content-collection';
			$this->properties['typo3:content-collection']->ranges = array_keys($this->types);
		}

		$this->configuration = (object) array(
			'types' => (object) $this->types,
			'properties' => (object) $this->properties,
		);
		return $this->configuration;
	}

	/**
	 * @param string $nodeTypeName
	 * @param \TYPO3\TYPO3CR\Domain\Model\NodeType $nodeType
	 * @return void
	 */
	protected function readNodeTypeConfiguration($nodeTypeName, $nodeType) {
		$nodeTypeConfiguration = $nodeType->getFullConfiguration();
		$this->superTypeConfiguration['typo3:' . $nodeTypeName] = array();
		if (isset($nodeTypeConfiguration['superTypes']) && is_array($nodeTypeConfiguration['superTypes'])) {
			foreach ($nodeTypeConfiguration['superTypes'] as $superType) {
				$this->superTypeConfiguration['typo3:' . $nodeTypeName][] = 'typo3:' . $superType;
			}
		}

		$nodeTypeProperties = array();

		if (isset($nodeTypeConfiguration['properties'])) {
			foreach ($nodeTypeConfiguration['properties'] as $property => $propertyConfiguration) {
				// TODO Make sure we can configure the range for all multi column elements to define what types a column may contain
				$this->addProperty('typo3:' . $nodeTypeName, 'typo3:' . $property, $propertyConfiguration);
				$nodeTypeProperties[] = 'typo3:' . $property;
			}
		}

		$metadata = array();
		$metaDataPropertyIndexes = array('ui');
		foreach ($metaDataPropertyIndexes as $propertyName) {
			if (isset($nodeTypeConfiguration[$propertyName])) {
				$metadata[$propertyName] = $nodeTypeConfiguration[$propertyName];
			}
		}
		if ($nodeType->isAbstract()) {
			$metadata['abstract'] = TRUE;
		}

		$this->types['typo3:' . $nodeTypeName] = (object) array(
			'label' => isset($nodeTypeConfiguration['ui']['label']) ? $nodeTypeConfiguration['ui']['label'] : $nodeTypeName,
			'id' => 'typo3:' . $nodeTypeName,
			'properties' => array(),
			'specific_properties' => $nodeTypeProperties,
			'subtypes' => array(),
			'metadata' => (object)$metadata,
			'supertypes' => $this->superTypeConfiguration['typo3:' . $nodeTypeName],
			'url' => 'http://www.typo3.org/ns/2012/Flow/Packages/Neos/Content/',
			'ancestors' => array(),
			'comment' => '',
			'comment_plain' => ''
		);
	}

	/**
	 * Adds a property to the list of known properties
	 *
	 * @param string $nodeType
	 * @param string $propertyName
	 * @param array $propertyConfiguration
	 * @return void
	 */
	protected function addProperty($nodeType, $propertyName, array $propertyConfiguration) {
		if (isset($this->properties[$propertyName])) {
			$this->properties[$propertyName]->domains[] = $nodeType;
		} else {
			$propertyLabel = isset($propertyConfiguration['ui']['label']) ? $propertyConfiguration['ui']['label'] : $propertyName;
			$this->properties[$propertyName] = (object) array(
				'comment' => $propertyLabel,
				'comment_plain' => $propertyLabel,
				'domains' => array($nodeType),
				'id' => $propertyName,
				'label' => $propertyName,
				'ranges' => array(),
				'min' => 0,
				'max' => -1
			);
		}
	}

	/**
	 * Cleans up all types which are not know in given configuration array
	 *
	 * @param array $configuration
	 * @return void
	 */
	protected function removeUndeclaredTypes(array &$configuration) {
		foreach ($configuration as $index => $type) {
			if (!isset($this->types[$type])) {
				unset($configuration[$index]);
			}
		}
	}

	/**
	 * Return all sub node types of a node type (recursively)
	 *
	 * @param string $type
	 * @return array
	 */
	protected function getAllSubtypes($type) {
		$subTypes = array();

		foreach ($this->superTypeConfiguration as $nodeType => $superTypes) {
			if (in_array($type, $superTypes)) {
				if (isset($this->types[$nodeType])) {
					$subTypes[] = $nodeType;

					$nodeTypeSubTypes = $this->getAllSubtypes($nodeType);
					foreach ($nodeTypeSubTypes as $nodeTypeSubType) {
						if (!in_array($nodeTypeSubType, $subTypes)) {
							$subTypes[] = $nodeTypeSubType;
						}
					}
				}
			}
		}

		return $subTypes;
	}

	/**
	 * Return all ancestors of a node type
	 *
	 * @param string $type
	 * @return array
	 */
	protected function getAllAncestors($type) {
		if (!isset($this->superTypeConfiguration[$type])) {
			return array();
		}
		$ancestors = $this->superTypeConfiguration[$type];

		foreach ($this->superTypeConfiguration[$type] as $currentSuperType) {
			if (isset($this->types[$currentSuperType])) {
				$currentSuperTypeAncestors = $this->getAllAncestors($currentSuperType);
				$ancestors = array_merge($ancestors, $currentSuperTypeAncestors);
			}
		}

		return $ancestors;
	}

}
namespace TYPO3\Neos\Service;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * Generate a schema in JSON format for the VIE dataTypes validation, necessary
 * when using nodes as semantic types.
 * 
 * Example schema: http://schema.rdfs.org/all.json
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class VieSchemaBuilder extends VieSchemaBuilder_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\Neos\Service\VieSchemaBuilder') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Service\VieSchemaBuilder', $this);
		if ('TYPO3\Neos\Service\VieSchemaBuilder' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\Neos\Service\VieSchemaBuilder') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Service\VieSchemaBuilder', $this);

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
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\Service\VieSchemaBuilder');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\Service\VieSchemaBuilder', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\Service\VieSchemaBuilder', $propertyName, 'var');
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
		$nodeTypeManager_reference = &$this->nodeTypeManager;
		$this->nodeTypeManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Service\NodeTypeManager');
		if ($this->nodeTypeManager === NULL) {
			$this->nodeTypeManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('478a517efacb3d47415a96d9caded2e9', $nodeTypeManager_reference);
			if ($this->nodeTypeManager === NULL) {
				$this->nodeTypeManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('478a517efacb3d47415a96d9caded2e9',  $nodeTypeManager_reference, 'TYPO3\TYPO3CR\Domain\Service\NodeTypeManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Service\NodeTypeManager'); });
			}
		}
$this->Flow_Injected_Properties = array (
  0 => 'nodeTypeManager',
);
	}
}
#