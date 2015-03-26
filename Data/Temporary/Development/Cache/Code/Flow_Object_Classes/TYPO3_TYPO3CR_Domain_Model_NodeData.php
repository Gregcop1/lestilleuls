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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use TYPO3\Flow\Persistence\PersistenceManagerInterface;
use TYPO3\Flow\Reflection\ObjectAccess;
use TYPO3\Flow\Utility\Algorithms;
use TYPO3\TYPO3CR\Domain\Repository\NodeDataRepository;
use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Exception\NodeExistsException;

/**
 * The node data inside the content repository. This is only a data
 * container that could be exchanged in the future.
 *
 * NOTE: This is internal only and should not be used or extended by userland code.
 *
 * @Flow\Entity
 * @ORM\Table(
 * 	uniqueConstraints={
 * 		@ORM\UniqueConstraint(name="path_workspace_dimensions",columns={"pathhash", "workspace", "dimensionshash"}),
 * 		@ORM\UniqueConstraint(name="identifier_workspace_dimensions_movedto",columns={"identifier", "workspace", "dimensionshash", "movedto"})
 * 	},
 * 	indexes={
 * 		@ORM\Index(name="parentpath_sortingindex",columns={"parentpathhash", "sortingindex"}),
 * 		@ORM\Index(name="identifierindex",columns={"identifier"}),
 * 		@ORM\Index(name="nodetypeindex",columns={"nodetype"})
 * 	}
 * )
 */
class NodeData_Original extends AbstractNodeData {

	/**
	 * Auto-incrementing version of this node data, used for optimistic locking
	 *
	 * @ORM\Version
	 * @var integer
	 */
	protected $version;

	/**
	 * MD5 hash of the path
	 * This property is needed for the unique index over path & workspace (for which the path property is too large).
	 * The hash is generated in calculatePathHash().
	 *
	 * @var string
	 * @ORM\Column(length=32)
	 */
	protected $pathHash;

	/**
	 * Absolute path of this node
	 *
	 * @var string
	 * @ORM\Column(length=4000)
	 * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=4000 })
	 */
	protected $path;

	/**
	 * MD5 hash of the parent path
	 * This property is needed to speed up lookup by parent path.
	 * The hash is generated in calculateParentPathHash().
	 *
	 * @var string
	 * @ORM\Column(length=32)
	 */
	protected $parentPathHash;

	/**
	 * Absolute path of the parent path
	 *
	 * @var string
	 * @ORM\Column(length=4000)
	 * @Flow\Validate(type="StringLength", options={ "maximum"=4000 })
	 */
	protected $parentPath;

	/**
	 * Workspace this node is contained in
	 *
	 * @var \TYPO3\TYPO3CR\Domain\Model\Workspace
	 *
	 * Note: Since we compare workspace instances for various purposes it's unsafe to have a lazy relation
	 * @ORM\ManyToOne
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 */
	protected $workspace;

	/**
	 * Identifier of this node which is unique within its workspace
	 *
	 * @var string
	 */
	protected $identifier;

	/**
	 * Index within the nodes with the same parent
	 *
	 * @var integer
	 * @ORM\Column(name="sortingindex", nullable=true)
	 */
	protected $index;

	/**
	 * Level number within the global node tree
	 *
	 * @var integer
	 * @Flow\Transient
	 */
	protected $depth;

	/**
	 * Node name, derived from its node path
	 *
	 * @var string
	 * @Flow\Transient
	 */
	protected $name;

	/**
	 * Optional proxy for a content object which acts as an alternative property container
	 *
	 * @var \TYPO3\TYPO3CR\Domain\Model\ContentObjectProxy
	 * @ORM\ManyToOne
	 */
	protected $contentObjectProxy;

	/**
	 * If this is a removed node. This flag can and is only used in workspaces
	 * which do have a base workspace. In a bottom level workspace nodes are
	 * really removed, in other workspaces, removal is realized by this flag.
	 *
	 * @var boolean
	 */
	protected $removed = FALSE;

	/**
	 * @ORM\OneToMany(mappedBy="nodeData", orphanRemoval=true)
	 * @var \Doctrine\Common\Collections\Collection<\TYPO3\TYPO3CR\Domain\Model\NodeDimension>
	 */
	protected $dimensions;

	/**
	 * MD5 hash of the content dimensions
	 * The hash is generated in buildDimensionValues().
	 *
	 * @var string
	 * @ORM\Column(length=32)
	 */
	protected $dimensionsHash;

	/**
	 * @var \DateTime
	 * @ORM\Column(nullable=true)
	 */
	protected $hiddenBeforeDateTime;

	/**
	 * @var \DateTime
	 * @ORM\Column(nullable=true)
	 */
	protected $hiddenAfterDateTime;

	/**
	 * @ORM\Column(type="objectarray")
	 * @var array
	 */
	protected $dimensionValues;

	/**
	 * If a node data is moved a "shadow" node data is inserted that references the new node data
	 *
	 * @var NodeData
	 * @ORM\ManyToOne
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 */
	protected $movedTo;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * @Flow\Inject
	 * @var PersistenceManagerInterface
	 */
	protected $persistenceManager;

