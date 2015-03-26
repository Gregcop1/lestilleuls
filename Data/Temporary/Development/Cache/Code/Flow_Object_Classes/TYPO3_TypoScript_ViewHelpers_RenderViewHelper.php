<?php 
namespace TYPO3\TypoScript\ViewHelpers;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TypoScript".            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\TypoScript\TypoScriptObjects\AbstractTypoScriptObject;
use TYPO3\TypoScript\View\TypoScriptView;

/**
 * Render a TypoScript object with a relative TypoScript path, optionally
 * pushing new variables onto the TypoScript context.
 *
 * = Examples =
 *
 * <code title="Simple">
 * TypoScript:
 * some.given {
 * 	path = TYPO3.TypoScript:Template
 * 	…
 * }
 * ViewHelper:
 * <ts:render path="some.given.path" />
 * </code>
 * <output>
 * (the evaluated TypoScript, depending on the given path)
 * </output>
 *
 * <code title="TypoScript from a foreign package">
 * <ts:render path="some.given.path" typoScriptPackageKey="Acme.Bookstore" />
 * </code>
 * <output>
 * (the evaluated TypoScript, depending on the given path)
 * </output>
 */
class RenderViewHelper_Original extends AbstractViewHelper {

	/**
	 * @var TypoScriptView
	 */
	protected $typoScriptView;

	/**
	 * Initialize the arguments.
	 *
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerArgument('typoScriptFilePathPattern', 'string', 'Resource pattern to load TypoScript from. Defaults to: resource://@package/Private/TypoScript/', FALSE);
	}

	/**
	 * Evaluate the TypoScript object at $path and return the rendered result.
	 *
	 * @param string $path Relative TypoScript path to be rendered
	 * @param array $context Additional context variables to be set.
	 * @param string $typoScriptPackageKey The key of the package to load TypoScript from, if not from the current context.
	 * @return string
	 * @throws \InvalidArgumentException
	 */
	public function render($path, array $context = NULL, $typoScriptPackageKey = NULL) {
		if (strpos($path, '/') === 0 || strpos($path, '.') === 0) {
			throw new \InvalidArgumentException('When calling the TypoScript render view helper only relative paths are allowed.', 1368740480);
		}
		if (preg_match('/^[a-z0-9.]+$/i', $path) !== 1) {
			throw new \InvalidArgumentException('Invalid path given to the TypoScript render view helper ', 1368740484);
		}

		$slashSeparatedPath = str_replace('.', '/', $path);

		if ($typoScriptPackageKey === NULL) {
			/** @var $typoScriptObject AbstractTypoScriptObject */
			$typoScriptObject = $this->viewHelperVariableContainer->getView()->getTypoScriptObject();
			if ($context !== NULL) {
				$currentContext = $typoScriptObject->getTsRuntime()->getCurrentContext();
				foreach ($context as $key => $value) {
					$currentContext[$key] = $value;
				}
				$typoScriptObject->getTsRuntime()->pushContextArray($currentContext);
			}
			$absolutePath = $typoScriptObject->getPath() . '/' . $slashSeparatedPath;

			$output = $typoScriptObject->getTsRuntime()->render($absolutePath);

			if ($context !== NULL) {
				$typoScriptObject->getTsRuntime()->popContext();
			}
		} else {
			$this->initializeTypoScriptView();
			$this->typoScriptView->setPackageKey($typoScriptPackageKey);
			$this->typoScriptView->setTypoScriptPath($slashSeparatedPath);
			if ($context !== NULL) {
				$this->typoScriptView->assignMultiple($context);
			}

			$output = $this->typoScriptView->render();
		}

		return $output;
	}

	/**
	 * Initialize the TypoScript View
	 *
	 * @return void
	 */
	protected function initializeTypoScriptView() {
		$this->typoScriptView = new TypoScriptView();
		$this->typoScriptView->setControllerContext($this->controllerContext);
		$this->typoScriptView->disableFallbackView();
		if ($this->hasArgument('typoScriptFilePathPattern')) {
			$this->typoScriptView->setTypoScriptPathPattern($this->arguments['typoScriptFilePathPattern']);
		}
	}
}
namespace TYPO3\TypoScript\ViewHelpers;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * Render a TypoScript object with a relative TypoScript path, optionally
 * pushing new variables onto the TypoScript context.
 * 
 * = Examples =
 * 
 * <code title="Simple">
 * TypoScript:
 * some.given {
 * 	path = TYPO3.TypoScript:Template
 * 	…
 * }
 * ViewHelper:
 * <ts:render path="some.given.path" />
 * </code>
 * <output>
 * (the evaluated TypoScript, depending on the given path)
 * </output>
 * 
 * <code title="TypoScript from a foreign package">
 * <ts:render path="some.given.path" typoScriptPackageKey="Acme.Bookstore" />
 * </code>
 * <output>
 * (the evaluated TypoScript, depending on the given path)
 * </output>
 */
class RenderViewHelper extends RenderViewHelper_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if ('TYPO3\TypoScript\ViewHelpers\RenderViewHelper' === get_class($this)) {
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
	$reflectedClass = new \ReflectionClass('TYPO3\TypoScript\ViewHelpers\RenderViewHelper');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\TypoScript\ViewHelpers\RenderViewHelper', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\TypoScript\ViewHelpers\RenderViewHelper', $propertyName, 'var');
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
		$this->injectObjectManager(\TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Object\ObjectManagerInterface'));
		$this->injectSystemLogger(\TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Log\SystemLoggerInterface'));
$this->Flow_Injected_Properties = array (
  0 => 'objectManager',
  1 => 'systemLogger',
);
	}
}
#