<?php 
namespace TYPO3\TYPO3CR\Domain\Service\ImportExport;

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
use TYPO3\Flow\Reflection\ObjectAccess;
use TYPO3\Flow\Utility\Algorithms;
use TYPO3\Media\Domain\Model\AssetInterface;
use TYPO3\Media\Domain\Model\ImageVariant;
use TYPO3\TYPO3CR\Domain\Model\NodeData;
use TYPO3\TYPO3CR\Exception\ImportException;

/**
 * Service for importing nodes from an XML structure into the content repository
 *
 * Internally, uses associative arrays instead of Domain Models for performance reasons, so "nodeData" in this
 * class is always an associative array.
 *
 * @Flow\Scope("singleton")
 */
class NodeImportService_Original {

	const SUPPORTED_FORMAT_VERSION = '2.0';

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 */
	protected $persistenceManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Property\PropertyMapper
	 */
	protected $propertyMapper;

	/**
	 * Doctrine's Entity Manager. Note that "ObjectManager" is the name of the related
	 * interface ...
	 *
	 * @Flow\Inject
	 * @var \Doctrine\Common\Persistence\ObjectManager
	 */
	protected $entityManager;

	/**
	 * @var ImportExportPropertyMappingConfiguration
	 */
	protected $propertyMappingConfiguration;

	/**
	 * @var array
	 */
	protected $nodeDataStack = array();

	/**
	 * @var array
	 */
	protected $nodeIdentifierStack = array();

	/**
	 * @var array
	 */
	protected $nodeNameStack;

	/**
	 * the list of property names of NodeData. These are the keys inside the nodeData array which is built as intermediate
	 * representation while parsing the XML.
	 *
	 * For each element, an array of additional settings can be specified; currently the only setting is the following:
	 *
	 * - columnType => \PDO::PARAM_*
	 *
	 * @var array
	 */
	protected $nodeDataPropertyNames = array(
		'Persistence_Object_Identifier' => array(),
		'identifier' => array(),
		'nodeType' => array(),
		'workspace' => array(),
		'sortingIndex' => array(),
		'version' => array(),
		'removed' => array(
			'columnType' => \PDO::PARAM_BOOL
		),
		'hidden' => array(
			'columnType' => \PDO::PARAM_BOOL
		),
		'hiddenInIndex' => array(
			'columnType' => \PDO::PARAM_BOOL
		),
		'path' => array(),
		'pathHash' => array(),
		'parentPath' => array(),
		'parentPathHash' => array(),
		'dimensionsHash' => array(),
		'dimensionValues' => array(),
		'properties' => array(),
		'accessRoles' => array()
	);

	/**
	 * Imports the sub-tree from the xml reader into the given target path.
	 *
	 * The root node of the imported tree becomes a child of the node specified as the target path,
	 * as the following example illustrates:
	 *
	 * 1. Existing Nodes Before Import:
	 *
	 *   path
	 *   - to
	 *   - - my
	 *   - - - targetNode
	 *   - - - - A
	 *   - other
	 *   - - nodes
	 *
	 * 2. Sub-tree in xml to import to 'path/to/my/targetNode':
	 *
	 *   <B>
	 *   - <B1/>
	 *   </B>
	 *
	 * 3. existing nodes after the import:
	 *
	 *   path
	 *   - to
	 *   - - my
	 *   - - - targetNode
	 *   - - - - A
	 *   - - - - B
	 *   - - - - - B1
	 *   - another
	 *   - - sub-tree
	 *
	 * @param \XMLReader $xmlReader The XML input to import - must be either XML as a string or a prepared \XMLReader instance containing XML data
	 * @param string $targetPath path to the node which becomes parent of the root of the imported sub-tree
	 * @param string $resourceLoadPath
	 * @throws \Exception
	 * @return void
	 */
	public function import(\XMLReader $xmlReader, $targetPath, $resourceLoadPath = NULL) {
		$this->propertyMappingConfiguration = new ImportExportPropertyMappingConfiguration($resourceLoadPath);
		$this->nodeNameStack = ($targetPath === '/') ? array() : explode('/', $targetPath);

		$formatVersion = $this->determineFormatVersion($xmlReader);
		switch ($formatVersion) {
			case self::SUPPORTED_FORMAT_VERSION:
				$this->importSubtree($xmlReader);
				break;
			case NULL:
				throw new ImportException('Failed to recognize format of the Node Data XML to import. Please make sure that you use a valid Node Data XML structure.', 1409059346);
			default:
				throw new ImportException('Failed to import Node Data XML: The format with version ' . $formatVersion . ' is not supported, only version ' . self::SUPPORTED_FORMAT_VERSION . ' is supported.', 1409059352);
		}
	}

