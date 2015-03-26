<?php 
namespace TYPO3\Neos\TypoScript;

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
use TYPO3\Flow\Log\SystemLoggerInterface;
use TYPO3\Flow\Mvc\ActionRequest;
use TYPO3\Flow\Http\Response;
use TYPO3\Flow\Mvc\Dispatcher;
use TYPO3\Flow\Object\ObjectManagerInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TypoScript\TypoScriptObjects\AbstractArrayTypoScriptObject;

/**
 * A TypoScript Plugin object.
 */
class PluginImplementation_Original extends AbstractArrayTypoScriptObject {

	/**
	 * @Flow\Inject
	 * @var ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @Flow\Inject
	 * @var Dispatcher
	 */
	protected $dispatcher;

	/**
	 * @var NodeInterface
	 */
	protected $node;

	/**
	 * @var NodeInterface
	 */
	protected $documentNode;

	/**
	 * @Flow\Inject
	 * @var SystemLoggerInterface
	 */
	protected $systemLogger;

	/**
	 * @return string
	 */
	public function getPackage() {
		return $this->tsValue('package');
	}

	/**
	 * @return string
	 */
	public function getSubpackage() {
		return $this->tsValue('subpackage');
	}

	/**
	 * @return string
	 */
	public function getController() {
		return $this->tsValue('controller');
	}

	/**
	 * @return string
	 */
	public function getAction() {
		return $this->tsValue('action');
	}

	/**
	 * @return string
	 */
	public function getArgumentNamespace() {
		return $this->tsValue('argumentNamespace');
	}

	/**
	 * Build the pluginRequest object
	 *
	 * @return ActionRequest
	 */
	protected function buildPluginRequest() {
		/** @var $parentRequest ActionRequest */
		$parentRequest = $this->tsRuntime->getControllerContext()->getRequest();
		$pluginRequest = new ActionRequest($parentRequest);
		$pluginRequest->setArgumentNamespace('--' . $this->getPluginNamespace());
		$this->passArgumentsToPluginRequest($pluginRequest);

		if ($this->node instanceof NodeInterface) {
			$pluginRequest->setArgument('__node', $this->node);
			if ($pluginRequest->getControllerPackageKey() === NULL) {
				$pluginRequest->setControllerPackageKey($this->node->getProperty('package') ?: $this->getPackage());
			}
			if ($pluginRequest->getControllerSubpackageKey() === NULL) {
				$pluginRequest->setControllerSubpackageKey($this->node->getProperty('subpackage') ?: $this->getSubpackage());
			}
			if ($pluginRequest->getControllerName() === NULL) {
				$pluginRequest->setControllerName($this->node->getProperty('controller') ?: $this->getController());
			}
			if ($pluginRequest->getControllerActionName() === NULL) {
				$actionName = $this->node->getProperty('action');
				if ($actionName === NULL || $actionName === '') {
					$actionName = $this->getAction() !== NULL ? $this->getAction() : 'index';
				}
				$pluginRequest->setControllerActionName($actionName);
			}

			$pluginRequest->setArgument('__node', $this->node);
			$pluginRequest->setArgument('__documentNode', $this->documentNode);
		} else {
			$pluginRequest->setControllerPackageKey($this->getPackage());
			$pluginRequest->setControllerSubpackageKey($this->getSubpackage());
			$pluginRequest->setControllerName($this->getController());
			$pluginRequest->setControllerActionName($this->getAction());
		}

		foreach ($this->properties as $key => $value) {
			$pluginRequest->setArgument('__' . $key, $this->tsValue($key));
		}
		return $pluginRequest;
	}

	/**
	 * Returns the rendered content of this plugin
	 *
	 * @return string The rendered content as a string
	 */
	public function evaluate() {
		try {
			$currentContext = $this->tsRuntime->getCurrentContext();
			$this->node = $currentContext['node'];
			$this->documentNode = $currentContext['documentNode'];
			/** @var $parentResponse Response */
			$parentResponse = $this->tsRuntime->getControllerContext()->getResponse();
			$pluginResponse = new Response($parentResponse);

			$this->dispatcher->dispatch($this->buildPluginRequest(), $pluginResponse);
			$content = $pluginResponse->getContent();
		} catch (\Exception $exception) {
			$content = $this->tsRuntime->handleRenderingException($this->path, $exception);
		}
		return $content;
	}

