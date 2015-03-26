<?php 
namespace TYPO3\Media\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Media".           *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Exception;
use TYPO3\Flow\Object\ObjectManagerInterface;
use TYPO3\Media\Domain\Service\ImageService;
use TYPO3\Media\Exception\ImageFileException;

/**
 * An image
 *
 * TODO: Remove duplicate code in Image and ImageVariant, by introducing a common base class or through Mixins/Traits (once they are available)
 *
 * @Flow\Entity
 */
class Image_Original extends Asset implements ImageInterface {

	/**
	 * @Flow\Inject
	 * @var ImageService
	 */
	protected $imageService;

	/**
	 * @var integer
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $width;

	/**
	 * @var integer
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $height;

	/**
	 * one of PHPs IMAGETYPE_* constants
	 *
	 * @var integer
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $type;

	/**
	 * @fixme this should be a collection, but that is currently not serialized by Doctrine
	 * @var array
	 */
	protected $imageVariants = array();

	/**
	 * @var boolean
	 * @Flow\Transient
	 */
	protected $imageSizeAndTypeInitialized = FALSE;

	/**
	 * If the object is recreated (that is, hydrated from persistence) the size and type is set to be initialized.
	 *
	 * @param integer $cause Why this object is initialized
	 */
	public function initializeObject($cause) {
		if ($cause === ObjectManagerInterface::INITIALIZATIONCAUSE_RECREATED) {
			$this->imageSizeAndTypeInitialized = TRUE;
		}
	}

	/**
	 * Calculates image width, height and type from the image resource
	 * The getimagesize() method may either return FALSE; or throw a Warning
	 * which is translated to a \TYPO3\Flow\Error\Exception by Flow. In both
	 * cases \TYPO3\Media\Exception\ImageFileException should be thrown.
	 *
	 * @throws ImageFileException
	 * @return void
	 */
	public function initializeImageSizeAndType() {
		try {
			if ($this->imageSizeAndTypeInitialized === TRUE) {
				return;
			}
			list($this->width, $this->height, $this->type) = $this->imageService->getImageSize($this->resource);
			$this->imageSizeAndTypeInitialized = TRUE;
		} catch (ImageFileException $exception) {
			throw $exception;
		} catch (Exception $exception) {
			$exceptionMessage = 'An error with code "' . $exception->getCode() . '" occurred when trying to read the image: "' . $exception->getMessage() . '"';
			throw new ImageFileException($exceptionMessage, 1336663970);
		}
	}

	/**
	 * Width of the image in pixels
	 *
	 * @return integer
	 */
	public function getWidth() {
		$this->initializeImageSizeAndType();

		return $this->width;
	}

	/**
	 * Height of the image in pixels
	 *
	 * @return integer
	 */
	public function getHeight() {
		$this->initializeImageSizeAndType();

		return $this->height;
	}

	/**
	 * Edge / aspect ratio of the image
	 *
	 * @param boolean $respectOrientation If false (the default), orientation is disregarded and always a value >= 1 is returned (like usual in "4 / 3" or "16 / 9")
	 * @return float
	 */
	public function getAspectRatio($respectOrientation = FALSE) {
		$aspectRatio = $this->getWidth() / $this->getHeight();
		if ($respectOrientation === FALSE && $aspectRatio < 1) {
			$aspectRatio = 1 / $aspectRatio;
		}

		return $aspectRatio;
	}

	/**
	 * Orientation of this image, i.e. portrait, landscape or square
	 *
	 * @return string One of this interface's ORIENTATION_* constants.
	 */
	public function getOrientation() {
		$aspectRatio = $this->getAspectRatio(TRUE);
		if ($aspectRatio > 1) {
			return ImageInterface::ORIENTATION_LANDSCAPE;
		} elseif ($aspectRatio < 1) {
			return ImageInterface::ORIENTATION_PORTRAIT;
		} else {
			return ImageInterface::ORIENTATION_SQUARE;
		}
	}

