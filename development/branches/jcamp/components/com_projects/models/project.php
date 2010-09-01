<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	Projects
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Project model to display or edit a project
 * @author eden
 *
 */
class ProjectsModelProject extends JModelAdmin
{
	protected $text_prefix = 'COM_PROJECTS_PROJECT';
	protected $context = 'com_projects.edit.project';
	protected $item = null;
	protected $portfolio = null;
	
	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param	object	A record object.
	 * @return	boolean	True if allowed to delete the record. Defaults to the permission set in the component.
	 */
	protected function canDelete($record)
	{	
		return ProjectsHelper::canDo('core.delete',  
			$record->get('catid'), 
			$record->get('id'),
			$record);
	}

	/**
	 * Method to test whether a record can be edited.
	 *
	 * @param	object	A record object.
	 * @return	boolean	True if allowed to change the state of the record. Defaults to the permission set in the component.
	 */
	protected function canEditState($record)
	{
		return ProjectsHelper::canDo('core.edit', 
			$record->get('catid'), 
			$record->get('id'),
			$record);
	}
	
	/**
	 * Method to test whether a record can be edited.
	 *
	 * @param	object	A record object.
	 * @return	boolean	True if allowed to change the state of the record. Defaults to the permission set in the component.
	 */
	protected function canEdit($record)
	{
		return ProjectsHelper::canDo('core.edit', 
			$record->get('catid'), 
			$record->get('id'),
			$record);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *, $user=null
	 * @return	void
	 * @since	1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication();	
		
		// Load the User state.
		if (!($pk = (int) $app->getUserState($this->context.'.id'))) {
			$pk = (int) JRequest::getInt('id');
		}
		$this->setState($this->getName().'.id', $pk);
		$app->setUserState('project.id', $pk);
	}
	
