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
use TYPO3\Flow\Security\Context;
use TYPO3\Neos;
use TYPO3\Neos\Domain\Model\PluginViewDefinition;
use TYPO3\Neos\Domain\Service\ContentContext;
use TYPO3\Neos\Domain\Service\ContentContextFactory;
use TYPO3\TYPO3CR\Domain\Factory\NodeFactory;
use TYPO3\TYPO3CR\Domain\Model\Node;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeType;
use TYPO3\TYPO3CR\Domain\Repository\NodeDataRepository;
use TYPO3\TYPO3CR\Domain\Service\NodeTypeManager;

/**
 * Central authority for interactions with plugins.
 * Whenever details about Plugins or PluginViews are needed this service should be used.
 *
 * For some methods the ContentContext has to be specified. This is required in order for the TYPO3CR to fetch nodes
 * of the current workspace. The context can be retrieved from any node of the correct workspace & tree. If no node
 * is available (e.g. for CLI requests) the ContentContextFactory can be used to create a context instance.
 *
 * @Flow\Scope("singleton")
 */
class PluginService_Original {

	/**
	 * @var NodeTypeManager
	 * @Flow\Inject
	 */
	protected $nodeTypeManager;

	/**
	 * @Flow\Inject
	 * @var NodeDataRepository
	 */
	protected $nodeDataRepository;

	/**
	 * @Flow\Inject
	 * @var Context
	 */
	protected $securityContext;

	/**
	 * @Flow\Inject
	 * @var ContentContextFactory
	 */
	protected $contentContextFactory;

	/**
	 * @Flow\Inject
	 * @var NodeFactory
	 */
	protected $nodeFactory;

	/**
	 * Returns an array of all available plugin nodes
	 *
	 * @param ContentContext $context current content context, see class doc comment for details
	 * @return array<NodeInterface> all plugin nodes in the current $context
	 */
	public function getPluginNodes(ContentContext $context) {
		$pluginNodeTypes = $this->nodeTypeManager->getSubNodeTypes('TYPO3.Neos:Plugin', FALSE);
		$pluginNodes = array();
		foreach (array_keys($pluginNodeTypes) as $pluginNodeType) {
			$pluginNodes = array_merge($pluginNodes, $this->getNodes($pluginNodeType, $context));
		}
		return $pluginNodes;
	}

	/**
	 * Returns an array of all plugin nodes with View Definitions
	 *
	 * @param ContentContext $context
	 * @return array<NodeInterface> all plugin nodes with View Definitions in the current $context
	 */
	public function getPluginNodesWithViewDefinitions(ContentContext $context) {
		$pluginNodes = array();
		foreach ($this->getPluginNodes($context) as $pluginNode) {
			/** @var NodeInterface $pluginNode */
			if ($this->getPluginViewDefinitionsByPluginNodeType($pluginNode->getNodeType()) !== array()) {
				$pluginNodes[] = $pluginNode;
			}
		}
		return $pluginNodes;
	}

	/**
	 * Find all nodes of a specific node type
	 *
	 * @param string $nodeType
	 * @param ContentContext $context current content context, see class doc comment for details
	 * @return array<NodeInterface> all nodes of type $nodeType in the current $context
	 */
	protected function getNodes($nodeType, ContentContext $context) {
		$nodes = array();
		$siteNode = $context->getCurrentSiteNode();
		foreach ($this->nodeDataRepository->findByParentAndNodeTypeRecursively($siteNode->getPath(), $nodeType, $context->getWorkspace()) as $nodeData) {
			$nodes[] = $this->nodeFactory->createFromNodeData($nodeData, $context);
		}
		return $nodes;
	}

	/**
	 * Get all configured PluginView definitions for a specific $pluginNodeType
	 *
	 * @param NodeType $pluginNodeType node type name of the master plugin
	 * @return array<PluginViewDefinition> list of PluginViewDefinition instances for the given $pluginNodeName
	 */
	public function getPluginViewDefinitionsByPluginNodeType(NodeType $pluginNodeType) {
		$viewDefinitions = array();
		foreach ($this->getPluginViewConfigurationsByPluginNodeType($pluginNodeType) as $pluginViewName => $pluginViewConfiguration) {
			$viewDefinitions[] = new PluginViewDefinition($pluginNodeType, $pluginViewName, $pluginViewConfiguration);
		}
		return $viewDefinitions;
	}

	/**
	 * @param NodeType $pluginNodeType
	 * @return array
	 */
	protected function getPluginViewConfigurationsByPluginNodeType(NodeType $pluginNodeType) {
		$pluginNodeTypeOptions = $pluginNodeType->getOptions();
		return isset($pluginNodeTypeOptions['pluginViews']) ? $pluginNodeTypeOptions['pluginViews'] : array();
	}

	/**
	 * returns a plugin node or one of it's view nodes
	 * if an view has been configured for that specific
	 * controller and action combination
	 *
	 * @param NodeInterface $currentNode
	 * @param string $controllerObjectName
	 * @param string $actionName
	 * @return NodeInterface
	 */
	public function getPluginNodeByAction(NodeInterface $currentNode, $controllerObjectName, $actionName) {
		$viewDefinition = $this->getPluginViewDefinitionByAction($controllerObjectName, $actionName);

		if ($currentNode->getNodeType()->isOfType('TYPO3.Neos:PluginView')) {
			$masterPluginNode = $this->getPluginViewNodeByMasterPlugin($currentNode, $viewDefinition->getName());
		} else {
			$masterPluginNode = $currentNode;
		}

		if ($viewDefinition !== NULL) {
			$viewNode = $this->getPluginViewNodeByMasterPlugin($currentNode, $viewDefinition->getName());
			if ($viewNode instanceof Node) {
				return $viewNode;
			}
		}

		return $masterPluginNode;
	}

