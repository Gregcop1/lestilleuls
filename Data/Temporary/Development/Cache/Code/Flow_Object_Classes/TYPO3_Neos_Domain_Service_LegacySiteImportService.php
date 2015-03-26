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
use TYPO3\Flow\Object\ObjectManagerInterface;
use TYPO3\Flow\Package\Exception\InvalidPackageStateException;
use TYPO3\Flow\Package\Exception\UnknownPackageException;
use TYPO3\Flow\Package\PackageManagerInterface;
use TYPO3\Flow\Reflection\ObjectAccess;
use TYPO3\Flow\Reflection\ReflectionService;
use TYPO3\Flow\Resource\ResourceManager;
use TYPO3\Flow\Utility\Files;
use TYPO3\Media\Domain\Model\AssetInterface;
use TYPO3\Media\Domain\Model\Image;
use TYPO3\Media\Domain\Model\ImageVariant;
use TYPO3\Media\Domain\Repository\ImageRepository;
use TYPO3\Media\Domain\Repository\AssetRepository;
use TYPO3\Neos\Domain\Exception as DomainException;
use TYPO3\Neos\Domain\Model\Site;
use TYPO3\Neos\Domain\Repository\SiteRepository;
use TYPO3\Neos\Exception as NeosException;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeType;
use TYPO3\TYPO3CR\Domain\Service\NodeTypeManager;

/**
 * The "legacy" Site Import Service which understands the "old" XML format used in Neos 1.0 and 1.1 which does not
 * allow for content dimensions.
 *
 * @Flow\Scope("singleton")
 * @deprecated since Neos 1.2. Will be removed with Neos 1.4
 */
class LegacySiteImportService_Original {

	/**
	 * @Flow\Inject
	 * @var PackageManagerInterface
	 */
	protected $packageManager;

	/**
	 * @Flow\Inject
	 * @var ResourceManager
	 */
	protected $resourceManager;

	/**
	 * @Flow\Inject
	 * @var SiteRepository
	 */
	protected $siteRepository;

	/**
	 * @Flow\Inject
	 * @var NodeTypeManager
	 */
	protected $nodeTypeManager;

	/**
	 * @Flow\Inject
	 * @var ImageRepository
	 */
	protected $imageRepository;

	/**
	 * @Flow\Inject
	 * @var AssetRepository
	 */
	protected $assetRepository;

	/**
	 * @Flow\Inject
	 * @var ReflectionService
	 */
	protected $reflectionService;

	/**
	 * @Flow\Inject
	 * @var ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @var string
	 */
	protected $resourcesPath = NULL;

	/**
	 * An array that contains all fully qualified class names that extend ImageVariant including ImageVariant itself
	 *
	 * @var array<string>
	 */
	protected $imageVariantClassNames = array();

	/**
	 * An array that contains all fully qualified class names that implement AssetInterface
	 *
	 * @var array<string>
	 */
	protected $assetClassNames = array();

	/**
	 * An array that contains all fully qualified class names that extend \DateTime including \DateTime itself
	 *
	 * @var array<string>
	 */
	protected $dateTimeClassNames = array();

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 */
	protected $persistenceManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface
	 */
	protected $contextFactory;

	/**
	 * @return void
	 */
	public function initializeObject() {
		$this->imageVariantClassNames = $this->reflectionService->getAllSubClassNamesForClass('TYPO3\Media\Domain\Model\ImageVariant');
		array_unshift($this->imageVariantClassNames, 'TYPO3\Media\Domain\Model\ImageVariant');

		$this->assetClassNames = $this->reflectionService->getAllImplementationClassNamesForInterface('TYPO3\Media\Domain\Model\AssetInterface');

		$this->dateTimeClassNames = $this->reflectionService->getAllSubClassNamesForClass('DateTime');
		array_unshift($this->dateTimeClassNames, 'DateTime');
	}

