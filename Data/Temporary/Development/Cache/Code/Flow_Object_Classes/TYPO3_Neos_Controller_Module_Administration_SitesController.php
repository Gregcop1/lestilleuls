<?php 
namespace TYPO3\Neos\Controller\Module\Administration;

/*                                                                        *
 * This script belongs to the Flow package "TYPO3.Neos".                  *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;
use TYPO3\Flow\Log\SystemLoggerInterface;
use TYPO3\Flow\Package\PackageManagerInterface;
use TYPO3\Flow\Property\PropertyMapper;
use TYPO3\Neos\Controller\Module\AbstractModuleController;
use TYPO3\Neos\Domain\Model\Domain;use TYPO3\Neos\Domain\Model\Site;use TYPO3\Neos\Domain\Repository\DomainRepository;
use TYPO3\Neos\Domain\Repository\SiteRepository;
use TYPO3\Neos\Domain\Service\ContentContext;
use TYPO3\Neos\Domain\Service\SiteImportService;
use TYPO3\TYPO3CR\Domain\Model\Workspace;
use TYPO3\TYPO3CR\Domain\Repository\NodeDataRepository;
use TYPO3\TYPO3CR\Domain\Repository\WorkspaceRepository;
use TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface;

/**
 * The TYPO3 Neos Sites Management module controller
 */
class SitesController_Original extends AbstractModuleController {

	/**
	 * @Flow\Inject
	 * @var DomainRepository
	 */
	protected $domainRepository;

	/**
	 * @Flow\Inject
	 * @var SiteRepository
	 */
	protected $siteRepository;

	/**
	 * @Flow\Inject
	 * @var NodeDataRepository
	 */
	protected $nodeDataRepository;

	/**
	 * @Flow\Inject
	 * @var WorkspaceRepository
	 */
	protected $workspaceRepository;

	/**
	 * @Flow\Inject
	 * @var PackageManagerInterface
	 */
	protected $packageManager;

	/**
	 * @Flow\Inject
	 * @var SiteImportService
	 */
	protected $siteImportService;

	/**
	 * @Flow\Inject
	 * @var PropertyMapper
	 */
	protected $propertyMapper;

	/**
	 * @Flow\Inject
	 * @var SystemLoggerInterface
	 */
	protected $systemLogger;

	/**
	 * @Flow\Inject
	 * @var ContextFactoryInterface
	 */
	protected $contextFactory;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Session\SessionInterface
	 */
	protected $session;

	/**
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('sites', $this->siteRepository->findAll());
	}

	/**
	 * A edit view for a site and its settings.
	 *
	 * @param Site $site Site to view
	 * @Flow\IgnoreValidation("$site")
	 * @return void
	 */
	public function editAction(Site $site) {
		try {
			$sitePackage = $this->packageManager->getPackage($site->getSiteResourcesPackageKey());
		} catch(\Exception $e) {
			$this->addFlashMessage('The site package with key "%s" was not found.', 'Site package not found', Message::SEVERITY_ERROR, array($site->getSiteResourcesPackageKey()));
		}

		$this->view->assignMultiple(array(
			'site' => $site,
			'sitePackageMetaData' => isset($sitePackage) ? $sitePackage->getPackageMetaData() : array(),
			'domains' => $this->domainRepository->findBySite($site)
		));
	}

	/**
	 * Update a site
	 *
	 * @param Site $site A site to update
	 * @param string $newSiteNodeName A new site node name
	 * @return void
	 * @Flow\Validate(argumentName="$site", type="UniqueEntity")
	 * @Flow\Validate(argumentName="$newSiteNodeName", type="NotEmpty")
	 * @Flow\Validate(argumentName="$newSiteNodeName", type="StringLength", options={ "minimum"=1, "maximum"=250 })
	 * @Flow\Validate(argumentName="$newSiteNodeName", type="TYPO3.Neos:NodeName")
	 */
	public function updateSiteAction(Site $site, $newSiteNodeName) {
		if ($site->getNodeName() !== $newSiteNodeName) {
			$oldSiteNodePath = '/sites/' . $site->getNodeName();
			$newSiteNodePath = '/sites/' . $newSiteNodeName;
			/** @var $workspace Workspace */
			foreach ($this->workspaceRepository->findAll() as $workspace) {
				$siteNode = $this->nodeDataRepository->findOneByPath($oldSiteNodePath, $workspace);
				if ($siteNode !== NULL) {
					$siteNode->setPath($newSiteNodePath);
				}
			}
			$site->setNodeName($newSiteNodeName);
			$this->nodeDataRepository->persistEntities();
		}
		$this->siteRepository->update($site);
		$this->addFlashMessage(sprintf('The site "%s" has been updated.', $site->getName()));
		$this->unsetLastVisitedNodeAndRedirect('index');
	}

