<?php 
namespace TYPO3\Form\Core\Runtime;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Form".            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\ActionRequest;

/**
 * This class implements the *runtime logic* of a form, i.e. deciding which
 * page is shown currently, what the current values of the form are, trigger
 * validation and property mapping.
 *
 * **This class is not meant to be subclassed by developers.**
 *
 * You generally receive an instance of this class by calling {@link \TYPO3\Form\Core\Model\FormDefinition::bind}.
 *
 * Rendering a Form
 * ================
 *
 * That's easy, just call render() on the FormRuntime:
 *
 * /---code php
 * $form = $formDefinition->bind($request, $response);
 * $renderedForm = $form->render();
 * \---
 *
 * Accessing Form Values
 * =====================
 *
 * In order to get the values the user has entered into the form, you can access
 * this object like an array: If a form field with the identifier *firstName*
 * exists, you can do **$form['firstName']** to retrieve its current value.
 *
 * You can also set values in the same way.
 *
 * Rendering Internals
 * ===================
 *
 * The FormRuntime asks the FormDefinition about the configured Renderer
 * which should be used ({@link \TYPO3\Form\Core\Model\FormDefinition::getRendererClassName}),
 * and then trigger render() on this element.
 *
 * This makes it possible to declaratively define how a form should be rendered.
 *
 * @api
 */
class FormRuntime_Original implements \TYPO3\Form\Core\Model\Renderable\RootRenderableInterface, \ArrayAccess {

	/**
	 * @var \TYPO3\Form\Core\Model\FormDefinition
	 * @internal
	 */
	protected $formDefinition;

	/**
	 * @var \TYPO3\Flow\Mvc\ActionRequest
	 * @internal
	 */
	protected $request;

	/**
	 * @var \TYPO3\Flow\Http\Response
	 * @internal
	 */
	protected $response;

	/**
	 * @var \TYPO3\Form\Core\Runtime\FormState
	 * @internal
	 */
	protected $formState;

	/**
	 * The current page is the page which will be displayed to the user
	 * during rendering.
	 *
	 * If $currentPage is NULL, the *last* page has been submitted and
	 * finishing actions need to take place. You should use $this->isAfterLastPage()
	 * instead of explicitely checking for NULL.
	 *
	 * @var \TYPO3\Form\Core\Model\Page
	 * @internal
	 */
	protected $currentPage = NULL;

	/**
	 * Reference to the page which has been shown on the last request (i.e.
	 * we have to handle the submitted data from lastDisplayedPage)
	 *
	 * @var \TYPO3\Form\Core\Model\Page
	 * @internal
	 */
	protected $lastDisplayedPage = NULL;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Cryptography\HashService
	 * @internal
	 */
	protected $hashService;

	/**
	 * Workaround...
	 *
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Mvc\FlashMessageContainer
	 * @internal
	 */
	protected $flashMessageContainer;

	/**
	 * @param \TYPO3\Form\Core\Model\FormDefinition $formDefinition
	 * @param \TYPO3\Flow\Mvc\ActionRequest $request
	 * @param \TYPO3\Flow\Http\Response $response
	 * @throws \TYPO3\Form\Exception\IdentifierNotValidException
	 * @internal
	 */
	public function __construct(\TYPO3\Form\Core\Model\FormDefinition $formDefinition, \TYPO3\Flow\Mvc\ActionRequest $request, \TYPO3\Flow\Http\Response $response) {
		$this->formDefinition = $formDefinition;
		$rootRequest = $request->getMainRequest() ?: $request;
		$pluginArguments = $rootRequest->getPluginArguments();
		$this->request = new ActionRequest($request);
		$formIdentifier = $this->formDefinition->getIdentifier();
		$this->request->setArgumentNamespace('--' . $formIdentifier);
		if (isset($pluginArguments[$formIdentifier])) {
			$this->request->setArguments($pluginArguments[$formIdentifier]);
		}

		$this->response = $response;
	}

	/**
	 * @return void
	 * @internal
	 */
	public function initializeObject() {
		$this->initializeFormStateFromRequest();
		$this->initializeCurrentPageFromRequest();

		if (!$this->isFirstRequest() && $this->getRequest()->getHttpRequest()->getMethod() === 'POST') {
			$this->processSubmittedFormValues();
		}
	}