	/**
	 * Determines the TYPO3CR format version of the given xml
	 *
	 * @param \XMLReader $xmlReader
	 * @return null|string the version as a string or null if the version could not be determined
	 */
	protected function determineFormatVersion(\XMLReader $xmlReader) {
		while ($xmlReader->nodeType !== \XMLReader::ELEMENT || $xmlReader->name !== 'nodes') {
			if (!$xmlReader->read()) {
				break;
			}
		}

		if ($xmlReader->name == 'nodes' && $xmlReader->nodeType == \XMLReader::ELEMENT) {
			return $xmlReader->getAttribute('formatVersion');
		}

		return FALSE;
	}

	/**
	 * Imports the sub-tree from the xml reader into the given target path.
	 * The root node of the imported tree becomes a child of the node specified by target path.
	 *
	 * This parser uses the depth-first reading strategy, which means it will read the input from top til bottom.
	 *
	 * @param \XMLReader $xmlReader A prepared XML Reader with the structure to import
	 * @return void
	 */
	protected function importSubtree(\XMLReader $xmlReader) {
		while ($xmlReader->read()) {
			if ($xmlReader->nodeType === \XMLReader::COMMENT) {
				continue;
			}

			switch ($xmlReader->nodeType) {
				case \XMLReader::ELEMENT:
					if (!$xmlReader->isEmptyElement) {
						$this->parseElement($xmlReader);
					}
					break;
				case \XMLReader::END_ELEMENT:
					if ((string)$xmlReader->name === 'nodes') {
						return; // all done, reached the closing </nodes> tag
					}
					$this->parseEndElement($xmlReader);
					break;
			}
		}
	}

	/**
	 * Parses the given XML element and adds its content to the internal content tree
	 *
	 * @param \XMLReader $xmlReader The XML Reader with the element to be parsed as its root
	 * @return void
	 */
	protected function parseElement(\XMLReader $xmlReader) {
		$elementName = $xmlReader->name;
		switch ($elementName) {
			case 'node':
				// update current node identifier
				$this->nodeIdentifierStack[] = $xmlReader->getAttribute('identifier');
				// update current path
				$nodeName = $xmlReader->getAttribute('nodeName');
				if ($nodeName != '/') {
					$this->nodeNameStack[] = $nodeName;
				}
				break;
			case 'variant':
				$path = $this->getCurrentPath();
				$parentPath = $this->getParentPath($path);

				$currentNodeIdentifier = $this->nodeIdentifierStack[count($this->nodeIdentifierStack) - 1];
				$this->nodeDataStack[] = array(
					'Persistence_Object_Identifier' => Algorithms::generateUUID(),
					'identifier' => $currentNodeIdentifier,
					'nodeType' => $xmlReader->getAttribute('nodeType'),
					'workspace' => $xmlReader->getAttribute('workspace'),
					'sortingIndex' => $xmlReader->getAttribute('sortingIndex'),
					'version' => $xmlReader->getAttribute('version'),
					'removed' => (boolean)$xmlReader->getAttribute('removed'),
					'hidden' => (boolean)$xmlReader->getAttribute('hidden'),
					'hiddenInIndex' => (boolean)$xmlReader->getAttribute('hiddenInIndex'),
					'path' => $path,
					'pathHash' => md5($path),
					'parentPath' => $parentPath,
					'parentPathHash' => md5($parentPath),
					'properties' => array(),
					'accessRoles' => array(),
					'dimensionValues' => array() // is post-processed before save in END_ELEMENT-case
				);
				break;
			case 'dimensions':
				$this->nodeDataStack[count($this->nodeDataStack) - 1]['dimensionValues'] = $this->parseDimensionsElement($xmlReader);
				break;
			case 'properties':
				$this->nodeDataStack[count($this->nodeDataStack) - 1]['properties'] = $this->parsePropertiesElement($xmlReader);
				break;
			case 'accessRoles':
				$this->nodeDataStack[count($this->nodeDataStack) - 1]['accessRoles'] = $this->parseArrayElements($xmlReader, 'accessRoles');
				break;
			default:
				throw new ImportException(sprintf('Unexpected element <%s> ', $elementName), 1423578065);
				break;
		}
	}

