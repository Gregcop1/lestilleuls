<?php 
namespace TYPO3\Neos\Service\Controller;

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
use TYPO3\Neos\Domain\Service\NodeSearchService;
use TYPO3\Neos\Service\View\NodeView;
use TYPO3\Neos\View\TypoScriptView;
use TYPO3\TYPO3CR\Domain\Factory\NodeFactory;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Model\Node;
use TYPO3\TYPO3CR\Domain\Repository\NodeDataRepository;
use TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface;
use TYPO3\TYPO3CR\Domain\Service\NodeTypeManager;
use TYPO3\TYPO3CR\Exception\NodeException;
use TYPO3\TYPO3CR\TypeConverter\NodeConverter;

/**
 * Service Controller for managing Nodes
 *
 * Note: This controller should be, step-by-step, transformed into a clean REST controller (see NEOS-190 and NEOS-199).
 *       Since this is a rather big endeavor, we slice the elephant and move methods in a clean way from here to the
 *       new NodesController (\TYPO3\Neos\Controller\Service\NodesController)
 */
class NodeController_Original extends AbstractServiceController {

	/**
	 * @var NodeView
	 */
	protected $view;

	/**
	 * @var string
	 */
	protected $defaultViewObjectName = 'TYPO3\Neos\Service\View\NodeView';

	/**
	 * @Flow\Inject
	 * @var NodeTypeManager
	 */
	protected $nodeTypeManager;

	/**
	 * @Flow\Inject
	 * @var NodeSearchService
	 */
	protected $nodeSearchService;

	/**
	 * @Flow\Inject
	 * @var NodeFactory
	 */
	protected $nodeFactory;

	/**
	 * @Flow\Inject
	 * @var ContextFactoryInterface
	 */
	protected $contextFactory;

	/**
	 * @Flow\Inject
	 * @var NodeDataRepository
	 */
	protected $nodeDataRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Neos\Service\NodeOperations
	 */
	protected $nodeOperations;

	/**
	 * Select special error action
	 *
	 * @return void
	 */
	protected function initializeAction() {
		if ($this->arguments->hasArgument('referenceNode')) {
			$this->arguments->getArgument('referenceNode')->getPropertyMappingConfiguration()->setTypeConverterOption('TYPO3\TYPO3CR\TypeConverter\NodeConverter', NodeConverter::REMOVED_CONTENT_SHOWN, TRUE);
		}
		$this->uriBuilder->setRequest($this->request->getMainRequest());
	}

	#
	# Actions which are not yet refactored to REST below (see NEOS-199):
	#

	/**
	 * Return child nodes of specified node for usage in a TreeLoader
	 *
	 * @param Node $node The node to find child nodes for
	 * @param string $nodeTypeFilter A node type filter
	 * @param integer $depth levels of childNodes (0 = unlimited)
	 * @param Node $untilNode expand the child nodes until $untilNode is reached, independent of $depth
	 * @return void
	 */
	public function getChildNodesForTreeAction(Node $node, $nodeTypeFilter, $depth, Node $untilNode) {
		$this->view->assignChildNodes($node, $nodeTypeFilter, NodeView::STYLE_TREE, $depth, $untilNode);
	}

	/**
	 * Return child nodes of specified node for usage in a TreeLoader based on filter
	 *
	 * @param Node $node The node to find child nodes for
	 * @param string $term
	 * @param string $nodeType
	 * @return void
	 */
	public function filterChildNodesForTreeAction(Node $node, $term, $nodeType) {
		$nodeTypes = strlen($nodeType) > 0 ? array($nodeType) : array_keys($this->nodeTypeManager->getSubNodeTypes('TYPO3.Neos:Document', FALSE));
		$context = $node->getContext();
		if ($term !== '') {
			$nodes = $this->nodeSearchService->findByProperties($term, $nodeTypes, $context, $node);
		} else {
			$nodes = array();
			$nodeDataRecords = $this->nodeDataRepository->findByParentAndNodeTypeRecursively($node->getPath(), implode(',', $nodeTypes), $context->getWorkspace(), $context->getDimensions());
			foreach ($nodeDataRecords as $nodeData) {
				$node = $this->nodeFactory->createFromNodeData($nodeData, $context);
				if ($node !== NULL) {
					$nodes[$node->getPath()] = $node;
				}
			}
		}
		$this->view->assignFilteredChildNodes(
			$node,
			$nodes
		);
	}