	/**
	 * @return void
	 * @internal
	 */
	protected function initializeFormStateFromRequest() {
		$serializedFormStateWithHmac = $this->request->getInternalArgument('__state');
		if ($serializedFormStateWithHmac === NULL) {
			$this->formState = new FormState();
		} else {
			$serializedFormState = $this->hashService->validateAndStripHmac($serializedFormStateWithHmac);
			$this->formState = unserialize(base64_decode($serializedFormState));
		}
	}

	/**
	 * @return void
	 * @internal
	 */
	protected function initializeCurrentPageFromRequest() {
		if (!$this->formState->isFormSubmitted()) {
			$this->currentPage = $this->formDefinition->getPageByIndex(0);
			return;
		}
		$this->lastDisplayedPage = $this->formDefinition->getPageByIndex($this->formState->getLastDisplayedPageIndex());

		// We know now that lastDisplayedPage is filled
		$currentPageIndex = (integer)$this->request->getInternalArgument('__currentPage');
		if ($currentPageIndex > $this->lastDisplayedPage->getIndex() + 1) {
				// We only allow jumps to following pages
			$currentPageIndex = $this->lastDisplayedPage->getIndex() + 1;
		}

		// We now know that the user did not try to skip a page
		if ($currentPageIndex === count($this->formDefinition->getPages())) {
				// Last Page
			$this->currentPage = NULL;
		} else {
			$this->currentPage = $this->formDefinition->getPageByIndex($currentPageIndex);
		}
	}

	/**
	 * Returns TRUE if the last page of the form has been submitted, otherwise FALSE
	 *
	 * @return boolean
	 */
	protected function isAfterLastPage() {
		return ($this->currentPage === NULL);
	}

	/**
	 * Returns TRUE if no previous page is stored in the FormState, otherwise FALSE
	 *
	 * @return boolean
	 */
	protected function isFirstRequest() {
		return ($this->lastDisplayedPage === NULL);
	}

	/**
	 * @return void
	 * @internal
	 */
	protected function processSubmittedFormValues() {
		$result = $this->mapAndValidatePage($this->lastDisplayedPage);
		if ($result->hasErrors() && !$this->userWentBackToPreviousStep()) {
			$this->currentPage = $this->lastDisplayedPage;
			$this->request->setArgument('__submittedArguments', $this->request->getArguments());
			$this->request->setArgument('__submittedArgumentValidationResults', $result);
		}
	}

	/**
	 * returns TRUE if the user went back to any previous step in the form.
	 *
	 * @return boolean
	 */
	protected function userWentBackToPreviousStep() {
		return !$this->isAfterLastPage() && !$this->isFirstRequest() && $this->currentPage->getIndex() < $this->lastDisplayedPage->getIndex();
	}

