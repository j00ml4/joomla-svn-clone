<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * @package		Joomla.Administrator
 * @subpackage	com_content
 */
class ContentViewFeatured extends JView
{
	protected $state;
	protected $items;
	protected $pagination;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$state		= $this->get('State');
		$items		= $this->get('Items');
		$pagination	= $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->assignRef('state',		$state);
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);

		$this->_setToolbar();
		parent::display($tpl);
	}

	/**
	 * Display the toolbar
	 *
	 * @access	private
	 */
	protected function _setToolbar()
	{
		$state = $this->get('State');
		JToolBarHelper::title(JText::_('Content_Featured_Title'), 'frontpage.png');

		JToolBarHelper::custom('article.edit', 'new.png', 'new_f2.png', 'New', false);
		JToolBarHelper::custom('article.edit', 'edit.png', 'edit_f2.png', 'Edit', true);
		JToolBarHelper::divider();
		JToolBarHelper::custom('featured.publish', 'publish.png', 'publish_f2.png', 'Publish', true);
		JToolBarHelper::custom('featured.unpublish', 'unpublish.png', 'unpublish_f2.png', 'Unpublish', true);
		if ($state->get('filter.published') != -1) {
			JToolBarHelper::archiveList('featured.archive');
		}
		JToolBarHelper::custom('featured.delete','delete.png','delete_f2.png','JToolbar_Remove', true);
		JToolBarHelper::divider();
		JToolBarHelper::preferences('com_content');
		JToolBarHelper::divider();
		JToolBarHelper::help('screen.content.featured');
	}
}