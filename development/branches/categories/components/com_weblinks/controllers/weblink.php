<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * @package		Joomla.Site
 * @subpackage	com_weblinks
 */
class WeblinksControllerWeblink extends JControllerForm
{
	protected $_context = 'com_weblinks.edit.weblink';
	/**
	 * Constructor
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('apply',		'save');
		$this->registerTask('save2new',		'save');
		$this->registerTask('save2copy',	'save');
	}
	protected $_view_item = 'form';

	protected $_view_list = 'categories';

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param	string	The model name. Optional.
	 * @param	string	The class prefix. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	object	The model.
	 * @since	1.5
	 */
	public function &getModel($name = 'form', $prefix = '', $config = array())
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	/**
	 * Go to a weblink
	 */
	public function go()
	{
		// Get the ID from the request
		$id		= JRequest::getInt('id');

		// Get the model, requiring published items
		$modelLink	= &$this->getModel('Weblink', '', array('ignore_request' => true));
		$modelLink->setState('filter.published', 1);

		// Get the item
		$link	= &$modelLink->getItem($id);

		// Make sure the item was found.
		if (empty($link)) {
			return JError::raiseWarning(404, JText::_('COM_WEBLINKS_ERROR_WEBLINK_NOT_FOUND'));
		}

		// Check whether item access level allows access.
		$user	= &JFactory::getUser();
		$groups	= $user->authorisedLevels();
		if (!in_array($link->access, $groups)) {
			return JError::raiseError(403, JText::_("JERROR_ALERTNOAUTHOR"));
		}

		// Check whether category access level allows access.
		$modelCat = &$this->getModel('Category', 'WeblinksModel', array('ignore_request' => true));
		$modelCat->setState('filter.published', 1);

		// Get the category
		$category = &$modelCat->getCategory($link->catid);

		// Make sure the category was found.
		if (empty($category)) {
			return JError::raiseWarning(404, JText::_('COM_WEBLINKS_ERROR_WEBLINK_NOT_FOUND'));
		}

		// Check whether item access level allows access.
		if (!in_array($category->access, $groups)) {
			return JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
		}

		// Redirect to the URL
		if ($link->url)
		{
			$modelLink->hit($id);
			JFactory::getApplication()->redirect($link->url);
		}
		else {
			return JError::raiseWarning(404, JText::_('COM_WEBLINKS_ERROR_WEBLINK_URL_INVALID'));
		}
	}
}