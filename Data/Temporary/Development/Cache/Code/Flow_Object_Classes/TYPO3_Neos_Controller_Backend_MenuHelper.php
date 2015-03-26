<?php 
namespace TYPO3\Neos\Controller\Backend;

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
use TYPO3\Flow\Mvc\Controller\ControllerContext;

/**
 * A helper class for menu generation in backend controllers / view helpers
 *
 * @Flow\Scope("singleton")
 */
class MenuHelper_Original {

	/**
	 * @var \TYPO3\Neos\Domain\Repository\SiteRepository
	 * @Flow\Inject
	 */
	protected $siteRepository;

	/**
	 * @var \TYPO3\Flow\Security\Authorization\AccessDecisionManagerInterface
	 * @Flow\Inject
	 */
	protected $accessDecisionManager;

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @param array $settings
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * Build a list of sites
	 *
	 * @param \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext
	 * @return array
	 */
	public function buildSiteList(ControllerContext $controllerContext) {
		$requestUri = $controllerContext->getRequest()->getHttpRequest()->getUri();
		$baseUri = $controllerContext->getRequest()->getHttpRequest()->getBaseUri();

		$domainsFound = FALSE;
		$sites = array();
		foreach ($this->siteRepository->findOnline() as $site) {
			$uri = NULL;
			/** @var $site \TYPO3\Neos\Domain\Model\Site */
			if ($site->hasActiveDomains()) {
				$uri = $controllerContext->getUriBuilder()
					->reset()
					->uriFor('index', array(), 'Backend\Backend', 'TYPO3.Neos');
				$uri = sprintf('%s://%s%s%s',
					$requestUri->getScheme(),
					$site->getFirstActiveDomain()->getHostPattern(),
					rtrim($baseUri->getPath(), '/'), // remove trailing slash, $uri has leading slash already
					$uri
				);
				$domainsFound = TRUE;
			}

			$sites[] = array(
				'name' => $site->getName(),
				'nodeName' => $site->getNodeName(),
				'uri' => $uri,
				'active' => parse_url($uri, PHP_URL_HOST) === $requestUri->getHost() ? TRUE : FALSE
			);
		}

		if ($domainsFound === FALSE) {
			$uri = $controllerContext->getUriBuilder()
				->reset()
				->setCreateAbsoluteUri(TRUE)
				->uriFor('index', array(), 'Backend\Backend', 'TYPO3.Neos');
			$sites[0]['uri'] = $uri;
		}

		return $sites;
	}

	/**
	 * @param \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext
	 * @return array
	 */
	public function buildModuleList(ControllerContext $controllerContext) {
		$modules = array();
		foreach ($this->settings['modules'] as $module => $moduleConfiguration) {
			if (isset($moduleConfiguration['resource']) && !$this->accessDecisionManager->hasAccessToResource($moduleConfiguration['resource'])) {
				continue;
			}
			$submodules = array();
			if (isset($moduleConfiguration['submodules'])) {
				foreach ($moduleConfiguration['submodules'] as $submodule => $submoduleConfiguration) {
					if (isset($submoduleConfiguration['resource']) && !$this->accessDecisionManager->hasAccessToResource($submoduleConfiguration['resource'])) {
						continue;
					}
					$submodules[] = $this->collectModuleData($controllerContext, $submodule, $submoduleConfiguration, $module . '/' . $submodule);
				}
			}
			$modules[] = array_merge(
				$this->collectModuleData($controllerContext, $module, $moduleConfiguration, $module),
				array('group' => $module, 'submodules' => $submodules)
			);
		}
		return $modules;
	}

	/**
	 * @param \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext
	 * @param string $module
	 * @param array $moduleConfiguration
	 * @param string $modulePath
	 * @return array
	 */
	protected function collectModuleData(ControllerContext $controllerContext, $module, $moduleConfiguration, $modulePath) {
		$moduleUri = $controllerContext->getUriBuilder()
			->reset()
			->setCreateAbsoluteUri(TRUE)
			->uriFor('index', array('module' => $modulePath), 'Backend\Module', 'TYPO3.Neos');
		return array(
			'module' => $module,
			'modulePath' => $modulePath,
			'uri' => $moduleUri,
			'label' => isset($moduleConfiguration['label']) ? $moduleConfiguration['label'] : '',
			'description' => isset($moduleConfiguration['description']) ? $moduleConfiguration['description'] : '',
			'icon' => isset($moduleConfiguration['icon']) ? $moduleConfiguration['icon'] : '',
			'hideInMenu' => isset($moduleConfiguration['hideInMenu']) ? (boolean)$moduleConfiguration['hideInMenu'] : FALSE
		);
	}
}
namespace TYPO3\Neos\Controller\Backend;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * A helper class for menu generation in backend controllers / view helpers
 * @\TYPO3\Flow\Annotations\Scope("singleton")
 */
class MenuHelper extends MenuHelper_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {
		if (get_class($this) === 'TYPO3\Neos\Controller\Backend\MenuHelper') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Controller\Backend\MenuHelper', $this);
		if ('TYPO3\Neos\Controller\Backend\MenuHelper' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {
		if (get_class($this) === 'TYPO3\Neos\Controller\Backend\MenuHelper') \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->setInstance('TYPO3\Neos\Controller\Backend\MenuHelper', $this);

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
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\Controller\Backend\MenuHelper');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\Controller\Backend\MenuHelper', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\Controller\Backend\MenuHelper', $propertyName, 'var');
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
		$this->injectSettings(\TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Configuration\ConfigurationManager')->getConfiguration(\TYPO3\Flow\Configuration\ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, 'TYPO3.Neos'));
		$siteRepository_reference = &$this->siteRepository;
		$this->siteRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Neos\Domain\Repository\SiteRepository');
		if ($this->siteRepository === NULL) {
			$this->siteRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('5c3f2ab0e14ff0be3090c1f3efe77d7a', $siteRepository_reference);
			if ($this->siteRepository === NULL) {
				$this->siteRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('5c3f2ab0e14ff0be3090c1f3efe77d7a',  $siteRepository_reference, 'TYPO3\Neos\Domain\Repository\SiteRepository', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Neos\Domain\Repository\SiteRepository'); });
			}
		}
		$accessDecisionManager_reference = &$this->accessDecisionManager;
		$this->accessDecisionManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Security\Authorization\AccessDecisionManagerInterface');
		if ($this->accessDecisionManager === NULL) {
			$this->accessDecisionManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('10ee96a39938f84232ff1e0f033d2850', $accessDecisionManager_reference);
			if ($this->accessDecisionManager === NULL) {
				$this->accessDecisionManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('10ee96a39938f84232ff1e0f033d2850',  $accessDecisionManager_reference, 'TYPO3\Flow\Security\Authorization\AccessDecisionVoterManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Security\Authorization\AccessDecisionManagerInterface'); });
			}
		}
$this->Flow_Injected_Properties = array (
  0 => 'settings',
  1 => 'siteRepository',
  2 => 'accessDecisionManager',
);
	}
}
#