	/**
	 * Constructs this node data container
	 *
	 * Creating new nodes by instantiating NodeData is not part of the public API!
	 * The content repository needs to properly integrate new nodes into the node
	 * tree and therefore you must use createNode() or createNodeFromTemplate()
	 * in a Node object which will internally create a NodeData object.
	 *
	 * @param string $path Absolute path of this node
	 * @param \TYPO3\TYPO3CR\Domain\Model\Workspace $workspace The workspace this node will be contained in
	 * @param string $identifier The node identifier (not the persistence object identifier!). Specifying this only makes sense while creating corresponding nodes
	 * @param array $dimensions An array of dimension name to dimension values
	 */
	public function __construct($path, Workspace $workspace, $identifier = NULL, array $dimensions = NULL) {
		$this->setPath($path, FALSE);
		$this->workspace = $workspace;
		$this->identifier = ($identifier === NULL) ? Algorithms::generateUUID() : $identifier;

		$this->dimensions = new ArrayCollection();
		if ($dimensions !== NULL) {
			foreach ($dimensions as $dimensionName => $dimensionValues) {
				foreach ($dimensionValues as $dimensionValue) {
					$this->dimensions->add(new NodeDimension($this, $dimensionName, $dimensionValue));
				}
			}
		}
		$this->buildDimensionValues();
	}

	/**
	 * Returns the name of this node
	 *
	 * @return string
	 */
	public function getName() {
		return $this->path === '/' ? '' : substr($this->path, strrpos($this->path, '/') + 1);
	}

	/**
	 * Sets the absolute path of this node
	 *
	 * @param string $path
	 * @param boolean $recursive
	 * @return void
	 * @throws \InvalidArgumentException if the given node path is invalid.
	 */
	public function setPath($path, $recursive = TRUE) {
		if (!is_string($path) || preg_match(NodeInterface::MATCH_PATTERN_PATH, $path) !== 1) {
			throw new \InvalidArgumentException('Invalid path "' . $path . '" (a path must be a valid string, be absolute (starting with a slash) and contain only the allowed characters).', 1284369857);
		}

		if ($path === $this->path) {
			return;
		}

		if ($recursive === TRUE) {
			/** @var $childNodeData NodeData */
			foreach ($this->getChildNodeData() as $childNodeData) {
				$childNodeData->setPath($path . '/' . $childNodeData->getName());
			}
		}

		$pathBeforeChange = $this->path;

		$this->path = $path;
		$this->calculatePathHash();
		if ($path === '/') {
			$this->parentPath = '';
			$this->depth = 0;
		} elseif (substr_count($path, '/') === 1) {
				$this->parentPath = '/';
		} else {
			$this->parentPath = substr($path, 0, strrpos($path, '/'));
		}
		$this->calculateParentPathHash();

		if ($pathBeforeChange !== NULL) {
			// this method is called both for changing the path AND in the constructor of Node; so we only want to do
			// these things below if called OUTSIDE a constructor.
			$this->emitNodePathChanged($this);
			$this->addOrUpdate();
		}
	}

	/**
	 * Returns the path of this node
	 *
	 * Example: /sites/mysitecom/homepage/about
	 *
	 * @return string The absolute node path
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * Returns the absolute path of this node with additional context information (such as the workspace name).
	 *
	 * Example: /sites/mysitecom/homepage/about@user-admin
	 *
	 * @return string Node path with context information
	 */
	public function getContextPath() {
		$contextPath = $this->path;
		$workspaceName = $this->workspace->getName();
		if ($workspaceName !== 'live') {
			$contextPath .= '@' . $workspaceName;
		}
		return $contextPath;
	}

	/**
	 * Returns the level at which this node is located.
	 * Counting starts with 0 for "/", 1 for "/foo", 2 for "/foo/bar" etc.
	 *
	 * @return integer
	 */
	public function getDepth() {
		if ($this->depth === NULL) {
			$this->depth = $this->path === '/' ? 0 : substr_count($this->path, '/');
		}
		return $this->depth;
	}

	/**
	 * Sets the workspace of this node.
	 *
	 *
	 * @param \TYPO3\TYPO3CR\Domain\Model\Workspace $workspace
	 * @return void
	 */
	public function setWorkspace(Workspace $workspace = NULL) {
		if ($this->workspace !== $workspace) {
			$this->workspace = $workspace;
			$this->addOrUpdate();
		}
	}

	/**
	 * Returns the workspace this node is contained in
	 *
	 * @return \TYPO3\TYPO3CR\Domain\Model\Workspace
	 */
	public function getWorkspace() {
		return $this->workspace;
	}

	/**
	 * Returns the identifier of this node.
	 *
	 * This UUID is not the same as the technical persistence identifier used by
	 * Flow's persistence framework. It is an additional identifier which is unique
	 * within the same workspace and is used for tracking the same node in across
	 * workspaces.
	 *
	 * It is okay and recommended to use this identifier for synchronisation purposes
	 * as it does not change even if all of the nodes content or its path changes.
	 *
	 * @return string the node's UUID
	 */
	public function getIdentifier() {
		return $this->identifier;
	}

	/**
	 * Sets the index of this node
	 *
	 * @param integer $index The new index
	 * @return void
	 */
	public function setIndex($index) {
		if ($this->index !== $index) {
			$this->index = $index;
			$this->addOrUpdate();
		}
	}

	/**
	 * Returns the index of this node which determines the order among siblings
	 * with the same parent node.
	 *
	 * @return integer
	 */
	public function getIndex() {
		return $this->index;
	}

