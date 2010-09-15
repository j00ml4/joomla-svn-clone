<?php
/**
 * @version		$Id: article.php 17873 2010-06-25 18:24:21Z 3dentech $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * @package		Joomla.Site
 * @subpackage	com_project
 */
class ProjectsControllerDocuments extends JControllerAdmin
{
	protected $_context = 'com_projects.edit.document';
	protected $text_prefix = 'COM_PROJECTS_DOCUMENTS';
	

	/**
	 * Constructor
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('delete',		'delete');
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
	public function getModel($name = 'Document', $prefix = 'ProjectsModel', $config = array())
	{
		return parent::getModel($name, $prefix, $config);
	}
	
	/*
	 * Deletes checked documents
	 * 
	 * @since 1.6
	 */
	
	public function delete()
	{		
		parent::delete();
		
		$app = JFactory::getApplication();
		$this->setRedirect(ProjectsHelper::getLink('documents', $app->getUserState('project.id')));	
	}
}