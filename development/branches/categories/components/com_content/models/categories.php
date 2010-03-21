<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

/**
 * This models supports retrieving lists of article categories.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_content
 * @since		1.6
 */
class ContentModelCategories extends JModel
{
	/**
	 * Model context string.
	 *
	 * @var		string
	 */
	public $_context = 'com_content.categories';

	/**
	 * The category context (allows other extensions to derived from this model).
	 *
	 * @var		string
	 */
	protected $_extension = 'com_content';

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
		$this->setState('filter.parent_id', $parentId);

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
		$id	.= ':'.$this->getState('filter.parent_id');

		return parent::_getStoreId($id);
	}

	/**
	 * Get the categories
	 *
	 * @return mixed An array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$categories = JCategories::getInstance('com_content');
		if((int)$this->getState('filter.parent_id') > 0)
		{
			$temp = $categories->get($this->getState('filter.parent_id'));
		} else {
			$temp = $categories->get('root');
		}
		if($temp instanceof JCategoryNode)
		{
			return $temp->getChildren();
		}
		return false;
	}
	
	
	
}
