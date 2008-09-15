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

/**
 * @package		Joomla.UnitTest
 * @subpackage	framework.mail
 */
class JMailHelperTest extends PHPUnit_Framework_TestCase
{
	function setUp()
	{
		// Set up minimalist Joomla framework
		require_once JPATH_BASE.DS.'includes'.DS.'defines.php';
		//require_once JPATH_LIBRARIES.DS.'joomla'.DS.'import.php';
		//require_once JPATH_CONFIGURATION.DS.'configuration.php';

		// Loading the UserModelReset class
		require_once JPATH_BASE.DS.'libraries'.DS.'joomla'.DS.'mail'.DS.'helper.php';
	}

	function tearDown()
	{
	}

	/**
	 * @group	framework.mail
	 * @dataProvider	getCleanLineData
	 */
	public function testCleanLine( $input, $expected )
	{
		$this->assertThat(
			JMailHelper::cleanLine( $input ),
			$this->equalTo( $expected )
		);
	}

	/**
	 * @group	framework.mail
	 * @dataProvider	getCleanTextData
	 */
	public function testCleanText( $input, $expected )
	{
		$this->assertThat(
			JMailHelper::cleanText( $input ),
			$this->equalTo( $expected )
		);
	}

	/**
	 * @group	framework.mail
	 * @dataProvider	getCleanBodyData
	 */
	public function testCleanBody( $input, $expected )
	{
		$this->assertThat(
			JMailHelper::cleanBody( $input ),
			$this->equalTo( $expected )
		);
	}

	/**
	 * @group	framework.mail
	 * @todo	Implement cleanSubject() test(s).
	 */
	public function testCleanSubject()
	{
		// Remove the following line when you implement this method.
		$this->markTestIncomplete(
			'This test for cleanSubject has not been implemented yet.'
		);
	}

	/**
	 * @group	framework.mail
	 * @todo	Implement cleanAddress() test(s).
	 */
	public function testCleanAddress()
	{
		// Remove the following line when you implement this method.
		$this->markTestIncomplete(
			'This test for cleanAddress has not been implemented yet.'
		);
	}

	/**
	 * @group	framework.mail
	 * @todo	Implement isEmailAddress() test(s).
	 */
	public function testIsEmailAddress()
	{
		// Remove the following line when you implement this method.
		$this->markTestIncomplete(
			'This test for isEmailAddress has not been implemented yet.'
		);
	}

	//
	// Data Providers
	//

	/**
	 * Test data for cleanLine method
	 *
	 * @return array
	 */
	static public function getCleanLineData()
	{
		return array(
			array( "test\n\nme\r\r", 'testme' ),
			array( "test%0Ame", 'testme' ),
			array( "test%0Dme", 'testme' ),
		);
	}

	/**
	 * Test data for cleanText method
	 *
	 * @return array
	 */
	static public function getCleanTextData()
	{
		return array(
			array( "test\nme", "test\nme" ),

			array( "test%0AconTenT-Type:me", 'testme' ),
			array( "test%0Dcontent-type:me", 'testme' ),
			array( "test\ncontent-type:me", 'testme' ),
			array( "test\n\ncontent-type:me", 'testme' ),
			array( "test\rcontent-type:me", 'testme' ),
			array( "test\r\rcontent-type:me", 'testme' ),
			// @TODO Should this be included array( "test\r\ncoNTent-tYPe:me", 'testme' ),

			array( "test%0Ato:me", 'testme' ),
			array( "test%0DTO:me", 'testme' ),
			array( "test\nTo:me", 'testme' ),
			array( "test\n\ntO:me", 'testme' ),
			array( "test\rto:me", 'testme' ),
			array( "test\r\rto:me", 'testme' ),
			// @TODO Should this be included array( "test\r\nto:me", 'testme' ),

			array( "test%0Acc:me", 'testme' ),
			array( "test%0DCC:me", 'testme' ),
			array( "test\nCc:me", 'testme' ),
			array( "test\n\ncC:me", 'testme' ),
			array( "test\rcc:me", 'testme' ),
			array( "test\r\rcc:me", 'testme' ),
			// @TODO Should this be included array( "test\r\ncc:me", 'testme' ),

			array( "test%0Abcc:me", 'testme' ),
			array( "test%0DBCC:me", 'testme' ),
			array( "test\nBCc:me", 'testme' ),
			array( "test\n\nbcC:me", 'testme' ),
			array( "test\rbcc:me", 'testme' ),
			array( "test\r\rbcc:me", 'testme' ),
			// @TODO Should this be included array( "test\r\nbcc:me", 'testme' ),
		);
	}

	/**
	 * Test data for cleanBody method
	 *
	 * @return array
	 */
	static public function getCleanBodyData()
	{
		return array(
			array( "testFrom: Foobar me", "test me" ),
			array( "testfrom: Foobar me", "testfrom: Foobar me" ),	// @TODO should this be case sensitive
		);
	}


}