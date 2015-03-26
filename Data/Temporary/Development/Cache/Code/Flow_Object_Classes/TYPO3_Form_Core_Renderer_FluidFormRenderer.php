<?php 
namespace TYPO3\Form\Core\Renderer;

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

/**
 * Default form renderer which used *Fluid Templates* to render *Renderables*.
 *
 * **This class is not intended to be subclassed by developers.**
 *
 * The Fluid Form Renderer is especially capable of rendering nested renderables
 * as well, i.e a form with a page, with all FormElements.
 *
 * Options
 * =======
 *
 * The FluidFormRenderer uses some rendering options which are of particular
 * importance, as they determine how the form field is resolved to a path
 * in the file system.
 *
 * All rendering options are retrieved from the renderable which shall be rendered,
 * using the {@link \TYPO3\Form\Core\Model\Renderable\RenderableInterface::getRenderingOptions()}
 * method.
 *
 * templatePathPattern
 * -------------------
 *
 * File Path which is used to look up the template. Can contain the placeholders
 * *{@package}* and *{@type}*, which are filled from the *type* of the Renderable.
 *
 * Examples of template path patterns:
 *
 * - *resource://TYPO3.Form/Private/Templates/MyTemplate.html* <br />
 *   Path without any placeholders; is directly used as template.
 * - *resource://{@package}/Privat/Templates/Form/{@type}.html* <br />
 *   If the current renderable has the namespaced type *TYPO3.Form:FooBar*,
 *   then this path is *{@package}* from above is replaced with *TYPO3.Form*
 *   and *{@type}* is replaced with *FooBar*.
 *
 * The use of Template Path Patterns together with Form Element Inheritance
 * is a very powerful way to configure the mapping from Form Element Types
 * to Fluid Templates.
 *
 * layoutPathPattern
 * -----------------
 *
 * This pattern is used to resolve the *layout* which is referenced inside a
 * template. The same constraints as above apply, again *{@package}* and *{@type}*
 * are replaced.
 *
 * renderableNameInTemplate
 * ------------------------
 *
 * This is a mostly-internal setting which controls the name under which the current
 * renderable is made available inside the template. For example, it controls that
 * inside the template of a "Page", the Page object is available using the variable
 * *page*.
 *
 * Rendering Child Renderables
 * ===========================
 *
 * If a renderable wants to render child renderables, inside its template,
 * it can do that using the <code><form:renderable></code> ViewHelper.
 *
 * A template example from Page shall demonstrate this:
 *
 * <pre>
 * {namespace form=TYPO3\Form\ViewHelpers}
 * <f:for each="{page.elements}" as="element">
 *   <form:renderRenderable renderable="{element}" />
 * </f:for>
 * </pre>
 *
 * Rendering PHP Based Child Renderables
 * =====================================
 *
 * If a child renderable has a *rendererClassName* set (i.e. {@link \TYPO3\Form\Core\Model\FormElementInterface::getRendererClassName()}
 * returns a non-NULL string), this renderer is automatically instanciated
 * and the rendering for this element is delegated to this Renderer.
 */
class FluidFormRenderer_Original extends \TYPO3\Fluid\View\TemplateView implements RendererInterface {

	/**
	 * @var \TYPO3\Form\Core\Runtime\FormRuntime
	 */
	protected $formRuntime;

	/**
	 * Sets the current controller context
	 *
	 * @param \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext Controller context which is available inside the view
	 * @return void
	 * @api
	 */
	public function setControllerContext(\TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext) {
		$this->controllerContext = $controllerContext;
	}

	/**
	 * @param \TYPO3\Form\Core\Runtime\FormRuntime $formRuntime
	 * @return void
	 * @api
	 */
	public function setFormRuntime(\TYPO3\Form\Core\Runtime\FormRuntime $formRuntime) {
		$this->formRuntime = $formRuntime;
	}

	/**
	 * @return \TYPO3\Form\Core\Runtime\FormRuntime
	 * @api
	 */
	public function getFormRuntime() {
		return $this->formRuntime;
	}

