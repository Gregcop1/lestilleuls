<?php 
namespace TYPO3\Setup\Core;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Setup".           *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Configuration\ConfigurationManager;
use TYPO3\Flow\Http\Component\ComponentContext;
use TYPO3\Flow\Http\Request;
use TYPO3\Flow\Http\RequestHandler as FlowRequestHandler;
use TYPO3\Flow\Http\Response;
use TYPO3\Flow\Error\Message;
use TYPO3\Flow\Http\Uri;
use TYPO3\Flow\Utility\Arrays;
use TYPO3\Flow\Utility\Files;

/**
 * A request handler which can handle HTTP requests.
 *
 * @Flow\Scope("singleton")
 */
class RequestHandler_Original extends FlowRequestHandler {

	/**
	 * This request handler can handle any web request.
	 *
	 * @return boolean If the request is a web request, TRUE otherwise FALSE
	 */
	public function canHandleRequest() {
		return (PHP_SAPI !== 'cli' && ((strlen($_SERVER['REQUEST_URI']) === 6 && $_SERVER['REQUEST_URI'] === '/setup') || in_array(substr($_SERVER['REQUEST_URI'], 0, 7), array('/setup/', '/setup?'))));
	}

	/**
	 * Returns the priority - how eager the handler is to actually handle the
	 * request.
	 *
	 * @return integer The priority of the request handler.
	 */
	public function getPriority() {
		return 200;
	}

	/**
	 * Handles a HTTP request
	 *
	 * @return void
	 */
	public function handleRequest() {
			// Create the request very early so the Resource Management has a chance to grab it:
		$this->request = Request::createFromEnvironment();
		$this->response = new Response();

		$this->checkBasicRequirementsAndDisplayLoadingScreen();

		$this->boot();
		$this->resolveDependencies();
		if (isset($this->settings['http']['baseUri'])) {
			$this->request->setBaseUri(new Uri($this->settings['http']['baseUri']));
		}

		$componentContext = new ComponentContext($this->request, $this->response);
		$this->baseComponentChain->handle($componentContext);

		$this->response->send();

		$this->bootstrap->shutdown('Runtime');
		$this->exit->__invoke();
	}

	/**
	 * Check the basic requirements, and display a loading screen on initial request.
	 *
	 * @return void
	 */
	protected function checkBasicRequirementsAndDisplayLoadingScreen() {
		$messageRenderer = new MessageRenderer($this->bootstrap);
		$basicRequirements = new BasicRequirements();
		$result = $basicRequirements->findError();
		if ($result instanceof \TYPO3\Flow\Error\Error) {
			$messageRenderer->showMessage($result);
		}

		$result = $this->checkAndSetPhpBinaryIfNeeded();
		if ($result instanceof \TYPO3\Flow\Error\Error) {
			$messageRenderer->showMessage($result);
		}

		$currentUri = substr($this->request->getUri(), strlen($this->request->getBaseUri()));
		if ($currentUri === 'setup' || $currentUri === 'setup/') {
			$redirectUri = ($currentUri === 'setup/' ? 'index': 'setup/index');
			$messageRenderer->showMessage(new Message('We are now redirecting you to the setup. <b>This might take 10-60 seconds on the first run,</b> as TYPO3 Flow needs to build up various caches.', NULL, array(), 'Your environment is suited for installing TYPO3 Flow!'), '<meta http-equiv="refresh" content="2;URL=\'' . $redirectUri . '\'">');
		}
	}

