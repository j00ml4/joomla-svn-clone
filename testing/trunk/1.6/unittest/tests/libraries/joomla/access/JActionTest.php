<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

class JActionTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		jimport('joomla.access.action');
	}

	public function testConstructor()
	{
		$array = array(
			-42	=> 1,
			2	=> 1,
			3	=> 0
		);

		// Get the string representation.
		$string		= json_encode($array);

		// Test constructor with array.
		$action1	= new JAction($array);

		// Check that import equals export.
		$this->assertEquals(
			$string,
			(string) $action1
		);

		// Test constructor with string.
		$action2	= new JAction($string);

		// Check that import equals export.
		$this->assertEquals(
			$string,
			(string) $action1
		);
	}

	public function testMergeIdentity()
	{
		// Construct an action with no identities.
		$action = new JAction('');

		// Add the identity with allow.
		$action->mergeIdentity(-42, true);
		$this->assertEquals(
			'{"-42":1}',
			(string) $action
		);

		// Readd the identity, but deny.
		$action->mergeIdentity(-42, false);
		$this->assertEquals(
			'{"-42":0}',
			(string) $action
		);

		// Readd the identity with allow (checking deny wins).
		$action->mergeIdentity(-42, true);
		$this->assertEquals(
			'{"-42":0}',
			(string) $action
		);
	}

	public function testMergeIdentities()
	{
		$array = array(
			-42	=> 1,
			2	=> 1,
			3	=> 0
		);

		// Construct an action with no identities.
		$action = new JAction('');

		$action->mergeIdentities($array);
		$this->assertEquals(
			json_encode($array),
			(string) $action
		);

		// Merge a new set, flipping some bits.
		$array = array(
			-42	=> 0,
			2	=> 1,
			3	=> 1,
			4	=> 1
		);

		// Ident 3 should remain false, 4 should be added.
		$result = array(
			-42	=> 0,
			2	=> 1,
			3	=> 0,
			4	=> 1
		);
		$action->mergeIdentities($array);
		$this->assertEquals(
			json_encode($result),
			(string) $action
		);
	}

	public function testAllow()
	{
		// Simple allow and deny test.
		$array = array(
			-42	=> 0,
			2	=> 1
		);
		$action = new JAction($array);

		// This one should be denied.
		$this->assertFalse(
			$action->allow(-42)
		);

		// This one should be allowed.
		$this->assertTrue(
			$action->allow(2)
		);

		// This one should be denied.
		$this->assertFalse(
			$action->allow(array(-42, 2))
		);
	}
}