	/**
	 * Overridden parser configuration, which always enables the escape interceptor
	 *
	 * @return \TYPO3\Fluid\Core\Parser\Configuration
	 */
	protected function buildParserConfiguration() {
		$parserConfiguration = new \TYPO3\Fluid\Core\Parser\Configuration();
		$parserConfiguration->addInterceptor($this->objectManager->get('TYPO3\Fluid\Core\Parser\Interceptor\Escape'));

		return $parserConfiguration;
	}

	/**
	 * Render the passed $renderable and return the rendered Renderable.
	 *
	 * @param \TYPO3\Form\Core\Model\Renderable\RootRenderableInterface $renderable
	 * @return string the rendered $renderable
	 * @throws \TYPO3\Form\Exception\RenderingException
	 * @api
	 */
	public function renderRenderable(\TYPO3\Form\Core\Model\Renderable\RootRenderableInterface $renderable) {
		$renderable->beforeRendering($this->formRuntime);

		$this->templateParser->setConfiguration($this->buildParserConfiguration());
		$renderableType = $renderable->getType();

		if ($renderable->getRendererClassName() !== NULL && $renderable->getRendererClassName() !== get_class($this)) {
			$rendererClassName = $renderable->getRendererClassName();
			$renderer = new $rendererClassName;
			if (!($renderer instanceof RendererInterface)) {
				throw new \TYPO3\Form\Exception\RenderingException(sprintf('The renderer class "%s" for "%s" does not implement RendererInterface.', $rendererClassName, $renderableType), 1326098022);
			}
			$renderer->setControllerContext($this->controllerContext);
			$renderer->setFormRuntime($this->formRuntime);
			return $renderer->renderRenderable($renderable);
		}

		$renderingOptions = $renderable->getRenderingOptions();

		$renderablePathAndFilename = $this->getPathAndFilenameForRenderable($renderableType, $renderingOptions);
		$parsedRenderable = $this->getParsedRenderable($renderable->getType(), $renderablePathAndFilename);

		if ($this->getCurrentRenderingContext() === NULL) {
				// We do not have a "current" rendering context yet, so we use the base rendering context
			$this->baseRenderingContext->setControllerContext($this->controllerContext);
			$renderingContext = $this->baseRenderingContext;
		} else {
			$renderingContext = clone $this->getCurrentRenderingContext();
		}
		$renderingContext->getViewHelperVariableContainer()->addOrUpdate('TYPO3\Form\Core\Renderer\FluidFormRenderer', 'currentRenderable', $renderable);

		if (!isset($renderingOptions['renderableNameInTemplate'])) {
			throw new \TYPO3\Form\Exception\RenderingException(sprintf('The Renderable "%s" did not have the rendering option "renderableNameInTemplate" defined.', $renderableType), 1326094948);
		}

		$templateVariableContainer = new \TYPO3\Fluid\Core\ViewHelper\TemplateVariableContainer(array($renderingOptions['renderableNameInTemplate'] => $renderable));
		$renderingContext->injectTemplateVariableContainer($templateVariableContainer);

		if ($parsedRenderable->hasLayout()) {
			$renderableLayoutName = $parsedRenderable->getLayoutName($renderingContext);
			$renderableLayoutPathAndFilename = $this->getPathAndFilenameForRenderableLayout($renderableLayoutName, $renderingOptions);
			$parsedLayout = $this->getParsedRenderable($renderableLayoutName, $renderableLayoutPathAndFilename);

			$this->startRendering(self::RENDERING_LAYOUT, $parsedRenderable, $renderingContext);
			$output = $parsedLayout->render($renderingContext);
			$this->stopRendering();
		} else {
			$this->startRendering(self::RENDERING_TEMPLATE, $parsedRenderable, $renderingContext);
			$output = $parsedRenderable->render($renderingContext);
			$this->stopRendering();
		}

		return $output;
	}

	/**
	 * Get full template path and filename for the given $renderableType.
	 *
	 * Reads the $renderingOptions['templatePathPattern'], replacing {@package} and {@type}
	 * from the given $renderableType.
	 *
	 * @param string $renderableType
	 * @param array $renderingOptions
	 * @return string the full path to the template which shall be used.
	 * @throws \TYPO3\Form\Exception\RenderingException
	 * @internal
	 */
	protected function getPathAndFilenameForRenderable($renderableType, array $renderingOptions) {
		if (!isset($renderingOptions['templatePathPattern'])) {
			throw new \TYPO3\Form\Exception\RenderingException(sprintf('The Renderable "%s" did not have the rendering option "templatePathPattern" defined.', $renderableType), 1326094041);
		}
		list($packageKey, $shortRenderableType) = explode(':', $renderableType);

		return strtr($renderingOptions['templatePathPattern'], array(
			'{@package}' => $packageKey,
			'{@type}' => $shortRenderableType
		));
	}

