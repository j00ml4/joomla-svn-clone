<?php
/**
 * @version		$Id: featured.php 14276 2010-01-18 14:20:28Z louis $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// why the heck isn't this working?!
//jimport('joomla.database.table.content');

require_once 'libraries'.DS.'joomla'.DS.'database'.DS.'table'.DS.'content.php';
/**
 * @package		Joomla.Site
 * @subpackage	Projects
 */
class ProjectsTableContent extends JTableContent
{
	
		/**
	 * Overriden JTable::store to set modified data and user id.
	 *
	 * @param	boolean	True to update fields even if they are null.
	 *
	 * @return	boolean	True on success.
	 * @since	1.6
	 */
	public function store($updateNulls=false) 
	{
		$save = $this->id == null;
		if(!parent::store($updateNulls))
			return false;
			
		if($save)
		{
			$db = $this->getDbo();
			$app= JFactory::getApplication();		
			$q = 'INSERT INTO `#__project_contents` (`project_id`,`content_id`) VALUES ('.$db->quote($app->getUserState('project.id')).', '.$db->quote($this->id).')';
			
			$db->setQuery($q);
			$db->query();
			if($db->getErrorNum())
				return false;
			else
				return true;
		}
		else
			return true;
	}
	
	/**
	 * Overriden JTable::delete to delete stored connection
	 *
	 * @param	mixed	An optional primary key value to delete.  If not set the
	 *					instance property value is used.
	 * @return	boolean	True on success.
	 * @since	1.0
	 * @link	http://docs.joomla.org/JTable/delete
	 */
	public function delete($pk = null)
	{
		if(!parent::delete($pk))
			return false;
		$db= $this->getDBO();
			
		$app = &JFactory::getApplication();
		$project_id = $app->getUserState('project.id');
		$q = 'DELETE FROM `#__project_contents` WHERE `project_id`='.$this->_db->quote($project_id).' AND `content_id`='.$this->_db->quote($pk).' LIMIT 1';		
		$db->setQuery($q);
		$db->query($q);
		if($db->getErrorNum())
		{
			return false;
		}
		else
		{
			return true;			
		}
	}
}