<?php
/**
 * @version		$Id: JRequestTest.php 10892 2008-09-03 00:45:00Z eddieajau $
 * @package		Joomla.UnitTest
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU General Public License
 */

/**
 * A unit test class for JURI
 */
class JURITest extends PHPUnit_Framework_TestCase
{
	/**
	 * Clear the cache and load sample data
	 */
	function setUp()
	{
		// Set up minimalist Joomla framework
		if (! defined('_JEXEC')) {
			define('_JEXEC', 1);
		}
		require_once JPATH_BASE.DS.'includes'.DS.'defines.php';
		require_once JPATH_LIBRARIES.DS.'joomla'.DS.'import.php';
		jimport( 'joomla.environment.uri' );
	}

	/**
	 * @group juri
	 */
	function testIsInternalFailsWithExternalAddress()
	{
		$this->assertThat(
			JURI::isInternal( 'http://www.foobar.com' ),
			$this->identicalTo( false )
		);
	}

}
