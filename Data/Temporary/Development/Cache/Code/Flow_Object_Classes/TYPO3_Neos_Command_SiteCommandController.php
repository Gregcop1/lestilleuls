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
use TYPO3\Neos\Domain\Repository\SiteRepository;
use TYPO3\Neos\Domain\Service\SiteExportService;
use TYPO3\Neos\Domain\Service\SiteImportService;
use TYPO3\Neos\Domain\Service\SiteService;

/**
 * The Site Command Controller
 *
 * @Flow\Scope("singleton")
 */
class SiteCommandController_Original extends CommandController {

	/**
	 * @Flow\Inject
	 * @var SiteImportService
	 */
	protected $siteImportService;

	/**
	 * @Flow\Inject
	 * @var SiteExportService
	 */
	protected $siteExportService;

	/**
	 * @Flow\Inject
	 * @var SiteRepository
	 */
	protected $siteRepository;

	/**
	 * @Flow\Inject
	 * @var SiteService
	 */
	protected $siteService;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Log\SystemLoggerInterface
	 */
	protected $systemLogger;

	/**
	 * Import sites content
	 *
	 * This command allows for importing one or more sites or partial content from an XML source. The format must
	 * be identical to that produced by the export command.
	 *
	 * If a filename is specified, this command expects the corresponding file to contain the XML structure. The
	 * filename php://stdin can be used to read from standard input.
	 *
	 * If a package key is specified, this command expects a Sites.xml file to be located in the private resources
	 * directory of the given package (Resources/Private/Content/Sites.xml).
	 *
	 * @param string $packageKey Package key specifying the package containing the sites content
	 * @param string $filename relative path and filename to the XML file containing the sites content
	 * @return void
	 */
	public function importCommand($packageKey = NULL, $filename = NULL) {
		if ($packageKey === NULL && $filename === NULL) {
			$this->outputLine('You have to specify either "--package-key" or "--filename"');
			$this->quit(1);
		}
		$site = NULL;
		if ($filename !== NULL) {
			try {
				$site = $this->siteImportService->importFromFile($filename);
			} catch (\Exception $exception) {
				$this->systemLogger->logException($exception);
				$this->outputLine('Error: During the import of the file "%s" an exception occurred: %s, see log for further information.', array($filename, $exception->getMessage()));
				$this->quit(1);
			}
		} else {
			try {
				$site = $this->siteImportService->importFromPackage($packageKey);
			} catch (\Exception $exception) {
				$this->systemLogger->logException($exception);
				$this->outputLine('Error: During the import of the "Sites.xml" from the package "%s" an exception occurred: %s, see log for further information.', array($packageKey, $exception->getMessage()));
				$this->quit(1);
			}
		}
		$this->outputLine('Import of site "%s" finished.', array($site->getName()));
	}

	/**
	 * Export sites content
	 *
	 * This command exports all or one specific site with all its content into an XML format.
	 *
	 * If the package key option is given, the site(s) will be exported to the given package in the default
	 * location Resources/Private/Content/Sites.xml.
	 *
	 * If the filename option is given, any resources will be exported to files in a folder named "Resources"
	 * alongside the XML file.
	 *
	 * If neither the filename nor the package key option are given, the XML will be printed to standard output and
	 * assets will be embedded into the XML in base64 encoded form.
	 *
	 * @param string $siteNode the node name of the site to be exported; if none given will export all sites
	 * @param boolean $tidy Whether to export formatted XML
	 * @param string $filename relative path and filename to the XML file to create. Any resource will be stored in a sub folder "Resources".
	 * @param string $packageKey Package to store the XML file in. Any resource will be stored in a sub folder "Resources".
	 * @return void
	 */
	public function exportCommand($siteNode = NULL, $tidy = FALSE, $filename = NULL, $packageKey = NULL) {
		if ($siteNode === NULL) {
			$sites = $this->siteRepository->findAll()->toArray();
		} else {
			$sites = $this->siteRepository->findByNodeName($siteNode)->toArray();
		}

		if (count($sites) === 0) {
			$this->outputLine('Error: No site for exporting found');
			$this->quit(1);
		}

		if ($packageKey !== NULL) {
			$this->siteExportService->exportToPackage($sites, $tidy, $packageKey);
			if ($siteNode !== NULL) {
				$this->outputLine('The site "%s" has been exported to package "%s".', array($siteNode, $packageKey));
			} else {
				$this->outputLine('All sites have been exported to package "%s".', array($packageKey));
			}
		} elseif ($filename !== NULL) {
			$this->siteExportService->exportToFile($sites, $tidy, $filename);
			if ($siteNode !== NULL) {
				$this->outputLine('The site "%s" has been exported to "%s".', array($siteNode, $filename));
			} else {
				$this->outputLine('All sites have been exported to "%s".', array($filename));
			}
		} else {
			$this->output($this->siteExportService->export($sites, $tidy));
		}
	}

