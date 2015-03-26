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
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Service\Cache\FirstLevelNodeCache;

/**
 * Context
 *
 * @api
 */
class Context_Original {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Domain\Repository\WorkspaceRepository
	 */
	protected $workspaceRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Domain\Repository\NodeDataRepository
	 */
	protected $nodeDataRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Domain\Factory\NodeFactory
	 */
	protected $nodeFactory;

	/**
	 * @var \TYPO3\TYPO3CR\Domain\Model\Workspace
	 */
	protected $workspace;

	/**
	 * @var string
	 */
	protected $workspaceName;

	/**
	 * @var \DateTime
	 */
	protected $currentDateTime;

	/**
	 * If TRUE, invisible content elements will be shown.
	 *
	 * @var boolean
	 */
	protected $invisibleContentShown = FALSE;

	/**
	 * If TRUE, removed content elements will be shown, even though they are removed.
	 *
	 * @var boolean
	 */
	protected $removedContentShown = FALSE;

	/**
	 * If TRUE, even content elements will be shown which are not accessible by the currently logged in account.
	 *
	 * @var boolean
	 */
	protected $inaccessibleContentShown = FALSE;

	/**
	 * @var array
	 */
	protected $dimensions = array();

	/**
	 * @var array
	 */
	protected $targetDimensions = array();

	/**
	 * @Flow\IgnoreValidation
	 * @var FirstLevelNodeCache
	 */
	protected $firstLevelNodeCache;

	/**
	 * Creates a new Context object.
	 *
	 * NOTE: This is for internal use only, you should use the ContextFactory for creating Context instances.
	 *
	 * @param string $workspaceName
	 * @param \DateTime $currentDateTime
	 * @param array $dimensions Array of dimensions with array of ordered values
	 * @param array $targetDimensions
	 * @param boolean $invisibleContentShown
	 * @param boolean $removedContentShown
	 * @param boolean $inaccessibleContentShown
	 * @return \TYPO3\TYPO3CR\Domain\Service\Context
	 * @see ContextFactoryInterface
	 */
	public function __construct($workspaceName, \DateTime $currentDateTime, array $dimensions, array $targetDimensions, $invisibleContentShown, $removedContentShown, $inaccessibleContentShown) {
		$this->workspaceName = $workspaceName;
		$this->currentDateTime = $currentDateTime;
		$this->dimensions = $dimensions;
		$this->invisibleContentShown = $invisibleContentShown;
		$this->removedContentShown = $removedContentShown;
		$this->inaccessibleContentShown = $inaccessibleContentShown;
		$this->targetDimensions = $targetDimensions;

		$this->firstLevelNodeCache = new FirstLevelNodeCache();
	}

	/**
	 * Returns the current workspace.
	 *
	 * @param boolean $createWorkspaceIfNecessary If enabled, creates a workspace with the configured name if it doesn't exist already
	 * @return \TYPO3\TYPO3CR\Domain\Model\Workspace The workspace or NULL
	 * @api
	 */
	public function getWorkspace($createWorkspaceIfNecessary = TRUE) {
		if ($this->workspace === NULL) {
			$this->workspace = $this->workspaceRepository->findOneByName($this->workspaceName);
			if (!$this->workspace) {
				if ($createWorkspaceIfNecessary === FALSE) {
					return NULL;
				}
				$liveWorkspace = $this->workspaceRepository->findOneByName('live');
				if (!$liveWorkspace) {
					$liveWorkspace = new \TYPO3\TYPO3CR\Domain\Model\Workspace('live');
					$this->workspaceRepository->add($liveWorkspace);
				}
				if ($this->workspaceName === 'live') {
					$this->workspace = $liveWorkspace;
				} else {
					$this->workspace = new \TYPO3\TYPO3CR\Domain\Model\Workspace($this->workspaceName, $liveWorkspace);
					$this->workspaceRepository->add($this->workspace);
				}
			}
		}
		return $this->workspace;
	}

	/**
	 * Returns the name of the workspace.
	 *
	 * @return string
	 * @api
	 */
	public function getWorkspaceName() {
		return $this->workspaceName;
	}

	/**
	 * Returns the current date and time in form of a \DateTime
	 * object.
	 *
	 * If you use this method for getting the current date and time
	 * everywhere in your code, it will be possible to simulate a certain
	 * time in unit tests or in the actual application (for realizing previews etc).
	 *
	 * @return \DateTime The current date and time - or a simulated version of it
	 * @api
	 */
	public function getCurrentDateTime() {
		return $this->currentDateTime;
	}

	/**
	 * Convenience method returns the root node for
	 * this context workspace.
	 *
	 * @return \TYPO3\TYPO3CR\Domain\Model\NodeInterface
	 * @api
	 */
	public function getRootNode() {
		return $this->getNode('/');
	}

