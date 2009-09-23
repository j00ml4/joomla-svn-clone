<?php
/**
 * @version		$Id: JRequestTest.php 10892 2008-09-03 00:45:00Z eddieajau $
 * @package		Joomla.UnitTest
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU General Public License
 */

class JURITest extends PHPUnit_Framework_TestCase
{
	/**
	 * Clear the cache and load sample data
	 */
	public function setUp()
	{
		jimport( 'joomla.environment.uri' );
	}

	/**
	 * @group juri
	 */
	public function testIsInternalFailsWithExternalAddress()
	{
		//$uri = JURI::getInstance();
		//$this->assertThat(
			//JURI::isInternal( 'http://www.foobar.com' ),
			//$this->identicalTo( false )
		//);
	}

}
