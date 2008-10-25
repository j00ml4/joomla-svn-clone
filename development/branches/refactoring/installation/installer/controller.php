<?php

/**
 * @version		$Id$
 * @package		Joomla
 * @subpackage	Installation
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * @package		Joomla
 * @subpackage	Installation
 */

jimport('joomla.application.component.controller');
require_once dirname(__FILE__).DS.'models'.DS.'model.php';
require_once dirname(__FILE__).DS.'views'.DS.'install'.DS.'view.php';

class JInstallationController extends JController
{
	protected $_model		= null;

	protected $_view		= null;


	/**
	 * Constructor
	 */
	function __construct( $config = array() )
	{
		$config['name']	= 'JInstallation';
		parent::__construct( $config );
	}

	/**
	 *
	 *
	 * @return	Boolean True if successful
	 * @access	public
	 * @since	1.5
	 */
	function dbconfig()
	{
		$model	= $this->getModel();
		$view	= $this->getView();

		if ( ! $model->dbConfig() )
		{
			$view->error();
			return false;
		}

		$view->dbConfig();

		return true;
	}

	/**
	 * Overload the parent controller method to add a check for configuration variables
	 *  when a task has been provided
	 *
	 * @param	String $task Task to perform
	 * @return	Boolean True if successful
	 * @access	public
	 * @since	1.5
	 */
	function execute($task)
	{
		$appl = JFactory::getApplication();

		// Sanity check
		if ( $task && ( $task != 'lang' ) && ( $task != 'removedir' ) )
		{

			/**
			 * To get past this point, a cookietest must be carried in the user's state.
			 * If the state is not set, then cookies are probably disabled.
			 **/

			$goodEnoughForMe = $appl->getUserState('application.cookietest');

			if ( ! $goodEnoughForMe )
			{
				$model	= $this->getModel();
				$model->setError(JText::_('WARNCOOKIESNOTENABLED'));
				$view	= $this->getView();
				$view->error();
				return false;
			}
			
			// Check for request forgeries.
			$token = JUtility::getToken();
			if (!JRequest::getInt($token, 0, 'post')) {
				$model	= $this->getModel();
				$model->setError(JText::_('INVALID_SESSION_USE_NAVIGATION'));
				$view	= $this->getView();
				$view->error();
				return false;
			}

		}
		else
		{
			// Zilch the application registry - start from scratch
			$session	=& JFactory::getSession();
			$registry	=& $session->get('registry');
			$registry->makeNameSpace('application');

			// Set the cookie test seed
			$appl->setUserState('application.cookietest', 1);
		}

		// Try to execute the specified task
		try {
			
			return parent::execute($task);
		
		} catch ( Exception $e )
		{
			// Doh!!!
			$message	= $e->getMessage();
			
			// Additional information
			if ( $info = $e->get('info') )
			{
				$message = "$message ( $info )"; 
			}
			
			$model	= $this->getModel();
			$view	= $this->getView();
			
			$model->setError($message);
			$view->error();
		}
	}

	/**
	 * Initialize data for the installation
	 *
	 * @return	Boolean True if successful
	 * @access	public
	 * @since	1.5
	 */
	function initialize()
	{
		return true;
	}

	/**
	 * Present form for FTP information
	 *
	 * @return	Boolean True if successful
	 * @access	public
	 * @since	1.5
	 */
	function ftpConfig()
	{
		$model	= $this->getModel();
		$view	= $this->getView();

		if ( ! $model->ftpConfig() )
		{
			$view->error();
			return false;
		}

		$view->ftpConfig();

		return true;
	}
	
	function ftpTest()
	{
		$model	= $this->getModel();
		
		if ( ! $model->ftpTest() )
		{
			$view	= $this->getView();
			$view->error();
			return false;
		}
		
		return $this->loadData();
	}

	/**
	 * Get the model for the installer component
	 *
	 * @return	JInstallerModel
	 * @access	protected
	 * @since	1.5
	 */
	function & getModel()
	{

		if ( ! $this->_model )
		{
			$this->_model	= new JInstallationModel();
		}

		return $this->_model;
	}