	/**
	 * Imports one or multiple sites from the XML file at $pathAndFilename
	 *
	 * @param string $pathAndFilename
	 * @return Site The imported site or NULL if not successful
	 * @throws UnknownPackageException
	 * @throws InvalidPackageStateException
	 */
	public function importSitesFromFile($pathAndFilename) {
		$contentContext = $this->createContext();
		$sitesXmlString = $this->loadSitesXml($pathAndFilename);

		if (defined('LIBXML_PARSEHUGE')) {
			$options = LIBXML_PARSEHUGE;
		} else {
			$options = 0;
		}
		$site = NULL;
		$sitesXml = new \SimpleXMLElement($sitesXmlString, $options);
		foreach ($sitesXml->site as $siteXml) {
			$site = $this->getSiteByNodeName((string)$siteXml['nodeName']);
			if ((string)$siteXml->properties->name !== '') {
				$site->setName((string)$siteXml->properties->name);
			}
			$site->setState((integer)$siteXml->properties->state);

			$siteResourcesPackageKey = (string)$siteXml->properties->siteResourcesPackageKey;
			if (!$this->packageManager->isPackageAvailable($siteResourcesPackageKey)) {
				throw new UnknownPackageException(sprintf('Package "%s" specified in the XML as site resources package does not exist.', $siteResourcesPackageKey), 1303891443);
			}
			if (!$this->packageManager->isPackageActive($siteResourcesPackageKey)) {
				throw new InvalidPackageStateException(sprintf('Package "%s" specified in the XML as site resources package is not active.', $siteResourcesPackageKey), 1303898135);
			}
			$site->setSiteResourcesPackageKey($siteResourcesPackageKey);

			$rootNode = $contentContext->getRootNode();
			$sitesNode = $rootNode->getNode('/sites');
			if ($sitesNode === NULL) {
				$sitesNode = $rootNode->createSingleNode('sites');
			}

			if ($siteXml['type'] === NULL) {
				$this->upgradeLegacySiteXml($siteXml, $site);
			}

			$this->importNode($siteXml, $sitesNode);
		}
		return $site;
	}

	/**
	 * @return \TYPO3\TYPO3CR\Domain\Service\Context
	 */
	protected function createContext() {
		return $this->contextFactory->create(array(
			'workspaceName' => 'live',
			'invisibleContentShown' => TRUE,
			'inaccessibleContentShown' => TRUE
		));
	}

	/**
	 * Loads and returns the XML found at $pathAndFilename
	 *
	 * @param string $pathAndFilename
	 * @return string
	 * @throws NeosException
	 */
	protected function loadSitesXml($pathAndFilename) {
		if ($pathAndFilename === 'php://stdin') {
			// no file_get_contents here because it does not work on php://stdin
			$fp = fopen($pathAndFilename, 'rb');
			$xmlString = '';
			while (!feof($fp)) {
				$xmlString .= fread($fp, 4096);
			}
			fclose($fp);

			return $xmlString;
		}
		if (!file_exists($pathAndFilename)) {
			throw new NeosException(sprintf('Could not load Content from "%s". This file does not exist.', $pathAndFilename), 1384193282);
		}
		$this->resourcesPath = Files::concatenatePaths(array(dirname($pathAndFilename), 'Resources'));

		return file_get_contents($pathAndFilename);
	}

	/**
	 * Updates or creates a site with the given $siteNodeName
	 *
	 * @param string $siteNodeName
	 * @return Site
	 */
	protected function getSiteByNodeName($siteNodeName) {
		$site = $this->siteRepository->findOneByNodeName($siteNodeName);
		if ($site === NULL) {
			$site = new Site($siteNodeName);
			$this->siteRepository->add($site);

			return $site;
		}
		$this->siteRepository->update($site);

		return $site;
	}