	/**
	 * Whether this image is square aspect ratio and therefore has a square orientation
	 *
	 * @return boolean
	 */
	public function isOrientationSquare() {
		return $this->getOrientation() === ImageInterface::ORIENTATION_SQUARE;
	}

	/**
	 * Whether this image is in landscape orientation
	 *
	 * @return boolean
	 */
	public function isOrientationLandscape() {
		return $this->getOrientation() === ImageInterface::ORIENTATION_LANDSCAPE;
	}

	/**
	 * Whether this image is in portrait orientation
	 *
	 * @return boolean
	 */
	public function isOrientationPortrait() {
		return $this->getOrientation() === ImageInterface::ORIENTATION_PORTRAIT;
	}

	/**
	 * One of PHPs IMAGETYPE_* constants that reflects the image type
	 *
	 * @see http://php.net/manual/image.constants.php
	 * @return integer
	 */
	public function getType() {
		$this->initializeImageSizeAndType();

		return $this->type;
	}

	/**
	 * File extension of the image without leading dot.
	 *
	 * @see http://www.php.net/manual/function.image-type-to-extension.php
	 *
	 * @return string
	 */
	public function getFileExtension() {
		return image_type_to_extension($this->getType(), FALSE);
	}

	/**
	 * Returns a thumbnail of this image.
	 *
	 * If maximum width/height is not specified or exceed the original images size,
	 * width/height of the original image is used
	 *
	 * Note: The image variant that will be created is intentionally not added to the
	 * imageVariants collection of this image. If you want to create a persisted image
	 * variant, use createImageVariant() instead.
	 *
	 * @param integer $maximumWidth
	 * @param integer $maximumHeight
	 * @param string $ratioMode Whether the resulting image should be cropped if both edge's sizes are supplied that would hurt the aspect ratio.
	 * @return \TYPO3\Media\Domain\Model\ImageVariant
	 * @see \TYPO3\Media\Domain\Service\ImageService::transformImage()
	 */
	public function getThumbnail($maximumWidth = NULL, $maximumHeight = NULL, $ratioMode = ImageInterface::RATIOMODE_INSET) {
		$processingInstructions = array(
			array(
				'command' => 'thumbnail',
				'options' => array(
					'size' => array(
						'width' => intval($maximumWidth ?: $this->width),
						'height' => intval($maximumHeight ?: $this->height)
					),
					'mode' => $ratioMode
				),
			),
		);

		return new ImageVariant($this, $processingInstructions);
	}

	/**
	 * Set all variants of this image.
	 *
	 * @param array<\TYPO3\Media\Domain\Model\ImageVariant> $imageVariants
	 * @return void
	 */
	public function setImageVariants(array $imageVariants) {
		$this->imageVariants = $imageVariants;
	}

	/**
	 * Return all variants of this image.
	 *
	 * @return array<\TYPO3\Media\Domain\Model\ImageVariant>
	 */
	public function getImageVariants() {
		return $this->imageVariants;
	}

	/**
	 * Create a variant of this image using the given processing instructions.
	 *
	 * The variant is attached to the image for later (re-)use. If the optional alias parameter is specified, the image
	 * variant can later be retrieved via getImageVariantByAlias()
	 * An alias could, for example, be "thumbnail", "small", "micro", "face-emphasized" etc.
	 *
	 * NOTE: If you want the new image variant to be persisted, make sure to update the image with ImageRepository::update()
	 *
	 * @param array $processingInstructions
	 * @param string $alias An optional alias name to allow easier retrieving of a previously created image variant
	 * @return \TYPO3\Media\Domain\Model\ImageVariant
	 */
	public function createImageVariant(array $processingInstructions, $alias = NULL) {
		$imageVariant = new ImageVariant($this, $processingInstructions, $alias);
		// FIXME we currently need a unique hash because $this->imageVariants has to be an array in order to be serialized by Doctrine
		$uniqueHash = sha1($this->resource->getResourcePointer()->getHash() . '|' . ($alias ?: json_encode($processingInstructions)));
		$this->imageVariants[$uniqueHash] = $imageVariant;

		return $imageVariant;
	}

