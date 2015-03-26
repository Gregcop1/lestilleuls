<?php 
namespace TYPO3\Neos\Service;

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
use TYPO3\Flow\Http\Uri;
use TYPO3\Flow\Log\SystemLoggerInterface;
use TYPO3\Flow\Mvc\ActionRequest;
use TYPO3\Flow\Mvc\Controller\ControllerContext;
use TYPO3\Flow\Property\PropertyMapper;
use TYPO3\Flow\Resource\Publishing\ResourcePublisher;
use TYPO3\Media\Domain\Model\AssetInterface;
use TYPO3\Media\Domain\Repository\AssetRepository;
use TYPO3\Neos\Domain\Service\ContentContext;
use TYPO3\Neos\Domain\Service\NodeShortcutResolver;
use TYPO3\Neos\Exception as NeosException;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

/**
 * A service for creating URIs pointing to nodes and assets.
 *
 * The target node can be provided as string or as a Node object; if not specified
 * at all, the generated URI will refer to the current document node inside the TypoScript context.
 *
 * When specifying the ``node`` argument as string, the following conventions apply:
 *
 * *``node`` starts with ``/``:*
 * The given path is an absolute node path and is treated as such.
 * Example: ``/sites/acmecom/home/about/us``
 *
 * *``node`` does not start with ``/``:*
 * The given path is treated as a path relative to the current node.
 * Examples: given that the current node is ``/sites/acmecom/products/``,
 * ``stapler`` results in ``/sites/acmecom/products/stapler``,
 * ``../about`` results in ``/sites/acmecom/about/``,
 * ``./neos/info`` results in ``/sites/acmecom/products/neos/info``.
 *
 * *``node`` starts with a tilde character (``~``):*
 * The given path is treated as a path relative to the current site node.
 * Example: given that the current node is ``/sites/acmecom/products/``,
 * ``~/about/us`` results in ``/sites/acmecom/about/us``,
 * ``~`` results in ``/sites/acmecom``.
 *
 * @Flow\Scope("singleton")
 */
class LinkingService_Original {

	/**
	 * Pattern to match supported URIs.
	 *
	 * @var string
	 */
	const PATTERN_SUPPORTED_URIS = '/(node|asset):\/\/(([a-f0-9]){8}-([a-f0-9]){4}-([a-f0-9]){4}-([a-f0-9]){4}-([a-f0-9]){12})/';

	/**
	 * @Flow\Inject
	 * @var AssetRepository
	 */
	protected $assetRepository;

	/**
	 * @Flow\Inject
	 * @var ResourcePublisher
	 */
	protected $resourcePublisher;

	/**
	 * @Flow\Inject
	 * @var NodeShortcutResolver
	 */
	protected $nodeShortcutResolver;

	/**
	 * @Flow\Inject
	 * @var PropertyMapper
	 */
	protected $propertyMapper;

	/**
	 * @var NodeInterface
	 */
	protected $lastLinkedNode;

	/**
	 * @Flow\Inject
	 * @var SystemLoggerInterface
	 */
	protected $systemLogger;

	/**
	 * @param string|Uri $uri
	 * @return boolean
	 */
	public function hasSupportedScheme($uri) {
		if ($uri instanceof Uri) {
			$uri = (string)$uri;
		}
		return preg_match(self::PATTERN_SUPPORTED_URIS, $uri) === 1;
	}

	/**
	 * @param string|Uri $uri
	 * @return string
	 */
	public function getScheme($uri) {
		if ($uri instanceof Uri) {
			return $uri->getScheme();
		}

		if (preg_match(self::PATTERN_SUPPORTED_URIS, $uri, $matches) === 1) {
			return $matches[1];
		}

		return '';
	}

	/**
	 * Resolves a given node:// URI to a "normal" HTTP(S) URI for the addressed node.
	 *
	 * @param string|Uri $uri
	 * @param NodeInterface $contextNode
	 * @param ControllerContext $controllerContext
	 * @return string
	 */
	public function resolveNodeUri($uri, NodeInterface $contextNode, ControllerContext $controllerContext) {
		$targetObject = $this->convertUriToObject($uri, $contextNode);
		if ($targetObject === NULL) {
			$this->systemLogger->log(sprintf('Could not resolve "%s" to an existing node; The node was probably deleted.', $uri));
			return NULL;
		}
		return $this->createNodeUri($controllerContext, $targetObject);
	}

	/**
	 * Resolves a given asset:// URI to a "normal" HTTP(S) URI for the addressed asset's resource.
	 *
	 * @param string|Uri $uri
	 * @return string
	 */
	public function resolveAssetUri($uri) {
		$targetObject = $this->convertUriToObject($uri);
		if ($targetObject === NULL) {
			$this->systemLogger->log(sprintf('Could not resolve "%s" to an existing asset; The asset was probably deleted.', $uri));
			return NULL;
		}
		return $this->resourcePublisher->getPersistentResourceWebUri($targetObject->getResource());
	}

