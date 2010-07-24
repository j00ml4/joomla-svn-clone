<?php
/**
 * @version		$Id: article.php 17873 2010-06-25 18:24:21Z 3dentech $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * @package		Joomla.Site
 * @subpackage	com_project
 */
class ProjectsControllerDocuments extends JController
{
	protected $_context = 'com_projects.edit.document';
	

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
	public function getModel($name = 'Documents', $prefix = 'ProjectsModel', $config = null)
	{
		return parent::getModel($name, $prefix, $config);
	}
	
	/*
	 * Deletes checked documents
	 * 
	 */
	
	public function delete()
	{		
		// Check for request forgeries
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		$cid = JRequest::getVar('cid',array(),'default','array');
		JArrayHelper::toInteger($cid);
		$c = count($cid);
		$model = $this->getModel();
		$app = JFactory::getApplication();
		$tbl = $model->getTable();
		for($i = 0;$i <$c; $i++)
		{
			if(!$tbl->delete($cid[$i]))
			{
				return JError::raiseError(500, JText::_('COM_PROJECTS_DOCUMENTS_ERROR_DELETE'));
			}
		}
		$this->setRedirect(JRoute::_('index.php?option=com_projects&view=documents&id='.$app->getUserState('project.id').'&Itemid='.ProjectsHelper::getMenuItemId(), false),
		JText::_('COM_PROJECTS_DOCUMENTS_SUCCESS_DELETE'));
	}
}