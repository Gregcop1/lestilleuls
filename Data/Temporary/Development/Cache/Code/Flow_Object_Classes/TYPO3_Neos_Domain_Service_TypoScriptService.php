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
use TYPO3\Flow\Utility\Files;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeType;

/**
 * The TypoScript Service
 *
 * @Flow\Scope("prototype")
 * @api
 */
class TypoScriptService_Original {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TypoScript\Core\Parser
	 */
	protected $typoScriptParser;

	/**
	 * Pattern used for determining the TypoScript root file for a site
	 *
	 * @var string
	 */
	protected $siteRootTypoScriptPattern = 'resource://%s/Private/TypoScript/Root.ts2';

	/**
	 * Pattern used for determining the TypoScript root file for a site
	 *
	 * @var string
	 * @deprecated since 1.2 will be removed in 2.0
	 */
	protected $legacySiteRootTypoScriptPattern = 'resource://%s/Private/TypoScripts/Library/Root.ts2';

	/**
	 * Pattern used for determining the TypoScript root file for autoIncludes
	 *
	 * @var string
	 */
	protected $autoIncludeTypoScriptPattern = 'resource://%s/Private/TypoScript/Root.ts2';

	/**
	 * Array of TypoScript files to include before the site TypoScript
	 *
	 * Example:
	 *
	 *     array(
	 *         'resources://MyVendor.MyPackageKey/Private/TypoScript/Root.ts2',
	 *         'resources://SomeVendor.OtherPackage/Private/TypoScript/Root.ts2'
	 *     )
	 *
	 * @var array
	 */
	protected $prependTypoScriptIncludes = array();

	/**
	 * Array of TypoScript files to include after the site TypoScript
	 *
	 * Example:
	 *
	 *     array(
	 *         'resources://MyVendor.MyPackageKey/Private/TypoScript/Root.ts2',
	 *         'resources://SomeVendor.OtherPackage/Private/TypoScript/Root.ts2'
	 *     )
	 *
	 * @var array
	 */
	protected $appendTypoScriptIncludes = array();

	/**
	 * @Flow\Inject(setting="typoScript.autoInclude")
	 * @var array
	 */
	protected $autoIncludeConfiguration = array();

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Domain\Service\NodeTypeManager
	 */
	protected $nodeTypeManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Package\PackageManagerInterface
	 */
	protected $packageManager;

	/**
	 * Initializes the parser
	 *
	 * @return void
	 */
	public function initializeObject() {
		$this->typoScriptParser->setObjectTypeNamespace('default', 'TYPO3.Neos');
	}

	/**
	 * Create a runtime for the given site node
	 *
	 * @param \TYPO3\TYPO3CR\Domain\Model\NodeInterface $currentSiteNode
	 * @param \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext
	 * @return \TYPO3\TypoScript\Core\Runtime
	 */
	public function createRuntime(NodeInterface $currentSiteNode, \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext) {
		$typoScriptObjectTree = $this->getMergedTypoScriptObjectTree($currentSiteNode);
		$typoScriptRuntime = new \TYPO3\TypoScript\Core\Runtime($typoScriptObjectTree, $controllerContext);
		return $typoScriptRuntime;
	}

	/**
	 * Returns a merged TypoScript object tree in the context of the given nodes
	 *
	 * @param \TYPO3\TYPO3CR\Domain\Model\NodeInterface $startNode Node marking the starting point
	 * @return array The merged object tree as of the given node
	 * @throws \TYPO3\Neos\Domain\Exception
	 */
	public function getMergedTypoScriptObjectTree(NodeInterface $startNode) {
		$contentContext = $startNode->getContext();
		$siteResourcesPackageKey = $contentContext->getCurrentSite()->getSiteResourcesPackageKey();

		$siteRootTypoScriptPathAndFilename = sprintf($this->siteRootTypoScriptPattern, $siteResourcesPackageKey);
		$siteRootTypoScriptCode = $this->readExternalTypoScriptFile($siteRootTypoScriptPathAndFilename);
		$expectedSiteRootTypoScriptPathAndFilename = $siteRootTypoScriptPathAndFilename;

		if ($siteRootTypoScriptCode === '') {
			$siteRootTypoScriptPathAndFilename = sprintf($this->legacySiteRootTypoScriptPattern, $siteResourcesPackageKey);
			$siteRootTypoScriptCode = $this->readExternalTypoScriptFile($siteRootTypoScriptPathAndFilename);
		}

		if (trim($siteRootTypoScriptCode) === '') {
			throw new \TYPO3\Neos\Domain\Exception(sprintf('The site package %s did not contain a root TypoScript configuration. Please make sure that there is one at %s.', $siteResourcesPackageKey, $expectedSiteRootTypoScriptPathAndFilename), 1357215211);
		}

		$mergedTypoScriptCode = '';
		$mergedTypoScriptCode .= $this->generateNodeTypeDefinitions();
		$mergedTypoScriptCode .= $this->getTypoScriptIncludes($this->prepareAutoIncludeTypoScript());
		$mergedTypoScriptCode .= $this->getTypoScriptIncludes($this->prependTypoScriptIncludes);
		$mergedTypoScriptCode .= $siteRootTypoScriptCode;
		$mergedTypoScriptCode .= $this->getTypoScriptIncludes($this->appendTypoScriptIncludes);

		return $this->typoScriptParser->parse($mergedTypoScriptCode, $siteRootTypoScriptPathAndFilename);
	}

