<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

class JRulesTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		jimport('joomla.access.rules');
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
		$rules	= new JRules($string);
		$this->assertEquals(
			$string,
			(string) $rules
		);

		// Test input as array.
		$rules	= new JRules($array);
		$this->assertEquals(
			$string,
			(string) $rules
		);
	}

	public function testMergeRule()
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

		// Construct and empty JRules.
		$rules = new JRules('');
		$rules->mergeAction('edit', $identities);

		$this->assertEquals(
			json_encode($result),
			(string) $rules
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

		$rules->mergeAction('edit', $identities);

		$this->assertEquals(
			json_encode($result),
			(string) $rules
		);
	}

	/**
	 * This method can merge an array of rules,
	 * a single JRules object,
	 * or a JSON rules string.
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
		$rules1 = new JRules($string1);
		$this->assertEquals(
			$string1,
			(string) $rules1
		);

		// Test construction by array.
		$rules1 = new JRules($array1);
		$this->assertEquals(
			$string1,
			(string) $rules1
		);

		// Test merge by JRules.
		$rules1 = new JRules($array1);
		$rules2 = new JRules('');
		$rules2->merge($rules1);
		$this->assertEquals(
			$string1,
			(string) $rules2
		);

		$rules1 = new JRules($array1);
		$rules1->merge($array2);
		$this->assertEquals(
			json_encode($result2),
			(string) $rules1
		);

	}

	function testAllow()
	{
		$array1 = array(
			'edit' => array(
				-42	=> 1
			),
			'delete' => array(
				-42	=> 0,
				2	=> 1
			)
		);

		$rules = new JRules($array1);

		// Explicit allow.
		$this->assertTrue(
			$rules->allow('edit', -42)
		);

		// Check string or int works.
		$this->assertTrue(
			$rules->allow('edit', '-42')
		);

		// Implicit deny
		$this->assertNull(
			$rules->allow('edit', 999)
		);

		// Explicit deny
		$this->assertFalse(
			$rules->allow('delete', -42)
		);

		// This should be true, implicit deny does not win.
		$this->assertTrue(
			$rules->allow('edit', array(-42, 999))
		);

		// This should be false, explicit deny wins.
		$this->assertFalse(
			$rules->allow('delete', array(-42, 2))
		);
	}
}