	/**
	 * Creates a new node
	 *
	 * We need to call persistEntities() in order to return the nextUri.
	 *
	 * @param Node $referenceNode
	 * @param array $nodeData
	 * @param string $position where the node should be added (allowed: before, into, after)
	 * @return void
	 * @throws \InvalidArgumentException
	 */
	public function createAction(Node $referenceNode, array $nodeData, $position) {
		$newNode = $this->nodeOperations->create($referenceNode, $nodeData, $position);

		$this->nodeDataRepository->persistEntities();

		$nextUri = $this->uriBuilder->reset()->setFormat('html')->setCreateAbsoluteUri(TRUE)->uriFor('show', array('node' => $newNode), 'Frontend\Node', 'TYPO3.Neos');
		$this->view->assign('value', array('data' => array('nextUri' => $nextUri), 'success' => TRUE));
	}

	/**
	 * Creates a new node and renders the node inside the containing section
	 *
	 * @param Node $referenceNode
	 * @param string $typoScriptPath The TypoScript path of the collection
	 * @param array $nodeData
	 * @param string $position where the node should be added (allowed: before, into, after)
	 * @return string
	 * @throws \InvalidArgumentException
	 */
	public function createAndRenderAction(Node $referenceNode, $typoScriptPath, array $nodeData, $position) {
		$newNode = $this->nodeOperations->create($referenceNode, $nodeData, $position);

		$view = new TypoScriptView();
		$this->controllerContext->getRequest()->setFormat('html');
		$view->setControllerContext($this->controllerContext);
		$view->setOption('enableContentCache', FALSE);

		$view->setTypoScriptPath($typoScriptPath);
		$view->assign('value', $newNode->getParent());

		$result = $view->render();
		$this->response->setContent(json_encode((object)array('collectionContent' => $result, 'nodePath' => $newNode->getContextPath())));

		return '';
	}

	/**
	 * Creates a new node and returns tree structure
	 *
	 * @param Node $referenceNode
	 * @param array $nodeData
	 * @param string $position where the node should be added, -1 is before, 0 is in, 1 is after
	 * @return void
	 * @throws \InvalidArgumentException
	 */
	public function createNodeForTheTreeAction(Node $referenceNode, array $nodeData, $position) {
		$newNode = $this->nodeOperations->create($referenceNode, $nodeData, $position);
		$this->view->assign('value', array('data' => $this->view->collectTreeNodeData($newNode), 'success' => TRUE));
	}

	/**
	 * Move $node before, into or after $targetNode
	 *
	 * @param Node $node
	 * @param Node $targetNode
	 * @param string $position where the node should be added (allowed: before, into, after)
	 * @return void
	 * @throws NodeException
	 */
	public function moveAction(Node $node, Node $targetNode, $position) {
		$node = $this->nodeOperations->move($node, $targetNode, $position);

		$this->nodeDataRepository->persistEntities();

		$data = array('newNodePath' => $node->getContextPath());
		if ($node->getNodeType()->isOfType('TYPO3.Neos:Document')) {
			$data['nextUri'] = $this->uriBuilder->reset()->setFormat('html')->setCreateAbsoluteUri(TRUE)->uriFor('show', array('node' => $node), 'Frontend\Node', 'TYPO3.Neos');
		}
		$this->view->assign('value', array('data' => $data, 'success' => TRUE));
	}

