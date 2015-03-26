<?php 
namespace TYPO3\Neos\Kickstarter\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Neos.Kickstarter".*
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Package\MetaData;
use TYPO3\Flow\Package\MetaData\PackageConstraint;
use TYPO3\Flow\Package\MetaDataInterface;
use TYPO3\Flow\Package\PackageManagerInterface;
use TYPO3\TYPO3CR\Domain\Repository\ContentDimensionRepository;

/**
 * Service to generate site packages
 */
class GeneratorService_Original extends \TYPO3\Kickstart\Service\GeneratorService {

	/**
	 * @Flow\Inject
	 * @var PackageManagerInterface
	 */
	protected $packageManager;

	/**
	 * @Flow\Inject
	 * @var ContentDimensionRepository
	 */
	protected $contentDimensionRepository;

	/**
	 * Generate a site package and fill it with boilerplate data.
	 *
	 * @param string $packageKey
	 * @param string $siteName
	 * @return array
	 */
	public function generateSitePackage($packageKey, $siteName) {
		$packageMetaData = new MetaData($packageKey);
		$packageMetaData->addConstraint(new PackageConstraint(MetaDataInterface::CONSTRAINT_TYPE_DEPENDS, 'TYPO3.Neos'));
		$packageMetaData->addConstraint(new PackageConstraint(MetaDataInterface::CONSTRAINT_TYPE_DEPENDS, 'TYPO3.Neos.NodeTypes'));
		$this->packageManager->createPackage($packageKey, $packageMetaData, NULL, 'typo3-flow-site');
		$this->generateSitesXml($packageKey, $siteName);
		$this->generateSitesTypoScript($packageKey, $siteName);
		$this->generateSitesTemplate($packageKey, $siteName);
		$this->generateNodeTypesConfiguration($packageKey);

		return $this->generatedFiles;
	}

	/**
	 * Generate a "Sites.xml" for the given package and name.
	 *
	 * @param string $packageKey
	 * @param string $siteName
	 * @return void
	 */
	protected function generateSitesXml($packageKey, $siteName) {
		$templatePathAndFilename = 'resource://TYPO3.Neos.Kickstarter/Private/Generator/Content/Sites.xml';

		$contextVariables = array();
		$contextVariables['packageKey'] = $packageKey;
		$contextVariables['siteName'] = htmlspecialchars($siteName);
		$packageKeyDomainPart = substr(strrchr($packageKey, '.'), 1) ?: $packageKey;
		$contextVariables['siteNodeName'] = strtolower($packageKeyDomainPart);
		$contextVariables['dimensions'] = $this->contentDimensionRepository->findAll();

		$fileContent = $this->renderTemplate($templatePathAndFilename, $contextVariables);

		$sitesXmlPathAndFilename = $this->packageManager->getPackage($packageKey)->getResourcesPath() . 'Private/Content/Sites.xml';
		$this->generateFile($sitesXmlPathAndFilename, $fileContent);
	}

	/**
	 * Generate basic TypoScript files.
	 *
	 * @param string $packageKey
	 * @param string $siteName
	 * @return void
	 */
	protected function generateSitesTypoScript($packageKey, $siteName) {
		$templatePathAndFilename = 'resource://TYPO3.Neos.Kickstarter/Private/Generator/TypoScript/Root.ts2';

		$contextVariables = array();
		$contextVariables['packageKey'] = $packageKey;
		$contextVariables['siteName'] = $siteName;
		$packageKeyDomainPart = substr(strrchr($packageKey, '.'), 1) ?: $packageKey;
		$contextVariables['siteNodeName'] = $packageKeyDomainPart;

		$fileContent = $this->renderTemplate($templatePathAndFilename, $contextVariables);

		$sitesTypoScriptPathAndFilename = $this->packageManager->getPackage($packageKey)->getResourcesPath() . 'Private/TypoScript/Root.ts2';
		$this->generateFile($sitesTypoScriptPathAndFilename, $fileContent);
	}

