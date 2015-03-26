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

use Imagine\Image\AbstractLayers;
use Imagine\Exception\RuntimeException;
use Imagine\Exception\NotSupportedException;
use Imagine\Exception\OutOfBoundsException;
use Imagine\Exception\InvalidArgumentException;
use Imagine\Image\Metadata\MetadataBag;
use Imagine\Image\Palette\PaletteInterface;

class Layers_Original extends AbstractLayers
{
    /**
     * @var Image
     */
    private $image;

    /**
     * @var \Gmagick
     */
    private $resource;

    /**
     * @var integer
     */
    private $offset = 0;

    /**
     * @var array
     */
    private $layers = array();

    /**
     * @var PaletteInterface
     */
    private $palette;

    public function __construct(Image $image, PaletteInterface $palette, \Gmagick $resource)
    {
        $this->image = $image;
        $this->resource = $resource;
        $this->palette = $palette;
    }

    /**
     * {@inheritdoc}
     */
    public function merge()
    {
        foreach ($this->layers as $offset => $image) {
            try {
                $this->resource->setimageindex($offset);
                $this->resource->setimage($image->getGmagick());
            } catch (\GmagickException $e) {
                throw new RuntimeException('Failed to substitute layer', $e->getCode(), $e);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function coalesce()
    {
        throw new NotSupportedException('Gmagick does not support coalescing');
    }

    /**
     * {@inheritdoc}
     */
    public function animate($format, $delay, $loops)
    {
        if ('gif' !== strtolower($format)) {
            throw new NotSupportedException('Animated picture is currently only supported on gif');
        }

        if (!is_int($loops) || $loops < 0) {
            throw new InvalidArgumentException('Loops must be a positive integer.');
        }

        if (null !== $delay && (!is_int($delay) || $delay < 0)) {
            throw new InvalidArgumentException('Delay must be either null or a positive integer.');
        }

        try {
            foreach ($this as $offset => $layer) {
                $this->resource->setimageindex($offset);
                $this->resource->setimageformat($format);

                if (null !== $delay) {
                    $this->resource->setimagedelay($delay / 10);
                }

                $this->resource->setimageiterations($loops);
            }
        } catch (\GmagickException $e) {
            throw new RuntimeException('Failed to animate layers', $e->getCode(), $e);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->extractAt($this->offset);
    }

    /**
     * Tries to extract layer at given offset
     *
     * @param  integer          $offset
     * @return Image
     * @throws RuntimeException
     */
    private function extractAt($offset)
    {
        if (!isset($this->layers[$offset])) {
            try {
                $this->resource->setimageindex($offset);
                $this->layers[$offset] = new Image($this->resource->getimage(), $this->palette, new MetadataBag());
            } catch (\GmagickException $e) {
                throw new RuntimeException(sprintf('Failed to extract layer %d', $offset), $e->getCode(), $e);
            }
        }

        return $this->layers[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->offset;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        ++$this->offset;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->offset = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->offset < count($this);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        try {
            return $this->resource->getnumberimages();
        } catch (\GmagickException $e) {
            throw new RuntimeException('Failed to count the number of layers', $e->getCode(), $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return is_int($offset) && $offset >= 0 && $offset < count($this);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->extractAt($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $image)
    {
        if (!$image instanceof Image) {
            throw new InvalidArgumentException('Only a Gmagick Image can be used as layer');
        }

        if (null === $offset) {
            $offset = count($this) - 1;
        } else {
            if (!is_int($offset)) {
                throw new InvalidArgumentException('Invalid offset for layer, it must be an integer');
            }

            if (count($this) < $offset || 0 > $offset) {
                throw new OutOfBoundsException(sprintf('Invalid offset for layer, it must be a value between 0 and %d, %d given', count($this), $offset));
            }

            if (isset($this[$offset])) {
                unset($this[$offset]);
                $offset = $offset - 1;
            }
        }

        $frame = $image->getGmagick();

        try {
            if (count($this) > 0) {
                $this->resource->setimageindex($offset);
                $this->resource->nextimage();
            }
            $this->resource->addimage($frame);

            /**
             * ugly hack to bypass issue https://bugs.php.net/bug.php?id=64623
             */
            if (count($this) == 2) {
                $this->resource->setimageindex($offset+1);
                $this->resource->nextimage();
                $this->resource->addimage($frame);
                unset($this[0]);
            }
        } catch (\GmagickException $e) {
            throw new RuntimeException('Unable to set the layer', $e->getCode(), $e);
        }

        $this->layers = array();
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        try {
            $this->extractAt($offset);
        } catch (RuntimeException $e) {
            return;
        }

        try {
            $this->resource->setimageindex($offset);
            $this->resource->removeimage();
        } catch (\GmagickException $e) {
            throw new RuntimeException('Unable to remove layer', $e->getCode(), $e);
        }
    }
}
namespace Imagine\Gmagick;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * 
 */
class Layers extends Layers_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		$arguments = func_get_args();
		if (!array_key_exists(0, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $image in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		if (!array_key_exists(1, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $palette in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		if (!array_key_exists(2, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $resource in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		call_user_func_array('parent::__construct', $arguments);
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
			}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __sleep() {
		$result = NULL;
		$this->Flow_Object_PropertiesToSerialize = array();
	$reflectionService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Reflection\ReflectionService');
	$reflectedClass = new \ReflectionClass('Imagine\Gmagick\Layers');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('Imagine\Gmagick\Layers', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('Imagine\Gmagick\Layers', $propertyName, 'var');
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