<?php 
namespace TYPO3\Setup\Step;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Setup".           *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Configuration\ConfigurationManager;
use TYPO3\Flow\Core\Booting\Scripts;
use TYPO3\Flow\Utility\Arrays;
use TYPO3\Flow\Validation\Validator\NotEmptyValidator;
use TYPO3\Setup\Exception as SetupException;
use TYPO3\Form\Core\Model\FormDefinition;

/**
 * @Flow\Scope("singleton")
 */
class DatabaseStep_Original extends \TYPO3\Setup\Step\AbstractStep {

	/**
	 * @var \TYPO3\Flow\Configuration\Source\YamlSource
	 * @Flow\Inject
	 */
	protected $configurationSource;

	/**
	 * @var \TYPO3\Flow\Security\Policy\PolicyService
	 * @Flow\Inject
	 */
	protected $policyService;

	/**
	 * Returns the form definitions for the step
	 *
	 * @param FormDefinition $formDefinition
	 * @return void
	 */
	protected function buildForm(FormDefinition $formDefinition) {
		$page1 = $formDefinition->createPage('page1');
		$page1->setRenderingOption('header', 'Configure database');

		$introduction = $page1->createElement('introduction', 'TYPO3.Form:StaticText');
		$introduction->setProperty('text', 'Please enter database details below:');

		$connectionSection = $page1->createElement('connectionSection', 'TYPO3.Form:Section');
		$connectionSection->setLabel('Connection');

		$databaseDriver = $connectionSection->createElement('driver', 'TYPO3.Form:SingleSelectDropdown');
		$databaseDriver->setLabel('DB Driver');
		$databaseDriver->setProperty('options', array('pdo_mysql' => 'MySQL/MariaDB via PDO', 'pdo_pgsql' => 'PostgreSQL via PDO'));
		$databaseDriver->setDefaultValue(Arrays::getValueByPath($this->distributionSettings, 'TYPO3.Flow.persistence.backendOptions.driver'));
		$databaseDriver->addValidator(new NotEmptyValidator());

		$databaseUser = $connectionSection->createElement('user', 'TYPO3.Form:SingleLineText');
		$databaseUser->setLabel('DB Username');
		$databaseUser->setDefaultValue(Arrays::getValueByPath($this->distributionSettings, 'TYPO3.Flow.persistence.backendOptions.user'));
		$databaseUser->addValidator(new NotEmptyValidator());

		$databasePassword = $connectionSection->createElement('password', 'TYPO3.Form:Password');
		$databasePassword->setLabel('DB Password');
		$databasePassword->setDefaultValue(Arrays::getValueByPath($this->distributionSettings, 'TYPO3.Flow.persistence.backendOptions.password'));

		$databaseHost = $connectionSection->createElement('host', 'TYPO3.Form:SingleLineText');
		$databaseHost->setLabel('DB Host');
		$defaultHost = Arrays::getValueByPath($this->distributionSettings, 'TYPO3.Flow.persistence.backendOptions.host');
		if ($defaultHost === NULL) {
			$defaultHost = '127.0.0.1';
		}
		$databaseHost->setDefaultValue($defaultHost);
		$databaseHost->addValidator(new NotEmptyValidator());

		$databaseSection = $page1->createElement('databaseSection', 'TYPO3.Form:Section');
		$databaseSection->setLabel('Database');

		$databaseName = $databaseSection->createElement('dbname', 'TYPO3.Setup:DatabaseSelector');
		$databaseName->setLabel('DB Name');
		$databaseName->setProperty('driverDropdownFieldId', $databaseDriver->getUniqueIdentifier());
		$databaseName->setProperty('userFieldId', $databaseUser->getUniqueIdentifier());
		$databaseName->setProperty('passwordFieldId', $databasePassword->getUniqueIdentifier());
		$databaseName->setProperty('hostFieldId', $databaseHost->getUniqueIdentifier());
		$databaseName->setDefaultValue(Arrays::getValueByPath($this->distributionSettings, 'TYPO3.Flow.persistence.backendOptions.dbname'));
		$databaseName->addValidator(new NotEmptyValidator());
	}