	/**
	 * Copy $node before, into or after $targetNode
	 *
	 * @param Node $node the node to be copied
	 * @param Node $targetNode the target node to be copied "to", see $position
	 * @param string $position where the node should be added in relation to $targetNode (allowed: before, into, after)
	 * @param string $nodeName optional node name (if empty random node name will be generated)
	 * @return void
	 * @throws NodeException
	 */
	public function copyAction(Node $node, Node $targetNode, $position, $nodeName = NULL) {
		$copiedNode = $this->nodeOperations->copy($node, $targetNode, $position, $nodeName);

		$this->nodeDataRepository->persistEntities();

		$q = new FlowQuery(array($copiedNode));
		$closestDocumentNode = $q->closest('[instanceof TYPO3.Neos:Document]')->get(0);

		$requestData = array(
			'nextUri' => $this->uriBuilder->reset()->setFormat('html')->setCreateAbsoluteUri(TRUE)->uriFor('show', array('node' => $closestDocumentNode), 'Frontend\Node', 'TYPO3.Neos'),
			'newNodePath' => $copiedNode->getContextPath()
		);

		if ($node->getNodeType()->isOfType('TYPO3.Neos:Document')) {
			$requestData['nodeUri'] = $this->uriBuilder->reset()->setFormat('html')->setCreateAbsoluteUri(TRUE)->uriFor('show', array('node' => $copiedNode), 'Frontend\Node', 'TYPO3.Neos');
		}

		$this->view->assign('value', array('data' => $requestData, 'success' => TRUE));
	}

	/**
	 * Updates the specified node. Returns the following data:
	 * - the (possibly changed) workspace name of the node
	 * - the URI of the closest document node. If $node is a document node (f.e. a Page), the own URI is returned.
	 *   This is important to handle renamings of nodes correctly.
	 *
	 * Note: We do not call $nodeDataRepository->update() here, as TYPO3CR has a stateful API for now.
	 *
	 * @param Node $node
	 * @return void
	 */
	public function updateAction(Node $node) {
		$this->nodeDataRepository->persistEntities();
		$q = new FlowQuery(array($node));
		$closestDocumentNode = $q->closest('[instanceof TYPO3.Neos:Document]')->get(0);
		$nextUri = $this->uriBuilder->reset()->setFormat('html')->setCreateAbsoluteUri(TRUE)->uriFor('show', array('node' => $closestDocumentNode), 'Frontend\Node', 'TYPO3.Neos');
		$this->view->assign('value', array('data' => array('workspaceNameOfNode' => $node->getWorkspace()->getName(), 'nextUri' => $nextUri), 'success' => TRUE));
	}

	/**
	 * Deletes the specified node and all of its sub nodes
	 *
	 * @param Node $node
	 * @return void
	 */
	public function deleteAction(Node $node) {
		$this->nodeDataRepository->persistEntities();
		$q = new FlowQuery(array($node));
		$node->remove();
		$closestDocumentNode = $q->closest('[instanceof TYPO3.Neos:Document]')->get(0);
		$nextUri = $this->uriBuilder->reset()->setFormat('html')->setCreateAbsoluteUri(TRUE)->uriFor('show', array('node' => $closestDocumentNode), 'Frontend\Node', 'TYPO3.Neos');

		$this->view->assign('value', array('data' => array('nextUri' => $nextUri), 'success' => TRUE));
	}

	/**
	 * Search a page, needed for internal links.
	 *
	 * @param string $query
	 * @return void
	 */
	public function searchPageAction($query) {
		$searchResult = array();

		$documentNodeTypes = $this->nodeTypeManager->getSubNodeTypes('TYPO3.Neos:Document');
		/** @var NodeInterface $node */
		foreach ($this->nodeSearchService->findByProperties($query, $documentNodeTypes, $this->createContext('live')) as $node) {
			$searchResult[$node->getPath()] = $this->processNodeForEditorPlugins($node);
		}

		$this->view->assign('value', array('searchResult' => $searchResult, 'success' => TRUE));
	}

	/**
	 * Get the page by the node path, needed for internal links.
	 *
	 * @param string $nodePath
	 * @return void
	 */
	public function getPageByNodePathAction($nodePath) {
		$contentContext = $this->createContext('live');

		$node = $contentContext->getNode($nodePath);
		$this->view->assign('value', array('node' => $this->processNodeForEditorPlugins($node), 'success' => TRUE));
	}