	/**
	 * Function to get the portifolo
	 * @param $pk
	 */
	public function getPortfolio($pk = 'root'){
		// Get portifolio
		if(!is_object($this->portfolio)){
			jimport('joomla.application.categories');
			$categories = JCategories::getInstance('Projects');
			$this->portfolio = $categories->get($pk);
		}
		
		return $this->portfolio;
	} 
	
	
	/**
	 * Returns a reference to the a Table object, always creating it
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 *
	 * @return	JTable	A database object
	 * @since	1.6
	*/
	public function getTable($type = 'Project', $prefix = 'ProjectsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get a form object.
	 *
	 * @access	public
	 * @param	string		$xml		The form data. Can be XML string if file flag is set to false.
	 * @param	array		$options	Optional array of parameters.
	 * @param	boolean		$clear		Optional argument to force load a new form.
	 *
	 * @return	mixed		JForm object on success, False on error.
	 * @since	1.6
	 */
	public function getForm($data=array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_projects.project', 'project', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{	
		$app = JFactory::getApplication();
		// Check the session for previously entered form data.
		$data = $app->getUserState($this->context.'.data', array());
		if (empty($data)) {
			$data = $this->getItem();
		}
		return $data;
	}
	/**
	 * Method to validate the form data.
	 *
	 * @param	object		$form		The form to validate against.
	 * @param	array		$data		The data to validate.
	 *
	 * @return	mixed		Array of filtered data if valid, false otherwise.
	 */
	public function validate($form, $data)
	{
		return parent::validate($form, $data);
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{	
		if(!is_object($this->item)){
			$app = JFactory::getApplication();
			$this->item = parent::getItem($pk);
			if(!empty($this->item)){
				if(empty($this->item->id)){ // when creating a new project, try to set current portfolio as default
					$this->setState('portfolio.id',$app->getUserState('portfolio.id'));
					$this->item->set('catid',$this->setState('portfolio.id'));
					$this->item->progress = 0;
				}
				else {
					$this->setState('portfolio.id', $this->item->get('catid'));
					$app->setUserState('portfolio.id', $this->item->get('catid'));
					
					// Progress
					$db = $this->getDbo();
					$query = $db->getQuery(true);
					$query->from('`#__projects` AS p');
					$query->select(' FLOOR((COUNT(DISTINCT ntf.id) / COUNT(DISTINCT nt.id)) * 100) AS progress');
					$query->join('LEFT', '#__project_tasks AS nt ON nt.project_id=p.id AND nt.state != 0');
					$query->join('LEFT', '#__project_tasks AS ntf ON ntf.project_id=p.id AND ntf.state=2');
					$query->where('p.id='.$this->item->id);
					$db->setQuery($query);
	
					$this->item->progress = $db->loadResult();
				}

			}
		}
		return $this->item;
	}

	/**
	 * Prepare and sanitise the table data prior to saving.
	 *
	 * @param	JTable	A JTable object.
	 *		$this->_setAccessFilters($form, $data);
	 * @return	void
	 * @since	1.6
	 */
	protected function prepareTable(&$table)
	{
		jimport('joomla.filter.output');
		$date = &JFactory::getDate();
		$user = &JFactory::getUser();

		$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);
		$table->alias 		= JApplication::stringURLSafe($table->title);

		if (empty($table->id)) {
			// Set the values
			$table->created		= $date->toMySQL();
			$table->created_by	= $user->get('id');
			
			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db = &$this->getDbo();
				$db->setQuery('SELECT MAX(ordering)+1 FROM #__projects WHERE catid = '.(int)$table->catid);
				$max = $db->loadResult();

				$table->ordering = $max;
			}
		} else {
			// Set the values
			$table->modified	= $date->toMySQL();
			$table->modified_by	= $user->get('id');
		}
	}
	
	
	/**
	 * Assigns members to a project
	 *
	 * @since	1.6
	 */
	public function addMembers($pk=null, $members=array())
	{
		$members = (array) $members;
		if(empty($members)){
			$this->setError(JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'));
			return false;
		}
		
		$project = $this->getItem($pk);
		if(!$this->canEdit($project)) {
			return JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
		}
		
		$app 	= JFactory::getApplication();
		$db 	= $this->getDBO();
		$query	= '';
		foreach ($members as $i => $member) {
			$query .= 'INSERT INTO `#__project_members` (`project_id`,`user_id`) VALUES ('.$pk.','.$member.');';	
		}		
		$db->setQuery($query);
		$db->queryBatch();
		
		return true;
	}
	
	/**
	 * Deletes members from a project
	 *
	 * @since	1.6
	 */
	public function removeMembers($pk, $members=array())
	{
		$members = (array) $members;
		if(empty($members)){
			$this->setError(JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'));
			return false;
		}
		
		$project = $this->getItem($pk);
		if(!$this->canEdit($project)) {
			return JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
		}
		
		$app 	= JFactory::getApplication();
		$db 	= $this->getDBO();
		$query	= '';
		foreach ($members as $i => $member) {
			$query .= 'DELETE FROM `#__project_members` WHERE `project_id` = '.$pk.' AND `user_id`='.$member.';';	
		}		
		$db->setQuery($query);
		$db->queryBatch();

		return true;
	}
	
	/**
	 * Delete project
	 * @see libraries/joomla/application/component/JModelAdmin::delete()
	 */
	public function delete($pks)
	{	
		if(!parent::delete($pks)){
			return false;
		}
		
		$db 	= $this->getDBO();
		$where 	= 'WHERE `project_id` IN ('.implode(',', (array)$pks).') ';
		$query	= '';
		$query	.= ' DELETE FROM `#__project_members` '.$where.';';
		$query	.= ' DELETE FROM `#__project_tasks` '.$where.';';
		$query	.= ' DELETE FROM `#__content` WHERE `id` IN ('.
			'SELECT content_id FROM `#__project_contents` '.$where.');';
		$query	.= ' DELETE FROM `#__project_contents`'.$where.';';
		$db->setQuery($query);
		$db->queryBatch();

		return true;
	}
}
?>