	/**
	 * Fetch a PluginView definition that matches the specified controller and action combination
	 *
	 * @param string $controllerObjectName
	 * @param string $actionName
	 * @return PluginViewDefinition
	 * @throws Neos\Exception if more than one PluginView matches the given controller/action pair
	 */
	public function getPluginViewDefinitionByAction($controllerObjectName, $actionName) {
		$pluginNodeTypes = $this->nodeTypeManager->getSubNodeTypes('TYPO3.Neos:Plugin', FALSE);

		$matchingPluginViewDefinitions = array();
		foreach ($pluginNodeTypes as $pluginNodeType) {
			/** @var $pluginViewDefinition PluginViewDefinition */
			foreach ($this->getPluginViewDefinitionsByPluginNodeType($pluginNodeType) as $pluginViewDefinition) {
				if ($pluginViewDefinition->matchesControllerActionPair($controllerObjectName, $actionName) !== TRUE) {
					continue;
				}
				$matchingPluginViewDefinitions[] = $pluginViewDefinition;
			}
		}
		if (count($matchingPluginViewDefinitions) > 1) {
			throw new Neos\Exception(sprintf('More than one PluginViewDefinition found for controller "%s", action "%s":%s', $controllerObjectName, $actionName, chr(10) . implode(chr(10), $matchingPluginViewDefinitions)), 1377597671);
		}

		return count($matchingPluginViewDefinitions) > 0 ? current($matchingPluginViewDefinitions) : NULL;
	}

	/**
	 * returns a specific view node of an master plugin
	 * or NULL if it does not exist
	 *
	 * @param NodeInterface $node
	 * @param string $viewName
	 * @return NodeInterface
	 */
	public function getPluginViewNodeByMasterPlugin(NodeInterface $node, $viewName) {
		/** @var $context ContentContext */
		$context = $node->getContext();
		foreach ($this->getNodes('TYPO3.Neos:PluginView', $context) as $pluginViewNode) {
			/** @var \TYPO3\TYPO3CR\Domain\Model\NodeInterface $pluginViewNode */
			if ($pluginViewNode->isRemoved()) {
				continue;
			}
			if ($pluginViewNode->getProperty('plugin') === $node->getPath()
				&& $pluginViewNode->getProperty('view') === $viewName) {
				return $pluginViewNode;
			}
		}

		return NULL;
	}
}
namespace TYPO3\Neos\Service;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * Central authority for interactions with plugins.
 * Whenever details about Plugins or PluginViews are needed this service should be used.
 * 
 * For some methods the ContentContext has to be specified. This is required in order for the TYPO3CR to fetch nodes
 * of the current workspace. The context can be retrieved from any node of the correct workspace & tree. If no node
 * is available (e.g. for CLI requests) the ContentContextFactory can be used to create a context instance.
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class PluginService extends PluginService_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\Neos\Service\PluginService') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Service\PluginService', $this);
		if ('TYPO3\Neos\Service\PluginService' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\Neos\Service\PluginService') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Service\PluginService', $this);

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
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\Service\PluginService');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\Service\PluginService', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\Service\PluginService', $propertyName, 'var');
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
		$nodeDataRepository_reference = &$this->nodeDataRepository;
		$this->nodeDataRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Repository\NodeDataRepository');
		if ($this->nodeDataRepository === NULL) {
			$this->nodeDataRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('6d8e58e235099c88f352e23317321129', $nodeDataRepository_reference);
			if ($this->nodeDataRepository === NULL) {
				$this->nodeDataRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('6d8e58e235099c88f352e23317321129',  $nodeDataRepository_reference, 'TYPO3\TYPO3CR\Domain\Repository\NodeDataRepository', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Repository\NodeDataRepository'); });
			}
		}
		$securityContext_reference = &$this->securityContext;
		$this->securityContext = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Security\Context');
		if ($this->securityContext === NULL) {
			$this->securityContext = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('48836470c14129ade5f39e28c4816673', $securityContext_reference);
			if ($this->securityContext === NULL) {
				$this->securityContext = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('48836470c14129ade5f39e28c4816673',  $securityContext_reference, 'TYPO3\Flow\Security\Context', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Security\Context'); });
			}
		}
		$contentContextFactory_reference = &$this->contentContextFactory;
		$this->contentContextFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Neos\Domain\Service\ContentContextFactory');
		if ($this->contentContextFactory === NULL) {
			$this->contentContextFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('b0f43d8a69099e5990a8079e0c191fa3', $contentContextFactory_reference);
			if ($this->contentContextFactory === NULL) {
				$this->contentContextFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('b0f43d8a69099e5990a8079e0c191fa3',  $contentContextFactory_reference, 'TYPO3\Neos\Domain\Service\ContentContextFactory', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Neos\Domain\Service\ContentContextFactory'); });
			}
		}
		$nodeFactory_reference = &$this->nodeFactory;
		$this->nodeFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Factory\NodeFactory');
		if ($this->nodeFactory === NULL) {
			$this->nodeFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('bc9bb21d5b30e2ec064f6bb8e860feb4', $nodeFactory_reference);
			if ($this->nodeFactory === NULL) {
				$this->nodeFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('bc9bb21d5b30e2ec064f6bb8e860feb4',  $nodeFactory_reference, 'TYPO3\TYPO3CR\Domain\Factory\NodeFactory', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Factory\NodeFactory'); });
			}
		}
$this->Flow_Injected_Properties = array (
  0 => 'nodeTypeManager',
  1 => 'nodeDataRepository',
  2 => 'securityContext',
  3 => 'contentContextFactory',
  4 => 'nodeFactory',
);
	}
}
#