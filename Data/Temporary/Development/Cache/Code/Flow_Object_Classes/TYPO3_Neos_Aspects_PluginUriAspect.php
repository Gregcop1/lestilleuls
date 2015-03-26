<?php 
namespace TYPO3\Neos\Aspects;

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
use TYPO3\Flow\Aop\JoinPointInterface;
use TYPO3\Flow\Mvc\ActionRequest;
use TYPO3\TYPO3CR\Domain\Model\Node;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\Eel\FlowQuery\FlowQuery;

/**
 * @Flow\Scope("singleton")
 * @Flow\Aspect
 */
class PluginUriAspect_Original {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * The pluginService
	 *
	 * @var \TYPO3\Neos\Service\PluginService
	 * @Flow\Inject
	 */
	protected $pluginService;

	/**
	 * @Flow\Around("method(TYPO3\Flow\Mvc\Routing\UriBuilder->uriFor())")
	 * @param \TYPO3\Flow\Aop\JoinPointInterface $joinPoint The current join point
	 * @return string The result of the target method if it has not been intercepted
	 */
	public function rewritePluginViewUris(JoinPointInterface $joinPoint) {
		/** @var \TYPO3\Flow\Mvc\ActionRequest $request */
		$request = $joinPoint->getProxy()->getRequest();
		$arguments = $joinPoint->getMethodArguments();

		$currentNode = $request->getInternalArgument('__node');
		if (!$request->getMainRequest()->hasArgument('node') || !$currentNode instanceof Node) {
			return $joinPoint->getAdviceChain()->proceed($joinPoint);
		}

		$currentNode = $request->getInternalArgument('__node');
		$controllerObjectName = $this->getControllerObjectName($request, $arguments);
		$actionName = $arguments['actionName'] !== NULL ? $arguments['actionName'] : $request->getControllerActionName();

		$targetNode = $this->pluginService->getPluginNodeByAction($currentNode, $controllerObjectName, $actionName);

		// TODO override namespace

		$q = new FlowQuery(array($targetNode));
		$pageNode = $q->closest('[instanceof TYPO3.Neos:Document]')->get(0);
		$result = $this->generateUriForNode($request, $joinPoint, $pageNode);

		return $result;
	}

	/**
	 * Merge the default plugin arguments of the Plugin with the arguments in the request
	 * and generate a controllerObjectName
	 *
	 * @param object $request
	 * @param array $arguments
	 * @return string $controllerObjectName
	 */
	public function getControllerObjectName($request, array $arguments) {
		$controllerName = $arguments['controllerName'] !== NULL ? $arguments['controllerName'] : $request->getControllerName();
		$subPackageKey = $arguments['subPackageKey'] !== NULL ? $arguments['subPackageKey'] : $request->getControllerSubpackageKey();
		$packageKey = $arguments['packageKey'] !== NULL ? $arguments['packageKey'] : $request->getControllerPackageKey();

		$possibleObjectName = '@package\@subpackage\Controller\@controllerController';
		$possibleObjectName = str_replace('@package', str_replace('.', '\\', $packageKey), $possibleObjectName);
		$possibleObjectName = str_replace('@subpackage', $subPackageKey, $possibleObjectName);
		$possibleObjectName = str_replace('@controller', $controllerName, $possibleObjectName);
		$possibleObjectName = str_replace('\\\\', '\\', $possibleObjectName);

		$controllerObjectName = $this->objectManager->getCaseSensitiveObjectName($possibleObjectName);
		return ($controllerObjectName !== FALSE) ? $controllerObjectName : '';
	}

	/**
	 * This method generates the Uri through the joinPoint with
	 * temporary overriding the used node
	 *
	 * @param ActionRequest $request
	 * @param JoinPointInterface $joinPoint The current join point
	 * @param NodeInterface $node
	 * @return string $uri
	 */
	public function generateUriForNode(ActionRequest $request, JoinPointInterface $joinPoint, NodeInterface $node) {
		// store original node path to restore it after generating the uri
		$originalNodePath = $request->getMainRequest()->getArgument('node');

		// generate the uri for the given node
		$request->getMainRequest()->setArgument('node', $node->getContextPath());
		$result = $joinPoint->getAdviceChain()->proceed($joinPoint);

		// restore the original node path
		$request->getMainRequest()->setArgument('node', $originalNodePath);

		return $result;
	}
}
namespace TYPO3\Neos\Aspects;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * 
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 * @\TYPO3\Flow\Annotations\Aspect
 */
class PluginUriAspect extends PluginUriAspect_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\Neos\Aspects\PluginUriAspect') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Aspects\PluginUriAspect', $this);
		if ('TYPO3\Neos\Aspects\PluginUriAspect' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\Neos\Aspects\PluginUriAspect') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Aspects\PluginUriAspect', $this);

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
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\Aspects\PluginUriAspect');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\Aspects\PluginUriAspect', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\Aspects\PluginUriAspect', $propertyName, 'var');
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
		$pluginService_reference = &$this->pluginService;
		$this->pluginService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Neos\Service\PluginService');
		if ($this->pluginService === NULL) {
			$this->pluginService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('532382c725d032803dbc49ab78bcf0d8', $pluginService_reference);
			if ($this->pluginService === NULL) {
				$this->pluginService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('532382c725d032803dbc49ab78bcf0d8',  $pluginService_reference, 'TYPO3\Neos\Service\PluginService', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Neos\Service\PluginService'); });
			}
		}
$this->Flow_Injected_Properties = array (
  0 => 'objectManager',
  1 => 'pluginService',
);
	}
}
#