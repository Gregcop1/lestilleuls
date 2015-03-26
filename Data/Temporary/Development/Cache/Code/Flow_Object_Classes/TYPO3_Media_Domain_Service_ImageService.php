<?php 
namespace TYPO3\Media\Domain\Service;

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
use TYPO3\Flow\Cache\Frontend\VariableFrontend;
use TYPO3\Flow\Configuration\Exception\InvalidConfigurationException;
use TYPO3\Flow\Object\ObjectManagerInterface;
use TYPO3\Flow\Resource\Resource;
use TYPO3\Flow\Resource\ResourceManager;
use TYPO3\Flow\Utility\Arrays;
use TYPO3\Flow\Resource\ResourcePointer;
use TYPO3\Media\Domain\Model\ImageInterface;
use TYPO3\Media\Exception\ImageFileException;
use TYPO3\Media\Exception\MissingResourceException;

/**
 * An image service that acts as abstraction for the Imagine library
 *
 * @Flow\Scope("singleton")
 */
class ImageService_Original {

	/**
	 * @var ObjectManagerInterface
	 * @Flow\Inject
	 */
	protected $objectManager;

	/**
	 * @var ResourceManager
	 * @Flow\Inject
	 */
	protected $resourceManager;

	/**
	 * @var VariableFrontend
	 * @Flow\Inject
	 */
	protected $imageSizeCache;

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @param array $settings
	 * @return void
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * @param ImageInterface $image
	 * @param array $processingInstructions
	 * @return Resource
	 * @throws \Exception
	 */
	public function transformImage(ImageInterface $image, array $processingInstructions) {
		if (!$image->getResource()) {
			throw new MissingResourceException('Image resource could not be found.', 1403195673);
		}
		if (!$image->getResource()->getResourcePointer()) {
			throw new MissingResourceException('Image resource pointer could not be found.', 1403195674);
		}
		$uniqueHash = sha1($image->getResource()->getResourcePointer()->getHash() . '|' . json_encode($processingInstructions));
		$additionalOptions = array();
		if (!file_exists('resource://' . $uniqueHash)) {
			$originalResourcePath = 'resource://' . $image->getResource()->getResourcePointer()->getHash();
			if (!file_exists($originalResourcePath)) {
				throw new MissingResourceException('Image resource file could not be found.', 1418243434);
			}

			/** @var \Imagine\Image\ImagineInterface $imagine */
			$imagine = $this->objectManager->get('Imagine\Image\ImagineInterface');
			$imageContent = file_get_contents($originalResourcePath);
			$imagineImage = $imagine->load($imageContent);
			if ($imagine instanceof \Imagine\Imagick\Imagine &&  $image->getFileExtension() === 'gif' && $this->isAnimatedGif($imageContent) === TRUE) {
				$imagineImage->layers()->coalesce();
				foreach ($imagineImage->layers() as $imagineFrame) {
					$this->applyProcessingInstructions($imagineFrame, $processingInstructions);
				}
				$additionalOptions['animated'] = TRUE;
			} else {
				$imagineImage = $this->applyProcessingInstructions($imagineImage, $processingInstructions);
			}
			file_put_contents('resource://' . $uniqueHash, $imagineImage->get($image->getFileExtension(), $this->getDefaultOptions($additionalOptions)));
		}
		$resource = new Resource();
		$resource->setFilename($image->getResource()->getFilename());
		$resource->setResourcePointer(new ResourcePointer($uniqueHash));

		return $resource;
	}

	/**
	 * @param array $additionalOptions
	 * @return array
	 * @throws InvalidConfigurationException
	 */
	protected function getDefaultOptions(array $additionalOptions = array()) {
		$defaultOptions = Arrays::getValueByPath($this->settings, 'image.defaultOptions');
		if (!is_array($defaultOptions)) {
			$defaultOptions = array();
		}
		if ($additionalOptions !== array()) {
			$defaultOptions = Arrays::arrayMergeRecursiveOverrule($defaultOptions, $additionalOptions);
		}
		$quality = isset($defaultOptions['quality']) ? (integer)$defaultOptions['quality'] : 90;
		if ($quality < 0 || $quality > 100) {
			throw new InvalidConfigurationException(
				sprintf('Setting "TYPO3.Media.image.defaultOptions.quality" allow only value between 0 and 100, current value: %s', $quality),
				1404982574
			);
		}
		$defaultOptions['jpeg_quality'] = $quality;
		// png_compression_level should be an integer between 0 and 9 and inverse to the quality level given. So quality 100 should result in compression 0.
		$defaultOptions['png_compression_level'] = (9 - ceil($quality * 9 / 100));

		return $defaultOptions;
	}