	/**
	 * Remove the given variant from this image.
	 *
	 * NOTE: If you want to remove the image variant from persistence, make sure to update the image with ImageRepository::update()
	 *
	 * @param \TYPO3\Media\Domain\Model\ImageVariant $imageVariant
	 * @return void
	 */
	public function removeImageVariant(ImageVariant $imageVariant) {
		// FIXME we currently need a unique hash because $this->imageVariants has to be an array in order to be serialized by Doctrine
		$uniqueHash = sha1($this->resource->getResourcePointer()->getHash() . '|' . ($imageVariant->getAlias() ?: json_encode($imageVariant->getProcessingInstructions())));
		if (isset($this->imageVariants[$uniqueHash])) {
			unset($this->imageVariants[$uniqueHash]);
		}
	}

	/**
	 * Gets an ImageVariant by its alias
	 *
	 * @param string $alias
	 * @return \TYPO3\Media\Domain\Model\ImageVariant The ImageVariant if such found for the given alias, or NULL if not
	 */
	public function getImageVariantByAlias($alias) {
		foreach ($this->imageVariants as $imageVariant) {
			if ($imageVariant->getAlias() === $alias) {
				return $imageVariant;
			}
		}

		return NULL;
	}

	/**
	 * Removes an ImageVariant by its alias
	 *
	 * @param string $alias
	 * @return void
	 */
	public function removeImageVariantByAlias($alias) {
		$imageVariant = $this->getImageVariantByAlias($alias);
		if ($imageVariant instanceof ImageVariant) {
			$this->removeImageVariant($imageVariant);
		}
	}
}
namespace TYPO3\Media\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * An image
 * 
 * TODO: Remove duplicate code in Image and ImageVariant, by introducing a common base class or through Mixins/Traits (once they are available)
 * @\TYPO3\Flow\Annotations\Entity
 */
class Image extends Image_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface, \TYPO3\Flow\Persistence\Aspect\PersistenceMagicInterface {

	/**
	 * @var string
	 * @ORM\Id
	 * @ORM\Column(length=40)
	 * introduced by TYPO3\Flow\Persistence\Aspect\PersistenceMagicAspect
	 */
	protected $Persistence_Object_Identifier = NULL;

	private $Flow_Aop_Proxy_targetMethodsAndGroupedAdvices = array();

	private $Flow_Aop_Proxy_groupedAdviceChains = array();

	private $Flow_Aop_Proxy_methodIsInAdviceMode = array();