	/**
	 * Returns the parent node of this node
	 *
	 * @return \TYPO3\TYPO3CR\Domain\Model\NodeData The parent node or NULL if this is the root node
	 */
	public function getParent() {
		if ($this->path === '/') {
			return NULL;
		}
		return $this->nodeDataRepository->findOneByPath($this->parentPath, $this->workspace);
	}

	/**
	 * Returns the parent node path
	 *
	 * @return string Absolute node path of the parent node
	 */
	public function getParentPath() {
		return $this->parentPath;
	}

	/**
	 * Creates, adds and returns a child node of this node. Also sets default
	 * properties and creates default subnodes.
	 *
	 * @param string $name Name of the new node
	 * @param \TYPO3\TYPO3CR\Domain\Model\NodeType $nodeType Node type of the new node (optional)
	 * @param string $identifier The identifier of the node, unique within the workspace, optional(!)
	 * @param \TYPO3\TYPO3CR\Domain\Model\Workspace $workspace
	 * @param array $dimensions
	 * @return \TYPO3\TYPO3CR\Domain\Model\NodeData
	 */
	public function createNodeData($name, NodeType $nodeType = NULL, $identifier = NULL, Workspace $workspace = NULL, array $dimensions = NULL) {
		$newNodeData = $this->createSingleNodeData($name, $nodeType, $identifier, $workspace, $dimensions);
		if ($nodeType !== NULL) {
			foreach ($nodeType->getDefaultValuesForProperties() as $propertyName => $propertyValue) {
				if (substr($propertyName, 0, 1) === '_') {
					ObjectAccess::setProperty($newNodeData, substr($propertyName, 1), $propertyValue);
				} else {
					$newNodeData->setProperty($propertyName, $propertyValue);
				}
			}

			foreach ($nodeType->getAutoCreatedChildNodes() as $childNodeName => $childNodeType) {
				$newNodeData->createNodeData($childNodeName, $childNodeType, NULL, $workspace, $dimensions);
			}
		}
		return $newNodeData;
	}

	/**
	 * Creates, adds and returns a child node of this node, without setting default
	 * properties or creating subnodes.
	 *
	 * @param string $name Name of the new node
	 * @param \TYPO3\TYPO3CR\Domain\Model\NodeType $nodeType Node type of the new node (optional)
	 * @param string $identifier The identifier of the node, unique within the workspace, optional(!)
	 * @param \TYPO3\TYPO3CR\Domain\Model\Workspace $workspace
	 * @param array $dimensions An array of dimension name to dimension values
	 * @throws NodeExistsException if a node with this path already exists.
	 * @throws \InvalidArgumentException if the node name is not accepted.
	 * @return \TYPO3\TYPO3CR\Domain\Model\NodeData
	 */
	public function createSingleNodeData($name, NodeType $nodeType = NULL, $identifier = NULL, Workspace $workspace = NULL, array $dimensions = NULL) {
		if (!is_string($name) || preg_match(NodeInterface::MATCH_PATTERN_NAME, $name) !== 1) {
			throw new \InvalidArgumentException('Invalid node name "' . $name . '" (a node name must only contain characters, numbers and the "-" sign).', 1292428697);
		}

		$nodeWorkspace = $workspace ? : $this->workspace;
		$newPath = $this->path . ($this->path === '/' ? '' : '/') . $name;
		if ($this->nodeDataRepository->findOneByPath($newPath, $nodeWorkspace, $dimensions) !== NULL) {
			throw new NodeExistsException(sprintf('Node with path "' . $newPath . '" already exists in workspace %s and given dimensions %s.', $nodeWorkspace->getName(), var_export($dimensions, TRUE)), 1292503471);
		}

		$newNodeData = new NodeData($newPath, $nodeWorkspace, $identifier, $dimensions);
		if ($nodeType !== NULL) {
			$newNodeData->setNodeType($nodeType);
		}
		$this->nodeDataRepository->setNewIndex($newNodeData, NodeDataRepository::POSITION_LAST);

		return $newNodeData;
	}

	/**
	 * Creates and persists a node from the given $nodeTemplate as child node
	 *
	 * @param \TYPO3\TYPO3CR\Domain\Model\NodeTemplate $nodeTemplate
	 * @param string $nodeName name of the new node. If not specified the name of the nodeTemplate will be used.
	 * @param \TYPO3\TYPO3CR\Domain\Model\Workspace $workspace
	 * @param array $dimensions
	 * @return \TYPO3\TYPO3CR\Domain\Model\NodeData the freshly generated node
	 */
	public function createNodeDataFromTemplate(NodeTemplate $nodeTemplate, $nodeName = NULL, Workspace $workspace = NULL, array $dimensions = NULL) {
		$newNodeName = $nodeName !== NULL ? $nodeName : $nodeTemplate->getName();

		$possibleNodeName = $newNodeName;
		$counter = 1;
		while ($this->getNode($possibleNodeName) !== NULL) {
			$possibleNodeName = $newNodeName . '-' . $counter++;
		}

		$newNodeData = $this->createNodeData($possibleNodeName, $nodeTemplate->getNodeType(), $nodeTemplate->getIdentifier(), $workspace, $dimensions);
		$newNodeData->similarize($nodeTemplate);
		return $newNodeData;
	}

	/**
	 * Returns a node specified by the given relative path.
	 *
	 * @param string $path Path specifying the node, relative to this node
	 * @return \TYPO3\TYPO3CR\Domain\Model\NodeData The specified node or NULL if no such node exists
	 *
	 * @TODO This method should be removed, since it doesn't take the context into account correctly
	 */
	public function getNode($path) {
		return $this->nodeDataRepository->findOneByPath($this->normalizePath($path), $this->workspace);
	}

