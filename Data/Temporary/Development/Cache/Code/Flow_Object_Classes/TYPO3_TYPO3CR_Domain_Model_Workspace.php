<?php 
namespace TYPO3\TYPO3CR\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3CR".               *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Object\ObjectManagerInterface;
use TYPO3\TYPO3CR\Exception\WorkspaceException;

/**
 * A Workspace
 *
 * @Flow\Entity
 * @api
 */
class Workspace_Original {

	/**
	 * @var string
	 * @Flow\Identity
	 * @ORM\Id
	 * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=200 })
	 */
	protected $name;

	/**
	 * Workspace (if any) this workspace is based on.
	 *
	 * Content from the base workspace will shine through in this workspace
	 * as long as they are not modified in this workspace.
	 *
	 * @var Workspace
	 * @ORM\ManyToOne
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 */
	protected $baseWorkspace;

	/**
	 * Root node data of this workspace
	 *
	 * @var \TYPO3\TYPO3CR\Domain\Model\NodeData
	 * @ORM\ManyToOne
	 * @ORM\JoinColumn(referencedColumnName="id")
	 */
	protected $rootNodeData;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Domain\Repository\NodeDataRepository
	 */
	protected $nodeDataRepository;

	/**
	 * @Flow\Inject
	 * @var ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Service\PublishingServiceInterface
	 */
	protected $publishingService;

	/**
	 * Constructs a new workspace
	 *
	 * @param string $name Name of this workspace
	 * @param Workspace $baseWorkspace A workspace this workspace is based on (if any)
	 * @api
	 */
	public function __construct($name, Workspace $baseWorkspace = NULL) {
		$this->name = $name;
		$this->baseWorkspace = $baseWorkspace;
	}

	/**
	 * Initializes this workspace.
	 *
	 * If this workspace is brand new, a root node is created automatically.
	 *
	 * @param integer $initializationCause
	 * @return void
	 */
	public function initializeObject($initializationCause) {
		if ($initializationCause === ObjectManagerInterface::INITIALIZATIONCAUSE_CREATED) {
			$this->rootNodeData = new NodeData('/', $this);
			$this->nodeDataRepository->add($this->rootNodeData);
		}
	}

	/**
	 * Returns the name of this workspace
	 *
	 * @return string Name of this workspace
	 * @api
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Returns the base workspace, if any
	 *
	 * @return Workspace
	 * @api
	 */
	public function getBaseWorkspace() {
		return $this->baseWorkspace;
	}

	/**
	 * Returns the root node data of this workspace
	 *
	 * @return \TYPO3\TYPO3CR\Domain\Model\NodeData
	 */
	public function getRootNodeData() {
		return $this->rootNodeData;
	}

	/**
	 * Publishes the content of this workspace to another workspace.
	 *
	 * The specified workspace must be a base workspace of this workspace.
	 *
	 * @param Workspace $targetWorkspace The workspace to publish to
	 * @return void
	 * @api
	 */
	public function publish(Workspace $targetWorkspace) {
		$sourceNodes = $this->publishingService->getUnpublishedNodes($this);
		$this->publishNodes($sourceNodes, $targetWorkspace);
	}

	/**
	 * Publishes the given nodes to the target workspace.
	 *
	 * The specified workspace must be a base workspace of this workspace.
	 *
	 * @param array<\TYPO3\TYPO3CR\Domain\Model\NodeInterface> $nodes
	 * @param Workspace $targetWorkspace The workspace to publish to
	 * @return void
	 * @api
	 */
	public function publishNodes(array $nodes, Workspace $targetWorkspace) {
		foreach ($nodes as $node) {
			$this->publishNode($node, $targetWorkspace);
		}
	}

