<?php 
namespace TYPO3\Neos\Setup\Step;

/*                                                                        *
 * This script belongs to the Flow package "TYPO3.Neos".                  *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class SiteImportStep_Original extends \TYPO3\Setup\Step\AbstractStep {

	/**
	 * @var boolean
	 */
	protected $optional = TRUE;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Package\PackageManagerInterface
	 */
	protected $packageManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Neos\Domain\Repository\SiteRepository
	 */
	protected $siteRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Neos\Domain\Service\SiteImportService
	 */
	protected $siteImportService;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Neos\Domain\Repository\DomainRepository
	 */
	protected $domainRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Domain\Repository\NodeDataRepository
	 */
	protected $nodeDataRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Domain\Repository\WorkspaceRepository
	 */
	protected $workspaceRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 */
	protected $persistenceManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Mvc\FlashMessageContainer
	 */
	protected $flashMessageContainer;

	/**
	 * @var \TYPO3\Form\Finishers\ClosureFinisher
	 */
	protected $closureFinisher;

	/**
	 * @var \TYPO3\Flow\Log\SystemLoggerInterface
	 * @Flow\Inject
	 */
	protected $systemLogger;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface
	 */
	protected $contextFactory;

	/**
	 * Returns the form definitions for the step
	 *
	 * @param \TYPO3\Form\Core\Model\FormDefinition $formDefinition
	 * @return void
	 */
	protected function buildForm(\TYPO3\Form\Core\Model\FormDefinition $formDefinition) {
		$page1 = $formDefinition->createPage('page1');
		$page1->setRenderingOption('header', 'Create a new site');

		$introduction = $page1->createElement('introduction', 'TYPO3.Form:StaticText');
		$introduction->setProperty('text', 'There are two ways of creating a site. Choose between the following:');

		$importSection = $page1->createElement('import', 'TYPO3.Form:Section');
		$importSection->setLabel('Import a site from an existing site package');

		$sitePackages = array();
		foreach ($this->packageManager->getFilteredPackages('available', NULL, 'typo3-flow-site') as $package) {
			$sitePackages[$package->getPackageKey()] = $package->getPackageKey();
		}

		if (count($sitePackages) > 0) {
			$site = $importSection->createElement('site', 'TYPO3.Form:SingleSelectDropdown');
			$site->setLabel('Select a site package');
			$site->setProperty('options', $sitePackages);
			$site->addValidator(new \TYPO3\Flow\Validation\Validator\NotEmptyValidator());

			$sites = $this->siteRepository->findAll();
			if ($sites->count() > 0) {
				$prune = $importSection->createElement('prune', 'TYPO3.Form:Checkbox');
				$prune->setLabel('Delete existing sites');
			}
		} else {
			$error = $importSection->createElement('noSitePackagesError', 'TYPO3.Form:StaticText');
			$error->setProperty('text', 'No site packages were available, make sure you have an active site package');
			$error->setProperty('elementClassAttribute', 'alert alert-warning');
		}

		if ($this->packageManager->isPackageActive('TYPO3.Neos.Kickstarter')) {
			$separator = $page1->createElement('separator', 'TYPO3.Form:StaticText');
			$separator->setProperty('elementClassAttribute', 'section-separator');

			$newPackageSection = $page1->createElement('newPackageSection', 'TYPO3.Form:Section');
			$newPackageSection->setLabel('Create a new site package with a dummy site');
			$packageName = $newPackageSection->createElement('packageKey', 'TYPO3.Form:SingleLineText');
			$packageName->setLabel('Package Name (in form "Vendor.DomainCom")');
			$packageName->addValidator(new \TYPO3\Neos\Validation\Validator\PackageKeyValidator());

			$siteName = $newPackageSection->createElement('siteName', 'TYPO3.Form:SingleLineText');
			$siteName->setLabel('Site Name (e.g. "domain.com")');
		} else {
			$error = $importSection->createElement('neosKickstarterUnavailableError', 'TYPO3.Form:StaticText');
			$error->setProperty('text', 'The Neos Kickstarter package (TYPO3.Neos.Kickstarter) is not installed, install it for kickstarting new sites (using "composer require typo3/neos-kickstarter")');
			$error->setProperty('elementClassAttribute', 'alert alert-warning');
		}

		$explanation = $page1->createElement('explanation', 'TYPO3.Form:StaticText');
		$explanation->setProperty('text', 'Notice the difference between a site package and a site. A site package is a Flow package that can be used for creating multiple site instances.');
		$explanation->setProperty('elementClassAttribute', 'alert alert-info');

		$step = $this;
		$callback = function(\TYPO3\Form\Core\Model\FinisherContext $finisherContext) use ($step) {
			$step->importSite($finisherContext);
		};
		$this->closureFinisher = new \TYPO3\Form\Finishers\ClosureFinisher();
		$this->closureFinisher->setOption('closure', $callback);
		$formDefinition->addFinisher($this->closureFinisher);

		$formDefinition->setRenderingOption('skipStepNotice', 'You can always import a site using the site:import command');
	}

	/**
	 * @param \TYPO3\Form\Core\Model\FinisherContext $finisherContext
	 * @return void
	 * @throws \TYPO3\Setup\Exception
	 */
	public function importSite(\TYPO3\Form\Core\Model\FinisherContext $finisherContext) {
		$formValues = $finisherContext->getFormRuntime()->getFormState()->getFormValues();

		if (isset($formValues['prune']) && intval($formValues['prune']) === 1) {
			$this->nodeDataRepository->removeAll();
			$this->workspaceRepository->removeAll();
			$this->domainRepository->removeAll();
			$this->siteRepository->removeAll();
			$this->persistenceManager->persistAll();
		}

		if (!empty($formValues['packageKey'])) {
			if ($this->packageManager->isPackageAvailable($formValues['packageKey'])) {
				throw new \TYPO3\Setup\Exception(sprintf('The package key "%s" already exists.', $formValues['packageKey']), 1346759486);
			}
			$packageKey = $formValues['packageKey'];
			$siteName = $formValues['siteName'];

			$generatorService = $this->objectManager->get('TYPO3\Neos\Kickstarter\Service\GeneratorService');
			$generatorService->generateSitePackage($packageKey, $siteName);
			$this->packageManager->activatePackage($packageKey);
		} elseif (!empty($formValues['site'])) {
			$packageKey = $formValues['site'];
		}
		if (!empty($packageKey)) {
			try {
				$contentContext = $this->contextFactory->create(array('workspaceName' => 'live'));
				$this->siteImportService->importFromPackage($packageKey, $contentContext);
			} catch (\Exception $exception) {
				$finisherContext->cancel();
				$this->systemLogger->logException($exception);
				$this->flashMessageContainer->addMessage(new \TYPO3\Flow\Error\Error(sprintf('Error: During the import of the "Sites.xml" from the package "%s" an exception occurred: %s', $packageKey, $exception->getMessage())));
			}
		}
	}

}
namespace TYPO3\Neos\Setup\Step;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * 
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class SiteImportStep extends SiteImportStep_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\Neos\Setup\Step\SiteImportStep') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Setup\Step\SiteImportStep', $this);
		if ('TYPO3\Neos\Setup\Step\SiteImportStep' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}

		if (get_class($this) === 'TYPO3\Neos\Setup\Step\SiteImportStep') {
			$this->initializeObject(1);
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\Neos\Setup\Step\SiteImportStep') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Setup\Step\SiteImportStep', $this);

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

		if (get_class($this) === 'TYPO3\Neos\Setup\Step\SiteImportStep') {
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
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\Setup\Step\SiteImportStep');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\Setup\Step\SiteImportStep', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\Setup\Step\SiteImportStep', $propertyName, 'var');
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
		$packageManager_reference = &$this->packageManager;
		$this->packageManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Package\PackageManagerInterface');
		if ($this->packageManager === NULL) {
			$this->packageManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('aad0cdb65adb124cf4b4d16c5b42256c', $packageManager_reference);
			if ($this->packageManager === NULL) {
				$this->packageManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('aad0cdb65adb124cf4b4d16c5b42256c',  $packageManager_reference, 'TYPO3\Flow\Package\PackageManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Package\PackageManagerInterface'); });
			}
		}
		$siteRepository_reference = &$this->siteRepository;
		$this->siteRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Neos\Domain\Repository\SiteRepository');
		if ($this->siteRepository === NULL) {
			$this->siteRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('5c3f2ab0e14ff0be3090c1f3efe77d7a', $siteRepository_reference);
			if ($this->siteRepository === NULL) {
				$this->siteRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('5c3f2ab0e14ff0be3090c1f3efe77d7a',  $siteRepository_reference, 'TYPO3\Neos\Domain\Repository\SiteRepository', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Neos\Domain\Repository\SiteRepository'); });
			}
		}
		$siteImportService_reference = &$this->siteImportService;
		$this->siteImportService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Neos\Domain\Service\SiteImportService');
		if ($this->siteImportService === NULL) {
			$this->siteImportService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('a382bdbc7e75d00f0510a58eb9dd5b14', $siteImportService_reference);
			if ($this->siteImportService === NULL) {
				$this->siteImportService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('a382bdbc7e75d00f0510a58eb9dd5b14',  $siteImportService_reference, 'TYPO3\Neos\Domain\Service\SiteImportService', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Neos\Domain\Service\SiteImportService'); });
			}
		}
		$domainRepository_reference = &$this->domainRepository;
		$this->domainRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Neos\Domain\Repository\DomainRepository');
		if ($this->domainRepository === NULL) {
			$this->domainRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('6f2987c5f47777b01540a314d984b09c', $domainRepository_reference);
			if ($this->domainRepository === NULL) {
				$this->domainRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('6f2987c5f47777b01540a314d984b09c',  $domainRepository_reference, 'TYPO3\Neos\Domain\Repository\DomainRepository', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Neos\Domain\Repository\DomainRepository'); });
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
		$workspaceRepository_reference = &$this->workspaceRepository;
		$this->workspaceRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Repository\WorkspaceRepository');
		if ($this->workspaceRepository === NULL) {
			$this->workspaceRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('2e64c564c983af14b47d0c9ae8992997', $workspaceRepository_reference);
			if ($this->workspaceRepository === NULL) {
				$this->workspaceRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('2e64c564c983af14b47d0c9ae8992997',  $workspaceRepository_reference, 'TYPO3\TYPO3CR\Domain\Repository\WorkspaceRepository', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Repository\WorkspaceRepository'); });
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
		$objectManager_reference = &$this->objectManager;
		$this->objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Object\ObjectManagerInterface');
		if ($this->objectManager === NULL) {
			$this->objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('0c3c44be7be16f2a287f1fb2d068dde4', $objectManager_reference);
			if ($this->objectManager === NULL) {
				$this->objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('0c3c44be7be16f2a287f1fb2d068dde4',  $objectManager_reference, 'TYPO3\Flow\Object\ObjectManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Object\ObjectManagerInterface'); });
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
		$systemLogger_reference = &$this->systemLogger;
		$this->systemLogger = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Log\SystemLoggerInterface');
		if ($this->systemLogger === NULL) {
			$this->systemLogger = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('6d57d95a1c3cd7528e3e6ea15012dac8', $systemLogger_reference);
			if ($this->systemLogger === NULL) {
				$this->systemLogger = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('6d57d95a1c3cd7528e3e6ea15012dac8',  $systemLogger_reference, 'TYPO3\Flow\Log\SystemLoggerInterface', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Log\SystemLoggerInterface'); });
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
		$configurationManager_reference = &$this->configurationManager;
		$this->configurationManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Configuration\ConfigurationManager');
		if ($this->configurationManager === NULL) {
			$this->configurationManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('13edcae8fd67699bb78dadc8c1eac29c', $configurationManager_reference);
			if ($this->configurationManager === NULL) {
				$this->configurationManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('13edcae8fd67699bb78dadc8c1eac29c',  $configurationManager_reference, 'TYPO3\Flow\Configuration\ConfigurationManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Configuration\ConfigurationManager'); });
			}
		}
$this->Flow_Injected_Properties = array (
  0 => 'packageManager',
  1 => 'siteRepository',
  2 => 'siteImportService',
  3 => 'domainRepository',
  4 => 'nodeDataRepository',
  5 => 'workspaceRepository',
  6 => 'persistenceManager',
  7 => 'objectManager',
  8 => 'flashMessageContainer',
  9 => 'systemLogger',
  10 => 'contextFactory',
  11 => 'configurationManager',
);
	}
}
#