<?php 
namespace TYPO3\Media\ViewHelpers;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Media".           *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Fluid\Core\ViewHelper\Exception;
use TYPO3\Media\Domain\Model\AssetInterface;
use TYPO3\Media\Domain\Model\ImageInterface;
use TYPO3\Fluid\Core\ViewHelper\Exception as ViewHelperException;

/**
 * Renders an <img> HTML tag from a given TYPO3.Media's asset instance
 *
 * = Examples =
 *
 * <code title="Rendering an asset as-is">
 * <m:image asset="{assetObject}" alt="a sample image without scaling" />
 * </code>
 * <output>
 * (depending on the asset, no scaling applied)
 * <img src="_Resources/Persistent/b29[...]95d.jpeg" width="120" height="180" alt="a sample image without scaling" />
 * </output>
 *
 *
 * <code title="Rendering an image with scaling at a given width only">
 * <m:image asset="{assetObject}" maximumWidth="80" alt="sample" />
 * </code>
 * <output>
 * (depending on the asset; scaled down to a maximum width of 80 pixels, keeping the aspect ratio)
 * <img src="_Resources/Persistent/b29[...]95d.jpeg" width="80" height="120" alt="sample" />
 * </output>
 *
 *
 * <code title="Rendering an image with scaling at given width and height, keeping aspect ratio">
 * <m:image asset="{assetObject}" maximumWidth="80" maximumHeight="80" alt="sample" />
 * </code>
 * <output>
 * (depending on the asset; scaled down to a maximum width and height of 80 pixels, keeping the aspect ratio)
 * <img src="_Resources/Persistent/b29[...]95d.jpeg" width="53" height="80" alt="sample" />
 * </output>
 *
 *
 * <code title="Rendering an image with crop-scaling at given width and height">
 * <m:image asset="{assetObject}" maximumWidth="80" maximumHeight="80" allowCropping="true" alt="sample" />
 * </code>
 * <output>
 * (depending on the asset; scaled down to a width and height of 80 pixels, possibly changing aspect ratio)
 * <img src="_Resources/Persistent/b29[...]95d.jpeg" width="80" height="80" alt="sample" />
 * </output>
 *
 * <code title="Rendering an image with allowed up-scaling at given width and height">
 * <m:image asset="{assetObject}" maximumWidth="5000" allowUpScaling="true" alt="sample" />
 * </code>
 * <output>
 * (depending on the asset; scaled up or down to a width 5000 pixels, keeping aspect ratio)
 * <img src="_Resources/Persistent/b29[...]95d.jpeg" width="80" height="80" alt="sample" />
 * </output>
 *
 */
class ImageViewHelper_Original extends \TYPO3\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {

	/**
	 * @var \TYPO3\Flow\Resource\Publishing\ResourcePublisher
	 * @Flow\Inject
	 */
	protected $resourcePublisher;

	/**
	 * @var \TYPO3\Media\Service\ImageService
	 * @Flow\Inject
	 */
	protected $imageService;

	/**
	 * name of the tag to be created by this view helper
	 *
	 * @var string
	 */
	protected $tagName = 'img';