	/**
	 * Publishes the given node to the target workspace.
	 *
	 * The specified workspace must be a base workspace of this workspace.
	 *
	 * @param NodeInterface $node The node to publish
	 * @param Workspace $targetWorkspace The workspace to publish to
	 * @return void
	 * @api
	 */
	public function publishNode(NodeInterface $node, Workspace $targetWorkspace) {
		if ($this->baseWorkspace === NULL) {
			return;
		}
		if ($node->getWorkspace() !== $this) {
			return;
		}
		$this->verifyPublishingTargetWorkspace($targetWorkspace);
		$this->emitBeforeNodePublishing($node, $targetWorkspace);
		if ($node->getPath() === '/') {
			return;
		}

		$targetNodeData = $this->findNodeDataInTargetWorkspace($node, $targetWorkspace);
		$matchingNodeVariantExistsInTargetWorkspace = $targetNodeData !== NULL && $targetNodeData->getDimensionValues() === $node->getDimensions();

		if ($matchingNodeVariantExistsInTargetWorkspace) {
			$this->replaceNodeData($node, $targetNodeData);
		} else {
			$this->moveNodeVariantToTargetWorkspace($node, $targetWorkspace);
		}

		$this->emitAfterNodePublishing($node, $targetWorkspace);
	}

	/**
	 * Replace the node data of a node instance with a given target node data
	 *
	 * The node data of the node that is published will be removed and the existing node data inside the target
	 * workspace is updated to the changes and will be injected into the node instance. If the node was marked as
	 * removed, both node data are removed.
	 *
	 * @param NodeInterface $node The node instance with node data to be published
	 * @param NodeData $targetNodeData The existing node data in the target workspace
	 * @return void
	 */
	protected function replaceNodeData(NodeInterface $node, NodeData $targetNodeData) {
		$sourceNodeData = $node->getNodeData();

		$nodeWasMoved = FALSE;
		$movedShadowNodeData = $this->nodeDataRepository->findOneByMovedTo($sourceNodeData);
		if ($movedShadowNodeData instanceof NodeData) {
			$nodeWasMoved = TRUE;
			if ($movedShadowNodeData->isRemoved()) {
				$this->nodeDataRepository->remove($movedShadowNodeData);
			}
		}

		if ($node->isRemoved() === TRUE) {
			$this->nodeDataRepository->remove($targetNodeData);
		} else {
			$targetNodeData->similarize($node->getNodeData());
			if ($nodeWasMoved) {
				$targetNodeData->setPath($node->getPath(), FALSE);
			}
			$node->setNodeData($targetNodeData);
		}
		$this->nodeDataRepository->remove($sourceNodeData);
	}

	/**
	 * Move the given node instance to the target workspace
	 *
	 * If no target node variant (having the same dimension values) exists in the target workspace, the node that
	 * is published will be used as a new node variant in the target workspace.
	 *
	 * @param NodeInterface $node The node to publish
	 * @param Workspace $targetWorkspace The workspace to publish to
	 * @return void
	 */
	protected function moveNodeVariantToTargetWorkspace(NodeInterface $node, Workspace $targetWorkspace) {
		$nodeData = $node->getNodeData();

		$movedShadowNodeData = $this->nodeDataRepository->findOneByMovedTo($nodeData);
		if ($movedShadowNodeData instanceof NodeData && $movedShadowNodeData->isRemoved()) {
			$this->nodeDataRepository->remove($movedShadowNodeData);
		}

		if ($targetWorkspace->getBaseWorkspace() === NULL && $node->isRemoved()) {
			$this->nodeDataRepository->remove($nodeData);
		} else {
			$nodeData->setWorkspace($targetWorkspace);
		}
		$node->setNodeDataIsMatchingContext(NULL);
	}

	/**
	 * Returns the number of nodes in this workspace.
	 *
	 * If $includeBaseWorkspaces is enabled, also nodes of base workspaces are
	 * taken into account. If it is disabled (default) then the number of nodes
	 * is the actual number (+1) of changes related to its base workspaces.
	 *
	 * A node count of 1 means that no changes are pending in this workspace
	 * because a workspace always contains at least its Root Node.
	 *
	 * @return integer
	 * @api
	 */
	public function getNodeCount() {
		return $this->nodeDataRepository->countByWorkspace($this);
	}

	/**
	 * Checks if the specified workspace is a base workspace of this workspace
	 * and if not, throws an exception
	 *
	 * @param Workspace $targetWorkspace The publishing target workspace
	 * @return void
	 * @throws WorkspaceException if the specified workspace is not a base workspace of this workspace
	 */
	protected function verifyPublishingTargetWorkspace(Workspace $targetWorkspace) {
		$baseWorkspace = $this->baseWorkspace;
		while ($targetWorkspace !== $baseWorkspace) {
			if ($baseWorkspace === NULL) {
				throw new WorkspaceException(sprintf('The specified workspace "%s" is not a base workspace of "%s".', $targetWorkspace->getName(), $this->getName()), 1289499117);
			}
			$baseWorkspace = $baseWorkspace->getBaseWorkspace();
		}
	}

