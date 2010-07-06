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

jimport('joomla.application.component.model');

/**
 * Tests model.
 *
 * @package		Repair
 * @subpackage	com_repair
 * @since		1.0
 */
class RepairModelTests extends JModel
{
	/**
	 * Method to get the test data.
	 *
	 * @return	array	A reference to an array of test results or false if an error occurred.
	 * @since	1.0
	 */
	public function &getTests()
	{
		$result = array();

		// Get the plugin dispatcher and load the plugin group
		$dispatcher	= JDispatcher::getInstance();
		JPluginHelper::importPlugin('repair');

		// Trigger the event.
		$results = $dispatcher->trigger('onRepairRunTests');

		// Check for errors encountered.
		if (count($results) && in_array(false, $results, true)) {
			$this->setError($dispatcher->getError());
			$result = false;
		}

		// The results come back as a array of arrays. Flatten to one array.
		foreach ($results as $array) {
			$result[] = $array;
		}

		return $result;
	}
}