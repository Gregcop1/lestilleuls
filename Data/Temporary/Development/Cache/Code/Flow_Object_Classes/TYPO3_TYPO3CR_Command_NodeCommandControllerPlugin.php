<?php 
namespace TYPO3\TYPO3CR\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.TYPO3CR".         *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Doctrine\ORM\QueryBuilder;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cli\ConsoleOutput;
use TYPO3\TYPO3CR\Domain\Factory\NodeFactory;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeType;
use TYPO3\TYPO3CR\Domain\Repository\WorkspaceRepository;
use TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface;
use TYPO3\TYPO3CR\Domain\Service\NodeTypeManager;

/**
 * Plugin for the TYPO3CR NodeCommandController which provides functionality for creating missing child nodes.
 *
 * @Flow\Scope("singleton")
 */
class NodeCommandControllerPlugin_Original implements NodeCommandControllerPluginInterface {

	/**
	 * @Flow\Inject
	 * @var ContextFactoryInterface
	 */
	protected $contextFactory;

	/**
	 * @Flow\Inject
	 * @var NodeTypeManager
	 */
	protected $nodeTypeManager;

	/**
	 * @Flow\Inject
	 * @var WorkspaceRepository
	 */
	protected $workspaceRepository;

	/**
	 * @Flow\Inject
	 * @var NodeFactory
	 */
	protected $nodeFactory;

	/**
	 * @var ConsoleOutput
	 */
	protected $output;

	/**
	 * Doctrine's Entity Manager. Note that "ObjectManager" is the name of the related
	 * interface ...
	 *
	 * @Flow\Inject
	 * @var \Doctrine\Common\Persistence\ObjectManager
	 */
	protected $entityManager;

	/**
	 * @var array
	 */
	protected $pluginConfigurations = array();

	/**
	 * Returns a short description
	 *
	 * @param string $controllerCommandName Name of the command in question, for example "repair"
	 * @return string A piece of text to be included in the overall description of the node:xy command
	 */
	static public function getSubCommandShortDescription($controllerCommandName) {
		switch ($controllerCommandName) {
			case 'repair':
				return 'Check and fix possibly missing child nodes';
			break;
		}
	}

	/**
	 * Returns a piece of description for the specific task the plugin solves for the specified command
	 *
	 * @param string $controllerCommandName Name of the command in question, for example "repair"
	 * @return string A piece of text to be included in the overall description of the node:xy command
	 */
	static public function getSubCommandDescription($controllerCommandName) {
		switch ($controllerCommandName) {
			case 'repair':
				return
					'<u>Missing child nodes</u>' . PHP_EOL .
					PHP_EOL .
					'For all nodes (or only those which match the --node-type filter specified with this' . PHP_EOL .
					'command) which currently don\'t have child nodes as configured by the node type\'s' . PHP_EOL .
					'configuration new child nodes will be created.' . PHP_EOL;
			break;
		}
	}

	/**
	 * A method which runs the task implemented by the plugin for the given command
	 *
	 * @param string $controllerCommandName Name of the command in question, for example "repair"
	 * @param ConsoleOutput $output An instance of ConsoleOutput which can be used for output or dialogues
	 * @param NodeType $nodeType Only handle this node type (if specified)
	 * @param string $workspaceName Only handle this workspace (if specified)
	 * @param boolean $dryRun If TRUE, don't do any changes, just simulate what you would do
	 * @return void
	 */
	public function invokeSubCommand($controllerCommandName, ConsoleOutput $output, NodeType $nodeType = NULL, $workspaceName = 'live', $dryRun = FALSE) {
		$this->output = $output;
		$this->createMissingChildNodes($nodeType, $workspaceName, $dryRun);
	}

