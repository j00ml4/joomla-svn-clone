<?php
/**
 * @version		$Id: view.html.php 12447 2009-07-05 03:43:03Z eddieajau $
 * @package		Joomla.Administrator
 * @subpackage	com_config
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * @package		Joomla.Administrator
 * @subpackage	com_config
 */
class ConfigViewComponent extends JView
{
	/**
	 * Display the view
	 */
	function display()
	{
		$form		= $this->get('Form');
		$component	= $this->get('Component');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Bind the form to the data.
		if ($form && $component->params) {
			$form->bind($component->params);
		}

		$this->assignRef('form',		$form);
		$this->assignRef('component',	$component);

		$this->document->setTitle(JText::_('Edit Preferences'));

		parent::display();
		JRequest::setVar('hidemainmenu', true);
	}
}