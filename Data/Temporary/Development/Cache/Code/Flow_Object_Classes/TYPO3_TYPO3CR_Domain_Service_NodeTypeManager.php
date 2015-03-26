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
use TYPO3\Flow\Configuration\ConfigurationManager;
use TYPO3\TYPO3CR\Domain\Model\NodeType;
use TYPO3\TYPO3CR\Exception\NodeTypeNotFoundException;

/**
 * Manager for node types
 *
 * @Flow\Scope("singleton")
 * @api
 */
class NodeTypeManager_Original {

	/**
	 * Node types, indexed by name
	 *
	 * @var array
	 */
	protected $cachedNodeTypes = array();

	/**
	 * @Flow\Inject
	 * @var ConfigurationManager
	 */
	protected $configurationManager;

	/**
	 * Return all registered node types.
	 *
	 * @param boolean $includeAbstractNodeTypes Whether to include abstract node types, defaults to TRUE
	 * @return array<NodeType> All node types registered in the system, indexed by node type name
	 * @api
	 */
	public function getNodeTypes($includeAbstractNodeTypes = TRUE) {
		if ($this->cachedNodeTypes === array()) {
			$this->loadNodeTypes();
		}
		if ($includeAbstractNodeTypes) {
			return $this->cachedNodeTypes;
		} else {
			$nonAbstractNodeTypes = array();
			foreach ($this->cachedNodeTypes as $nodeTypeName => $nodeType) {
				if (!$nodeType->isAbstract()) {
					$nonAbstractNodeTypes[$nodeTypeName] = $nodeType;
				}
			}
			return $nonAbstractNodeTypes;
		}
	}

	/**
	 * Return all non-abstract node types which have a certain $superType, without
	 * the $superType itself.
	 *
	 * @param string $superTypeName
	 * @param boolean $includeAbstractNodeTypes Whether to include abstract node types, defaults to TRUE
	 * @return array<NodeType> Sub node types of the given super type, indexed by node type name
	 * @api
	 */
	public function getSubNodeTypes($superTypeName, $includeAbstractNodeTypes = TRUE) {
		if ($this->cachedNodeTypes === array()) {
			$this->loadNodeTypes();
		}

		$filteredNodeTypes = array();
		/** @var NodeType $nodeType */
		foreach ($this->cachedNodeTypes as $nodeTypeName => $nodeType) {
			if ($includeAbstractNodeTypes === FALSE && $nodeType->isAbstract()) {
				continue;
			}
			if ($nodeType->isOfType($superTypeName) && $nodeTypeName !== $superTypeName) {
				$filteredNodeTypes[$nodeTypeName] = $nodeType;
			}
		}
		return $filteredNodeTypes;
	}

	/**
	 * Returns the specified node type (which could be abstract)
	 *
	 * @param string $nodeTypeName
	 * @return NodeType or NULL
	 * @throws NodeTypeNotFoundException
	 * @api
	 */
	public function getNodeType($nodeTypeName) {
		if ($this->cachedNodeTypes === array()) {
			$this->loadNodeTypes();
		}
		if (!isset($this->cachedNodeTypes[$nodeTypeName])) {
			throw new NodeTypeNotFoundException('The node type "' . $nodeTypeName . '" is not available.', 1316598370);
		}
		return $this->cachedNodeTypes[$nodeTypeName];
	}

	/**
	 * Checks if the specified node type exists
	 *
	 * @param string $nodeTypeName Name of the node type
	 * @return boolean TRUE if it exists, otherwise FALSE
	 * @api
	 */
	public function hasNodeType($nodeTypeName) {
		if ($this->cachedNodeTypes === array()) {
			$this->loadNodeTypes();
		}
		return isset($this->cachedNodeTypes[$nodeTypeName]);
	}

	/**
	 * Creates a new node type
	 *
	 * @param string $nodeTypeName Unique name of the new node type. Example: "TYPO3.Neos:Page"
	 * @return NodeType
	 * @throws \TYPO3\TYPO3CR\Exception
	 */
	public function createNodeType($nodeTypeName) {
		throw new \TYPO3\TYPO3CR\Exception('Creation of node types not supported so far; tried to create "' . $nodeTypeName . '".', 1316449432);
	}

	/**
	 * Loads all node types into memory.
	 *
	 * @return void
	 */
	protected function loadNodeTypes() {
		$completeNodeTypeConfiguration = $this->configurationManager->getConfiguration('NodeTypes');
		foreach (array_keys($completeNodeTypeConfiguration) as $nodeTypeName) {
			$this->loadNodeType($nodeTypeName, $completeNodeTypeConfiguration);
		}
	}