	/**
	 * Performs checks for missing child nodes according to the node's auto-create configuration and creates
	 * them if necessary.
	 *
	 * @param NodeType $nodeType Only for this node type, if specified
	 * @param string $workspaceName Name of the workspace to consider
	 * @param boolean $dryRun Simulate?
	 * @return void
	 */
	protected function createMissingChildNodes(NodeType $nodeType = NULL, $workspaceName, $dryRun) {
		if ($nodeType !== NULL) {
			$this->output->outputLine('Checking nodes of type "%s" for missing child nodes ...', array($nodeType->getName()));
			$this->createChildNodesByNodeType($nodeType, $workspaceName, $dryRun);
		} else {
			$this->output->outputLine('Checking for missing child nodes ...');
			foreach ($this->nodeTypeManager->getNodeTypes() as $nodeType) {
				/** @var NodeType $nodeType */
				if ($nodeType->isAbstract()) {
					continue;
				}
				$this->createChildNodesByNodeType($nodeType, $workspaceName, $dryRun);
			}
		}
	}

	/**
	 * Create missing child nodes for the given node type
	 *
	 * @param NodeType $nodeType
	 * @param string $workspaceName
	 * @param boolean $dryRun
	 * @return void
	 */
	protected function createChildNodesByNodeType(NodeType $nodeType, $workspaceName, $dryRun) {
		$createdNodesCount = 0;
		$nodeCreationExceptions = 0;

		$nodeTypes = $this->nodeTypeManager->getSubNodeTypes($nodeType->getName(), FALSE);
		$nodeTypes[$nodeType->getName()] = $nodeType;

		if ($this->nodeTypeManager->hasNodeType((string)$nodeType)) {
			$nodeType = $this->nodeTypeManager->getNodeType((string)$nodeType);
			$nodeTypeNames[$nodeType->getName()] = $nodeType;
		} else {
			$this->output->outputLine('Node type "%s" does not exist', array((string)$nodeType));
			exit(1);
		}

		$context = $this->createContext($workspaceName);
		/** @var $nodeType NodeType */
		foreach ($nodeTypes as $nodeTypeName => $nodeType) {
			$childNodes = $nodeType->getAutoCreatedChildNodes();
			foreach ($this->getNodeDataByNodeTypeAndWorkspace($nodeTypeName, $workspaceName) as $nodeData) {
				$node = $this->nodeFactory->createFromNodeData($nodeData, $context);
				if (!$node instanceof NodeInterface) {
					continue;
				}
				foreach ($childNodes as $childNodeName => $childNodeType) {
					try {
						$childNodeMissing = $node->getNode($childNodeName) ? FALSE : TRUE;
						if ($childNodeMissing) {
							if ($dryRun === FALSE) {
								$node->createNode($childNodeName, $childNodeType);
								$this->output->outputLine('Auto created node named "%s" in "%s"', array($childNodeName, $node->getPath()));
							} else {
								$this->output->outputLine('Missing node named "%s" in "%s"', array($childNodeName, $node->getPath()));
							}
							$createdNodesCount++;
						}
					} catch (\Exception $exception) {
						$this->output->outputLine('Could not create node named "%s" in "%s" (%s)', array($childNodeName, $node->getPath(), $exception->getMessage()));
						$nodeCreationExceptions++;
					}
				}
			}
		}

		if ($createdNodesCount !== 0 || $nodeCreationExceptions !== 0) {
			if ($dryRun === FALSE) {
				$this->output->outputLine('Created %s new child nodes', array($createdNodesCount));

				if ($nodeCreationExceptions > 0) {
					$this->output->outputLine('%s Errors occurred during child node creation', array($nodeCreationExceptions));
				}
			} else {
				$this->output->outputLine('%s missing child nodes need to be created', array($createdNodesCount));
			}
		}
	}

	/**
	 * Creates a content context for given workspace
	 *
	 * @param string $workspaceName
	 * @return \TYPO3\TYPO3CR\Domain\Service\Context
	 */
	protected function createContext($workspaceName) {
		return $this->contextFactory->create(array(
			'workspaceName' => $workspaceName,
			'invisibleContentShown' => TRUE,
			'inaccessibleContentShown' => TRUE
		));
	}

