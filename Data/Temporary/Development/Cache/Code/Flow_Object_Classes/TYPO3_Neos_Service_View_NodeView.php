<?php 
namespace TYPO3\Neos\Service\View;

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
use TYPO3\Eel\FlowQuery\FlowQuery;
use TYPO3\Flow\Log\SystemLoggerInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\Flow\Utility\Arrays;

/**
 * An View specialized on single or multiple Nodes in a tree structure
 *
 * NOTE: This class only exists for backwards compatibility with not-yet refactored service end points and service
 *       controllers.
 *
 * @Flow\Scope("prototype")
 */
class NodeView_Original extends \TYPO3\Flow\Mvc\View\JsonView {

	/**
	 * @var integer
	 */
	const STYLE_LIST = 1;
	const STYLE_TREE = 2;

	/**
	 * @var integer
	 */
	protected $outputStyle;

	/**
	 * @Flow\Inject
	 * @var SystemLoggerInterface
	 */
	protected $systemLogger;

	/**
	 * Assigns a node to the NodeView.
	 *
	 * @param NodeInterface $node The node to render
	 * @param array $propertyNames Optional list of property names to include in the JSON output
	 * @return void
	 */
	public function assignNode(NodeInterface $node, array $propertyNames = array('name', 'path', 'identifier', 'properties', 'nodeType')) {
		$this->setConfiguration(
			array(
				'value' => array(
					'data' => array(
						'_only' => array('name', 'path', 'identifier', 'properties', 'nodeType'),
						'_descend' => array('properties' => $propertyNames)
					)
				)
			)
		);
		$this->assign('value', array('data' => $node, 'success' => TRUE));
	}

	/**
	 * @param array $nodes
	 */
	public function assignNodes(array $nodes) {
		$data = array();
		foreach ($nodes as $node) {
			if ($node->getPath() !== '/') {
				$q = new FlowQuery(array($node));
				$closestDocumentNode = $q->closest('[instanceof TYPO3.Neos:Document]')->get(0);
				if ($closestDocumentNode !== NULL) {
					$data[] = array(
						'nodeContextPath' => $node->getContextPath(),
						'documentNodeContextPath' => $closestDocumentNode->getContextPath(),
					);
				} else {
					$this->systemLogger->log('You have a node that is no longer connected to a parent. Path: ' . $node->getPath() . ' (Identifier: ' . $node->getIdentifier() . ')');
				}
			}
		}

		$this->assign('value', array('data' => $data, 'success' => TRUE));
	}

	/**
	 * Prepares this view to render a list or tree of child nodes of the given node.
	 *
	 * @param NodeInterface $node The node to fetch child nodes of
	 * @param string $nodeTypeFilter Criteria for filtering the child nodes
	 * @param integer $outputStyle Either STYLE_TREE or STYLE_list
	 * @param integer $depth How many levels of childNodes (0 = unlimited)
	 * @param NodeInterface $untilNode if given, expand all nodes on the rootline towards $untilNode, no matter what is defined with $depth.
	 * @return void
	 */
	public function assignChildNodes(NodeInterface $node, $nodeTypeFilter, $outputStyle = self::STYLE_LIST, $depth = 0, NodeInterface $untilNode = NULL) {
		$this->outputStyle = $outputStyle;
		$nodes = array();
		$this->collectChildNodeData($nodes, $node, ($nodeTypeFilter === '' ? NULL : $nodeTypeFilter), $depth, $untilNode);
		$this->setConfiguration(array('value' => array('data' => array('_descendAll' => array()))));

		$this->assign('value', array('data' => $nodes, 'success' => TRUE));
	}

	/**
	 * Prepares this view to render a list or tree of filtered nodes.
	 *
	 * @param NodeInterface $node
	 * @param array<\TYPO3\TYPO3CR\Domain\Model\NodeData> $matchedNodes
	 * @param integer $outputStyle Either STYLE_TREE or STYLE_list
	 * @return void
	 */
	public function assignFilteredChildNodes(NodeInterface $node, array $matchedNodes, $outputStyle = self::STYLE_LIST) {
		$this->outputStyle = $outputStyle;
		$nodes = $this->collectParentNodeData($node, $matchedNodes);
		$this->setConfiguration(array('value' => array('data' => array('_descendAll' => array()))));

		$this->assign('value', array('data' => $nodes, 'success' => TRUE));
	}