	/**
	 * This method is called when the form of this step has been submitted
	 *
	 * @param array $formValues
	 * @return void
	 * @throws \TYPO3\Flow\Configuration\Exception
	 * @throws \TYPO3\Flow\Core\Booting\Exception\SubProcessException
	 * @throws \TYPO3\Setup\Exception
	 */
	public function postProcessFormValues(array $formValues) {
		$this->distributionSettings = Arrays::setValueByPath($this->distributionSettings, 'TYPO3.Flow.persistence.backendOptions.driver', $formValues['driver']);
		$this->distributionSettings = Arrays::setValueByPath($this->distributionSettings, 'TYPO3.Flow.persistence.backendOptions.dbname', $formValues['dbname']);
		$this->distributionSettings = Arrays::setValueByPath($this->distributionSettings, 'TYPO3.Flow.persistence.backendOptions.user', $formValues['user']);
		$this->distributionSettings = Arrays::setValueByPath($this->distributionSettings, 'TYPO3.Flow.persistence.backendOptions.password', $formValues['password']);
		$this->distributionSettings = Arrays::setValueByPath($this->distributionSettings, 'TYPO3.Flow.persistence.backendOptions.host', $formValues['host']);
		$this->configurationSource->save(FLOW_PATH_CONFIGURATION . ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, $this->distributionSettings);

		$this->configurationManager->flushConfigurationCache();

		$settings = $this->configurationManager->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, 'TYPO3.Flow');
		$connectionSettings = $settings['persistence']['backendOptions'];
		try {
			$this->connectToDatabase($connectionSettings);
		} catch (\PDOException $exception) {
			try {
				$this->createDatabase($connectionSettings, $formValues['dbname']);
			} catch (\Doctrine\DBAL\DBALException $exception) {
				throw new SetupException(sprintf('Database "%s" could not be created. Please check the permissions for user "%s". DBAL Exception: "%s"', $formValues['dbname'], $formValues['user'], $exception->getMessage()), 1351000841, $exception);
			} catch (\PDOException $exception) {
				throw new SetupException(sprintf('Database "%s" could not be created. Please check the permissions for user "%s". PDO Exception: "%s"', $formValues['dbname'], $formValues['user'], $exception->getMessage()), 1346758663, $exception);
			}
			try {
				$this->connectToDatabase($connectionSettings);
			} catch (\Doctrine\DBAL\DBALException $exception) {
				throw new SetupException(sprintf('Could not connect to database "%s". Please check the permissions for user "%s". DBAL Exception: "%s"', $formValues['dbname'], $formValues['user'], $exception->getMessage()), 1351000864);
			} catch (\PDOException $exception) {
				throw new SetupException(sprintf('Could not connect to database "%s". Please check the permissions for user "%s". PDO Exception: "%s"', $formValues['dbname'], $formValues['user'], $exception->getMessage()), 1346758737);
			}
		}

		$migrationExecuted = Scripts::executeCommand('typo3.flow:doctrine:migrate', $settings, FALSE);
		if ($migrationExecuted !== TRUE) {
			throw new SetupException(sprintf('Could not execute database migrations. Please check the permissions for user "%s" and execute "./flow typo3.flow:doctrine:migrate" manually.', $formValues['user']), 1346759486);
		}

