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
/*
 * Mock classes
 */
// Include mocks here
/*
 * We now return to our regularly scheduled environment.
 */
require_once JPATH_LIBRARIES . '/joomla/import.php';
require_once JPATH_LIBRARIES . '/joomla/factory.php';

require_once JPATH_LIBRARIES . '/joomla/methods.php';

class JTextTest extends PHPUnit_Framework_TestCase
{
	var $instance = null;

	function setUp()
	{
		$this->instance = new JText;
	}

	function tearDown()
	{
		$this->instance = null;
		unset($this->instance);
	}

	function testJText()
	{
		$this->assertType('JText',$this->instance);
	}

	function test_()
	{
		$lang =& JFactory::getLanguage();
		$this->assertEquals($lang->_('Next'), $this->instance->_('Next'));

		$lang->setLanguage('es-ES');
		if($lang->load('es-ES')){
			$this->assertNotEquals('Next', $this->instance->_('Next'));
			$this->assertEquals($lang->_('Next'),$this->instance->_('Next'));
		}
	}

	function testSprintf()
	{
		// Remove the following line when you implement this test.
		return $this->markTestSkipped();
	}

	function testPrintf()
	{
		// Remove the following line when you implement this test.
		return $this->markTestSkipped();
	}
}