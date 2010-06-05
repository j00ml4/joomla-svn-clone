<?php
/**
 * @version     $Id$
 * @package     Joomla
 * @subpackage	Projects
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

require_once dirname(__FILE__).DS.'dmodel.php';

/**
 * Project model to display or edit a project
 * @author eden
 *
 */
class ProjectsModelProject extends DModel
{
	protected $text_prefix = 'COM_PROJECTS';
	protected $context = 'com_projects.edit.project';
	protected $form_context = 'com_projects.project';
	protected $table_name = 'Project';
	protected $table_prefix = 'ProjectsTable';

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param	object	A record object.
	 * @return	boolean	True if allowed to delete the record. Defaults to the permission set in the component.
	 * @since	1.6
	 */
	protected function canDelete($record, $user=null)
	{
		// Can delete? 
		return $this->canDo($record, array(
			'projecs.manage' 	=> 'com_projects.portfolio.'.(int)$record->catid,
			'projecs.manage'	=> 'com_projects'
		));
	}

	/**
	 * Method to test whether a record can be edited.
	 *
	 * @param	object	A record object.
	 * @return	boolean	True if allowed to change the state of the record. Defaults to the permission set in the component.
	 * @since	1.6
	 */
	protected function canEditState($record=null)
	{
		// Can delete? 
		return $this->canDo(array(
			'owner',
			'projecs.manage' 	=> 'com_projects.portfolio.'.(int)$record->catid,
			'projecs.manage'	=> 'com_projects'
		), $record);
	}

	protected function canCreate($record=null)
	{
		// Can delete? 
		return $this->canDo(array(
			'projecs.manage' 	=> 'com_projects.portfolio.'.(int)$record->catid,
			'projecs.manage'	=> 'com_projects',
			'projecs.submit'	=> 'com_projects'
		), $record);
	}

}
?>