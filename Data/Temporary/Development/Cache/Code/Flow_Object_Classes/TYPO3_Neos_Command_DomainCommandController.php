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
use TYPO3\Neos\Domain\Model\Domain;
use TYPO3\Neos\Domain\Model\Site;
use TYPO3\Neos\Domain\Repository\DomainRepository;
use TYPO3\Neos\Domain\Repository\SiteRepository;

/**
 * Domain command controller for the TYPO3.Neos package
 *
 * @Flow\Scope("singleton")
 */
class DomainCommandController_Original extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var DomainRepository
	 * @Flow\Inject
	 */
	protected $domainRepository;

	/**
	 * @var SiteRepository
	 * @Flow\Inject
	 */
	protected $siteRepository;

	/**
	 * Add a domain record
	 *
	 * @param string $siteNodeName The nodeName of the site rootNode, e.g. "neostypo3org"
	 * @param string $hostPattern The host pattern to match on, e.g. "neos.typo3.org"
	 * @return void
	 */
	public function addCommand($siteNodeName, $hostPattern) {
		$site = $this->siteRepository->findOneByNodeName($siteNodeName);
		if (!$site instanceof Site) {
			$this->outputLine('No site found with nodeName "%s".', array($siteNodeName));
			$this->quit(1);
		}

		$domains = $this->domainRepository->findByHostPattern($hostPattern);
		if ($domains->count() > 0) {
			$this->outputLine('The host pattern "%s" is not unique.', array($hostPattern));
			$this->quit(1);
		}

		$domain = new Domain();
		$domain->setSite($site);
		$domain->setHostPattern($hostPattern);
		$this->domainRepository->add($domain);

		$this->outputLine('Domain created.');
	}

	/**
	 * Display a list of available domain records
	 *
	 * @param string $hostPattern An optional host pattern to search for
	 * @return void
	 */
	public function listCommand($hostPattern = NULL) {
		if ($hostPattern === NULL) {
			$domains = $this->domainRepository->findAll();
		} else {
			$domains = $this->domainRepository->findByHost($hostPattern);
		}

		if (count($domains) === 0) {
			$this->outputLine('No domains available.');
			$this->quit(0);
		}

		$longestNodeName = 9;
		$longestHostPattern = 12;
		$availableDomains = array();

		foreach ($domains as $domain) {
			/** @var \TYPO3\Neos\Domain\Model\Domain $domain */
			array_push($availableDomains, array(
				'nodeName' => $domain->getSite()->getNodeName(),
				'hostPattern' => $domain->getHostPattern(),
				'active' => $domain->getActive()
			));
			if (strlen($domain->getSite()->getNodeName()) > $longestNodeName) {
				$longestNodeName = strlen($domain->getSite()->getNodeName());
			}
			if (strlen($domain->getHostPattern()) > $longestHostPattern) {
				$longestHostPattern = strlen($domain->getHostPattern());
			}
		}

		$this->outputLine();
		$this->outputLine(' ' . str_pad('Node name', $longestNodeName + 10) . str_pad('Host pattern', $longestHostPattern + 5) . 'State');
		$this->outputLine(str_repeat('-', $longestNodeName + $longestHostPattern + 10 + 2 + 14));
		foreach ($availableDomains as $domain) {
			$this->outputLine(' ' . str_pad($domain['nodeName'], $longestNodeName + 10) . str_pad($domain['hostPattern'], $longestHostPattern + 5) . ($domain['active'] ? 'Active' : 'Inactive'));
		}
		$this->outputLine();
	}

	/**
	 * Delete a domain record
	 *
	 * @param string $hostPattern The host pattern of the domain to remove
	 * @return void
	 */
	public function deleteCommand($hostPattern) {
		$domain = $this->domainRepository->findOneByHostPattern($hostPattern);
		if (!$domain instanceof Domain) {
			$this->outputLine('Domain not found.');
			$this->quit(1);
		}

		$this->domainRepository->remove($domain);
		$this->outputLine('Domain deleted.');
	}

	/**
	 * Activate a domain record
	 *
	 * @param string $hostPattern The host pattern of the domain to activate
	 * @return void
	 */
	public function activateCommand($hostPattern) {
		$domain = $this->domainRepository->findOneByHostPattern($hostPattern);
		if (!$domain instanceof Domain) {
			$this->outputLine('Domain not found.');
			$this->quit(1);
		}

		$domain->setActive(TRUE);
		$this->domainRepository->update($domain);
		$this->outputLine('Domain activated.');
	}

	/**
	 * Deactivate a domain record
	 *
	 * @param string $hostPattern The host pattern of the domain to deactivate
	 * @return void
	 */
	public function deactivateCommand($hostPattern) {
		$domain = $this->domainRepository->findOneByHostPattern($hostPattern);
		if (!$domain instanceof Domain) {
			$this->outputLine('Domain not found.');
			$this->quit(1);
		}

		$domain->setActive(FALSE);
		$this->domainRepository->update($domain);
		$this->outputLine('Domain deactivated.');
	}

}namespace TYPO3\Neos\Command;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * Domain command controller for the TYPO3.Neos package
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class DomainCommandController extends DomainCommandController_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\Neos\Command\DomainCommandController') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Command\DomainCommandController', $this);
		parent::__construct();
		if ('TYPO3\Neos\Command\DomainCommandController' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\Neos\Command\DomainCommandController') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Command\DomainCommandController', $this);

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
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\Command\DomainCommandController');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\Command\DomainCommandController', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\Command\DomainCommandController', $propertyName, 'var');
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
		$domainRepository_reference = &$this->domainRepository;
		$this->domainRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Neos\Domain\Repository\DomainRepository');
		if ($this->domainRepository === NULL) {
			$this->domainRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('6f2987c5f47777b01540a314d984b09c', $domainRepository_reference);
			if ($this->domainRepository === NULL) {
				$this->domainRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('6f2987c5f47777b01540a314d984b09c',  $domainRepository_reference, 'TYPO3\Neos\Domain\Repository\DomainRepository', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Neos\Domain\Repository\DomainRepository'); });
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
$this->Flow_Injected_Properties = array (
  0 => 'reflectionService',
  1 => 'domainRepository',
  2 => 'siteRepository',
);
	}
}
#