	/**
	 * Parses the content of the dimensions-tag and returns the dimensions as an array
	 * 'dimension name' => dimension value
	 *
	 * @param \XMLReader $reader reader positioned just after an opening dimensions-tag
	 * @return array the dimension values
	 */
	protected function parseDimensionsElement(\XMLReader $reader) {
		$dimensions = array();
		$currentDimension = NULL;

		while ($reader->read()) {
			switch ($reader->nodeType) {
				case \XMLReader::ELEMENT:
					$currentDimension = $reader->name;
					break;
				case \XMLReader::END_ELEMENT:
					if ($reader->name == 'dimensions') {
						return $dimensions;
					}
					break;
				case \XMLReader::CDATA:
				case \XMLReader::TEXT:
					$dimensions[$currentDimension][] = $reader->value;
					break;
			}
		}

		return $dimensions;
	}

	/**
	 * Parses the content of exported array and returns the values
	 *
	 * @param \XMLReader $reader reader positioned just after an opening array-tag
	 * @param string $elementName
	 * @return array the array values
	 * @throws \Exception
	 */
	protected function parseArrayElements(\XMLReader $reader, $elementName) {
		$values = array();
		$currentKey = NULL;
		$depth = 0;

		do {
			switch ($reader->nodeType) {
				case \XMLReader::ELEMENT:
					$depth++;
					// __type="object" __identifier="uuid goes here" __classname="TYPO3\Media\Domain\Model\ImageVariant" __encoding="json"
					$currentType = $reader->getAttribute('__type');
					$currentIdentifier = $reader->getAttribute('__identifier');
					$currentClassName = $reader->getAttribute('__classname');
					$currentEncoding = $reader->getAttribute('__encoding');
					break;
				case \XMLReader::END_ELEMENT:
					if ($reader->name == $elementName) {
						return $values;
					}
					break;
				case \XMLReader::CDATA:
				case \XMLReader::TEXT:
					$values[] = $this->convertElementToValue($reader, $currentType, $currentEncoding, $currentClassName, $currentIdentifier);
					break;
			}
		} while  ($reader->read());
	}