	/**
	 * Create a new site form.
	 *
	 * @param Site $site Site to create
	 * @Flow\IgnoreValidation("$site")
	 * @return void
	 */
	public function newSiteAction(Site $site = NULL) {
		$sitePackages = $this->packageManager->getFilteredPackages('available', NULL, 'typo3-flow-site');
		$this->view->assignMultiple(array(
			'sitePackages' => $sitePackages,
			'site' => $site,
			'generatorServiceIsAvailable' => $this->packageManager->isPackageActive('TYPO3.Neos.Kickstarter')
		));
	}

	/**
	 * Create a new site.
	 *
	 * @param string $site Site to import
	 * @param string $packageKey Package Name to create
	 * @param string $siteName Site Name to create
	 * @Flow\Validate(argumentName="$packageKey", type="\TYPO3\Neos\Validation\Validator\PackageKeyValidator")
	 * @return void
	 */
	public function createSiteAction($site, $packageKey = '', $siteName = '') {
		if ($packageKey !== '' && $this->packageManager->isPackageActive('TYPO3.Neos.Kickstarter')) {
			if ($this->packageManager->isPackageAvailable($packageKey)) {
				$this->addFlashMessage('The package key "%s" already exists.', 'Invalid package key', Message::SEVERITY_ERROR, array($packageKey));
				$this->redirect('index');
			}

			$generatorService = $this->objectManager->get('TYPO3\Neos\Kickstarter\Service\GeneratorService');
			$generatorService->generateSitePackage($packageKey, $siteName);
			$this->packageManager->activatePackage($packageKey);
		} else {
			$packageKey = $site;
		}

		if ($packageKey !== '') {
			try {
				/** @var $contentContext ContentContext */
				$contentContext = $this->contextFactory->create(array(
					'workspaceName' => 'live',
					'invisibleContentShown' => TRUE,
					'inaccessibleContentShown' => TRUE
				));
				$this->siteImportService->importFromPackage($packageKey, $contentContext);
				$this->addFlashMessage('The site has been created.');
			} catch (\Exception $exception) {
				$this->systemLogger->logException($exception);
				$this->addFlashMessage('Error: During the import of the "Sites.xml" from the package "%s" an exception occurred: %s', 'Import error', Message::SEVERITY_ERROR, array($packageKey, $exception->getMessage()));
			}
		} else {
			$this->addFlashMessage('No site selected for import and no package name provided.', 'No site selected', Message::SEVERITY_ERROR);
			$this->redirect('newSite');
		}

		$this->unsetLastVisitedNodeAndRedirect('index');
	}

	/**
	 * Delete a site.
	 *
	 * @param Site $site Site to create
	 * @Flow\IgnoreValidation("$site")
	 * @return void
	 */
	public function deleteSiteAction(Site $site) {
		$domains = $this->domainRepository->findBySite($site);
		if (count($domains) > 0) {
			foreach ($domains as $domain) {
				$this->domainRepository->remove($domain);
			}
		}
		$this->siteRepository->remove($site);
		$siteNode = $this->propertyMapper->convert('/sites/' . $site->getNodeName(), 'TYPO3\TYPO3CR\Domain\Model\NodeInterface');
		$siteNode->remove();
		$this->addFlashMessage('The site "%s" has been deleted.', 'Site deleted', Message::SEVERITY_OK, array($site->getName()));
		$this->unsetLastVisitedNodeAndRedirect('index');
	}

	/**
	 * Activates a site
	 *
	 * @param Site $site Site to update
	 * @return void
	 */
	public function activateSiteAction(Site $site) {
		$site->setState($site::STATE_ONLINE);
		$this->siteRepository->update($site);
		$this->addFlashMessage('The site "%s" has been activated.', 'Site activated', Message::SEVERITY_OK, array($site->getName()));
		$this->unsetLastVisitedNodeAndRedirect('index');
	}

