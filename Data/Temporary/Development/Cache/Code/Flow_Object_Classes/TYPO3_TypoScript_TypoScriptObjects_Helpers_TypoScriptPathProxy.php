<?php 
namespace TYPO3\TypoScript\TypoScriptObjects\Helpers;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TypoScript".            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * A proxy object representing a TypoScript path inside a Fluid Template. It allows
 * to render arbitrary TypoScript objects or Eel expressions using the already-known
 * property path syntax.
 *
 * It wraps a part of the TypoScript tree which does not contain TypoScript objects or Eel expressions.
 *
 * This class is instantiated inside TemplateImplementation and is never used outside.
 */
class TypoScriptPathProxy_Original implements \TYPO3\Fluid\Core\Parser\SyntaxTree\TemplateObjectAccessInterface, \ArrayAccess, \IteratorAggregate, \Countable {

	/**
	 * Reference to the TypoScript Runtime which controls the whole rendering
	 *
	 * @var \TYPO3\TypoScript\Core\Runtime
	 */
	protected $tsRuntime;

	/**
	 * Reference to the "parent" TypoScript object
	 *
	 * @var \TYPO3\TypoScript\TypoScriptObjects\TemplateImplementation
	 */
	protected $templateImplementation;

	/**
	 * The TypoScript path this object proxies
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * This is a part of the TypoScript tree built when evaluating $this->path.
	 *
	 * @var array
	 */
	protected $partialTypoScriptTree;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Log\SystemLoggerInterface
	 */
	protected $systemLogger;

	/**
	 * Constructor.
	 *
	 * @param \TYPO3\TypoScript\TypoScriptObjects\TemplateImplementation $templateImplementation
	 * @param string $path
	 * @param array $partialTypoScriptTree
	 */
	public function __construct(\TYPO3\TypoScript\TypoScriptObjects\TemplateImplementation $templateImplementation, $path, array $partialTypoScriptTree) {
		$this->templateImplementation = $templateImplementation;
		$this->tsRuntime = $templateImplementation->getTsRuntime();
		$this->path = $path;
		$this->partialTypoScriptTree = $partialTypoScriptTree;
	}

	/**
	 * TRUE if a given subpath exists, FALSE otherwise.
	 *
	 * @param string $offset
	 * @return boolean
	 */
	public function offsetExists($offset) {
		return isset($this->partialTypoScriptTree[$offset]);
	}

	/**
	 * Return the object at $offset; evaluating simple types right away, and
	 * wrapping arrays into ourselves again.
	 *
	 * @param string $offset
	 * @return mixed|\TYPO3\TypoScript\TypoScriptObjects\Helpers\TypoScriptPathProxy
	 */
	public function offsetGet($offset) {
		if (!isset($this->partialTypoScriptTree[$offset])) {
			return NULL;
		}
		if (!is_array($this->partialTypoScriptTree[$offset])) {
				// Simple type; we call "evaluate" nevertheless to make sure processors are applied.
			return $this->tsRuntime->evaluate($this->path . '/' . $offset);
		} else {
				// arbitrary array (could be Eel expression, TypoScript object, nested sub-array) again, so we wrap it with ourselves.
			return new TypoScriptPathProxy($this->templateImplementation, $this->path . '/' . $offset, $this->partialTypoScriptTree[$offset]);
		}
	}

	/**
	 * Stub to implement the ArrayAccess interface cleanly
	 *
	 * @param string $offset
	 * @param mixed $value
	 * @throws \TYPO3\TypoScript\Exception\UnsupportedProxyMethodException
	 */
	public function offsetSet($offset, $value) {
		throw new \TYPO3\TypoScript\Exception\UnsupportedProxyMethodException('Setting a property of a path proxy not supported. (tried to set: ' . $this->path . ' -- ' . $offset . ')', 1372667221);
	}

	/**
	 * Stub to implement the ArrayAccess interface cleanly
	 *
	 * @param string $offset
	 * @throws \TYPO3\TypoScript\Exception\UnsupportedProxyMethodException
	 */
	public function offsetUnset($offset) {
		throw new \TYPO3\TypoScript\Exception\UnsupportedProxyMethodException('Unsetting a property of a path proxy not supported. (tried to unset: ' . $this->path . ' -- ' . $offset . ')', 1372667331);
	}

	/**
	 * Post-Processor which is called whenever this object is encountered in a Fluid
	 * object access.
	 *
	 * Evaluates TypoScript objects and eel expressions.
	 *
	 * @return \TYPO3\TypoScript\TypoScriptObjects\Helpers\TypoScriptPathProxy|mixed
	 */
	public function objectAccess() {
		if (isset($this->partialTypoScriptTree['__objectType'])) {
			try {
				return $this->tsRuntime->evaluate($this->path);
			} catch (\Exception $exception) {
				return $this->tsRuntime->handleRenderingException($this->path, $exception);
			}
		} elseif (isset($this->partialTypoScriptTree['__eelExpression'])) {
			return $this->tsRuntime->evaluate($this->path, $this->templateImplementation);
		}

		return $this;
	}