	/**
	 * Returns the plugin namespace that will be prefixed to plugin parameters in URIs.
	 * By default this is <plugin_class_name>
	 *
	 * @return string
	 */
	protected function getPluginNamespace() {
		if ($this->node instanceof NodeInterface) {
			$nodeArgumentNamespace = $this->node->getProperty('argumentNamespace');
			if ($nodeArgumentNamespace !== NULL) {
				return $nodeArgumentNamespace;
			}

			$nodeArgumentNamespace = $this->node->getNodeType()->getName();
			$nodeArgumentNamespace = str_replace(':', '-', $nodeArgumentNamespace);
			$nodeArgumentNamespace = str_replace('.', '_', $nodeArgumentNamespace);
			$nodeArgumentNamespace = strtolower($nodeArgumentNamespace);
			return $nodeArgumentNamespace;
		}

		if ($this->getArgumentNamespace() !== NULL) {
			return $this->getArgumentNamespace();
		}

		$argumentNamespace = str_replace(array(':', '.', '\\'), array('_', '_', '_'), ($this->getPackage() . '_' . $this->getSubpackage() . '-' . $this->getController()));
		$argumentNamespace = strtolower($argumentNamespace);

		return $argumentNamespace;
	}

	/**
	 * Pass the arguments which were addressed to the plugin to its own request
	 *
	 * @param ActionRequest $pluginRequest The plugin request
	 * @return void
	 */
	protected function passArgumentsToPluginRequest(ActionRequest $pluginRequest) {
		$arguments = $pluginRequest->getMainRequest()->getPluginArguments();
		$pluginNamespace = $this->getPluginNamespace();
		if (isset($arguments[$pluginNamespace])) {
			$pluginRequest->setArguments($arguments[$pluginNamespace]);
		}
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->evaluate();
	}
}
namespace TYPO3\Neos\TypoScript;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * A TypoScript Plugin object.
 */
class PluginImplementation extends PluginImplementation_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 * @param \TYPO3\TypoScript\Core\Runtime $tsRuntime
	 * @param string $path
	 * @param string $typoScriptObjectName
	 */
	public function __construct() {
		$arguments = func_get_args();

		if (!array_key_exists(0, $arguments)) $arguments[0] = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TypoScript\Core\Runtime');
		if (!array_key_exists(1, $arguments)) $arguments[1] = NULL;
		if (!array_key_exists(2, $arguments)) $arguments[2] = NULL;
		if (!array_key_exists(0, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $tsRuntime in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		if (!array_key_exists(1, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $path in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		if (!array_key_exists(2, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $typoScriptObjectName in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		call_user_func_array('parent::__construct', $arguments);
		if ('TYPO3\Neos\TypoScript\PluginImplementation' === get_class($this)) {
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
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\TypoScript\PluginImplementation');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\TypoScript\PluginImplementation', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\TypoScript\PluginImplementation', $propertyName, 'var');
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
		$objectManager_reference = &$this->objectManager;
		$this->objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Object\ObjectManagerInterface');
		if ($this->objectManager === NULL) {
			$this->objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('0c3c44be7be16f2a287f1fb2d068dde4', $objectManager_reference);
			if ($this->objectManager === NULL) {
				$this->objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('0c3c44be7be16f2a287f1fb2d068dde4',  $objectManager_reference, 'TYPO3\Flow\Object\ObjectManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Object\ObjectManagerInterface'); });
			}
		}
		$dispatcher_reference = &$this->dispatcher;
		$this->dispatcher = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Mvc\Dispatcher');
		if ($this->dispatcher === NULL) {
			$this->dispatcher = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('4a06fecb7a70b8eded695785a471c0f4', $dispatcher_reference);
			if ($this->dispatcher === NULL) {
				$this->dispatcher = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('4a06fecb7a70b8eded695785a471c0f4',  $dispatcher_reference, 'TYPO3\Flow\Mvc\Dispatcher', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Mvc\Dispatcher'); });
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
$this->Flow_Injected_Properties = array (
  0 => 'objectManager',
  1 => 'dispatcher',
  2 => 'systemLogger',
);
	}
}
#