	/**
	 * Parses the content of the properties-tag and returns the properties as an array
	 * 'property name' => property value
	 *
	 * @param \XMLReader $reader reader positioned just after an opening properties-tag
	 * @return array the properties
	 * @throws \Exception
	 */
	protected function parsePropertiesElement(\XMLReader $reader) {
		$properties = array();
		$currentProperty = NULL;
		$currentType = NULL;

		while ($reader->read()) {
			switch ($reader->nodeType) {
				case \XMLReader::ELEMENT:
					$currentProperty = $reader->name;
					$currentType = $reader->getAttribute('__type');
					$currentIdentifier = $reader->getAttribute('__identifier');
					$currentClassName = $reader->getAttribute('__classname');
					$currentEncoding = $reader->getAttribute('__encoding');

					if ($reader->isEmptyElement) {
						switch ($currentType) {
							case 'array':
								$properties[$currentProperty] = array();
								break;
							case 'string':
								$properties[$currentProperty] = '';
								break;
							default:
								$properties[$currentProperty] = NULL;
						}
						$currentType = NULL;
					}

					// __type="object" __identifier="uuid goes here" __classname="TYPO3\Media\Domain\Model\ImageVariant" __encoding="json"
					if ($currentType === 'array') {
						$value = $this->parseArrayElements($reader, $currentProperty);
						$properties[$currentProperty] = $value;
					}
					break;
				case \XMLReader::END_ELEMENT:
					if ($reader->name == 'properties') {
						return $properties;
					}
					break;
				case \XMLReader::CDATA:
				case \XMLReader::TEXT:
					$properties[$currentProperty] = $this->convertElementToValue($reader, $currentType, $currentEncoding, $currentClassName, $currentIdentifier);
					break;
			}
		}

		return $properties;
	}

	/**
	 * Convert an element to the value it represents.
	 *
	 * @param \XMLReader $reader
	 * @param string $currentType current element (userland) type
	 * @param string $currentEncoding date encoding of element
	 * @param string $currentClassName class name of element
	 * @param string $currentIdentifier identifier of element
	 * @return mixed
	 */
	protected function convertElementToValue(\XMLReader $reader, $currentType, $currentEncoding, $currentClassName, $currentIdentifier = '') {
		switch ($currentType) {
			case 'object':
				if ($currentClassName === 'DateTime') {
					$value = $this->propertyMapper->convert($reader->value, $currentClassName, $this->propertyMappingConfiguration);
				} elseif ($currentEncoding === 'json') {
					$value = $this->propertyMapper->convert(json_decode($reader->value, TRUE), $currentClassName, $this->propertyMappingConfiguration);
					if ($currentIdentifier !== '') {
						ObjectAccess::setProperty($value, 'Persistence_Object_Identifier', $currentIdentifier, TRUE);
					}
					$this->persistObjects($value);
				} else {
					throw new \Exception(sprintf('Unsupported encoding "%s"', $currentEncoding), 1404397061);
				}
				break;
			case 'string':
				$value = $reader->value;
				break;
			default:
				$value = $this->propertyMapper->convert($reader->value, $currentType, $this->propertyMappingConfiguration);

				return $value;
		}

		return $value;
	}

	/**
	 * This takes care of persisting the "embedded" objects in an ImageVariant
	 * not really nice, will hopefully go away with a rewritten resource handling.
	 *
	 * @param object $value
	 * @return void
	 */
	protected function persistObjects($value) {
		if ($value instanceof AssetInterface) {
			if ($value instanceof ImageVariant) {
				$value = $value->getOriginalImage();
			}

			$existingResource = $this->persistenceManager->getObjectByIdentifier($this->persistenceManager->getIdentifierByObject($value->getResource()), 'TYPO3\Flow\Resource\Resource');
			if ($existingResource === NULL || $this->persistenceManager->isNewObject($existingResource)) {
				$this->persistenceManager->add($value->getResource());
			}

			$existingAsset = $this->persistenceManager->getObjectByIdentifier($this->persistenceManager->getIdentifierByObject($value), 'TYPO3\Media\Domain\Model\Asset');
			if ($existingAsset === NULL || $this->persistenceManager->isNewObject($existingAsset)) {
				$this->persistenceManager->add($value);
			}
		}
	}

	/**
	 * Parses the closing tags writes data to the database then
	 *
	 * @param \XMLReader $reader
	 * @return void
	 */
	protected function parseEndElement(\XMLReader $reader) {
		switch ($reader->name) {
			case 'accessRoles':
				break;
			case 'node':
				// update current path
				array_pop($this->nodeNameStack);
				// update current node identifier
				array_pop($this->nodeIdentifierStack);
				break;
			case 'variant':
				// we have collected all data for the node so we save it
				$nodeData = array_pop($this->nodeDataStack);

				// if XML files lack the identifier for a node, add it here
				if (!isset($nodeData['identifier'])) {
					$nodeData['identifier'] = Algorithms::generateUUID();
				}

				$this->persistNodeData($nodeData);
				break;
			default:
				throw new ImportException(sprintf('Unexpected end element <%s> ', $reader->name), 1423578066);
				break;
		}
	}

