<?php 
namespace TYPO3\Neos\Command;

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
use TYPO3\Flow\Cli\CommandController;
use TYPO3\Neos\Service\PublishingService;
use TYPO3\TYPO3CR\Domain\Model\Workspace;
use TYPO3\TYPO3CR\Domain\Repository\WorkspaceRepository;

/**
 * The Workspace Command Controller
 *
 * @Flow\Scope("singleton")
 */
class WorkspaceCommandController_Original extends CommandController {

	/**
	 * @Flow\Inject
	 * @var PublishingService
	 */
	protected $publishingService;

	/**
	 * @Flow\Inject
	 * @var WorkspaceRepository
	 */
	protected $workspaceRepository;

	/**
	 * Publish changes of a workspace
	 *
	 * This command publishes all modified, created or deleted nodes in the specified workspace to the live workspace.
	 *
	 * @param string $workspace Name of the workspace containing the changes to publish, for example "user-john"
	 * @param boolean $verbose If enabled, some information about individual nodes will be displayed
	 * @param boolean $dryRun If set, only displays which nodes would be published, no real changes are committed
	 * @return void
	 */
	public function publishCommand($workspace, $verbose = FALSE, $dryRun = FALSE) {
		$workspaceName = $workspace;
		$workspace = $this->workspaceRepository->findOneByName($workspaceName);
		if (!$workspace instanceof Workspace) {
			$this->outputLine('Workspace "%s" does not exist', array($workspaceName));
			exit(1);
		}

		try {
			$nodes = $this->publishingService->getUnpublishedNodes($workspace);
		} catch (\Exception $exception) {
			$this->outputLine('An error occurred while fetching unpublished nodes from workspace %s, publish aborted.', array($workspaceName));
			exit(1);
		}

		$this->outputLine('The workspace %s contains %u unpublished nodes.', array($workspaceName, count($nodes)));

		foreach ($nodes as $node) {
			/** @var \TYPO3\TYPO3CR\Domain\Model\NodeInterface $node */
			if ($verbose) {
				$this->outputLine('    ' . $node->getPath());
			}
			if (!$dryRun) {
				$this->publishingService->publishNode($node);
			}
		}

		if (!$dryRun) {
			$this->outputLine('Published all nodes in workspace %s', array($workspaceName));
		}
	}

	/**
	 * Discard changes in workspace
	 *
	 * This command discards all modified, created or deleted nodes in the specified workspace.
	 *
	 * @param string $workspace Name of the workspace, for example "user-john"
	 * @param boolean $verbose If enabled, information about individual nodes will be displayed
	 * @param boolean $dryRun If set, only displays which nodes would be discarded, no real changes are committed
	 * @return void
	 */
	public function discardCommand($workspace, $verbose = FALSE, $dryRun = FALSE) {
		$workspaceName = $workspace;
		$workspace = $this->workspaceRepository->findOneByName($workspaceName);
		if (!$workspace instanceof Workspace) {
			$this->outputLine('Workspace "%s" does not exist', array($workspaceName));
			exit(1);
		}

		try {
			$nodes = $this->publishingService->getUnpublishedNodes($workspace);
		} catch (\Exception $exception) {
			$this->outputLine('An error occurred while fetching unpublished nodes from workspace %s, discard aborted.', array($workspaceName));
			exit(1);
		}

		$this->outputLine('The workspace %s contains %u unpublished nodes.', array($workspaceName, count($nodes)));

		foreach ($nodes as $node) {
			/** @var \TYPO3\TYPO3CR\Domain\Model\NodeInterface $node */
			if ($node->getPath() !== '/') {
				if ($verbose) {
					$this->outputLine('    ' . $node->getPath());
				}
				if (!$dryRun) {
					$this->publishingService->discardNode($node);
				}
			}
		}

		if (!$dryRun) {
			$this->outputLine('Discarded all nodes in workspace %s', array($workspaceName));
		}
	}

	/**
	 * Publish changes of a workspace
	 *
	 * This command publishes all modified, created or deleted nodes in the specified workspace to the live workspace.
	 *
	 * @param string $workspaceName Name of the workspace, for example "user-john"
	 * @param boolean $verbose If enabled, information about individual nodes will be displayed
	 * @return void
	 * @deprecated since 1.2
	 * @see typo3.neos:workspace:publish
	 */
	public function publishAllCommand($workspaceName, $verbose = FALSE) {
		$this->publishCommand($workspaceName, $verbose);
	}

	/**
	 * Discard changes in workspace
	 *
	 * This command discards all modified, created or deleted nodes in the specified workspace.
	 *
	 * @param string $workspaceName Name of the workspace, for example "user-john"
	 * @param boolean $verbose If enabled, information about individual nodes will be displayed
	 * @return void
	 * @deprecated since 1.2
	 * @see typo3.neos:workspace:discard
	 */
	public function discardAllCommand($workspaceName, $verbose = FALSE) {
		$this->discardCommand($workspaceName, $verbose);
	}

	/**
	 * Display a list of existing workspaces
	 *
	 * @return void
	 */
	public function listCommand() {
		$workspaces = $this->workspaceRepository->findAll();

		if ($workspaces->count() === 0) {
			$this->outputLine('No workspaces found.');
			exit(0);
		}

		$workspaceNames = array();
		foreach ($workspaces as $workspace) {
			$workspaceNames[$workspace->getName()] = $workspace->getBaseWorkspace() ? $workspace->getBaseWorkspace()->getName() : '';
		}
		ksort($workspaceNames);

		$longestName = max(array_map('strlen', array_keys($workspaceNames)));
		$this->outputLine(' <b>' . str_pad('Workspace', $longestName + 10) . 'Base workspace</b>');
		foreach ($workspaceNames as $workspaceName => $baseWorkspaceName) {
			$this->outputLine(' ' . str_pad($workspaceName, $longestName + 10) . $baseWorkspaceName);
		}
	}
}
namespace TYPO3\Neos\Command;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * The Workspace Command Controller
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class WorkspaceCommandController extends WorkspaceCommandController_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\Neos\Command\WorkspaceCommandController') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Command\WorkspaceCommandController', $this);
		parent::__construct();
		if ('TYPO3\Neos\Command\WorkspaceCommandController' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\Neos\Command\WorkspaceCommandController') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Command\WorkspaceCommandController', $this);

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
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\Command\WorkspaceCommandController');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\Command\WorkspaceCommandController', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\Command\WorkspaceCommandController', $propertyName, 'var');
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
		$this->injectReflectionService(\TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Reflection\ReflectionService'));
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
$this->Flow_Injected_Properties = array (
  0 => 'reflectionService',
  1 => 'publishingService',
  2 => 'workspaceRepository',
);
	}
}
#