	/**
	 * Reads the TypoScript file from the given path and filename.
	 * If it doesn't exist, this function will just return an empty string.
	 *
	 * @param string $pathAndFilename Path and filename of the TypoScript file
	 * @return string The content of the .ts2 file, plus one chr(10) at the end
	 */
	protected function readExternalTypoScriptFile($pathAndFilename) {
		return (file_exists($pathAndFilename)) ? Files::getFileContents($pathAndFilename) . chr(10) : '';
	}

	/**
	 * Generate TypoScript prototype definitions for all node types
	 *
	 * Only fully qualified node types (e.g. MyVendor.MyPackage:NodeType) will be considered.
	 *
	 * @return string
	 */
	protected function generateNodeTypeDefinitions() {
		$code = '';
		/** @var NodeType $nodeType */
		foreach ($this->nodeTypeManager->getNodeTypes(FALSE) as $nodeType) {
			$code .= $this->generateTypoScriptForNodeType($nodeType);
		}
		return $code;
	}

	/**
	 * Generate a TypoScript prototype definition for a given node type
	 *
	 * A node will be rendered by TYPO3.Neos:Content by default with a template in
	 * resource://PACKAGE_KEY/Private/Templates/NodeTypes/NAME.html and forwards all public
	 * node properties to the template TypoScript object.
	 *
	 * @param NodeType $nodeType
	 * @return string
	 */
	protected function generateTypoScriptForNodeType(NodeType $nodeType) {
		if (strpos($nodeType->getName(), ':') === FALSE) {
			return '';
		}

		if ($nodeType->isOfType('TYPO3.Neos:Content')) {
			$basePrototypeName = 'TYPO3.Neos:Content';
		} elseif ($nodeType->isOfType('TYPO3.Neos:Document')) {
			$basePrototypeName = 'TYPO3.Neos:Document';
		} else {
			$basePrototypeName = 'TYPO3.TypoScript:Template';
		}

		$output = 'prototype(' . $nodeType->getName() . ') < prototype(' . $basePrototypeName . ') {' . chr(10);

		list($packageKey, $relativeName) = explode(':', $nodeType->getName(), 2);
		$templatePath = 'resource://' . $packageKey . '/Private/Templates/NodeTypes/' . $relativeName . '.html';
		$output .= "\t" . 'templatePath = \'' . $templatePath . '\'' . chr(10);

		foreach ($nodeType->getProperties() as $propertyName => $propertyConfiguration) {
			if (isset($propertyName[0]) && $propertyName[0] !== '_') {
				$output .= "\t" . $propertyName . ' = ${q(node).property("' . $propertyName . '")}' . chr(10);
				if (isset($propertyConfiguration['type']) && isset($propertyConfiguration['ui']['inlineEditable']) && $propertyConfiguration['type'] === 'string' && $propertyConfiguration['ui']['inlineEditable'] === TRUE) {
					$output .= "\t" . $propertyName . '.@process.convertUris = TYPO3.Neos:ConvertUris' . chr(10);
				}
			}
		}

		$output .= '}' . chr(10);
		return $output;
	}