	/**
	 * Remove all content and related data - for now. In the future we need some more sophisticated cleanup.
	 *
	 * @param string $siteNodeName Name of a site root node to clear only content of this site.
	 * @return void
	 */
	public function pruneCommand($siteNodeName = NULL) {
		if ($siteNodeName !== NULL) {
			$possibleSite = $this->siteRepository->findOneByNodeName($siteNodeName);
			if ($possibleSite === NULL) {
				$this->outputLine('The given site site node did not match an existing site.');
				$this->quit(1);
			}
			$this->siteService->pruneSite($possibleSite);
			$this->outputLine('Site with root "' . $siteNodeName . '" has been removed.');
		} else {
			$this->siteService->pruneAll();
			$this->outputLine('All sites and content have been removed.');
		}
	}

	/**
	 * Display a list of available sites
	 *
	 * @return void
	 */
	public function listCommand() {
		$sites = $this->siteRepository->findAll();

		if ($sites->count() === 0) {
			$this->outputLine('No sites available');
			$this->quit(0);
		}

		$longestSiteName = 4;
		$longestNodeName = 9;
		$longestSiteResource = 17;
		$availableSites = array();

		foreach ($sites as $site) {
			/** @var \TYPO3\Neos\Domain\Model\Site $site */
			array_push($availableSites, array(
				'name' => $site->getName(),
				'nodeName' => $site->getNodeName(),
				'siteResourcesPackageKey' => $site->getSiteResourcesPackageKey()
			));
			if (strlen($site->getName()) > $longestSiteName) {
				$longestSiteName = strlen($site->getName());
			}
			if (strlen($site->getNodeName()) > $longestNodeName) {
				$longestNodeName = strlen($site->getNodeName());
			}
			if (strlen($site->getSiteResourcesPackageKey()) > $longestSiteResource) {
				$longestSiteResource = strlen($site->getSiteResourcesPackageKey());
			}
		}

		$this->outputLine();
		$this->outputLine(' ' . str_pad('Name', $longestSiteName + 15) . str_pad('Node name', $longestNodeName + 15) . 'Resources package');
		$this->outputLine(str_repeat('-', $longestSiteName + $longestNodeName + $longestSiteResource + 15 + 15 + 2));
		foreach ($availableSites as $site) {
			$this->outputLine(' ' . str_pad($site['name'], $longestSiteName + 15) . str_pad($site['nodeName'], $longestNodeName + 15) . $site['siteResourcesPackageKey']);
		}
		$this->outputLine();
	}
}namespace TYPO3\Neos\Command;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * The Site Command Controller
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class SiteCommandController extends SiteCommandController_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\Neos\Command\SiteCommandController') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Command\SiteCommandController', $this);
		parent::__construct();
		if ('TYPO3\Neos\Command\SiteCommandController' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\Neos\Command\SiteCommandController') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Command\SiteCommandController', $this);

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
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\Command\SiteCommandController');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\Command\SiteCommandController', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\Command\SiteCommandController', $propertyName, 'var');
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
		$siteImportService_reference = &$this->siteImportService;
		$this->siteImportService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Neos\Domain\Service\SiteImportService');
		if ($this->siteImportService === NULL) {
			$this->siteImportService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('a382bdbc7e75d00f0510a58eb9dd5b14', $siteImportService_reference);
			if ($this->siteImportService === NULL) {
				$this->siteImportService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('a382bdbc7e75d00f0510a58eb9dd5b14',  $siteImportService_reference, 'TYPO3\Neos\Domain\Service\SiteImportService', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Neos\Domain\Service\SiteImportService'); });
			}
		}
		$siteExportService_reference = &$this->siteExportService;
		$this->siteExportService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Neos\Domain\Service\SiteExportService');
		if ($this->siteExportService === NULL) {
			$this->siteExportService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('d54da1208d763f79742013e289d6d34f', $siteExportService_reference);
			if ($this->siteExportService === NULL) {
				$this->siteExportService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('d54da1208d763f79742013e289d6d34f',  $siteExportService_reference, 'TYPO3\Neos\Domain\Service\SiteExportService', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Neos\Domain\Service\SiteExportService'); });
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
		$siteService_reference = &$this->siteService;
		$this->siteService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Neos\Domain\Service\SiteService');
		if ($this->siteService === NULL) {
			$this->siteService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('8e9ecf839bc9ab67fc10c2853a54d7c6', $siteService_reference);
			if ($this->siteService === NULL) {
				$this->siteService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('8e9ecf839bc9ab67fc10c2853a54d7c6',  $siteService_reference, 'TYPO3\Neos\Domain\Service\SiteService', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Neos\Domain\Service\SiteService'); });
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
$this->Flow_Injected_Properties = array (
  0 => 'reflectionService',
  1 => 'siteImportService',
  2 => 'siteExportService',
  3 => 'siteRepository',
  4 => 'siteService',
  5 => 'systemLogger',
);
	}
}
#