	/**
	 * Change the identifier of this node data
	 *
	 * NOTE: This is only used for some very rare cases (to replace existing instances when moving).
	 *
	 * @param string $identifier
	 * @return void
	 */
	public function setIdentifier($identifier) {
		$this->identifier = $identifier;
	}

	/**
	 * Returns all direct child node data of this node data without reducing the result (multiple variants can be returned)
	 *
	 * @return array<\TYPO3\TYPO3CR\Domain\Model\NodeData>
	 */
	protected function getChildNodeData() {
		return $this->nodeDataRepository->findByParentWithoutReduce($this->path, $this->workspace);
	}

	/**
	 * Returns the number of child nodes a similar getChildNodes() call would return.
	 *
	 * @param string $nodeTypeFilter If specified, only nodes with that node type are considered
	 * @param Workspace $workspace
	 * @param array $dimensions
	 * @return integer The number of child nodes
	 */
	public function getNumberOfChildNodes($nodeTypeFilter = NULL, Workspace $workspace, array $dimensions) {
		return $this->nodeDataRepository->countByParentAndNodeType($this->path, $nodeTypeFilter, $workspace, $dimensions);
	}

	/**
	 * Removes this node and all its child nodes.
	 *
	 * @return void
	 */
	public function remove() {
		if ($this->workspace->getBaseWorkspace() === NULL) {
			$this->nodeDataRepository->remove($this);
		} else {
			$this->removed = TRUE;
		}
	}

	/**
	 * Enables using the remove method when only setters are available
	 *
	 * @param boolean $removed If TRUE, this node and it's child nodes will be removed. Cannot handle FALSE (yet).
	 * @return void
	 */
	public function setRemoved($removed) {
		if ((boolean)$removed === TRUE) {
			$this->removed = TRUE;
			$this->remove();
		} else {
			$this->removed = FALSE;
		}
		$this->addOrUpdate();
	}

	/**
	 * If this node is a removed node.
	 *
	 * @return boolean
	 */
	public function isRemoved() {
		return $this->removed;
	}

