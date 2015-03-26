<?php 
namespace TYPO3\Neos\Domain\Service;

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
use TYPO3\Flow\Package\PackageManagerInterface;
use TYPO3\Flow\Utility\Files;
use TYPO3\Neos\Domain\Model\Site;
use TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface;
use TYPO3\TYPO3CR\Domain\Service\ImportExport\NodeExportService;

/**
 * The Site Export Service
 *
 * @Flow\Scope("singleton")
 */
class SiteExportService_Original {

	/**
	 * @Flow\Inject
	 * @var ContextFactoryInterface
	 */
	protected $contextFactory;

	/**
	 * @Flow\Inject
	 *
	 * @var NodeExportService
	 */
	protected $nodeExportService;

	/**
	 * @Flow\Inject
	 * @var PackageManagerInterface
	 */
	protected $packageManager;

	/**
	 * Absolute path to exported resources, or NULL if resources should be inlined in the exported XML
	 *
	 * @var string
	 */
	protected $resourcesPath = NULL;

	/**
	 * The XMLWriter that is used to construct the export.
	 *
	 * @var \XMLWriter
	 */
	protected $xmlWriter;

	/**
	 * Fetches the site with the given name and exports it into XML.
	 *
	 * @param array<Site> $sites
	 * @param boolean $tidy Whether to export formatted XML
	 * @return string
	 */
	public function export(array $sites, $tidy = FALSE) {
		$this->xmlWriter = new \XMLWriter();
		$this->xmlWriter->openMemory();
		$this->xmlWriter->setIndent($tidy);

		$this->exportSites($sites);

		return $this->xmlWriter->outputMemory(TRUE);
	}

	/**
	 * Fetches the site with the given name and exports it into XML in the given package.
	 *
	 * @param array<Site> $sites
	 * @param boolean $tidy Whether to export formatted XML
	 * @param string $packageKey Package key where the export output should be saved to
	 * @return void
	 */
	public function exportToPackage(array $sites, $tidy = FALSE, $packageKey) {
		if (!$this->packageManager->isPackageActive($packageKey)) {
			throw new NeosException(sprintf('Error: Package "%s" is not active.', $packageKey), 1404375719);
		}
		$contentPathAndFilename = sprintf('resource://%s/Private/Content/Sites.xml', $packageKey);

		$this->resourcesPath = Files::concatenatePaths(array(dirname($contentPathAndFilename), 'Resources'));
		Files::createDirectoryRecursively($this->resourcesPath);

		$this->xmlWriter = new \XMLWriter();
		$this->xmlWriter->openUri($contentPathAndFilename);
		$this->xmlWriter->setIndent($tidy);

		$this->exportSites($sites);

		$this->xmlWriter->flush();
	}

	/**
	 * Fetches the site with the given name and exports it as XML into the given file.
	 *
	 * @param array<Site> $sites
	 * @param boolean $tidy Whether to export formatted XML
	 * @param string $pathAndFilename Path to where the export output should be saved to
	 * @return void
	 */
	public function exportToFile(array $sites, $tidy = FALSE, $pathAndFilename) {
		$this->resourcesPath = Files::concatenatePaths(array(dirname($pathAndFilename), 'Resources'));
		Files::createDirectoryRecursively($this->resourcesPath);

		$this->xmlWriter = new \XMLWriter();
		$this->xmlWriter->openUri($pathAndFilename);
		$this->xmlWriter->setIndent($tidy);

		$this->exportSites($sites);

		$this->xmlWriter->flush();
	}

	/**
	 * Exports the given sites to the XMLWriter
	 *
	 * @param array<Site> $sites
	 * @return void
	 */
	protected function exportSites(array $sites) {
		$this->xmlWriter->startDocument('1.0', 'UTF-8');
		$this->xmlWriter->startElement('root');

		foreach ($sites as $site) {
			$this->exportSite($site);
		}

		$this->xmlWriter->endElement();
		$this->xmlWriter->endDocument();
	}

	/**
	 * Export the given $site to the XMLWriter
	 *
	 * @param Site $site
	 * @return void
	 */
	protected function exportSite(Site $site) {
		$contentContext = $this->contextFactory->create(array(
			'currentSite' => $site,
			'invisibleContentShown' => TRUE,
			'inaccessibleContentShown' => TRUE
		));

		$siteNode = $contentContext->getCurrentSiteNode();
		$this->xmlWriter->startElement('site');
		$this->xmlWriter->writeAttribute('name', $site->getName());
		$this->xmlWriter->writeAttribute('state', $site->getState());
		$this->xmlWriter->writeAttribute('siteResourcesPackageKey', $site->getSiteResourcesPackageKey());
		$this->xmlWriter->writeAttribute('siteNodeName', $siteNode->getName());

		$this->nodeExportService->export($siteNode->getPath(), $contentContext->getWorkspaceName(), $this->xmlWriter, FALSE, FALSE, $this->resourcesPath);

		$this->xmlWriter->endElement();
	}
}
namespace TYPO3\Neos\Domain\Service;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * The Site Export Service
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class SiteExportService extends SiteExportService_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\Neos\Domain\Service\SiteExportService') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Domain\Service\SiteExportService', $this);
		if ('TYPO3\Neos\Domain\Service\SiteExportService' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\Neos\Domain\Service\SiteExportService') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Domain\Service\SiteExportService', $this);

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
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\Domain\Service\SiteExportService');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\Domain\Service\SiteExportService', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\Domain\Service\SiteExportService', $propertyName, 'var');
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
		$nodeExportService_reference = &$this->nodeExportService;
		$this->nodeExportService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Service\ImportExport\NodeExportService');
		if ($this->nodeExportService === NULL) {
			$this->nodeExportService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('2cd24b0250f4f3b6208c80a73e19e681', $nodeExportService_reference);
			if ($this->nodeExportService === NULL) {
				$this->nodeExportService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('2cd24b0250f4f3b6208c80a73e19e681',  $nodeExportService_reference, 'TYPO3\TYPO3CR\Domain\Service\ImportExport\NodeExportService', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Service\ImportExport\NodeExportService'); });
			}
		}
		$packageManager_reference = &$this->packageManager;
		$this->packageManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Package\PackageManagerInterface');
		if ($this->packageManager === NULL) {
			$this->packageManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('aad0cdb65adb124cf4b4d16c5b42256c', $packageManager_reference);
			if ($this->packageManager === NULL) {
				$this->packageManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('aad0cdb65adb124cf4b4d16c5b42256c',  $packageManager_reference, 'TYPO3\Flow\Package\PackageManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Package\PackageManagerInterface'); });
			}
		}
$this->Flow_Injected_Properties = array (
  0 => 'contextFactory',
  1 => 'nodeExportService',
  2 => 'packageManager',
);
	}
}
#