	/**
	 * Iterates through all subelements.
	 *
	 * @return \ArrayIterator
	 */
	public function getIterator() {
		$evaluatedArray = array();
		foreach ($this->partialTypoScriptTree as $key => $value) {
			if (!is_array($value)) {
				$evaluatedArray[$key] = $value;
			} elseif (isset($value['__objectType'])) {
				$evaluatedArray[$key] = $this->tsRuntime->evaluate($this->path . '/' . $key);
			} elseif (isset($value['__eelExpression'])) {
				$evaluatedArray[$key] = $this->tsRuntime->evaluate($this->path . '/' . $key, $this->templateImplementation);
			} else {
				$evaluatedArray[$key] = new TypoScriptPathProxy($this->templateImplementation, $this->path . '/' . $key, $this->partialTypoScriptTree[$key]);
			}
		}
		return new \ArrayIterator($evaluatedArray);
	}

	/**
	 * @return integer
	 */
	public function count() {
		return count($this->partialTypoScriptTree);
	}

	/**
	 * Finally evaluate the TypoScript path
	 *
	 * As PHP does not like throwing an exception here, we render any exception using the configured TypoScript exception
	 * handler and will also catch and log any exceptions resulting from that as a last resort.
	 *
	 * @return string
	 */
	public function __toString() {
		try {
			return (string)$this->tsRuntime->evaluate($this->path);
		} catch (\Exception $exception) {
			try {
				return $this->tsRuntime->handleRenderingException($this->path, $exception);
			} catch (\Exception $exceptionHandlerException) {
				try {
					// Throwing an exception in __toString causes a fatal error, so if that happens we catch them and use the context dependent exception handler instead.
					$contextDependentExceptionHandler = new \TYPO3\TypoScript\Core\ExceptionHandlers\ContextDependentHandler();
					$contextDependentExceptionHandler->setRuntime($this->tsRuntime);
					return $contextDependentExceptionHandler->handleRenderingException($this->path, $exception);
				} catch(\Exception $contextDepndentExceptionHandlerException) {
					$this->systemLogger->logException($contextDepndentExceptionHandlerException, array('path' => $this->path));
					return sprintf(
						'<!-- Exception while rendering exception in %s: %s (%s) -->',
						$this->path,
						$contextDepndentExceptionHandlerException->getMessage(),
						$contextDepndentExceptionHandlerException instanceof \TYPO3\Flow\Exception ? 'see reference code ' . $contextDepndentExceptionHandlerException->getReferenceCode() . ' in log' : $contextDepndentExceptionHandlerException->getCode()
					);
				}
			}
		}
	}

}namespace TYPO3\TypoScript\TypoScriptObjects\Helpers;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * A proxy object representing a TypoScript path inside a Fluid Template. It allows
 * to render arbitrary TypoScript objects or Eel expressions using the already-known
 * property path syntax.
 * 
 * It wraps a part of the TypoScript tree which does not contain TypoScript objects or Eel expressions.
 * 
 * This class is instantiated inside TemplateImplementation and is never used outside.
 */
class TypoScriptPathProxy extends TypoScriptPathProxy_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 * @param \TYPO3\TypoScript\TypoScriptObjects\TemplateImplementation $templateImplementation
	 * @param string $path
	 * @param array $partialTypoScriptTree
	 */
	public function __construct() {
		$arguments = func_get_args();

		if (!array_key_exists(0, $arguments)) $arguments[0] = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TypoScript\TypoScriptObjects\TemplateImplementation');
		if (!array_key_exists(1, $arguments)) $arguments[1] = NULL;
		if (!array_key_exists(0, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $templateImplementation in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		if (!array_key_exists(1, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $path in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		if (!array_key_exists(2, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $partialTypoScriptTree in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		call_user_func_array('parent::__construct', $arguments);
		if ('TYPO3\TypoScript\TypoScriptObjects\Helpers\TypoScriptPathProxy' === get_class($this)) {
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
	$reflectedClass = new \ReflectionClass('TYPO3\TypoScript\TypoScriptObjects\Helpers\TypoScriptPathProxy');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\TypoScript\TypoScriptObjects\Helpers\TypoScriptPathProxy', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\TypoScript\TypoScriptObjects\Helpers\TypoScriptPathProxy', $propertyName, 'var');
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
		$systemLogger_reference = &$this->systemLogger;
		$this->systemLogger = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Log\SystemLoggerInterface');
		if ($this->systemLogger === NULL) {
			$this->systemLogger = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('6d57d95a1c3cd7528e3e6ea15012dac8', $systemLogger_reference);
			if ($this->systemLogger === NULL) {
				$this->systemLogger = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('6d57d95a1c3cd7528e3e6ea15012dac8',  $systemLogger_reference, 'TYPO3\Flow\Log\SystemLoggerInterface', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Log\SystemLoggerInterface'); });
			}
		}
$this->Flow_Injected_Properties = array (
  0 => 'systemLogger',
);
	}
}
#