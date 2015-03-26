<?php 
namespace TYPO3\Neos\Controller\Module\Administration;

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

/**
 * The TYPO3 User Admin module controller
 *
 * @Flow\Scope("singleton")
 */
class UsersController_Original extends \TYPO3\Neos\Controller\Module\AbstractModuleController {

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
	 * @var \TYPO3\Neos\Domain\Factory\UserFactory
	 */
	protected $userFactory;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Cryptography\HashService
	 */
	protected $hashService;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * @var \TYPO3\Flow\Security\Policy\PolicyService
	 * @Flow\Inject
	 */
	protected $policyService;

	/**
	 * @return void
	 */
	protected function initializeAction() {
		parent::initializeAction();
		if ($this->arguments->hasArgument('account')) {
			$propertyMappingConfigurationForAccount = $this->arguments->getArgument('account')->getPropertyMappingConfiguration();
			$propertyMappingConfigurationForAccountParty = $propertyMappingConfigurationForAccount->forProperty('party');
			$propertyMappingConfigurationForAccountPartyName = $propertyMappingConfigurationForAccount->forProperty('party.name');
			$propertyMappingConfigurationForAccountParty->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_TARGET_TYPE, '\TYPO3\Neos\Domain\Model\User');
			foreach (array($propertyMappingConfigurationForAccountParty, $propertyMappingConfigurationForAccountPartyName) as $propertyMappingConfiguration) {
				$propertyMappingConfiguration->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE);
				$propertyMappingConfiguration->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_MODIFICATION_ALLOWED, TRUE);
			}
		}
	}

	/**
	 * @return void
	 */
	public function indexAction() {
		$accounts = array();
		foreach ($this->accountRepository->findByAuthenticationProviderName('Typo3BackendProvider') as $account) {
			$accounts[$this->persistenceManager->getIdentifierByObject($account)] = $account;
		}
		$this->view->assignMultiple(array(
			'currentAccount' => $this->securityContext->getAccount(),
			'accounts' => $accounts
		));
	}

	/**
	 * @param \TYPO3\Flow\Security\Account $account
	 * @return void
	 */
	public function newAction(\TYPO3\Flow\Security\Account $account = NULL) {
		$this->view->assign('account', $account);
		$this->view->assign('neosRoles', $this->getNeosRoles());
		$this->setTitle($this->moduleConfiguration['label'] . ' :: ' . ucfirst($this->request->getControllerActionName()));
	}

	/**
	 * @param string $identifier
	 * @Flow\Validate(argumentName="identifier", type="NotEmpty")
	 * @Flow\Validate(argumentName="identifier", type="StringLength", options={ "minimum"=1, "maximum"=255 })
	 * @Flow\Validate(argumentName="identifier", type="\TYPO3\Neos\Validation\Validator\AccountExistsValidator", options={ "authenticationProviderName"="Typo3BackendProvider" })
	 * @param array $password
	 * @Flow\Validate(argumentName="password", type="\TYPO3\Neos\Validation\Validator\PasswordValidator", options={ "allowEmpty"=0, "minimum"=1, "maximum"=255 })
	 * @param string $firstName
	 * @Flow\Validate(argumentName="firstName", type="NotEmpty")
	 * @Flow\Validate(argumentName="firstName", type="StringLength", options={ "minimum"=1, "maximum"=255 })
	 * @param string $lastName
	 * @Flow\Validate(argumentName="lastName", type="NotEmpty")
	 * @Flow\Validate(argumentName="lastName", type="StringLength", options={ "minimum"=1, "maximum"=255 })
	 * @param string $roleIdentifier The role identifier of the role this user should have
	 * @return void
	 * @todo Security
	 */
	public function createAction($identifier, array $password, $firstName, $lastName, $roleIdentifier) {
		$password = array_shift($password);

		$user = $this->userFactory->create($identifier, $password, $firstName, $lastName, array($roleIdentifier));

		$this->partyRepository->add($user);
		$accounts = $user->getAccounts();
		foreach ($accounts as $account) {
			$this->accountRepository->add($account);
		}

		$this->redirect('index');
	}

	/**
	 * @param \TYPO3\Flow\Security\Account $account
	 * @return void
	 */
	public function editAction(\TYPO3\Flow\Security\Account $account) {
		$this->assignElectronicAddressOptions();

		$currentRole = NULL;
		foreach ($account->getRoles() as $role) {
			if ($role->getPackageKey() === 'TYPO3.Neos') {
				$currentRole = $role;
				break;
			}
		}

		$this->view->assignMultiple(array(
			'account' => $account,
			'person' => $account->getParty(),
			'neosRoles' => $this->getNeosRoles(),
			'currentRole' => $currentRole
		));
		$this->setTitle($this->moduleConfiguration['label'] . ' :: ' . ucfirst($this->request->getControllerActionName()));
	}

	/**
	 * @param \TYPO3\Flow\Security\Account $account
	 * @return void
	 */
	public function showAction(\TYPO3\Flow\Security\Account $account) {
		$this->view->assign('account', $account);
		$this->view->assign('currentAccount', $this->securityContext->getAccount());
		$this->setTitle($this->moduleConfiguration['label'] . ' :: ' . ucfirst($this->request->getControllerActionName()));
	}

	/**
	 * @param \TYPO3\Flow\Security\Account $account
	 * @param \TYPO3\Party\Domain\Model\Person $person
	 * @param array $password
	 * @param string $roleIdentifier The role indentifier of the role this user should have
	 * @Flow\Validate(argumentName="password", type="\TYPO3\Neos\Validation\Validator\PasswordValidator", options={ "allowEmpty"=1, "minimum"=1, "maximum"=255 })
	 * @return void
	 * @todo Handle validation errors for account (accountIdentifier) & check if there's another account with the same accountIdentifier when changing it
	 * @todo Security
	 */
	public function updateAction(\TYPO3\Flow\Security\Account $account, \TYPO3\Party\Domain\Model\Person $person, array $password = array(), $roleIdentifier) {
		$password = array_shift($password);
		if (strlen(trim(strval($password))) > 0) {
			$account->setCredentialsSource($this->hashService->hashPassword($password, 'default'));
		}

		$account->setRoles(array($this->policyService->getRole($roleIdentifier)));

		$this->accountRepository->update($account);
		$this->partyRepository->update($person);

		$this->addFlashMessage('The user profile has been updated.');
		$this->redirect('index');
	}

	/**
	 * @param \TYPO3\Flow\Security\Account $account
	 * @return void
	 * @todo Security
	 */
	public function deleteAction(\TYPO3\Flow\Security\Account $account) {
		if ($this->securityContext->getAccount() === $account) {
			$this->addFlashMessage('You can not remove current logged in user');
			$this->redirect('index');
		}
		$this->accountRepository->remove($account);
		$this->addFlashMessage('The user has been deleted.');
		$this->redirect('index');
	}

	/**
	 * The add new electronic address action
	 *
	 * @param \TYPO3\Flow\Security\Account $account
	 * @Flow\IgnoreValidation("$account")
	 * @return void
	 */
	public function newElectronicAddressAction(\TYPO3\Flow\Security\Account $account) {
		$this->assignElectronicAddressOptions();
		$this->view->assign('account', $account);
	}

	/**
	 * Create an new electronic address
	 *
	 * @param \TYPO3\Flow\Security\Account $account
	 * @param \TYPO3\Party\Domain\Model\ElectronicAddress $electronicAddress
	 * @return void
	 * @todo Security
	 */
	public function createElectronicAddressAction(\TYPO3\Flow\Security\Account $account, \TYPO3\Party\Domain\Model\ElectronicAddress $electronicAddress) {
		$party = $account->getParty();
		$party->addElectronicAddress($electronicAddress);
		$this->partyRepository->update($party);
		$this->addFlashMessage(sprintf(
			'An electronic "%s" address has been added.',
			$electronicAddress->getType() . ' (' . $electronicAddress->getIdentifier() . ')'
		));
		$this->redirect('edit', NULL, NULL, array('account' => $account));
	}

	/**
	 * Delete an electronic address action
	 *
	 * @param \TYPO3\Flow\Security\Account $account
	 * @param \TYPO3\Party\Domain\Model\ElectronicAddress $electronicAddress
	 * @return void
	 * @todo Security
	 */
	public function deleteElectronicAddressAction(\TYPO3\Flow\Security\Account $account, \TYPO3\Party\Domain\Model\ElectronicAddress $electronicAddress) {
		$party = $account->getParty();
		$party->removeElectronicAddress($electronicAddress);
		$this->partyRepository->update($party);
		$this->addFlashMessage(sprintf(
			'The electronic address "%s" has been deleted for the person "%s".',
			$electronicAddress->getType() . ' (' . $electronicAddress->getIdentifier() . ')',
			$party->getName()
		));
		$this->redirect('edit', NULL, NULL, array('account' => $account));
	}

	/**
	 *  @return void
	 */
	protected function assignElectronicAddressOptions() {
		$electronicAddress = new \TYPO3\Party\Domain\Model\ElectronicAddress();
		$electronicAddressTypes = array();
		foreach ($electronicAddress->getAvailableElectronicAddressTypes() as $type) {
			$electronicAddressTypes[$type] = $type;
		}
		$electronicAddressUsageTypes = array();
		foreach ($electronicAddress->getAvailableUsageTypes() as $type) {
			$electronicAddressUsageTypes[$type] = $type;
		}
		array_unshift($electronicAddressUsageTypes, '');
		$this->view->assignMultiple(array(
			'electronicAddressTypes' => $electronicAddressTypes,
			'electronicAddressUsageTypes' => $electronicAddressUsageTypes
		));
	}

	/**
	 * Returns all roles defined in the Neos package
	 *
	 * @return array<\TYPO3\Flow\Security\Policy\Role>
	 */
	protected function getNeosRoles() {
		$neosRoles = array();
		foreach ($this->policyService->getRoles() as $role) {
			if ($role->getPackageKey() === 'TYPO3.Neos') {
				$neosRoles[] = $role;
			}
		}
		return $neosRoles;
	}
}
namespace TYPO3\Neos\Controller\Module\Administration;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * The TYPO3 User Admin module controller
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class UsersController extends UsersController_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {

	private $Flow_Aop_Proxy_targetMethodsAndGroupedAdvices = array();

	private $Flow_Aop_Proxy_groupedAdviceChains = array();

	private $Flow_Aop_Proxy_methodIsInAdviceMode = array();


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {

		$this->Flow_Aop_Proxy_buildMethodsAndAdvicesArray();
		if (get_class($this) === 'TYPO3\Neos\Controller\Module\Administration\UsersController') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Controller\Module\Administration\UsersController', $this);
		if ('TYPO3\Neos\Controller\Module\Administration\UsersController' === get_class($this)) {
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
			'indexAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'newAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'createAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'editAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'showAction' => array(
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
			'newElectronicAddressAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'createElectronicAddressAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'deleteElectronicAddressAction' => array(
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
		if (get_class($this) === 'TYPO3\Neos\Controller\Module\Administration\UsersController') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Controller\Module\Administration\UsersController', $this);

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
	 public function indexAction() {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['indexAction'])) {
		$result = parent::indexAction();

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['indexAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('indexAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Module\Administration\UsersController', 'indexAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['indexAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['indexAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param \TYPO3\Flow\Security\Account $account
	 * @return void
	 */
	 public function newAction(\TYPO3\Flow\Security\Account $account = NULL) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['newAction'])) {
		$result = parent::newAction($account);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['newAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['account'] = $account;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('newAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Module\Administration\UsersController', 'newAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['newAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['newAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param string $identifier
	 * @param array $password
	 * @param string $firstName
	 * @param string $lastName
	 * @param string $roleIdentifier The role identifier of the role this user should have
	 * @return void
	 * @\TYPO3\Flow\Annotations\Validate(type="NotEmpty", argumentName="identifier")
	 * @\TYPO3\Flow\Annotations\Validate(type="StringLength", options={ "minimum"=1, "maximum"=255 }, argumentName="identifier")
	 * @\TYPO3\Flow\Annotations\Validate(type="\TYPO3\Neos\Validation\Validator\AccountExistsValidator", options={ "authenticationProviderName"="Typo3BackendProvider" }, argumentName="identifier")
	 * @\TYPO3\Flow\Annotations\Validate(type="\TYPO3\Neos\Validation\Validator\PasswordValidator", options={ "allowEmpty"=0, "minimum"=1, "maximum"=255 }, argumentName="password")
	 * @\TYPO3\Flow\Annotations\Validate(type="NotEmpty", argumentName="firstName")
	 * @\TYPO3\Flow\Annotations\Validate(type="StringLength", options={ "minimum"=1, "maximum"=255 }, argumentName="firstName")
	 * @\TYPO3\Flow\Annotations\Validate(type="NotEmpty", argumentName="lastName")
	 * @\TYPO3\Flow\Annotations\Validate(type="StringLength", options={ "minimum"=1, "maximum"=255 }, argumentName="lastName")
	 */
	 public function createAction($identifier, array $password, $firstName, $lastName, $roleIdentifier) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['createAction'])) {
		$result = parent::createAction($identifier, $password, $firstName, $lastName, $roleIdentifier);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['createAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['identifier'] = $identifier;
				$methodArguments['password'] = $password;
				$methodArguments['firstName'] = $firstName;
				$methodArguments['lastName'] = $lastName;
				$methodArguments['roleIdentifier'] = $roleIdentifier;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('createAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Module\Administration\UsersController', 'createAction', $methodArguments, $adviceChain);
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
	 * @param \TYPO3\Flow\Security\Account $account
	 * @return void
	 */
	 public function editAction(\TYPO3\Flow\Security\Account $account) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['editAction'])) {
		$result = parent::editAction($account);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['editAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['account'] = $account;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('editAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Module\Administration\UsersController', 'editAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['editAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['editAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param \TYPO3\Flow\Security\Account $account
	 * @return void
	 */
	 public function showAction(\TYPO3\Flow\Security\Account $account) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['showAction'])) {
		$result = parent::showAction($account);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['showAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['account'] = $account;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('showAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Module\Administration\UsersController', 'showAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['showAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['showAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param \TYPO3\Flow\Security\Account $account
	 * @param \TYPO3\Party\Domain\Model\Person $person
	 * @param array $password
	 * @param string $roleIdentifier The role indentifier of the role this user should have
	 * @return void
	 * @\TYPO3\Flow\Annotations\Validate(type="\TYPO3\Neos\Validation\Validator\PasswordValidator", options={ "allowEmpty"=1, "minimum"=1, "maximum"=255 }, argumentName="password")
	 */
	 public function updateAction(\TYPO3\Flow\Security\Account $account, \TYPO3\Party\Domain\Model\Person $person, array $password, $roleIdentifier) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['updateAction'])) {
		$result = parent::updateAction($account, $person, $password, $roleIdentifier);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['updateAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['account'] = $account;
				$methodArguments['person'] = $person;
				$methodArguments['password'] = $password;
				$methodArguments['roleIdentifier'] = $roleIdentifier;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('updateAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Module\Administration\UsersController', 'updateAction', $methodArguments, $adviceChain);
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
	 * @param \TYPO3\Flow\Security\Account $account
	 * @return void
	 */
	 public function deleteAction(\TYPO3\Flow\Security\Account $account) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['deleteAction'])) {
		$result = parent::deleteAction($account);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['deleteAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['account'] = $account;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('deleteAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Module\Administration\UsersController', 'deleteAction', $methodArguments, $adviceChain);
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
	 * @param \TYPO3\Flow\Security\Account $account
	 * @return void
	 * @\TYPO3\Flow\Annotations\IgnoreValidation(argumentName="account")
	 */
	 public function newElectronicAddressAction(\TYPO3\Flow\Security\Account $account) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['newElectronicAddressAction'])) {
		$result = parent::newElectronicAddressAction($account);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['newElectronicAddressAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['account'] = $account;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('newElectronicAddressAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Module\Administration\UsersController', 'newElectronicAddressAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['newElectronicAddressAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['newElectronicAddressAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param \TYPO3\Flow\Security\Account $account
	 * @param \TYPO3\Party\Domain\Model\ElectronicAddress $electronicAddress
	 * @return void
	 */
	 public function createElectronicAddressAction(\TYPO3\Flow\Security\Account $account, \TYPO3\Party\Domain\Model\ElectronicAddress $electronicAddress) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['createElectronicAddressAction'])) {
		$result = parent::createElectronicAddressAction($account, $electronicAddress);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['createElectronicAddressAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['account'] = $account;
				$methodArguments['electronicAddress'] = $electronicAddress;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('createElectronicAddressAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Module\Administration\UsersController', 'createElectronicAddressAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['createElectronicAddressAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['createElectronicAddressAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param \TYPO3\Flow\Security\Account $account
	 * @param \TYPO3\Party\Domain\Model\ElectronicAddress $electronicAddress
	 * @return void
	 */
	 public function deleteElectronicAddressAction(\TYPO3\Flow\Security\Account $account, \TYPO3\Party\Domain\Model\ElectronicAddress $electronicAddress) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['deleteElectronicAddressAction'])) {
		$result = parent::deleteElectronicAddressAction($account, $electronicAddress);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['deleteElectronicAddressAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['account'] = $account;
				$methodArguments['electronicAddress'] = $electronicAddress;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('deleteElectronicAddressAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Module\Administration\UsersController', 'deleteElectronicAddressAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['deleteElectronicAddressAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['deleteElectronicAddressAction']);
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
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\Controller\Module\Administration\UsersController');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\Controller\Module\Administration\UsersController', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\Controller\Module\Administration\UsersController', $propertyName, 'var');
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
		$userFactory_reference = &$this->userFactory;
		$this->userFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Neos\Domain\Factory\UserFactory');
		if ($this->userFactory === NULL) {
			$this->userFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('35afcf9b8e5b8ee4a93d520d23245e7b', $userFactory_reference);
			if ($this->userFactory === NULL) {
				$this->userFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('35afcf9b8e5b8ee4a93d520d23245e7b',  $userFactory_reference, 'TYPO3\Neos\Domain\Factory\UserFactory', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Neos\Domain\Factory\UserFactory'); });
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
		$securityContext_reference = &$this->securityContext;
		$this->securityContext = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Security\Context');
		if ($this->securityContext === NULL) {
			$this->securityContext = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('48836470c14129ade5f39e28c4816673', $securityContext_reference);
			if ($this->securityContext === NULL) {
				$this->securityContext = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('48836470c14129ade5f39e28c4816673',  $securityContext_reference, 'TYPO3\Flow\Security\Context', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Security\Context'); });
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
  1 => 'accountRepository',
  2 => 'partyRepository',
  3 => 'userFactory',
  4 => 'hashService',
  5 => 'securityContext',
  6 => 'policyService',
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