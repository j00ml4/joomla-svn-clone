<?php
/**
 * @version		$Id$
 * @package		Joomla.UnitTest
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU General Public License
 */

/**
 * @package		Joomla.UnitTest
 * @subpackage	com_user
 */
class UserModelResetTest extends PHPUnit_Framework_TestCase
{
	function setUp()
	{
		// Set up minimalist Joomla framework
		if (! defined('_JEXEC')) {
			define('_JEXEC', 1);
		}
		require_once JPATH_BASE.DS.'includes'.DS.'defines.php';
		require_once JPATH_LIBRARIES.DS.'joomla'.DS.'import.php';
		require_once JPATH_CONFIGURATION.DS.'configuration.php';

		// Create the JConfig object and load it in the registry
		$config = new JConfig();
		$registry =& JFactory::getConfig();
		$registry->loadObject($config);

		// Loading the UserModelReset class
		require_once JPATH_BASE.DS.'components'.DS.'com_user'.DS.'models'.DS.'reset.php';

		// Add some temporary (incomplete) users
		$db = &JFactory::getDBO();
		$db->setQuery(
			'INSERT IGNORE INTO #__users (id, block, activation) VALUES ' .
			'(5,0,'.$db->Quote( '12345678901234567890123456789012' ).'),' .
			'(6,1,'.$db->Quote( '12345678901234567890123456789099' ).')'
		);
		if (!$db->query()) {
			$this->fail( $db->getyErrorMsg() );
		}

		//
		// Mock $mainframe
		// @TODO: The following method will not cater for $app = JFactory::getApplication
		//
		global $mainframe;
		$mainframe = $this->getMock( 'JApplication', array( 'setUserState' ) );
	}

	function tearDown()
	{
		// Remove temporary users
		$db = &JFactory::getDBO();
		$db->setQuery(
			'DELETE FROM #__users' .
			' WHERE id IN (5,6)'
		);
		if (!$db->query()) {
			$this->fail( $db->getyErrorMsg() );
		}

		// Cleanup variables
		global $mainframe;
		unset( $mainframe );
	}

	/**
	 * @todo	Implement RequestReset() test(s).
	 * @group	com_user
	 */
	public function testRequestReset()
	{
		// Remove the following line when you implement this method.
		$this->markTestIncomplete(
			'This test for RequestReset has not been implemented yet.'
		);
	}

	/**
	 * @todo	Implement CompleteReset() test(s).
	 * @group	com_user
	 */
	public function testCompleteReset()
	{
		// Remove the following line when you implement this method.
		$this->markTestIncomplete(
			'This test for CompleteReset has not been implemented yet.'
		);
	}

	/**
	 * @todo	Implement _sendConfirmationMail() test(s).
	 * @group	com_user
	 */
	public function test_sendConfirmationMail()
	{
		// Remove the following line when you implement this method.
		$this->markTestIncomplete(
			'This test for _sendConfirmationMail has not been implemented yet.'
		);
	}

	/**
	 * @param	string	The token to confirm reset
	 * @param	mixed	The expected result
	 * @group	com_user
	 */
	function testConfirmresetWithEmptyTokenFails()
	{
		$model = new UserModelReset;
		$this->assertThat(
			$model->confirmReset( '' ),
			$this->equalTo( false )
		);
	}

	/**
	 * @param	string	The token to confirm reset
	 * @param	mixed	The expected result
	 * @group	com_user
	 */
	function testConfirmresetWithBogusAuthenicationKeyFails()
	{
		$db = &JFactory::getDBO();

		$model = new UserModelReset;
		$this->assertThat(
			$model->confirmReset( '00000000000000000000000000000000' ),
			$this->equalTo( false )
		);
	}

	/**
	 * @param	string	The token to confirm reset
	 * @param	mixed	The expected result
	 * @group	com_user
	 */
	function testConfirmresetWithValidAuthenicationKeyButBlockedUserFails()
	{
		$db = &JFactory::getDBO();

		$model = new UserModelReset;
		$this->assertThat(
			$model->confirmReset( '12345678901234567890123456789099' ),
			$this->equalTo( false )
		);
	}

	/**
	 * @param	string	The token to confirm reset
	 * @param	mixed	The expected result
	 * @group	com_user
	 */
	function testConfirmresetWithValidAuthenicationKeyPasses()
	{
		$db = &JFactory::getDBO();

		$model = new UserModelReset;
		$this->assertThat(
			$model->confirmReset( '12345678901234567890123456789012' ),
			$this->equalTo( true )
		);
	}
}