	/**
	 * @param \TYPO3\Form\Core\Model\Page $page
	 * @return \TYPO3\Flow\Error\Result
	 * @internal
	 */
	protected function mapAndValidatePage(\TYPO3\Form\Core\Model\Page $page) {
		$result = new \TYPO3\Flow\Error\Result();
		$requestArguments = $this->request->getArguments();

		$propertyPathsForWhichPropertyMappingShouldHappen = array();
		$registerPropertyPaths = function($propertyPath) use (&$propertyPathsForWhichPropertyMappingShouldHappen) {
			$propertyPathParts = explode ('.', $propertyPath);
			$accumulatedPropertyPathParts = array();
			foreach ($propertyPathParts as $propertyPathPart) {
				$accumulatedPropertyPathParts[] = $propertyPathPart;
				$temporaryPropertyPath = implode('.', $accumulatedPropertyPathParts);
				$propertyPathsForWhichPropertyMappingShouldHappen[$temporaryPropertyPath] = $temporaryPropertyPath;
			}
		};

		foreach ($page->getElementsRecursively() as $element) {
			$value = \TYPO3\Flow\Utility\Arrays::getValueByPath($requestArguments, $element->getIdentifier());
			$element->onSubmit($this, $value);

			$this->formState->setFormValue($element->getIdentifier(), $value);
			$registerPropertyPaths($element->getIdentifier());
		}

		// The more parts the path has, the more early it is processed
		usort($propertyPathsForWhichPropertyMappingShouldHappen, function($a, $b) {
			return substr_count($b, '.') - substr_count($a, '.');
		});

		$processingRules = $this->formDefinition->getProcessingRules();
		foreach ($propertyPathsForWhichPropertyMappingShouldHappen as $propertyPath) {
			if (isset($processingRules[$propertyPath])) {
				$processingRule = $processingRules[$propertyPath];
				$value = $this->formState->getFormValue($propertyPath);
				try {
					$value = $processingRule->process($value);
				} catch (\TYPO3\Flow\Property\Exception $exception) {
					throw new \TYPO3\Form\Exception\PropertyMappingException('Failed to process FormValue at "' . $propertyPath . '" from "' . gettype($value) . '" to "' . $processingRule->getDataType() . '"', 1355218921, $exception);
				}
				$result->forProperty($propertyPath)->merge($processingRule->getProcessingMessages());
				$this->formState->setFormValue($propertyPath, $value);
			}
		}

		return $result;
	}

	/**
	 * Override the current page taken from the request, rendering the page with index $pageIndex instead.
	 *
	 * This is typically not needed in production code, but it is very helpful when displaying
	 * some kind of "preview" of the form.
	 *
	 * @param integer $pageIndex
	 * @return void
	 * @api
	 */
	public function overrideCurrentPage($pageIndex) {
		$this->currentPage = $this->formDefinition->getPageByIndex($pageIndex);
	}

	/**
	 * Render this form.
	 *
	 * @return string rendered form
	 * @api
	 * @throws \TYPO3\Form\Exception\RenderingException
	 */
	public function render() {
		if ($this->isAfterLastPage()) {
			$this->invokeFinishers();
			return $this->response->getContent();
		}

		$this->formState->setLastDisplayedPageIndex($this->currentPage->getIndex());

		if ($this->formDefinition->getRendererClassName() === NULL) {
			throw new \TYPO3\Form\Exception\RenderingException(sprintf('The form definition "%s" does not have a rendererClassName set.', $this->formDefinition->getIdentifier()), 1326095912);
		}
		$rendererClassName = $this->formDefinition->getRendererClassName();
		$renderer = new $rendererClassName();
		if (!($renderer instanceof \TYPO3\Form\Core\Renderer\RendererInterface)) {
			throw new \TYPO3\Form\Exception\RenderingException(sprintf('The renderer "%s" des not implement RendererInterface', $rendererClassName), 1326096024);
		}

		$controllerContext = $this->getControllerContext();
		$renderer->setControllerContext($controllerContext);

		$renderer->setFormRuntime($this);
		return $renderer->renderRenderable($this);
	}

	/**
	 * Executes all finishers of this form
	 *
	 * @return void
	 * @internal
	 */
	protected function invokeFinishers() {
		$finisherContext = new \TYPO3\Form\Core\Model\FinisherContext($this);
		foreach ($this->formDefinition->getFinishers() as $finisher) {
			$finisher->execute($finisherContext);
			if ($finisherContext->isCancelled()) {
				break;
			}
		}
	}

	/**
	 * @return string The identifier of underlying form
	 * @api
	 */
	public function getIdentifier() {
		return $this->formDefinition->getIdentifier();
	}

	/**
	 * Get the request this object is bound to.
	 *
	 * This is mostly relevant inside Finishers, where you f.e. want to redirect
	 * the user to another page.
	 *
	 * @return \TYPO3\Flow\Mvc\ActionRequest the request this object is bound to
	 * @api
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * Get the response this object is bound to.
	 *
	 * This is mostly relevant inside Finishers, where you f.e. want to set response
	 * headers or output content.
	 *
	 * @return \TYPO3\Flow\Http\Response the response this object is bound to
	 * @api
	 */
	public function getResponse() {
		return $this->response;
	}

