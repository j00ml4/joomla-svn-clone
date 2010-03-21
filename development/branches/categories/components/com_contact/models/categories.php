<?php
/**
 * @version		$Id: categories.php
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

/**
 * This models supports retrieving lists of contact categories.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_contact
 * @since		1.6
 */
class ContactModelCategories extends JModel
{
	/**
	 * Model context string.
	 *
	 * @var		string
	 */
	public $_context = 'com_contact.categories';

	/**
	 * The category context (allows other extensions to derived from this model).
	 *
	 * @var		string
	 */
	protected $_extension = 'com_contact';

	/**
	 * Method to auto-populate the model state.
	 *
	 * @since	1.6
	 */
	protected function _populateState()
	{
		$app = &JFactory::getApplication();

		$this->setState('filter.extension', $this->_extension);

		// Get the parent id if defined.
		$parentId = JRequest::getInt('id');
		$this->setState('filter.parentId', $parentId);

		$params = $app->getParams();
		$this->setState('params', $params);

		$this->setState('filter.published',	1);
		$this->setState('filter.access',	true);
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
	protected function _getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.extension');
		$id	.= ':'.$this->getState('filter.published');
		$id	.= ':'.$this->getState('filter.access');

		return parent::_getStoreId($id);
	}

	/**
	 * Return the Categories
	 * 
	 * @return array Array of JCategoryNode objects
	 */
	function getItems()
	{
		$options = array(
				'published', $this->getState('filter.published', 1),
				'access', $this->getState('filter.access', true)
				);
		$categories = JCategories::getInstance($this->getState('extension', 'com_contact'), $options);
		$category = $categories->get($this->getState('parentId', 'root'));
		return $category->getChildren();
	}
}
