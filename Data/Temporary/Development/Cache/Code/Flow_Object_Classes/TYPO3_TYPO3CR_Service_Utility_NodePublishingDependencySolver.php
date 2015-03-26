<?php 
namespace TYPO3\TYPO3CR\Service\Utility;

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
use TYPO3\TYPO3CR\Domain\Model\Node;
use TYPO3\TYPO3CR\Exception\WorkspaceException;

/**
 * Solve / sort nodes by dependencies for publishing
 */
class NodePublishingDependencySolver_Original {

	/**
	 * @var array
	 */
	protected $nodesByPath;

	/**
	 * @var array
	 */
	protected $nodesByNodeData;

	/**
	 * @var array
	 */
	protected $dependenciesOutgoing;

	/**
	 * @var array
	 */
	protected $dependenciesIncoming;

	/**
	 * @var array
	 */
	protected $nodesWithoutIncoming;

	/**
	 * Sort nodes by an order suitable for publishing
	 *
	 * This makes sure all parent and moved-to relations are resolved and changes that need to be published
	 * before other changes will be published first.
	 *
	 * Uses topological sorting of node dependencies (http://en.wikipedia.org/wiki/Topological_sorting) to build a publishable order of nodes.
	 *
	 * @param array $nodes Array of nodes to sort, if dependencies are missing in this list an exception will be thrown
	 * @return array Array of nodes sorted by dependencies for publishing
	 * @throws WorkspaceException
	 */
	public function sort(array $nodes) {
		$this->buildNodeDependencies($nodes);
		$sortedNodes = $this->resolveDependencies();

		$dependencyCount = array_filter($this->dependenciesOutgoing, function($a) { return $a !== array(); });
		if (count($dependencyCount) > 0) {
			throw new WorkspaceException('Cannot publish a list of nodes because of cycles', 1416484223);
		}

		return $sortedNodes;
	}

	/**
	 * Prepare dependencies for the given list of nodes
	 *
	 * @param array $nodes Unsorted list of nodes
	 * @throws WorkspaceException
	 */
	protected function buildNodeDependencies(array $nodes) {
		$this->nodesByPath = array();
		$this->nodesByNodeData = array();
		$this->dependenciesOutgoing = array();
		$this->dependenciesIncoming = array();
		$this->nodesWithoutIncoming = array();

		/** @var Node $node */
		foreach ($nodes as $node) {
			$this->nodesByPath[$node->getPath()][] = $node;
			$this->nodesByNodeData[spl_object_hash($node->getNodeData())] = $node;
			$this->nodesWithoutIncoming[spl_object_hash($node)] = $node;
		}

		/** @var Node $node */
		foreach ($nodes as $node) {
			$nodeHash = spl_object_hash($node);

			// Add dependencies for (direct) parents, this will also cover moved and created nodes
			if (isset($this->nodesByPath[$node->getParentPath()])) {
				/** @var Node $parentNode */
				foreach ($this->nodesByPath[$node->getParentPath()] as $parentNode) {
					$dependencyHash = spl_object_hash($parentNode);
					$this->dependenciesIncoming[$nodeHash][$dependencyHash] = $parentNode;
					$this->dependenciesOutgoing[$dependencyHash][$nodeHash] = $node;
					unset($this->nodesWithoutIncoming[$nodeHash]);
				}
			}

			// Add a dependency for a moved-to reference
			$movedToNodeData = $node->getNodeData()->getMovedTo();
			if ($movedToNodeData !== NULL) {
				$movedToHash = spl_object_hash($movedToNodeData);
				if (!isset($this->nodesByNodeData[$movedToHash])) {
					throw new WorkspaceException('Cannot publish a list of nodes with missing dependency (' . $node->getPath() . ' needs ' . $movedToNodeData->getPath() . ' to be published)', 1416483470);
				}
				$dependencyHash = spl_object_hash($this->nodesByNodeData[$movedToHash]);
				$this->dependenciesIncoming[$nodeHash][$dependencyHash] = $this->nodesByNodeData[$movedToHash];
				$this->dependenciesOutgoing[$dependencyHash][$nodeHash] = $node;
				unset($this->nodesWithoutIncoming[$nodeHash]);
			}
		}
	}

	/**
	 * Resolve node dependencies
	 *
	 * 1. Pick a node from the set of nodes without incoming dependencies
	 * 2. For all dependencies of that node:
	 * 2a. Remove the dependency
	 * 2b. If the dependency has no other incoming dependencies itself, add it to the set of nodes without incoming dependencies
	 *
	 * @return array Sorted list of nodes (not all dependencies might be solved)
	 */
	protected function resolveDependencies() {
		$sortedNodes = array();
		while (count($this->nodesWithoutIncoming) > 0) {
			$node = array_pop($this->nodesWithoutIncoming);
			$sortedNodes[] = $node;
			$nodeHash = spl_object_hash($node);
			if (isset($this->dependenciesOutgoing[$nodeHash])) {
				foreach ($this->dependenciesOutgoing[$nodeHash] as $dependencyHash => $dependencyNode) {
					unset($this->dependenciesOutgoing[$nodeHash][$dependencyHash]);
					unset($this->dependenciesIncoming[$dependencyHash][$nodeHash]);

					if (count($this->dependenciesIncoming[$dependencyHash]) === 0) {
						$this->nodesWithoutIncoming[$dependencyHash] = $dependencyNode;
					}
				}
			}
		}
		return $sortedNodes;
	}
}
namespace TYPO3\TYPO3CR\Service\Utility;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * Solve / sort nodes by dependencies for publishing
 */
class NodePublishingDependencySolver extends NodePublishingDependencySolver_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


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
	$reflectedClass = new \ReflectionClass('TYPO3\TYPO3CR\Service\Utility\NodePublishingDependencySolver');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\TYPO3CR\Service\Utility\NodePublishingDependencySolver', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\TYPO3CR\Service\Utility\NodePublishingDependencySolver', $propertyName, 'var');
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