	/**
	 * Collect node data and traverse child nodes
	 *
	 * @param array &$nodes
	 * @param NodeInterface $node
	 * @param string $nodeTypeFilter
	 * @param integer $depth levels of child nodes to fetch. 0 = unlimited
	 * @param \TYPO3\TYPO3CR\Domain\Model\NodeInterface $untilNode if given, expand all nodes on the rootline towards $untilNode, no matter what is defined with $depth.
	 * @param integer $recursionPointer current recursion level
	 * @return void
	 */
	protected function collectChildNodeData(array &$nodes, NodeInterface $node, $nodeTypeFilter, $depth = 0, NodeInterface $untilNode = NULL, $recursionPointer = 1) {
		foreach ($node->getChildNodes($nodeTypeFilter) as $childNode) {
			/** @var NodeInterface $childNode */
			$expand = ($depth === 0 || $recursionPointer < $depth);

			if ($expand === FALSE && $untilNode !== NULL && strpos($untilNode->getPath(), $childNode->getPath()) === 0 && $childNode !== $untilNode) {
				// in case $untilNode is set, and the current childNode is on the rootline of $untilNode (and not the node itself), expand the node.
				$expand = TRUE;
			}

			switch ($this->outputStyle) {
				case self::STYLE_LIST:
					$nodeType = $childNode->getNodeType()->getName();
					$properties = $childNode->getProperties();
					$properties['__contextNodePath'] = $childNode->getContextPath();
					$properties['__workspaceName'] = $childNode->getWorkspace()->getName();
					$properties['__nodeName'] = $childNode->getName();
					$properties['__nodeType'] = $nodeType;
					$properties['__title'] = $nodeType === 'TYPO3.Neos:Document' ? $childNode->getProperty('title') : $childNode->getLabel();
					array_push($nodes, $properties);
					if ($expand) {
						$this->collectChildNodeData($nodes, $childNode, $nodeTypeFilter, $depth, $untilNode, ($recursionPointer + 1));
					}
				break;
				case self::STYLE_TREE:
					$children = array();
					$hasChildNodes = $childNode->hasChildNodes($nodeTypeFilter) === TRUE;
					if ($expand && $hasChildNodes) {
						$this->collectChildNodeData($children, $childNode, $nodeTypeFilter, $depth, $untilNode, ($recursionPointer + 1));
					}
					array_push($nodes, $this->collectTreeNodeData($childNode, $expand, $children, $hasChildNodes));
			}
		}
	}

	/**
	 * @param NodeInterface $rootNode
	 * @param array<\TYPO3\TYPO3CR\Domain\Model\NodeData> $nodes
	 * @return array
	 */
	public function collectParentNodeData(NodeInterface $rootNode, array $nodes) {
		$nodeCollection = array();

		$addNode = function($node, $matched) use($rootNode, &$nodeCollection) {
			/** @var NodeInterface $node */
			$path = str_replace('/', '.children.', substr($node->getPath(), strlen($rootNode->getPath()) + 1));
			if ($path !== '') {
				$nodeCollection = Arrays::setValueByPath($nodeCollection, $path . '.node', $node);
				if ($matched === TRUE) {
					$nodeCollection = Arrays::setValueByPath($nodeCollection, $path . '.matched', TRUE);
				}
			}
		};

		$findParent = function($node) use(&$findParent, &$addNode) {
			/** @var NodeInterface $node */
			$parent = $node->getParent();
			if ($parent !== NULL) {
				$addNode($parent, FALSE);
				$findParent($parent);
			}
		};

		foreach ($nodes as $node) {
			$addNode($node, TRUE);
			$findParent($node);
		}

		$treeNodes = array();
		$self = $this;
		$collectTreeNodeData = function(&$treeNodes, $node) use(&$collectTreeNodeData, $self) {
			$children = array();
			if (isset($node['children'])) {
				foreach ($node['children'] as $childNode) {
					$collectTreeNodeData($children, $childNode);
				}
			}
			$treeNodes[] = $self->collectTreeNodeData($node['node'], TRUE, $children, $children !== array(), isset($node['matched']));
		};

		foreach ($nodeCollection as $firstLevelNode) {
			$collectTreeNodeData($treeNodes, $firstLevelNode);
		}

		return $treeNodes;
	}

