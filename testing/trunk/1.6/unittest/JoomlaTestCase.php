<?php
/**
 * JoomlaTestCase.php -- unit testing file for JUtilities
 *
 * @version		$Id: $
 * @package    Joomla.UnitTest
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
require_once 'PHPUnit/Framework.php';
/**
 * Test case class for Joomla Unit Testing
 *
 * @package    Joomla.UnitTest
 *
 */
abstract class JoomlaTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var factoryState
     */
    protected $factoryState = array();

    /**
     * @var errorState
     */
    protected $savedErrorState;

    /**
     * @var actualError
     */
    protected static $actualError;
    
    /**
     * Saves the current state of the JError error handlers.
     *
     * @return	void
     */
    protected function saveErrorHandlers()
    {
    	$this->savedErrorState = array();
     	$this->savedErrorState[E_NOTICE] = JError::getErrorHandling(E_NOTICE);
     	$this->savedErrorState[E_WARNING] = JError::getErrorHandling(E_WARNING);
     	$this->savedErrorState[E_ERROR] = JError::getErrorHandling(E_ERROR);
    }

    /**
     * Sets the JError error handlers.
     *
     * @param	array	araay of values and options to set the handlers
     *
     * @return	void
     */
    protected function setErrorHandlers( $errorHandlers )
    {
    	$mode = null;
    	$options = null;
    	
    	foreach ($errorHandlers as $type => $params)
    	{
    		$mode = $params['mode'];
    		if (isset($params['options']))
    		{
 				JError::setErrorHandling($type, $mode, $params['options']);
    		}
    		else
    		{
 				JError::setErrorHandling($type, $mode);
 			}
    	}
    }

    /**
     * Sets the JError error handlers to callback mode and points them at the test
     * logging method.
     *
     * @return	void
     */
    protected function setErrorCallback( $testName )
    {
    	$callbackHandlers = array(
    		E_NOTICE => array(
    			'mode' => 'callback',
    			'options' => array($testName, 'errorCallback')
    			),
    		E_WARNING => array(
    			'mode' => 'callback',
    			'options' => array($testName, 'errorCallback')
    			),
    		E_ERROR => array(
    			'mode' => 'callback',
    			'options' => array($testName, 'errorCallback')
    			),
    		);
    	$this->setErrorHandlers($callbackHandlers);
	}

    /**
     * Receives the callback from JError and logs the required error information for the test.
     *
     * @param	JException	The JException object from JError
     *
     * @return	bool	To not continue with JError processing
     */
    static function errorCallback( $error )
    {
    }

	/**
	 * Saves the Factory pointers
	 *
	 * @return void
	 */
	protected function saveFactoryState()
	{
		$this->savedFactoryState['application'] = JFactory::$application;
		$this->savedFactoryState['config'] = JFactory::$config;
		$this->savedFactoryState['session'] = JFactory::$session;
		$this->savedFactoryState['language'] = JFactory::$language;
		$this->savedFactoryState['document'] = JFactory::$document;
		$this->savedFactoryState['acl'] = JFactory::$acl;
		$this->savedFactoryState['database'] = JFactory::$database;
		$this->savedFactoryState['mailer'] = JFactory::$mailer;
	}

	/**
	 * Sets the Factory pointers
	 *
	 * @return void
	 */
	protected function restoreFactoryState()
	{
		JFactory::$application = $this->savedFactoryState['application'];
		JFactory::$config = $this->savedFactoryState['config'];
		JFactory::$session = $this->savedFactoryState['session'];
		JFactory::$language = $this->savedFactoryState['language'];
		JFactory::$document = $this->savedFactoryState['document'];
		JFactory::$acl = $this->savedFactoryState['acl'];
		JFactory::$database = $this->savedFactoryState['database'];
		JFactory::$mailer = $this->savedFactoryState['mailer'];
	}
}
?>