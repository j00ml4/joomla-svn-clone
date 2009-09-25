<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

class JActionsTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		jimport('joomla.access.actions');
	}

	/**
	 * This method tests both the contructor and the __toString magic method.
	 *
	 * The input for this class could come from a posted form, or from a JSON string
	 * stored in the database.  We need to ensure that the resulting JSON is the same
	 * as the input.
	 */
	public function testConstructor()
	{
		$array = array(
			'edit' => array(
				-42	=> 1,
				2	=> 1,
				3	=> 0
			)
		);

		$string = json_encode($array);

		// Test input as string.
		$actions	= new JActions($string);
		$this->assertEquals(
			$string,
			(string) $actions
		);

		// Test input as array.
		$actions	= new JActions($array);
		$this->assertEquals(
			$string,
			(string) $actions
		);
	}

	public function testMergeAction()
	{
		$identities = array(
			-42	=> 1,
			2	=> 1,
			3	=> 0
		);

		$result = array(
			'edit' => array(
				-42	=> 1,
				2	=> 1,
				3	=> 0
			)
		);

		// Construct and empty JActions.
		$actions = new JActions('');
		$actions->mergeAction('edit', $identities);

		$this->assertEquals(
			json_encode($result),
			(string) $actions
		);

		// Merge a new set, flipping some bits.
		$identities = array(
			-42	=> 0,
			2	=> 1,
			3	=> 1,
			4	=> 1
		);

		// Ident 3 should remain false, 4 should be added.
		$result = array(
			'edit' => array(
				-42	=> 0,
				2	=> 1,
				3	=> 0,
				4	=> 1
			)
		);

		$actions->mergeAction('edit', $identities);

		$this->assertEquals(
			json_encode($result),
			(string) $actions
		);
	}

	/**
	 * This method can merge an array of actions,
	 * a single JActions object,
	 * or a JSON actions string.
	 */
	public function testMerge()
	{
		$array1 = array(
			'edit' => array(
				-42	=> 1
			),
			'delete' => array(
				-42	=> 0
			)
		);
		$string1 = json_encode($array1);

		$array2 = array(
			'create' => array(
				2	=> 1
			),
			'delete' => array(
				2	=> 0
			)
		);

		$result2 = array(
			'edit' => array(
				-42	=> 1
			),
			'delete' => array(
				-42	=> 0,
				2	=> 0
			),
			'create' => array(
				2	=> 1
			),
		);

		// Test construction by string
		$actions1 = new JActions($string1);
		$this->assertEquals(
			$string1,
			(string) $actions1
		);

		// Test construction by array.
		$actions1 = new JActions($array1);
		$this->assertEquals(
			$string1,
			(string) $actions1
		);

		// Test merge by JActions.
		$actions1 = new JActions($array1);
		$actions2 = new JActions('');
		$actions2->merge($actions1);
		$this->assertEquals(
			$string1,
			(string) $actions2
		);

		$actions1 = new JActions($array1);
		$actions1->merge($array2);
		$this->assertEquals(
			json_encode($result2),
			(string) $actions1
		);

	}

	function testAllow()
	{
		$array1 = array(
			'edit' => array(
				-42	=> 1
			),
			'delete' => array(
				-42	=> 0
			)
		);

		$actions = new JActions($array1);

		$this->assertTrue(
			$actions->allow('edit', -42)
		);

		$this->assertTrue(
			$actions->allow('edit', '-42')
		);

		$this->assertFalse(
			$actions->allow('delete', -42)
		);
	}
}
