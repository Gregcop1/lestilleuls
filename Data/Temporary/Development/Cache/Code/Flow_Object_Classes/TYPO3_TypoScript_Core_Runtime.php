<?php 
namespace TYPO3\TypoScript\Core;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.TypoScript".      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Object\ObjectManagerInterface;
use TYPO3\Flow\Utility\Arrays;
use TYPO3\Flow\Reflection\ObjectAccess;
use TYPO3\Flow\Utility\PositionalArraySorter;
use TYPO3\TypoScript\Core\Cache\RuntimeContentCache;
use TYPO3\TypoScript\Core\ExceptionHandlers\AbstractRenderingExceptionHandler;
use TYPO3\TypoScript\Exception as Exceptions;
use TYPO3\TypoScript\Exception;
use TYPO3\Flow\Security\Exception as SecurityException;
use TYPO3\TypoScript\TypoScriptObjects\AbstractTypoScriptObject;
use TYPO3\Eel\FlowQuery\FlowQuery;
use TYPO3\Eel\Utility as EelUtility;

/**
 * TypoScript Runtime
 *
 * TypoScript Rendering Process
 * ============================
 *
 * During rendering, all TypoScript objects form a tree.
 *
 * When a TypoScript object at a certain $typoScriptPath is invoked, it has
 * access to all variables stored in the $context (which is an array).
 *
 * The TypoScript object can then add or replace variables to this context using pushContext()
 * or pushContextArray(), before rendering sub-TypoScript objects. After rendering
 * these, it must call popContext() to reset the context to the last state.
 */
class Runtime_Original {

	/**
	 * Internal constants defining how evaluateInternal should work in case of an error.
	 */
	const BEHAVIOR_EXCEPTION = 'Exception';
	const BEHAVIOR_RETURNNULL = 'NULL';

	/**
	 * Internal constants defining a status of how evaluateInternal evaluated.
	 */
	const EVALUATION_EXECUTED = 'Executed';
	const EVALUATION_SKIPPED = 'Skipped';

	/**
	 * @var \TYPO3\Eel\CompilingEvaluator
	 * @Flow\Inject
	 */
	protected $eelEvaluator;

	/**
	 * @var ObjectManagerInterface
	 * @Flow\Inject
	 */
	protected $objectManager;

	/**
	 * Contains list of contexts
	 * @var array
	 */
	protected $renderingStack = array();

	/**
	 * Default context with helper definitions
	 * @var array
	 */
	protected $defaultContextVariables;

	/**
	 * @var array
	 */
	protected $typoScriptConfiguration;

	/**
	 * @var \TYPO3\Flow\Mvc\Controller\ControllerContext
	 */
	protected $controllerContext;

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @var array
	 */
	protected $configurationOnPathRuntimeCache = array();

	/**
	 * @var boolean
	 */
	protected $debugMode = FALSE;

	/**
	 * @var RuntimeContentCache
	 */
	protected $runtimeContentCache;

	/**
	 * @var string
	 */
	protected $lastEvaluationStatus;

	/**
	 * Constructor for the TypoScript Runtime
	 *
	 * @param array $typoScriptConfiguration
	 * @param \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext
	 */
	public function __construct(array $typoScriptConfiguration, \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext) {
		$this->typoScriptConfiguration = $typoScriptConfiguration;
		$this->controllerContext = $controllerContext;
		$this->runtimeContentCache = new RuntimeContentCache($this);
	}

	/**
	 * Inject settings of this package
	 *
	 * @param array $settings The settings
	 * @return void
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
		if (isset($this->settings['debugMode'])) {
			$this->setDebugMode($this->settings['debugMode'] === TRUE);
		}
		if (isset($this->settings['enableContentCache'])) {
			$this->setEnableContentCache($this->settings['enableContentCache'] === TRUE);
		}
	}

	/**
	 * Completely replace the context array with the new $contextArray.
	 *
	 * Purely internal method, should not be called outside of TYPO3.TypoScript.
	 *
	 * @param array $contextArray
	 * @return void
	 */
	public function pushContextArray(array $contextArray) {
		$this->renderingStack[] = $contextArray;
	}

