<?php
/**
 * JFormTest.php -- unit testing file for JForm
 *
 * @version		$Id$
 * @package	Joomla.UnitTest
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Test class for JForm.
 *
 * @package	Joomla.UnitTest
 * @subpackage Utilities
 *
 */
class JFormTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Saves the Factory pointers
	 *
	 * @return void
	 */
	protected function saveFactoryState()
	{
		$this->factoryState['application'] = JFactory::$application;
		$this->factoryState['config'] = JFactory::$config;
		$this->factoryState['session'] = JFactory::$session;
		$this->factoryState['language'] = JFactory::$language;
		$this->factoryState['document'] = JFactory::$document;
		$this->factoryState['acl'] = JFactory::$acl;
		$this->factoryState['database'] = JFactory::$database;
		$this->factoryState['mailer'] = JFactory::$mailer;
	}

	/**
	 * Saves the Factory pointers
	 *
	 * @return void
	 */
	protected function restoreFactoryState()
	{
		JFactory::$application = $this->factoryState['application'];
		JFactory::$config = $this->factoryState['config'];
		JFactory::$session = $this->factoryState['session'];
		JFactory::$language = $this->factoryState['language'];
		JFactory::$document = $this->factoryState['document'];
		JFactory::$acl = $this->factoryState['acl'];
		JFactory::$database = $this->factoryState['database'];
		JFactory::$mailer = $this->factoryState['mailer'];
	}

	/**
	 * set up for testing
	 *
	 * @return void
	 */
	public function setUp()
	{
		$this->saveFactoryState();
		jimport('joomla.form.form');
		include_once 'inspectors.php';
	}

	/**
	 * Tear down test
	 *
	 * @return void
	 */
	function tearDown()
	{
		$this->restoreFactoryState();
	}

	/**
	 * Testing methods used by the instantiated object.
	 *
	 * @return void
	 */
	public function testConstruct()
	{
		// Check the empty contructor for basic errors.
		$form = new JFormInspector('form1');

		$this->assertThat(
			($form instanceof JForm),
			$this->isTrue()
		);

		$options = $form->getOptions();
		$this->assertThat(
			isset($options['control']),
			$this->isTrue()
		);

		$options = $form->getOptions();
		$this->assertThat(
			$options['control'],
			$this->isFalse()
		);

		// Test setting the control value.
		$form = new JFormInspector('form1', array('control' => 'jform'));

		$options = $form->getOptions();

		$this->assertThat(
			$options['control'],
			$this->equalTo('jform')
		);
	}

	/**
	 * Test the JForm::getName method.
	 */
	public function testGetName()
	{
		$form = new JForm('form1');

		$this->assertThat(
			$form->getName(),
			$this->equalTo('form1')
		);
	}

	/**
	 * Test the JForm::load method.
	 *
	 * This method can load an XML data object, or parse an XML string.
	 */
	public function testLoad()
	{
		$form = new JForm('form1');
		$this->markTestIncomplete();
	}

	/**
	 * Test the JForm::loadFile method.
	 *
	 * This method loads a file and passes the string to the JForm::load method.
	 */
	public function testLoadFile()
	{
		$form = new JForm('form1');
		$this->markTestIncomplete();
	}

	/**
	 * Tests the JForm::bind method.
	 *
	 * This method is used to load data into the JForm object.
	 */
	public function testBind()
	{
		$form = new JForm('form1');
		$this->markTestIncomplete();
	}
}
