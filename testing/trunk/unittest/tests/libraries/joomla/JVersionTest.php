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

require_once JPATH_LIBRARIES . '/joomla/version.php';

class JVersionTest extends PHPUnit_Framework_TestCase
{
	var $instance = null;

	function setUp()
	{
		$this->instance = new JVersion();
	}

	function tearDown()
	{
		$this->instance = null;
		unset($this->instance);
	}

	function testJVersionConstantIsMinorVersion()
	{
		$this->assertThat(
			JVERSION,
			$this->equalTo( $this->instance->RELEASE . '.' . $this->instance->DEV_LEVEL )
		);
	}

	function testGetLongVersion()
	{
		// @TODO: Do we really care about this test??
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
		$this->assertThat(
			$this->instance->getShortVersion(),
			$this->equalTo( $this->instance->RELEASE . '.' . $this->instance->DEV_LEVEL )
		);
	}

	function testHelpVersionIsNumericVersionWithoutDot()
	{
		$this->assertThat(
			$this->instance->getHelpVersion(),
			$this->equalTo( '.' . str_replace('.', '', $this->instance->RELEASE) )
		);
	}

	function testCurrentVersionIsCompatible()
	{
		$this->assertTrue(
			$this->instance->isCompatible(
				$this->instance->RELEASE . '.' . $this->instance->DEV_LEVEL
			)
		);
	}

	function testOlderVersionIsCompaitble()
	{
		$minor = '1.4.9';
		$this->assertThat(
			$this->instance->isCompatible( $minor ),
			$this->equalTo( true ),
			$minor . ' is not compatible with ' . $this->instance->getShortVersion()
		);
	}

	function testNewerVersionIsNotCompaitble()
	{
		$minor = $this->instance->RELEASE . '.' . ($this->instance->DEV_LEVEL + 1);
		$this->assertThat(
			$this->instance->isCompatible($minor),
			$this->equalTo( false ),
			$minor . ' is not compatible with ' . $this->instance->getShortVersion()
		);
	}

}
