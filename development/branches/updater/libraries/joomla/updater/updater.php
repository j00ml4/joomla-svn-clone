<?php
/**
 * @version     $Id$
 * @package     Joomla.Framework
 * @subpackage  Updater
 * @copyright   Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.base.adapter');

/**
 * Updater Class for the Joomla Framework.
 *
 * @package     Joomla.Framework
 * @subpackage  Updater
 * @since       1.6
 */
class JUpdater extends JAdapter
{
	/**
	 * @var  JUpdater  The global updater object.
	 */
	protected static $instance;

	/**
	 * Method to instantiate the updater object.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public function __construct()
	{
		// Set the adapter base path and class prefix.
		parent::__construct(dirname(__FILE__),'JUpdater');
	}

	/**
	 * Multi-dimensional array safe method to get only unique values for an array.
	 *
	 * @param   array  Input array.
	 *
	 * @return  array  Output array with only unique values from the input.
	 *
	 * @see     http://php.net/manual/en/function.array-unique.php
	 * @since   1.6
	 */
	public static function arrayUnique($arr)
	{
		// If not an array just return.
		if (!is_array($arr)) {
			return $arr;
		}

		// Serialize all array values.
		$arr = array_map('serialize', $arr);

		// Get the unique elements in a serialized state.
		$arr = array_unique($arr);

		// Unserialize all array values.
		$arr = array_map('unserialize', $arr);

		return $arr;
	}

	/**
	 * Method to return a reference to the global updater object.  The object is only created if it
	 * does not already exist.
	 *
	 * @return  JUpdater  A reference to the global updater object.
	 *
	 * @since   1.6
	 */
	public static function & getInstance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new JUpdater();
		}

		return self::$instance;
	}

	/**
	 * Method to find any available updates for a set of extensions.  If no specific extensions are
	 * given then find updates for all extensions.
	 *
	 * @param   array    An array of extension ids for which to find updates.
	 *
	 * @return  boolean  True if there are updates to process.
	 *
	 * @since   1.6
	 */
	public function findUpdates($eid = null)
	{
		// Initialize variables.
		$db = & JFactory::getDBO();
		$found = false;

		// Get a new query object.
		$query = $db->getQuery(true);
		$query->select('DISTINCT update_site_id, type, location');
		$query->from('#__update_sites');

		// If no values are given get all of them.
		if (empty($eid)) {
			$query->where('enabled = 1');
		}
		else {
			// Sanitize the array as integers.
			JArrayHelper::toInteger((array) $eid);

			// Build the requisite subquery.
			$subQuery = $db->getQuery(true);
			$subQuery->select('update_site_id');
			$subQuery->from('#__update_sites_extensions');
			$subQuery->where('extension_id IN ('.implode(',',$eid).')');

			// Add the subquery.
			$query->where('update_site_id IN ('.(string) $subQuery.')');
		}

		// Get the sites to check from the database.
		$db->setQuery($query);
		$sites = $db->loadObjectList();

		foreach ($sites as $site)
		{
			// Attempt to set the adapter for the update site type.
			$this->setAdapter($site->type);

			// Ignore any sites for which we have no installed adapters.
			if(!isset($this->_adapters[$site->type])) {
				continue;
			}

			// Get the available updates from the adapter.
			$updates = (array) $this->_adapters[$site->type]->findUpdate($site);

			if(!empty($updates))
			{
				foreach ($updates as $update)
				{
					// Get table objects for update and extension.
					$uRow = JTable::getInstance('update');
					$eRow = JTable::getInstance('extension');

					$updateId = $uRow->find(array(
						'element'   => strtolower($update->get('element')),
						'type'      => strtolower($update->get('type')),
						'client_id' => strtolower($update->get('client_id')),
						'folder'    => strtolower($update->get('folder'))
					));

					$extensionId = $eRow->find(array(
						'element'   => strtolower($update->get('element')),
						'type'      => strtolower($update->get('type')),
						'client_id' => strtolower($update->get('client_id')),
						'folder'    => strtolower($update->get('folder'))
					));

					// If there is no existing update id for the update, check and see if we need to
					// add one based on installed version, etc.
					if(!$updateId)
					{
						// Check to see if we have an installed extension for this update.
						if($extensionId)
						{
							// Make sure that the update is newer than the installed version.
							$eRow->load($extensionId);
							$data = unserialize($eRow->manifest_cache);
							if(version_compare($update->version, $data['version'], '>') == 1) {
								$uRow->extension_id = $extensionId;
								$uRow->store();
								$found = true;
							}
						}
						// No extension found for this update, potentially new to be installed.
						else {
							$uRow->store();
							$found = true;
						}
					}
					// An update is already in the queue for this extension.
					else
					{
						// if there is an update, check that the version is newer then replaces
						$uRow->load($updateId);
						if(version_compare($update->version, $uRow->version, '>') == 1) {
							$uRow->store();
							$found = true;
						}
					}
				}
			}
		}

		return $found;
	}
}
