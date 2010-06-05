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
jimport('joomla.application.component.modeladmin');

/**
 * DRY Model on Steroids
 * @author eden
 *
 */
class DModel extends JModelAdmin
{
	/**
	 * @var		string context	The prefix to use with controller messages.
	 */
	protected $form_context;
	
	/**
	 * @var		string	The prefix to use with controller messages.
	 */
	protected $text_prefix;
	
	/**
	 * @var		string name of the table 
	 */
	protected $table_name;
	
	/**
	 * @var		string name of the table 
	 */
	protected $table_prefix;

	/**
	 * @var Object table
	 */
	protected $table;
	
	/**
	 * @var Object form
	 */
	protected $form;
	
	/**
	 * @var Object item
	 */
	protected $item;
	
	
	/**
	 * Method to test whether a user can do an action in the record
	 *
	 * @param	object	A record object.
	 * @param	array	a list of {action => asset}
	 * @return	boolean	True if allowed to change the state of the record. Defaults to the permission set in the component.
	 * @since	1.6
	 */
	public function canDo($accesses=array(), $record=null, $user=null)
	{
		empty($user) &&	$user = JFactory::getUser();
		
		// Nothing set?
		if (empty($accesses)) return false;
		// Check if have access
		foreach($accesses as $role -> $asset){
			//Is the author?
			if (
				($asset=='owner') && 
				!empty($record->created_by)
			){
				return ($record->created_by == $user->id);
			}

			// Has Permition?
			if ($user->authorise($role, $asset)){
				return true;	
			}
		}
		return false;
	}
	
	
	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($name = '', $options = array(), $clear = false)
	{
		if (empty($name)) {
			$name = $this->getName();
		}
		
		// Get the form.
		$this->form = $this->loadForm($this->form_context, $name); 
		//	array('control' => 'jform', 'load_data' => $loadData));
		
		// Nothing here
		if (empty($this->form)) {
			return false;
		}

		return $this->form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_'.$this->table_name.'.edit.weblink.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($name = null, $prefix = null, $config = array())
	{	
		empty($name) && $name = $this->table_name;
		empty($prefix) && $prefix = $this->table_prefix;
		
		// Return Table
		return $this->table = parent::getTable($name, $prefix, $config);
	}
	
	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		if(empty($pk)) return false;
		
		if ($this->item = parent::getItem($pk)) {
			
		}
		return $this->item;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @since	1.6
	 */
	protected function prepareTable(&$table)
	{
		jimport('joomla.filter.output');
		empty($table) && $table = $this->getTablee();
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);
		$table->alias		= JApplication::stringURLSafe($table->title);

		
		if (empty($table->id)) {
			// Set the values
			$table->created	= $date->toMySQL();
			$table->created_by	= $user->get('id');
		}
		else {
			// Set the values
			$table->modified	= $date->toMySQL();
			$table->modified_by	= $user->get('id');
		}
	}

	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param	object	A record object.
	 * @return	array	An array of conditions to add to add to ordering queries.
	 * @since	1.6
	 */
	protected function getReorderConditions(&$table = null)
	{
		$table or $table = $this->table;
		$condition = array();
		if (!empty($table->catid)) {
			$condition[] = 'catid = '.(int) $table->catid;
		}
		return $condition;
	}
}
?>