	/**
	 * @param Resource $resource
	 * @return array width, height and image type
	 * @throws ImageFileException
	 */
	public function getImageSize(Resource $resource) {
		$cacheIdentifier = $resource->getResourcePointer()->getHash();
		if ($this->imageSizeCache->has($cacheIdentifier)) {
			return $this->imageSizeCache->get($cacheIdentifier);
		}
		$imageSize = getimagesize($resource->getUri());
		if ($imageSize === FALSE) {
			throw new ImageFileException('The given resource was not a valid image file', 1336662898);
		}
		$imageSize = array(
			(integer)$imageSize[0],
			(integer)$imageSize[1],
			(integer)$imageSize[2]
		);
		$this->imageSizeCache->set($cacheIdentifier, $imageSize);
		return $imageSize;
	}

	/**
	 * @param \Imagine\Image\ImageInterface $image
	 * @param array $processingInstructions
	 * @return \Imagine\Image\ImageInterface
	 * @throws \InvalidArgumentException
	 */
	protected function applyProcessingInstructions(\Imagine\Image\ImageInterface $image, array $processingInstructions) {
		foreach ($processingInstructions as $processingInstruction) {
			$commandName = $processingInstruction['command'];
			$commandMethodName = sprintf('%sCommand', $commandName);
			if (!is_callable(array($this, $commandMethodName))) {
				throw new \InvalidArgumentException('Invalid command "' . $commandName . '"', 1316613563);
			}
			$image = call_user_func(array($this, $commandMethodName), $image, $processingInstruction['options']);
		}
		return $image;
	}

	/**
	 * @param \Imagine\Image\ImageInterface $image
	 * @param array $commandOptions array('size' => ('width' => 123, 'height => 456), 'mode' => 'outbound')
	 * @return \Imagine\Image\ImageInterface
	 */
	protected function thumbnailCommand(\Imagine\Image\ImageInterface $image, array $commandOptions) {
		if (!isset($commandOptions['size'])) {
			throw new \InvalidArgumentException('The thumbnailCommand needs a "size" option.', 1393510202);
		}
		$dimensions = $this->parseBox($commandOptions['size']);
		if (isset($commandOptions['mode']) && $commandOptions['mode'] === ImageInterface::RATIOMODE_OUTBOUND) {
			$mode = \Imagine\Image\ManipulatorInterface::THUMBNAIL_OUTBOUND;
		} else {
			$mode = \Imagine\Image\ManipulatorInterface::THUMBNAIL_INSET;
		}
		return $image->thumbnail($dimensions, $mode);
	}

	/**
	 * @param \Imagine\Image\ImageInterface $image
	 * @param array $commandOptions array('size' => ('width' => 123, 'height => 456))
	 * @return \Imagine\Image\ImageInterface
	 */
	protected function resizeCommand(\Imagine\Image\ImageInterface $image, array $commandOptions) {
		if (!isset($commandOptions['size'])) {
			throw new \InvalidArgumentException('The resizeCommand needs a "size" option.', 1393510215);
		}
		$dimensions = $this->parseBox($commandOptions['size']);
		return $image->resize($dimensions);
	}

	/**
	 * @param \Imagine\Image\ImageInterface $image
	 * @param array $commandOptions array('start' => array('x' => 123, 'y' => 456), 'size' => array('width' => 123, 'height => 456))
	 * @return \Imagine\Image\ImageInterface
	 */
	protected function cropCommand(\Imagine\Image\ImageInterface $image, array $commandOptions) {
		if (!isset($commandOptions['start'])) {
			throw new \InvalidArgumentException('The cropCommand needs a "start" option.', 1393510229);
		}
		if (!isset($commandOptions['size'])) {
			throw new \InvalidArgumentException('The cropCommand needs a "size" option.', 1393510231);
		}
		$startPoint = $this->parsePoint($commandOptions['start']);
		$dimensions = $this->parseBox($commandOptions['size']);
		return $image->crop($startPoint, $dimensions);
	}

	/**
	 * @param \Imagine\Image\ImageInterface $image
	 * @param array $commandOptions
	 * @return \Imagine\Image\ImageInterface
	 */
	protected function drawCommand(\Imagine\Image\ImageInterface $image, array $commandOptions) {
		$drawer = $image->draw();
		foreach($commandOptions as $drawCommandName => $drawCommandOptions) {
			if ($drawCommandName === 'ellipse') {
				$drawer = $this->drawEllipse($drawer, $drawCommandOptions);
			} elseif ($drawCommandName === 'text') {
				$drawer = $this->drawText($drawer, $drawCommandOptions);
			} else {
				throw new \InvalidArgumentException('Invalid draw command "' . $drawCommandName . '"', 1316613593);
			}
		}
		return $image;
	}

