<?php
/**
 * Joomla! v1.5 Unit Test Facility
 *
 * @package Joomla
 * @subpackage UnitTest
 * @copyright Copyright (C) 2005 - 2008 Open Source Matters, Inc.
 * @version $Id: $
 *
 */

/**
 * Mock of JPluginHelper for unit testing
 */
class JPluginHelper
{
	/**
	 * Information on the calls expected to the mock object.
	 *
	 * This array is indexed by a hash of the type and plugin; each element is
	 * an array containing the source, type, expected response and number of
	 * expected calls.
	 */
	static private $_expectations = array();

	/**
	 * Stub for the getPlugin method.
	 *
	 * @param string The plugin type, relates to the sub-directory in the
	 * plugins directory
	 * @param string The plugin name
	 * @return mixed An array of plugin data objects, or a plugin data object
	 */
	function &getPlugin($type, $plugin = null)
	{
		$hash = md5($type . '|' . $plugin);
		if (! isset(self::$_expectations[$hash])) {
			self::$_expectations[$hash] = array(
				'plugin' => $plugin,
				'type' => $type,
				'result' => null,
				'count' => 0,
			);
		}
		--self::$_expectations[$hash]['count'];
		return self::$_expectations[$hash]['result'];
	}

	/**
	* Loads all the plugin files for a particular type if no specific plugin is specified
	* otherwise only the specific pugin is loaded.
	*
	* @access public
	* @param string     $type   The plugin type, relates to the sub-directory in the plugins directory
	* @param string     $plugin The plugin name
	* @return boolean True if success
	*/
	function importPlugin($type, $plugin = null, $autocreate = true, $dispatcher = null)
	{
		throw new Exception(
			'Sorry, Mock ' . __CLASS__ . ' does not support method ' . __FUNCTION__
		);
	}

	/**
	 * Checks if a plugin is enabled
	 *
	 * @access  public
	 * @param string    $type   The plugin type, relates to the sub-directory in the plugins directory
	 * @param string    $plugin The plugin name
	 * @return  boolean
	 */
	function isEnabled( $type, $plugin = null )
	{
		$result = &JPluginHelper::getPlugin( $type, $plugin);
		return (!empty($result));
	}

	function mockReset() {
		self::$_expectations = array();
	}

	/**
	 * Define an expectation.
	 *
	 * @param string The plugin type.
	 * @param string The plugin name, can be null.
	 * @param mixed The result
	 */
	function mockSetUp($type, $plugin, $result, $count = 1) {
		$hash = md5($type . '|' . $plugin);
		self::$_expectations[$hash] = array(
			'plugin' => $plugin,
			'type' => $type,
			'result' => $result,
			'count' => $count
		);
	}

	/**
	 * Report post-test results.
	 *
	 * Returns all expectations that were not met, or true if all expectations
	 * were met.
	 */
	function mockTearDown() {
		foreach (self::$_expectations as $hash => $info) {
			if (! $info['count']) {
				unset(self::$_expectations[$hash]);
			}
		}
		if (count(self::$_expectations)) {
			return self::$_expectations;
		}
		return true;
	}

}
