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
	 * Test the JForm::addNode method.
	 */
	public function testAddNode()
	{
		// The source data.
		$xml1 = simplexml_load_string('<form><fields /></form>');

		// The new data for adding the field.
		$xml2 = simplexml_load_string('<form><field name="foo" /></form>');

		if ($xml1 === false || $xml2 === false) {
			$this->fail('Error in text XML data');
		}

		JFormInspector::addNode($xml1->fields, $xml2->field);

		$fields = $xml1->xpath('fields/field[@name="foo"]');
		$this->assertThat(
			count($fields),
			$this->equalTo(1)
		);
	}

	/**
	 * Test the JForm::mergeNode method.
	 */
	public function testMergeNode()
	{
		// The source data.
		$xml1 = simplexml_load_string('<form><field name="foo" /></form>');

		// The new data for adding the field.
		$xml2 = simplexml_load_string('<form><field name="bar" type="text" /></form>');

		if ($xml1 === false || $xml2 === false) {
			$this->fail('Error in text XML data');
		}

		JFormInspector::mergeNode($xml1->field, $xml2->field);

		$fields = $xml1->xpath('field[@name="foo"] | field[@type="text"]');
		$this->assertThat(
			count($fields),
			$this->equalTo(1)
		);
	}

	/**
	 * Test the JForm::mergeNode method.
	 */
	public function testMergeNodes()
	{
		// The source data.
		$xml1 = simplexml_load_string('<form><fields><field name="foo" /></fields></form>');

		// The new data for adding the field.
		$xml2 = simplexml_load_string('<form><fields><field name="foo" type="text" /><field name="soap" /></fields></form>');

		if ($xml1 === false || $xml2 === false) {
			$this->fail('Error in text XML data');
		}

		JFormInspector::mergeNodes($xml1->fields, $xml2->fields);

		$fields = $xml1->xpath('fields/field[@name="foo"] | fields/field[@type="text"]');
		$this->assertThat(
			count($fields),
			$this->equalTo(1)
		);

		$fields = $xml1->xpath('fields/field[@name="soap"]');
		$this->assertThat(
			count($fields),
			$this->equalTo(1)
		);
	}

	/**
	 * Test the JForm::load method.
	 *
	 * This method can load an XML data object, or parse an XML string.
	 */
	public function testLoad()
	{
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
		$form->load('<form><fields /></form>');
		$data1 = clone $form->getXML();

		$this->assertThat(
			($data1 instanceof JXMLElement),
			$this->isTrue()
		);

		// Test implied reset.
		$form->load('<form><fields><field /><fields></form>');
		$data2 = clone $form->getXML();

		$this->assertThat(
			$data1,
			$this->logicalNot($this->identicalTo($data2))
		);

		// Test bad structure.
		$this->assertThat(
			$form->load('<foobar />'),
			$this->isFalse()
		);

		$this->assertThat(
			$form->load('<form><fields /><fields /></form>'),
			$this->isFalse()
		);

		// Test merging.
		$form = new JFormInspector('form1');

		$xml1 = <<<XML
<form>
	<fields>
		<field
			name="title" />
		<field
			name="abstract" />

		<fields
			name="params">
			<field
				name="show_title"
				type="radio">
				<option value="1">JYes</option>
				<option value="0">JNo</option>
			</field>
		</fields>
	</fields>
</form>
XML;
		// Load the data (checking it was ok).
		$this->assertThat(
			$form->load($xml1),
			$this->isTrue()
		);

		$xml2 = <<<XML
<form>
	<fields>
		<field
			name="published"
			type="list">
			<option
				value="1">JYes</option>
			<option
				value="0">JNo</option>
		</field>
		<field
			name="abstract"
			label="Abstract" />

		<fields
			label="A general group">
			<field
				name="access" />
			<field
				name="ordering" />
		</fields>
		<fields
			name="params">
			<field
				name="show_abstract"
				type="radio">
				<option value="1">JYes</option>
				<option value="0">JNo</option>
			</field>
		</fields>
		<fieldset>
			<field
				name="language"
				type="text"/>
		</fieldset>
	</fields>
</form>
XML;
		// Merge in the second batch of data (checking it was ok).
		$this->assertThat(
			$form->load($xml2, false),
			$this->isTrue()
		);

		/*$dom = new DOMDocument('1.0');
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($form->getXml()->asXML());
		echo $dom->saveXML();*/

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
			<fieldset
				name="basic">
				<field
					name="show_author" />
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
		$fields = $form->getFieldsByGroup('foo');
		$this->assertThat(
			empty($fields),
			$this->isTrue()
		);

		// Check the valid groups.
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

		// Check the valid fieldsets.
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
		$form = new JFormInspector('form1');

		$xml = <<<XML
<form>
	<fields
		description="All the fields">
		<!-- Set up a group of fields called details. -->
		<fields
			name="details"
			description="The Details Group">
			<field
				name="title" />
			<field
				name="abstract" />
		</fields>
		<fields
			name="params"
			description="Optional Settings">
			<field
				name="show_title" />
			<field
				name="show_abstract" />
			<fieldset
				name="basic">
				<field
					name="show_author" />
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

		$data = array(
			'title'		=> 'Joomla Framework',
			'author'	=> 'Should not bind',
			'params'	=> array(
				'show_title'	=> 1,
				'show_abstract'	=> 0,
				'show_author'	=> 1,
			)
		);

		// Bind the data.
		$this->assertThat(
			$form->bind($data),
			$this->isTrue()
		);

		// Get the data to inspect it.
		$data = $form->getData();
		$this->assertThat(
			isset($data['title']) && $data['title'] = 'Joomla Framework',
			$this->isTrue()
		);

		$this->assertThat(
			isset($data['author']),
			$this->isFalse()
		);
	}

	/**
	 * Test the JForm::getField method.
	 */
	public function testLoadFieldType()
	{
		$field = JFormInspector::loadFieldType('list');
		$this->assertThat(
			($field instanceof JFormFieldList),
			$this->isTrue()
		);

		JForm::addFieldPath(dirname(__FILE__).'/_testfields');
		$field = JFormInspector::loadFieldType('test');
		$this->assertThat(
			($field instanceof JFormFieldTest),
			$this->isTrue()
		);

		$field = JFormInspector::loadFieldType('bogus');
		$this->assertThat(
			$field,
			$this->isFalse()
		);
	}

	/**
	 * Test the JForm::getFormControl method.
	 */
	public function testGetFormControl()
	{
		$form = new JForm('form8ion');

		$this->assertThat(
			$form->getFormControl(),
			$this->equalTo('')
		);

		$form = new JForm('form8ion', array('control' => 'jform'));

		$this->assertThat(
			$form->getFormControl(),
			$this->equalTo('jform')
		);
	}

	/**
	 * Test the JForm::findField method.
	 */
	public function testFindField()
	{
		// Prepare the form.
		$form = new JFormInspector('form1');

		$xml = <<<XML
<form>
	<fields>
		<field
			name="title" type="text" place="root" />
		<fields
			label="Details">
			<field
				name="published" type="list" />
		</fields>
		<fields
			name="params">
			<field
				name="title" place="child" type="password" />
			<fieldset
				label="Basic">
				<field
					name="show_title" />
			</fieldset>
			<fieldset
				label="Advanced">
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

		// Check that a non-existant field returns nothing.
		$field = $form->findField('foo', null);
		$this->assertThat(
			$field,
			$this->isFalse()
		);

		// Check that a non-existant group returns nothing.
		$field = $form->findField('title', 'foo');
		$this->assertThat(
			$field,
			$this->isFalse()
		);

		// Check for a groupless field.
		$field = $form->findField('title', null);
		$this->assertThat(
			(string) $field['place'],
			$this->equalTo('root')
		);

		// Check for a grouped field.
		$field = $form->findField('title', 'params');
		$this->assertThat(
			(string) $field['place'],
			$this->equalTo('child')
		);

		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test the JForm::getField method.
	 */
	public function testGetField()
	{
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Testing filter
	 *
	 * @return void
	 */
	/*
	public function testFilter()
	{
		include_once JPATH_BASE . '/libraries/joomla/user/user.php';

		$user = new JUser;
		$mockSession = $this->getMock('JSession', array('_start', 'get'));
		$mockSession->expects($this->once())->method('get')->will(
			$this->returnValue($user)
		);
		JFactory::$session = $mockSession;
		// Adjust the timezone offset to a known value.
		$config = JFactory::getConfig();
		$config->setValue('config.offset', 10);

		// TODO: Mock JFactory and JUser
		//$user = JFactory::getUser();
		//$user->setParam('timezone', 5);

		$form = new JForm;
		$form->load('example');

		$text = '<script>alert();</script> <p>Some text</p>';
		$data = array(
			'f_text' => $text,
			'f_safe_text' => $text,
			'f_raw_text' => $text,
			'f_svr_date' => '2009-01-01 00:00:00',
			'f_usr_date' => '2009-01-01 00:00:00',
			'f_unset' => 1
		);

		$result = $form->filter($data);

		// Check that the unset filter worked.
		$this->assertThat(
			isset($result['f_text']),
			$this->isTrue()
		);

		$this->assertThat(
			isset($result['f_safe_text']),
			$this->isTrue()
		);

		$this->assertThat(
			isset($result['f_raw_text']),
			$this->isTrue()
		);

		$this->assertThat(
			isset($result['f_svr_date']),
			$this->isTrue()
		);

		$this->assertThat(
			isset($result['f_unset']),
			$this->isFalse()
		);

		// Check the date filters.
		$this->assertThat(
			$result['f_svr_date'],
			$this->equalTo('2008-12-31 14:00:00')
		);

		//$this->assertThat(
		//	$result['f_usr_date'],
		//	$this->equalTo('2009-01-01 05:00:00')
		//);

		// Check that text filtering worked.
		$this->assertThat(
			$result['f_raw_text'],
			$this->equalTo($text)
		);

		$this->assertThat(
			$result['f_text'],
			$this->equalTo('alert(); Some text')
		);

		$this->assertThat(
			$result['f_safe_text'],
			$this->equalTo('alert(); <p>Some text</p>')
		);

		$this->markTestIncomplete();
	}
	*/
}