	/**
	 * Autogenerated Proxy Method
	 * @param \TYPO3\Flow\Resource\Resource $resource
	 */
	public function __construct() {
		$arguments = func_get_args();

		$this->Flow_Aop_Proxy_buildMethodsAndAdvicesArray();

			if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['__construct'])) {

		if (!array_key_exists(0, $arguments)) $arguments[0] = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Resource\Resource');
		if (!array_key_exists(0, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $resource in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		call_user_func_array('parent::__construct', $arguments);

			} else {
				$this->Flow_Aop_Proxy_methodIsInAdviceMode['__construct'] = TRUE;
				try {
				
					$methodArguments = array();

				if (array_key_exists(0, $arguments)) $methodArguments['resource'] = $arguments[0];
			
				if (isset($this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices['__construct']['TYPO3\Flow\Aop\Advice\BeforeAdvice'])) {
					$advices = $this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices['__construct']['TYPO3\Flow\Aop\Advice\BeforeAdvice'];
					$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Media\Domain\Model\Image', '__construct', $methodArguments);
					foreach ($advices as $advice) {
						$advice->invoke($joinPoint);
					}

					$methodArguments = $joinPoint->getMethodArguments();
				}

				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Media\Domain\Model\Image', '__construct', $methodArguments);
				$result = $this->Flow_Aop_Proxy_invokeJoinPoint($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

				} catch (\Exception $e) {
					unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['__construct']);
					throw $e;
				}
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['__construct']);
				return;
			}
		if ('TYPO3\Media\Domain\Model\Image' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}

		if (get_class($this) === 'TYPO3\Media\Domain\Model\Image') {
			$this->initializeObject(1);
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 protected function Flow_Aop_Proxy_buildMethodsAndAdvicesArray() {
		if (method_exists(get_parent_class($this), 'Flow_Aop_Proxy_buildMethodsAndAdvicesArray') && is_callable('parent::Flow_Aop_Proxy_buildMethodsAndAdvicesArray')) parent::Flow_Aop_Proxy_buildMethodsAndAdvicesArray();

		$objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager;
		$this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices = array(
			'__clone' => array(
				'TYPO3\Flow\Aop\Advice\BeforeAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\BeforeAdvice('TYPO3\Flow\Persistence\Aspect\PersistenceMagicAspect', 'generateUuid', $objectManager, NULL),
				),
				'TYPO3\Flow\Aop\Advice\AfterReturningAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AfterReturningAdvice('TYPO3\Flow\Persistence\Aspect\PersistenceMagicAspect', 'cloneObject', $objectManager, NULL),
				),
			),
			'__construct' => array(
				'TYPO3\Flow\Aop\Advice\BeforeAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\BeforeAdvice('TYPO3\Flow\Persistence\Aspect\PersistenceMagicAspect', 'generateUuid', $objectManager, NULL),
				),
			),
		);
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {

		$this->Flow_Aop_Proxy_buildMethodsAndAdvicesArray();

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

		if (get_class($this) === 'TYPO3\Media\Domain\Model\Image') {
			$this->initializeObject(2);
		}
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
	 */
	 public function __clone() {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['__clone'])) {
		$result = NULL;

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['__clone'] = TRUE;
			try {
			
					$methodArguments = array();

				if (isset($this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices['__clone']['TYPO3\Flow\Aop\Advice\BeforeAdvice'])) {
					$advices = $this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices['__clone']['TYPO3\Flow\Aop\Advice\BeforeAdvice'];
					$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Media\Domain\Model\Image', '__clone', $methodArguments);
					foreach ($advices as $advice) {
						$advice->invoke($joinPoint);
					}

					$methodArguments = $joinPoint->getMethodArguments();
				}

				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Media\Domain\Model\Image', '__clone', $methodArguments);
				$result = $this->Flow_Aop_Proxy_invokeJoinPoint($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

				if (isset($this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices['__clone']['TYPO3\Flow\Aop\Advice\AfterReturningAdvice'])) {
					$advices = $this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices['__clone']['TYPO3\Flow\Aop\Advice\AfterReturningAdvice'];
					$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Media\Domain\Model\Image', '__clone', $methodArguments, NULL, $result);
					foreach ($advices as $advice) {
						$advice->invoke($joinPoint);
					}

					$methodArguments = $joinPoint->getMethodArguments();
				}

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['__clone']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['__clone']);
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
	$reflectedClass = new \ReflectionClass('TYPO3\Media\Domain\Model\Image');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Media\Domain\Model\Image', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Media\Domain\Model\Image', $propertyName, 'var');
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
		$imageService_reference = &$this->imageService;
		$this->imageService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Media\Domain\Service\ImageService');
		if ($this->imageService === NULL) {
			$this->imageService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('cc17f3f26e20cfdc73dbc825104a8538', $imageService_reference);
			if ($this->imageService === NULL) {
				$this->imageService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('cc17f3f26e20cfdc73dbc825104a8538',  $imageService_reference, 'TYPO3\Media\Domain\Service\ImageService', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Media\Domain\Service\ImageService'); });
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
$this->Flow_Injected_Properties = array (
  0 => 'imageService',
  1 => 'persistenceManager',
);
	}
}
#