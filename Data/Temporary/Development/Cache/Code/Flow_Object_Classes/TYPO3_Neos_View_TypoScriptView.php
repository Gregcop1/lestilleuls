<?php 
namespace TYPO3\Neos\View;

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
use TYPO3\Flow\Http\Response;
use TYPO3\Flow\I18n\Locale;
use TYPO3\Flow\Mvc\View\AbstractView;
use TYPO3\TYPO3CR\Domain\Model\Node;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TypoScript\Core\Runtime;
use TYPO3\TypoScript\Exception\RuntimeException;
use TYPO3\Flow\Security\Context;

/**
 * A TypoScript view for Neos
 */
class TypoScriptView_Original extends AbstractView {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\I18n\Service
	 */
	protected $i18nService;

	/**
	 * This contains the supported options, their default values, descriptions and types.
	 *
	 * @var array
	 */
	protected $supportedOptions = array(
		'enableContentCache' => array(NULL, 'Flag to enable content caching inside TypoScript (overriding the global setting).', 'boolean')
	);

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Neos\Domain\Service\TypoScriptService
	 */
	protected $typoScriptService;

	/**
	 * The TypoScript path to use for rendering the node given in "value", defaults to "page".
	 *
	 * @var string
	 */
	protected $typoScriptPath = 'root';

	/**
	 * @var \TYPO3\TypoScript\Core\Runtime
	 */
	protected $typoScriptRuntime;

	/**
	 * @Flow\Inject
	 * @var Context
	 */
	protected $securityContext;

	/**
	 * Renders the view
	 *
	 * @return string The rendered view
	 * @throws \Exception if no node is given
	 * @api
	 */
	public function render() {
		$currentNode = $this->getCurrentNode();
		$currentSiteNode = $currentNode->getContext()->getCurrentSiteNode();
		$typoScriptRuntime = $this->getTypoScriptRuntime($currentSiteNode);

		$dimensions = $currentNode->getContext()->getDimensions();
		if (array_key_exists('language', $dimensions) && $dimensions['language'] !== array()) {
			$currentLocale = new Locale($dimensions['language'][0]);
			$this->i18nService->getConfiguration()->setCurrentLocale($currentLocale);
			$this->i18nService->getConfiguration()->setFallbackRule(array('strict' => FALSE, 'order' => array_reverse($dimensions['language'])));
		}

		$typoScriptRuntime->pushContextArray(array(
			'node' => $currentNode,
			'documentNode' => $this->getClosestDocumentNode($currentNode),
			'site' => $currentSiteNode,
			'account' => $this->securityContext->canBeInitialized() ? $this->securityContext->getAccount() : NULL,
			'editPreviewMode' => isset($this->variables['editPreviewMode']) ? $this->variables['editPreviewMode'] : NULL
		));
		try {
			$output = $typoScriptRuntime->render($this->typoScriptPath);
			$output = $this->mergeHttpResponseFromOutput($output, $typoScriptRuntime);
		} catch (RuntimeException $exception) {
			throw $exception->getPrevious();
		}
		$typoScriptRuntime->popContext();

		return $output;
	}

	/**
	 * @param string $output
	 * @param Runtime $typoScriptRuntime
	 * @return string The message body without the message head
	 */
	protected function mergeHttpResponseFromOutput($output, Runtime $typoScriptRuntime) {
		if (substr($output, 0, 5) === 'HTTP/') {
			$endOfHeader = strpos($output, "\r\n\r\n");
			if ($endOfHeader !== FALSE) {
				$header = substr($output, 0, $endOfHeader + 4);
				try {
					$renderedResponse = Response::createFromRaw($header);

					/** @var Response $response */
					$response = $typoScriptRuntime->getControllerContext()->getResponse();
					$response->setStatus($renderedResponse->getStatusCode());
					foreach ($renderedResponse->getHeaders()->getAll() as $headerName => $headerValues) {
						$response->setHeader($headerName, $headerValues[0]);
					}

					$output = substr($output, strlen($header));
				} catch (\InvalidArgumentException $exception) {
				}
			}
		}

		return $output;
	}

