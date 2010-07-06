<?php
/**
 * @version		$Id$
 * @package		Repair
 * @subpackage	com_repair
 * @copyright	Copyright 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * About view.
 *
 * @package		Repair
 * @subpackage	com_repair
 * @since		1.0
 */
class RepairViewAbout extends JView
{
	/**
	 * Override the display method for the view.
	 *
	 * @return	void
	 * @since	1.0
	 */
	public function display()
	{
		$this->addToolbar();
		parent::display();
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return	void
	 * @since	1.0
	 */
	protected function addToolbar()
	{
		$canDo	= RepairHelper::getActions();

		// Add the view title.
		JToolBarHelper::title(JText::_('COM_REPAIR_ABOUT_TITLE'));

		// Check if the Options button can be added.
		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_repair');
		}
	}
}