	/**
	 * Get full layout path and filename for the given $renderableType.
	 *
	 * Reads the $renderingOptions['layoutPathPattern'], replacing {@package} and {@type}
	 * from the given $renderableType.
	 *
	 * @param string $renderableType
	 * @param array $renderingOptions
	 * @return string the full path to the layout which shall be used.
	 * @throws \TYPO3\Form\Exception\RenderingException
	 * @internal
	 */
	protected function getPathAndFilenameForRenderableLayout($renderableType, array $renderingOptions) {
		if (!isset($renderingOptions['layoutPathPattern'])) {
			throw new \TYPO3\Form\Exception\RenderingException(sprintf('The Renderable "%s" did not have the rendering option "layoutPathPattern" defined.', $renderableType), 1326094161);
		}
		list($packageKey, $shortRenderableType) = explode(':', $renderableType);

		return strtr($renderingOptions['layoutPathPattern'], array(
			'{@package}' => $packageKey,
			'{@type}' => $shortRenderableType
		));
	}

	/**
	 * Resolve the partial path and filename based on $this->partialPathAndFilenamePattern.
	 *
	 * @param string $renderableType The name of the partial
	 * @return string the full path which should be used. The path definitely exists.
	 * @throws \TYPO3\Fluid\View\Exception\InvalidTemplateResourceException
	 * @throws \TYPO3\Form\Exception\RenderingException
	 */
	protected function getPartialPathAndFilename($renderableType) {
		$renderingContext = $this->getCurrentRenderingContext();
		$currentRenderable = $renderingContext->getViewHelperVariableContainer()->get('TYPO3\Form\Core\Renderer\FluidFormRenderer', 'currentRenderable');
		$renderingOptions = $currentRenderable->getRenderingOptions();
		if (!isset($renderingOptions['partialPathPattern'])) {
			throw new \TYPO3\Form\Exception\RenderingException(sprintf('The Renderable "%s" did not have the rendering option "partialPathPattern" defined.', $renderableType), 1326713352);
		}
		list($packageKey, $shortRenderableType) = explode(':', $renderableType);

		$partialPath = strtr($renderingOptions['partialPathPattern'], array(
			'{@package}' => $packageKey,
			'{@type}' => $shortRenderableType
		));
		if (file_exists($partialPath)) {
			return $partialPath;
		}
		throw new \TYPO3\Fluid\View\Exception\InvalidTemplateResourceException('The template file "' . $partialPath . '" could not be loaded.', 1326713418);
	}

