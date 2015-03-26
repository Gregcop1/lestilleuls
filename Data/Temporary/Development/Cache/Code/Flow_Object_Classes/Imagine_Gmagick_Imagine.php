<?php 

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Gmagick;

use Imagine\Image\AbstractImagine;
use Imagine\Exception\NotSupportedException;
use Imagine\Image\BoxInterface;
use Imagine\Image\Metadata\MetadataBag;
use Imagine\Image\Palette\Color\ColorInterface;
use Imagine\Image\Palette\Grayscale;
use Imagine\Image\Palette\CMYK;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Palette\Color\CMYK as CMYKColor;
use Imagine\Exception\InvalidArgumentException;
use Imagine\Exception\RuntimeException;

/**
 * Imagine implementation using the Gmagick PHP extension
 */
class Imagine_Original extends AbstractImagine
{
    /**
     * @throws RuntimeException
     */
    public function __construct()
    {
        if (!class_exists('Gmagick')) {
            throw new RuntimeException('Gmagick not installed');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function open($path)
    {
        $path = $this->checkPath($path);

        try {
            $gmagick = new \Gmagick($path);
            $image = new Image($gmagick, $this->createPalette($gmagick), $this->getMetadataReader()->readFile($path));
        } catch (\GmagickException $e) {
            throw new RuntimeException(sprintf('Unable to open image %s', $path), $e->getCode(), $e);
        }

        return $image;
    }

    /**
     * {@inheritdoc}
     */
    public function create(BoxInterface $size, ColorInterface $color = null)
    {
        $width = $size->getWidth();
        $height = $size->getHeight();

        $palette = null !== $color ? $color->getPalette() : new RGB();
        $color = null !== $color ? $color : $palette->color('fff');

        try {
            $gmagick = new \Gmagick();
            // Gmagick does not support creation of CMYK GmagickPixel
            // see https://bugs.php.net/bug.php?id=64466
            if ($color instanceof CMYKColor) {
                $switchPalette = $palette;
                $palette = new RGB();
                $pixel   = new \GmagickPixel($palette->color((string) $color));
            } else {
                $switchPalette = null;
                $pixel   = new \GmagickPixel((string) $color);
            }

            if ($color->getPalette()->supportsAlpha() && $color->getAlpha() < 100) {
                throw new NotSupportedException('alpha transparency is not supported');
            }

            $gmagick->newimage($width, $height, $pixel->getcolor(false));
            $gmagick->setimagecolorspace(\Gmagick::COLORSPACE_TRANSPARENT);
            $gmagick->setimagebackgroundcolor($pixel);

            $image = new Image($gmagick, $palette, new MetadataBag());

            if ($switchPalette) {
                $image->usePalette($switchPalette);
            }

            return $image;
        } catch (\GmagickException $e) {
            throw new RuntimeException('Could not create empty image', $e->getCode(), $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function load($string)
    {
        return $this->doLoad($string, $this->getMetadataReader()->readData($string));
    }

    /**
     * {@inheritdoc}
     */
    public function read($resource)
    {
        if (!is_resource($resource)) {
            throw new InvalidArgumentException('Variable does not contain a stream resource');
        }

        $content = stream_get_contents($resource);

        if (false === $content) {
            throw new InvalidArgumentException('Couldn\'t read given resource');
        }

        return $this->doLoad($content, $this->getMetadataReader()->readStream($resource));
    }

    /**
     * {@inheritdoc}
     */
    public function font($file, $size, ColorInterface $color)
    {
        $gmagick = new \Gmagick();
        $gmagick->newimage(1, 1, 'transparent');

        return new Font($gmagick, $file, $size, $color);
    }

    private function createPalette(\Gmagick $gmagick)
    {
        switch ($gmagick->getimagecolorspace()) {
            case \Gmagick::COLORSPACE_SRGB:
            case \Gmagick::COLORSPACE_RGB:
                return new RGB();
            case \Gmagick::COLORSPACE_CMYK:
                return new CMYK();
            case \Gmagick::COLORSPACE_GRAY:
                return new Grayscale();
            default:
                throw new NotSupportedException('Only RGB and CMYK colorspace are currently supported');
        }
    }

    private function doLoad($content, MetadataBag $metadata)
    {
        try {
            $gmagick = new \Gmagick();
            $gmagick->readimageblob($content);
        } catch (\GmagickException $e) {
            throw new RuntimeException(
                'Could not load image from string', $e->getCode(), $e
            );
        }

        return new Image($gmagick, $this->createPalette($gmagick), $metadata);
    }
}
namespace Imagine\Gmagick;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * Imagine implementation using the Gmagick PHP extension
 */
class Imagine extends Imagine_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


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
			}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __sleep() {
		$result = NULL;
		$this->Flow_Object_PropertiesToSerialize = array();
	$reflectionService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Reflection\ReflectionService');
	$reflectedClass = new \ReflectionClass('Imagine\Gmagick\Imagine');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('Imagine\Gmagick\Imagine', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('Imagine\Gmagick\Imagine', $propertyName, 'var');
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
}
#