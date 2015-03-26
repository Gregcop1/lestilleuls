<?php 
namespace TYPO3\TypoScript\View;

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
use TYPO3\Flow\Mvc\View\AbstractView;
use TYPO3\Flow\Utility\Arrays;
use TYPO3\Flow\Utility\Files;
use TYPO3\TypoScript\Core\Runtime;
use TYPO3\TypoScript\Exception\RuntimeException;

/**
 * View for using TypoScript for standard MVC controllers.
 *
 * Recursively loads all TypoScript files from the configured path (By default that's Resources/Private/TypoScripts
 * of the current package) and then checks whether a TypoScript object for current controller and action can be found.
 *
 * If the controller class name is Foo\Bar\Baz\Controller\BlahController and the action is "index",
 * it checks for the TypoScript path Foo.Bar.Baz.BlahController.index.
 * If this path is found, then it is used for rendering. Otherwise, the $fallbackView is used.
 */
class TypoScriptView_Original extends AbstractView {

	/**
	 * This contains the supported options, their default values, descriptions and types.
	 *
	 * @var array
	 */
	protected $supportedOptions = array(
		'typoScriptPathPatterns' => array(array('resource://@package/Private/TypoScript'), 'TypoScript files will be recursively loaded from this paths.', 'array'),
		'typoScriptPath' => array(NULL, 'The TypoScript path which should be rendered; derived from the controller and action names or set by the user.', 'string'),
		'packageKey' => array(NULL, 'The package key where the TypoScript should be loaded from. If not given, is automatically derived from the current request.', 'string'),
		'debugMode' => array(FALSE, 'Flag to enable debug mode of the TypoScript runtime explicitly (overriding the global setting).', 'boolean'),
		'enableContentCache' => array(FALSE, 'Flag to enable content caching inside TypoScript (overriding the global setting).', 'boolean')
	);

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TypoScript\Core\Parser
	 */
	protected $typoScriptParser;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Mvc\View\ViewInterface
	 */
	protected $fallbackView;

	/**
	 * The parsed TypoScript array in its internal representation
	 *
	 * @var array
	 */
	protected $parsedTypoScript;

	/**
	 * Runtime cache of the TypoScript path which should be rendered; derived from the controller
	 * and action names or set by the user.
	 *
	 * @var string
	 */
	protected $typoScriptPath = NULL;

	/**
	 * if FALSE, the fallback view will never be used.
	 *
	 * @var boolean
	 */
	protected $fallbackViewEnabled = TRUE;

	/**
	 * The TypoScript Runtime
	 *
	 * @var Runtime
	 */
	protected $typoScriptRuntime = NULL;

	/**
	 * Reset runtime cache if an option is changed
	 *
	 * @param string $optionName
	 * @param mixed $value
	 * @return void
	 */
	public function setOption($optionName, $value) {
		$this->typoScriptPath = NULL;
		parent::setOption($optionName, $value);
	}

	/**
	 * Sets the TypoScript path to be rendered to an explicit value;
	 * to be used mostly inside tests.
	 *
	 * @param string $typoScriptPath
	 * @return void
	 */
	public function setTypoScriptPath($typoScriptPath) {
		$this->setOption('typoScriptPath', $typoScriptPath);
	}

	/**
	 * The package key where the TypoScript should be loaded from. If not given,
	 * is automatically derived from the current request.
	 *
	 * @param string $packageKey
	 * @return void
	 */
	public function setPackageKey($packageKey) {
		$this->setOption('packageKey', $packageKey);
	}

	/**
	 * @param string $pathPattern
	 * @return void
	 */
	public function setTypoScriptPathPattern($pathPattern) {
		$this->setOption('typoScriptPathPatterns', array($pathPattern));
	}

	/**
	 * @param array $pathPatterns
	 * @return void
	 */
	public function setTypoScriptPathPatterns(array $pathPatterns) {
		$this->setOption('typoScriptPathPatterns', $pathPatterns);
	}

	/**
	 * Disable the use of the Fallback View
	 *
	 * @return void
	 */
	public function disableFallbackView() {
		$this->fallbackViewEnabled = FALSE;
	}

	/**
	 * Re-Enable the use of the Fallback View. By default, it is enabled,
	 * so calling this method only makes sense if disableFallbackView() has
	 * been called before.
	 *
	 * @return void
	 */
	public function enableFallbackView() {
		$this->fallbackViewEnabled = TRUE;
	}

	/**
	 * Render the view
	 *
	 * @return string The rendered view
	 * @api
	 */
	public function render() {
		$this->initializeTypoScriptRuntime();
		if ($this->typoScriptRuntime->canRender($this->getTypoScriptPathForCurrentRequest()) || $this->fallbackViewEnabled === FALSE) {
			return $this->renderTypoScript();
		} else {
			return $this->renderFallbackView();
		}
	}

	/**
	 * Load the TypoScript Files form the defined
	 * paths and construct a Runtime from the
	 * parsed results
	 *
	 * @return void
	 */
	public function initializeTypoScriptRuntime() {
		if ($this->typoScriptRuntime === NULL) {
			$this->loadTypoScript();
			$this->typoScriptRuntime = new Runtime($this->parsedTypoScript, $this->controllerContext);
		}
		if (isset($this->options['debugMode'])) {
			$this->typoScriptRuntime->setDebugMode($this->options['debugMode']);
		}
		if (isset($this->options['enableContentCache'])) {
			$this->typoScriptRuntime->setEnableContentCache($this->options['enableContentCache']);
		}
	}