		$this->resetPolicyRolesCacheAfterDatabaseChanges();
	}

	/**
	 * A changed database needs to resynchronize the roles
	 *
	 * @return void
	 */
	public function resetPolicyRolesCacheAfterDatabaseChanges() {
		$this->policyService->reset();
	}

	/**
	 * Tries to connect to the database using the specified $connectionSettings
	 *
	 * @param array $connectionSettings array in the format array('user' => 'dbuser', 'password' => 'dbpassword', 'host' => 'dbhost', 'dbname' => 'dbname')
	 * @return void
	 * @throws \PDOException if the connection fails
	 */
	protected function connectToDatabase(array $connectionSettings) {
		$connection = DriverManager::getConnection($connectionSettings);
		$connection->connect();
	}

	/**
	 * Connects to the database using the specified $connectionSettings
	 * and tries to create a database named $databaseName.
	 *
	 * @param array $connectionSettings array in the format array('user' => 'dbuser', 'password' => 'dbpassword', 'host' => 'dbhost', 'dbname' => 'dbname')
	 * @param string $databaseName name of the database to create
	 * @throws \TYPO3\Setup\Exception
	 * @return void
	 */
	protected function createDatabase(array $connectionSettings, $databaseName) {
		unset($connectionSettings['dbname']);
		$connection = DriverManager::getConnection($connectionSettings);
		$databasePlatform = $connection->getSchemaManager()->getDatabasePlatform();
		$databaseName = $databasePlatform->quoteIdentifier($databaseName);
		if ($databasePlatform instanceof MySqlPlatform) {
			$connection->executeUpdate(sprintf('CREATE DATABASE %s CHARACTER SET utf8 COLLATE utf8_unicode_ci', $databaseName));
		} elseif ($databasePlatform instanceof PostgreSqlPlatform) {
			$connection->executeUpdate(sprintf('CREATE DATABASE %s WITH ENCODING = %s', $databaseName, "'UTF8'"));
		} else {
			throw new SetupException(sprintf('The given database platform "%s" is not supported.', $databasePlatform->getName()), 1386454885);
		}
		$connection->close();
	}
}
namespace TYPO3\Setup\Step;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * 
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class DatabaseStep extends DatabaseStep_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\Setup\Step\DatabaseStep') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Setup\Step\DatabaseStep', $this);
		if ('TYPO3\Setup\Step\DatabaseStep' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}

		if (get_class($this) === 'TYPO3\Setup\Step\DatabaseStep') {
			$this->initializeObject(1);
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\Setup\Step\DatabaseStep') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Setup\Step\DatabaseStep', $this);

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

		if (get_class($this) === 'TYPO3\Setup\Step\DatabaseStep') {
			$this->initializeObject(2);
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
	$reflectedClass = new \ReflectionClass('TYPO3\Setup\Step\DatabaseStep');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Setup\Step\DatabaseStep', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Setup\Step\DatabaseStep', $propertyName, 'var');
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
		$configurationSource_reference = &$this->configurationSource;
		$this->configurationSource = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Configuration\Source\YamlSource');
		if ($this->configurationSource === NULL) {
			$this->configurationSource = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('3ff75d2363593363cb0d0607df40c19a', $configurationSource_reference);
			if ($this->configurationSource === NULL) {
				$this->configurationSource = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('3ff75d2363593363cb0d0607df40c19a',  $configurationSource_reference, 'TYPO3\Flow\Configuration\Source\YamlSource', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Configuration\Source\YamlSource'); });
			}
		}
		$policyService_reference = &$this->policyService;
		$this->policyService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Security\Policy\PolicyService');
		if ($this->policyService === NULL) {
			$this->policyService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('16231078e783810895dba92e364c25f7', $policyService_reference);
			if ($this->policyService === NULL) {
				$this->policyService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('16231078e783810895dba92e364c25f7',  $policyService_reference, 'TYPO3\Flow\Security\Policy\PolicyService', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Security\Policy\PolicyService'); });
			}
		}
		$configurationManager_reference = &$this->configurationManager;
		$this->configurationManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Configuration\ConfigurationManager');
		if ($this->configurationManager === NULL) {
			$this->configurationManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('13edcae8fd67699bb78dadc8c1eac29c', $configurationManager_reference);
			if ($this->configurationManager === NULL) {
				$this->configurationManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('13edcae8fd67699bb78dadc8c1eac29c',  $configurationManager_reference, 'TYPO3\Flow\Configuration\ConfigurationManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Configuration\ConfigurationManager'); });
			}
		}
$this->Flow_Injected_Properties = array (
  0 => 'configurationSource',
  1 => 'policyService',
  2 => 'configurationManager',
);
	}
}
#