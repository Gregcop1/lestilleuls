<?php 
namespace TYPO3\TYPO3CR\TypeConverter;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.TYPO3CR".         *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Error;
use TYPO3\Flow\Object\ObjectManagerInterface;
use TYPO3\Flow\Property\Exception\TypeConverterException;
use TYPO3\Flow\Property\PropertyMapper;
use TYPO3\Flow\Property\PropertyMappingConfigurationInterface;
use TYPO3\Flow\Property\TypeConverter\AbstractTypeConverter;
use TYPO3\Flow\Reflection\ObjectAccess;
use TYPO3\Flow\Security\Context;
use TYPO3\TYPO3CR\Domain\Factory\NodeFactory;
use TYPO3\TYPO3CR\Domain\Model\NodeType;
use TYPO3\TYPO3CR\Domain\Service\Context as TYPO3CRContext;
use TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface;
use TYPO3\TYPO3CR\Domain\Service\NodeService;
use TYPO3\TYPO3CR\Domain\Service\NodeTypeManager;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Exception\NodeException;

/**
 * An Object Converter for Nodes which can be used for routing (but also for other
 * purposes) as a plugin for the Property Mapper.
 *
 * @Flow\Scope("singleton")
 */
class NodeConverter_Original extends AbstractTypeConverter {

	/**
	 * @var boolean
	 */
	const REMOVED_CONTENT_SHOWN = 1;

	/**
	 * @var array
	 */
	protected $sourceTypes = array('string', 'array');

	/**
	 * @Flow\Inject
	 * @var Context
	 */
	protected $securityContext;

	/**
	 * @Flow\Inject
	 * @var ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @Flow\Inject
	 * @var PropertyMapper
	 */
	protected $propertyMapper;

	/**
	 * @Flow\Inject
	 * @var ContextFactoryInterface
	 */
	protected $contextFactory;

	/**
	 * @Flow\Inject
	 * @var NodeFactory
	 */
	protected $nodeFactory;

	/**
	 * @Flow\Inject
	 * @var NodeTypeManager
	 */
	protected $nodeTypeManager;

	/**
	 * @Flow\Inject
	 * @var NodeService
	 */
	protected $nodeService;

	/**
	 * @var string
	 */
	protected $targetType = 'TYPO3\TYPO3CR\Domain\Model\NodeInterface';

	/**
	 * @var integer
	 */
	protected $priority = 1;

	/**
	 * Converts the specified $source into a Node.
	 *
	 * If $source is a UUID it is expected to refer to the identifier of a NodeData record of the "live" workspace
	 *
	 * Otherwise $source has to be a valid node path:
	 *
	 * The node path must be an absolute context node path and can be specified as a string or as an array item with the
	 * key "__contextNodePath". The latter case is for updating existing nodes.
	 *
	 * This conversion method does not support / allow creation of new nodes because new nodes should be created through
	 * the createNode() method of an existing reference node.
	 *
	 * Also note that the context's "current node" is not affected by this object converter, you will need to set it to
	 * whatever node your "current" node is, if any.
	 *
	 * All elements in the source array which start with two underscores (like __contextNodePath) are specially treated
	 * by this converter.
	 *
	 * All elements in the source array which start with a *single underscore (like _hidden) are *directly* set on the Node
	 * object.
	 *
	 * All other elements, not being prefixed with underscore, are properties of the node.
	 *
	 * @param string|array $source Either a string or array containing the absolute context node path which identifies the node. For example "/sites/mysitecom/homepage/about@user-admin"
	 * @param string $targetType not used
	 * @param array $subProperties not used
	 * @param PropertyMappingConfigurationInterface $configuration
	 * @return mixed An object or \TYPO3\Flow\Error\Error if the input format is not supported or could not be converted for other reasons
	 * @throws NodeException
	 */
	public function convertFrom($source, $targetType = NULL, array $subProperties = array(), PropertyMappingConfigurationInterface $configuration = NULL) {
		if (is_string($source)) {
			$source = array('__contextNodePath' => $source);
		}

		if (!is_array($source) || !isset($source['__contextNodePath'])) {
			return new Error('Could not convert ' . gettype($source) . ' to Node object, a valid absolute context node path as a string or array is expected.', 1302879936);
		}

		preg_match(NodeInterface::MATCH_PATTERN_CONTEXTPATH, $source['__contextNodePath'], $matches);
		if (!isset($matches['NodePath'])) {
			return new Error('Could not convert array to Node object because the node path was invalid.', 1285162903);
		}
		$nodePath = $matches['NodePath'];

		$workspaceName = (isset($matches['WorkspaceName']) && $matches['WorkspaceName'] !== '' ? $matches['WorkspaceName'] : 'live');

		$dimensions = NULL;
		if (isset($matches['Dimensions'])) {
			$dimensions = $this->contextFactory->parseDimensionValueStringToArray($matches['Dimensions']);
		}

		$context = $this->contextFactory->create($this->prepareContextProperties($workspaceName, $configuration, $dimensions));
		$workspace = $context->getWorkspace(FALSE);
		if (!$workspace) {
			return new Error(sprintf('Could not convert the given source to Node object because the workspace "%s" as specified in the context node path does not exist.', $workspaceName), 1383577859);
		}

		$node = $context->getNode($nodePath);
		if (!$node) {
			return new Error(sprintf('Could not convert array to Node object because the node "%s" does not exist.', $nodePath), 1370502328);
		}

		$targetNodeType = NULL;
		if (isset($source['_nodeType'])) {
			$source['_nodeType'] = $this->nodeTypeManager->getNodeType($source['_nodeType']);
			if ($source['_nodeType'] !== $node->getNodeType()) {
				if ($context->getWorkspace()->getName() === 'live') {
					throw new NodeException('Could not convert the node type in live workspace');
				}
				$targetNodeType = $source['_nodeType'];
			}
		}
		$this->setNodeProperties($node, $node->getNodeType(), $source, $context);
		if ($targetNodeType !== NULL) {
			$this->nodeService->setDefaultValues($node, $targetNodeType);
			$this->nodeService->createChildNodes($node, $targetNodeType);
		}

		return $node;
	}

