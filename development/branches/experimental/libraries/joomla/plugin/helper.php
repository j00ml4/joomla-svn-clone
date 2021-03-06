<?php
/**
 * @version		$Id$
 * @package		Joomla.Framework
 * @subpackage	Plugin
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 */

// No direct access
defined('JPATH_BASE') or die;

/**
 * Plugin helper class
 *
 * @static
 * @package		Joomla.Framework
 * @subpackage	Plugin
 * @since		1.5
 */
abstract class JPluginHelper
{
	/**
	 * Get the plugin data of a specific type if no specific plugin is specified
	 * otherwise only the specific plugin data is returned
	 *
	 * @access public
	 * @param string 	$type 	The plugin type, relates to the sub-directory in the plugins directory
	 * @param string 	$plugin	The plugin name
	 * @return mixed 	An array of plugin data objects, or a plugin data object
	 */
	public static function &getPlugin($type, $plugin = null)
	{
		$result = array();

		$plugins = JPluginHelper::_load();

		$total = count($plugins);
		for ($i = 0; $i < $total; $i++)
		{
			if (is_null($plugin))
			{
				if ($plugins[$i]->type == $type) {
					$result[] = $plugins[$i];
				}
			}
			else
			{
				if ($plugins[$i]->type == $type && $plugins[$i]->name == $plugin) {
					$result = $plugins[$i];
					break;
				}
			}

		}

		return $result;
	}

	/**
	 * Checks if a plugin is enabled
	 *
	 * @access	public
	 * @param string 	$type 	The plugin type, relates to the sub-directory in the plugins directory
	 * @param string 	$plugin	The plugin name
	 * @return	boolean
	 */
	public static function isEnabled($type, $plugin = null)
	{
		$result = &JPluginHelper::getPlugin($type, $plugin);
		return (!empty($result));
	}

	/**
	* Loads all the plugin files for a particular type if no specific plugin is specified
	* otherwise only the specific pugin is loaded.
	*
	* @access public
	* @param string 	$type 	The plugin type, relates to the sub-directory in the plugins directory
	* @param string 	$plugin	The plugin name
	* @return boolean True if success
	*/
	public static function importPlugin($type, $plugin = null, $autocreate = true, $dispatcher = null)
	{
		$result = false;

		$plugins = JPluginHelper::_load();

		$total = count($plugins);
		for ($i = 0; $i < $total; $i++) {
			if ($plugins[$i]->type == $type && ($plugins[$i]->name == $plugin ||  $plugin === null)) {
				JPluginHelper::_import($plugins[$i], $autocreate, $dispatcher);
				$result = true;
			}
		}

		return $result;
	}

	/**
	 * Loads the plugin file
	 *
	 * @access private
	 * @return boolean True if success
	 */
	protected static function _import(&$plugin, $autocreate = true, $dispatcher = null)
	{
		static $paths;

		if (!$paths) {
			$paths = array();
		}

		$result	= false;
		$plugin->type = preg_replace('/[^A-Z0-9_\.-]/i', '', $plugin->type);
		$plugin->name  = preg_replace('/[^A-Z0-9_\.-]/i', '', $plugin->name);

		$legacypath	= JPATH_PLUGINS.DS.$plugin->type.DS.$plugin->name.'.php';
		$path = JPATH_PLUGINS.DS.$plugin->type.DS.$plugin->name.DS.$plugin->name.'.php';

		if (!isset($paths[$path]) || !isset($paths[$legacypath]))
		{
			if (file_exists($path) || file_exists($legacypath))
			{
				$path = file_exists($path) ? $path : $legacypath;
				//needed for backwards compatibility
				// @todo if legacy ...
				$mainframe = JFactory::getApplication();

				jimport('joomla.plugin.plugin');
				require_once $path;
				$paths[$path] = true;

				if ($autocreate)
				{
					// Makes sure we have an event dispatcher
					if (!is_object($dispatcher)) {
						$dispatcher = & JDispatcher::getInstance();
					}

					$className = 'plg'.$plugin->type.$plugin->name;
					if (class_exists($className))
					{
						// load plugin parameters
						$plugin = &JPluginHelper::getPlugin($plugin->type, $plugin->name);

						// create the plugin
						$instance = new $className($dispatcher, (array)($plugin));
					}
				}
			}
			else
			{
				$paths[$path] = false;
			}
		}
	}

	/**
	 * Loads the published plugins
	 *
	 * @access private
	 */
	protected static function _load()
	{
		static $plugins;

		if (isset($plugins)) {
			return $plugins;
		}

		$db		= &JFactory::getDbo();
		$user	= &JFactory::getUser();

		if (isset($user))
		{
			$aid = $user->get('aid', 0);

			$query = 'SELECT folder AS type, element AS name, params'
				. ' FROM #__extensions'
				. ' WHERE enabled >= 1'
				. ' AND type = "plugin"'
				. ' AND state >= 0'
				. ' AND access IN ('.implode(',', $user->authorisedLevels('core.plugins.view')).')'
				. ' ORDER BY ordering';
		}
		else
		{
			$query = 'SELECT folder AS type, element AS name, params'
				. ' FROM #__extensions'
				. ' WHERE enabled >= 1'
				. ' AND type = "plugin"'
				. ' AND state >= 0'
				. ' ORDER BY ordering';
		}

		$db->setQuery($query);

		try {
			$plugins = $db->loadObjectList();
		} catch (JException $e) {
			throw new JException('Error loading plugins', 0, E_WARNING, $e);
		}

		return $plugins;
	}

}