	/**
	 * Concatenate the given TypoScript resources with include statements
	 *
	 * @param array $typoScriptResources An array of TypoScript resource URIs
	 * @return string A string of include statements for all resources
	 */
	protected function getTypoScriptIncludes(array $typoScriptResources) {
		$code = chr(10);
		foreach ($typoScriptResources as $typoScriptResource) {
			$code .= 'include: ' . (string)$typoScriptResource . chr(10);
		}
		$code .= chr(10);
		return $code;
	}

	/**
	 * Prepares an array with TypoScript paths to auto include before the Site TypoScript.
	 *
	 * @return array
	 */
	protected function prepareAutoIncludeTypoScript() {
		$autoIncludeTypoScript = array();
		foreach (array_keys($this->packageManager->getActivePackages()) as $packageKey) {
			if (isset($this->autoIncludeConfiguration[$packageKey]) && $this->autoIncludeConfiguration[$packageKey] === TRUE) {
				$autoIncludeTypoScript[] = sprintf($this->autoIncludeTypoScriptPattern, $packageKey);
			}
		}

		return $autoIncludeTypoScript;
	}

	/**
	 * Set the pattern for including the site root TypoScript
	 *
	 * @param string $siteRootTypoScriptPattern A string for the sprintf format that takes the site package key as a single placeholder
	 * @return void
	 */
	public function setSiteRootTypoScriptPattern($siteRootTypoScriptPattern) {
		$this->siteRootTypoScriptPattern = $siteRootTypoScriptPattern;
	}

	/**
	 * Get the TypoScript resources that are included before the site TypoScript.
	 *
	 * @return array
	 */
	public function getPrependTypoScriptIncludes() {
		return $this->prependTypoScriptIncludes;
	}

	/**
	 * Set TypoScript resources that should be prepended before the site TypoScript,
	 * it defaults to the Neos Root.ts2 TypoScript.
	 *
	 * @param array $prependTypoScriptIncludes
	 * @return void
	 */
	public function setPrependTypoScriptIncludes(array $prependTypoScriptIncludes) {
		$this->prependTypoScriptIncludes = $prependTypoScriptIncludes;
	}

	/**
	 * Get TypoScript resources that will be appended after the site TypoScript.
	 *
	 * @return array
	 */
	public function getAppendTypoScriptIncludes() {
		return $this->appendTypoScriptIncludes;
	}

	/**
	 * Set TypoScript resources that should be appended after the site TypoScript,
	 * this defaults to an empty array.
	 *
	 * @param array $appendTypoScriptIncludes An array of TypoScript resource URIs
	 * @return void
	 */
	public function setAppendTypoScriptIncludes(array $appendTypoScriptIncludes) {
		$this->appendTypoScriptIncludes = $appendTypoScriptIncludes;
	}

}
namespace TYPO3\Neos\Domain\Service;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * The TypoScript Service
 * @\TYPO3\Flow\Annotations\Scope("prototype")
 */
class TypoScriptService extends TypoScriptService_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if ('TYPO3\Neos\Domain\Service\TypoScriptService' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}

		if (get_class($this) === 'TYPO3\Neos\Domain\Service\TypoScriptService') {
			$this->initializeObject(1);
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
		$result = NULL;

		if (get_class($this) === 'TYPO3\Neos\Domain\Service\TypoScriptService') {
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
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\Domain\Service\TypoScriptService');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\Domain\Service\TypoScriptService', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\Domain\Service\TypoScriptService', $propertyName, 'var');
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
		$this->typoScriptParser = new \TYPO3\TypoScript\Core\Parser();
		$this->autoIncludeConfiguration = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Configuration\ConfigurationManager')->getConfiguration(\TYPO3\Flow\Configuration\ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, 'TYPO3.Neos.typoScript.autoInclude');
		$nodeTypeManager_reference = &$this->nodeTypeManager;
		$this->nodeTypeManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Service\NodeTypeManager');
		if ($this->nodeTypeManager === NULL) {
			$this->nodeTypeManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('478a517efacb3d47415a96d9caded2e9', $nodeTypeManager_reference);
			if ($this->nodeTypeManager === NULL) {
				$this->nodeTypeManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('478a517efacb3d47415a96d9caded2e9',  $nodeTypeManager_reference, 'TYPO3\TYPO3CR\Domain\Service\NodeTypeManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Service\NodeTypeManager'); });
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
  0 => 'typoScriptParser',
  1 => 'autoIncludeConfiguration',
  2 => 'nodeTypeManager',
  3 => 'packageManager',
);
	}
}
#