	/**
	 * Iterates through the given $properties setting them on the specified $node using the appropriate TypeConverters.
	 *
	 * @param object $nodeLike
	 * @param NodeType $nodeType
	 * @param array $properties
	 * @param TYPO3CRContext $context
	 * @throws TypeConverterException
	 * @return void
	 */
	protected function setNodeProperties($nodeLike, NodeType $nodeType, array $properties, TYPO3CRContext $context) {
		$nodeTypeProperties = $nodeType->getProperties();
		foreach ($properties as $nodePropertyName => $nodePropertyValue) {
			if (substr($nodePropertyName, 0, 2) === '__') {
				continue;
			}
			$nodePropertyType = isset($nodeTypeProperties[$nodePropertyName]['type']) ? $nodeTypeProperties[$nodePropertyName]['type'] : NULL;
			switch ($nodePropertyType) {
				case 'reference':
					$nodePropertyValue = $context->getNodeByIdentifier($nodePropertyValue);
				break;
				case 'references':
					$nodeIdentifiers = json_decode($nodePropertyValue);
					$nodePropertyValue = array();
					if (is_array($nodeIdentifiers)) {
						foreach ($nodeIdentifiers as $nodeIdentifier) {
							$referencedNode = $context->getNodeByIdentifier($nodeIdentifier);
							if ($referencedNode !== NULL) {
								$nodePropertyValue[] = $referencedNode;
							}
						}
					} else {
						throw new TypeConverterException(sprintf('node type "%s" expects an array of identifiers for its property "%s"', $nodeType->getName(), $nodePropertyName), 1383587419);
					}
				break;
				case 'date':
					if ($nodePropertyValue !== '') {
						$nodePropertyValue = \DateTime::createFromFormat(\DateTime::W3C, $nodePropertyValue);
						$nodePropertyValue->setTimezone(new \DateTimeZone(date_default_timezone_get()));
					} else {
						$nodePropertyValue = NULL;
					}
				break;
				case 'integer':
					$nodePropertyValue = intval($nodePropertyValue);
				break;
				case 'boolean':
					if (is_string($nodePropertyValue)) {
						$nodePropertyValue = $nodePropertyValue === 'true' ? TRUE : FALSE;
					}
				break;
				case 'array':
					$nodePropertyValue = json_decode($nodePropertyValue);
				break;
			}
			if (substr($nodePropertyName, 0, 1) === '_') {
				$nodePropertyName = substr($nodePropertyName, 1);
				ObjectAccess::setProperty($nodeLike, $nodePropertyName, $nodePropertyValue);
				continue;
			}
			if (!isset($nodeTypeProperties[$nodePropertyName])) {
				throw new TypeConverterException(sprintf('Node type "%s" does not have a property "%s" according to the schema', $nodeType->getName(), $nodePropertyName), 1359552744);
			}
			$innerType = $nodePropertyType;
			if ($nodePropertyType !== NULL) {
				try {
					$parsedType = \TYPO3\Flow\Utility\TypeHandling::parseType($nodePropertyType);
					$innerType = $parsedType['elementType'] ?: $parsedType['type'];
				} catch(\TYPO3\Flow\Utility\Exception\InvalidTypeException $exception) {
				}
			}
			if ($this->objectManager->isRegistered($innerType) && $nodePropertyValue !== '') {
				$nodePropertyValue = $this->propertyMapper->convert(json_decode($nodePropertyValue, TRUE), $nodePropertyType);
			}
			$nodeLike->setProperty($nodePropertyName, $nodePropertyValue);
		}
	}