	/**
	 * Return the object the URI addresses or NULL.
	 *
	 * @param string|Uri $uri
	 * @param NodeInterface $contextNode
	 * @return NodeInterface|AssetInterface|NULL
	 */
	public function convertUriToObject($uri, NodeInterface $contextNode = NULL) {
		if ($uri instanceof Uri) {
			$uri = (string)$uri;
		}

		if (preg_match(self::PATTERN_SUPPORTED_URIS, $uri, $matches) === 1) {
			switch ($matches[1]) {
				case 'node':
					if ($contextNode === NULL) {
						throw new \RuntimeException('node:// URI conversion requires a context node to be passed', 1409734235);
					};

					return $contextNode->getContext()->getNodeByIdentifier($matches[2]);
				case 'asset':
					return $this->assetRepository->findByIdentifier($matches[2]);
			}
		}

		return NULL;
	}

	/**
	 * Renders the URI to a given node instance or -path.
	 *
	 * @param ControllerContext $controllerContext
	 * @param mixed $node A node object or a string node path, if a relative path is provided the baseNode argument is required
	 * @param NodeInterface $baseNode
	 * @param string $format Format to use for the URL, for example "html" or "json"
	 * @param boolean $absolute If set, an absolute URI is rendered
	 * @param array $arguments Additional arguments to be passed to the UriBuilder (for example pagination parameters)
	 * @param string $section
	 * @param boolean $addQueryString If set, the current query parameters will be kept in the URI
	 * @param array $argumentsToBeExcludedFromQueryString arguments to be removed from the URI. Only active if $addQueryString = TRUE
	 * @param boolean $resolveShortcuts INTERNAL Parameter - if FALSE, shortcuts are not redirected to their target. Only needed on rare backend occasions when we want to link to the shortcut itself.
	 * @return string The rendered URI
	 * @throws \InvalidArgumentException if the given node/baseNode is not valid
	 * @throws NeosException if no URI could be resolved for the given node
	 */
	public function createNodeUri(ControllerContext $controllerContext, $node = NULL, NodeInterface $baseNode = NULL, $format = NULL, $absolute = FALSE, array $arguments = array(), $section = '', $addQueryString = FALSE, array $argumentsToBeExcludedFromQueryString = array(), $resolveShortcuts = TRUE) {
		$this->lastLinkedNode = NULL;
		if (!($node instanceof NodeInterface || is_string($node) || $baseNode instanceof NodeInterface)) {
			throw new \InvalidArgumentException('Expected an instance of NodeInterface or a string for the node argument, or alternatively a baseNode argument.', 1373101025);
		}

		if (is_string($node)) {
			$nodeString = $node;
			if ($nodeString === '') {
				throw new NeosException(sprintf('Empty strings can not be resolved to nodes.', $nodeString), 1415709942);
			}
			preg_match(NodeInterface::MATCH_PATTERN_CONTEXTPATH, $nodeString, $matches);
			if (isset($matches['WorkspaceName']) && $matches['WorkspaceName'] !== '') {
				$node = $this->propertyMapper->convert($nodeString, 'TYPO3\TYPO3CR\Domain\Model\NodeInterface');
			} else {
				if ($baseNode === NULL) {
					throw new NeosException('The baseNode argument is required for linking to nodes with a relative path.', 1407879905);
				}
				/** @var ContentContext $contentContext */
				$contentContext = $baseNode->getContext();
				if ($nodeString === '~' || $nodeString === '~/') {
					$node = $contentContext->getCurrentSiteNode();
				} elseif (substr($nodeString, 0, 2) === '~/') {
					$node = $contentContext->getCurrentSiteNode()->getNode(substr($nodeString, 2));
				} else {
					if (substr($nodeString, 0, 1) === '/') {
						$node = $contentContext->getNode($nodeString);
					} else {
						$node = $baseNode->getNode($nodeString);
					}
				}
			}
			if (!$node instanceof NodeInterface) {
				throw new NeosException(sprintf('The string "%s" could not be resolved to an existing node.', $nodeString), 1415709674);
			}
		} elseif (!$node instanceof NodeInterface) {
			$node = $baseNode;
		}

		if (!$node instanceof NodeInterface) {
			throw new NeosException(sprintf('Node must be an instance of NodeInterface or string, given "%s".', gettype($node)), 1414772029);
		}
		$this->lastLinkedNode = $node;

		if ($resolveShortcuts === TRUE) {
			$resolvedNode = $this->nodeShortcutResolver->resolveShortcutTarget($node);
		} else {
			// this case is only relevant in extremely rare occasions in the Neos Backend, when we want to generate
			// a link towards the *shortcut itself*, and not to its target.
			$resolvedNode = $node;
		}

		if (is_string($resolvedNode)) {
			return $resolvedNode;
		}
		if (!$resolvedNode instanceof NodeInterface) {
			throw new NeosException(sprintf('Could not resolve shortcut target for node "%s"', $node->getPath()), 1414771137);
		}

		/** @var ActionRequest $request */
		$request = $controllerContext->getRequest()->getMainRequest();

		$uriBuilder = clone $controllerContext->getUriBuilder();
		$uriBuilder->setRequest($request);
		$uri = $uriBuilder
			->reset()
			->setSection($section)
			->setCreateAbsoluteUri($absolute)
			->setArguments($arguments)
			->setAddQueryString($addQueryString)
			->setArgumentsToBeExcludedFromQueryString($argumentsToBeExcludedFromQueryString)
			->setFormat($format ?: $request->getFormat())
			->uriFor('show', array('node' => $resolvedNode), 'Frontend\Node', 'TYPO3.Neos');

		return $uri;
	}

