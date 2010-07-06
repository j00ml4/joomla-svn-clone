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
 * Repair view.
 *
 * @package		Repair
 * @subpackage	com_repair
 * @since		1.0
 */
class RepairViewTests extends JView
{
	/**
	 * @var		array	The array of records to display in the list.
	 * @since	1.0
	 */
	protected $tests;

	/**
	 * @var		JObject	The model state.
	 * @since	1.0
	 */
	protected $state;

	/**
	 * Prepare and display the Tests view.
	 *
	 * @return	void
	 * @since	1.0
	 */
	public function display()
	{
		// Initialise variables.
		$this->tests		= $this->get('Tests');
		$this->state		= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		parent::display();
	}
}