	/**
	 * Converts the given $nodeXml to a node and adds it to the $parentNode (or overrides an existing node)
	 *
	 * @param \SimpleXMLElement $nodeXml
	 * @param NodeInterface $parentNode
	 * @return void
	 */
	protected function importNode(\SimpleXMLElement $nodeXml, NodeInterface $parentNode) {
		$nodeName = (string)$nodeXml['nodeName'];
		$nodeType = $this->parseNodeType($nodeXml);
		$node = $parentNode->getNode($nodeName);

		if ($node === NULL) {
			$identifier = (string)$nodeXml['identifier'] === '' ? NULL : (string)$nodeXml['identifier'];
			$node = $parentNode->createSingleNode((string)$nodeXml['nodeName'], $nodeType, $identifier);
		} else {
			$node->setNodeType($nodeType);
		}

		$this->importNodeVisibility($nodeXml, $node);
		$this->importNodeProperties($nodeXml, $node);
		$this->importNodeAccessRoles($nodeXml, $node);

		if ($nodeXml->node) {
			foreach ($nodeXml->node as $childNodeXml) {
				$this->importNode($childNodeXml, $node);
			}
		}
	}

	/**
	 * Detects and retrieves the NodeType of the given $nodeXml
	 *
	 * @param \SimpleXMLElement $nodeXml
	 * @return NodeType
	 * @throws \TYPO3\Neos\Domain\Exception
	 */
	protected function parseNodeType(\SimpleXMLElement $nodeXml) {
		$nodeTypeName = (string)$nodeXml['type'];
		if ($this->nodeTypeManager->hasNodeType($nodeTypeName)) {
			$nodeType = $this->nodeTypeManager->getNodeType($nodeTypeName);
			if ($nodeType->isAbstract()) {
				throw new DomainException(sprintf('The node type "%s" is marked as abstract and cannot be assigned to nodes.', $nodeTypeName), 1386590052);
			}
			return $nodeType;
		}

		return $this->nodeTypeManager->createNodeType($nodeTypeName);
	}

	/**
	 * Sets visibility of the $node according to the $nodeXml
	 *
	 * @param \SimpleXMLElement $nodeXml
	 * @param NodeInterface $node
	 * @return void
	 */
	protected function importNodeVisibility(\SimpleXMLElement $nodeXml, NodeInterface $node) {
		$node->setHidden((boolean)$nodeXml['hidden']);
		$node->setHiddenInIndex((boolean)$nodeXml['hiddenInIndex']);
		if ((string)$nodeXml['hiddenBeforeDateTime'] !== '') {
			$node->setHiddenBeforeDateTime(\DateTime::createFromFormat(\DateTime::W3C, (string)$nodeXml['hiddenBeforeDateTime']));
		}
		if ((string)$nodeXml['hiddenAfterDateTime'] !== '') {
			$node->setHiddenAfterDateTime(\DateTime::createFromFormat(\DateTime::W3C, (string)$nodeXml['hiddenAfterDateTime']));
		}
	}

	/**
	 * Iterates over properties child nodes of the given $nodeXml (if any)  and sets them on the $node instance
	 *
	 * @param \SimpleXMLElement $nodeXml
	 * @param NodeInterface $node
	 * @return void
	 */
	protected function importNodeProperties(\SimpleXMLElement $nodeXml, NodeInterface $node) {
		if (!$nodeXml->properties) {
			return;
		}
		foreach ($nodeXml->properties->children() as $nodePropertyXml) {
			$this->importNodeProperty($nodePropertyXml, $node);
		}
	}