	/**
	 * Returns the node that was last used to resolve a link to.
	 * May return NULL in case no link has been generated or an error occurred on the last linking run.
	 *
	 * @return NodeInterface
	 */
	public function getLastLinkedNode() {
		return $this->lastLinkedNode;
	}

}
namespace TYPO3\Neos\Service;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * A service for creating URIs pointing to nodes and assets.
 * 
 * The target node can be provided as string or as a Node object; if not specified
 * at all, the generated URI will refer to the current document node inside the TypoScript context.
 * 
 * When specifying the ``node`` argument as string, the following conventions apply:
 * 
 * *``node`` starts with ``/``:*
 * The given path is an absolute node path and is treated as such.
 * Example: ``/sites/acmecom/home/about/us``
 * 
 * *``node`` does not start with ``/``:*
 * The given path is treated as a path relative to the current node.
 * Examples: given that the current node is ``/sites/acmecom/products/``,
 * ``stapler`` results in ``/sites/acmecom/products/stapler``,
 * ``../about`` results in ``/sites/acmecom/about/``,
 * ``./neos/info`` results in ``/sites/acmecom/products/neos/info``.
 * 
 * *``node`` starts with a tilde character (``~``):*
 * The given path is treated as a path relative to the current site node.
 * Example: given that the current node is ``/sites/acmecom/products/``,
 * ``~/about/us`` results in ``/sites/acmecom/about/us``,
 * ``~`` results in ``/sites/acmecom``.
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class LinkingService extends LinkingService_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\Neos\Service\LinkingService') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Service\LinkingService', $this);
		if ('TYPO3\Neos\Service\LinkingService' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\Neos\Service\LinkingService') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Service\LinkingService', $this);

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
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\Service\LinkingService');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\Service\LinkingService', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\Service\LinkingService', $propertyName, 'var');
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
		$assetRepository_reference = &$this->assetRepository;
		$this->assetRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Media\Domain\Repository\AssetRepository');
		if ($this->assetRepository === NULL) {
			$this->assetRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('f32c311dcec701178d68823855159b62', $assetRepository_reference);
			if ($this->assetRepository === NULL) {
				$this->assetRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('f32c311dcec701178d68823855159b62',  $assetRepository_reference, 'TYPO3\Media\Domain\Repository\AssetRepository', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Media\Domain\Repository\AssetRepository'); });
			}
		}
		$resourcePublisher_reference = &$this->resourcePublisher;
		$this->resourcePublisher = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Resource\Publishing\ResourcePublisher');
		if ($this->resourcePublisher === NULL) {
			$this->resourcePublisher = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('666dcb29134e5c4063bc71f63e10ab36', $resourcePublisher_reference);
			if ($this->resourcePublisher === NULL) {
				$this->resourcePublisher = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('666dcb29134e5c4063bc71f63e10ab36',  $resourcePublisher_reference, 'TYPO3\Flow\Resource\Publishing\ResourcePublisher', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Resource\Publishing\ResourcePublisher'); });
			}
		}
		$nodeShortcutResolver_reference = &$this->nodeShortcutResolver;
		$this->nodeShortcutResolver = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Neos\Domain\Service\NodeShortcutResolver');
		if ($this->nodeShortcutResolver === NULL) {
			$this->nodeShortcutResolver = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('9f9682798c61802b3965dc4930488242', $nodeShortcutResolver_reference);
			if ($this->nodeShortcutResolver === NULL) {
				$this->nodeShortcutResolver = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('9f9682798c61802b3965dc4930488242',  $nodeShortcutResolver_reference, 'TYPO3\Neos\Domain\Service\NodeShortcutResolver', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Neos\Domain\Service\NodeShortcutResolver'); });
			}
		}
		$propertyMapper_reference = &$this->propertyMapper;
		$this->propertyMapper = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Property\PropertyMapper');
		if ($this->propertyMapper === NULL) {
			$this->propertyMapper = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('d727d5722bb68256b2c0c712d1adda00', $propertyMapper_reference);
			if ($this->propertyMapper === NULL) {
				$this->propertyMapper = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('d727d5722bb68256b2c0c712d1adda00',  $propertyMapper_reference, 'TYPO3\Flow\Property\PropertyMapper', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Property\PropertyMapper'); });
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
  0 => 'assetRepository',
  1 => 'resourcePublisher',
  2 => 'nodeShortcutResolver',
  3 => 'propertyMapper',
  4 => 'systemLogger',
);
	}
}
#