	/**
	 * Returns the currently selected page
	 *
	 * @return \TYPO3\Form\Core\Model\Page
	 * @api
	 */
	public function getCurrentPage() {
		return $this->currentPage;
	}

	/**
	 * Returns the previous page of the currently selected one or NULL if there is no previous page
	 *
	 * @return \TYPO3\Form\Core\Model\Page
	 * @api
	 */
	public function getPreviousPage() {
		$previousPageIndex = $this->currentPage->getIndex() - 1;
		if ($this->formDefinition->hasPageWithIndex($previousPageIndex)) {
			return $this->formDefinition->getPageByIndex($previousPageIndex);
		}
	}

	/**
	 * Returns the next page of the currently selected one or NULL if there is no next page
	 *
	 * @return \TYPO3\Form\Core\Model\Page
	 * @api
	 */
	public function getNextPage() {
		$nextPageIndex = $this->currentPage->getIndex() + 1;
		if ($this->formDefinition->hasPageWithIndex($nextPageIndex)) {
			return $this->formDefinition->getPageByIndex($nextPageIndex);
		}
	}

	/**
	 * @return \TYPO3\Flow\Mvc\Controller\ControllerContext
	 * @internal
	 */
	protected function getControllerContext() {
		$uriBuilder = new \TYPO3\Flow\Mvc\Routing\UriBuilder();
		$uriBuilder->setRequest($this->request);

		return new \TYPO3\Flow\Mvc\Controller\ControllerContext(
			$this->request,
			$this->response,
			new \TYPO3\Flow\Mvc\Controller\Arguments(array()),
			$uriBuilder,
			$this->flashMessageContainer
		);
	}

	/**
	 * Abstract "type" of this Renderable. Is used during the rendering process
	 * to determine the template file or the View PHP class being used to render
	 * the particular element.
	 *
	 * @return string
	 * @api
	 */
	public function getType() {
		return $this->formDefinition->getType();
	}

	/**
	 * @param string $identifier
	 * @return mixed
	 * @api
	 */
	public function offsetExists($identifier) {
		return ($this->getElementValue($identifier) !== NULL);
	}

	/**
	 * Returns the value of the specified element
	 *
	 * @param string $identifier
	 * @return mixed
	 * @api
	 */
	protected function getElementValue($identifier) {
		$formValue = $this->formState->getFormValue($identifier);
		if ($formValue !== NULL) {
			return $formValue;
		}
		return $this->formDefinition->getElementDefaultValueByIdentifier($identifier);
	}

	/**
	 * @param string $identifier
	 * @return mixed
	 * @api
	 */
	public function offsetGet($identifier) {
		return $this->getElementValue($identifier);
	}

	/**
	 * @param string $identifier
	 * @param mixed $value
	 * @return void
	 * @api
	 */
	public function offsetSet($identifier, $value) {
		$this->formState->setFormValue($identifier, $value);
	}

	/**
	 * @api
	 * @param string $identifier
	 * @return void
	 */
	public function offsetUnset($identifier) {
		$this->formState->setFormValue($identifier, NULL);
	}

	/**
	 * @return array<TYPO3\Form\Core\Model\Page> The Form's pages in the correct order
	 * @api
	 */
	public function getPages() {
		return $this->formDefinition->getPages();
	}

	/**
	 * @return \TYPO3\Form\Core\Runtime\FormState
	 * @internal
	 */
	public function getFormState() {
		return $this->formState;
	}

	/**
	 * Get all rendering options
	 *
	 * @return array associative array of rendering options
	 * @api
	 */
	public function getRenderingOptions() {
		return $this->formDefinition->getRenderingOptions();
	}

	/**
	 * Get the renderer class name to be used to display this renderable;
	 * must implement RendererInterface
	 *
	 * @return string the renderer class name
	 * @api
	 */
	public function getRendererClassName() {
		return $this->formDefinition->getRendererClassName();
	}

	/**
	 * Get the label which shall be displayed next to the form element
	 *
	 * @return string
	 * @api
	 */
	public function getLabel() {
		return $this->formDefinition->getLabel();
	}

