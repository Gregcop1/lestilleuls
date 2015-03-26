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
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\TypeConverter\NodeConverter;

/**
 * Service Controller for managing Workspaces
 */
class WorkspaceController_Original extends AbstractServiceController {

	/**
	 * @var string
	 */
	protected $defaultViewObjectName = 'TYPO3\Neos\Service\View\NodeView';

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Domain\Repository\NodeDataRepository
	 */
	protected $nodeDataRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Neos\Service\PublishingService
	 */
	protected $publishingService;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Domain\Repository\WorkspaceRepository
	 */
	protected $workspaceRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Property\PropertyMapper
	 */
	protected $propertyMapper;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Property\PropertyMappingConfigurationBuilder
	 */
	protected $propertyMappingConfigurationBuilder;

	/**
	 * @return void
	 */
	protected function initializeAction() {
		if ($this->arguments->hasArgument('node')) {
			$this
				->arguments
				->getArgument('node')
				->getPropertyMappingConfiguration()
				->setTypeConverterOption('TYPO3\TYPO3CR\TypeConverter\NodeConverter', NodeConverter::REMOVED_CONTENT_SHOWN, TRUE);
		}

		if ($this->arguments->hasArgument('nodes')) {
			$this
				->arguments
				->getArgument('nodes')
				->getPropertyMappingConfiguration()
				->forProperty('*')
				->setTypeConverterOption('TYPO3\TYPO3CR\TypeConverter\NodeConverter', NodeConverter::REMOVED_CONTENT_SHOWN, TRUE);
		}
	}

	/**
	 * Publishes the given node to the specified targetWorkspace
	 *
	 * @param NodeInterface $node
	 * @param string $targetWorkspaceName
	 * @return void
	 */
	public function publishNodeAction(NodeInterface $node, $targetWorkspaceName) {
		$targetWorkspace = $this->workspaceRepository->findOneByName($targetWorkspaceName);

		$this->publishingService->publishNode($node, $targetWorkspace);

		$this->throwStatus(204, 'Node has been published');
	}

	/**
	 * Publishes the given nodes to the specified targetWorkspace
	 *
	 * @param array<\TYPO3\TYPO3CR\Domain\Model\NodeInterface> $nodes
	 * @param string $targetWorkspaceName
	 * @return void
	 */
	public function publishNodesAction(array $nodes, $targetWorkspaceName) {
		$targetWorkspace = $this->workspaceRepository->findOneByName($targetWorkspaceName);

		$this->publishingService->publishNodes($nodes, $targetWorkspace);

		$this->throwStatus(204, 'Nodes have been published');
	}

	/**
	 * Discards the given node
	 *
	 * @param NodeInterface $node
	 * @return void
	 */
	public function discardNodeAction(NodeInterface $node) {
		$this->publishingService->discardNode($node);

		$this->throwStatus(204, 'Node changes have been discarded');
	}

	/**
	 * Discards the given nodes
	 *
	 * @param array<\TYPO3\TYPO3CR\Domain\Model\NodeInterface> $nodes
	 * @return void
	 */
	public function discardNodesAction(array $nodes) {
		$this->publishingService->discardNodes($nodes);

		$this->throwStatus(204, 'Node changes have been discarded');
	}

	/**
	 * Publish everything in the workspace with the given workspace name
	 *
	 * @param string $workspaceName
	 * @return void
	 */
	public function publishAllAction($workspaceName) {
		$workspace = $this->workspaceRepository->findOneByName($workspaceName);
		$this->publishingService->publishNodes($this->publishingService->getUnpublishedNodes($workspace));

		$this->throwStatus(204, 'Workspace changes have been published');
	}

	/**
	 * Get every unpublished node in the workspace with the given workspace name
	 *
	 * @param \TYPO3\TYPO3CR\Domain\Model\Workspace $workspace
	 * @return void
	 */
	public function getWorkspaceWideUnpublishedNodesAction($workspace) {
		$nodes = $this->publishingService->getUnpublishedNodes($workspace);

		$this->view->assignNodes($nodes);
	}