	/**
	 * Sets the given $nodePropertyXml on the $node instance
	 *
	 * @param \SimpleXMLElement $nodePropertyXml
	 * @param NodeInterface $node
	 * @return void
	 */
	protected function importNodeProperty(\SimpleXMLElement $nodePropertyXml, NodeInterface $node) {
		if (!isset($nodePropertyXml['__type'])) {
			$node->setProperty($nodePropertyXml->getName(), (string)$nodePropertyXml);

			return;
		}
		switch ($nodePropertyXml['__type']) {
			case 'boolean':
				$node->setProperty($nodePropertyXml->getName(), (string)$nodePropertyXml === '1');
				break;
			case 'reference':
				$targetIdentifier = ((string)$nodePropertyXml->node['identifier'] === '' ? NULL : (string)$nodePropertyXml->node['identifier']);
				$node->setProperty($nodePropertyXml->getName(), $targetIdentifier);
				break;
			case 'references':
				$referencedNodeIdentifiers = array();
				foreach ($nodePropertyXml->node as $referenceNodeXml) {
					if ((string)$referenceNodeXml['identifier'] !== '') {
						$referencedNodeIdentifiers[] = (string)$referenceNodeXml['identifier'];
					}
				}
				$node->setProperty($nodePropertyXml->getName(), $referencedNodeIdentifiers);
				break;
			case 'array':
				$entries = array();
				foreach ($nodePropertyXml->children() as $childNodeXml) {
					$entry = NULL;

					if (!isset($childNodeXml['__classname']) || !in_array($childNodeXml['__classname'], array('TYPO3\Media\Domain\Model\Image', 'TYPO3\Media\Domain\Model\Asset'))) {
						// Only arrays of asset objects are supported now
						continue;
					}

					$entryClassName = (string)$childNodeXml['__classname'];
					if (isset($childNodeXml['__identifier'])) {
						if ($entryClassName === 'TYPO3\Media\Domain\Model\Image') {
							$entry = $this->imageRepository->findByIdentifier((string)$childNodeXml['__identifier']);
						} else {
							$entry = $this->assetRepository->findByIdentifier((string)$childNodeXml['__identifier']);
						}
					}

					if ($entry === NULL) {
						$resourceXml = $childNodeXml->xpath('resource');
						$resourceHash = $resourceXml[0]->xpath('hash');
						$content = $resourceXml[0]->xpath('content');
						$filename = $resourceXml[0]->xpath('filename');

						$resource = $this->importResource(
							!empty($filename) ? (string)$filename[0] : NULL,
							!empty($resourceHash) ? (string)$resourceHash[0] : NULL,
							!empty($content) ? (string)$content[0] : NULL,
							isset($resourceXml[0]['__identifier']) ? (string)$resourceXml[0]['__identifier'] : NULL
						);

						$entry = new $entryClassName($resource);

						if (isset($childNodeXml['__identifier'])) {
							ObjectAccess::setProperty($entry, 'Persistence_Object_Identifier', (string)$childNodeXml['__identifier'], TRUE);
						}
					}

					$propertiesXml = $childNodeXml->xpath('properties/*');
					foreach ($propertiesXml as $propertyXml) {
						if (!isset($propertyXml['__type'])) {
							ObjectAccess::setProperty($entry, $propertyXml->getName(), (string)$propertyXml);
							continue;
						}

						switch ($propertyXml['__type']) {
							case 'boolean':
								ObjectAccess::setProperty($entry, $propertyXml->getName(), (boolean)$propertyXml);
								break;
							case 'string':
								ObjectAccess::setProperty($entry, $propertyXml->getName(), (string)$propertyXml);
								break;
							case 'object':
								ObjectAccess::setProperty($entry, $propertyXml->getName(), $this->xmlToObject($propertyXml));
						}
					}

					/**
					 * During the persist Doctrine calls the serialize() method on the asset for the ObjectArray
					 * object, during this serialize the resource property gets lost.
					 * The AssetList node type has a custom implementation to work around this bug.
					 * @see NEOS-121
					 */
					$repositoryAction = $this->persistenceManager->isNewObject($entry) ? 'add' : 'update';
					if ($entry instanceof Image) {
						$this->imageRepository->$repositoryAction($entry);
					} else {
						$this->assetRepository->$repositoryAction($entry);
					}

					$entries[] = $entry;
				}

				$node->setProperty($nodePropertyXml->getName(), $entries);
				break;
			case 'object':
				$node->setProperty($nodePropertyXml->getName(), $this->xmlToObject($nodePropertyXml));
				break;
		}
	}