	/**
	 * Create a HTTP component chain that adds our own routing configuration component
	 * only for this request handler.
	 *
	 * @return void
	 */
	protected function resolveDependencies() {
		$objectManager = $this->bootstrap->getObjectManager();
		$componentChainFactory = $objectManager->get('TYPO3\Flow\Http\Component\ComponentChainFactory');
		$configurationManager = $objectManager->get('TYPO3\Flow\Configuration\ConfigurationManager');
		$this->settings = $configurationManager->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, 'TYPO3.Flow');
		$setupSettings = $configurationManager->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, 'TYPO3.Setup');
		$httpChainSettings = Arrays::arrayMergeRecursiveOverrule($this->settings['http']['chain'], $setupSettings['http']['chain']);
		$this->baseComponentChain = $componentChainFactory->create($httpChainSettings);
	}

	/**
	 * Checks if the configured PHP binary is executable and the same version as the one
	 * running the current (web server) PHP process. If not or if there is no binary configured,
	 * tries to find the correct one on the PATH.
	 *
	 * Once found, the binary will be written to the configuration, if it is not the default one
	 * (PHP_BINARY or in PHP_BINDIR).
	 *
	 * @return boolean|\TYPO3\Flow\Error\Error TRUE on success, otherwise an instance of \TYPO3\Flow\Error\Error
	 */
	protected function checkAndSetPhpBinaryIfNeeded() {
		$configurationSource = new \TYPO3\Flow\Configuration\Source\YamlSource();
		$distributionSettings = $configurationSource->load(FLOW_PATH_CONFIGURATION . ConfigurationManager::CONFIGURATION_TYPE_SETTINGS);
		if (isset($distributionSettings['TYPO3']['Flow']['core']['phpBinaryPathAndFilename'])) {
			return $this->checkPhpBinary($distributionSettings['TYPO3']['Flow']['core']['phpBinaryPathAndFilename']);
		}
		$phpBinaryPathAndFilename = $this->detectPhpBinaryPathAndFilename();
		if ($phpBinaryPathAndFilename !== NULL) {
			$defaultPhpBinaryPathAndFilename = PHP_BINDIR . '/php';
			if (DIRECTORY_SEPARATOR !== '/') {
				$defaultPhpBinaryPathAndFilename = str_replace('\\', '/', $defaultPhpBinaryPathAndFilename) . '.exe';
			}
			if ($phpBinaryPathAndFilename !== $defaultPhpBinaryPathAndFilename) {
				$distributionSettings = \TYPO3\Flow\Utility\Arrays::setValueByPath($distributionSettings, 'TYPO3.Flow.core.phpBinaryPathAndFilename', $phpBinaryPathAndFilename);
				$configurationSource->save(FLOW_PATH_CONFIGURATION . ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, $distributionSettings);
			}
			return TRUE;
		} else {
			return new \TYPO3\Flow\Error\Error('The path to your PHP (cli) binary could not be detected. Please set it manually in Configuration/Settings.yaml.', 1341499159, array(), 'Environment requirements not fulfilled');
		}
	}

	/**
	 * Checks if the given PHP binary is executable and of the same version as the currently running one.
	 *
	 * @param string $phpBinaryPathAndFilename
	 * @return boolean|\TYPO3\Flow\Error\Error
	 */
	protected function checkPhpBinary($phpBinaryPathAndFilename) {
		$phpVersion = NULL;
		if (file_exists($phpBinaryPathAndFilename) && is_file($phpBinaryPathAndFilename)) {
			if (DIRECTORY_SEPARATOR === '/') {
				$phpCommand = '"' . escapeshellcmd(Files::getUnixStylePath($phpBinaryPathAndFilename)) . '"';
			} else {
				$phpCommand = escapeshellarg(Files::getUnixStylePath($phpBinaryPathAndFilename));
			}

			exec($phpCommand . ' -v', $phpVersionString);
			if (!isset($phpVersionString[0]) || strpos($phpVersionString[0], '(cli)') === FALSE) {
				return new \TYPO3\Flow\Error\Error('The specified path to your PHP binary (see Configuration/Settings.yaml) is incorrect or not a PHP command line (cli) version.', 1341839376, array(), 'Environment requirements not fulfilled');
			}
			$versionStringParts = explode(' ', $phpVersionString[0]);
			if (isset($versionStringParts[1]) && trim($versionStringParts[1]) === PHP_VERSION) {
				return TRUE;
			}
		}
		if ($phpVersion === NULL) {
			return new \TYPO3\Flow\Error\Error('The specified path to your PHP binary (see Configuration/Settings.yaml) is incorrect.', 1341839376, array(), 'Environment requirements not fulfilled');
		} else {
			return new \TYPO3\Flow\Error\Error('The specified path to your PHP binary (see Configuration/Settings.yaml) points to a PHP binary with the version "%s". This is not the same version as is currently running ("%s").', 1341839377, array($phpVersion, PHP_VERSION), 'Environment requirements not fulfilled');
		}
	}

	/**
	 * Traverse the PATH locations and check for the existence of a valid PHP binary.
	 * If found, the path and filename are returned, if not NULL is returned.
	 *
	 * We only use PHP_BINARY if it's set to a file in the path PHP_BINDIR.
	 * This is because PHP_BINARY might, for example, be "/opt/local/sbin/php54-fpm"
	 * while PHP_BINDIR contains "/opt/local/bin" and the actual CLI binary is "/opt/local/bin/php".
	 *
	 * @return string
	 */
	protected function detectPhpBinaryPathAndFilename() {
		if (defined('PHP_BINARY') && PHP_BINARY !== '' && dirname(PHP_BINARY) === PHP_BINDIR) {
			return PHP_BINARY;
		}

		$environmentPaths = explode(PATH_SEPARATOR, getenv('PATH'));
		$environmentPaths[] = PHP_BINDIR;
		foreach ($environmentPaths as $path) {
			$path = rtrim(str_replace('\\', '/', $path), '/');
			if (strlen($path) === 0) {
				continue;
			}
			$phpBinaryPathAndFilename = $path . '/php' . (DIRECTORY_SEPARATOR !== '/' ? '.exe' : '');
			if ($this->checkPhpBinary($phpBinaryPathAndFilename) === TRUE) {
				return $phpBinaryPathAndFilename;
			}
		}
		return NULL;
	}
}
namespace TYPO3\Setup\Core;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * A request handler which can handle HTTP requests.
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class RequestHandler extends RequestHandler_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 * @param Bootstrap $bootstrap
	 */
	public function __construct() {
		$arguments = func_get_args();
		if (get_class($this) === 'TYPO3\Setup\Core\RequestHandler') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Setup\Core\RequestHandler', $this);

		if (!array_key_exists(0, $arguments)) $arguments[0] = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Core\Bootstrap');
		if (!array_key_exists(0, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $bootstrap in class ' . __CLASS__ . '. Please check your calling code and Dependency Injection configuration.', 1296143787);
		call_user_func_array('parent::__construct', $arguments);
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\Setup\Core\RequestHandler') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Setup\Core\RequestHandler', $this);

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
	$reflectedClass = new \ReflectionClass('TYPO3\Setup\Core\RequestHandler');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Setup\Core\RequestHandler', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Setup\Core\RequestHandler', $propertyName, 'var');
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