	/**
	 * provides the path for a NodeData according to the current stacks
	 *
	 * @return string
	 */
	protected function getCurrentPath() {
		$path = join('/', $this->nodeNameStack);
		if ($path == '') {
			$path = '/';

			return $path;
		}

		return $path;
	}

	/**
	 * provides the parent of the given path
	 *
	 * @param string $path path to get parent for
	 * @return string parent path
	 */
	protected function getParentPath($path) {
		if ($path == '/') {
			return '';
		}
		if ($path != '/') {
			$endIndex = strrpos($path, '/');
			$index = strpos($path, '/');
			// path is something like /nodeInRootSpace
			if ($index == $endIndex) {
				return '/';
			} else { // node is something like /node/not/in/root/space
				return substr($path, 0, $endIndex);
			}
		}
	}

	/**
	 * Saves the given array as a node data entity without using the ORM.
	 *
	 * If the node data already exists (same dimensions, same identifier, same workspace)
	 * it is replaced.
	 *
	 * @param array $nodeData node data to save as an associative array ( $column_name => $value )
	 * @throws \TYPO3\TYPO3CR\Exception\ImportException
	 * @return void
	 */
	protected function persistNodeData($nodeData) {
		if ($nodeData['workspace'] != 'live') {
			throw new ImportException('Saving NodeData with workspace != "live" using direct SQL not supported yet. Workspace is "' . $nodeData['workspace'] . '".');
		}
		if ($nodeData['path'] === '/') {
			return;
		}

		// cleanup old data
		/** @var \Doctrine\DBAL\Connection $connection */
		$connection = $this->entityManager->getConnection();

		// prepare node dimensions
		$dimensionValues = $nodeData['dimensionValues'];
		$dimensionsHash = NodeData::sortDimensionValueArrayAndReturnDimensionsHash($dimensionValues);

		$objectArrayDataTypeHandler = \TYPO3\Flow\Persistence\Doctrine\DataTypes\ObjectArray::getType(\TYPO3\Flow\Persistence\Doctrine\DataTypes\ObjectArray::OBJECTARRAY);

		// post-process node data
		$nodeData['dimensionsHash'] = $dimensionsHash;
		$nodeData['dimensionValues'] = $objectArrayDataTypeHandler->convertToDatabaseValue($dimensionValues, $connection->getDatabasePlatform());
		$nodeData['properties'] = $objectArrayDataTypeHandler->convertToDatabaseValue($nodeData['properties'], $connection->getDatabasePlatform());
		$nodeData['accessRoles'] = serialize($nodeData['accessRoles']);

		// cleanup old data
		/** @var \Doctrine\DBAL\Connection $connection */
		$connection = $this->entityManager->getConnection();
		$connection->prepare('DELETE FROM typo3_typo3cr_domain_model_nodedimension'
			. ' WHERE nodedata IN ('
			. '   SELECT persistence_object_identifier FROM typo3_typo3cr_domain_model_nodedata'
			. '   WHERE identifier = :identifier'
			. '   AND workspace = :workspace'
			. '   AND dimensionshash = :dimensionsHash'
			. ' )'
		)->execute(array(
			'identifier' => $nodeData['identifier'],
			'workspace' => $nodeData['workspace'],
			'dimensionsHash' => $nodeData['dimensionsHash']
		));

		/** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
		$queryBuilder = $this->entityManager->createQueryBuilder();
		$queryBuilder
			->delete()
			->from('TYPO3\TYPO3CR\Domain\Model\NodeData', 'n')
			->where('n.identifier = :identifier')
			->andWhere('n.dimensionsHash = :dimensionsHash')
			->andWhere('n.workspace = :workspace')
			->setParameter('identifier', $nodeData['identifier'])
			->setParameter('workspace', $nodeData['workspace'])
			->setParameter('dimensionsHash', $nodeData['dimensionsHash']);
		$queryBuilder->getQuery()->execute();

		// insert new data
		// we need to use executeUpdate to execute the INSERT -- else the data types are not taken into account.
		// That's why we build a DQL INSERT statement which is then executed.
		$queryParts = array();
		$queryArguments = array();
		$queryTypes = array();
		foreach ($this->nodeDataPropertyNames as $propertyName => $propertyConfig) {
			$queryParts[$propertyName] = ':' . $propertyName;
			$queryArguments[$propertyName] = $nodeData[$propertyName];
			if (isset($propertyConfig['columnType'])) {
				$queryTypes[$propertyName] = $propertyConfig['columnType'];
			}
		}
		$connection->executeUpdate('INSERT INTO typo3_typo3cr_domain_model_nodedata (' . implode(', ', array_keys($queryParts)) . ') VALUES (' . implode(', ', $queryParts) . ')', $queryArguments, $queryTypes);

		foreach ($dimensionValues as $dimension => $values) {
			foreach ($values as $value) {
				$nodeDimension = array(
					'persistence_object_identifier' => Algorithms::generateUUID(),
					'nodedata' => $nodeData['Persistence_Object_Identifier'],
					'name' => $dimension,
					'value' => $value
				);
				$connection->insert('typo3_typo3cr_domain_model_nodedimension', $nodeDimension);
			}
		}
	}
}
namespace TYPO3\TYPO3CR\Domain\Service\ImportExport;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * Service for importing nodes from an XML structure into the content repository
 * 
 * Internally, uses associative arrays instead of Domain Models for performance reasons, so "nodeData" in this
 * class is always an associative array.
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class NodeImportService extends NodeImportService_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\TYPO3CR\Domain\Service\ImportExport\NodeImportService') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\TYPO3CR\Domain\Service\ImportExport\NodeImportService', $this);
		if ('TYPO3\TYPO3CR\Domain\Service\ImportExport\NodeImportService' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\TYPO3CR\Domain\Service\ImportExport\NodeImportService') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\TYPO3CR\Domain\Service\ImportExport\NodeImportService', $this);

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
	$reflectedClass = new \ReflectionClass('TYPO3\TYPO3CR\Domain\Service\ImportExport\NodeImportService');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\TYPO3CR\Domain\Service\ImportExport\NodeImportService', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\TYPO3CR\Domain\Service\ImportExport\NodeImportService', $propertyName, 'var');
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
		$persistenceManager_reference = &$this->persistenceManager;
		$this->persistenceManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Persistence\PersistenceManagerInterface');
		if ($this->persistenceManager === NULL) {
			$this->persistenceManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('f1bc82ad47156d95485678e33f27c110', $persistenceManager_reference);
			if ($this->persistenceManager === NULL) {
				$this->persistenceManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('f1bc82ad47156d95485678e33f27c110',  $persistenceManager_reference, 'TYPO3\Flow\Persistence\Doctrine\PersistenceManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Persistence\PersistenceManagerInterface'); });
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
		$entityManager_reference = &$this->entityManager;
		$this->entityManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('Doctrine\Common\Persistence\ObjectManager');
		if ($this->entityManager === NULL) {
			$this->entityManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('ea59127cf49656654065ffe160cf78e1', $entityManager_reference);
			if ($this->entityManager === NULL) {
				$this->entityManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('ea59127cf49656654065ffe160cf78e1',  $entityManager_reference, 'Doctrine\Common\Persistence\ObjectManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('Doctrine\Common\Persistence\ObjectManager'); });
			}
		}
$this->Flow_Injected_Properties = array (
  0 => 'persistenceManager',
  1 => 'propertyMapper',
  2 => 'entityManager',
);
	}
}
#