	/**
	 * @param NodeInterface $node
	 * @param boolean $expand
	 * @param array $children
	 * @param boolean $hasChildNodes
	 * @param boolean $matched
	 * @return array
	 */
	public function collectTreeNodeData(NodeInterface $node, $expand = TRUE, array $children = array(), $hasChildNodes = FALSE, $matched = FALSE) {
		$isTimedPage = FALSE;
		$now = new \DateTime();
		$now = $now->getTimestamp();
		$hiddenBeforeDateTime = $node->getHiddenBeforeDateTime();
		$hiddenAfterDateTime = $node->getHiddenAfterDateTime();

		if ($hiddenBeforeDateTime !== NULL && $hiddenBeforeDateTime->getTimestamp() > $now) {
			$isTimedPage = TRUE;
		}
		if ($hiddenAfterDateTime !== NULL) {
			$isTimedPage = TRUE;
		}

		$classes = array();
		if ($isTimedPage === TRUE && $node->isHidden() === FALSE) {
			array_push($classes, 'neos-timedVisibility');
		}
		if ($node->isHidden() === TRUE) {
			array_push($classes, 'neos-hidden');
		}
		if ($node->isHiddenInIndex() === TRUE) {
			array_push($classes, 'neos-hiddenInIndex');
		}
		if ($matched) {
			array_push($classes, 'neos-matched');
		}

		$uriBuilder = $this->controllerContext->getUriBuilder();
		$nodeType = $node->getNodeType();
		$nodeTypeConfiguration = $nodeType->getFullConfiguration();
		if ($node->getNodeType()->isOfType('TYPO3.Neos:Document')) {
			$uriForNode = $uriBuilder->reset()->setFormat('html')->setCreateAbsoluteUri(TRUE)->uriFor('show', array('node' => $node), 'Frontend\Node', 'TYPO3.Neos');
		} else {
			$uriForNode = '#';
		}
		$label = $node->getLabel();
		$nodeTypeLabel = $node->getNodeType()->getLabel();
		$treeNode = array(
			'key' => $node->getContextPath(),
			'title' => $label,
			'fullTitle' => $node->getProperty('title'),
			'tooltip' => ($nodeTypeLabel == '' || strpos($label, $nodeTypeLabel) === FALSE) ? $label . ' (' . $nodeTypeLabel . ')' : $label,
			'href' => $uriForNode,
			'isFolder' => $hasChildNodes,
			'isLazy' => ($hasChildNodes && !$expand),
			'nodeType' => $nodeType->getName(),
			'isAutoCreated' => $node->isAutoCreated(),
			'expand' => $expand,
			'addClass' => implode(' ', $classes),
			'name' => $node->getName(),
			'iconClass' => isset($nodeTypeConfiguration['ui']) && isset($nodeTypeConfiguration['ui']['icon']) ? $nodeTypeConfiguration['ui']['icon'] : '',
			'isHidden' => $node->isHidden()
		);
		if ($hasChildNodes) {
			$treeNode['children'] = $children;
		}
		return $treeNode;
	}
}
namespace TYPO3\Neos\Service\View;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * An View specialized on single or multiple Nodes in a tree structure
 * 
 * NOTE: This class only exists for backwards compatibility with not-yet refactored service end points and service
 *       controllers.
 * @\TYPO3\Flow\Annotations\Scope("prototype")
 */
class NodeView extends NodeView_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 * @param array $options
	 * @throws \TYPO3\Flow\Mvc\Exception
	 */
	public function __construct() {
		$arguments = func_get_args();

		if (!array_key_exists(0, $arguments)) $arguments[0] = array (
);
		call_user_func_array('parent::__construct', $arguments);
		if ('TYPO3\Neos\Service\View\NodeView' === get_class($this)) {
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
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\Service\View\NodeView');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\Service\View\NodeView', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\Service\View\NodeView', $propertyName, 'var');
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
		$systemLogger_reference = &$this->systemLogger;
		$this->systemLogger = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Log\SystemLoggerInterface');
		if ($this->systemLogger === NULL) {
			$this->systemLogger = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('6d57d95a1c3cd7528e3e6ea15012dac8', $systemLogger_reference);
			if ($this->systemLogger === NULL) {
				$this->systemLogger = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('6d57d95a1c3cd7528e3e6ea15012dac8',  $systemLogger_reference, 'TYPO3\Flow\Log\SystemLoggerInterface', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Log\SystemLoggerInterface'); });
			}
		}
		$reflectionService_reference = &$this->reflectionService;
		$this->reflectionService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Reflection\ReflectionService');
		if ($this->reflectionService === NULL) {
			$this->reflectionService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('921ad637f16d2059757a908fceaf7076', $reflectionService_reference);
			if ($this->reflectionService === NULL) {
				$this->reflectionService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('921ad637f16d2059757a908fceaf7076',  $reflectionService_reference, 'TYPO3\Flow\Reflection\ReflectionService', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Reflection\ReflectionService'); });
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
$this->Flow_Injected_Properties = array (
  0 => 'systemLogger',
  1 => 'reflectionService',
  2 => 'persistenceManager',
);
	}
}
#