	/**
	 * Returns an array with the data needed by for example the Hallo and Aloha
	 * link plugins to represent the passed Node instance.
	 *
	 * @param NodeInterface $node
	 * @return array
	 */
	protected function processNodeForEditorPlugins(NodeInterface $node) {
		return array(
			'id' => $node->getPath(),
			'name' => $node->getLabel(),
			'url' => $this->uriBuilder->uriFor('show', array('node' => $node), 'Frontend\Node', 'TYPO3.Neos'),
			'type' => 'neos/internal-link'
		);
	}

	/**
	 * Create a Context for a workspace given by name to be used in this controller.
	 *
	 * @param string $workspaceName Name of the current workspace
	 * @return \TYPO3\TYPO3CR\Domain\Service\Context
	 */
	protected function createContext($workspaceName) {
		$contextProperties = array(
			'workspaceName' => $workspaceName
		);

		return $this->contextFactory->create($contextProperties);
	}
}
namespace TYPO3\Neos\Service\Controller;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * Service Controller for managing Nodes
 * 
 * Note: This controller should be, step-by-step, transformed into a clean REST controller (see NEOS-190 and NEOS-199).
 *       Since this is a rather big endeavor, we slice the elephant and move methods in a clean way from here to the
 *       new NodesController (\TYPO3\Neos\Controller\Service\NodesController)
 */
class NodeController extends NodeController_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {

	private $Flow_Aop_Proxy_targetMethodsAndGroupedAdvices = array();

	private $Flow_Aop_Proxy_groupedAdviceChains = array();

