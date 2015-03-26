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
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Service\NodeService;
use TYPO3\TYPO3CR\Domain\Service\NodeTypeManager;
use TYPO3\TYPO3CR\Exception\NodeException;
use TYPO3\TYPO3CR\Utility;

/**
 * Centralizes common operations like moving and copying of Nodes with Neos specific additional handling.
 *
 * @Flow\Scope("singleton")
 */
class NodeOperations_Original {

	/**
	 * @Flow\Inject
	 * @var NodeTypeManager
	 */
	protected $nodeTypeManager;

	/**
	 * @Flow\Inject
	 * @var NodeNameGenerator
	 */
	protected $nodeNameGenerator;

	/**
	 * @Flow\Inject
	 * @var NodeService
	 */
	protected $nodeService;

	/**
	 * Helper method for creating a new node.
	 *
	 * @param NodeInterface $referenceNode
	 * @param array $nodeData
	 * @param string $position
	 * @return NodeInterface
	 * @throws \InvalidArgumentException
	 */
	public function create(NodeInterface $referenceNode, array $nodeData, $position) {
		if (!in_array($position, array('before', 'into', 'after'), TRUE)) {
			throw new \InvalidArgumentException('The position should be one of the following: "before", "into", "after".', 1347133640);
		}
		$nodeType = $this->nodeTypeManager->getNodeType($nodeData['nodeType']);

		if ($nodeType->isOfType('TYPO3.Neos:Document') && !isset($nodeData['properties']['uriPathSegment']) && isset($nodeData['properties']['title'])) {
			$nodeData['properties']['uriPathSegment'] = Utility::renderValidNodeName($nodeData['properties']['title']);
		}

		$proposedNodeName = isset($nodeData['nodeName']) ? $nodeData['nodeName'] : NULL;
		$nodeData['nodeName'] = $this->nodeNameGenerator->generateUniqueNodeName($this->getDesignatedParentNode($referenceNode, $position), $proposedNodeName);

		if ($position === 'into') {
			$newNode = $referenceNode->createNode($nodeData['nodeName'], $nodeType);
		} else {
			$parentNode = $referenceNode->getParent();
			$newNode = $parentNode->createNode($nodeData['nodeName'], $nodeType);

			if ($position === 'before') {
				$newNode->moveBefore($referenceNode);
			} else {
				$newNode->moveAfter($referenceNode);
			}
		}

		if (isset($nodeData['properties']) && is_array($nodeData['properties'])) {
			foreach ($nodeData['properties'] as $propertyName => $propertyValue) {
				$newNode->setProperty($propertyName, $propertyValue);
			}
		}

		return $newNode;
	}

	/**
	 * Move $node before, into or after $targetNode
	 *
	 * @param NodeInterface $node
	 * @param NodeInterface $targetNode
	 * @param string $position where the node should be added (allowed: before, into, after)
	 * @return NodeInterface The same node given as first argument
	 * @throws NodeException
	 */
	public function move(NodeInterface $node, NodeInterface $targetNode, $position) {
		if (!in_array($position, array('before', 'into', 'after'), TRUE)) {
			throw new NodeException('The position should be one of the following: "before", "into", "after".', 1296132542);
		}

		$nodeName = $this->nodeNameGenerator->generateUniqueNodeName($this->getDesignatedParentNode($targetNode, $position), $node->getName());
		if ($nodeName !== $node->getName()) {
			$currentParentPath = $node->getParentPath();
			$currentParentPath = $currentParentPath !== '/' ? $currentParentPath . '/' : $currentParentPath;
			while ($this->nodeService->nodePathExistsInAnyContext($currentParentPath . $nodeName)) {
				$nodeName = $this->nodeNameGenerator->generateUniqueNodeName($this->getDesignatedParentNode($targetNode, $position), $node->getName());
			}
			// FIXME: This can be removed if $node->move* support additionally changing the name of the node.
			$node->setName($nodeName);
		}

		switch ($position) {
			case 'before':
				$node->moveBefore($targetNode);
				break;
			case 'into':
				$node->moveInto($targetNode);
				break;
			case 'after':
				$node->moveAfter($targetNode);
		}

		return $node;
	}

	/**
	 * Copy $node before, into or after $targetNode
	 *
	 * @param NodeInterface $node the node to be copied
	 * @param NodeInterface $targetNode the target node to be copied "to", see $position
	 * @param string $position where the node should be added in relation to $targetNode (allowed: before, into, after)
	 * @param string $nodeName optional node name (if empty random node name will be generated)
	 * @return NodeInterface The copied node
	 * @throws NodeException
	 */
	public function copy(NodeInterface $node, NodeInterface $targetNode, $position, $nodeName = NULL) {
		if (!in_array($position, array('before', 'into', 'after'), TRUE)) {
			throw new NodeException('The position should be one of the following: "before", "into", "after".', 1346832303);
		}

		$nodeName = $this->nodeNameGenerator->generateUniqueNodeName($this->getDesignatedParentNode($targetNode, $position), (!empty($nodeName) ? $nodeName : NULL));

		switch ($position) {
			case 'before':
				$copiedNode = $node->copyBefore($targetNode, $nodeName);
				break;
			case 'after':
				$copiedNode = $node->copyAfter($targetNode, $nodeName);
				break;
			case 'into':
			default:
				$copiedNode = $node->copyInto($targetNode, $nodeName);
		}

		return $copiedNode;
	}

	/**
	 * @param NodeInterface $targetNode
	 * @param string $position
	 * @return NodeInterface
	 */
	protected function getDesignatedParentNode(NodeInterface $targetNode, $position) {
		$referenceNode = $targetNode;
		if (in_array($position, array('before', 'after'))) {
			$referenceNode = $targetNode->getParent();
		}

		return $referenceNode;
	}
}namespace TYPO3\Neos\Service;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * Centralizes common operations like moving and copying of Nodes with Neos specific additional handling.
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class NodeOperations extends NodeOperations_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\Neos\Service\NodeOperations') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Service\NodeOperations', $this);
		if ('TYPO3\Neos\Service\NodeOperations' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\Neos\Service\NodeOperations') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Service\NodeOperations', $this);

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
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\Service\NodeOperations');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\Service\NodeOperations', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\Service\NodeOperations', $propertyName, 'var');
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
		$nodeNameGenerator_reference = &$this->nodeNameGenerator;
		$this->nodeNameGenerator = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Neos\Service\NodeNameGenerator');
		if ($this->nodeNameGenerator === NULL) {
			$this->nodeNameGenerator = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('9278b3ec98b9f9c513ec23887338ac5a', $nodeNameGenerator_reference);
			if ($this->nodeNameGenerator === NULL) {
				$this->nodeNameGenerator = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('9278b3ec98b9f9c513ec23887338ac5a',  $nodeNameGenerator_reference, 'TYPO3\Neos\Service\NodeNameGenerator', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Neos\Service\NodeNameGenerator'); });
			}
		}
		$nodeService_reference = &$this->nodeService;
		$this->nodeService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Service\NodeService');
		if ($this->nodeService === NULL) {
			$this->nodeService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('882ea910950cb2841572ab634091e4ee', $nodeService_reference);
			if ($this->nodeService === NULL) {
				$this->nodeService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('882ea910950cb2841572ab634091e4ee',  $nodeService_reference, 'TYPO3\TYPO3CR\Domain\Service\NodeService', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Service\NodeService'); });
			}
		}
$this->Flow_Injected_Properties = array (
  0 => 'nodeTypeManager',
  1 => 'nodeNameGenerator',
  2 => 'nodeService',
);
	}
}
#