	/**
	 * Tells if this node is "visible".
	 *
	 * For this the "hidden" flag and the "hiddenBeforeDateTime" and "hiddenAfterDateTime" dates are taken into account.
	 * The fact that a node is "visible" does not imply that it can / may be shown to the user. Further modifiers
	 * such as isAccessible() need to be evaluated.
	 *
	 * @return boolean
	 */
	public function isVisible() {
		if ($this->hidden === TRUE) {
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Tells if this node may be accessed according to the current security context.
	 *
	 * @return boolean
	 */
	public function isAccessible() {
		if ($this->hasAccessRestrictions() === FALSE) {
			return TRUE;
		}

		if ($this->securityContext->canBeInitialized() === FALSE) {
			return TRUE;
		}

		foreach ($this->accessRoles as $roleName) {
			if ($this->securityContext->hasRole($roleName)) {
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * Tells if a node, in general, has access restrictions, independent of the
	 * current security context.
	 *
	 * @return boolean
	 */
	public function hasAccessRestrictions() {
		if (!is_array($this->accessRoles) || empty($this->accessRoles)) {
			return FALSE;
		}
		if (count($this->accessRoles) === 1 && in_array('TYPO3.Flow:Everybody', $this->accessRoles)) {
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Internal use, do not retrieve collection directly
	 *
	 * @return array<NodeDimension>
	 */
	public function getDimensions() {
		return $this->dimensions->toArray();
	}

	/**
	 * Internal use, do not manipulate collection directly
	 *
	 * @param array $dimensions
	 * @return void
	 */
	public function setDimensions(array $dimensions) {
		$this->dimensions->clear();
		foreach ($dimensions as $dimension) {
			$this->dimensions->add($dimension);
		}
		$this->buildDimensionValues();
	}

	/**
	 * @return NodeData
	 */
	public function getMovedTo() {
		return $this->movedTo;
	}

	/**
	 * @param NodeData $nodeData
	 * @return void
	 */
	public function setMovedTo(NodeData $nodeData = NULL) {
		$this->movedTo = $nodeData;
	}

	/**
	 * Make the node "similar" to the given source node. That means,
	 *  - all properties
	 *  - index
	 *  - node type
	 *  - content object
	 * will be set to the same values as in the source node.
	 *
	 * @param \TYPO3\TYPO3CR\Domain\Model\AbstractNodeData $sourceNode
	 * @return void
	 */
	public function similarize(AbstractNodeData $sourceNode) {
		$this->properties = array();
		foreach ($sourceNode->getProperties(TRUE) as $propertyName => $propertyValue) {
			$this->setProperty($propertyName, $propertyValue);
		}

		$propertyNames = array(
			'nodeType', 'hidden', 'hiddenAfterDateTime',
			'hiddenBeforeDateTime', 'hiddenInIndex', 'accessRoles'
		);
		if ($sourceNode instanceof NodeData) {
			$propertyNames[] = 'index';
		}
		foreach ($propertyNames as $propertyName) {
			ObjectAccess::setProperty($this, $propertyName, ObjectAccess::getProperty($sourceNode, $propertyName));
		}

		$contentObject = $sourceNode->getContentObject();
		if ($contentObject !== NULL) {
			$this->setContentObject($contentObject);
		}
	}

	/**
	 * Normalizes the given path and returns an absolute path
	 *
	 * @param string $path The non-normalized path
	 * @return string The normalized absolute path
	 * @throws \InvalidArgumentException if your node path contains two consecutive slashes.
	 */
	public function normalizePath($path) {
		if ($path === '.') {
			return $this->path;
		}

		if (!is_string($path)) {
			throw new \InvalidArgumentException(sprintf('An invalid node path was specified: is of type %s but a string is expected.', gettype($path)), 1357832901);
		}

		if (strpos($path, '//') !== FALSE) {
			throw new \InvalidArgumentException('Paths must not contain two consecutive slashes.', 1291371910);
		}

		if ($path[0] === '/') {
			$absolutePath = $path;
		} else {
			$absolutePath = ($this->path === '/' ? '' : $this->path) . '/' . $path;
		}
		$pathSegments = explode('/', $absolutePath);
		while (each($pathSegments)) {
			if (current($pathSegments) === '..') {
				prev($pathSegments);
				unset($pathSegments[key($pathSegments)]);
				unset($pathSegments[key($pathSegments)]);
				prev($pathSegments);
			} elseif (current($pathSegments) === '.') {
				unset($pathSegments[key($pathSegments)]);
				prev($pathSegments);
			}
		}
		$normalizedPath = implode('/', $pathSegments);
		return ($normalizedPath === '') ? '/' : $normalizedPath;
	}

	/**
	 * Returns the dimensions and their values.
	 *
	 * @return array
	 */
	public function getDimensionValues() {
		return $this->dimensionValues;
	}

	/**
	 * Build a cached array of dimension values and a hash to search for it.
	 *
	 * @return void
	 */
	protected function buildDimensionValues() {
		$dimensionValues = array();
		foreach ($this->dimensions as $dimension) {
			/** @var NodeDimension $dimension */
			$dimensionValues[$dimension->getName()][] = $dimension->getValue();
		}
		$this->dimensionsHash = self::sortDimensionValueArrayAndReturnDimensionsHash($dimensionValues);
		$this->dimensionValues = $dimensionValues;
	}

	/**
	 * Sorts the incoming $dimensionValues array to make sure that before hashing, the ordering is made deterministic.
	 * Then, calculates and returns the dimensionsHash.
	 *
	 * This method is public because it is used inside SiteImportService.
	 *
	 * @param array $dimensionValues, which will be ordered alphabetically
	 * @return string the calculated DimensionsHash
	 */
	public static function sortDimensionValueArrayAndReturnDimensionsHash(array &$dimensionValues) {
		foreach ($dimensionValues as &$values) {
			sort($values);
		}
		ksort($dimensionValues);

		return md5(json_encode($dimensionValues));
	}

	/**
	 * Get a unique string for all dimension values
	 *
	 * Internal method
	 *
	 * @return string
	 */
	public function getDimensionsHash() {
		return $this->dimensionsHash;
	}

	/**
	 * Checks if this instance matches the given workspace and dimensions.
	 *
	 * @param Workspace $workspace
	 * @param array $dimensions
	 * @return boolean
	 */
	public function matchesWorkspaceAndDimensions($workspace, array $dimensions = NULL) {
		if ($this->workspace->getName() !== $workspace->getName()) {
			return FALSE;
		}
		if ($dimensions !== NULL) {
			$nodeDimensionValues = $this->dimensionValues;
			foreach ($dimensions as $dimensionName => $dimensionValues) {
				if (!isset($nodeDimensionValues[$dimensionName]) || array_intersect($nodeDimensionValues[$dimensionName], $dimensionValues) === array()) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	/**
	 * Check if this NodeData object is a purely internal technical object (like a shadow node).
	 * An internal NodeData should never produce a Node object.
	 *
	 * @return boolean
	 */
	public function isInternal() {
		return $this->movedTo !== NULL && $this->removed === TRUE;
	}

	/**
	 * Move this NodeData to the given path and workspace.
	 *
	 * Basically 4 scenarios have to be covered here, depending on:
	 *
	 * - Does the NodeData have to be materialized (adapted to the workspace or target dimension)?
	 * - Does a shadow node exist on the target path?
	 *
	 * Because unique key constraints and Doctrine ORM don't support arbitrary removal and update combinations,
	 * existing NodeData instances are re-used and the metadata and content is swapped around.
	 *
	 * @param string $path
	 * @param Workspace $workspace
	 * @return NodeData If a shadow node was created this is the new NodeData object after the move.
	 */
	public function move($path, $workspace) {
		$nodeData = $this;
		$originalPath = $this->path;
		if ($originalPath ===  $path) {
			return $this;
		}

		$targetPathShadowNodeData = $this->getExistingShadowNodeData($path, $workspace, $nodeData->getDimensionValues());

		if ($this->workspace->getName() !== $workspace->getName()) {
			if ($targetPathShadowNodeData === NULL) {
				// If there is no shadow node at the target path we need to materialize before moving and create a shadow node.
				$nodeData = $this->materializeToWorkspace($workspace);
				$nodeData->setPath($path, FALSE);
				$movedNodeData = $nodeData;
			} else {
				// The existing shadow node on the target path will be used as the moved node. We don't need to materialize into the workspace.
				$movedNodeData = $targetPathShadowNodeData;
				$movedNodeData->setAsShadowOf(NULL);
				$movedNodeData->setIdentifier($nodeData->getIdentifier());
				$movedNodeData->similarize($nodeData);
				// A new shadow node will be created for the node data that references the recycled, existing shadow node
			}
			$movedNodeData->createShadow($originalPath);
		} else {
			$referencedShadowNode = $this->nodeDataRepository->findOneByMovedTo($nodeData);
			if ($targetPathShadowNodeData === NULL) {
				if ($referencedShadowNode === NULL) {
					// There is no shadow node on the original or target path, so the current node data will be turned to a shadow node and a new node data will be created for the moved node.
					// We cannot just create a new shadow node, since the order of Doctrine queries would cause unique key conflicts
					$movedNodeData = new NodeData($path, $nodeData->getWorkspace(), $nodeData->getIdentifier(), $nodeData->getDimensionValues());
					$movedNodeData->similarize($nodeData);
					$this->addOrUpdate($movedNodeData);

					$shadowNodeData = $nodeData;
					$shadowNodeData->setAsShadowOf($movedNodeData);
				} else {
					// A shadow node that references this node data already exists, so we just move the current node data
					$movedNodeData = $nodeData;
					$movedNodeData->setPath($path, FALSE);
				}
			} else {
				if ($referencedShadowNode === NULL) {
					// Turn the target path shadow node into the moved node (and adjust the identifier!)
					// Do not reset the movedTo property to keep tracing the original move operation - TODO: Does that make sense if the identifier changes?
					$movedNodeData = $targetPathShadowNodeData;
					// Since the shadow node at the target path does not belong to the current node, we have to adjust the identifier
					$movedNodeData->setRemoved(FALSE);
					$movedNodeData->setIdentifier($nodeData->getIdentifier());
					$movedNodeData->similarize($nodeData);
					// Create a shadow node from the current node that shadows the recycled node
					$shadowNodeData = $nodeData;
					$shadowNodeData->setAsShadowOf($movedNodeData);
				} else {
					// If there is already shadow node on the target path, we need to make that shadow node the actual moved node and remove the current node data (which cannot be live).
					// We cannot remove the shadow node and update the current node data, since the order of Doctrine queries would cause unique key conflicts.
					$movedNodeData = $targetPathShadowNodeData;
					$movedNodeData->setAsShadowOf(NULL);
					$movedNodeData->similarize($nodeData);
					$movedNodeData->setPath($path, FALSE);
					$this->nodeDataRepository->remove($nodeData);
				}
			}
		}

		return $movedNodeData;
	}

	/**
	 * Find an existing shadow node data on the given path for the current node data of the node (used by setPath)
	 *
	 * @param string $path The (new) path of the node data
	 * @param Workspace $workspace
	 * @param array $dimensionValues
	 * @return NodeData
	 */
	protected function getExistingShadowNodeData($path, $workspace, $dimensionValues) {
		$existingShadowNode = $this->nodeDataRepository->findOneByPath($path, $workspace, $dimensionValues, TRUE);
		if ($existingShadowNode !== NULL && $existingShadowNode->getMovedTo() !== NULL) {
			return $existingShadowNode;
		}
		return NULL;
	}

	/**
	 * Create a shadow NodeData at the given path with the same workspace and dimensions as this
	 *
	 * Note: The constructor will already add the new object to the repository
	 *
	 * @param string $path The (original) path for the node data
	 * @return NodeData
	 */
	public function createShadow($path) {
		$shadowNode = new NodeData($path, $this->workspace, $this->identifier, $this->dimensionValues);
		$shadowNode->similarize($this);
		$shadowNode->setAsShadowOf($this);
		return $shadowNode;
	}

	/**
	 * This becomes a shdow of the given NodeData object.
	 * If NULL or no argument is given then movedTo is nulled and removed is set to FALSE effectively turning this into a normal NodeData.
	 *
	 * @param NodeData $nodeData
	 * @return void
	 */
	public function setAsShadowOf(NodeData $nodeData = NULL) {
		$this->setMovedTo($nodeData);
		$this->setRemoved(($nodeData !== NULL));
		$this->addOrUpdate();
	}

	/**
	 * Materializes the original node data (of a different workspace) into the current
	 * workspace, excluding content dimensions
	 *
	 * This is only used in setPath for now
	 *
	 * @param Workspace $workspace
	 * @return NodeData
	 */
	protected function materializeToWorkspace($workspace) {
		$newNodeData = new NodeData($this->path, $workspace, $this->identifier, $this->dimensionValues);
		$this->nodeDataRepository->add($newNodeData);

		$newNodeData->similarize($this);
		return $newNodeData;
	}

	/**
	 * Adds this node to the Node Repository or updates it if it has been added earlier
	 *
	 * @param NodeData $nodeData Other NodeData object to addOrUpdate
	 * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
	 */
	protected function addOrUpdate(NodeData $nodeData = NULL) {
		$nodeData = ($nodeData === NULL ? $this : $nodeData);

		// If this NodeData was previously removed and is in live workspace we don't want to add it again to persistence.
		if ($nodeData->isRemoved() && $this->workspace->getBaseWorkspace() === NULL) {
			// Just in case, normally setRemoved() should have already removed the object from the identityMap.
			if ($this->persistenceManager->isNewObject($nodeData) === FALSE) {
				$nodeData->remove();
			}
		} else {
			if ($this->persistenceManager->isNewObject($nodeData)) {
				$this->nodeDataRepository->add($nodeData);
			} else {
				$this->nodeDataRepository->update($nodeData);
			}
		}
	}

	/**
	 * Updates the attached content object
	 *
	 * @param object $contentObject
	 * @return void
	 */
	protected function updateContentObject($contentObject) {
		$this->persistenceManager->update($contentObject);
	}

	/**
	 * Calculates the hash corresponding to the path of this instance.
	 *
	 * @return void
	 */
	protected function calculatePathHash() {
		$this->pathHash = md5($this->path);
	}

	/**
	 * Calculates the hash corresponding to the dimensions and their values for this instance.
	 *
	 * @return void
	 */
	protected function calculateParentPathHash() {
		$this->parentPathHash = md5($this->parentPath);
	}

	/**
	 * Create a fresh collection instance and clone dimensions
	 *
	 * @return void
	 */
	public function __clone() {
		if ($this->dimensions instanceof Collection) {
			$existingDimensions = $this->dimensions->toArray();
			$this->dimensions = new ArrayCollection();
			/** @var NodeDimension $existingDimension */
			foreach ($existingDimensions as $existingDimension) {
				$this->dimensions->add(new NodeDimension($this, $existingDimension->getName(), $existingDimension->getValue()));
			}
		}
	}

	/**
	 * Signals that a node has changed its path.
	 *
	 * @Flow\Signal
	 * @param NodeData $nodeData the node data instance that has been changed
	 * @return void
	 */
	protected function emitNodePathChanged(NodeData $nodeData) {
	}
}
namespace TYPO3\TYPO3CR\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * The node data inside the content repository. This is only a data
 * container that could be exchanged in the future.
 * 
 * NOTE: This is internal only and should not be used or extended by userland code.
 * @\TYPO3\Flow\Annotations\Entity
 * @\Doctrine\ORM\Mapping\Table(indexes={ @\Doctrine\ORM\Mapping\Index(name="parentpath_sortingindex", columns={ "parentpathhash", "sortingindex" }), @\Doctrine\ORM\Mapping\Index(name="identifierindex", columns={ "identifier" }), @\Doctrine\ORM\Mapping\Index(name="nodetypeindex", columns={ "nodetype" }) }, uniqueConstraints={ @\Doctrine\ORM\Mapping\UniqueConstraint(name="path_workspace_dimensions", columns={ "pathhash", "workspace", "dimensionshash" }), @\Doctrine\ORM\Mapping\UniqueConstraint(name="identifier_workspace_dimensions_movedto", columns={ "identifier", "workspace", "dimensionshash", "movedto" }) })
 */
class NodeData extends NodeData_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface, \TYPO3\Flow\Persistence\Aspect\PersistenceMagicInterface {

	/**
	 * @var string
	 * @ORM\Id
	 * @ORM\Column(length=40)
	 * introduced by TYPO3\Flow\Persistence\Aspect\PersistenceMagicAspect
	 */
	protected $Persistence_Object_Identifier = NULL;

	private $Flow_Aop_Proxy_targetMethodsAndGroupedAdvices = array();

	private $Flow_Aop_Proxy_groupedAdviceChains = array();

	private $Flow_Aop_Proxy_methodIsInAdviceMode = array();


	/**
	 * Autogenerated Proxy Method
	 * @param string $path Absolute path of this node
	 * @param \TYPO3\TYPO3CR\Domain\Model\Workspace $workspace The workspace this node will be contained in
	 * @param string $identifier The node identifier (not the persistence object identifier!). Specifying this only makes sense while creating corresponding nodes
	 * @param array $dimensions An array of dimension name to dimension values
	 */
	public function __construct() {
		$arguments = func_get_args();

		$this->Flow_Aop_Proxy_buildMethodsAndAdvicesArray();

			if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['__construct'])) {

		if (!array_key_exists(0, $arguments)) $arguments[0] = NULL;
		if (!array_key_exists(1, $arguments)) $arguments[1] = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Model\Workspace');
		if (!array_key_exists(2, $arguments)) $arguments[2] = NULL;
		if (!array_key_exists(3, $arguments)) $arguments[3] = NULL;
		if (!array_key_exists(0, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $path in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		if (!array_key_exists(1, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $workspace in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		call_user_func_array('parent::__construct', $arguments);

			} else {
				$this->Flow_Aop_Proxy_methodIsInAdviceMode['__construct'] = TRUE;
				try {
				
					$methodArguments = array();

				if (array_key_exists(0, $arguments)) $methodArguments['path'] = $arguments[0];
				if (array_key_exists(1, $arguments)) $methodArguments['workspace'] = $arguments[1];
				if (array_key_exists(2, $arguments)) $methodArguments['identifier'] = $arguments[2];
				if (array_key_exists(3, $arguments)) $methodArguments['dimensions'] = $arguments[3];
			
				if (isset($this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices['__construct']['TYPO3\Flow\Aop\Advice\BeforeAdvice'])) {
					$advices = $this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices['__construct']['TYPO3\Flow\Aop\Advice\BeforeAdvice'];
					$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\TYPO3CR\Domain\Model\NodeData', '__construct', $methodArguments);
					foreach ($advices as $advice) {
						$advice->invoke($joinPoint);
					}

					$methodArguments = $joinPoint->getMethodArguments();
				}

				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\TYPO3CR\Domain\Model\NodeData', '__construct', $methodArguments);
				$result = $this->Flow_Aop_Proxy_invokeJoinPoint($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

				} catch (\Exception $e) {
					unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['__construct']);
					throw $e;
				}
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['__construct']);
				return;
			}
		if ('TYPO3\TYPO3CR\Domain\Model\NodeData' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 protected function Flow_Aop_Proxy_buildMethodsAndAdvicesArray() {
		if (method_exists(get_parent_class($this), 'Flow_Aop_Proxy_buildMethodsAndAdvicesArray') && is_callable('parent::Flow_Aop_Proxy_buildMethodsAndAdvicesArray')) parent::Flow_Aop_Proxy_buildMethodsAndAdvicesArray();

		$objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager;
		$this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices = array(
			'__construct' => array(
				'TYPO3\Flow\Aop\Advice\BeforeAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\BeforeAdvice('TYPO3\Flow\Persistence\Aspect\PersistenceMagicAspect', 'generateUuid', $objectManager, NULL),
				),
			),
			'__clone' => array(
				'TYPO3\Flow\Aop\Advice\BeforeAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\BeforeAdvice('TYPO3\Flow\Persistence\Aspect\PersistenceMagicAspect', 'generateUuid', $objectManager, NULL),
				),
				'TYPO3\Flow\Aop\Advice\AfterReturningAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AfterReturningAdvice('TYPO3\Flow\Persistence\Aspect\PersistenceMagicAspect', 'cloneObject', $objectManager, NULL),
				),
			),
			'emitNodePathChanged' => array(
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
	 * @return void
	 */
	 public function __clone() {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['__clone'])) {
		$result = parent::__clone();

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['__clone'] = TRUE;
			try {
			
					$methodArguments = array();

				if (isset($this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices['__clone']['TYPO3\Flow\Aop\Advice\BeforeAdvice'])) {
					$advices = $this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices['__clone']['TYPO3\Flow\Aop\Advice\BeforeAdvice'];
					$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\TYPO3CR\Domain\Model\NodeData', '__clone', $methodArguments);
					foreach ($advices as $advice) {
						$advice->invoke($joinPoint);
					}

					$methodArguments = $joinPoint->getMethodArguments();
				}

				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\TYPO3CR\Domain\Model\NodeData', '__clone', $methodArguments);
				$result = $this->Flow_Aop_Proxy_invokeJoinPoint($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

				if (isset($this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices['__clone']['TYPO3\Flow\Aop\Advice\AfterReturningAdvice'])) {
					$advices = $this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices['__clone']['TYPO3\Flow\Aop\Advice\AfterReturningAdvice'];
					$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\TYPO3CR\Domain\Model\NodeData', '__clone', $methodArguments, NULL, $result);
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
	 * @param NodeData $nodeData the node data instance that has been changed
	 * @return void
	 * @\TYPO3\Flow\Annotations\Signal
	 */
	 protected function emitNodePathChanged(\TYPO3\TYPO3CR\Domain\Model\NodeData $nodeData) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['emitNodePathChanged'])) {
		$result = parent::emitNodePathChanged($nodeData);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['emitNodePathChanged'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['nodeData'] = $nodeData;
			
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\TYPO3CR\Domain\Model\NodeData', 'emitNodePathChanged', $methodArguments);
				$result = $this->Flow_Aop_Proxy_invokeJoinPoint($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

				if (isset($this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices['emitNodePathChanged']['TYPO3\Flow\Aop\Advice\AfterReturningAdvice'])) {
					$advices = $this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices['emitNodePathChanged']['TYPO3\Flow\Aop\Advice\AfterReturningAdvice'];
					$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\TYPO3CR\Domain\Model\NodeData', 'emitNodePathChanged', $methodArguments, NULL, $result);
					foreach ($advices as $advice) {
						$advice->invoke($joinPoint);
					}

					$methodArguments = $joinPoint->getMethodArguments();
				}

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['emitNodePathChanged']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['emitNodePathChanged']);
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
	$reflectedClass = new \ReflectionClass('TYPO3\TYPO3CR\Domain\Model\NodeData');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\TYPO3CR\Domain\Model\NodeData', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\TYPO3CR\Domain\Model\NodeData', $propertyName, 'var');
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
		$securityContext_reference = &$this->securityContext;
		$this->securityContext = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Security\Context');
		if ($this->securityContext === NULL) {
			$this->securityContext = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('48836470c14129ade5f39e28c4816673', $securityContext_reference);
			if ($this->securityContext === NULL) {
				$this->securityContext = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('48836470c14129ade5f39e28c4816673',  $securityContext_reference, 'TYPO3\Flow\Security\Context', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Security\Context'); });
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
		$nodeDataRepository_reference = &$this->nodeDataRepository;
		$this->nodeDataRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Repository\NodeDataRepository');
		if ($this->nodeDataRepository === NULL) {
			$this->nodeDataRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('6d8e58e235099c88f352e23317321129', $nodeDataRepository_reference);
			if ($this->nodeDataRepository === NULL) {
				$this->nodeDataRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('6d8e58e235099c88f352e23317321129',  $nodeDataRepository_reference, 'TYPO3\TYPO3CR\Domain\Repository\NodeDataRepository', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Repository\NodeDataRepository'); });
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
$this->Flow_Injected_Properties = array (
  0 => 'securityContext',
  1 => 'persistenceManager',
  2 => 'nodeDataRepository',
  3 => 'nodeTypeManager',
);
	}
}
#