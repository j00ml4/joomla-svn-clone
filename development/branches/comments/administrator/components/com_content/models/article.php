<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Article model.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_content
 */
class ContentModelArticle extends JModelAdmin
{
	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param	object	A record object.
	 * @return	boolean	True if allowed to delete the record. Defaults to the permission set in the component.
	 * @since	1.6
	 */
	protected function canDelete($record)
	{
		$user = JFactory::getUser();
		
		return $user->authorise('core.delete', 'com_content.article.'.(int) $record->id);
	}

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param	object	A record object.
	 * @return	boolean	True if allowed to change the state of the record. Defaults to the permission set in the component.
	 * @since	1.6
	 */
	protected function canEditState($record)
	{
		$user = JFactory::getUser();
		
		return $user->authorise('core.edit.state', 'com_content.article.'.(int) $record->id);
	}

	/**
	 * Returns a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	*/
	public function getTable($type = 'Content', $prefix = 'JTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 */
	public function &getItem($pk = null)
	{
		$value = parent::getItem($pk);

		// Convert the params field to an array.
		$registry = new JRegistry;
		$registry->loadJSON($value->attribs);
		$value->attribs = $registry->toArray();

		// Convert the params field to an array.
		$registry = new JRegistry;
		$registry->loadJSON($value->metadata);
		$value->metadata = $registry->toArray();

		$value->articletext = trim($value->fulltext) ? $value->introtext . "<hr id=\"system-readmore\" />" . $value->fulltext : $value->introtext;

		return $value;
	}

	/**
	 * Method to get the record form.
	 *
	 * @return	mixed	JForm object on success, false on failure.
	 * @since	1.6
	 */
	public function getForm()
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = parent::getForm('com_content.article', 'article', array('control' => 'jform'));

		// Check for an error.
		if (JError::isError($form)) {
			$this->setError($form->getMessage());
			return false;
		}

		// Determine correct permissions to check.
		if ($this->getState('article.id'))
		{
			// Existing record. Can only edit in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit');
		}
		else
		{
			// New record. Can only create in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.create');
		}

		// Check the session for previously entered form data.
		$data = $app->getUserState('com_content.edit.article.data', array());

		// Bind the form data if present.
		if (!empty($data)) {
			$form->bind($data);
		}

		return $form;
	}

	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param	object	A record object.
	 * @return	array	An array of conditions to add to add to ordering queries.
	 * @since	1.6
	 */
	protected function getReorderConditions($record = null)
	{
		$condition = array(
			'catid = '. (int) $record->catid
		);
		return $condition;
	}

	/**
	 * Method to toggle the featured setting of articles.
	 *
	 * @param	array	The ids of the items to toggle.
	 * @param	int		The value to toggle to.
	 *
	 * @return	boolean	True on success.
	 */
	function featured($pks, $value = 0)
	{
		// Sanitize the ids.
		$pks = (array) $pks;
		JArrayHelper::toInteger($pks);

		if (empty($pks)) {
			$this->setError(JText::_('JError_No_items_selected'));
			return false;
		}

		$table = $this->getTable('Featured', 'ContentTable');

		try
		{
			$this->_db->setQuery(
				'UPDATE #__content AS a' .
				' SET a.featured = '.(int) $value.
				' WHERE a.id IN ('.implode(',', $pks).')'
			);
			if (!$this->_db->query()) {
				throw new Exception($this->_db->getErrorMsg());
			}

			// Adjust the mapping table.
			if ($value == 0)
			{
				// Unfeaturing.
				$this->_db->setQuery(
					'DELETE FROM #__content_frontpage' .
					' WHERE content_id IN ('.implode(',', $pks).')'
				);
				if (!$this->_db->query()) {
					throw new Exception($this->_db->getErrorMsg());
				}
			}
			else
			{
				// Featuring.
				$tuples = array();
				foreach ($pks as $i => $pk) {
					$tuples[] = '('.$pk.', '.(int)($i + 1).')';
				}

				$this->_db->setQuery(
					'INSERT INTO #__content_frontpage (`content_id`, `ordering`)' .
					' VALUES '.implode(',', $tuples)
				);
				if (!$this->_db->query()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
			return false;
		}

		$table->reorder();

		$cache = JFactory::getCache('com_content');
		$cache->clean();

		return true;
	}
}
