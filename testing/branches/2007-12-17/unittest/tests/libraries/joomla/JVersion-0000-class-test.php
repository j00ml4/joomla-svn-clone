<?php

// $Id$

// Call main() if this source file is executed directly.
if (!defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'JVersionTest::main');
	$JUnit_home = DIRECTORY_SEPARATOR . 'unittest' . DIRECTORY_SEPARATOR;
	if (($JUnit_posn = strpos(__FILE__, $JUnit_home)) === false) {
		die('Unable to find ' . $JUnit_home . ' in path.');
	}
	$JUnit_posn += strlen($JUnit_home) - 1;
	$JUnit_root = substr(__FILE__, 0, $JUnit_posn);
	$JUnit_start = substr(
		__FILE__,
		$JUnit_posn + 1,
		strlen(__FILE__) - strlen(basename(__FILE__)) - $JUnit_posn - 2
	);
	require_once $JUnit_root . DIRECTORY_SEPARATOR . 'setup.php';
}

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