	/**
	 * Get the underlying form definition from the runtime
	 *
	 * @return \TYPO3\Form\Core\Model\FormDefinition
	 * @api
	 */
	public function getFormDefinition() {
		return $this->formDefinition;
	}

	/**
	 * This is a callback that is invoked by the Renderer before the corresponding element is rendered.
	 * Use this to access previously submitted values and/or modify the $formRuntime before an element
	 * is outputted to the browser.
	 *
	 * @param \TYPO3\Form\Core\Runtime\FormRuntime $formRuntime
	 * @return void
	 * @api
	 */
	public function beforeRendering(\TYPO3\Form\Core\Runtime\FormRuntime $formRuntime) {
	}
}
namespace TYPO3\Form\Core\Runtime;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * This class implements the *runtime logic* of a form, i.e. deciding which
 * page is shown currently, what the current values of the form are, trigger
 * validation and property mapping.
 * 
 * **This class is not meant to be subclassed by developers.**
 * 
 * You generally receive an instance of this class by calling {@link \TYPO3\Form\Core\Model\FormDefinition::bind}.
 * 
 * Rendering a Form
 * ================
 * 
 * That's easy, just call render() on the FormRuntime:
 * 
 * /---code php
 * $form = $formDefinition->bind($request, $response);
 * $renderedForm = $form->render();
 * \---
 * 
 * Accessing Form Values
 * =====================
 * 
 * In order to get the values the user has entered into the form, you can access
 * this object like an array: If a form field with the identifier *firstName*
 * exists, you can do **$form['firstName']** to retrieve its current value.
 * 
 * You can also set values in the same way.
 * 
 * Rendering Internals
 * ===================
 * 
 * The FormRuntime asks the FormDefinition about the configured Renderer
 * which should be used ({@link \TYPO3\Form\Core\Model\FormDefinition::getRendererClassName}),
 * and then trigger render() on this element.
 * 
 * This makes it possible to declaratively define how a form should be rendered.
 */
class FormRuntime extends FormRuntime_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 * @param \TYPO3\Form\Core\Model\FormDefinition $formDefinition
	 * @param \TYPO3\Flow\Mvc\ActionRequest $request
	 * @param \TYPO3\Flow\Http\Response $response
	 * @throws \TYPO3\Form\Exception\IdentifierNotValidException
	 */
	public function __construct() {
		$arguments = func_get_args();

		if (!array_key_exists(0, $arguments)) $arguments[0] = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Form\Core\Model\FormDefinition');
		if (!array_key_exists(1, $arguments)) $arguments[1] = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Mvc\ActionRequest');
		if (!array_key_exists(2, $arguments)) $arguments[2] = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Http\Response');
		if (!array_key_exists(0, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $formDefinition in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		if (!array_key_exists(1, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $request in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		if (!array_key_exists(2, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $response in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		call_user_func_array('parent::__construct', $arguments);
		if ('TYPO3\Form\Core\Runtime\FormRuntime' === get_class($this)) {
			$this->Flow_Proxy_injectProperties();
		}

		if (get_class($this) === 'TYPO3\Form\Core\Runtime\FormRuntime') {
			$this->initializeObject(1);
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
		$result = NULL;

		if (get_class($this) === 'TYPO3\Form\Core\Runtime\FormRuntime') {
			$this->initializeObject(2);
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
	$reflectedClass = new \ReflectionClass('TYPO3\Form\Core\Runtime\FormRuntime');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Form\Core\Runtime\FormRuntime', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Form\Core\Runtime\FormRuntime', $propertyName, 'var');
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
		$hashService_reference = &$this->hashService;
		$this->hashService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Flow\Security\Cryptography\HashService');
		if ($this->hashService === NULL) {
			$this->hashService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('af606f3838da2ad86bf0ed2ff61be394', $hashService_reference);
			if ($this->hashService === NULL) {
				$this->hashService = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('af606f3838da2ad86bf0ed2ff61be394',  $hashService_reference, 'TYPO3\Flow\Security\Cryptography\HashService', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Security\Cryptography\HashService'); });
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
$this->Flow_Injected_Properties = array (
  0 => 'hashService',
  1 => 'flashMessageContainer',
);
	}
}
#