	/**
	 * Prepares the context properties for the nodes based on the given workspace and dimensions
	 *
	 * @param string $workspaceName
	 * @param PropertyMappingConfigurationInterface $configuration
	 * @param array $dimensions
	 * @return array
	 */
	protected function prepareContextProperties($workspaceName, PropertyMappingConfigurationInterface $configuration = NULL, array $dimensions = NULL) {
		$contextProperties = array(
			'workspaceName' => $workspaceName,
			'invisibleContentShown' => FALSE,
			'removedContentShown' => FALSE
		);
		if ($workspaceName !== 'live') {
			$contextProperties['invisibleContentShown'] = TRUE;
			if ($configuration !== NULL && $configuration->getConfigurationValue('TYPO3\TYPO3CR\TypeConverter\NodeConverter', self::REMOVED_CONTENT_SHOWN) === TRUE) {
				$contextProperties['removedContentShown'] = TRUE;
			}
		}

		if ($dimensions !== NULL) {
			$contextProperties['dimensions'] = $dimensions;
		}

		return $contextProperties;
	}
}
namespace TYPO3\TYPO3CR\TypeConverter;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * An Object Converter for Nodes which can be used for routing (but also for other
 * purposes) as a plugin for the Property Mapper.
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class NodeConverter extends NodeConverter_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\TYPO3CR\TypeConverter\NodeConverter') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\TYPO3CR\TypeConverter\NodeConverter', $this);
		if ('TYPO3\TYPO3CR\TypeConverter\NodeConverter' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\TYPO3CR\TypeConverter\NodeConverter') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\TYPO3CR\TypeConverter\NodeConverter', $this);

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
	$reflectedClass = new \ReflectionClass('TYPO3\TYPO3CR\TypeConverter\NodeConverter');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\TYPO3CR\TypeConverter\NodeConverter', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\TYPO3CR\TypeConverter\NodeConverter', $propertyName, 'var');
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
		$securityContext_reference = &$this->securityContext;
		$this->securityContext = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Security\Context');
		if ($this->securityContext === NULL) {
			$this->securityContext = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('48836470c14129ade5f39e28c4816673', $securityContext_reference);
			if ($this->securityContext === NULL) {
				$this->securityContext = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('48836470c14129ade5f39e28c4816673',  $securityContext_reference, 'TYPO3\Flow\Security\Context', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Security\Context'); });
			}
		}
		$objectManager_reference = &$this->objectManager;
		$this->objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Object\ObjectManagerInterface');
		if ($this->objectManager === NULL) {
			$this->objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('0c3c44be7be16f2a287f1fb2d068dde4', $objectManager_reference);
			if ($this->objectManager === NULL) {
				$this->objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('0c3c44be7be16f2a287f1fb2d068dde4',  $objectManager_reference, 'TYPO3\Flow\Object\ObjectManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Object\ObjectManagerInterface'); });
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
		$contextFactory_reference = &$this->contextFactory;
		$this->contextFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface');
		if ($this->contextFactory === NULL) {
			$this->contextFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('6b6e9d36a8365cb0dccb3d849ae9366e', $contextFactory_reference);
			if ($this->contextFactory === NULL) {
				$this->contextFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('6b6e9d36a8365cb0dccb3d849ae9366e',  $contextFactory_reference, 'TYPO3\Neos\Domain\Service\ContentContextFactory', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface'); });
			}
		}
		$nodeFactory_reference = &$this->nodeFactory;
		$this->nodeFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Factory\NodeFactory');
		if ($this->nodeFactory === NULL) {
			$this->nodeFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('bc9bb21d5b30e2ec064f6bb8e860feb4', $nodeFactory_reference);
			if ($this->nodeFactory === NULL) {
				$this->nodeFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('bc9bb21d5b30e2ec064f6bb8e860feb4',  $nodeFactory_reference, 'TYPO3\TYPO3CR\Domain\Factory\NodeFactory', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Factory\NodeFactory'); });
			}
		}
		$nodeTypeManager_reference = &$this->nodeTypeManager;
		$this->nodeTypeManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Service\NodeTypeManager');
		if ($this->nodeTypeManager === NULL) {
			$this->nodeTypeManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('478a517efacb3d47415a96d9caded2e9', $nodeTypeManager_reference);
			if ($this->nodeTypeManager === NULL) {
				$this->nodeTypeManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('478a517efacb3d47415a96d9caded2e9',  $nodeTypeManager_reference, 'TYPO3\TYPO3CR\Domain\Service\NodeTypeManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Service\NodeTypeManager'); });
			}
		}
		$nodeService_reference = &$this->nodeService;
		$this->nodeService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Service\NodeService');
		if ($this->nodeService === NULL) {
			$this->nodeService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('882ea910950cb2841572ab634091e4ee', $nodeService_reference);
			if ($this->nodeService === NULL) {
				$this->nodeService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('882ea910950cb2841572ab634091e4ee',  $nodeService_reference, 'TYPO3\TYPO3CR\Domain\Service\NodeService', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Service\NodeService'); });
			}
		}
$this->Flow_Injected_Properties = array (
  0 => 'securityContext',
  1 => 'objectManager',
  2 => 'propertyMapper',
  3 => 'contextFactory',
  4 => 'nodeFactory',
  5 => 'nodeTypeManager',
  6 => 'nodeService',
);
	}
}
#