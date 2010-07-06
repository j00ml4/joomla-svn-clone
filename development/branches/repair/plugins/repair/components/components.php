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

jimport('joomla.plugin.plugin');

/**
 * Repair Components Plugin.
 *
 * @package		Repair
 * @subpackage	plg_repair_components
 * @since		1.0
 */
class plgRepairComponents extends JPlugin
{
	/**
	 * Check integrity of component assets.
	 *
	 * @return	boolean	True if successful, false if not and a plugin error is set.
	 * @since	1.0
	 */
	public function onRepairRunTests()
	{
		// Initialise variables.
		$result	= array(
			// The test results.
			'tests' => array(),
			// Error or exception data.
			'edata' => array()
		);
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		try {
			// Prepare query to get the list of components.
			$query->select('element');
			$query->from('#__extensions');
			$query->where('type = '.$db->quote('component'));

			// Inject the query and load the result.
			$components = $db->setQuery($query)->loadResultArray();

			// Check for errors.
			if ($error = $db->getErrorMsg()) {
				throw new Exception($error);
			}

			// Prepare query to get the list of component assets.
			$query->clear();
			$query->select('name');
			$query->from('#__assets');
			$query->where('level = 1');

			// Inject the query and load the result.
			$assets = $db->setQuery($query)->loadResultArray();

			// Check for errors.
			if ($error = $db->getErrorMsg()) {
				throw new Exception($error);
			}

			$diff = array_diff($components, $assets);
			print_r($diff);

		} catch (Exception $e) {
		 	$this->_subject->setError($e->getMessage() /*JText::_('PLG_REPAIR_COMPONENTS_ERROR')*/);
		 	$result = false;
		}

		return $result;
	}
}