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
use TYPO3\TYPO3CR\Exception\ExportException;

/**
 * Service for exporting content repository nodes as an XML structure
 *
 * Internally, uses associative arrays instead of Domain Models for performance reasons, so "nodeData" in this
 * class is always an associative array.
 *
 * @Flow\Scope("singleton")
 */
class NodeExportService_Original {

	/**
	 * @var string
	 */
	const SUPPORTED_FORMAT_VERSION = '2.0';

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Log\SystemLoggerInterface
	 */
	protected $systemLogger;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 */
	protected $objectManager;

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
	 * @var \TYPO3\Media\Domain\Repository\ImageRepository
	 */
	protected $imageRepository;

	/**
	 * @var \TYPO3\Media\Domain\Repository\AssetRepository
	 */
	protected $assetRepository;

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
	 * @var \XMLWriter
	 */
	protected $xmlWriter;

	/**
	 * @var array<\Exception> a list of exceptions which happened during export
	 */
	protected $exceptionsDuringExport;

	/**
	 * @var array Node paths that have been exported, this is used for consistency checks of broken node rootlines
	 */
	protected $exportedNodePaths;

	/**
	 * Exports the node data of all nodes in the given sub-tree
	 * by writing them to the given XMLWriter.
	 *
	 * @param string $startingPointNodePath path to the root node of the sub-tree to export. The specified node will not be included, only its sub nodes.
	 * @param string $workspaceName
	 * @param \XMLWriter $xmlWriter
	 * @param boolean $tidy
	 * @param boolean $endDocument
	 * @param string $resourceSavePath
	 * @return \XMLWriter
	 */
	public function export($startingPointNodePath = '/', $workspaceName = 'live', \XMLWriter $xmlWriter = NULL, $tidy = TRUE, $endDocument = TRUE, $resourceSavePath = NULL) {
		$this->propertyMappingConfiguration = new ImportExportPropertyMappingConfiguration($resourceSavePath);
		$this->exceptionsDuringExport = array();
		$this->exportedNodePaths = array();
		if ($startingPointNodePath !== '/') {
			$startingPointParentPath = substr($startingPointNodePath, 0, strrpos($startingPointNodePath, '/'));
			$this->exportedNodePaths[$startingPointParentPath] = TRUE;
		}

		$this->xmlWriter = $xmlWriter;
		if ($this->xmlWriter === NULL) {
			$this->xmlWriter = new \XMLWriter();
			$this->xmlWriter->openMemory();
			$this->xmlWriter->setIndent($tidy);
			$this->xmlWriter->startDocument('1.0', 'UTF-8');
		}

		$nodeDataList = $this->findNodeDataListToExport($startingPointNodePath, $workspaceName);
		$this->exportNodeDataList($nodeDataList);

		if ($endDocument) {
			$this->xmlWriter->endDocument();
		}

		$this->handleExceptionsDuringExport();

		return $this->xmlWriter;
	}