	/**
	 * Imports a resource based on exported hash or content
	 *
	 * @param string $fileName
	 * @param string|null $hash
	 * @param string|null $content
	 * @param string $forcedIdentifier
	 * @return \TYPO3\Flow\Resource\Resource
	 * @throws NeosException
	 */
	protected function importResource($fileName, $hash = NULL, $content = NULL, $forcedIdentifier = NULL) {
		if ($hash !== NULL) {
			$resource = $this->resourceManager->createResourceFromContent(
				file_get_contents(Files::concatenatePaths(array($this->resourcesPath, $hash))),
				$fileName
			);
		} else {
			$resourceData = trim($content);
			if ($resourceData === '') {
				throw new NeosException('Could not import resource because neither "hash" nor "content" tags are present.', 1403009453);
			}
			$decodedResourceData = base64_decode($resourceData);
			if ($decodedResourceData === FALSE) {
				throw new NeosException('Could not import resource because the "content" tag doesn\'t contain valid base64 encoded data.', 1403009477);
			}

			$resource = $this->resourceManager->createResourceFromContent(
				$decodedResourceData,
				$fileName
			);
		}

		if ($forcedIdentifier !== NULL) {
			ObjectAccess::setProperty($resource, 'Persistence_Object_Identifier', $forcedIdentifier, TRUE);
		}

		return $resource;
	}

	/**
	 * Sets the $nodes access roles
	 *
	 * @param \SimpleXMLElement $nodeXml
	 * @param NodeInterface $node
	 * @return void
	 */
	protected function importNodeAccessRoles(\SimpleXMLElement $nodeXml, NodeInterface $node) {
		if (!$nodeXml->accessRoles) {
			return;
		}
		$accessRoles = array();
		foreach ($nodeXml->accessRoles->children() as $accessRoleXml) {
			$accessRoles[] = (string)$accessRoleXml;
		}
		$node->setAccessRoles($accessRoles);
	}

	/**
	 * Handles conversion of our XML format into objects.
	 *
	 * @param \SimpleXMLElement $objectXml
	 * @return object
	 * @throws DomainException
	 */
	protected function xmlToObject(\SimpleXMLElement $objectXml) {
		$object = NULL;
		$className = (string)$objectXml['__classname'];
		if (in_array($className, $this->imageVariantClassNames)) {
			return $this->importImageVariant($objectXml, $className);
		}

		if (in_array($className, $this->assetClassNames)) {
			return $this->importAsset($objectXml, $className);
		}

		if (in_array($className, $this->dateTimeClassNames)) {
			return call_user_func_array($className . '::createFromFormat', array(\DateTime::W3C, (string)$objectXml->dateTime));
		}

		throw new DomainException(sprintf('Unsupported object of target type "%s" hit during XML import.', $className), 1347144938);
	}

	/**
	 * Converts the given $objectXml to an ImageVariant instance and returns it
	 *
	 * @param \SimpleXMLElement $objectXml
	 * @param string $className the concrete class name of the ImageVariant to create (ImageVariant or a subclass)
	 * @return ImageVariant
	 * @throws NeosException
	 */
	protected function importImageVariant(\SimpleXMLElement $objectXml, $className) {
		$processingInstructions = unserialize(trim((string)$objectXml->processingInstructions));

		if (isset($objectXml->originalImage['__identifier'])) {
			$image = $this->imageRepository->findByIdentifier((string)$objectXml->originalImage['__identifier']);
			if (is_object($image)) {
				return $this->objectManager->get($className, $image, $processingInstructions);
			}
		}

		$resourceHash = (string)$objectXml->originalImage->resource->hash;
		$resourceData = trim((string)$objectXml->originalImage->resource->content);

		if ((string)$objectXml->originalImage->resource['__identifier'] !== '') {
			$resource = $this->persistenceManager->getObjectByIdentifier((string)$objectXml->originalImage->resource['__identifier'], 'TYPO3\Flow\Resource\Resource');
		}

		if (!isset($resource) || $resource === NULL) {
			$resource = $this->importResource(
				(string)$objectXml->originalImage->resource->filename,
				$resourceHash !== '' ? $resourceHash : NULL,
				!empty($resourceData) ? $resourceData : NULL,
				(string)$objectXml->originalImage->resource['__identifier'] !== '' ? (string)$objectXml->originalImage->resource['__identifier'] : NULL
			);
		}

		$image = new Image($resource);
		if ((string)$objectXml->originalImage['__identifier'] !== '') {
			ObjectAccess::setProperty($image, 'Persistence_Object_Identifier', (string)$objectXml->originalImage['__identifier'], TRUE);
		}
		$this->imageRepository->add($image);

		return $this->objectManager->get($className, $image, $processingInstructions);
	}