	/**
	 * Returns a node specified by the given absolute path.
	 *
	 * @param string $path Absolute path specifying the node
	 * @return \TYPO3\TYPO3CR\Domain\Model\NodeInterface The specified node or NULL if no such node exists
	 * @throws \InvalidArgumentException
	 * @api
	 */
	public function getNode($path) {
		if (!is_string($path) || $path[0] !== '/') {
			throw new \InvalidArgumentException('Only absolute paths are allowed for Context::getNode()', 1284975105);
		}
		$workspaceRootNode = $this->getWorkspace()->getRootNodeData();
		$rootNode = $this->nodeFactory->createFromNodeData($workspaceRootNode, $this);
		if ($path !== '/') {
			$node = $this->firstLevelNodeCache->getByPath($path);
			if ($node === FALSE) {
				$node = $rootNode->getNode(substr($path, 1));
				$this->firstLevelNodeCache->setByPath($path, $node);
			}
		} else {
			$node = $rootNode;
		}

		return $node;
	}

	/**
	 * Get a node by identifier and this context
	 *
	 * @param string $identifier The identifier of a node
	 * @return \TYPO3\TYPO3CR\Domain\Model\NodeInterface The node with the given identifier or NULL if no such node exists
	 */
	public function getNodeByIdentifier($identifier) {
		$node = $this->firstLevelNodeCache->getByIdentifier($identifier);
		if ($node !== FALSE) {
			return $node;
		}
		$nodeData = $this->nodeDataRepository->findOneByIdentifier($identifier, $this->getWorkspace(FALSE), $this->dimensions);
		if ($nodeData !== NULL) {
			$node = $this->nodeFactory->createFromNodeData($nodeData, $this);
		} else {
			$node = NULL;
		}
		$this->firstLevelNodeCache->setByIdentifier($identifier, $node);
		return $node;
	}

	/**
	 * Finds all nodes lying on the path specified by (and including) the given
	 * starting point and end point.
	 *
	 * @param mixed $startingPoint Either an absolute path or an actual node specifying the starting point, for example /sites/mysite.com/
	 * @param mixed $endPoint Either an absolute path or an actual node specifying the end point, for example /sites/mysite.com/homepage/subpage
	 * @return array<\TYPO3\TYPO3CR\Domain\Model\NodeInterface> The nodes found between and including the given paths or an empty array of none were found
	 * @api
	 */
	public function getNodesOnPath($startingPoint, $endPoint) {
		$startingPointPath = ($startingPoint instanceof \TYPO3\TYPO3CR\Domain\Model\NodeInterface) ? $startingPoint->getPath() : $startingPoint;
		$endPointPath = ($endPoint instanceof \TYPO3\TYPO3CR\Domain\Model\NodeInterface) ? $endPoint->getPath() : $endPoint;

		$nodeDataElements = $this->nodeDataRepository->findOnPath($startingPointPath, $endPointPath, $this->getWorkspace(), $this->getDimensions(), $this->isRemovedContentShown());
		$nodes = array();
		foreach ($nodeDataElements as $nodeData) {
			$node = $this->nodeFactory->createFromNodeData($nodeData, $this);
			if ($node !== NULL) {
				$nodes[] = $node;
				$this->firstLevelNodeCache->setByPath($node->getPath(), $node);
			}
		}

		return $nodes;
	}

	/**
	 * Adopts a node from a (possibly) different context to this context
	 *
	 * Checks if a node variant matching the exact dimensions already exists for this context and
	 * return it if found. Otherwise a new node variant for this context is created.
	 *
	 * In case the node already exists in the context but does not match the target dimensions a
	 * new, more specific node is created and returned.
	 *
	 * @param NodeInterface $node The node with a different context. If the context of the given node is the same as this context the operation will have no effect.
	 * @return NodeInterface A new or existing node that matches this context
	 */
	public function adoptNode(NodeInterface $node) {
		if ($node->getContext() === $this && $node->dimensionsAreMatchingTargetDimensionValues()) {
			return $node;
		}

		$existingNode = $this->getNodeByIdentifier($node->getIdentifier());
		if ($existingNode !== NULL) {
			if ($existingNode->dimensionsAreMatchingTargetDimensionValues()) {
				$adoptedNode = $existingNode;
			} else {
				$adoptedNode = $existingNode->createVariantForContext($this);
			}
		} else {
			$adoptedNode = $node->createVariantForContext($this);
		}

		$this->firstLevelNodeCache->setByIdentifier($adoptedNode->getIdentifier(), $adoptedNode);
		return $adoptedNode;
	}

	/**
	 * Tells if nodes which are usually invisible should be accessible through the Node API and queries
	 *
	 * @return boolean
	 * @see NodeFactory->filterNodeByContext()
	 * @api
	 */
	public function isInvisibleContentShown() {
		return $this->invisibleContentShown;
	}

	/**
	 * Tells if nodes which have their "removed" flag set should be accessible through
	 * the Node API and queries
	 *
	 * @return boolean
	 * @see Node->filterNodeByContext()
	 * @api
	 */
	public function isRemovedContentShown() {
		return $this->removedContentShown;
	}