	/**
	 * Push a new context object to the rendering stack
	 *
	 * @param string $key the key inside the context
	 * @param mixed $context
	 * @return void
	 */
	public function pushContext($key, $context) {
		$newContext = $this->getCurrentContext();
		$newContext[$key] = $context;
		$this->renderingStack[] = $newContext;
	}

	/**
	 * Remove the topmost context objects and return them
	 *
	 * @return array the topmost context objects as associative array
	 */
	public function popContext() {
		return array_pop($this->renderingStack);
	}

	/**
	 * Get the current context array
	 *
	 * @return array the array of current context objects
	 */
	public function getCurrentContext() {
		return $this->renderingStack[count($this->renderingStack) - 1];
	}

	/**
	 * Evaluate an absolute TypoScript path and return the result
	 *
	 * @param string $typoScriptPath
	 * @param object $contextObject the object available as "this" in Eel expressions. ONLY FOR INTERNAL USE!
	 * @return mixed the result of the evaluation, can be a string but also other data types
	 */
	public function evaluate($typoScriptPath, $contextObject = NULL) {
		return $this->evaluateInternal($typoScriptPath, self::BEHAVIOR_RETURNNULL, $contextObject);
	}

	/**
	 * @return string
	 */
	public function getLastEvaluationStatus() {
		return $this->lastEvaluationStatus;
	}

	/**
	 * Render an absolute TypoScript path and return the result.
	 *
	 * Compared to $this->evaluate, this adds some more comments helpful for debugging.
	 *
	 * @param string $typoScriptPath
	 * @return string
	 * @throws \Exception
	 * @throws \TYPO3\Flow\Configuration\Exception\InvalidConfigurationException
	 * @throws \TYPO3\Flow\Security\Exception
	 */
	public function render($typoScriptPath) {
		try {
			$output = $this->evaluateInternal($typoScriptPath, self::BEHAVIOR_EXCEPTION);
			if ($this->debugMode) {
				$output = sprintf('%1$s<!-- Beginning to render TS path "%2$s" (Context: %3$s) -->%4$s%1$s<!-- End to render TS path "%2$s" (Context: %3$s) -->',
					chr(10),
					$typoScriptPath,
					implode(', ', array_keys($this->getCurrentContext())),
					$output
				);
			}
		} catch (SecurityException $securityException) {
			throw $securityException;
		} catch (\Exception $exception) {
			$output = $this->handleRenderingException($typoScriptPath, $exception);
		}

		return $output;
	}

