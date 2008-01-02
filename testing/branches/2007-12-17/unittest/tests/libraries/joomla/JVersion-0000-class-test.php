<?php

// $Id$

// Call JVersionTest::main() if this source file is executed directly.
if (!defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'JVersionTest::main');
	$JUNIT_ROOT = substr(__FILE__, 0, strpos(__FILE__, DIRECTORY_SEPARATOR.'unittest'));
	require_once($JUNIT_ROOT.'/unittest/setup.php');
}

require_once JPATH_LIBRARIES . '/joomla/version.php';

class JVersionTest extends PHPUnit_Framework_TestCase
{
	var $instance = null;

	/**
	 * Runs the test methods of this class.
	 *
	 * @access public
	 * @static
	 */
	function main() {
		require_once 'PHPUnit/TextUI/TestRunner.php';

		$suite  = new PHPUnit_Framework_TestSuite(__CLASS__);
		$result = PHPUnit_TextUI_TestRunner::run($suite);
	}

	function setUp()
	{
		$this->instance = new JVersion();
	}

	function tearDown()
	{
		$this->instance = null;
		unset($this->instance);
	}

	function testJVERSION()
	{
		$this->assertEquals(
			JVERSION,
			$this->instance->RELEASE . '.' . $this->instance->DEV_LEVEL
		);
	}

	function testGetLongVersion()
	{
		$version = $this->instance->PRODUCT
			. ' ' . $this->instance->RELEASE
			. '.' . $this->instance->DEV_LEVEL
			. ' ' . $this->instance->DEV_STATUS
			. ' [ ' . $this->instance->CODENAME . ' ]'
			. ' ' . $this->instance->RELDATE
			. ' ' . $this->instance->RELTIME
			. ' ' . $this->instance->RELTZ;
		$this->assertEquals($this->instance->getLongVersion(), $version);
	}

	function testGetShortVersion()
	{
		$this->assertEquals(
			$this->instance->getShortVersion(),
			$this->instance->RELEASE . '.' . $this->instance->DEV_LEVEL
		);
	}

	function testGetHelpVersion()
	{
		$this->assertEquals(
			$this->instance->getHelpVersion(),
			'.' . str_replace('.', '', $this->instance->RELEASE)
		);
	}

	function testIsCompatible()
	{
		$this->assertTrue(
			$this->instance->isCompatible(
				$this->instance->RELEASE . '.' . $this->instance->DEV_LEVEL
			)
		);
	}

	/*
	 * how do you define compatibility?
	 * will 1.5.1 be incompatible with 1.5.0 ?
	 */
	function testIsCompatible_minor()
	{
		$minor = $this->instance->RELEASE . '.' . ($this->instance->DEV_LEVEL + 1);
		$this->assertTrue(
			! $this->instance->isCompatible($minor),
			$minor . ' not compatible to ' . JVERSION . chr(10) . ' %s'
		);
		if ($this->instance->DEV_LEVEL) {
			$minor = $this->instance->RELEASE . '.' . ($this->instance->DEV_LEVEL - 1);
			$this->assertTrue(
				$this->instance->isCompatible($minor),
				$minor . ' not compatible to ' . JVERSION . chr(10) . ' %s'
			);
		}
	}

}

// Call JVersionTest::main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'JVersionTest::main') {
	JVersionTest::main();
}