	/**
	 * Tells if nodes which have access restrictions should be accessible through
	 * the Node API and queries even without the necessary roles / rights
	 *
	 * @return boolean
	 * @api
	 */
	public function isInaccessibleContentShown() {
		return $this->inaccessibleContentShown;
	}

	/**
	 * An indexed array of dimensions with ordered list of values for matching nodes by content dimensions
	 *
	 * @return array
	 */
	public function getDimensions() {
		return $this->dimensions;
	}

	/**
	 * An indexed array of dimensions with a set of values that should be applied when updating or creating
	 *
	 * @return array
	 */
	public function getTargetDimensions() {
		return $this->targetDimensions;
	}

	/**
	 * An indexed array of dimensions with a set of values that should be applied when updating or creating
	 *
	 * @return array
	 */
	public function getTargetDimensionValues() {
		return array_map(function ($value) { return array($value); }, $this->getTargetDimensions());
	}

	/**
	 * Returns the properties of this context.
	 *
	 * @return array
	 */
	public function getProperties() {
		return array(
			'workspaceName' => $this->workspaceName,
			'currentDateTime' => $this->currentDateTime,
			'dimensions' => $this->dimensions,
			'targetDimensions' => $this->targetDimensions,
			'invisibleContentShown' => $this->invisibleContentShown,
			'removedContentShown' => $this->removedContentShown,
			'inaccessibleContentShown' => $this->inaccessibleContentShown
		);
	}

	/**
	 * Not public API!
	 *
	 * @return FirstLevelNodeCache
	 */
	public function getFirstLevelNodeCache() {
		return $this->firstLevelNodeCache;
	}

}
namespace TYPO3\TYPO3CR\Domain\Service;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * Context
 */
class Context extends Context_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 * @param string $workspaceName
	 * @param \DateTime $currentDateTime
	 * @param array $dimensions Array of dimensions with array of ordered values
	 * @param array $targetDimensions
	 * @param boolean $invisibleContentShown
	 * @param boolean $removedContentShown
	 * @param boolean $inaccessibleContentShown
	 * @return \TYPO3\TYPO3CR\Domain\Service\Context
	 */
	public function __construct() {
		$arguments = func_get_args();

		if (!array_key_exists(0, $arguments)) $arguments[0] = NULL;
		if (!array_key_exists(1, $arguments)) $arguments[1] = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('DateTime');
		if (!array_key_exists(4, $arguments)) $arguments[4] = NULL;
		if (!array_key_exists(5, $arguments)) $arguments[5] = NULL;
		if (!array_key_exists(6, $arguments)) $arguments[6] = NULL;
		if (!array_key_exists(0, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $workspaceName in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		if (!array_key_exists(1, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $currentDateTime in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		if (!array_key_exists(2, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $dimensions in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		if (!array_key_exists(3, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $targetDimensions in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		if (!array_key_exists(4, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $invisibleContentShown in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		if (!array_key_exists(5, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $removedContentShown in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		if (!array_key_exists(6, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $inaccessibleContentShown in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		call_user_func_array('parent::__construct', $arguments);
		if ('TYPO3\TYPO3CR\Domain\Service\Context' === get_class($this)) {
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
	$reflectedClass = new \ReflectionClass('TYPO3\TYPO3CR\Domain\Service\Context');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\TYPO3CR\Domain\Service\Context', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\TYPO3CR\Domain\Service\Context', $propertyName, 'var');
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
		$workspaceRepository_reference = &$this->workspaceRepository;
		$this->workspaceRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Repository\WorkspaceRepository');
		if ($this->workspaceRepository === NULL) {
			$this->workspaceRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('2e64c564c983af14b47d0c9ae8992997', $workspaceRepository_reference);
			if ($this->workspaceRepository === NULL) {
				$this->workspaceRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('2e64c564c983af14b47d0c9ae8992997',  $workspaceRepository_reference, 'TYPO3\TYPO3CR\Domain\Repository\WorkspaceRepository', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Repository\WorkspaceRepository'); });
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
		$nodeFactory_reference = &$this->nodeFactory;
		$this->nodeFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Factory\NodeFactory');
		if ($this->nodeFactory === NULL) {
			$this->nodeFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('bc9bb21d5b30e2ec064f6bb8e860feb4', $nodeFactory_reference);
			if ($this->nodeFactory === NULL) {
				$this->nodeFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('bc9bb21d5b30e2ec064f6bb8e860feb4',  $nodeFactory_reference, 'TYPO3\TYPO3CR\Domain\Factory\NodeFactory', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Factory\NodeFactory'); });
			}
		}
$this->Flow_Injected_Properties = array (
  0 => 'workspaceRepository',
  1 => 'nodeDataRepository',
  2 => 'nodeFactory',
);
	}
}
#