	/**
	 * Converts the given $objectXml to an AssetInterface instance and returns it
	 *
	 * @param \SimpleXMLElement $objectXml
	 * @param string $className the concrete class name of the AssetInterface to create
	 * @return AssetInterface
	 * @throws NeosException
	 */
	protected function importAsset(\SimpleXMLElement $objectXml, $className) {
		if (isset($objectXml['__identifier'])) {
			$asset = $this->assetRepository->findByIdentifier((string)$objectXml['__identifier']);
			if (is_object($asset)) {
				return $asset;
			}
		}

		$resourceHash = (string)$objectXml->resource->hash;
		$resourceData = trim((string)$objectXml->resource->content);

		if ((string)$objectXml->resource['__identifier'] !== '') {
			$resource = $this->persistenceManager->getObjectByIdentifier((string)$objectXml->resource['__identifier'], 'TYPO3\Flow\Resource\Resource');
		}

		if (!isset($resource) || $resource === NULL) {
			$resource = $this->importResource(
				(string)$objectXml->resource->filename,
				$resourceHash !== '' ? $resourceHash : NULL,
				!empty($resourceData) ? $resourceData : NULL,
				(string)$objectXml->resource['__identifier'] !== '' ? (string)$objectXml->resource['__identifier'] : NULL
			);
		}

		$asset = $this->objectManager->get($className);
		$asset->setResource($resource);
		$this->assetRepository->add($asset);

		return $asset;
	}

