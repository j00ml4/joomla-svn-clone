<?php
/**
 * @version		$Id$
 * @package		Joomla.UnitTest
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU General Public License
 */

/*
 * Now load the Joomla environment
 */
if (! defined('_JEXEC')) {
	define('_JEXEC', 1);
}
require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_LIBRARIES . '/joomla/import.php';

class JLoaderTest_Class extends PHPUnit_Framework_TestCase
{
	/**
	 * function import($filePath, $base = null, $key = null)
	 */
	function testImportAClass()
	{
		$r = JLoader::import('joomla.factory');
		$this->assertTrue($r);
	}

	/**
	 * function import($filePath, $base = test dir, $key = null)
	 */
	function testImportBase()
	{
		$testLib = 'joomla._testdata.loader-data';
		$this->assertFalse(defined('JUNIT_DATA_JLOADER'), 'Test set up failure.');
		$r = JLoader::import($testLib, dirname(__FILE__));
		if ($this->assertTrue($r)) {
			$this->assertTrue(defined('JUNIT_DATA_JLOADER'));
		}

		// retry
		$r = JLoader::import($testLib, dirname(__FILE__));
		$this->assertTrue($r);
	}

	/**
	 * function import($filePath, $base = null, $key = null)
	 */
	function testImportKey()
	{
		// Remove the following line when you implement this test.
		return $this->markTestSkipped();
	}

	/**
	 * function &factory($class, $options=null)
	 */
	function testFactory()
	{
		// Remove the following line when you implement this test.
		return $this->markTestSkipped();
	}
}