	/**
	 * @param \Imagine\Draw\DrawerInterface $drawer
	 * @param array $commandOptions
	 * @return \Imagine\Draw\DrawerInterface
	 */
	protected function drawEllipse(\Imagine\Draw\DrawerInterface $drawer, array $commandOptions) {
		$center = $this->parsePoint($commandOptions['center']);
		$size = $this->parseBox($commandOptions['size']);
		$color = $this->parseColor($commandOptions['color']);
		$fill = isset($commandOptions['fill']) ? (boolean)$commandOptions['fill'] : FALSE;
		return $drawer->ellipse($center, $size, $color, $fill);
	}

	/**
	 * @param \Imagine\Draw\DrawerInterface $drawer
	 * @param array $commandOptions
	 * @return \Imagine\Draw\DrawerInterface
	 */
	protected function drawText(\Imagine\Draw\DrawerInterface $drawer, array $commandOptions) {
		$string = $commandOptions['string'];
		$font = $this->parseFont($commandOptions['font']);
		$position = $this->parsePoint($commandOptions['position']);
		$angle = (integer)$commandOptions['angle'];
		return $drawer->text($string, $font, $position, $angle);
	}

	/**
	 * @param array $coordinates
	 * @return \Imagine\Image\Point
	 */
	protected function parsePoint($coordinates) {
		return $this->objectManager->get('Imagine\Image\Point', $coordinates['x'], $coordinates['y']);
	}

	/**
	 * @param array $dimensions
	 * @return \Imagine\Image\Box
	 */
	protected function parseBox($dimensions) {
		return $this->objectManager->get('Imagine\Image\Box', $dimensions['width'], $dimensions['height']);
	}

	/**
	 * @param array $color
	 * @return \Imagine\Image\Color
	 */
	protected function parseColor($color) {
		$alpha = isset($color['alpha']) ? (integer)$color['alpha'] : NULL;
		if ($alpha > 100) {
			$alpha = 100;
		}
		if ($alpha < 0) {
			$alpha = 0;
		}
		return $this->objectManager->get('Imagine\Image\Color', $color['color'], $alpha);
	}

	/**
	 * @param array $options
	 * @return \Imagine\Image\FontInterface
	 */
	protected function parseFont($options) {
		$file = $options['file'];
		$size = $options['size'];
		$color = $this->parseColor($options['color']);
		return $this->objectManager->get('Imagine\Image\FontInterface', $file, $size, $color);
	}

	/**
	 * Detects whether the given GIF image data contains more than one frame
	 *
	 * @param string $image string containing the binary GIF data
	 * @return boolean true if gif contains more than one frame
	 */
	protected function isAnimatedGif($image) {
		$count = preg_match_all('#\x00\x21\xF9\x04.{4}\x00(\x2C|\x21)#s', $image, $matches);
		return $count ? TRUE : FALSE;
	}
}
namespace TYPO3\Media\Domain\Service;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * An image service that acts as abstraction for the Imagine library
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class ImageService extends ImageService_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\Media\Domain\Service\ImageService') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Media\Domain\Service\ImageService', $this);
		if ('TYPO3\Media\Domain\Service\ImageService' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\Media\Domain\Service\ImageService') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Media\Domain\Service\ImageService', $this);

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
	$reflectedClass = new \ReflectionClass('TYPO3\Media\Domain\Service\ImageService');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Media\Domain\Service\ImageService', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Media\Domain\Service\ImageService', $propertyName, 'var');
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
		$imageSizeCache_reference = &$this->imageSizeCache;
		$this->imageSizeCache = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('');
		if ($this->imageSizeCache === NULL) {
			$this->imageSizeCache = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('efc20710606cb1f639a267ab6a9b453f', $imageSizeCache_reference);
			if ($this->imageSizeCache === NULL) {
				$this->imageSizeCache = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('efc20710606cb1f639a267ab6a9b453f',  $imageSizeCache_reference, '', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Cache\CacheManager')->getCache('TYPO3_Media_ImageSize'); });
			}
		}
		$this->injectSettings(\TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Configuration\ConfigurationManager')->getConfiguration(\TYPO3\Flow\Configuration\ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, 'TYPO3.Media'));
		$objectManager_reference = &$this->objectManager;
		$this->objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Object\ObjectManagerInterface');
		if ($this->objectManager === NULL) {
			$this->objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('0c3c44be7be16f2a287f1fb2d068dde4', $objectManager_reference);
			if ($this->objectManager === NULL) {
				$this->objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('0c3c44be7be16f2a287f1fb2d068dde4',  $objectManager_reference, 'TYPO3\Flow\Object\ObjectManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Object\ObjectManagerInterface'); });
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
$this->Flow_Injected_Properties = array (
  0 => 'imageSizeCache',
  1 => 'settings',
  2 => 'objectManager',
  3 => 'resourceManager',
);
	}
}
#