	/**
	 * Deactivates a site
	 *
	 * @param Site $site Site to deactivate
	 * @return void
	 */
	public function deactivateSiteAction(Site $site) {
		$site->setState($site::STATE_OFFLINE);
		$this->siteRepository->update($site);
		$this->addFlashMessage('The site "%s" has been deactivated.', 'Site deactivated', Message::SEVERITY_OK, array($site->getName()));
		$this->unsetLastVisitedNodeAndRedirect('index');
	}

	/**
	 * Edit a domain
	 *
	 * @param Domain $domain Domain to edit
	 * @Flow\IgnoreValidation("$domain")
	 * @return void
	 */
	public function editDomainAction(Domain $domain) {
		$this->view->assign('domain', $domain);
	}

	/**
	 * Update a domain
	 *
	 * @param Domain $domain Domain to update
	 * @Flow\Validate(argumentName="$domain", type="UniqueEntity")
	 * @return void
	 */
	public function updateDomainAction(Domain $domain) {
		$this->domainRepository->update($domain);
		$this->addFlashMessage('The domain "%s" has been updated.', 'Domain updated', Message::SEVERITY_OK, array($domain->getHostPattern()));
		$this->unsetLastVisitedNodeAndRedirect('edit', NULL, NULL, array('site' => $domain->getSite()));
	}

	/**
	 * The create a new domain action.
	 *
	 * @param Domain $domain
	 * @param Site $site
	 * @Flow\IgnoreValidation("$domain")
	 * @return void
	 */
	public function newDomainAction(Domain $domain = NULL, Site $site = NULL) {
		$this->view->assignMultiple(array(
			'domain' => $domain,
			'site' => $site
		));
	}

	/**
	 * Create a domain
	 *
	 * @param Domain $domain Domain to create
	 * @Flow\Validate(argumentName="$domain", type="UniqueEntity")
	 * @return void
	 */
	public function createDomainAction(Domain $domain) {
		$this->domainRepository->add($domain);
		$this->addFlashMessage('The domain "%s" has been created.', 'Domain created', Message::SEVERITY_OK, array($domain->getHostPattern()));
		$this->unsetLastVisitedNodeAndRedirect('edit', NULL, NULL, array('site' => $domain->getSite()));
	}

	/**
	 * Deletes a domain attached to a site
	 *
	 * @param Domain $domain A domain to delete
	 * @Flow\IgnoreValidation("$domain")
	 * @return void
	 */
	public function deleteDomainAction(Domain $domain) {
		$this->domainRepository->remove($domain);
		$this->addFlashMessage('The domain "%s" has been deleted.', 'Domain deleted', Message::SEVERITY_OK, array($domain->getHostPattern()));
		$this->unsetLastVisitedNodeAndRedirect('edit', NULL, NULL, array('site' => $domain->getSite()));
	}

	/**
	 * Activates a domain
	 *
	 * @param Domain $domain Domain to activate
	 * @return void
	 */
	public function activateDomainAction(Domain $domain) {
		$domain->setActive(TRUE);
		$this->domainRepository->update($domain);
		$this->addFlashMessage('The domain "%s" has been activated.', 'Domain activated', Message::SEVERITY_OK, array($domain->getHostPattern()));
		$this->unsetLastVisitedNodeAndRedirect('edit', NULL, NULL, array('site' => $domain->getSite()));
	}

	/**
	 * Deactivates a domain
	 *
	 * @param Domain $domain Domain to deactivate
	 * @return void
	 */
	public function deactivateDomainAction(Domain $domain) {
		$domain->setActive(FALSE);
		$this->domainRepository->update($domain);
		$this->addFlashMessage('The domain "%s" has been deactivated.', 'Domain deactivated', Message::SEVERITY_OK, array($domain->getHostPattern()));
		$this->unsetLastVisitedNodeAndRedirect('edit', NULL, NULL, array('site' => $domain->getSite()));
	}

