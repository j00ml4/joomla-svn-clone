<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once JPATH_BASE.'/libraries/joomla/form/formrule.php';

require_once JPATH_BASE.'/tests/unit/suite/libraries/joomla/form/inspectors.php';

/**
 * Test class for JForm.
 *
 * @package		Joomla.UnitTest
 * @subpackage	Form
 */
class JFormRuleTest extends JoomlaTestCase {
	/**
	 * @var JFormRule
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->object = new JFormRuleInspector;
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}

	/**
	 * Tests JFormRule::test
	 */
	public function testTest() {
		$this->object->setProtectedProperty('regex', '[A-Za-z]+');
		$this->object->setProtectedProperty('modifiers', '');

		$element = array('name' => 'MyElementName');

		$this->assertThat(
			$this->object->test($element, 235),
			$this->isFalse(),
			'Asserting that regex fails.'
		);

	}


	/**
	 * Tests JFormRule::test - tests that an exception is thrown when the regex is empty
	 * @expectedException Exception
	 */
	public function testTestExceptionThrown() {
		$this->object->setProtectedProperty('regex', '');
		$this->object->setProtectedProperty('modifiers', '');

		$element = array('name' => 'MyElementName');

		$this->object->test($element, 235);

	}

}