	/**
	 * Returns the NodeData instance with the given identifier from the target workspace.
	 * If no NodeData instance is found, NULL is returned.
	 *
	 * @param NodeInterface $node
	 * @param Workspace $targetWorkspace
	 * @return NodeData
	 */
	protected function findNodeDataInTargetWorkspace(NodeInterface $node, Workspace $targetWorkspace) {
		return $this->nodeDataRepository->findOneByIdentifier($node->getIdentifier(), $targetWorkspace, $node->getDimensions());
	}

	/**
	 * Emits a signal just before a node is being published
	 *
	 * The signal emits the source node and target workspace, i.e. the node contains its source
	 * workspace.
	 *
	 * @param NodeInterface $node The node to be published
	 * @param Workspace $targetWorkspace The publishing target workspace
	 * @return void
	 * @Flow\Signal
	 */
	protected function emitBeforeNodePublishing(NodeInterface $node, Workspace $targetWorkspace) {}

	/**
	 * Emits a signal when a node has been published.
	 *
	 * The signal emits the source node and target workspace, i.e. the node contains its source
	 * workspace.
	 *
	 * @param NodeInterface $node The node that was published
	 * @param Workspace $targetWorkspace The publishing target workspace
	 * @return void
	 * @Flow\Signal
	 */
	protected function emitAfterNodePublishing(NodeInterface $node, Workspace $targetWorkspace) {}

}
namespace TYPO3\TYPO3CR\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * A Workspace
 * @\TYPO3\Flow\Annotations\Entity
 */
class Workspace extends Workspace_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface, \TYPO3\Flow\Persistence\Aspect\PersistenceMagicInterface {

	private $Flow_Aop_Proxy_targetMethodsAndGroupedAdvices = array();

	private $Flow_Aop_Proxy_groupedAdviceChains = array();

	private $Flow_Aop_Proxy_methodIsInAdviceMode = array();