	/**
	 * @param string $actionName Name of the action to forward to
	 * @param string $controllerName Unqualified object name of the controller to forward to. If not specified, the current controller is used.
	 * @param string $packageKey Key of the package containing the controller to forward to. If not specified, the current package is assumed.
	 * @param array $arguments Array of arguments for the target action
	 * @param integer $delay (optional) The delay in seconds. Default is no delay.
	 * @param integer $statusCode (optional) The HTTP status code for the redirect. Default is "303 See Other"
	 * @param string $format The format to use for the redirect URI
	 * @return void
	 */
	protected function unsetLastVisitedNodeAndRedirect($actionName, $controllerName = NULL, $packageKey = NULL, array $arguments = NULL, $delay = 0, $statusCode = 303, $format = NULL) {
		$this->session->putData('lastVisitedNode', NULL);
		parent::redirect($actionName, $controllerName, $packageKey, $arguments, $delay, $statusCode, $format);
	}
}
namespace TYPO3\Neos\Controller\Module\Administration;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * The TYPO3 Neos Sites Management module controller
 */
class SitesController extends SitesController_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {

	private $Flow_Aop_Proxy_targetMethodsAndGroupedAdvices = array();

	private $Flow_Aop_Proxy_groupedAdviceChains = array();

	private $Flow_Aop_Proxy_methodIsInAdviceMode = array();


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {

		$this->Flow_Aop_Proxy_buildMethodsAndAdvicesArray();
		if ('TYPO3\Neos\Controller\Module\Administration\SitesController' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 protected function Flow_Aop_Proxy_buildMethodsAndAdvicesArray() {
		if (method_exists(get_parent_class($this), 'Flow_Aop_Proxy_buildMethodsAndAdvicesArray') && is_callable('parent::Flow_Aop_Proxy_buildMethodsAndAdvicesArray')) parent::Flow_Aop_Proxy_buildMethodsAndAdvicesArray();

		$objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager;
		$this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices = array(
			'indexAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'editAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'updateSiteAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'newSiteAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'createSiteAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'deleteSiteAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'activateSiteAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'deactivateSiteAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'editDomainAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'updateDomainAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'newDomainAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'createDomainAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'deleteDomainAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'activateDomainAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
			'deactivateDomainAction' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Flow\Security\Aspect\PolicyEnforcementAspect', 'enforcePolicy', $objectManager, NULL),
				),
			),
		);
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {

		$this->Flow_Aop_Proxy_buildMethodsAndAdvicesArray();

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
		$result = NULL;
		if (method_exists(get_parent_class($this), '__wakeup') && is_callable('parent::__wakeup')) parent::__wakeup();
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies() {
		if (!isset($this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices) || empty($this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices)) {
			$this->Flow_Aop_Proxy_buildMethodsAndAdvicesArray();
			if (is_callable('parent::Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies')) parent::Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		}	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function Flow_Aop_Proxy_fixInjectedPropertiesForDoctrineProxies() {
		if (!$this instanceof \Doctrine\ORM\Proxy\Proxy || isset($this->Flow_Proxy_injectProperties_fixInjectedPropertiesForDoctrineProxies)) {
			return;
		}
		$this->Flow_Proxy_injectProperties_fixInjectedPropertiesForDoctrineProxies = TRUE;
		if (is_callable(array($this, 'Flow_Proxy_injectProperties'))) {
			$this->Flow_Proxy_injectProperties();
		}	}

	/**
	 * Autogenerated Proxy Method
	 */
	 private function Flow_Aop_Proxy_getAdviceChains($methodName) {
		$adviceChains = array();
		if (isset($this->Flow_Aop_Proxy_groupedAdviceChains[$methodName])) {
			$adviceChains = $this->Flow_Aop_Proxy_groupedAdviceChains[$methodName];
		} else {
			if (isset($this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices[$methodName])) {
				$groupedAdvices = $this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices[$methodName];
				if (isset($groupedAdvices['TYPO3\Flow\Aop\Advice\AroundAdvice'])) {
					$this->Flow_Aop_Proxy_groupedAdviceChains[$methodName]['TYPO3\Flow\Aop\Advice\AroundAdvice'] = new \TYPO3\Flow\Aop\Advice\AdviceChain($groupedAdvices['TYPO3\Flow\Aop\Advice\AroundAdvice']);
					$adviceChains = $this->Flow_Aop_Proxy_groupedAdviceChains[$methodName];
				}
			}
		}
		return $adviceChains;
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function Flow_Aop_Proxy_invokeJoinPoint(\TYPO3\Flow\Aop\JoinPointInterface $joinPoint) {
		if (__CLASS__ !== $joinPoint->getClassName()) return parent::Flow_Aop_Proxy_invokeJoinPoint($joinPoint);
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode[$joinPoint->getMethodName()])) {
			return call_user_func_array(array('self', $joinPoint->getMethodName()), $joinPoint->getMethodArguments());
		}
	}

	/**
	 * Autogenerated Proxy Method
	 * @return void
	 */
	 public function indexAction() {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['indexAction'])) {
		$result = parent::indexAction();

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['indexAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('indexAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Module\Administration\SitesController', 'indexAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['indexAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['indexAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param Site $site Site to view
	 * @return void
	 * @\TYPO3\Flow\Annotations\IgnoreValidation(argumentName="site")
	 */
	 public function editAction(\TYPO3\Neos\Domain\Model\Site $site) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['editAction'])) {
		$result = parent::editAction($site);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['editAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['site'] = $site;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('editAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Module\Administration\SitesController', 'editAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['editAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['editAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param Site $site A site to update
	 * @param string $newSiteNodeName A new site node name
	 * @return void
	 * @\TYPO3\Flow\Annotations\Validate(type="UniqueEntity", argumentName="site")
	 * @\TYPO3\Flow\Annotations\Validate(type="NotEmpty", argumentName="newSiteNodeName")
	 * @\TYPO3\Flow\Annotations\Validate(type="StringLength", options={ "minimum"=1, "maximum"=250 }, argumentName="newSiteNodeName")
	 * @\TYPO3\Flow\Annotations\Validate(type="TYPO3.Neos:NodeName", argumentName="newSiteNodeName")
	 */
	 public function updateSiteAction(\TYPO3\Neos\Domain\Model\Site $site, $newSiteNodeName) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['updateSiteAction'])) {
		$result = parent::updateSiteAction($site, $newSiteNodeName);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['updateSiteAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['site'] = $site;
				$methodArguments['newSiteNodeName'] = $newSiteNodeName;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('updateSiteAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Module\Administration\SitesController', 'updateSiteAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['updateSiteAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['updateSiteAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param Site $site Site to create
	 * @return void
	 * @\TYPO3\Flow\Annotations\IgnoreValidation(argumentName="site")
	 */
	 public function newSiteAction(\TYPO3\Neos\Domain\Model\Site $site = NULL) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['newSiteAction'])) {
		$result = parent::newSiteAction($site);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['newSiteAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['site'] = $site;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('newSiteAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Module\Administration\SitesController', 'newSiteAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['newSiteAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['newSiteAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param string $site Site to import
	 * @param string $packageKey Package Name to create
	 * @param string $siteName Site Name to create
	 * @return void
	 * @\TYPO3\Flow\Annotations\Validate(type="\TYPO3\Neos\Validation\Validator\PackageKeyValidator", argumentName="packageKey")
	 */
	 public function createSiteAction($site, $packageKey = '', $siteName = '') {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['createSiteAction'])) {
		$result = parent::createSiteAction($site, $packageKey, $siteName);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['createSiteAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['site'] = $site;
				$methodArguments['packageKey'] = $packageKey;
				$methodArguments['siteName'] = $siteName;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('createSiteAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Module\Administration\SitesController', 'createSiteAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['createSiteAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['createSiteAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param Site $site Site to create
	 * @return void
	 * @\TYPO3\Flow\Annotations\IgnoreValidation(argumentName="site")
	 */
	 public function deleteSiteAction(\TYPO3\Neos\Domain\Model\Site $site) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['deleteSiteAction'])) {
		$result = parent::deleteSiteAction($site);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['deleteSiteAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['site'] = $site;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('deleteSiteAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Module\Administration\SitesController', 'deleteSiteAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['deleteSiteAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['deleteSiteAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param Site $site Site to update
	 * @return void
	 */
	 public function activateSiteAction(\TYPO3\Neos\Domain\Model\Site $site) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['activateSiteAction'])) {
		$result = parent::activateSiteAction($site);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['activateSiteAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['site'] = $site;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('activateSiteAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Module\Administration\SitesController', 'activateSiteAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['activateSiteAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['activateSiteAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param Site $site Site to deactivate
	 * @return void
	 */
	 public function deactivateSiteAction(\TYPO3\Neos\Domain\Model\Site $site) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['deactivateSiteAction'])) {
		$result = parent::deactivateSiteAction($site);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['deactivateSiteAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['site'] = $site;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('deactivateSiteAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Module\Administration\SitesController', 'deactivateSiteAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['deactivateSiteAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['deactivateSiteAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param Domain $domain Domain to edit
	 * @return void
	 * @\TYPO3\Flow\Annotations\IgnoreValidation(argumentName="domain")
	 */
	 public function editDomainAction(\TYPO3\Neos\Domain\Model\Domain $domain) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['editDomainAction'])) {
		$result = parent::editDomainAction($domain);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['editDomainAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['domain'] = $domain;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('editDomainAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Module\Administration\SitesController', 'editDomainAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['editDomainAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['editDomainAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param Domain $domain Domain to update
	 * @return void
	 * @\TYPO3\Flow\Annotations\Validate(type="UniqueEntity", argumentName="domain")
	 */
	 public function updateDomainAction(\TYPO3\Neos\Domain\Model\Domain $domain) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['updateDomainAction'])) {
		$result = parent::updateDomainAction($domain);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['updateDomainAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['domain'] = $domain;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('updateDomainAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Module\Administration\SitesController', 'updateDomainAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['updateDomainAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['updateDomainAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param Domain $domain
	 * @param Site $site
	 * @return void
	 * @\TYPO3\Flow\Annotations\IgnoreValidation(argumentName="domain")
	 */
	 public function newDomainAction(\TYPO3\Neos\Domain\Model\Domain $domain = NULL, \TYPO3\Neos\Domain\Model\Site $site = NULL) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['newDomainAction'])) {
		$result = parent::newDomainAction($domain, $site);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['newDomainAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['domain'] = $domain;
				$methodArguments['site'] = $site;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('newDomainAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Module\Administration\SitesController', 'newDomainAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['newDomainAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['newDomainAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param Domain $domain Domain to create
	 * @return void
	 * @\TYPO3\Flow\Annotations\Validate(type="UniqueEntity", argumentName="domain")
	 */
	 public function createDomainAction(\TYPO3\Neos\Domain\Model\Domain $domain) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['createDomainAction'])) {
		$result = parent::createDomainAction($domain);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['createDomainAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['domain'] = $domain;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('createDomainAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Module\Administration\SitesController', 'createDomainAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['createDomainAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['createDomainAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param Domain $domain A domain to delete
	 * @return void
	 * @\TYPO3\Flow\Annotations\IgnoreValidation(argumentName="domain")
	 */
	 public function deleteDomainAction(\TYPO3\Neos\Domain\Model\Domain $domain) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['deleteDomainAction'])) {
		$result = parent::deleteDomainAction($domain);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['deleteDomainAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['domain'] = $domain;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('deleteDomainAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Module\Administration\SitesController', 'deleteDomainAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['deleteDomainAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['deleteDomainAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param Domain $domain Domain to activate
	 * @return void
	 */
	 public function activateDomainAction(\TYPO3\Neos\Domain\Model\Domain $domain) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['activateDomainAction'])) {
		$result = parent::activateDomainAction($domain);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['activateDomainAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['domain'] = $domain;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('activateDomainAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Module\Administration\SitesController', 'activateDomainAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['activateDomainAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['activateDomainAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 * @param Domain $domain Domain to deactivate
	 * @return void
	 */
	 public function deactivateDomainAction(\TYPO3\Neos\Domain\Model\Domain $domain) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['deactivateDomainAction'])) {
		$result = parent::deactivateDomainAction($domain);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['deactivateDomainAction'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['domain'] = $domain;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('deactivateDomainAction');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Neos\Controller\Module\Administration\SitesController', 'deactivateDomainAction', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['deactivateDomainAction']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['deactivateDomainAction']);
		}
		return $result;
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __sleep() {
		$result = NULL;
		$this->Flow_Object_PropertiesToSerialize = array();
	$reflectionService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Reflection\ReflectionService');
	$reflectedClass = new \ReflectionClass('TYPO3\Neos\Controller\Module\Administration\SitesController');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Neos\Controller\Module\Administration\SitesController', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Neos\Controller\Module\Administration\SitesController', $propertyName, 'var');
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
		$domainRepository_reference = &$this->domainRepository;
		$this->domainRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Neos\Domain\Repository\DomainRepository');
		if ($this->domainRepository === NULL) {
			$this->domainRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('6f2987c5f47777b01540a314d984b09c', $domainRepository_reference);
			if ($this->domainRepository === NULL) {
				$this->domainRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('6f2987c5f47777b01540a314d984b09c',  $domainRepository_reference, 'TYPO3\Neos\Domain\Repository\DomainRepository', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Neos\Domain\Repository\DomainRepository'); });
			}
		}
		$siteRepository_reference = &$this->siteRepository;
		$this->siteRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Neos\Domain\Repository\SiteRepository');
		if ($this->siteRepository === NULL) {
			$this->siteRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('5c3f2ab0e14ff0be3090c1f3efe77d7a', $siteRepository_reference);
			if ($this->siteRepository === NULL) {
				$this->siteRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('5c3f2ab0e14ff0be3090c1f3efe77d7a',  $siteRepository_reference, 'TYPO3\Neos\Domain\Repository\SiteRepository', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Neos\Domain\Repository\SiteRepository'); });
			}
		}
		$nodeDataRepository_reference = &$this->nodeDataRepository;
		$this->nodeDataRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Repository\NodeDataRepository');
		if ($this->nodeDataRepository === NULL) {
			$this->nodeDataRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('6d8e58e235099c88f352e23317321129', $nodeDataRepository_reference);
			if ($this->nodeDataRepository === NULL) {
				$this->nodeDataRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('6d8e58e235099c88f352e23317321129',  $nodeDataRepository_reference, 'TYPO3\TYPO3CR\Domain\Repository\NodeDataRepository', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Repository\NodeDataRepository'); });
			}
		}
		$workspaceRepository_reference = &$this->workspaceRepository;
		$this->workspaceRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Repository\WorkspaceRepository');
		if ($this->workspaceRepository === NULL) {
			$this->workspaceRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('2e64c564c983af14b47d0c9ae8992997', $workspaceRepository_reference);
			if ($this->workspaceRepository === NULL) {
				$this->workspaceRepository = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('2e64c564c983af14b47d0c9ae8992997',  $workspaceRepository_reference, 'TYPO3\TYPO3CR\Domain\Repository\WorkspaceRepository', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Repository\WorkspaceRepository'); });
			}
		}
		$packageManager_reference = &$this->packageManager;
		$this->packageManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Package\PackageManagerInterface');
		if ($this->packageManager === NULL) {
			$this->packageManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('aad0cdb65adb124cf4b4d16c5b42256c', $packageManager_reference);
			if ($this->packageManager === NULL) {
				$this->packageManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('aad0cdb65adb124cf4b4d16c5b42256c',  $packageManager_reference, 'TYPO3\Flow\Package\PackageManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Package\PackageManagerInterface'); });
			}
		}
		$siteImportService_reference = &$this->siteImportService;
		$this->siteImportService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Neos\Domain\Service\SiteImportService');
		if ($this->siteImportService === NULL) {
			$this->siteImportService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('a382bdbc7e75d00f0510a58eb9dd5b14', $siteImportService_reference);
			if ($this->siteImportService === NULL) {
				$this->siteImportService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('a382bdbc7e75d00f0510a58eb9dd5b14',  $siteImportService_reference, 'TYPO3\Neos\Domain\Service\SiteImportService', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Neos\Domain\Service\SiteImportService'); });
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
		$contextFactory_reference = &$this->contextFactory;
		$this->contextFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface');
		if ($this->contextFactory === NULL) {
			$this->contextFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('6b6e9d36a8365cb0dccb3d849ae9366e', $contextFactory_reference);
			if ($this->contextFactory === NULL) {
				$this->contextFactory = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('6b6e9d36a8365cb0dccb3d849ae9366e',  $contextFactory_reference, 'TYPO3\Neos\Domain\Service\ContentContextFactory', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface'); });
			}
		}
		$session_reference = &$this->session;
		$this->session = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Session\SessionInterface');
		if ($this->session === NULL) {
			$this->session = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('3055dab6d586d9b0b7e34ad0e5d2b702', $session_reference);
			if ($this->session === NULL) {
				$this->session = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('3055dab6d586d9b0b7e34ad0e5d2b702',  $session_reference, 'TYPO3\Flow\Session\SessionInterface', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Session\SessionInterface'); });
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
		$reflectionService_reference = &$this->reflectionService;
		$this->reflectionService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Reflection\ReflectionService');
		if ($this->reflectionService === NULL) {
			$this->reflectionService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('921ad637f16d2059757a908fceaf7076', $reflectionService_reference);
			if ($this->reflectionService === NULL) {
				$this->reflectionService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('921ad637f16d2059757a908fceaf7076',  $reflectionService_reference, 'TYPO3\Flow\Reflection\ReflectionService', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Reflection\ReflectionService'); });
			}
		}
		$mvcPropertyMappingConfigurationService_reference = &$this->mvcPropertyMappingConfigurationService;
		$this->mvcPropertyMappingConfigurationService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Mvc\Controller\MvcPropertyMappingConfigurationService');
		if ($this->mvcPropertyMappingConfigurationService === NULL) {
			$this->mvcPropertyMappingConfigurationService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('35acb49fbe78f28099d45aa647797c83', $mvcPropertyMappingConfigurationService_reference);
			if ($this->mvcPropertyMappingConfigurationService === NULL) {
				$this->mvcPropertyMappingConfigurationService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('35acb49fbe78f28099d45aa647797c83',  $mvcPropertyMappingConfigurationService_reference, 'TYPO3\Flow\Mvc\Controller\MvcPropertyMappingConfigurationService', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Mvc\Controller\MvcPropertyMappingConfigurationService'); });
			}
		}
		$viewConfigurationManager_reference = &$this->viewConfigurationManager;
		$this->viewConfigurationManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Mvc\ViewConfigurationManager');
		if ($this->viewConfigurationManager === NULL) {
			$this->viewConfigurationManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('5a345bfd515fdb9f0c97080ff13c7079', $viewConfigurationManager_reference);
			if ($this->viewConfigurationManager === NULL) {
				$this->viewConfigurationManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('5a345bfd515fdb9f0c97080ff13c7079',  $viewConfigurationManager_reference, 'TYPO3\Flow\Mvc\ViewConfigurationManager', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Mvc\ViewConfigurationManager'); });
			}
		}
		$validatorResolver_reference = &$this->validatorResolver;
		$this->validatorResolver = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Validation\ValidatorResolver');
		if ($this->validatorResolver === NULL) {
			$this->validatorResolver = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('b457db29305ddeae13b61d92da000ca0', $validatorResolver_reference);
			if ($this->validatorResolver === NULL) {
				$this->validatorResolver = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('b457db29305ddeae13b61d92da000ca0',  $validatorResolver_reference, 'TYPO3\Flow\Validation\ValidatorResolver', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Validation\ValidatorResolver'); });
			}
		}
		$flashMessageContainer_reference = &$this->flashMessageContainer;
		$this->flashMessageContainer = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Mvc\FlashMessageContainer');
		if ($this->flashMessageContainer === NULL) {
			$this->flashMessageContainer = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('e4fd26f8afd3994317304b563b2a9561', $flashMessageContainer_reference);
			if ($this->flashMessageContainer === NULL) {
				$this->flashMessageContainer = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('e4fd26f8afd3994317304b563b2a9561',  $flashMessageContainer_reference, 'TYPO3\Flow\Mvc\FlashMessageContainer', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Mvc\FlashMessageContainer'); });
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
$this->Flow_Injected_Properties = array (
  0 => 'settings',
  1 => 'domainRepository',
  2 => 'siteRepository',
  3 => 'nodeDataRepository',
  4 => 'workspaceRepository',
  5 => 'packageManager',
  6 => 'siteImportService',
  7 => 'propertyMapper',
  8 => 'systemLogger',
  9 => 'contextFactory',
  10 => 'session',
  11 => 'objectManager',
  12 => 'reflectionService',
  13 => 'mvcPropertyMappingConfigurationService',
  14 => 'viewConfigurationManager',
  15 => 'validatorResolver',
  16 => 'flashMessageContainer',
  17 => 'persistenceManager',
);
	}
}
#