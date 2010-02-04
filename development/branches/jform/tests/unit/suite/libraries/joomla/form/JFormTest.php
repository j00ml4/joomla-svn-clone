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

		// Check the integrity of the options.
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
	 * Tests the JForm::addFormPath method.
	 *
	 * This method is used to add additional lookup paths for form XML files.
	 */
	public function testAddFormPath()
	{
		// Check the default behaviour.
		$paths = JForm::addFormPath();

		// The default path is the class file folder/forms
		$valid = JPATH_LIBRARIES.'/joomla/form/forms';

		$this->assertThat(
			in_array($valid, $paths),
			$this->isTrue()
		);

		// Test adding a custom folder.
		JForm::addFormPath(dirname(__FILE__));
		$paths = JForm::addFormPath();

		$this->assertThat(
			in_array(dirname(__FILE__), $paths),
			$this->isTrue()
		);
	}

	/**
	 * Tests the JForm::addFieldPath method.
	 *
	 * This method is used to add additional lookup paths for field helpers.
	 */
	public function testAddFieldPath()
	{
		// Check the default behaviour.
		$paths = JForm::addFieldPath();

		// The default path is the class file folder/forms
		$valid = JPATH_LIBRARIES.'/joomla/form/fields';

		$this->assertThat(
			in_array($valid, $paths),
			$this->isTrue()
		);

		// Test adding a custom folder.
		JForm::addFieldPath(dirname(__FILE__));
		$paths = JForm::addFieldPath();

		$this->assertThat(
			in_array(dirname(__FILE__), $paths),
			$this->isTrue()
		);
	}

	/**
	 * Test the JForm::load method.
	 *
	 * This method can load an XML data object, or parse an XML string.
	 */
	public function testLoad()
	{
		//jimport('joomla.utilities.xmlelement');

		$form = new JFormInspector('form1');

		// Test a non-string input.
		$this->assertThat(
			$form->load(123),
			$this->isFalse()
		);

		// Test an invalid string input.
		$this->assertThat(
			$form->load('junk'),
			$this->isFalse()
		);

		// Test an XML string.
		$form->load('<?xml version="1.0" encoding="utf-8" ?>'."\n".'<form><fields /></form>');
		$data1 = clone $form->getXML();

		$this->assertThat(
			($data1 instanceof JXMLElement),
			$this->isTrue()
		);

		// Test reset.
		$form->load('<?xml version="1.0" encoding="utf-8" ?>'."\n".'<form><fields><field /><fields></form>', true);
		$data2 = clone $form->getXML();

		$this->assertThat(
			$data1,
			$this->logicalNot($this->identicalTo($data2))
		);
		$this->markTestIncomplete('JForm::load with reset option not implemented in JForm yet.');

		// Test bad structure.
		$this->assertThat(
			$form->load('<?xml version="1.0" encoding="utf-8" ?>'."\n".'<foobar />', true),
			$this->isFalse()
		);

		$this->assertThat(
			$form->load('<?xml version="1.0" encoding="utf-8" ?>'."\n".'<form><fields /><fields /></form>', true),
			$this->isFalse()
		);
	}

	/**
	 * Test the JForm::loadFile method.
	 *
	 * This method loads a file and passes the string to the JForm::load method.
	 */
	public function testLoadFile()
	{
		$form = new JFormInspector('form1');

		// Check that this file won't be found.
		$this->assertThat(
			$form->load('example.xml'),
			$this->isFalse()
		);

		// Add the local path and check the file loads.
		JForm::addFormPath(dirname(__FILE__));

		$form->load('example.xml');
		$data1 = $form->getXML();

		$this->assertThat(
			($data1 instanceof JXMLElement),
			$this->isFalse()
		);
	}

	/**
	 * Test the JForm::getFieldsByGroup method.
	 */
	public function testGetFieldsByGroup()
	{
		// Prepare the form.
		$form = new JFormInspector('form1');

		// Check the test data loads ok.
		$this->assertThat(
			$form->getFieldsByGroup('foo'),
			$this->isFalse()
		);

		$xml = <<<XML
<?xml version="1.0" encoding="utf-8" ?>
<form>
	<fields>
		<!-- Set up a group of fields called details. -->
		<fields
			name="details">
			<field
				name="title" />
			<field
				name="abstract" />
		</fields>
		<fields
			name="params">
			<field
				name="show_title" />
			<field
				name="show_abstract" />
			<field
				name="show_author" />
		</fields>
	</fields>
</form>
XML;
		// Check the test data loads ok.
		$this->assertThat(
			$form->load($xml),
			$this->isTrue()
		);

		// Check that a non-existant group returns nothing.
		$fields = $form->getFieldsByGroup('foo');
		$this->assertThat(
			empty($fields),
			$this->isTrue()
		);

		$fields = $form->getFieldsByGroup('details');
		$this->assertThat(
			count($fields),
			$this->equalTo(2)
		);

		$fields = $form->getFieldsByGroup('params');
		$this->assertThat(
			count($fields),
			$this->equalTo(3)
		);
	}

	/**
	 * Tests the JForm::getFieldsByFieldset method.
	 */
	public function testGetFieldsByFieldset()
	{
		// Prepare the form.
		$form = new JFormInspector('form1');

		// Check the test data loads ok.
		$this->assertThat(
			$form->getFieldsByGroup('foo'),
			$this->isFalse()
		);

		$xml = <<<XML
<?xml version="1.0" encoding="utf-8" ?>
<form>
	<fields>
		<!-- Set up a group of fields called details. -->
		<fields
			name="details">
			<field
				name="title" />
			<field
				name="abstract" />
		</fields>
		<fields
			name="params">
			<field
				name="outlier" />
			<fieldset
				name="params-basic">
				<field
					name="show_title" />
				<field
					name="show_abstract" />
				<field
					name="show_author" />
			</fieldset>
			<fieldset
				name="params-advanced">
				<field
					name="module_prefix" />
				<field
					name="caching" />
			</fieldset>
		</fields>
	</fields>
</form>
XML;
		// Check the test data loads ok.
		$this->assertThat(
			$form->load($xml),
			$this->isTrue()
		);

		// Check that a non-existant group returns nothing.
		$fields = $form->getFieldsByFieldset('foo');
		$this->assertThat(
			empty($fields),
			$this->isTrue()
		);

		$fields = $form->getFieldsByFieldset('params-basic');
		$this->assertThat(
			count($fields),
			$this->equalTo(3)
		);

		$fields = $form->getFieldsByFieldset('params-advanced');
		$this->assertThat(
			count($fields),
			$this->equalTo(2)
		);
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