	/**
	 * Get the view for the installer component
	 *
	 * @return	JInstallerView
	 * @access	protected
	 * @since	1.5
	 */
	function & getView()
	{

		if ( ! $this->_view )
		{
			$this->_view	= new JInstallationView();
			$model	= $this->getModel();
			$model->test = "blah";
			$this->_view->setModel($model, true);
		}

		return $this->_view;
	}

	/**
	 * Present license information
	 *
	 * @return	Boolean True if successful
	 * @access	public
	 * @since	1.5
	 */
	function license()
	{
		$model	= $this->getModel();
		$view	= $this->getView();

		if ( ! $model->license() )
		{
			$view->error();
			return false;
		}

		$view->license();

		return true;
	}
	
	/**
	 * Load data into the system
	 *
	 */
	function loadData()
	{
		$model	= $this->getModel();
		$view	= $this->getView();
		
		$view->loadData();
	}

	/**
	 * Present a choice of languages
	 *
	 * Step One!
	 *
	 * @return	Boolean True if successful
	 * @access	public
	 * @since	1.5
	 */
	function lang()
	{
		$model	= $this->getModel();
		$view	= $this->getView();

		if ( ! $model->chooseLanguage() )
		{
			$view->error();
			return false;
		}

		$view->chooseLanguage();

		return true;
	}

	/**
	 * Test the database connection
	 *
	 * @return	Boolean True if successful
	 * @access	public
	 * @since	1.6
	 */
	function dbtest()
	{
		$model	= $this->getModel();
		$view	= $this->getView();

		if ( ! $model->dbTest())
		{
			$view->error();
			return false;
		}

		if ( ! $model->ftpConfig( 1 ) )
		{
			$view->error();
			return false;
		}

	
		$view->ftpConfig();
		
		return true;
	}

	/**
	 * Present the main configuration options
	 *
	 * @return	Boolean True if successful
	 * @access	public
	 * @since	1.5
	 */
	function mainconfig()
	{

		$model	= $this->getModel();
		$view	= $this->getView();

		if ( ! $model->mainConfig() )
		{
			$view->error();
			return false;
		}

		$view->mainConfig();

		return true;
	}
	

	function installFresh()
	{
		$model	= $this->getModel();
		$view	= $this->getView();

		if ( ! $model->makeDB() )
		{
			$view->error();
			return true;
		}

		$view->freshInstall();

		return true;
	}
	
	public function installUpgrade()
	{
		echo "need to implement installUpgrade";
	}

	public function installMigrate()
	{
		echo "need to implement installMigrate";
	}
	
	/**
	 * Present a preinstall check
	 *
	 * Step Two!
	 *
	 * @return	Boolean True if successful
	 * @access	public
	 * @since	1.5
	 */
	function preinstall()
	{
		$model	= $this->getModel();
		$view	= $this->getView();

		if ( ! $model->preInstall() )
		{
			$view->error();
			return true;
		}

		$view->preInstall();

		return true;
	}

	/**
	 * Remove directory messages
	 *
	 * @return	Boolean True if successful
	 * @access	public
	 * @since	1.5
	 */
	function removedir()
	{
		$model	= $this->getModel();
		$view	= $this->getView();

		if ( ! $model->removedir() )
		{
			$view->error();
			return true;
		}

		$view->removedir();

		return true;
	}

	/**
	 *
	 *
	 * @return	Boolean True if successful
	 * @access	public
	 * @since	1.5
	 */
	function saveconfig()
	{
		$model	= $this->getModel();
		$view	= $this->getView();

		if ( ! $model->saveConfig() )
		{
			$view->error();
			return false;
		}

		if ( ! $model->finish() )
		{
			$view->error();
			return false;
		}

		$view->finish();

		return true;
	}

	function migration()
	{
		$model	= $this->getModel();
		$view	= $this->getView();
		
		if(!$model->checkUpload()) {
			$view->error();
			return false;
		}

		if (!$model->dumpLoad())
		{
			$view->error();
			return false;
		}

		$view->migration();
		return true;
	}

	function postmigrate()
	{
		$model	= $this->getModel();
		$view	= $this->getView();
		
		if($model->postMigrate()) {
			// errors!
		}
	}

}