	/**
	 * Is it possible to render $node with $his->typoScriptPath?
	 *
	 * @return boolean TRUE if $node can be rendered at $typoScriptPath
	 *
	 * @throws \TYPO3\Neos\Exception
	 */
	public function canRenderWithNodeAndPath() {
		$currentNode = $this->getCurrentNode();
		$currentSiteNode = $currentNode->getContext()->getCurrentSiteNode();
		$typoScriptRuntime = $this->getTypoScriptRuntime($currentSiteNode);

		return $typoScriptRuntime->canRender($this->typoScriptPath);
	}

	/**
	 * Set the TypoScript path to use for rendering the node given in "value"
	 *
	 * @param string $typoScriptPath
	 * @return void
	 */
	public function setTypoScriptPath($typoScriptPath) {
		$this->typoScriptPath = $typoScriptPath;
	}

	/**
	 * @return string
	 */
	public function getTypoScriptPath() {
		return $this->typoScriptPath;
	}

	/**
	 * @param NodeInterface $node
	 * @return NodeInterface
	 */
	protected function getClosestDocumentNode(NodeInterface $node) {
		while ($node !== NULL && !$node->getNodeType()->isOfType('TYPO3.Neos:Document')) {
			$node = $node->getParent();
		}
		return $node;
	}

	/**
	 * @return NodeInterface
	 * @throws \TYPO3\Neos\Exception
	 */
	protected function getCurrentNode() {
		$currentNode = isset($this->variables['value']) ? $this->variables['value'] : NULL;
		if (!$currentNode instanceof Node) {
			throw new \TYPO3\Neos\Exception('TypoScriptView needs a variable \'value\' set with a Node object.', 1329736456);
		}
		return $currentNode;
	}

	/**
	 * @param NodeInterface $currentSiteNode
	 * @return \TYPO3\TypoScript\Core\Runtime
	 */
	protected function getTypoScriptRuntime(NodeInterface $currentSiteNode) {
		if ($this->typoScriptRuntime === NULL) {
			$this->typoScriptRuntime = $this->typoScriptService->createRuntime($currentSiteNode, $this->controllerContext);

			if (isset($this->options['enableContentCache']) && $this->options['enableContentCache'] !== NULL) {
				$this->typoScriptRuntime->setEnableContentCache($this->options['enableContentCache']);
			}
		}
		return $this->typoScriptRuntime;
	}

	/**
	 * Clear the cached runtime instance on assignment of variables
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return TypoScriptView
	 */
	public function assign($key, $value) {
		$this->typoScriptRuntime = NULL;
		return parent::assign($key, $value);
	}

}namespace TYPO3\Neos\View;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * A TypoScript view for Neos
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
		if ('TYPO3\Neos\View\TypoScriptView' === get_class($this)) {
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
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\View\TypoScriptView');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\View\TypoScriptView', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\View\TypoScriptView', $propertyName, 'var');
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
		$i18nService_reference = &$this->i18nService;
		$this->i18nService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\I18n\Service');
		if ($this->i18nService === NULL) {
			$this->i18nService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('d147918505b040be63714e111bab34f3', $i18nService_reference);
			if ($this->i18nService === NULL) {
				$this->i18nService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('d147918505b040be63714e111bab34f3',  $i18nService_reference, 'TYPO3\Flow\I18n\Service', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\I18n\Service'); });
			}
		}
		$this->typoScriptService = new \TYPO3\Neos\Domain\Service\TypoScriptService();
		$securityContext_reference = &$this->securityContext;
		$this->securityContext = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Security\Context');
		if ($this->securityContext === NULL) {
			$this->securityContext = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('48836470c14129ade5f39e28c4816673', $securityContext_reference);
			if ($this->securityContext === NULL) {
				$this->securityContext = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('48836470c14129ade5f39e28c4816673',  $securityContext_reference, 'TYPO3\Flow\Security\Context', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Security\Context'); });
			}
		}
$this->Flow_Injected_Properties = array (
  0 => 'i18nService',
  1 => 'typoScriptService',
  2 => 'securityContext',
);
	}
}
#