	/**
	 * Get the parsed renderable for $renderablePathAndFilename.
	 *
	 * Internally, uses the templateCompiler automatically.
	 *
	 * @param string $renderableType
	 * @param string $renderablePathAndFilename
	 * @return \TYPO3\Fluid\Core\Parser\ParsedTemplateInterface
	 * @return \TYPO3\Fluid\Core\Parser\ParsedTemplateInterface
	 * @throws \TYPO3\Form\Exception
	 * @internal
	 */
	protected function getParsedRenderable($renderableType, $renderablePathAndFilename) {
		if (!file_exists($renderablePathAndFilename)) {
			throw new \TYPO3\Form\Exception(sprintf('The template "%s" does not exist', $renderablePathAndFilename), 1329233920);
		}
		$templateModifiedTimestamp = \filemtime($renderablePathAndFilename);
		$renderableIdentifier = sprintf('renderable_%s_%s', str_replace(array('.', ':'), '_', $renderableType), sha1($renderablePathAndFilename . '|' . $templateModifiedTimestamp));

		if ($this->templateCompiler->has($renderableIdentifier)) {
			$parsedRenderable = $this->templateCompiler->get($renderableIdentifier);
		} else {
			$parsedRenderable = $this->templateParser->parse(file_get_contents($renderablePathAndFilename));
			if ($parsedRenderable->isCompilable()) {
				$this->templateCompiler->store($renderableIdentifier, $parsedRenderable);
			}
		}
		return $parsedRenderable;
	}

}
namespace TYPO3\Form\Core\Renderer;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * Default form renderer which used *Fluid Templates* to render *Renderables*.
 * 
 * **This class is not intended to be subclassed by developers.**
 * 
 * The Fluid Form Renderer is especially capable of rendering nested renderables
 * as well, i.e a form with a page, with all FormElements.
 * 
 * Options
 * =======
 * 
 * The FluidFormRenderer uses some rendering options which are of particular
 * importance, as they determine how the form field is resolved to a path
 * in the file system.
 * 
 * All rendering options are retrieved from the renderable which shall be rendered,
 * using the {@link \TYPO3\Form\Core\Model\Renderable\RenderableInterface::getRenderingOptions()}
 * method.
 * 
 * templatePathPattern
 * -------------------
 * 
 * File Path which is used to look up the template. Can contain the placeholders
 * *{@package}* and *{@type}*, which are filled from the *type* of the Renderable.
 * 
 * Examples of template path patterns:
 * 
 * - *resource://TYPO3.Form/Private/Templates/MyTemplate.html* <br />
 *   Path without any placeholders; is directly used as template.
 * - *resource://{@package}/Privat/Templates/Form/{@type}.html* <br />
 *   If the current renderable has the namespaced type *TYPO3.Form:FooBar*,
 *   then this path is *{@package}* from above is replaced with *TYPO3.Form*
 *   and *{@type}* is replaced with *FooBar*.
 * 
 * The use of Template Path Patterns together with Form Element Inheritance
 * is a very powerful way to configure the mapping from Form Element Types
 * to Fluid Templates.
 * 
 * layoutPathPattern
 * -----------------
 * 
 * This pattern is used to resolve the *layout* which is referenced inside a
 * template. The same constraints as above apply, again *{@package}* and *{@type}*
 * are replaced.
 * 
 * renderableNameInTemplate
 * ------------------------
 * 
 * This is a mostly-internal setting which controls the name under which the current
 * renderable is made available inside the template. For example, it controls that
 * inside the template of a "Page", the Page object is available using the variable
 * *page*.
 * 
 * Rendering Child Renderables
 * ===========================
 * 
 * If a renderable wants to render child renderables, inside its template,
 * it can do that using the <code><form:renderable></code> ViewHelper.
 * 
 * A template example from Page shall demonstrate this:
 * 
 * <pre>
 * {namespace form=TYPO3\Form\ViewHelpers}
 * <f:for each="{page.elements}" as="element">
 *   <form:renderRenderable renderable="{element}" />
 * </f:for>
 * </pre>
 * 
 * Rendering PHP Based Child Renderables
 * =====================================
 * 
 * If a child renderable has a *rendererClassName* set (i.e. {@link \TYPO3\Form\Core\Model\FormElementInterface::getRendererClassName()}
 * returns a non-NULL string), this renderer is automatically instanciated
 * and the rendering for this element is delegated to this Renderer.
 */
class FluidFormRenderer extends FluidFormRenderer_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


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
		if ('TYPO3\Form\Core\Renderer\FluidFormRenderer' === get_class($this)) {
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
	$reflectedClass = new \ReflectionClass('TYPO3\Form\Core\Renderer\FluidFormRenderer');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\Form\Core\Renderer\FluidFormRenderer', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\Form\Core\Renderer\FluidFormRenderer', $propertyName, 'var');
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
		$this->setRenderingContext(new \TYPO3\Fluid\Core\Rendering\RenderingContext());
		$this->injectObjectManager(\TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Object\ObjectManagerInterface'));
		$this->injectTemplateParser(new \TYPO3\Fluid\Core\Parser\TemplateParser());
		$this->injectTemplateCompiler(\TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Fluid\Core\Compiler\TemplateCompiler'));
$this->Flow_Injected_Properties = array (
  0 => 'renderingContext',
  1 => 'objectManager',
  2 => 'templateParser',
  3 => 'templateCompiler',
);
	}
}
#