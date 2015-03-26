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
use TYPO3\Flow\Cli\ConsoleOutput;
use TYPO3\Neos\Domain\Service\ContentDimensionPresetSourceInterface;
use TYPO3\TYPO3CR\Command\NodeCommandControllerPluginInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeType;
use TYPO3\TYPO3CR\Domain\Repository\ContentDimensionRepository;
use TYPO3\TYPO3CR\Domain\Repository\WorkspaceRepository;
use TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface;
use TYPO3\TYPO3CR\Utility;

/**
 * A plugin for the TYPO3CR NodeCommandController which adds a task adding missing URI segments to the node:repair
 * command.
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
	 * @var WorkspaceRepository
	 */
	protected $workspaceRepository;

	/**
	 * @Flow\Inject
	 * @var ContentDimensionRepository
	 */
	protected $contentDimensionRepository;

	/**
	 * @var ContentDimensionPresetSourceInterface
	 * @Flow\Inject
	 */
	protected $contentDimensionPresetSource;

	/**
	 * @var ConsoleOutput
	 */
	protected $output;

	/**
	 * Returns a short description
	 *
	 * @param string $controllerCommandName Name of the command in question, for example "repair"
	 * @return string A piece of text to be included in the overall description of the node:xy command
	 */
	static public function getSubCommandShortDescription($controllerCommandName) {
		switch ($controllerCommandName) {
			case 'repair':
				return 'Generate missing URI path segments';
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
					'<u>Generate missing URI path segments</u>' . PHP_EOL .
					PHP_EOL .
					'Generates URI path segment properties for all document nodes which don\'t have a path' . PHP_EOL .
					'segment set yet.' . PHP_EOL;
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
		$this->generateUriPathSegments($workspaceName, $dryRun);
	}

	/**
	 * Generate missing URI path segments
	 *
	 * This generates URI path segment properties for all document nodes which don't have
	 * a path segment set yet.
	 *
	 * @param string $workspaceName
	 * @param boolean $dryRun
	 * @return void
	 */
	public function generateUriPathSegments($workspaceName, $dryRun) {
		$contentDimensionPresets = $this->contentDimensionPresetSource->getAllPresets();
		if (isset($contentDimensionPresets['language']['presets'])) {
			foreach ($contentDimensionPresets['language']['presets'] as $languagePreset) {
				$this->output->outputLine('Migrating nodes for %s', array($languagePreset['label']));
				$context = $this->createContext($workspaceName, $languagePreset['values']);
				foreach ($context->getRootNode()->getChildNodes() as $siteNode) {
					$this->generateUriPathSegmentsForSubtree($siteNode, $dryRun);
				}
			}
		} else {
			$context = $this->createContext($workspaceName);
			foreach ($context->getRootNode()->getChildNodes() as $siteNode) {
				$this->generateUriPathSegmentsForSubtree($siteNode, $dryRun);
			}
		}
	}

	/**
	 * Traverses through the tree starting at the given root node and sets the uriPathSegment property derived from
	 * the node label. If $force is set, uriPathSegment is overwritten even if it already contained a value.
	 *
	 * @param NodeInterface $rootNode The node where the traversal starts
	 * @param boolean $dryRun
	 * @return void
	 */
	protected function generateUriPathSegmentsForSubtree(NodeInterface $rootNode, $dryRun) {
		foreach ($rootNode->getChildNodes('TYPO3.Neos:Document') as $node) {
			/** @var NodeInterface $node */
			if ($node->getProperty('uriPathSegment') == '') {
				$uriPathSegment = Utility::renderValidNodeName($node->getName());
				if ($dryRun === FALSE) {
					$node->setProperty('uriPathSegment', $uriPathSegment);
				}
				$this->output->outputLine('%s (%s) => %s', array($node->getPath(), $node->getName(), $uriPathSegment));
			}
			if ($node->hasChildNodes('TYPO3.Neos:Document')) {
				$this->generateUriPathSegmentsForSubtree($node, $dryRun);
			}
		}
	}

	/**
	 * Creates a content context for given workspace and language identifiers
	 *
	 * @param string $workspaceName
	 * @param array $languageIdentifiers
	 * @return \TYPO3\TYPO3CR\Domain\Service\Context
	 */
	protected function createContext($workspaceName, array $languageIdentifiers = NULL) {
		$contextProperties = array(
			'workspaceName' => $workspaceName,
			'invisibleContentShown' => TRUE,
			'inaccessibleContentShown' => TRUE
		);
		if ($languageIdentifiers !== NULL) {
			$contextProperties = array_merge($contextProperties, array(
				'dimensions' => array('language' => $languageIdentifiers)
			));
		}
		return $this->contextFactory->create($contextProperties);
	}

}namespace TYPO3\Neos\Command;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * A plugin for the TYPO3CR NodeCommandController which adds a task adding missing URI segments to the node:repair
 * command.
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class NodeCommandControllerPlugin extends NodeCommandControllerPlugin_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\Neos\Command\NodeCommandControllerPlugin') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Command\NodeCommandControllerPlugin', $this);
		if ('TYPO3\Neos\Command\NodeCommandControllerPlugin' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\Neos\Command\NodeCommandControllerPlugin') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Command\NodeCommandControllerPlugin', $this);

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
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\Command\NodeCommandControllerPlugin');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\Command\NodeCommandControllerPlugin', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\Command\NodeCommandControllerPlugin', $propertyName, 'var');
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
		$workspaceRepository_reference = &$this->workspaceRepository;
		$this->workspaceRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Repository\WorkspaceRepository');
		if ($this->workspaceRepository === NULL) {
			$this->workspaceRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('2e64c564c983af14b47d0c9ae8992997', $workspaceRepository_reference);
			if ($this->workspaceRepository === NULL) {
				$this->workspaceRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('2e64c564c983af14b47d0c9ae8992997',  $workspaceRepository_reference, 'TYPO3\TYPO3CR\Domain\Repository\WorkspaceRepository', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Repository\WorkspaceRepository'); });
			}
		}
		$contentDimensionRepository_reference = &$this->contentDimensionRepository;
		$this->contentDimensionRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Repository\ContentDimensionRepository');
		if ($this->contentDimensionRepository === NULL) {
			$this->contentDimensionRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('c4ce8954e3d47ef3fdb068b6c07c9ebb', $contentDimensionRepository_reference);
			if ($this->contentDimensionRepository === NULL) {
				$this->contentDimensionRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('c4ce8954e3d47ef3fdb068b6c07c9ebb',  $contentDimensionRepository_reference, 'TYPO3\TYPO3CR\Domain\Repository\ContentDimensionRepository', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Repository\ContentDimensionRepository'); });
			}
		}
		$this->contentDimensionPresetSource = new \TYPO3\Neos\Domain\Service\ConfigurationContentDimensionPresetSource();
$this->Flow_Injected_Properties = array (
  0 => 'contextFactory',
  1 => 'workspaceRepository',
  2 => 'contentDimensionRepository',
  3 => 'contentDimensionPresetSource',
);
	}
}
#