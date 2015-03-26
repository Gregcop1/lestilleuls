<?php 
namespace TYPO3\Flow\Persistence;

/*                                                                        *
 * This script belongs to the TYPO3 Flow framework.                       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */
use TYPO3\Flow\Core\ApplicationContext;

/**
 * The Flow Persistence Manager base class
 *
 * @api
 */
abstract class AbstractPersistenceManager_Original implements \TYPO3\Flow\Persistence\PersistenceManagerInterface {

	/**
	 * @var array
	 */
	protected $settings = array();

	/**
	 * @var array
	 */
	protected $newObjects = array();

	/**
	 * @var boolean
	 */
	protected $hasUnpersistedChanges = FALSE;

	/**
	 * @var \SplObjectStorage
	 */
	protected $whitelistedObjects;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->whitelistedObjects = new \SplObjectStorage();
	}

	/**
	 * Injects the Flow settings, the persistence part is kept
	 * for further use.
	 *
	 * @param array $settings
	 * @return void
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings['persistence'];
	}

	/**
	 * Clears the in-memory state of the persistence.
	 *
	 * @return void
	 */
	public function clearState() {
		$this->newObjects = array();
	}

	/**
	 * Registers an object which has been created or cloned during this request.
	 *
	 * The given object must contain the Persistence_Object_Identifier property, thus
	 * the PersistenceMagicInterface type hint. A "new" object does not necessarily
	 * have to be known by any repository or be persisted in the end.
	 *
	 * Objects registered with this method must be known to the getObjectByIdentifier()
	 * method.
	 *
	 * @param \TYPO3\Flow\Persistence\Aspect\PersistenceMagicInterface $object The new object to register
	 * @return void
	 */
	public function registerNewObject(\TYPO3\Flow\Persistence\Aspect\PersistenceMagicInterface $object) {
		$identifier = \TYPO3\Flow\Reflection\ObjectAccess::getProperty($object, 'Persistence_Object_Identifier', TRUE);
		$this->newObjects[$identifier] = $object;
	}

	/**
	 * Adds the given object to a whitelist of objects which may be persisted even if the current HTTP request
	 * is considered a "safe" request.
	 *
	 * @param object $object The object
	 * @return void
	 * @api
	 */
	public function whitelistObject($object) {
		$this->whitelistedObjects->attach($object);
	}

	/**
	 * Checks if the given object is whitelisted and if not, throws an exception
	 *
	 * @param object $object
	 * @return void
	 * @throws \TYPO3\Flow\Persistence\Exception
	 */
	protected function throwExceptionIfObjectIsNotWhitelisted($object) {
		if (!$this->whitelistedObjects->contains($object)) {
			$message = 'Detected modified or new objects (' . get_class($object). ', uuid:' . $this->getIdentifierByObject($object). ') to be persisted which is not allowed for "safe requests"' . chr(10) .
					'According to the HTTP 1.1 specification, so called "safe request" (usually GET or HEAD requests)' . chr(10) .
					'should not change your data on the server side and should be considered read-only. If you need to add,' . chr(10) .
					'modify or remove data, you should use the respective request methods (POST, PUT, DELETE and PATCH).' . chr(10) . chr(10) .
					'If you need to store some data during a safe request (for example, logging some data for your analytics),' . chr(10) .
					'you are still free to call PersistenceManager->persistAll() manually.';
			throw new \TYPO3\Flow\Persistence\Exception($message, 1377788621);
		}
	}

	/**
	 * Converts the given object into an array containing the identity of the domain object.
	 *
	 * @param object $object The object to be converted
	 * @return array The identity array in the format array('__identity' => '...')
	 * @throws \TYPO3\Flow\Persistence\Exception\UnknownObjectException if the given object is not known to the Persistence Manager
	 */
	public function convertObjectToIdentityArray($object) {
		$identifier = $this->getIdentifierByObject($object);
		if ($identifier === NULL) {
			throw new \TYPO3\Flow\Persistence\Exception\UnknownObjectException('The given object is unknown to the Persistence Manager.', 1302628242);
		}
		return array('__identity' => $identifier);
	}

	/**
	 * Recursively iterates through the given array and turns objects
	 * into an arrays containing the identity of the domain object.
	 *
	 * @param array $array The array to be iterated over
	 * @return array The modified array without objects
	 * @throws \TYPO3\Flow\Persistence\Exception\UnknownObjectException if array contains objects that are not known to the Persistence Manager
	 */
	public function convertObjectsToIdentityArrays(array $array) {
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$array[$key] = $this->convertObjectsToIdentityArrays($value);
			} elseif (is_object($value) && $value instanceof \Traversable) {
				$array[$key] = $this->convertObjectsToIdentityArrays(iterator_to_array($value));
			} elseif (is_object($value)) {
				$array[$key] = $this->convertObjectToIdentityArray($value);
			}
		}
		return $array;
	}

	/**
	 * Gives feedback if the persistence Manager has unpersisted changes.
	 *
	 * This is primarily used to inform the user if he tries to save
	 * data in an unsafe request.
	 *
	 * @return boolean
	 */
	public function hasUnpersistedChanges() {
		return $this->hasUnpersistedChanges;
	}

}namespace TYPO3\Flow\Persistence;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * The Flow Persistence Manager base class
 */