	private $Flow_Aop_Proxy_methodIsInAdviceMode = array();


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {

		$this->Flow_Aop_Proxy_buildMethodsAndAdvicesArray();
		if ('TYPO3\Neos\Service\Controller\NodeController' === get_class($this)) {
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
			'getChildNodesForTreeAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'filterChildNodesForTreeAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'createAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'createAndRenderAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'createNodeForTheTreeAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'moveAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'copyAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'updateAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'deleteAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'searchPageAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'getPageByNodePathAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'errorAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
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
	 * @param Node $node The node to find child nodes for
	 * @param string $nodeTypeFilter A node type filter
	 * @param integer $depth levels of childNodes (0 = unlimited)
	 * @param Node $untilNode expand the child nodes until $untilNode is reached, independent of $depth
	 * @return void
	 */
	 public function getChildNodesForTreeAction(\TYPO3\TYPO3CR\Domain\Model\Node $node, $nodeTypeFilter, $depth, \TYPO3\TYPO3CR\Domain\Model\Node $untilNode) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['getChildNodesForTreeAction'])) {
		$result = parent::getChildNodesForTreeAction($node, $nodeTypeFilter, $depth, $untilNode);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['getChildNodesForTreeAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['node'] = $node;
				$methodArguments['nodeTypeFilter'] = $nodeTypeFilter;
				$methodArguments['depth'] = $depth;
				$methodArguments['untilNode'] = $untilNode;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('getChildNodesForTreeAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Service\Controller\NodeController', 'getChildNodesForTreeAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['getChildNodesForTreeAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['getChildNodesForTreeAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param Node $node The node to find child nodes for
	 * @param string $term
	 * @param string $nodeType
	 * @return void
	 */
	 public function filterChildNodesForTreeAction(\TYPO3\TYPO3CR\Domain\Model\Node $node, $term, $nodeType) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['filterChildNodesForTreeAction'])) {
		$result = parent::filterChildNodesForTreeAction($node, $term, $nodeType);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['filterChildNodesForTreeAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['node'] = $node;
				$methodArguments['term'] = $term;
				$methodArguments['nodeType'] = $nodeType;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('filterChildNodesForTreeAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Service\Controller\NodeController', 'filterChildNodesForTreeAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['filterChildNodesForTreeAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['filterChildNodesForTreeAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param Node $referenceNode
	 * @param array $nodeData
	 * @param string $position where the node should be added (allowed: before, into, after)
	 * @return void
	 * @throws \InvalidArgumentException
	 */
	 public function createAction(\TYPO3\TYPO3CR\Domain\Model\Node $referenceNode, array $nodeData, $position) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['createAction'])) {
		$result = parent::createAction($referenceNode, $nodeData, $position);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['createAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['referenceNode'] = $referenceNode;
				$methodArguments['nodeData'] = $nodeData;
				$methodArguments['position'] = $position;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('createAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Service\Controller\NodeController', 'createAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['createAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['createAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param Node $referenceNode
	 * @param string $typoScriptPath The TypoScript path of the collection
	 * @param array $nodeData
	 * @param string $position where the node should be added (allowed: before, into, after)
	 * @return string
	 * @throws \InvalidArgumentException
	 */
	 public function createAndRenderAction(\TYPO3\TYPO3CR\Domain\Model\Node $referenceNode, $typoScriptPath, array $nodeData, $position) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['createAndRenderAction'])) {
		$result = parent::createAndRenderAction($referenceNode, $typoScriptPath, $nodeData, $position);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['createAndRenderAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['referenceNode'] = $referenceNode;
				$methodArguments['typoScriptPath'] = $typoScriptPath;
				$methodArguments['nodeData'] = $nodeData;
				$methodArguments['position'] = $position;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('createAndRenderAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Service\Controller\NodeController', 'createAndRenderAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['createAndRenderAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['createAndRenderAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param Node $referenceNode
	 * @param array $nodeData
	 * @param string $position where the node should be added, -1 is before, 0 is in, 1 is after
	 * @return void
	 * @throws \InvalidArgumentException
	 */
	 public function createNodeForTheTreeAction(\TYPO3\TYPO3CR\Domain\Model\Node $referenceNode, array $nodeData, $position) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['createNodeForTheTreeAction'])) {
		$result = parent::createNodeForTheTreeAction($referenceNode, $nodeData, $position);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['createNodeForTheTreeAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['referenceNode'] = $referenceNode;
				$methodArguments['nodeData'] = $nodeData;
				$methodArguments['position'] = $position;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('createNodeForTheTreeAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Service\Controller\NodeController', 'createNodeForTheTreeAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['createNodeForTheTreeAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['createNodeForTheTreeAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param Node $node
	 * @param Node $targetNode
	 * @param string $position where the node should be added (allowed: before, into, after)
	 * @return void
	 * @throws NodeException
	 */
	 public function moveAction(\TYPO3\TYPO3CR\Domain\Model\Node $node, \TYPO3\TYPO3CR\Domain\Model\Node $targetNode, $position) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['moveAction'])) {
		$result = parent::moveAction($node, $targetNode, $position);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['moveAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['node'] = $node;
				$methodArguments['targetNode'] = $targetNode;
				$methodArguments['position'] = $position;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('moveAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Service\Controller\NodeController', 'moveAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['moveAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['moveAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param Node $node the node to be copied
	 * @param Node $targetNode the target node to be copied "to", see $position
	 * @param string $position where the node should be added in relation to $targetNode (allowed: before, into, after)
	 * @param string $nodeName optional node name (if empty random node name will be generated)
	 * @return void
	 * @throws NodeException
	 */
	 public function copyAction(\TYPO3\TYPO3CR\Domain\Model\Node $node, \TYPO3\TYPO3CR\Domain\Model\Node $targetNode, $position, $nodeName = NULL) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['copyAction'])) {
		$result = parent::copyAction($node, $targetNode, $position, $nodeName);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['copyAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['node'] = $node;
				$methodArguments['targetNode'] = $targetNode;
				$methodArguments['position'] = $position;
				$methodArguments['nodeName'] = $nodeName;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('copyAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Service\Controller\NodeController', 'copyAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['copyAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['copyAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param Node $node
	 * @return void
	 */
	 public function updateAction(\TYPO3\TYPO3CR\Domain\Model\Node $node) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['updateAction'])) {
		$result = parent::updateAction($node);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['updateAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['node'] = $node;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('updateAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Service\Controller\NodeController', 'updateAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['updateAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['updateAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param Node $node
	 * @return void
	 */
	 public function deleteAction(\TYPO3\TYPO3CR\Domain\Model\Node $node) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['deleteAction'])) {
		$result = parent::deleteAction($node);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['deleteAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['node'] = $node;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('deleteAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Service\Controller\NodeController', 'deleteAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['deleteAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['deleteAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param string $query
	 * @return void
	 */
	 public function searchPageAction($query) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['searchPageAction'])) {
		$result = parent::searchPageAction($query);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['searchPageAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['query'] = $query;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('searchPageAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Service\Controller\NodeController', 'searchPageAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['searchPageAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['searchPageAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param string $nodePath
	 * @return void
	 */
	 public function getPageByNodePathAction($nodePath) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['getPageByNodePathAction'])) {
		$result = parent::getPageByNodePathAction($nodePath);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['getPageByNodePathAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['nodePath'] = $nodePath;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('getPageByNodePathAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Service\Controller\NodeController', 'getPageByNodePathAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['getPageByNodePathAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['getPageByNodePathAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @return void
	 */
	 public function errorAction() {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['errorAction'])) {
		$result = parent::errorAction();

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['errorAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('errorAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Service\Controller\NodeController', 'errorAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['errorAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['errorAction']);
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
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\Service\Controller\NodeController');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\Service\Controller\NodeController', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\Service\Controller\NodeController', $propertyName, 'var');
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
		$this->injectSettings(\TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Configuration\ConfigurationManager')->getConfiguration(\TYPO3\Flow\Configuration\ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, 'TYPO3.Neos'));
		$nodeTypeManager_reference = &$this->nodeTypeManager;
		$this->nodeTypeManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Service\NodeTypeManager');
		if ($this->nodeTypeManager === NULL) {
			$this->nodeTypeManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('478a517efacb3d47415a96d9caded2e9', $nodeTypeManager_reference);
			if ($this->nodeTypeManager === NULL) {
				$this->nodeTypeManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('478a517efacb3d47415a96d9caded2e9',  $nodeTypeManager_reference, 'TYPO3\TYPO3CR\Domain\Service\NodeTypeManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Service\NodeTypeManager'); });
			}
		}
		$nodeSearchService_reference = &$this->nodeSearchService;
		$this->nodeSearchService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Neos\Domain\Service\NodeSearchService');
		if ($this->nodeSearchService === NULL) {
			$this->nodeSearchService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('8fbb345eb0af55ff53e13b5dddb8aab5', $nodeSearchService_reference);
			if ($this->nodeSearchService === NULL) {
				$this->nodeSearchService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('8fbb345eb0af55ff53e13b5dddb8aab5',  $nodeSearchService_reference, 'TYPO3\Neos\Domain\Service\NodeSearchService', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Neos\Domain\Service\NodeSearchService'); });
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
		$contextFactory_reference = &$this->contextFactory;
		$this->contextFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface');
		if ($this->contextFactory === NULL) {
			$this->contextFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('6b6e9d36a8365cb0dccb3d849ae9366e', $contextFactory_reference);
			if ($this->contextFactory === NULL) {
				$this->contextFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('6b6e9d36a8365cb0dccb3d849ae9366e',  $contextFactory_reference, 'TYPO3\Neos\Domain\Service\ContentContextFactory', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface'); });
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
		$nodeOperations_reference = &$this->nodeOperations;
		$this->nodeOperations = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Neos\Service\NodeOperations');
		if ($this->nodeOperations === NULL) {
			$this->nodeOperations = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('b85a227aaba6305a81dffff1fd1c9613', $nodeOperations_reference);
			if ($this->nodeOperations === NULL) {
				$this->nodeOperations = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('b85a227aaba6305a81dffff1fd1c9613',  $nodeOperations_reference, 'TYPO3\Neos\Service\NodeOperations', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Neos\Service\NodeOperations'); });
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
		$reflectionService_reference = &$this->reflectionService;
		$this->reflectionService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Reflection\ReflectionService');
		if ($this->reflectionService === NULL) {
			$this->reflectionService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('921ad637f16d2059757a908fceaf7076', $reflectionService_reference);
			if ($this->reflectionService === NULL) {
				$this->reflectionService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('921ad637f16d2059757a908fceaf7076',  $reflectionService_reference, 'TYPO3\Flow\Reflection\ReflectionService', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Reflection\ReflectionService'); });
			}
		}
		$mvcPropertyMappingConfigurationService_reference = &$this->mvcPropertyMappingConfigurationService;
		$this->mvcPropertyMappingConfigurationService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Mvc\Controller\MvcPropertyMappingConfigurationService');
		if ($this->mvcPropertyMappingConfigurationService === NULL) {
			$this->mvcPropertyMappingConfigurationService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('35acb49fbe78f28099d45aa647797c83', $mvcPropertyMappingConfigurationService_reference);
			if ($this->mvcPropertyMappingConfigurationService === NULL) {
				$this->mvcPropertyMappingConfigurationService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('35acb49fbe78f28099d45aa647797c83',  $mvcPropertyMappingConfigurationService_reference, 'TYPO3\Flow\Mvc\Controller\MvcPropertyMappingConfigurationService', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Mvc\Controller\MvcPropertyMappingConfigurationService'); });
			}
		}
		$viewConfigurationManager_reference = &$this->viewConfigurationManager;
		$this->viewConfigurationManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Mvc\ViewConfigurationManager');
		if ($this->viewConfigurationManager === NULL) {
			$this->viewConfigurationManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('5a345bfd515fdb9f0c97080ff13c7079', $viewConfigurationManager_reference);
			if ($this->viewConfigurationManager === NULL) {
				$this->viewConfigurationManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('5a345bfd515fdb9f0c97080ff13c7079',  $viewConfigurationManager_reference, 'TYPO3\Flow\Mvc\ViewConfigurationManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Mvc\ViewConfigurationManager'); });
			}
		}
		$systemLogger_reference = &$this->systemLogger;
		$this->systemLogger = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Log\SystemLoggerInterface');
		if ($this->systemLogger === NULL) {
			$this->systemLogger = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('6d57d95a1c3cd7528e3e6ea15012dac8', $systemLogger_reference);
			if ($this->systemLogger === NULL) {
				$this->systemLogger = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('6d57d95a1c3cd7528e3e6ea15012dac8',  $systemLogger_reference, 'TYPO3\Flow\Log\SystemLoggerInterface', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Log\SystemLoggerInterface'); });
			}
		}
		$validatorResolver_reference = &$this->validatorResolver;
		$this->validatorResolver = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Validation\ValidatorResolver');
		if ($this->validatorResolver === NULL) {
			$this->validatorResolver = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('b457db29305ddeae13b61d92da000ca0', $validatorResolver_reference);
			if ($this->validatorResolver === NULL) {
				$this->validatorResolver = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('b457db29305ddeae13b61d92da000ca0',  $validatorResolver_reference, 'TYPO3\Flow\Validation\ValidatorResolver', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Validation\ValidatorResolver'); });
			}
		}
		$flashMessageContainer_reference = &$this->flashMessageContainer;
		$this->flashMessageContainer = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Mvc\FlashMessageContainer');
		if ($this->flashMessageContainer === NULL) {
			$this->flashMessageContainer = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('e4fd26f8afd3994317304b563b2a9561', $flashMessageContainer_reference);
			if ($this->flashMessageContainer === NULL) {
				$this->flashMessageContainer = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('e4fd26f8afd3994317304b563b2a9561',  $flashMessageContainer_reference, 'TYPO3\Flow\Mvc\FlashMessageContainer', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Mvc\FlashMessageContainer'); });
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
  0 => 'settings',
  1 => 'nodeTypeManager',
  2 => 'nodeSearchService',
  3 => 'nodeFactory',
  4 => 'contextFactory',
  5 => 'nodeDataRepository',
  6 => 'nodeOperations',
  7 => 'objectManager',
  8 => 'reflectionService',
  9 => 'mvcPropertyMappingConfigurationService',
  10 => 'viewConfigurationManager',
  11 => 'systemLogger',
  12 => 'validatorResolver',
  13 => 'flashMessageContainer',
  14 => 'persistenceManager',
);
	}
}
#