	/**
	 * Find all nodes of the specified workspace lying below the path specified by
	 * (and including) the given starting point.
	 *
	 * @param string $pathStartingPoint Absolute path specifying the starting point
	 * @param string $workspace The containing workspace
	 * @return array an array of node-data in array format.
	 */
	protected function findNodeDataListToExport($pathStartingPoint, $workspace = 'live') {
		/** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
		$queryBuilder = $this->entityManager->createQueryBuilder();
		$queryBuilder->select(
			'n.path AS path,'
			. ' n.identifier AS identifier,'
			. ' n.index AS sortingIndex,'
			. ' n.properties AS properties, '
			. ' n.nodeType as nodeType,'
			. ' n.removed AS removed,'
			. ' n.hidden,'
			. ' n.hiddenBeforeDateTime AS hiddenBeforeDateTime,'
			. ' n.hiddenAfterDateTime AS hiddenAfterDateTime,'
			. ' n.hiddenInIndex AS hiddenInIndex,'
			. ' n.accessRoles AS accessRoles,'
			. ' n.version AS version,'
			. ' n.parentPath AS parentPath,'
			. ' n.pathHash AS pathHash,'
			. ' n.dimensionsHash AS dimensionsHash,'
			. ' n.parentPathHash AS parentPathHash,'
			. ' n.dimensionValues AS dimensionValues,'
			. ' w.name AS workspace'
		)->distinct()
			->from('TYPO3\TYPO3CR\Domain\Model\NodeData', 'n')
			->innerJoin('n.workspace', 'w', 'WITH', 'n.workspace=w.name')
			->where('n.workspace = :workspace')
			->setParameter('workspace', $workspace)
			->andWhere('n.path = :pathPrefix OR n.path LIKE :pathPrefixMatch')
			->setParameter('pathPrefix', $pathStartingPoint)
			->setParameter('pathPrefixMatch', ($pathStartingPoint === '/' ? '%' : $pathStartingPoint . '/%'))
			->orderBy('n.identifier', 'ASC')
			->orderBy('n.path', 'ASC');

		$nodeDataList = $queryBuilder->getQuery()->getResult();
		// Sort nodeDataList by path, replacing "/" with "!" (the first visible ASCII character)
		// because there may be characters like "-" in the node path
		// that would break the sorting order
		usort($nodeDataList,
			function ($node1, $node2) {
				return strcmp(
					str_replace("/", "!", $node1['path']),
					str_replace("/", "!", $node2['path'])
				);
			}
		);
		return $nodeDataList;
	}

	/**
	 * Exports the given Nodes into the XML structure, contained in <nodes> </nodes> tags.
	 *
	 * @param array $nodeDataList The nodes to export
	 * @return void The result is written directly into $this->xmlWriter
	 */
	protected function exportNodeDataList(array &$nodeDataList) {
		$this->xmlWriter->startElement('nodes');
		$this->xmlWriter->writeAttribute('formatVersion', self::SUPPORTED_FORMAT_VERSION);

		$nodesStack = array();
		foreach ($nodeDataList as $nodeData) {
			$this->exportNodeData($nodeData, $nodesStack);
		}

		// Close remaining <node> tags according to the stack:
		while (array_pop($nodesStack)) {
			$this->xmlWriter->endElement();
		}

		$this->xmlWriter->endElement();
	}

	/**
	 * Exports a single Node into the XML structure
	 *
	 * @param array $nodeData The node data as an array
	 * @param array $nodesStack The stack keeping track of open tags, as passed by exportNodeDataList()
	 * @return void The result is written directly into $this->xmlWriter
	 */
	protected function exportNodeData(array &$nodeData, array &$nodesStack) {
		if ($nodeData['path'] !== '/' && !isset($this->exportedNodePaths[$nodeData['parentPath']])) {
			$this->xmlWriter->writeComment(sprintf('Skipped node with identifier "%s" and path "%s" because of a missing parent path. This is caused by a broken rootline and needs to be fixed with the "node:repair" command.', $nodeData['identifier'], $nodeData['path']));
			return;
		}

		$this->exportedNodePaths[$nodeData['path']] = TRUE;

		if ($nodeData['parentPath'] === '/') {
			$nodeName = substr($nodeData['path'], 1);
		} else {
			$nodeName = substr($nodeData['path'], strlen($nodeData['parentPath']) + 1);
		}

		// is this a variant of currently open node?
		// then close all open nodes until parent is currently open and start new node element
		// else reuse the currently open node element and add a new variant element
		// @todo what about nodes with a different path in some dimension
		$parentNode = end($nodesStack);
		if (!$parentNode || $parentNode['path'] !== $nodeData['path'] || $parentNode['identifier'] !== $nodeData['identifier']) {
			while ($parentNode && $nodeData['parentPath'] !== $parentNode['path']) {
				$this->xmlWriter->endElement();
				array_pop($nodesStack);
				$parentNode = end($nodesStack);
			}

			$nodesStack[] = $nodeData;
			$this->xmlWriter->startElement('node');
			$this->xmlWriter->writeAttribute('identifier', $nodeData['identifier']);
			$this->xmlWriter->writeAttribute('nodeName', $nodeName);
		}

		$this->xmlWriter->startElement('variant');

		if ($nodeData['sortingIndex'] !== NULL) {
			// the "/" node has no sorting index by default; so we should only write it if it has been set.
			$this->xmlWriter->writeAttribute('sortingIndex', $nodeData['sortingIndex']);
		}

		foreach(
			array(
				'workspace',
				'nodeType',
				'version',
				'removed',
				'hidden',
				'hiddenInIndex'
			) as $propertyName) {
			$this->xmlWriter->writeAttribute($propertyName, $nodeData[$propertyName]);
		}

		$this->xmlWriter->startElement('dimensions');
		foreach ($nodeData['dimensionValues'] as $dimensionKey => $dimensionValues) {
			foreach ($dimensionValues as $dimensionValue) {
				$this->xmlWriter->writeElement($dimensionKey, $dimensionValue);
			}
		}
		$this->xmlWriter->endElement();

		foreach(
			array(
				'accessRoles',
				'hiddenBeforeDateTime',
				'hiddenAfterDateTime',
				'contentObjectProxy'
			) as $propertyName) {
			$this->writeConvertedElement($nodeData, $propertyName);
		}

		$this->xmlWriter->startElement('properties');
		foreach ($nodeData['properties'] as $propertyName => $propertyValue) {
			$this->writeConvertedElement($nodeData['properties'], $propertyName);
		}
		$this->xmlWriter->endElement(); // "properties"

		$this->xmlWriter->endElement(); // "variant"
	}

	/**
	 * Writes out a single property into the XML structure.
	 *
	 * @param array $data The data as an array, the given property name is looked up there
	 * @param string $propertyName The name of the property
	 * @param string $elementName an optional name to use, defaults to $propertyName
	 * @return void
	 */
	protected function writeConvertedElement(array &$data, $propertyName, $elementName = NULL) {
		if (array_key_exists($propertyName, $data) && $data[$propertyName] !== NULL) {
			$this->xmlWriter->startElement($elementName ?: $propertyName);

			$this->xmlWriter->writeAttribute('__type', gettype($data[$propertyName]));
			try {
				if (is_object($data[$propertyName]) && !$data[$propertyName] instanceof \DateTime) {
					$objectIdentifier = $this->persistenceManager->getIdentifierByObject($data[$propertyName]);
					if ($objectIdentifier !== NULL) {
						$this->xmlWriter->writeAttribute('__identifier', $objectIdentifier);
					}
					$this->xmlWriter->writeAttribute('__classname', get_class($data[$propertyName]));
					$this->xmlWriter->writeAttribute('__encoding', 'json');

					/*
					 * In the site import command we load images and assets and Doctrine
					 * serializes them in when we store the node properties as ObjectArray.
					 *
					 * This serialize removes the resource property without a clear reason
					 * and there's no solution for this issue available yet. THIS IS A WORKAROUND!
					 * @see NEOS-121
					 */
					if ($data[$propertyName] instanceof \TYPO3\Media\Domain\Model\AssetInterface) {
						if ($data[$propertyName]->getResource() === NULL) {
							$this->injectMediaRepositories();
							if ($data[$propertyName] instanceof \TYPO3\Media\Domain\Model\Image) {
								$data[$propertyName] = $this->imageRepository->findByIdentifier($data[$propertyName]->getIdentifier());
							} else {
								$data[$propertyName] = $this->assetRepository->findByIdentifier($data[$propertyName]->getIdentifier());
							}
						}
					}
					$convertedObject = $this->propertyMapper->convert($data[$propertyName], 'array', $this->propertyMappingConfiguration);
					if (!is_array($convertedObject)) {
						if (is_object($convertedObject) && $convertedObject instanceof \TYPO3\Flow\Validation\Error) {
							throw new ExportException($convertedObject->getMessage(), $convertedObject->getCode());
						} else {
							throw new ExportException(sprintf('Conversion of property "%s" which is a "%s" failed, please check if the data is consistent.', $propertyName, get_class($data[$propertyName])));
						}
					}

					$this->xmlWriter->text(json_encode($convertedObject));
				} elseif (is_array($data[$propertyName])) {
					foreach ($data[$propertyName] as $key => $element) {
						$this->writeConvertedElement($data[$propertyName], $key, 'entry' . $key);
					}
				} else {
					if (is_object($data[$propertyName]) && $data[$propertyName] instanceof \DateTime) {
						$this->xmlWriter->writeAttribute('__classname', 'DateTime');
					}
					$this->xmlWriter->text($this->propertyMapper->convert($data[$propertyName], 'string', $this->propertyMappingConfiguration));
				}
			} catch (\Exception $exception) {
				$this->xmlWriter->writeComment(sprintf('Could not convert property "%s" to string.', $propertyName));
				$this->xmlWriter->writeComment($exception->getMessage());
				$this->systemLogger->logException($exception);
				$this->exceptionsDuringExport[] = $exception;
			}

			$this->xmlWriter->endElement();
		}
	}

	/**
	 * Fetch AssetRepository and ImageRepository.
	 *
	 * They are not injected because there must not be a hard dependency to TYPO3.Media.
	 *
	 * @return void
	 */
	protected function injectMediaRepositories() {
		if ($this->imageRepository === NULL) {
			$this->imageRepository = $this->objectManager->get('TYPO3\Media\Domain\Repository\ImageRepository');
		}
		if ($this->assetRepository === NULL) {
			$this->assetRepository = $this->objectManager->get('TYPO3\Media\Domain\Repository\AssetRepository');
		}
	}

	/**
	 * If $this->exceptionsDuringImport is non-empty, build up a new composite exception which contains the individual messages and
	 * re-throw that one.
	 */
	protected function handleExceptionsDuringExport() {
		if (count($this->exceptionsDuringExport) > 0) {
			$exceptionMessages = '';
			foreach ($this->exceptionsDuringExport as $i => $exception) {
				$exceptionMessages .= "\n" . $i . ': ' . get_class($exception) . "\n" . $exception->getMessage() . "\n";
			}

			throw new ExportException(sprintf('%s exceptions occured during export. Please see the log for the full exceptions (including stack traces). The exception messages follow below: %s', count($this->exceptionsDuringExport), $exceptionMessages), 1409057360);
		}
	}
}
namespace TYPO3\TYPO3CR\Domain\Service\ImportExport;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * Service for exporting content repository nodes as an XML structure
 * 
 * Internally, uses associative arrays instead of Domain Models for performance reasons, so "nodeData" in this
 * class is always an associative array.
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class NodeExportService extends NodeExportService_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\TYPO3CR\Domain\Service\ImportExport\NodeExportService') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\TYPO3CR\Domain\Service\ImportExport\NodeExportService', $this);
		if ('TYPO3\TYPO3CR\Domain\Service\ImportExport\NodeExportService' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\TYPO3CR\Domain\Service\ImportExport\NodeExportService') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\TYPO3CR\Domain\Service\ImportExport\NodeExportService', $this);

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
	$reflectedClass = new \ReflectionClass('TYPO3\TYPO3CR\Domain\Service\ImportExport\NodeExportService');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\TYPO3CR\Domain\Service\ImportExport\NodeExportService', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\TYPO3CR\Domain\Service\ImportExport\NodeExportService', $propertyName, 'var');
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
		$objectManager_reference = &$this->objectManager;
		$this->objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Object\ObjectManagerInterface');
		if ($this->objectManager === NULL) {
			$this->objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('0c3c44be7be16f2a287f1fb2d068dde4', $objectManager_reference);
			if ($this->objectManager === NULL) {
				$this->objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('0c3c44be7be16f2a287f1fb2d068dde4',  $objectManager_reference, 'TYPO3\Flow\Object\ObjectManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Object\ObjectManagerInterface'); });
			}
		}
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
  0 => 'systemLogger',
  1 => 'objectManager',
  2 => 'persistenceManager',
  3 => 'propertyMapper',
  4 => 'entityManager',
);
	}
}
#