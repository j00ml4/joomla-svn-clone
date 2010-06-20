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

/**
 * @package		Joomla.Site
 * @subpackage	Projects
 * @since		1.6
 */
class ProjectsControllerMembers extends JController
{			
	
	protected $view_item='project';
	protected $view_list='members';
	
	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param	string	The model name. Optional.
	 * @param	string	The class prefix. Optional.
	 * @param	array	Configuration array for model. Optional.
	 *
	 * @return	object	The model.
	 */
	public function getModel($name = 'Members', $prefix = 'ProjectsModel', $config = null)
	{
		return parent::getModel($name, $prefix, $config);
	}

	/**
	 * Constructor.
	 *
	 * @param	array An optional associative array of configuration settings. 
	 * @since	1.5
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask('assign','assignMembers');
		$this->registerTask('delete','deleteMembers');
	}
	
	
	/**
	 * Assigns members to a project
	 *
	 * @since	1.6
	 */
	public function assignMembers()
	{
		if(!ProjectsHelper::can('project.edit', $this->option)) {
			return JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
		}
		
		$users = JRequest::getVar('usr',array(),'','array');
		$c = count($users);
		$id = JRequest::getInt('id',0);
		
		if($id == 0 || !$c) {
			return JError::raiseError(404, JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'));
		}
		
		$model = $this->getModel();
		$db = $model->getDBO();
		for($i = 0; $i<$c;$i++) {
			$q = 'INSERT INTO `#__project_members` (`project_id`,`user_id`) VALUES ('.$id.','.$users[$i].')';
			$db->setQuery($q);
			$db->query();
		}
		
		$append='&layout=default&id='.$id;
		$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_item.$append, false),
											 JText::_('COM_PROJECT_PROJECT_MEMBER_ASSIGN_SUCCESSFUL'));
		return true;
	}
	
	/**
	 * deletes members from a project
	 *
	 * @since	1.6
	 */
	public function deleteMembers()
	{
		if(!ProjectsHelper::can('project.edit', $this->option)) {
			return JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
		}
		
		$users = JRequest::getVar('usr',array(),'','array');
		$c = count($users);
		$id = JRequest::getInt('id',0);
		
		if($id == 0 || !$c) {
			return JError::raiseError(404, JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'));
		}
		
		$model = $this->getModel();
		$db = $model->getDBO();
		for($i = 0; $i<$c;$i++) {
			$q = 'DELETE FROM `#__project_members` WHERE `project_id` = '.$id.' AND `user_id`='.$users[$i].' LIMIT 1';
			$db->setQuery($q);
			$db->query();
		}
		
		$append='&layout=default&id='.$id;
		$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_item.$append, false),
											 JText::_('COM_PROJECT_PROJECT_MEMBER_DELETE_SUCCESSFUL'));
		return true;
	}
}