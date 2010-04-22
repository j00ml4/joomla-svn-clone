<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Categories Component Categories Model
 *
 * @package		Joomla.Administrator
 * @subpackage	com_categories
 * @since		1.6
 */
class CategoriesModelCategories extends JModelList
{
	/**
	 * Method to auto-populate the model state.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		// Initialise variables.
		$app		= JFactory::getApplication();
		$context	= $this->context;

		$extension = $app->getUserStateFromRequest($this->context.'.filter.extension', 'extension');
		$this->setState('filter.extension', $extension);
		$parts = explode('.',$extension);
		// extract the component name
		$this->setState('filter.component', $parts[0]);
		// extract the optional section name
		$this->setState('filter.section', (count($parts) > 1) ? $parts[1] : null);

		if (!empty($extension)) {
			$context .= '.'.$extension;
			$this->context = $context;
		}

		$data = JRequest::getVar('filters');
		if (empty($data)) {
			$data = $app->getUserState($context.'.data');
		}
		else {
			$app->setUserState($context.'.data', $data);
		}

		$this->setState('filter.search', isset($data['search']['expr']) ? $data['search']['expr'] : '');

		$this->setState('filter.published', isset($data['select']['state']) ? $data['select']['state'] : '');
		$this->setState('filter.access', isset($data['select']['access']) ? $data['select']['access'] : '');
		$this->setState('filter.language', isset($data['select']['language']) ? $data['select']['language'] : '-');

		// List state information.
		parent::populateState('a.lft', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 *
	 * @return	string		A store id.
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.extension');
		$id	.= ':'.$this->getState('filter.published');
		$id	.= ':'.$this->getState('filter.language');

		return parent::getStoreId($id);
	}

	/**
	 * @param	boolean	True to join selected foreign information
	 *
	 * @return	string
	 */
	function getListQuery($resolveFKs = true)
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id, a.title, a.alias, a.note, a.published, a.access' .
				', a.checked_out, a.checked_out_time, a.created_user_id' .
				', a.path, a.parent_id, a.level, a.lft, a.rgt' .
				', a.language'
			)
		);
		$query->from('#__categories AS a');

		// Join over the language
		$query->select('l.title AS language_title');
		$query->join('LEFT', '`#__languages` AS l ON l.lang_code = a.language');
		
		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

		// Join over the users for the author.
		$query->select('ua.name AS author_name');
		$query->join('LEFT', '#__users AS ua ON ua.id = a.created_user_id');

		// Filter by extension
		if ($extension = $this->getState('filter.extension')) {
			$query->where('a.extension = '.$db->quote($extension));
		}

		// Filter by access level.
		if ($access = $this->getState('filter.access')) {
			$query->where('a.access = ' . (int) $access);
		}

		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('a.published = ' . (int) $published);
		} else if ($published === '') {
			$query->where('(a.published IN (0, 1))');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else if (stripos($search, 'author:') === 0) {
				$search = $db->Quote('%'.$db->getEscaped(substr($search, 7), true).'%');
				$query->where('(ua.name LIKE '.$search.' OR ua.username LIKE '.$search.')');
			} else {
				$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
				$query->where('(a.title LIKE '.$search.' OR a.alias LIKE '.$search.' OR a.note LIKE '.$search.')');
			}
		}

		// Filter on the language.
		if (($language = $this->getState('filter.language'))!='-') {
			$query->where('a.language = '.$db->quote($language));
		}

		// Add the list ordering clause.
		$query->order($db->getEscaped($this->getState('list.ordering', 'a.title')).' '.$db->getEscaped($this->getState('list.direction', 'ASC')));

		//echo nl2br(str_replace('#__','jos_',$query));
		return $query;
	}
	/**
	 * Method to get the row form.
	 *
	 * @return	mixed	JForm object on success, false on failure.
	 */
	public function getForm() {

		// Initialise variables.
		$app = & JFactory::getApplication();

		// Get the form.
		jimport('joomla.form.form');
		JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
		JForm::addFieldPath(JPATH_COMPONENT . '/models/fields');
		$form = & JForm::getInstance($this->context.'.'.$this->getState('extension').'.'.$this->getState('section'), 'categories', array('control' => 'filters', 'event' => 'onPrepareForm'));

		// Check for an error.
		if (JError::isError($form)) {
			$this->setError($form->getMessage());
			return false;
		}

		// Check the session for previously entered form data.
		$data = $app->getUserState($this->context.'.data', array());
		// Bind the form data if present.
		if (!empty($data)) {
			$form->bind($data);
		}
		return $form;
	}
}