	/**
	 * Retrieves all NodeData objects of a certain node type inside a given workspace.
	 *
	 * Shadow nodes are excluded, because they will be published when publishing the moved node.
	 *
	 * @param string $nodeType
	 * @param string $workspaceName
	 * @return array<NodeData>
	 */
	protected function getNodeDataByNodeTypeAndWorkspace($nodeType, $workspaceName) {
		/** @var QueryBuilder $queryBuilder */
		$queryBuilder = $this->entityManager->createQueryBuilder();

		$queryBuilder->select('n')
			->distinct()
			->from('TYPO3\TYPO3CR\Domain\Model\NodeData', 'n')
			->where('n.nodeType = :nodeType')
			->andWhere('n.workspace = :workspace')
			->andWhere('n.movedTo IS NULL OR n.removed = :removed')
			->setParameter('nodeType', $nodeType)
			->setParameter('workspace', $workspaceName)
			->setParameter('removed', FALSE, \PDO::PARAM_BOOL);
		return $queryBuilder->getQuery()->getResult();
	}

}namespace TYPO3\TYPO3CR\Command;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * Plugin for the TYPO3CR NodeCommandController which provides functionality for creating missing child nodes.
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class NodeCommandControllerPlugin extends NodeCommandControllerPlugin_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\TYPO3CR\Command\NodeCommandControllerPlugin') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\TYPO3CR\Command\NodeCommandControllerPlugin', $this);
		if ('TYPO3\TYPO3CR\Command\NodeCommandControllerPlugin' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\TYPO3CR\Command\NodeCommandControllerPlugin') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\TYPO3CR\Command\NodeCommandControllerPlugin', $this);

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
	$reflectedClass = new \ReflectionClass('TYPO3\TYPO3CR\Command\NodeCommandControllerPlugin');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\TYPO3CR\Command\NodeCommandControllerPlugin', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\TYPO3CR\Command\NodeCommandControllerPlugin', $propertyName, 'var');
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
		$contextFactory_reference = &$this->contextFactory;
		$this->contextFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface');
		if ($this->contextFactory === NULL) {
			$this->contextFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('6b6e9d36a8365cb0dccb3d849ae9366e', $contextFactory_reference);
			if ($this->contextFactory === NULL) {
				$this->contextFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('6b6e9d36a8365cb0dccb3d849ae9366e',  $contextFactory_reference, 'TYPO3\Neos\Domain\Service\ContentContextFactory', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface'); });
			}
		}
		$nodeTypeManager_reference = &$this->nodeTypeManager;
		$this->nodeTypeManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Service\NodeTypeManager');
		if ($this->nodeTypeManager === NULL) {
			$this->nodeTypeManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('478a517efacb3d47415a96d9caded2e9', $nodeTypeManager_reference);
			if ($this->nodeTypeManager === NULL) {
				$this->nodeTypeManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('478a517efacb3d47415a96d9caded2e9',  $nodeTypeManager_reference, 'TYPO3\TYPO3CR\Domain\Service\NodeTypeManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Service\NodeTypeManager'); });
			}
		}
		$workspaceRepository_reference = &$this->workspaceRepository;
		$this->workspaceRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Repository\WorkspaceRepository');
		if ($this->workspaceRepository === NULL) {
			$this->workspaceRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('2e64c564c983af14b47d0c9ae8992997', $workspaceRepository_reference);
			if ($this->workspaceRepository === NULL) {
				$this->workspaceRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('2e64c564c983af14b47d0c9ae8992997',  $workspaceRepository_reference, 'TYPO3\TYPO3CR\Domain\Repository\WorkspaceRepository', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Repository\WorkspaceRepository'); });
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
		$entityManager_reference = &$this->entityManager;
		$this->entityManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('Doctrine\Common\Persistence\ObjectManager');
		if ($this->entityManager === NULL) {
			$this->entityManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('ea59127cf49656654065ffe160cf78e1', $entityManager_reference);
			if ($this->entityManager === NULL) {
				$this->entityManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('ea59127cf49656654065ffe160cf78e1',  $entityManager_reference, 'Doctrine\Common\Persistence\ObjectManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('Doctrine\Common\Persistence\ObjectManager'); });
			}
		}
$this->Flow_Injected_Properties = array (
  0 => 'contextFactory',
  1 => 'nodeTypeManager',
  2 => 'workspaceRepository',
  3 => 'nodeFactory',
  4 => 'entityManager',
);
	}
}
#