	/**
	 * Discard everything in the workspace with the given workspace name
	 *
	 * @param \TYPO3\TYPO3CR\Domain\Model\Workspace $workspace
	 * @return void
	 */
	public function discardAllAction($workspace) {
		$this->publishingService->discardNodes($this->publishingService->getUnpublishedNodes($workspace));

		$this->throwStatus(204, 'Workspace changes have been discarded');
	}

}namespace TYPO3\Neos\Service\Controller;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * Service Controller for managing Workspaces
 */
class WorkspaceController extends WorkspaceController_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {

	private $Flow_Aop_Proxy_targetMethodsAndGroupedAdvices = array();

	private $Flow_Aop_Proxy_groupedAdviceChains = array();

	private $Flow_Aop_Proxy_methodIsInAdviceMode = array();


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {

		$this->Flow_Aop_Proxy_buildMethodsAndAdvicesArray();
		if ('TYPO3\Neos\Service\Controller\WorkspaceController' === get_class($this)) {
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
			'publishNodeAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'publishNodesAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'discardNodeAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'discardNodesAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'publishAllAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'getWorkspaceWideUnpublishedNodesAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'discardAllAction' => array(
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
	 * @param NodeInterface $node
	 * @param string $targetWorkspaceName
	 * @return void
	 */
	 public function publishNodeAction(\TYPO3\TYPO3CR\Domain\Model\NodeInterface $node, $targetWorkspaceName) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['publishNodeAction'])) {
		$result = parent::publishNodeAction($node, $targetWorkspaceName);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['publishNodeAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['node'] = $node;
				$methodArguments['targetWorkspaceName'] = $targetWorkspaceName;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('publishNodeAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Service\Controller\WorkspaceController', 'publishNodeAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['publishNodeAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['publishNodeAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param array<\TYPO3\TYPO3CR\Domain\Model\NodeInterface> $nodes
	 * @param string $targetWorkspaceName
	 * @return void
	 */
	 public function publishNodesAction(array $nodes, $targetWorkspaceName) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['publishNodesAction'])) {
		$result = parent::publishNodesAction($nodes, $targetWorkspaceName);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['publishNodesAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['nodes'] = $nodes;
				$methodArguments['targetWorkspaceName'] = $targetWorkspaceName;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('publishNodesAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Service\Controller\WorkspaceController', 'publishNodesAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['publishNodesAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['publishNodesAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param NodeInterface $node
	 * @return void
	 */
	 public function discardNodeAction(\TYPO3\TYPO3CR\Domain\Model\NodeInterface $node) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['discardNodeAction'])) {
		$result = parent::discardNodeAction($node);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['discardNodeAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['node'] = $node;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('discardNodeAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Service\Controller\WorkspaceController', 'discardNodeAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['discardNodeAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['discardNodeAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param array<\TYPO3\TYPO3CR\Domain\Model\NodeInterface> $nodes
	 * @return void
	 */
	 public function discardNodesAction(array $nodes) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['discardNodesAction'])) {
		$result = parent::discardNodesAction($nodes);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['discardNodesAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['nodes'] = $nodes;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('discardNodesAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Service\Controller\WorkspaceController', 'discardNodesAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['discardNodesAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['discardNodesAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param string $workspaceName
	 * @return void
	 */
	 public function publishAllAction($workspaceName) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['publishAllAction'])) {
		$result = parent::publishAllAction($workspaceName);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['publishAllAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['workspaceName'] = $workspaceName;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('publishAllAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Service\Controller\WorkspaceController', 'publishAllAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['publishAllAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['publishAllAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param \TYPO3\TYPO3CR\Domain\Model\Workspace $workspace
	 * @return void
	 */
	 public function getWorkspaceWideUnpublishedNodesAction($workspace) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['getWorkspaceWideUnpublishedNodesAction'])) {
		$result = parent::getWorkspaceWideUnpublishedNodesAction($workspace);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['getWorkspaceWideUnpublishedNodesAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['workspace'] = $workspace;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('getWorkspaceWideUnpublishedNodesAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Service\Controller\WorkspaceController', 'getWorkspaceWideUnpublishedNodesAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['getWorkspaceWideUnpublishedNodesAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['getWorkspaceWideUnpublishedNodesAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param \TYPO3\TYPO3CR\Domain\Model\Workspace $workspace
	 * @return void
	 */
	 public function discardAllAction($workspace) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['discardAllAction'])) {
		$result = parent::discardAllAction($workspace);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['discardAllAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['workspace'] = $workspace;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('discardAllAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Service\Controller\WorkspaceController', 'discardAllAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['discardAllAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['discardAllAction']);
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
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Service\Controller\WorkspaceController', 'errorAction', $methodArguments, $adviceChain);
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
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\Service\Controller\WorkspaceController');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\Service\Controller\WorkspaceController', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\Service\Controller\WorkspaceController', $propertyName, 'var');
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
		$nodeDataRepository_reference = &$this->nodeDataRepository;
		$this->nodeDataRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Repository\NodeDataRepository');
		if ($this->nodeDataRepository === NULL) {
			$this->nodeDataRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('6d8e58e235099c88f352e23317321129', $nodeDataRepository_reference);
			if ($this->nodeDataRepository === NULL) {
				$this->nodeDataRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('6d8e58e235099c88f352e23317321129',  $nodeDataRepository_reference, 'TYPO3\TYPO3CR\Domain\Repository\NodeDataRepository', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Repository\NodeDataRepository'); });
			}
		}
		$publishingService_reference = &$this->publishingService;
		$this->publishingService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Neos\Service\PublishingService');
		if ($this->publishingService === NULL) {
			$this->publishingService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('6fcea527449934bf6820d8ecf224e34c', $publishingService_reference);
			if ($this->publishingService === NULL) {
				$this->publishingService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('6fcea527449934bf6820d8ecf224e34c',  $publishingService_reference, 'TYPO3\Neos\Service\PublishingService', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Neos\Service\PublishingService'); });
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
		$propertyMapper_reference = &$this->propertyMapper;
		$this->propertyMapper = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Property\PropertyMapper');
		if ($this->propertyMapper === NULL) {
			$this->propertyMapper = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('d727d5722bb68256b2c0c712d1adda00', $propertyMapper_reference);
			if ($this->propertyMapper === NULL) {
				$this->propertyMapper = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('d727d5722bb68256b2c0c712d1adda00',  $propertyMapper_reference, 'TYPO3\Flow\Property\PropertyMapper', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Property\PropertyMapper'); });
			}
		}
		$propertyMappingConfigurationBuilder_reference = &$this->propertyMappingConfigurationBuilder;
		$this->propertyMappingConfigurationBuilder = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Property\PropertyMappingConfigurationBuilder');
		if ($this->propertyMappingConfigurationBuilder === NULL) {
			$this->propertyMappingConfigurationBuilder = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('59cb6d934c9fe22d52baf9011a7b3a39', $propertyMappingConfigurationBuilder_reference);
			if ($this->propertyMappingConfigurationBuilder === NULL) {
				$this->propertyMappingConfigurationBuilder = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('59cb6d934c9fe22d52baf9011a7b3a39',  $propertyMappingConfigurationBuilder_reference, 'TYPO3\Flow\Property\PropertyMappingConfigurationBuilder', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Property\PropertyMappingConfigurationBuilder'); });
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
  1 => 'nodeDataRepository',
  2 => 'publishingService',
  3 => 'workspaceRepository',
  4 => 'propertyMapper',
  5 => 'propertyMappingConfigurationBuilder',
  6 => 'objectManager',
  7 => 'reflectionService',
  8 => 'mvcPropertyMappingConfigurationService',
  9 => 'viewConfigurationManager',
  10 => 'systemLogger',
  11 => 'validatorResolver',
  12 => 'flashMessageContainer',
  13 => 'persistenceManager',
);
	}
}
#