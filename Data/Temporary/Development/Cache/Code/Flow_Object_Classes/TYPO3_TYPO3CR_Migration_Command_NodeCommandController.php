<?php 
namespace TYPO3\TYPO3CR\Migration\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3CR".               *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\TYPO3CR\Migration\Exception\MigrationException;
use TYPO3\TYPO3CR\Migration\Service\NodeMigration;
use TYPO3\TYPO3CR\Migration\Domain\Model\MigrationStatus;
use TYPO3\TYPO3CR\Migration\Domain\Model\MigrationConfiguration;
use TYPO3\Flow\Annotations as Flow;

/**
 * Command controller for tasks related to node handling.
 *
 * @Flow\Scope("singleton")
 */
class NodeCommandController_Original extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Configuration\Source\YamlSource
	 */
	protected $yamlSourceImporter;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Domain\Repository\NodeDataRepository
	 */
	protected $nodeDataRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Migration\Domain\Repository\MigrationStatusRepository
	 */
	protected $migrationStatusRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Migration\Domain\Factory\MigrationFactory
	 */
	protected $migrationFactory;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface
	 */
	protected $contextFactory;

	/**
	 * Do the configured migrations in the given migration file for the given workspace
	 *
	 * By default the up direction is applied, using the direction parameter this can
	 * be changed.
	 *
	 * @param string $version The version of the migration configuration you want to use.
	 * @param boolean $confirmation Confirm application of this migration, only needed if the given migration contains any warnings.
	 * @param string $direction The direction to work in, MigrationStatus::DIRECTION_UP or MigrationStatus::DIRECTION_DOWN
	 * @return void
	 */
	public function migrateCommand($version, $confirmation = FALSE, $direction = MigrationStatus::DIRECTION_UP) {
		try {
			$migrationConfiguration = $direction === MigrationStatus::DIRECTION_UP ?
				$this->migrationFactory->getMigrationForVersion($version)->getUpConfiguration() :
				$this->migrationFactory->getMigrationForVersion($version)->getDownConfiguration();

			$this->outputCommentsAndWarnings($migrationConfiguration);
			if ($migrationConfiguration->hasWarnings() && $confirmation === FALSE) {
				$this->outputLine();
				$this->outputLine('Migration has warnings. You need to confirm execution by adding the "--confirmation TRUE" option to the command.');
				$this->quit(1);
			}

			$nodeMigrationService = new NodeMigration($migrationConfiguration->getMigration());
			$nodeMigrationService->execute();
			$migrationStatus = new MigrationStatus($version, $direction, new \DateTime());
			$this->migrationStatusRepository->add($migrationStatus);
			$this->outputLine();
			$this->outputLine('Successfully applied migration.');
		} catch (MigrationException $e) {
			$this->outputLine('Error: ' . $e->getMessage());
			$this->quit(1);
		}
	}

	/**
	 * List available and applied migrations
	 *
	 * @return void
	 * @see typo3.typo3cr.migration:node:listavailablemigrations
	 */
	public function migrationStatusCommand() {
		/** @var $appliedMigration MigrationStatus */
		$this->outputLine();

		$appliedMigrations = $this->migrationStatusRepository->findAll();

		$appliedMigrationsDictionary = array();
		foreach ($appliedMigrations as $appliedMigration) {
			$appliedMigrationsDictionary[$appliedMigration->getVersion()][] = $appliedMigration;
		}

		$availableMigrations = $this->migrationFactory->getAvailableMigrationsForCurrentConfigurationType();
		if (count($availableMigrations) > 0) {
			$this->outputLine('<b>Available migrations</b>');
			$this->outputLine();
			foreach ($availableMigrations as $version => $migration) {
				$this->outputLine($version . '   ' . $migration['formattedVersionNumber'] . '   ' . $migration['package']->getPackageKey());

				if (isset($appliedMigrationsDictionary[$version])) {
					$migrationsInVersion = $appliedMigrationsDictionary[$version];
					usort($migrationsInVersion, function(MigrationStatus $migrationA, MigrationStatus $migrationB) {
						return $migrationA->getApplicationTimeStamp() > $migrationB->getApplicationTimeStamp();
					});
					foreach ($migrationsInVersion as $appliedMigration) {
						$this->outputFormatted('%s applied on %s to workspace "%s"',
							array(
								str_pad(strtoupper($appliedMigration->getDirection()), 4, ' ', STR_PAD_LEFT),
								$appliedMigration->getApplicationTimeStamp()->format('d-m-Y H:i:s')
							),
							2
						);
						$this->outputLine();
					}
				}
			}
		} else {
			$this->outputLine('No migrations available.');
		}
	}

	/**
	 * Helper to output comments and warnings for the given configuration.
	 *
	 * @param \TYPO3\TYPO3CR\Migration\Domain\Model\MigrationConfiguration $migrationConfiguration
	 * @return void
	 */
	protected function outputCommentsAndWarnings(MigrationConfiguration $migrationConfiguration) {
		if ($migrationConfiguration->hasComments()) {
			$this->outputLine();
			$this->outputLine('<b>Comments</b>');
			$this->outputFormatted($migrationConfiguration->getComments(), array(), 2);
		}

		if ($migrationConfiguration->hasWarnings()) {
			$this->outputLine();
			$this->outputLine('<b><u>Warnings</u></b>');
			$this->outputFormatted($migrationConfiguration->getWarnings(), array(), 2);
		}
	}
}
namespace TYPO3\TYPO3CR\Migration\Command;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * Command controller for tasks related to node handling.
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class NodeCommandController extends NodeCommandController_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\TYPO3CR\Migration\Command\NodeCommandController') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\TYPO3CR\Migration\Command\NodeCommandController', $this);
		parent::__construct();
		if ('TYPO3\TYPO3CR\Migration\Command\NodeCommandController' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\TYPO3CR\Migration\Command\NodeCommandController') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\TYPO3CR\Migration\Command\NodeCommandController', $this);

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
	$reflectedClass = new \ReflectionClass('TYPO3\TYPO3CR\Migration\Command\NodeCommandController');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\TYPO3CR\Migration\Command\NodeCommandController', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\TYPO3CR\Migration\Command\NodeCommandController', $propertyName, 'var');
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
		$yamlSourceImporter_reference = &$this->yamlSourceImporter;
		$this->yamlSourceImporter = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Configuration\Source\YamlSource');
		if ($this->yamlSourceImporter === NULL) {
			$this->yamlSourceImporter = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('3ff75d2363593363cb0d0607df40c19a', $yamlSourceImporter_reference);
			if ($this->yamlSourceImporter === NULL) {
				$this->yamlSourceImporter = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('3ff75d2363593363cb0d0607df40c19a',  $yamlSourceImporter_reference, 'TYPO3\Flow\Configuration\Source\YamlSource', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Configuration\Source\YamlSource'); });
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
		$migrationStatusRepository_reference = &$this->migrationStatusRepository;
		$this->migrationStatusRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Migration\Domain\Repository\MigrationStatusRepository');
		if ($this->migrationStatusRepository === NULL) {
			$this->migrationStatusRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('c4900acc13f5432e71eaaac13dbf3cf6', $migrationStatusRepository_reference);
			if ($this->migrationStatusRepository === NULL) {
				$this->migrationStatusRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('c4900acc13f5432e71eaaac13dbf3cf6',  $migrationStatusRepository_reference, 'TYPO3\TYPO3CR\Migration\Domain\Repository\MigrationStatusRepository', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Migration\Domain\Repository\MigrationStatusRepository'); });
			}
		}
		$this->migrationFactory = new \TYPO3\TYPO3CR\Migration\Domain\Factory\MigrationFactory();
		$contextFactory_reference = &$this->contextFactory;
		$this->contextFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface');
		if ($this->contextFactory === NULL) {
			$this->contextFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('6b6e9d36a8365cb0dccb3d849ae9366e', $contextFactory_reference);
			if ($this->contextFactory === NULL) {
				$this->contextFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('6b6e9d36a8365cb0dccb3d849ae9366e',  $contextFactory_reference, 'TYPO3\Neos\Domain\Service\ContentContextFactory', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface'); });
			}
		}
$this->Flow_Injected_Properties = array (
  0 => 'reflectionService',
  1 => 'yamlSourceImporter',
  2 => 'nodeDataRepository',
  3 => 'migrationStatusRepository',
  4 => 'migrationFactory',
  5 => 'contextFactory',
);
	}
}
#