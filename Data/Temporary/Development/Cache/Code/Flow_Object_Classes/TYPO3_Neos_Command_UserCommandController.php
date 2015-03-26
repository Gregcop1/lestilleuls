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
use TYPO3\Flow\Security\Account;
use TYPO3\Flow\Security\Policy\Role;
use TYPO3\Party\Domain\Model\Person;
use TYPO3\Flow\Utility\Now;

/**
 * The User Command Controller
 *
 * @Flow\Scope("singleton")
 */
class UserCommandController_Original extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\AccountRepository
	 */
	protected $accountRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Party\Domain\Repository\PartyRepository
	 */
	protected $partyRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Cryptography\HashService
	 */
	protected $hashService;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Neos\Domain\Factory\UserFactory
	 */
	protected $userFactory;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Policy\PolicyService
	 */
	protected $policyService;

	/**
	 * List all users
	 *
	 * @param string $authenticationProvider Name of the authentication provider to use
	 * @return void
	 */
	public function listCommand($authenticationProvider = 'Typo3BackendProvider') {
		$accounts = $this->accountRepository->findByAuthenticationProviderName($authenticationProvider);

		$activeAccounts = array();
		$inactiveAccounts = array();
		foreach ($accounts as $account) {
			/** @var Account $account */
			$accountIdentifier = $account->getAccountIdentifier();
			$expirationDate = $account->getExpirationDate();
			if ($expirationDate === NULL || $expirationDate > new Now()) {
				$activeAccounts[$accountIdentifier] = $account;
			} else {
				$inactiveAccounts[$accountIdentifier] = $account;
			}
		}

		ksort($activeAccounts);
		ksort($inactiveAccounts);

		$this->outputLine('ACTIVE USERS:');
		foreach ($activeAccounts as $accountIdentifier => $account) {
			$this->outputLine(' ' . $accountIdentifier);
		}

		if (count($inactiveAccounts) > 0) {
			$this->outputLine();
			$this->outputLine('INACTIVE USERS:');
			foreach ($inactiveAccounts as $accountIdentifier => $account) {
				$this->outputLine(' ' . $accountIdentifier);
			}
		}
	}

	/**
	 * Create a new user
	 *
	 * This command creates a new user which has access to the backend user interface.
	 * It is recommended to user the email address as a username.
	 *
	 * @param string $username The username of the user to be created.
	 * @param string $password Password of the user to be created
	 * @param string $firstName First name of the user to be created
	 * @param string $lastName Last name of the user to be created
	 * @param string $roles A comma separated list of roles to assign
	 * @param string $authenticationProvider Name of the authentication provider to use
	 * @return void
	 */
	public function createCommand($username, $password, $firstName, $lastName, $roles = NULL, $authenticationProvider = 'Typo3BackendProvider') {
		$account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($username, $authenticationProvider);
		if ($account instanceof Account) {
			$this->outputLine('The username "%s" is already in use', array($username));
			exit(1);
		}

		if (empty($roles)) {
			$roleIdentifiers = array('TYPO3.Neos:Editor');
		} else {
			$roleIdentifiers = \TYPO3\Flow\Utility\Arrays::trimExplode(',', $roles);
			foreach ($roleIdentifiers as &$role) {
				if (strpos($role, '.') === FALSE) {
					$role = 'TYPO3.Neos:' . $role;
				}
			}
		}

		try {
			$user = $this->userFactory->create($username, $password, $firstName, $lastName, $roleIdentifiers, $authenticationProvider);
			$this->partyRepository->add($user);
			$accounts = $user->getAccounts();
			foreach ($accounts as $account) {
				$this->accountRepository->add($account);
			}

			$this->outputLine('Created user "%s".', array($username));
		} catch (\TYPO3\Flow\Security\Exception\NoSuchRoleException $exception) {
			$this->outputLine($exception->getMessage());
			exit(1);
		}
	}

	/**
	 * Remove a user which has access to the backend user interface.
	 *
	 * @param string $username The username of the user to be removed.
	 * @param boolean $confirmation
	 * @param string $authenticationProvider Name of the authentication provider to use
	 * @return void
	 */
	public function removeCommand($username, $confirmation = FALSE, $authenticationProvider = 'Typo3BackendProvider') {
		if ($confirmation === FALSE) {
			$this->outputLine('Please confirm that you really want to remove the user from the database.');
			$this->outputLine('');
			$this->outputLine('Syntax:');
			$this->outputLine('  ./flow user:remove --username <username> --confirmation TRUE');
			exit(1);
		}

		$account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($username, $authenticationProvider);
		if (!$account instanceof Account) {
			$this->outputLine('The username "%s" is not in use', array($username));
			exit(1);
		}
		$this->accountRepository->remove($account);
		$this->outputLine('Removed user "%s".', array($username));
	}

	/**
	 * Activate a user which has access to the backend user interface.
	 *
	 * @param string $username The username of the user to be activated.
	 * @param string $authenticationProvider Name of the authentication provider to use
	 * @return void
	 */
	public function activateCommand($username, $authenticationProvider = 'Typo3BackendProvider') {
		$account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($username, $authenticationProvider);
		if (!$account instanceof Account) {
			$this->outputLine('The username "%s" is not in use', array($username));
			exit(1);
		}
		$account->setExpirationDate(NULL);
		$this->accountRepository->update($account);
		$this->outputLine('Activated user "%s".', array($username));
	}

	/**
	 * Deactivate a user which has access to the backend user interface.
	 *
	 * @param string $username The username of the user to be deactivated.
	 * @param string $authenticationProvider Name of the authentication provider to use
	 * @return void
	 */
	public function deactivateCommand($username, $authenticationProvider = 'Typo3BackendProvider') {
		$account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($username, $authenticationProvider);
		if (!$account instanceof Account) {
			$this->outputLine('The username "%s" is not in use', array($username));
			exit(1);
		}
		$account->setExpirationDate(new Now());
		$this->accountRepository->update($account);
		$this->outputLine('Deactivated user "%s".', array($username));
	}

	/**
	 * Set a new password for the given user
	 *
	 * This allows for setting a new password for an existing user account.
	 *
	 * @param string $username Username of the account to modify
	 * @param string $password The new password
	 * @param string $authenticationProvider Name of the authentication provider to use
	 * @return void
	 */
	public function setPasswordCommand($username, $password, $authenticationProvider = 'Typo3BackendProvider') {
		$account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($username, $authenticationProvider);
		if (!$account instanceof Account) {
			$this->outputLine('User "%s" does not exists.', array($username));
			exit(1);
		}
		$account->setCredentialsSource($this->hashService->hashPassword($password, 'default'));
		$this->accountRepository->update($account);

		$this->outputLine('The new password for user "%s" was set.', array($username));
	}

	/**
	 * Add a role to a user
	 *
	 * This command allows for adding a specific role to an existing user.
	 * Currently supported roles: "TYPO3.Neos:Editor", "TYPO3.Neos:Administrator"
	 *
	 * @param string $username The username of the user
	 * @param string $role Role ot be added to the use
	 * @param string $authenticationProvider Name of the authentication provider to user
	 * @return void
	 */
	public function addRoleCommand($username, $role, $authenticationProvider = 'Typo3BackendProvider') {
		$account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($username, $authenticationProvider);
		if (!$account instanceof Account) {
			$this->outputLine('User "%s" does not exists.', array($username));
			exit(1);
		}

		if (strpos($role, '.') === FALSE) {
			$role = 'TYPO3.Neos:' . $role;
		}
		$roleObject = $this->policyService->getRole($role);
		if ($roleObject === NULL) {
			$this->outputLine('Role "%s" does not exist.', array($role));
			exit(1);
		}

		if ($account->hasRole($roleObject)) {
			$this->outputLine('User "%s" already has the role "%s" assigned.', array($username, $role));
			exit(1);
		}

		$account->addRole($roleObject);
		$this->accountRepository->update($account);
		$this->outputLine('Added role "%s" to user "%s".', array($role, $username));
	}

	/**
	 * Remove a role from a user
	 *
	 * @param string $username The username of the user
	 * @param string $role Role ot be removed from the user
	 * @param string $authenticationProvider Name of the authentication provider to use
	 * @return void
	 */
	public function removeRoleCommand($username, $role, $authenticationProvider = 'Typo3BackendProvider') {
		$account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($username, $authenticationProvider);
		if (!$account instanceof Account) {
			$this->outputLine('User "%s" does not exists.', array($username));
			exit(1);
		}

		if (strpos($role, '.') === FALSE) {
			$role = 'TYPO3.Neos:' . $role;
		}

		$roleObject = $this->policyService->getRole($role);
		if ($roleObject === NULL) {
			$this->outputLine('Role "%s" does not exist.', array($role));
			exit(1);
		}

		if (!$account->hasRole($roleObject)) {
			$this->outputLine('User "%s" does not have the role "%s" assigned.', array($username, $role));
			exit(1);
		}

		$account->removeRole($roleObject);
		$this->accountRepository->update($account);
		$this->outputLine('Removed role "%s" from user "%s".', array($role, $username));
	}

	/**
	 * Shows the given user
	 *
	 * This command shows some basic details about the given user. If such a user does not exist, this command
	 * will exit with a non-zero status code.
	 *
	 * @param string $username The username of the user to show.
	 * @param string $authenticationProvider Name of the authentication provider to use
	 * @return void
	 */
	public function showCommand($username, $authenticationProvider = 'Typo3BackendProvider') {
		$account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($username, $authenticationProvider);
		if (!$account instanceof Account) {
			$this->outputLine('The username "%s" is not in use', array($username));
			exit(1);
		}

		$roleNames = array();
		foreach ($account->getRoles() as $role) {
			/** @var Role $role */
			$roleNames[] = $role->getIdentifier();
		}

		$this->outputLine('Username:  %s', array($username));
		$this->outputLine('Roles:     %s', array(implode(', ', $roleNames)));

		$party = $account->getParty();
		if ($party instanceof Person) {
			$this->outputLine('Name:      %s', array($party->getName()->getFullName()));
			$this->outputLine('Email:     %s', array($party->getPrimaryElectronicAddress()));
		}
	}

}
namespace TYPO3\Neos\Command;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * The User Command Controller
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class UserCommandController extends UserCommandController_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\Neos\Command\UserCommandController') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Command\UserCommandController', $this);
		parent::__construct();
		if ('TYPO3\Neos\Command\UserCommandController' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\Neos\Command\UserCommandController') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Command\UserCommandController', $this);

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
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\Command\UserCommandController');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\Command\UserCommandController', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\Command\UserCommandController', $propertyName, 'var');
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
		$accountRepository_reference = &$this->accountRepository;
		$this->accountRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Security\AccountRepository');
		if ($this->accountRepository === NULL) {
			$this->accountRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('d68c73088546244eb016f396195a461c', $accountRepository_reference);
			if ($this->accountRepository === NULL) {
				$this->accountRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('d68c73088546244eb016f396195a461c',  $accountRepository_reference, 'TYPO3\Flow\Security\AccountRepository', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Security\AccountRepository'); });
			}
		}
		$partyRepository_reference = &$this->partyRepository;
		$this->partyRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Party\Domain\Repository\PartyRepository');
		if ($this->partyRepository === NULL) {
			$this->partyRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('ab219dc818a4e84959d27bb17ce67d6e', $partyRepository_reference);
			if ($this->partyRepository === NULL) {
				$this->partyRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('ab219dc818a4e84959d27bb17ce67d6e',  $partyRepository_reference, 'TYPO3\Party\Domain\Repository\PartyRepository', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Party\Domain\Repository\PartyRepository'); });
			}
		}
		$hashService_reference = &$this->hashService;
		$this->hashService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Security\Cryptography\HashService');
		if ($this->hashService === NULL) {
			$this->hashService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('af606f3838da2ad86bf0ed2ff61be394', $hashService_reference);
			if ($this->hashService === NULL) {
				$this->hashService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('af606f3838da2ad86bf0ed2ff61be394',  $hashService_reference, 'TYPO3\Flow\Security\Cryptography\HashService', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Security\Cryptography\HashService'); });
			}
		}
		$userFactory_reference = &$this->userFactory;
		$this->userFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Neos\Domain\Factory\UserFactory');
		if ($this->userFactory === NULL) {
			$this->userFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('35afcf9b8e5b8ee4a93d520d23245e7b', $userFactory_reference);
			if ($this->userFactory === NULL) {
				$this->userFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('35afcf9b8e5b8ee4a93d520d23245e7b',  $userFactory_reference, 'TYPO3\Neos\Domain\Factory\UserFactory', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Neos\Domain\Factory\UserFactory'); });
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
$this->Flow_Injected_Properties = array (
  0 => 'reflectionService',
  1 => 'accountRepository',
  2 => 'partyRepository',
  3 => 'hashService',
  4 => 'userFactory',
  5 => 'policyService',
);
	}
}
#