	/**
	 * Load TypoScript from the directories specified by $this->getOption('typoScriptPathPatterns')
	 *
	 * @return void
	 */
	protected function loadTypoScript() {
		$mergedTypoScriptCode = '';
		$typoScriptPathPatterns = $this->getOption('typoScriptPathPatterns');
		ksort($typoScriptPathPatterns);
		foreach ($typoScriptPathPatterns as $typoScriptPathPattern) {
			$typoScriptPathPattern = str_replace('@package', $this->getPackageKey(), $typoScriptPathPattern);
			$filePaths = Files::readDirectoryRecursively($typoScriptPathPattern, '.ts2');
			sort($filePaths);
			foreach ($filePaths as $filePath) {
				$mergedTypoScriptCode .= PHP_EOL . file_get_contents($filePath) . PHP_EOL;
			}
		}
		$this->parsedTypoScript = $this->typoScriptParser->parse($mergedTypoScriptCode);
	}

	/**
	 * Get the package key to load the TypoScript from. If set, $this->getOption('packageKey') is used.
	 * Otherwise, the current request is taken and the controller package key is extracted
	 * from there.
	 *
	 * @return string the package key to load TypoScript from
	 */
	protected function getPackageKey() {
		$packageKey = $this->getOption('packageKey');
		if ($packageKey !== NULL) {
			return $packageKey;
		} else {
			/** @var $request \TYPO3\Flow\Mvc\ActionRequest */
			$request = $this->controllerContext->getRequest();
			return $request->getControllerPackageKey();
		}
	}

	/**
	 * Determines the TypoScript path depending on the current controller and action
	 *
	 * @return string
	 */
	protected function getTypoScriptPathForCurrentRequest() {
		if ($this->typoScriptPath === NULL) {
			$typoScriptPath = $this->getOption('typoScriptPath');
			if ($typoScriptPath !== NULL) {
				$this->typoScriptPath = $typoScriptPath;
			} else {
				/** @var $request \TYPO3\Flow\Mvc\ActionRequest */
				$request = $this->controllerContext->getRequest();
				$typoScriptPathForCurrentRequest = $request->getControllerObjectName();
				$typoScriptPathForCurrentRequest = str_replace('\\Controller\\', '\\', $typoScriptPathForCurrentRequest);
				$typoScriptPathForCurrentRequest = str_replace('\\', '/', $typoScriptPathForCurrentRequest);
				$typoScriptPathForCurrentRequest = trim($typoScriptPathForCurrentRequest, '/');
				$typoScriptPathForCurrentRequest .= '/' . $request->getControllerActionName();

				$this->typoScriptPath = $typoScriptPathForCurrentRequest;
			}
		}
		return $this->typoScriptPath;
	}

	/**
	 * Determine whether we are able to find TypoScript at the requested position
	 *
	 * @return boolean TRUE if TypoScript exists at the current TypoScript path; FALSE otherwise
	 */
	protected function isTypoScriptFoundForCurrentRequest() {
		return (Arrays::getValueByPath($this->parsedTypoScript, str_replace('/', '.', $this->getTypoScriptPathForCurrentRequest())) !== NULL);
	}

	/**
	 * Render the given TypoScript and return the rendered page
	 *
	 * @return string
	 */
	protected function renderTypoScript() {
		$this->typoScriptRuntime->pushContextArray($this->variables);
		try {
			$output = $this->typoScriptRuntime->render($this->getTypoScriptPathForCurrentRequest());
		} catch (RuntimeException $exception) {
			throw $exception->getPrevious();
		}
		$this->typoScriptRuntime->popContext();
		return $output;
	}

	/**
	 * Initialize and render the fallback view
	 *
	 * @return string
	 */
	public function renderFallbackView() {
		$this->fallbackView->setControllerContext($this->controllerContext);
		$this->fallbackView->assignMultiple($this->variables);
		return $this->fallbackView->render();
	}
}
namespace TYPO3\TypoScript\View;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * View for using TypoScript for standard MVC controllers.
 * 
 * Recursively loads all TypoScript files from the configured path (By default that's Resources/Private/TypoScripts
 * of the current package) and then checks whether a TypoScript object for current controller and action can be found.
 * 
 * If the controller class name is Foo\Bar\Baz\Controller\BlahController and the action is "index",
 * it checks for the TypoScript path Foo.Bar.Baz.BlahController.index.
 * If this path is found, then it is used for rendering. Otherwise, the $fallbackView is used.
 */
class TypoScriptView extends TypoScriptView_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 * @param array $options
	 * @throws \TYPO3\Flow\Mvc\Exception
	 */
	public function __construct() {
		$arguments = func_get_args();

		if (!array_key_exists(0, $arguments)) $arguments[0] = array (
);
		call_user_func_array('parent::__construct', $arguments);
		if ('TYPO3\TypoScript\View\TypoScriptView' === get_class($this)) {
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
	$reflectedClass = new \ReflectionClass('TYPO3\TypoScript\View\TypoScriptView');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\TypoScript\View\TypoScriptView', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\TypoScript\View\TypoScriptView', $propertyName, 'var');
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
		$this->fallbackView = new \TYPO3\Fluid\View\TemplateView(array (
));
		$this->typoScriptParser = new \TYPO3\TypoScript\Core\Parser();
$this->Flow_Injected_Properties = array (
  0 => 'fallbackView',
  1 => 'typoScriptParser',
);
	}
}
#