	/**
	 * Generate basic template file.
	 *
	 * @param string $packageKey
	 * @param string $siteName
	 * @return void
	 */
	protected function generateSitesTemplate($packageKey, $siteName) {
		$templatePathAndFilename = 'resource://TYPO3.Neos.Kickstarter/Private/Generator/Template/SiteTemplate.html';

		$contextVariables = array();
		$contextVariables['siteName'] = $siteName;
		$contextVariables['neosViewHelper'] = '{namespace neos=TYPO3\Neos\ViewHelpers}';
		$contextVariables['typoScriptViewHelper'] = '{namespace ts=TYPO3\TypoScript\ViewHelpers}';
		$packageKeyDomainPart = substr(strrchr($packageKey, '.'), 1) ?: $packageKey;
		$contextVariables['siteNodeName'] = lcfirst($packageKeyDomainPart);

		$fileContent = $this->renderTemplate($templatePathAndFilename, $contextVariables);

		$sitesTypoScriptPathAndFilename = $this->packageManager->getPackage($packageKey)->getResourcesPath() . 'Private/Templates/Page/Default.html';
		$this->generateFile($sitesTypoScriptPathAndFilename, $fileContent);
	}

	/**
	 * Generate a example NodeTypes.yaml
	 *
	 * @param string $packageKey
	 * @return void
	 */
	protected function generateNodeTypesConfiguration($packageKey) {
		$templatePathAndFilename = 'resource://TYPO3.Neos.Kickstarter/Private/Generator/Configuration/NodeTypes.yaml';

		$fileContent = file_get_contents($templatePathAndFilename);

		$sitesTypoScriptPathAndFilename = $this->packageManager->getPackage($packageKey)->getConfigurationPath() . 'NodeTypes.yaml';
		$this->generateFile($sitesTypoScriptPathAndFilename, $fileContent);
	}
}
namespace TYPO3\Neos\Kickstarter\Service;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * Service to generate site packages
 */
class GeneratorService extends GeneratorService_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if ('TYPO3\Neos\Kickstarter\Service\GeneratorService' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {

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
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\Kickstarter\Service\GeneratorService');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\Kickstarter\Service\GeneratorService', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\Kickstarter\Service\GeneratorService', $propertyName, 'var');
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
		$contentDimensionRepository_reference = &$this->contentDimensionRepository;
		$this->contentDimensionRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Repository\ContentDimensionRepository');
		if ($this->contentDimensionRepository === NULL) {
			$this->contentDimensionRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('c4ce8954e3d47ef3fdb068b6c07c9ebb', $contentDimensionRepository_reference);
			if ($this->contentDimensionRepository === NULL) {
				$this->contentDimensionRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('c4ce8954e3d47ef3fdb068b6c07c9ebb',  $contentDimensionRepository_reference, 'TYPO3\TYPO3CR\Domain\Repository\ContentDimensionRepository', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Repository\ContentDimensionRepository'); });
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
		$this->templateParser = new \TYPO3\Fluid\Core\Parser\TemplateParser();
		$this->inflector = new \TYPO3\Kickstart\Utility\Inflector();
		$reflectionService_reference = &$this->reflectionService;
		$this->reflectionService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Reflection\ReflectionService');
		if ($this->reflectionService === NULL) {
			$this->reflectionService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('921ad637f16d2059757a908fceaf7076', $reflectionService_reference);
			if ($this->reflectionService === NULL) {
				$this->reflectionService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('921ad637f16d2059757a908fceaf7076',  $reflectionService_reference, 'TYPO3\Flow\Reflection\ReflectionService', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Reflection\ReflectionService'); });
			}
		}
$this->Flow_Injected_Properties = array (
  0 => 'packageManager',
  1 => 'contentDimensionRepository',
  2 => 'objectManager',
  3 => 'templateParser',
  4 => 'inflector',
  5 => 'reflectionService',
);
	}
}
#