	/**
	 * Handle an Exception thrown while rendering TypoScript according to
	 * settings specified in TYPO3.TypoScript.rendering.exceptionHandler
	 *
	 * @param array $typoScriptPath
	 * @param \Exception $exception
	 * @param boolean $useInnerExceptionHandler
	 * @return string
	 * @throws \TYPO3\Flow\Mvc\Exception\StopActionException
	 * @throws \TYPO3\Flow\Configuration\Exception\InvalidConfigurationException
	 * @throws \Exception|\TYPO3\Flow\Exception
	 */
	public function handleRenderingException($typoScriptPath, \Exception $exception, $useInnerExceptionHandler = FALSE) {
		$typoScriptConfiguration = $this->getConfigurationForPath($typoScriptPath);

		if (isset($typoScriptConfiguration['__meta']['exceptionHandler'])) {
			$exceptionHandlerClass = $typoScriptConfiguration['__meta']['exceptionHandler'];
			$invalidExceptionHandlerMessage = 'The class "%s" is not valid for property "@exceptionHandler".';
		} else {
			if ($useInnerExceptionHandler === TRUE) {
				$exceptionHandlerClass = $this->settings['rendering']['innerExceptionHandler'];
			} else {
				$exceptionHandlerClass = $this->settings['rendering']['exceptionHandler'];
			}
			$invalidExceptionHandlerMessage = 'The class "%s" is not valid for setting "TYPO3.TypoScript.rendering.exceptionHandler".';
		}
		$exceptionHandler = NULL;
		if ($this->objectManager->isRegistered($exceptionHandlerClass)) {
			$exceptionHandler = $this->objectManager->get($exceptionHandlerClass);
		}

		if ($exceptionHandler === NULL || !($exceptionHandler instanceof AbstractRenderingExceptionHandler)) {
			$message = sprintf(
				$invalidExceptionHandlerMessage . "\n" .
					'Please specify a fully qualified classname to a subclass of %2$s\AbstractRenderingExceptionHandler.' . "\n" .
					'You might implement an own handler or use one of the following:' . "\n" .
					'%2$s\AbsorbingHandler' . "\n" .
					'%2$s\HtmlMessageHandler' . "\n" .
					'%2$s\PlainTextHandler' . "\n" .
					'%2$s\ThrowingHandler' . "\n" .
					'%2$s\XmlCommentHandler',
				$exceptionHandlerClass,
				'TYPO3\TypoScript\Core\ExceptionHandlers'
			);
			throw new \TYPO3\Flow\Configuration\Exception\InvalidConfigurationException($message, 1368788926);
		}

		$exceptionHandler->setRuntime($this);
		$output = $exceptionHandler->handleRenderingException($typoScriptPath, $exception);
		return $output;
	}

	/**
	 * Determine if the given TypoScript path is renderable, which means it exists
	 * and has an implementation.
	 *
	 * @param string $typoScriptPath
	 * @return boolean
	 */
	public function canRender($typoScriptPath) {
		$typoScriptConfiguration = $this->getConfigurationForPath($typoScriptPath);

		return $this->canRenderWithConfiguration($typoScriptConfiguration);
	}

