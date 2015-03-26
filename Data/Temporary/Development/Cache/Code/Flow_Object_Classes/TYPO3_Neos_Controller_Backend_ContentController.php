<?php 
namespace TYPO3\Neos\Controller\Backend;

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
use TYPO3\Media\Domain\Model\Asset;
use TYPO3\Media\Domain\Model\AssetInterface;
use TYPO3\Media\Domain\Model\Image;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\Eel\FlowQuery\FlowQuery;

/**
 * The TYPO3 ContentModule controller; providing backend functionality for the Content Module.
 *
 * @Flow\Scope("singleton")
 */
class ContentController_Original extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Media\Domain\Repository\AssetRepository
	 */
	protected $assetRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 */
	protected $persistenceManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Resource\Publishing\ResourcePublisher
	 */
	protected $resourcePublisher;

	/**
	 * The pluginService
	 *
	 * @var \TYPO3\Neos\Service\PluginService
	 * @Flow\Inject
	 */
	protected $pluginService;

	/**
	 * Upload a new Asset, and return its metadata
	 *
	 * Depending on the $metadata argument it will return asset metadata for the AssetEditor
	 * or image metadata for the ImageEditor
	 *
	 * @param Asset $asset
	 * @param string $metadata Type of metadata to return ("Asset" or "Image")
	 * @return string
	 */
	public function uploadAssetAction(Asset $asset, $metadata) {
		$this->assetRepository->add($asset);

		$this->response->setHeader('Content-Type', 'application/json');

		switch ($metadata) {
			case 'Asset':
				$result = $this->getAssetProperties($asset);
				break;
			case 'Image':
				$result = $this->getImageProperties($asset);
				break;
			default:
				$this->response->setStatus(400);
				$result = array('error' => 'Invalid "metadata" type: ' . $metadata);
		}
		return json_encode($result);
	}

	/**
	 * Fetch the metadata for a given image
	 *
	 * @param Image $image
	 * @return string JSON encoded response
	 */
	public function imageWithMetadataAction(Image $image) {
		$this->response->setHeader('Content-Type', 'application/json');

		$imageProperties = $this->getImageProperties($image);
		return json_encode($imageProperties);
	}

	/**
	 * @param Image $image
	 * @return array
	 */
	protected function getImageProperties(Image $image) {
		$thumbnail = $image->getThumbnail(600, 600);
		$imageProperties = array(
			'imageUuid' => $this->persistenceManager->getIdentifierByObject($image),
			'originalImageResourceUri' => $this->resourcePublisher->getPersistentResourceWebUri($image->getResource()),
			'previewImageResourceUri' => $this->resourcePublisher->getPersistentResourceWebUri($thumbnail->getResource()),
			'originalSize' => array('w' => $image->getWidth(), 'h' => $image->getHeight()),
			'previewSize' => array('w' => $thumbnail->getWidth(), 'h' => $thumbnail->getHeight())
		);
		return $imageProperties;
	}

	/**
	 * @return void
	 */
	public function initializeAssetsWithMetadataAction() {
		$propertyMappingConfiguration = $this->arguments->getArgument('assets')->getPropertyMappingConfiguration();
		$propertyMappingConfiguration->allowAllProperties();
	}

	/**
	 * Fetch the metadata for multiple assets
	 *
	 * @param array<TYPO3\Media\Domain\Model\Asset> $assets
	 * @return string JSON encoded response
	 */
	public function assetsWithMetadataAction(array $assets) {
		$this->response->setHeader('Content-Type', 'application/json');

		$result = array();
		foreach ($assets as $asset) {
			$result[] = $this->getAssetProperties($asset);
		}
		return json_encode($result);
	}

	/**
	 * @param Asset $asset
	 * @return array
	 */
	protected function getAssetProperties(Asset $asset) {
		$thumbnail = $this->getAssetThumbnailImage($asset, 16, 16);
		$assetProperties = array(
			'assetUuid' => $this->persistenceManager->getIdentifierByObject($asset),
			'filename' => $asset->getResource()->getFilename(),
			'previewImageResourceUri' => $this->resourcePublisher->getStaticResourcesWebBaseUri() . 'Packages/' . $thumbnail['src'],
			'previewSize' => array('w' => $thumbnail['width'], 'h' => $thumbnail['height'])
		);
		return $assetProperties;
	}

	/**
	 * @param integer $maximumWidth
	 * @param integer $maximumHeight
	 * @return integer
	 */
	protected function getDocumentIconSize($maximumWidth, $maximumHeight) {
		$size = max($maximumWidth, $maximumHeight);
		if ($size <= 16) {
			return 16;
		} elseif ($size <= 32) {
			return 32;
		} elseif ($size <= 48) {
			return 48;
		} else {
			return 512;
		}
	}

	/**
	 * @param AssetInterface $asset
	 * @param integer $maximumWidth
	 * @param integer $maximumHeight
	 * @return array
	 */
	protected function getAssetThumbnailImage(AssetInterface $asset, $maximumWidth, $maximumHeight) {
		$iconSize = $this->getDocumentIconSize($maximumWidth, $maximumHeight);

		if (is_file('resource://TYPO3.Media/Public/Icons/16px/' . $asset->getResource()->getFileExtension() . '.png')) {
			$icon = sprintf('TYPO3.Media/Icons/%spx/' . $asset->getResource()->getFileExtension() . '.png', $iconSize);
		} else {
			$icon =  sprintf('TYPO3.Media/Icons/%spx/_blank.png', $iconSize);
		}

		return array(
			'width' => $iconSize,
			'height' => $iconSize,
			'src' => $icon
		);
	}

	/**
	 * Fetch the configured views for the given master plugin
	 *
	 * @param NodeInterface $node
	 * @return string
	 *
	 * @Flow\IgnoreValidation("node")
	 */
	public function pluginViewsAction(NodeInterface $node = NULL) {
		$this->response->setHeader('Content-Type', 'application/json');

		$views = array();
		if ($node !== NULL) {
			/** @var $pluginViewDefinition \TYPO3\Neos\Domain\Model\PluginViewDefinition */
			$pluginViewDefinitions = $this->pluginService->getPluginViewDefinitionsByPluginNodeType($node->getNodeType());
			foreach ($pluginViewDefinitions as $pluginViewDefinition) {
				$label = $pluginViewDefinition->getLabel();

				$views[$pluginViewDefinition->getName()] = array('label' => $label);

				$pluginViewNode = $this->pluginService->getPluginViewNodeByMasterPlugin($node, $pluginViewDefinition->getName());
				if ($pluginViewNode === NULL) {
					continue;
				}
				$q = new FlowQuery(array($pluginViewNode));
				$page = $q->closest('[instanceof TYPO3.Neos:Document]')->get(0);
				$uri = $this->uriBuilder
					->reset()
					->uriFor('show', array('node' => $page), 'Frontend\Node', 'TYPO3.Neos');
				$pageTitle = $page->getProperty('title');
				$views[$pluginViewDefinition->getName()] = array(
					'label' => sprintf('"%s"', $label, $pageTitle),
					'pageNode' => array(
						'title' => $pageTitle,
						'path' => $page->getPath(),
						'uri' => $uri
					)
				);
			}
		}
		return json_encode((object) $views);
	}

	/**
	 * Fetch all master plugins that are available in the current
	 * workspace.
	 *
	 * @param NodeInterface $node
	 * @return string JSON encoded array of node path => label
	 */
	public function masterPluginsAction(NodeInterface $node) {
		$this->response->setHeader('Content-Type', 'application/json');

		$pluginNodes = $this->pluginService->getPluginNodesWithViewDefinitions($node->getContext());
		$masterPlugins = array();
		if (is_array($pluginNodes)) {
			/** @var $pluginNode NodeInterface */
			foreach ($pluginNodes as $pluginNode) {
				if ($pluginNode->isRemoved()) {
					continue;
				}
				$q = new FlowQuery(array($pluginNode));
				$page = $q->closest('[instanceof TYPO3.Neos:Document]')->get(0);
				if ($page === NULL) {
					continue;
				}
				$masterPlugins[$pluginNode->getPath()] = sprintf('"%s" on page "%s"', $pluginNode->getNodeType()->getLabel(), $page->getProperty('title'));
			}
		}
		return json_encode((object) $masterPlugins);
	}

}
namespace TYPO3\Neos\Controller\Backend;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * The TYPO3 ContentModule controller; providing backend functionality for the Content Module.
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class ContentController extends ContentController_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {

	private $Flow_Aop_Proxy_targetMethodsAndGroupedAdvices = array();

	private $Flow_Aop_Proxy_groupedAdviceChains = array();

	private $Flow_Aop_Proxy_methodIsInAdviceMode = array();


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {

		$this->Flow_Aop_Proxy_buildMethodsAndAdvicesArray();
		if (get_class($this) === 'TYPO3\Neos\Controller\Backend\ContentController') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Controller\Backend\ContentController', $this);
		if ('TYPO3\Neos\Controller\Backend\ContentController' === get_class($this)) {
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
			'uploadAssetAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'imageWithMetadataAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'assetsWithMetadataAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'pluginViewsAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'masterPluginsAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'errorAction' => array(
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
		if (get_class($this) === 'TYPO3\Neos\Controller\Backend\ContentController') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Controller\Backend\ContentController', $this);

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
	 * @param Asset $asset
	 * @param string $metadata Type of metadata to return ("Asset" or "Image")
	 * @return string
	 */
	 public function uploadAssetAction(\TYPO3\Media\Domain\Model\Asset $asset, $metadata) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['uploadAssetAction'])) {
		$result = parent::uploadAssetAction($asset, $metadata);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['uploadAssetAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['asset'] = $asset;
				$methodArguments['metadata'] = $metadata;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('uploadAssetAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Backend\ContentController', 'uploadAssetAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['uploadAssetAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['uploadAssetAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param Image $image
	 * @return string JSON encoded response
	 */
	 public function imageWithMetadataAction(\TYPO3\Media\Domain\Model\Image $image) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['imageWithMetadataAction'])) {
		$result = parent::imageWithMetadataAction($image);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['imageWithMetadataAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['image'] = $image;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('imageWithMetadataAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Backend\ContentController', 'imageWithMetadataAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['imageWithMetadataAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['imageWithMetadataAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param array<TYPO3\Media\Domain\Model\Asset> $assets
	 * @return string JSON encoded response
	 */
	 public function assetsWithMetadataAction(array $assets) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['assetsWithMetadataAction'])) {
		$result = parent::assetsWithMetadataAction($assets);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['assetsWithMetadataAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['assets'] = $assets;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('assetsWithMetadataAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Backend\ContentController', 'assetsWithMetadataAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['assetsWithMetadataAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['assetsWithMetadataAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param NodeInterface $node
	 * @return string
	 * @\TYPO3\Flow\Annotations\IgnoreValidation(argumentName="node")
	 */
	 public function pluginViewsAction(\TYPO3\TYPO3CR\Domain\Model\NodeInterface $node = NULL) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['pluginViewsAction'])) {
		$result = parent::pluginViewsAction($node);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['pluginViewsAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['node'] = $node;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('pluginViewsAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Backend\ContentController', 'pluginViewsAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['pluginViewsAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['pluginViewsAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param NodeInterface $node
	 * @return string JSON encoded array of node path => label
	 */
	 public function masterPluginsAction(\TYPO3\TYPO3CR\Domain\Model\NodeInterface $node) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['masterPluginsAction'])) {
		$result = parent::masterPluginsAction($node);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['masterPluginsAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['node'] = $node;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('masterPluginsAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Backend\ContentController', 'masterPluginsAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['masterPluginsAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['masterPluginsAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @return string
	 */
	 protected function errorAction() {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['errorAction'])) {
		$result = parent::errorAction();

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['errorAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('errorAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Backend\ContentController', 'errorAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['errorAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['errorAction']);
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
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\Controller\Backend\ContentController');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\Controller\Backend\ContentController', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\Controller\Backend\ContentController', $propertyName, 'var');
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
		$assetRepository_reference = &$this->assetRepository;
		$this->assetRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Media\Domain\Repository\AssetRepository');
		if ($this->assetRepository === NULL) {
			$this->assetRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('f32c311dcec701178d68823855159b62', $assetRepository_reference);
			if ($this->assetRepository === NULL) {
				$this->assetRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('f32c311dcec701178d68823855159b62',  $assetRepository_reference, 'TYPO3\Media\Domain\Repository\AssetRepository', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Media\Domain\Repository\AssetRepository'); });
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
		$resourcePublisher_reference = &$this->resourcePublisher;
		$this->resourcePublisher = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Resource\Publishing\ResourcePublisher');
		if ($this->resourcePublisher === NULL) {
			$this->resourcePublisher = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('666dcb29134e5c4063bc71f63e10ab36', $resourcePublisher_reference);
			if ($this->resourcePublisher === NULL) {
				$this->resourcePublisher = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('666dcb29134e5c4063bc71f63e10ab36',  $resourcePublisher_reference, 'TYPO3\Flow\Resource\Publishing\ResourcePublisher', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Resource\Publishing\ResourcePublisher'); });
			}
		}
		$pluginService_reference = &$this->pluginService;
		$this->pluginService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Neos\Service\PluginService');
		if ($this->pluginService === NULL) {
			$this->pluginService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('532382c725d032803dbc49ab78bcf0d8', $pluginService_reference);
			if ($this->pluginService === NULL) {
				$this->pluginService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('532382c725d032803dbc49ab78bcf0d8',  $pluginService_reference, 'TYPO3\Neos\Service\PluginService', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Neos\Service\PluginService'); });
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
$this->Flow_Injected_Properties = array (
  0 => 'settings',
  1 => 'assetRepository',
  2 => 'persistenceManager',
  3 => 'resourcePublisher',
  4 => 'pluginService',
  5 => 'objectManager',
  6 => 'reflectionService',
  7 => 'mvcPropertyMappingConfigurationService',
  8 => 'viewConfigurationManager',
  9 => 'systemLogger',
  10 => 'validatorResolver',
  11 => 'flashMessageContainer',
);
	}
}
#