	/**
	 * This method can be used by Functional of Behavioral Tests to completely
	 * override the node types known in the system.
	 *
	 * In order to reset the node type override, an empty array can be passed in.
	 * In this case, the system-node-types are used again.
	 *
	 * @param array $completeNodeTypeConfiguration
	 * @return void
	 */
	public function overrideNodeTypes(array $completeNodeTypeConfiguration) {
		$this->cachedNodeTypes = array();
		foreach (array_keys($completeNodeTypeConfiguration) as $nodeTypeName) {
			$this->loadNodeType($nodeTypeName, $completeNodeTypeConfiguration);
		}
	}

	/**
	 * Load one node type, if it is not loaded yet.
	 *
	 * @param string $nodeTypeName
	 * @param array $completeNodeTypeConfiguration the full node type configuration for all node types
	 * @return NodeType
	 * @throws \TYPO3\TYPO3CR\Exception
	 */
	protected function loadNodeType($nodeTypeName, array $completeNodeTypeConfiguration) {
		if (isset($this->cachedNodeTypes[$nodeTypeName])) {
			return $this->cachedNodeTypes[$nodeTypeName];
		}

		if (!isset($completeNodeTypeConfiguration[$nodeTypeName])) {
			throw new \TYPO3\TYPO3CR\Exception('Node type "' . $nodeTypeName . '" does not exist', 1316451800);
		}

		$nodeTypeConfiguration = $completeNodeTypeConfiguration[$nodeTypeName];

		$mergedConfiguration = array();
		$superTypes = array();
		if (isset($nodeTypeConfiguration['superTypes'])) {
			foreach ($nodeTypeConfiguration['superTypes'] as $superTypeName) {
				$superType = $this->loadNodeType($superTypeName, $completeNodeTypeConfiguration);
				if ($superType->isFinal() === TRUE) {
					throw new \TYPO3\TYPO3CR\Exception\NodeTypeIsFinalException('Node type "' . $nodeTypeName . '" has a supertype "' . $superType->getName() . '" which is final.', 1316452423);
				}
				$superTypes[] = $superType;
				$mergedConfiguration = \TYPO3\Flow\Utility\Arrays::arrayMergeRecursiveOverrule($mergedConfiguration, $superType->getFullConfiguration());
			}
			unset($mergedConfiguration['superTypes']);
		}
		$mergedConfiguration = \TYPO3\Flow\Utility\Arrays::arrayMergeRecursiveOverrule($mergedConfiguration, $nodeTypeConfiguration);

		// Remove unset properties
		if (isset($mergedConfiguration['properties'])) {
			foreach ($mergedConfiguration['properties'] as $propertyName => $propertyConfiguration) {
				if ($propertyConfiguration === NULL) {
					unset($mergedConfiguration['properties'][$propertyName]);
				}
			}
			if ($mergedConfiguration['properties'] === array()) {
				unset($mergedConfiguration['properties']);
			}
		}

		$nodeType = new NodeType($nodeTypeName, $superTypes, $mergedConfiguration);

		$this->cachedNodeTypes[$nodeTypeName] = $nodeType;
		return $nodeType;
	}
}
namespace TYPO3\TYPO3CR\Domain\Service;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * Manager for node types
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class NodeTypeManager extends NodeTypeManager_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\TYPO3CR\Domain\Service\NodeTypeManager') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\TYPO3CR\Domain\Service\NodeTypeManager', $this);
		if ('TYPO3\TYPO3CR\Domain\Service\NodeTypeManager' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\TYPO3CR\Domain\Service\NodeTypeManager') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\TYPO3CR\Domain\Service\NodeTypeManager', $this);

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
	$reflectedClass = new \ReflectionClass('TYPO3\TYPO3CR\Domain\Service\NodeTypeManager');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\TYPO3CR\Domain\Service\NodeTypeManager', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\TYPO3CR\Domain\Service\NodeTypeManager', $propertyName, 'var');
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
		$configurationManager_reference = &$this->configurationManager;
		$this->configurationManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Configuration\ConfigurationManager');
		if ($this->configurationManager === NULL) {
			$this->configurationManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('13edcae8fd67699bb78dadc8c1eac29c', $configurationManager_reference);
			if ($this->configurationManager === NULL) {
				$this->configurationManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('13edcae8fd67699bb78dadc8c1eac29c',  $configurationManager_reference, 'TYPO3\Flow\Configuration\ConfigurationManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Configuration\ConfigurationManager'); });
			}
		}
$this->Flow_Injected_Properties = array (
  0 => 'configurationManager',
);
	}
}
#