abstract class AbstractPersistenceManager extends AbstractPersistenceManager_Original implements \TYPO3\Flow\Object\Proxy\ProxyInterface {

	private $Flow_Aop_Proxy_targetMethodsAndGroupedAdvices = array();

	private $Flow_Aop_Proxy_groupedAdviceChains = array();

	private $Flow_Aop_Proxy_methodIsInAdviceMode = array();


	/**
	 * Autogenerated Proxy Method
	 */
	public function __construct() {

		$this->Flow_Aop_Proxy_buildMethodsAndAdvicesArray();
		parent::__construct();
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 protected function Flow_Aop_Proxy_buildMethodsAndAdvicesArray() {
		if (method_exists(get_parent_class($this), 'Flow_Aop_Proxy_buildMethodsAndAdvicesArray') && is_callable('parent::Flow_Aop_Proxy_buildMethodsAndAdvicesArray')) parent::Flow_Aop_Proxy_buildMethodsAndAdvicesArray();

		$objectManager = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager;
		$this->Flow_Aop_Proxy_targetMethodsAndGroupedAdvices = array(
			'convertObjectToIdentityArray' => array(
				'TYPO3\Flow\Aop\Advice\AroundAdvice' => array(
					new \TYPO3\Flow\Aop\Advice\AroundAdvice('TYPO3\Neos\Routing\NodeIdentityConverterAspect', 'convertNodeToContextPathForRouting', $objectManager, NULL),
				),
			),
		);
	}

	/**
	 * Autogenerated Proxy Method
	 */
	 public function __wakeup() {

		$this->Flow_Aop_Proxy_buildMethodsAndAdvicesArray();
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
	 * @param object $object The object to be converted
	 * @return array The identity array in the format array('__identity' => '...')
	 * @throws \TYPO3\Flow\Persistence\Exception\UnknownObjectException if the given object is not known to the Persistence Manager
	 */
	 public function convertObjectToIdentityArray($object) {

				// FIXME this can be removed again once Doctrine is fixed (see fixMethodsAndAdvicesArrayForDoctrineProxiesCode())
			$this->Flow_Aop_Proxy_fixMethodsAndAdvicesArrayForDoctrineProxies();
		if (isset($this->Flow_Aop_Proxy_methodIsInAdviceMode['convertObjectToIdentityArray'])) {
		$result = parent::convertObjectToIdentityArray($object);

		} else {
			$this->Flow_Aop_Proxy_methodIsInAdviceMode['convertObjectToIdentityArray'] = TRUE;
			try {
			
					$methodArguments = array();

				$methodArguments['object'] = $object;
			
				$adviceChains = $this->Flow_Aop_Proxy_getAdviceChains('convertObjectToIdentityArray');
				$adviceChain = $adviceChains['TYPO3\Flow\Aop\Advice\AroundAdvice'];
				$adviceChain->rewind();
				$joinPoint = new \TYPO3\Flow\Aop\JoinPoint($this, 'TYPO3\Flow\Persistence\AbstractPersistenceManager', 'convertObjectToIdentityArray', $methodArguments, $adviceChain);
				$result = $adviceChain->proceed($joinPoint);
				$methodArguments = $joinPoint->getMethodArguments();

			} catch (\Exception $e) {
				unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['convertObjectToIdentityArray']);
				throw $e;
			}
			unset($this->Flow_Aop_Proxy_methodIsInAdviceMode['convertObjectToIdentityArray']);
		}
		return $result;
	}
}
#