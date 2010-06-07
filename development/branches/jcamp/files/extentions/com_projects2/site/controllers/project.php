<?php
/**
 * @version		$Id: weblink.php 17248 2010-05-25 01:58:09Z eddieajau $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * @package		Joomla.Site
 * @subpackage	com_weblinks
 * @since		1.5
 */
class ProjectsControllerProject extends JControllerForm
{
	protected $context = 'com_projects.edit.project';
	protected $option = 'com_projects';
	protected $view_item = 'project';
	protected $view_list = 'projects';
	protected $text_prefix = 'COM_PROJECTS';
	
	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param	string	The model name. Optional.
	 * @param	string	The class prefix. Optional.
	 * @param	array	Configuration array for model. Optional.
	 *
	 * @return	object	The model.
	 */
	public function getModel($name = 'Project', $prefix = 'ProjectsModel', $config = null)
	{
		return parent::getModel($name, $prefix, $config);
	}
	
}