	/**
	 * Internal evaluation if given configuration is renderable.
	 *
	 * @param array $typoScriptConfiguration
	 * @return boolean
	 */
	protected function canRenderWithConfiguration(array $typoScriptConfiguration) {
		if (isset($typoScriptConfiguration['__eelExpression'])) {
			return TRUE;
		}
		if (isset($typoScriptConfiguration['__value'])) {
			return TRUE;
		}

		if (!isset($typoScriptConfiguration['__meta']['class']) || !isset($typoScriptConfiguration['__objectType'])) {
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Internal evaluation method of absolute $typoScriptPath
	 *
	 * @param string $typoScriptPath
	 * @param string $behaviorIfPathNotFound one of BEHAVIOR_EXCEPTION or BEHAVIOR_RETURNNULL
	 * @param mixed $contextObject the object which will be "this" in Eel expressions, if any
	 * @return mixed
	 *
	 * @throws \Exception
	 * @throws \TYPO3\Flow\Configuration\Exception\InvalidConfigurationException
	 * @throws \TYPO3\Flow\Mvc\Exception\StopActionException
	 * @throws \TYPO3\Flow\Security\Exception
	 * @throws \TYPO3\Flow\Utility\Exception\InvalidPositionException
	 * @throws \TYPO3\TypoScript\Exception
	 * @throws \TYPO3\TypoScript\Exception\MissingTypoScriptImplementationException
	 * @throws \TYPO3\TypoScript\Exception\MissingTypoScriptObjectException
	 * @throws \TYPO3\TypoScript\Exception\RuntimeException
	 */
	protected function evaluateInternal($typoScriptPath, $behaviorIfPathNotFound, $contextObject = NULL) {
		$needToPopContext = FALSE;
		$this->lastEvaluationStatus = self::EVALUATION_EXECUTED;
		$typoScriptConfiguration = $this->getConfigurationForPath($typoScriptPath);
		$runtimeContentCache = $this->runtimeContentCache;

		$cacheCtx = $runtimeContentCache->enter(isset($typoScriptConfiguration['__meta']['cache']) ? $typoScriptConfiguration['__meta']['cache'] : array(), $typoScriptPath);

		// A closure that needs to be called for every return path in this method
		$self = $this;
		$finallyClosure = function($needToPopContext = FALSE) use ($cacheCtx, $runtimeContentCache, $self) {
			if ($needToPopContext) $self->popContext();
			$runtimeContentCache->leave($cacheCtx);
		};

		if (!$this->canRenderWithConfiguration($typoScriptConfiguration)) {
			$finallyClosure();
			if ($behaviorIfPathNotFound === self::BEHAVIOR_EXCEPTION) {
				if (!isset($typoScriptConfiguration['__objectType'])) {
					throw new Exceptions\MissingTypoScriptObjectException('No "' . $typoScriptPath . '" TypoScript object found. Please make sure to define one in your TypoScript configuration.', 1332493990);
				} else {
					throw new Exceptions\MissingTypoScriptImplementationException('The TypoScript object at path "' . $typoScriptPath . '" could not be rendered: Missing implementation class name for "' . $typoScriptConfiguration['__objectType'] . '". Add @class in your TypoScript configuration.', 1332493995);
				}
			} else {
				$this->lastEvaluationStatus = self::EVALUATION_SKIPPED;
				return NULL;
			}
		}

		try {
			if (isset($typoScriptConfiguration['__eelExpression']) || isset($typoScriptConfiguration['__value'])) {
				if (isset($typoScriptConfiguration['__meta']['if'])) {
					foreach ($typoScriptConfiguration['__meta']['if'] as $conditionKey => $conditionValue) {
						$conditionValue = $this->evaluateInternal($typoScriptPath . '/__meta/if/' . $conditionKey, self::BEHAVIOR_EXCEPTION, NULL);
						if ($conditionValue === FALSE) {
							$finallyClosure();
							$this->lastEvaluationStatus = self::EVALUATION_SKIPPED;
							return NULL;
						}
					}
				}

				$evaluatedExpression = $this->evaluateEelExpressionOrSimpleValueWithProcessor($typoScriptPath, $typoScriptConfiguration, $contextObject);
				$finallyClosure();
				return $evaluatedExpression;
			}

			$tsObject = $this->instantiateTypoScriptObject($typoScriptPath, $typoScriptConfiguration);

				// modify context if @override is specified
			if (isset($typoScriptConfiguration['__meta']['override'])) {
				$contextArray = $this->getCurrentContext();
				foreach ($typoScriptConfiguration['__meta']['override'] as $overrideKey => $overrideValue) {
					$contextArray[$overrideKey] = $this->evaluateInternal($typoScriptPath . '/__meta/override/' . $overrideKey, self::BEHAVIOR_EXCEPTION, $tsObject);
				}
				$this->pushContextArray($contextArray);
				$needToPopContext = TRUE;
			}

			list($cacheHit, $cachedResult) = $runtimeContentCache->preEvaluate($cacheCtx, $tsObject);
			if ($cacheHit) {
				$finallyClosure($needToPopContext);
				return $cachedResult;
			}

			$evaluateObject = TRUE;
			if (isset($typoScriptConfiguration['__meta']['if'])) {
				foreach ($typoScriptConfiguration['__meta']['if'] as $conditionKey => $conditionValue) {
					$conditionValue = $this->evaluateInternal($typoScriptPath . '/__meta/if/' . $conditionKey, self::BEHAVIOR_EXCEPTION, $tsObject);
					if ($conditionValue === FALSE) {
						$evaluateObject = FALSE;
					}
				}
			}

			if ($evaluateObject) {
				$output = $tsObject->evaluate();
			} else {
				$output = NULL;
				$this->lastEvaluationStatus = self::EVALUATION_SKIPPED;
			}
		} catch (\TYPO3\Flow\Mvc\Exception\StopActionException $stopActionException) {
			$finallyClosure($needToPopContext);
			throw $stopActionException;
		} catch (SecurityException $securityException) {
			throw $securityException;
		} catch (Exceptions\RuntimeException $runtimeException) {
			$finallyClosure($needToPopContext);
			throw $runtimeException;
		} catch (\Exception $exception) {
			$finallyClosure($needToPopContext);
			return $this->handleRenderingException($typoScriptPath, $exception, TRUE);
		}

		if (isset($typoScriptConfiguration['__meta']['process'])) {
			$positionalArraySorter = new PositionalArraySorter($typoScriptConfiguration['__meta']['process'], '__meta.position');
			foreach ($positionalArraySorter->getSortedKeys() as $key) {

				$processorPath = $typoScriptPath . '/__meta/process/' . $key;
				if (isset($typoScriptConfiguration['__meta']['process'][$key]['expression'])) {
					$processorPath .= '/expression';
				}

				$this->pushContext('value', $output);
				$output = $this->evaluateInternal($processorPath, self::BEHAVIOR_EXCEPTION, $tsObject);
				$this->popContext();
			}
		}

		$output = $runtimeContentCache->postProcess($cacheCtx, $tsObject, $output);
		$finallyClosure($needToPopContext);

		return $output;
	}

	/**
	 * Get the TypoScript Configuration for the given TypoScript path
	 *
	 * @param string $typoScriptPath
	 * @return array
	 * @throws \TYPO3\TypoScript\Exception
	 */
	protected function getConfigurationForPath($typoScriptPath) {
		$pathParts = explode('/', $typoScriptPath);

		$configuration = $this->typoScriptConfiguration;

		$pathUntilNow = '';
		$currentPrototypeDefinitions = array();
		if (isset($configuration['__prototypes'])) {
			$currentPrototypeDefinitions = $configuration['__prototypes'];
		}

			// we also store the configuration on the *last* level such that we are
			// able to add __processors to eel expressions and values.

		foreach ($pathParts as $pathPart) {
			$pathUntilNow .= '/' . $pathPart;
			if (isset($this->configurationOnPathRuntimeCache[$pathUntilNow])) {
				$configuration = $this->configurationOnPathRuntimeCache[$pathUntilNow]['c'];
				$currentPrototypeDefinitions = $this->configurationOnPathRuntimeCache[$pathUntilNow]['p'];
				continue;
			}
			if (preg_match('#^([^<]*)(<(.*?)>)?$#', $pathPart, $matches)) {
				$currentPathSegment = $matches[1];

				if (isset($configuration[$currentPathSegment])) {
					if (is_array($configuration[$currentPathSegment])) {
						$configuration = $configuration[$currentPathSegment];
					} else {
							// Needed for simple values (which cannot be arrays)
						$configuration = array(
							'__value' => $configuration[$currentPathSegment]
						);
					}
				} else {
					$configuration = array();
				}

				if (isset($configuration['__prototypes'])) {
					$currentPrototypeDefinitions = Arrays::arrayMergeRecursiveOverrule($currentPrototypeDefinitions, $configuration['__prototypes']);
				}

				if (isset($matches[3])) {
					$currentPathSegmentType = $matches[3];
				} elseif (isset($configuration['__objectType'])) {
					$currentPathSegmentType = $configuration['__objectType'];
				} else {
					$currentPathSegmentType = NULL;
				}

				if ($currentPathSegmentType !== NULL) {
					if (isset($currentPrototypeDefinitions[$currentPathSegmentType])) {

						if (isset($currentPrototypeDefinitions[$currentPathSegmentType]['__prototypeChain'])) {
							$prototypeMergingOrder = $currentPrototypeDefinitions[$currentPathSegmentType]['__prototypeChain'];
							$prototypeMergingOrder[] = $currentPathSegmentType;
						} else {
							$prototypeMergingOrder = array($currentPathSegmentType);
						}

						$currentPrototypeWithInheritanceTakenIntoAccount = array();

						foreach ($prototypeMergingOrder as $prototypeName) {
							$currentPrototypeWithInheritanceTakenIntoAccount = Arrays::arrayMergeRecursiveOverrule($currentPrototypeWithInheritanceTakenIntoAccount, $currentPrototypeDefinitions[$prototypeName]);
						}

							// We merge the already flattened prototype with the current configuration (in that order),
							// to make sure that the current configuration (not being defined in the prototype) wins.
						$configuration = Arrays::arrayMergeRecursiveOverrule($currentPrototypeWithInheritanceTakenIntoAccount, $configuration);

							// If context-dependent prototypes are set (such as prototype("foo").prototype("baz")),
							// we update the current prototype definitions.
						if (isset($currentPrototypeWithInheritanceTakenIntoAccount['__prototypes'])) {
							$currentPrototypeDefinitions = Arrays::arrayMergeRecursiveOverrule($currentPrototypeDefinitions, $currentPrototypeWithInheritanceTakenIntoAccount['__prototypes']);
						}
					}

					$configuration['__objectType'] = $currentPathSegmentType;
				}

				$this->configurationOnPathRuntimeCache[$pathUntilNow]['c'] = $configuration;
				$this->configurationOnPathRuntimeCache[$pathUntilNow]['p'] = $currentPrototypeDefinitions;
			} else {
				throw new Exception('Path Part ' . $pathPart . ' not well-formed', 1332494645);
			}
		}

		return $configuration;
	}

	/**
	 * Instantiates a TypoScript object specified by the given path and configuration
	 *
	 * @param string $typoScriptPath Path to the configuration for this object instance
	 * @param array $typoScriptConfiguration Configuration at the given path
	 * @return \TYPO3\TypoScript\TypoScriptObjects\AbstractTypoScriptObject
	 * @throws \TYPO3\TypoScript\Exception
	 */
	protected function instantiateTypoScriptObject($typoScriptPath, $typoScriptConfiguration) {
		$typoScriptObjectType = $typoScriptConfiguration['__objectType'];

		$tsObjectClassName = isset($typoScriptConfiguration['__meta']['class']) ? $typoScriptConfiguration['__meta']['class'] : NULL;

		if (!preg_match('#<[^>]*>$#', $typoScriptPath)) {
				// Only add TypoScript object type to last path part if not already set
			$typoScriptPath .= '<' . $typoScriptObjectType . '>';
		}
		if (!class_exists($tsObjectClassName)) {
			throw new Exception(sprintf('The implementation class "%s" defined for TypoScript object of type "%s" does not exist (defined at %s).', $tsObjectClassName, $typoScriptObjectType, $typoScriptPath), 1347952109);
		}
		/**
		 * @var $typoScriptObject \TYPO3\TypoScript\TypoScriptObjects\AbstractTypoScriptObject
		 */
		$typoScriptObject = new $tsObjectClassName($this, $typoScriptPath, $typoScriptObjectType);
		if ($typoScriptObject instanceof \TYPO3\TypoScript\TypoScriptObjects\AbstractArrayTypoScriptObject) {
			$this->setPropertiesOnTypoScriptObject($typoScriptObject, $typoScriptConfiguration);
		}
		return $typoScriptObject;
	}

	/**
	 * Set options on the given (AbstractArray)TypoScript object
	 *
	 * @param \TYPO3\TypoScript\TypoScriptObjects\AbstractTypoScriptObject $typoScriptObject
	 * @param array $typoScriptConfiguration
	 * @return void
	 */
	protected function setPropertiesOnTypoScriptObject(AbstractTypoScriptObject $typoScriptObject, array $typoScriptConfiguration) {
		foreach ($typoScriptConfiguration as $key => $value) {
			// skip keys which start with __, as they are purely internal.
			if ($key[0] === '_' && $key[1] === '_') {
				continue;
			}

			ObjectAccess::setProperty($typoScriptObject, $key, $value);
		}
	}

	/**
	 * Evaluate a simple value or eel expression with processors
	 *
	 * @param string $typoScriptPath the TypoScript path up to now
	 * @param array $value TypoScript configuration for the value
	 * @param \TYPO3\TypoScript\TypoScriptObjects\AbstractTypoScriptObject $contextObject An optional object for the "this" value inside the context
	 * @return mixed The result of the evaluation
	 */
	protected function evaluateEelExpressionOrSimpleValueWithProcessor($typoScriptPath, array $value, AbstractTypoScriptObject $contextObject = NULL) {
		if (isset($value['__eelExpression'])) {
			$evaluatedValue = $this->evaluateEelExpression($value['__eelExpression'], $contextObject);
		} else {
			// must be simple type, as this is the only place where this method is called.
			$evaluatedValue = $value['__value'];
		}

		if (isset($value['__meta']['process'])) {
			$positionalArraySorter = new PositionalArraySorter($value['__meta']['process'], '__meta.position');
			foreach ($positionalArraySorter->getSortedKeys() as $key) {

				$processorPath = $typoScriptPath . '/__meta/process/' . $key;
				if (isset($value['__meta']['process'][$key]['expression'])) {
					$processorPath .= '/expression';
				}

				$this->pushContext('value', $evaluatedValue);
				$evaluatedValue = $this->evaluateInternal($processorPath, self::BEHAVIOR_EXCEPTION, $contextObject);
				$this->popContext();
			}
		}

		return $evaluatedValue;
	}

	/**
	 * Evaluate an Eel expression
	 *
	 * @param string $expression The Eel expression to evaluate
	 * @param \TYPO3\TypoScript\TypoScriptObjects\AbstractTypoScriptObject $contextObject An optional object for the "this" value inside the context
	 * @return mixed The result of the evaluated Eel expression
	 * @throws \TYPO3\TypoScript\Exception
	 */
	protected function evaluateEelExpression($expression, AbstractTypoScriptObject $contextObject = NULL) {
		if ($expression[0] !== '$' || $expression[1] !== '{') {
			// We still assume this is an EEL expression and wrap the markers for backwards compatibility.
			$expression = '${' . $expression . '}';
		}

		$contextVariables = array_merge($this->getDefaultContextVariables(), $this->getCurrentContext());

		if (isset($contextVariables['this'])) {
			throw new Exception('Context variable "this" not allowed, as it is already reserved for a pointer to the current TypoScript object.', 1344325044);
		}
		$contextVariables['this'] = $contextObject;

		if ($this->eelEvaluator instanceof \TYPO3\Flow\Object\DependencyInjection\DependencyProxy) {
			$this->eelEvaluator->_activateDependency();
		}

		return EelUtility::evaluateEelExpression($expression, $this->eelEvaluator, $contextVariables);
	}

	/**
	 * Returns the context which has been passed by the currently active MVC Controller
	 *
	 * @return \TYPO3\Flow\Mvc\Controller\ControllerContext
	 */
	public function getControllerContext() {
		return $this->controllerContext;
	}

	/**
	 * Get variables from configuration that should be set in the context by default.
	 * For example Eel helpers are made available by this.
	 *
	 * @return array Array with default context variable objects.
	 */
	protected function getDefaultContextVariables() {
		if ($this->defaultContextVariables === NULL) {
			$this->defaultContextVariables = array();
			if (isset($this->settings['defaultContext']) && is_array($this->settings['defaultContext'])) {
				$this->defaultContextVariables = EelUtility::getDefaultContextVariables($this->settings['defaultContext']);
			}
			$this->defaultContextVariables['request'] = $this->controllerContext->getRequest();
		}
		return $this->defaultContextVariables;
	}

	/**
	 * @param boolean $debugMode
	 * @return void
	 */
	public function setDebugMode($debugMode) {
		$this->debugMode = $debugMode;
	}

	/**
	 * @return boolean
	 */
	public function isDebugMode() {
		return $this->debugMode;
	}

	/**
	 * If the TypoScript content cache should be enabled at all
	 *
	 * @param boolean $flag
	 * @return void
	 */
	public function setEnableContentCache($flag) {
		$this->runtimeContentCache->setEnableContentCache($flag);
	}

}
namespace TYPO3\TypoScript\Core;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * TypoScript Runtime
 * 
 * TypoScript Rendering Process
 * ============================
 * 
 * During rendering, all TypoScript objects form a tree.
 * 
 * When a TypoScript object at a certain $typoScriptPath is invoked, it has
 * access to all variables stored in the $context (which is an array).
 * 
 * The TypoScript object can then add or replace variables to this context using pushContext()
 * or pushContextArray(), before rendering sub-TypoScript objects. After rendering
 * these, it must call popContext() to reset the context to the last state.
 */
class Runtime extends Runtime_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {


	/**
	 * Autogenerated Proxy Method
	 * @param array $typoScriptConfiguration
	 * @param \TYPO3\Flow\Mvc\Controller\ControllerContext $controllerContext
	 */
	public function __construct() {
		$arguments = func_get_args();

		if (!array_key_exists(1, $arguments)) $arguments[1] = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Mvc\Controller\ControllerContext');
		if (!array_key_exists(0, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $typoScriptConfiguration in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		if (!array_key_exists(1, $arguments)) throw new \TYPO3\Flow\Object\Exception\UnresolvedDependenciesException('Missing required constructor argument $controllerContext in class ' . __CLASS__ . '. Note that constructor injection is only support for objects of scope singleton (and this is not a singleton) – for other scopes you must pass each required argument to the constructor yourself.', 1296143788);
		call_user_func_array('parent::__construct', $arguments);
		if ('TYPO3\TypoScript\Core\Runtime' === get_class($this)) {
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
	$reflectedClass = new \ReflectionClass('TYPO3\TypoScript\Core\Runtime');
	$allReflectedProperties = $reflectedClass->getProperties();
	foreach ($allReflectedProperties as $reflectionProperty) {
		$propertyName = $reflectionProperty->name;
		if (in_array($propertyName, array('Flow_Aop_Proxy_targetMethodsAndGroupedAdvices', 'Flow_Aop_Proxy_groupedAdviceChains', 'Flow_Aop_Proxy_methodIsInAdviceMode'))) continue;
		if (isset($this->Flow_Injected_Properties) && is_array($this->Flow_Injected_Properties) && in_array($propertyName, $this->Flow_Injected_Properties)) continue;
		if ($reflectionService->isPropertyAnnotatedWith('TYPO3\TypoScript\Core\Runtime', $propertyName, 'TYPO3\Flow\Annotations\Transient')) continue;
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
				$varTagValues = $reflectionService->getPropertyTagValues('TYPO3\TypoScript\Core\Runtime', $propertyName, 'var');
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
		$this->injectSettings(\TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Flow\Configuration\ConfigurationManager')->getConfiguration(\TYPO3\Flow\Configuration\ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, 'TYPO3.TypoScript'));
		$eelEvaluator_reference = &$this->eelEvaluator;
		$this->eelEvaluator = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getInstance('TYPO3\Eel\CompilingEvaluator');
		if ($this->eelEvaluator === NULL) {
			$this->eelEvaluator = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->getLazyDependencyByHash('e515090dd69391f3f787ec4d6e631a06', $eelEvaluator_reference);
			if ($this->eelEvaluator === NULL) {
				$this->eelEvaluator = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->createLazyDependency('e515090dd69391f3f787ec4d6e631a06',  $eelEvaluator_reference, 'TYPO3\Eel\CompilingEvaluator', function() { return \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get('TYPO3\Eel\CompilingEvaluator'); });
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
$this->Flow_Injected_Properties = array (
  0 => 'settings',
  1 => 'eelEvaluator',
  2 => 'objectManager',
);
	}
}
#