	/**
	 * @return void
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerUniversalTagAttributes();
		$this->registerTagAttribute('alt', 'string', 'Specifies an alternate text for an image', TRUE);
		$this->registerTagAttribute('ismap', 'string', 'Specifies an image as a server-side image-map. Rarely used. Look at usemap instead', FALSE);
		$this->registerTagAttribute('usemap', 'string', 'Specifies an image as a client-side image-map', FALSE);
		// @deprecated since 1.1.0 image argument replaced with asset argument
		$this->registerArgument('image', 'ImageInterface', 'The image to be rendered', FALSE);
	}

	/**
	 * Renders an HTML tag from a given asset.
	 *
	 * @param AssetInterface $asset The asset to be rendered as an image
	 * @param integer $maximumWidth Desired maximum height of the image
	 * @param integer $maximumHeight Desired maximum width of the image
	 * @param boolean $allowCropping Whether the image should be cropped if the given sizes would hurt the aspect ratio
	 * @param boolean $allowUpScaling Whether the resulting image size might exceed the size of the original image
	 * @return string an <img...> html tag
	 * @throws Exception
	 */
	public function render(AssetInterface $asset = NULL, $maximumWidth = NULL, $maximumHeight = NULL, $allowCropping = FALSE, $allowUpScaling = FALSE) {
		// Fallback for deprecated image argument
		$asset = $asset === NULL && $this->hasArgument('image') ? $this->arguments['image'] : $asset;
		if (!$asset instanceof AssetInterface) {
			throw new ViewHelperException('No asset given for rendering.', 1415797903);
		}

		try {
			if ($asset instanceof ImageInterface) {
				$thumbnailImage = $this->imageService->getImageThumbnailImage($asset, $maximumWidth, $maximumHeight, $allowCropping, $allowUpScaling);
				$this->tag->addAttributes(array(
					'width' => $thumbnailImage->getWidth(),
					'height' => $thumbnailImage->getHeight(),
					'src' => $this->resourcePublisher->getPersistentResourceWebUri($thumbnailImage->getResource()),
				));
			} else {
				$thumbnailImage = $this->imageService->getAssetThumbnailImage($asset, $maximumWidth, $maximumHeight);
				$this->tag->addAttributes(array(
					'width' => $thumbnailImage['width'],
					'height' => $thumbnailImage['height'],
					'src' => $this->resourcePublisher->getStaticResourcesWebBaseUri() . 'Packages/' . $thumbnailImage['src'],
				));
			}
		} catch (\Exception $exception) {
			$this->systemLogger->logException($exception);
			return '<!-- Unable to render image, exception code ' . $exception->getCode() . ' -->';
		}

		return $this->tag->render();
	}

}
namespace TYPO3\Media\ViewHelpers;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * Renders an <img> HTML tag from a given TYPO3.Media's asset instance
 * 
 * = Examples =
 * 
 * <code title="Rendering an asset as-is">
 * <m:image asset="{assetObject}" alt="a sample image without scaling" />
 * </code>
 * <output>
 * (depending on the asset, no scaling applied)
 * <img src="_Resources/Persistent/b29[...]95d.jpeg" width="120" height="180" alt="a sample image without scaling" />
 * </output>
 * 
 * 
 * <code title="Rendering an image with scaling at a given width only">
 * <m:image asset="{assetObject}" maximumWidth="80" alt="sample" />
 * </code>
 * <output>
 * (depending on the asset; scaled down to a maximum width of 80 pixels, keeping the aspect ratio)
 * <img src="_Resources/Persistent/b29[...]95d.jpeg" width="80" height="120" alt="sample" />
 * </output>
 * 
 * 
 * <code title="Rendering an image with scaling at given width and height, keeping aspect ratio">
 * <m:image asset="{assetObject}" maximumWidth="80" maximumHeight="80" alt="sample" />
 * </code>
 * <output>
 * (depending on the asset; scaled down to a maximum width and height of 80 pixels, keeping the aspect ratio)
 * <img src="_Resources/Persistent/b29[...]95d.jpeg" width="53" height="80" alt="sample" />
 * </output>
 * 
 * 
 * <code title="Rendering an image with crop-scaling at given width and height">
 * <m:image asset="{assetObject}" maximumWidth="80" maximumHeight="80" allowCropping="true" alt="sample" />
 * </code>
 * <output>
 * (depending on the asset; scaled down to a width and height of 80 pixels, possibly changing aspect ratio)
 * <img src="_Resources/Persistent/b29[...]95d.jpeg" width="80" height="80" alt="sample" />
 * </output>
 * 
 * <code title="Rendering an image with allowed up-scaling at given width and height">
 * <m:image asset="{assetObject}" maximumWidth="5000" allowUpScaling="true" alt="sample" />
 * </code>
 * <output>
 * (depending on the asset; scaled up or down to a width 5000 pixels, keeping aspect ratio)
 * <img src="_Resources/Persistent/b29[...]95d.jpeg" width="80" height="80" alt="sample" />
 * </output>
 */
class ImageViewHelper extends ImageViewHelper_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		parent::__construct();
		if ('TYPO3\Media\ViewHelpers\ImageViewHelper' === get_class($this)) {
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
	$reflectedClass = new \ReflectionClass('TYPO3\Media\ViewHelpers\ImageViewHelper');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Media\ViewHelpers\ImageViewHelper', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Media\ViewHelpers\ImageViewHelper', $propertyName, 'var');
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
		$this->injectTagBuilder(new \TYPO3\Fluid\Core\ViewHelper\TagBuilder('', ''));
		$this->injectObjectManager(\TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Object\ObjectManagerInterface'));
		$this->injectSystemLogger(\TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Log\SystemLoggerInterface'));
		$resourcePublisher_reference = &$this->resourcePublisher;
		$this->resourcePublisher = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Resource\Publishing\ResourcePublisher');
		if ($this->resourcePublisher === NULL) {
			$this->resourcePublisher = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('666dcb29134e5c4063bc71f63e10ab36', $resourcePublisher_reference);
			if ($this->resourcePublisher === NULL) {
				$this->resourcePublisher = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('666dcb29134e5c4063bc71f63e10ab36',  $resourcePublisher_reference, 'TYPO3\Flow\Resource\Publishing\ResourcePublisher', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Resource\Publishing\ResourcePublisher'); });
			}
		}
		$imageService_reference = &$this->imageService;
		$this->imageService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Media\Service\ImageService');
		if ($this->imageService === NULL) {
			$this->imageService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('1981b8e0da50dd9d7d241e86129739fd', $imageService_reference);
			if ($this->imageService === NULL) {
				$this->imageService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('1981b8e0da50dd9d7d241e86129739fd',  $imageService_reference, 'TYPO3\Media\Service\ImageService', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Media\Service\ImageService'); });
			}
		}
$this->Flow_Injected_Properties = array (
  0 => 'tagBuilder',
  1 => 'objectManager',
  2 => 'systemLogger',
  3 => 'resourcePublisher',
  4 => 'imageService',
);
	}
}
#