	/**
	 * If the imported site is of a legacy schema where the near-root <site> element wasn't
	 * an actual node, the respective site xml is "upgraded" to become of type Shortcut,
	 * get a `title` property being the site's name, and being set to hidden in index.
	 *
	 * @param \SimpleXMLElement $siteXml
	 * @param \TYPO3\Neos\Domain\Model\Site $site
	 * @return void
	 */
	protected function upgradeLegacySiteXml(\SimpleXMLElement $siteXml, Site $site) {
		$siteXml->addAttribute('type', 'TYPO3.Neos:Shortcut');
		$siteXml->addAttribute('hiddenInIndex', 'true');

		if (property_exists($siteXml->properties, 'title') === FALSE) {
			$siteXml->properties->addChild('title', $site->getName());
		}
	}
}
namespace TYPO3\Neos\Domain\Service;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * The "legacy" Site Import Service which understands the "old" XML format used in Neos 1.0 and 1.1 which does not
 * allow for content dimensions.
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class LegacySiteImportService extends LegacySiteImportService_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\Neos\Domain\Service\LegacySiteImportService') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Domain\Service\LegacySiteImportService', $this);
		if ('TYPO3\Neos\Domain\Service\LegacySiteImportService' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}

		if (get_class($this) === 'TYPO3\Neos\Domain\Service\LegacySiteImportService') {
			$this->initializeObject(1);
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\Neos\Domain\Service\LegacySiteImportService') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Domain\Service\LegacySiteImportService', $this);

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

		if (get_class($this) === 'TYPO3\Neos\Domain\Service\LegacySiteImportService') {
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
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\Domain\Service\LegacySiteImportService');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\Domain\Service\LegacySiteImportService', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\Domain\Service\LegacySiteImportService', $propertyName, 'var');
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
		$resourceManager_reference = &$this->resourceManager;
		$this->resourceManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Resource\ResourceManager');
		if ($this->resourceManager === NULL) {
			$this->resourceManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('3b3239258e396ed88334e6f7199a1678', $resourceManager_reference);
			if ($this->resourceManager === NULL) {
				$this->resourceManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('3b3239258e396ed88334e6f7199a1678',  $resourceManager_reference, 'TYPO3\Flow\Resource\ResourceManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Resource\ResourceManager'); });
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
		$nodeTypeManager_reference = &$this->nodeTypeManager;
		$this->nodeTypeManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Service\NodeTypeManager');
		if ($this->nodeTypeManager === NULL) {
			$this->nodeTypeManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('478a517efacb3d47415a96d9caded2e9', $nodeTypeManager_reference);
			if ($this->nodeTypeManager === NULL) {
				$this->nodeTypeManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('478a517efacb3d47415a96d9caded2e9',  $nodeTypeManager_reference, 'TYPO3\TYPO3CR\Domain\Service\NodeTypeManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Service\NodeTypeManager'); });
			}
		}
		$imageRepository_reference = &$this->imageRepository;
		$this->imageRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Media\Domain\Repository\ImageRepository');
		if ($this->imageRepository === NULL) {
			$this->imageRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('329e1158c41cf5715d74ae55bb1e2310', $imageRepository_reference);
			if ($this->imageRepository === NULL) {
				$this->imageRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('329e1158c41cf5715d74ae55bb1e2310',  $imageRepository_reference, 'TYPO3\Media\Domain\Repository\ImageRepository', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Media\Domain\Repository\ImageRepository'); });
			}
		}
		$assetRepository_reference = &$this->assetRepository;
		$this->assetRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Media\Domain\Repository\AssetRepository');
		if ($this->assetRepository === NULL) {
			$this->assetRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('f32c311dcec701178d68823855159b62', $assetRepository_reference);
			if ($this->assetRepository === NULL) {
				$this->assetRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('f32c311dcec701178d68823855159b62',  $assetRepository_reference, 'TYPO3\Media\Domain\Repository\AssetRepository', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Media\Domain\Repository\AssetRepository'); });
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
		$objectManager_reference = &$this->objectManager;
		$this->objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Object\ObjectManagerInterface');
		if ($this->objectManager === NULL) {
			$this->objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('0c3c44be7be16f2a287f1fb2d068dde4', $objectManager_reference);
			if ($this->objectManager === NULL) {
				$this->objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('0c3c44be7be16f2a287f1fb2d068dde4',  $objectManager_reference, 'TYPO3\Flow\Object\ObjectManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Object\ObjectManagerInterface'); });
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
		$contextFactory_reference = &$this->contextFactory;
		$this->contextFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface');
		if ($this->contextFactory === NULL) {
			$this->contextFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('6b6e9d36a8365cb0dccb3d849ae9366e', $contextFactory_reference);
			if ($this->contextFactory === NULL) {
				$this->contextFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('6b6e9d36a8365cb0dccb3d849ae9366e',  $contextFactory_reference, 'TYPO3\Neos\Domain\Service\ContentContextFactory', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface'); });
			}
		}
$this->Flow_Injected_Properties = array (
  0 => 'packageManager',
  1 => 'resourceManager',
  2 => 'siteRepository',
  3 => 'nodeTypeManager',
  4 => 'imageRepository',
  5 => 'assetRepository',
  6 => 'reflectionService',
  7 => 'objectManager',
  8 => 'persistenceManager',
  9 => 'contextFactory',
);
	}
}
#