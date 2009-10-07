<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Example test case.
 */
class ExampleTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Setup dependancies.
	 *
	 * @link	http://www.phpunit.de/manual/current/en/fixtures.html#fixtures.more-setup-than-teardown
	 */
	public function setUp()
	{
	}

	/**
	 * Cleanup after testing.
	 *
	 * @link	http://www.phpunit.de/manual/current/en/fixtures.html#fixtures.more-setup-than-teardown
	 */
	function tearDown()
	{
	}

	/**
	 * Example test method.
	 *
	 * @link	http://www.phpunit.de/manual/current/en/appendixes.annotations.html
	 */
	public function testExample()
	{
		// Use Hamcrest style where appropriate.
		$this->assertThat(
			true,
			$this->isTrue()
		);

		// Example of how to mark a test incomplete.
		// $this->markTestIncomplete();
	}
}
