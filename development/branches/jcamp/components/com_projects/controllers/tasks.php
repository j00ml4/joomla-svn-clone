<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	Projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * @package		Joomla.Site
 * @subpackage	Projects
 * @since		1.6
 */
class ProjectsControllerTask extends JControllerAdmin
{
	protected $view_list = 'tasks';
	//protected $text_prefix = 'COM_PROJECTS';
	
	/**
	 * Constructor.
	 *
	 * @param	array An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		// Define standard task mappings.
		//$this->registerTask('unpublish',	'publish');	// value = 0 
		//$this->registerTask('archive',		'publish');	// value = 2	// finished
		//$this->registerTask('trash',		'publish');	// value = -2
		//$this->registerTask('report',		'publish');	// value = -3 	// pending
		//$this->registerTask('orderup',		'reorder');
		//$this->registerTask('orderdown',	'reorder');
	}
	
	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param	string	The model name. Optional.
	 * @param	string	The class prefix. Optional.
	 * @param	array	Configuration array for model. Optional.
	 *
	 * @return	object	The model.
	 */
	public function getModel($name = 'Task', $prefix = 'ProjectsModel', $config = null)
	{
		return parent::getModel($name, $prefix, $config);
	}
		 
}