	/**
	 * Autogenerated Proxy Method
	 * @param string $name Name of this workspace
	 * @param Workspace $baseWorkspace A workspace this workspace is based on (if any)
	 */
	public function __construct() {
		$arguments = func_get_args();

		$this->Flow_Aop_Proxy_buildMethodsAndAdvicesArray();

		if (!array_key_exists(0, $arguments)) $arguments[0] = NULL;
		if (!array_key_exists(1, $arguments)) $arguments[1] = NULL;
		if (!array_key_exists(0, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $name in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		call_user_func_array('parent::__construct', $arguments);
		if ('TYPO3\TYPO3CR\Domain\Model\Workspace' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}

		if (get_class($this) === 'TYPO3\TYPO3CR\Domain\Model\Workspace') {
			$this->initializeObject(1);
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 protected function Flow_Aop_Proxy_buildMethodsAndAdvicesArray() {
		if (method_exists(get_parent_class($this), 'Flow_Aop_Proxy_buildMethodsAndAdvicesArray') && is_callable('parent::Flow_Aop_Proxy_buildMethodsAndAdvicesArray')) parent::Flow_Aop_Proxy_buildMethodsAndAdvicesArray();

		$objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager;
		$this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices = array(
			'__clone' => array(
				'TYPO3\Flow\Aop\Advice\AfterReturningAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AfterReturningAdvice('TYPO3\Flow\Persistence\Aspect\PersistenceMagicAspect', 'cloneObject', $objectManager, NULL),
				),
			),
			'emitBeforeNodePublishing' => array(
				'TYPO3\Flow\Aop\Advice\AfterReturningAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AfterReturningAdvice('TYPO3\Flow\SignalSlot\SignalAspect', 'forwardSignalToDispatcher', $objectManager, NULL),
				),
			),
			'emitAfterNodePublishing' => array(
				'TYPO3\Flow\Aop\Advice\AfterReturningAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AfterReturningAdvice('TYPO3\Flow\SignalSlot\SignalAspect', 'forwardSignalToDispatcher', $objectManager, NULL),
				),
			),
		);
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {

		$this->Flow_Aop_Proxy_buildMethodsAndAdvicesArray();

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
		$result = NULL;
		if (method_exists(get_parent_class($this), '__wakeup') && is_callable('parent::__wakeup')) parent::__wakeup();

		if (get_class($this) === 'TYPO3\TYPO3CR\Domain\Model\Workspace') {
			$this->initializeObject(2);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies() {
		if (!isset($this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices) || empty($this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices)) {
			$this->Flow_Aop_Proxy_buildMethodsAndAdvicesArray();
			if (is_callable('parent::Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies')) parent::Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		}	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function Flow_Aop_Proxy_fixInjectedPropertiesForDoctrineProxies() {
		if (!$this instanceof \Doctrine\ORM\Proxy\Proxy || isset($this->Flow_Proxy_injectProperties_fixInjectedPropertiesForDoctrineProxies)) {
			return;
		}
		$this->Flow_Proxy_injectProperties_fixInjectedPropertiesForDoctrineProxies = TRUE;
		if (is_callable(array($this, 'Flow_Proxy_injectProperties'))) {
			$this->Flow_Proxy_injectProperties();
		}	}

	/**
	 * Autogenerated Proxy Method
	 */
	 private function Flow_Aop_Proxy_getAdviceChains($methodName) {
		$adviceChains = array();
		if (isset($this->Flow_Aop_Proxy_groupedAdviceChains[$methodName])) {
			$adviceChains = $this->Flow_Aop_Proxy_groupedAdviceChains[$methodName];
		} else {
			if (isset($this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices[$methodName])) {
				$groupedAdvices = $this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices[$methodName];
				if (isset($groupedAdvices['TYPO3\Flow\Aop\Advice\AroundAdvice'])) {
					$this->Flow_Aop_Proxy_groupedAdviceChains[$methodName]['TYPO3\Flow\Aop\Advice\AroundAdvice'] = new \TYPO3\Flow\Aop\Advice\AdviceChain($groupedAdvices['TYPO3\Flow\Aop\Advice\AroundAdvice']);
					$adviceChains = $this->Flow_Aop_Proxy_groupedAdviceChains[$methodName];
				}
			}
		}
		return $adviceChains;
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function Flow_Aop_Proxy_invokeJoinPoint(\TYPO3\Flow\Aop\JoinPointInterface $joinPoint) {
		if (__CLASS__ !== $joinPoint->getClassName()) return parent::Flow_Aop_Proxy_invokeJoinPoint($joinPoint);
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode[$joinPoint->getMethodName()])) {
			return call_user_func_array(array('self', $joinPoint->getMethodName()), $joinPoint->getMethodArguments());
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __clone() {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['__clone'])) {
		$result = NULL;

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['__clone'] = TRUE;
			try {
			
					$methodArguments = array();

				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\TYPO3CR\Domain\Model\Workspace', '__clone', $methodArguments);
				$result = $this->Flow_Aop_Proxy_invokeJoinPoint($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

				if (isset($this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices['__clone']['TYPO3\Flow\Aop\Advice\AfterReturningAdvice'])) {
					$advices = $this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices['__clone']['TYPO3\Flow\Aop\Advice\AfterReturningAdvice'];
					$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\TYPO3CR\Domain\Model\Workspace', '__clone', $methodArguments, NULL, $result);
					foreach ($advices as $advice) {
						$advice->invoke($joinPoint);
					}

					$methodArguments = $joinPoint->getMethodArguments();
				}

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['__clone']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['__clone']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param NodeInterface $node The node to be published
	 * @param Workspace $targetWorkspace The publishing target workspace
	 * @return void
	 * @\TYPO3\Flow\Annotations\Signal
	 */
	 protected function emitBeforeNodePublishing(\TYPO3\TYPO3CR\Domain\Model\NodeInterface $node, \TYPO3\TYPO3CR\Domain\Model\Workspace $targetWorkspace) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['emitBeforeNodePublishing'])) {
		$result = parent::emitBeforeNodePublishing($node, $targetWorkspace);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['emitBeforeNodePublishing'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['node'] = $node;
				$methodArguments['targetWorkspace'] = $targetWorkspace;
			
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\TYPO3CR\Domain\Model\Workspace', 'emitBeforeNodePublishing', $methodArguments);
				$result = $this->Flow_Aop_Proxy_invokeJoinPoint($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

				if (isset($this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices['emitBeforeNodePublishing']['TYPO3\Flow\Aop\Advice\AfterReturningAdvice'])) {
					$advices = $this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices['emitBeforeNodePublishing']['TYPO3\Flow\Aop\Advice\AfterReturningAdvice'];
					$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\TYPO3CR\Domain\Model\Workspace', 'emitBeforeNodePublishing', $methodArguments, NULL, $result);
					foreach ($advices as $advice) {
						$advice->invoke($joinPoint);
					}

					$methodArguments = $joinPoint->getMethodArguments();
				}

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['emitBeforeNodePublishing']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['emitBeforeNodePublishing']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param NodeInterface $node The node that was published
	 * @param Workspace $targetWorkspace The publishing target workspace
	 * @return void
	 * @\TYPO3\Flow\Annotations\Signal
	 */
	 protected function emitAfterNodePublishing(\TYPO3\TYPO3CR\Domain\Model\NodeInterface $node, \TYPO3\TYPO3CR\Domain\Model\Workspace $targetWorkspace) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['emitAfterNodePublishing'])) {
		$result = parent::emitAfterNodePublishing($node, $targetWorkspace);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['emitAfterNodePublishing'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['node'] = $node;
				$methodArguments['targetWorkspace'] = $targetWorkspace;
			
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\TYPO3CR\Domain\Model\Workspace', 'emitAfterNodePublishing', $methodArguments);
				$result = $this->Flow_Aop_Proxy_invokeJoinPoint($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

				if (isset($this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices['emitAfterNodePublishing']['TYPO3\Flow\Aop\Advice\AfterReturningAdvice'])) {
					$advices = $this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices['emitAfterNodePublishing']['TYPO3\Flow\Aop\Advice\AfterReturningAdvice'];
					$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\TYPO3CR\Domain\Model\Workspace', 'emitAfterNodePublishing', $methodArguments, NULL, $result);
					foreach ($advices as $advice) {
						$advice->invoke($joinPoint);
					}

					$methodArguments = $joinPoint->getMethodArguments();
				}

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['emitAfterNodePublishing']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['emitAfterNodePublishing']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __sleep() {
		$result = NULL;
		$this->Flow_Object_PropertiesToSerialize = array();
	$reflectionService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Reflection\ReflectionService');
	$reflectedClass = new \ReflectionClass('TYPO3\TYPO3CR\Domain\Model\Workspace');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\TYPO3CR\Domain\Model\Workspace', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\TYPO3CR\Domain\Model\Workspace', $propertyName, 'var');
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
		$nodeDataRepository_reference = &$this->nodeDataRepository;
		$this->nodeDataRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Repository\NodeDataRepository');
		if ($this->nodeDataRepository === NULL) {
			$this->nodeDataRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('6d8e58e235099c88f352e23317321129', $nodeDataRepository_reference);
			if ($this->nodeDataRepository === NULL) {
				$this->nodeDataRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('6d8e58e235099c88f352e23317321129',  $nodeDataRepository_reference, 'TYPO3\TYPO3CR\Domain\Repository\NodeDataRepository', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Repository\NodeDataRepository'); });
			}
		}
		$objectManager_reference = &$this->objectManager;
		$this->objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Object\ObjectManagerInterface');
		if ($this->objectManager === NULL) {
			$this->objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('0c3c44be7be16f2a287f1fb2d068dde4', $objectManager_reference);
			if ($this->objectManager === NULL) {
				$this->objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('0c3c44be7be16f2a287f1fb2d068dde4',  $objectManager_reference, 'TYPO3\Flow\Object\ObjectManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Object\ObjectManagerInterface'); });
			}
		}
		$publishingService_reference = &$this->publishingService;
		$this->publishingService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Service\PublishingServiceInterface');
		if ($this->publishingService === NULL) {
			$this->publishingService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('ef9001948b3581121867993d253f7696', $publishingService_reference);
			if ($this->publishingService === NULL) {
				$this->publishingService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('ef9001948b3581121867993d253f7696',  $publishingService_reference, 'TYPO3\Neos\Service\PublishingService', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Service\PublishingServiceInterface'); });
			}
		}
$this->Flow_Injected_Properties = array (
  0 => 'nodeDataRepository',
  1 => 'objectManager',
  2 => 'publishingService',
);
	}
}
#