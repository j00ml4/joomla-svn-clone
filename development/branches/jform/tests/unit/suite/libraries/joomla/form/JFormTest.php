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
class JFormTest extends JoomlaTestCase //PHPUnit_Framework_TestCase
{

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
		include_once 'JFormDataHelper.php';
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
			$this->isTrue(),
			'Line:'.__LINE__.' The libraries fields path should be included by default.'
		);

		// Test adding a custom folder.
		JForm::addFieldPath(dirname(__FILE__));
		$paths = JForm::addFieldPath();

		$this->assertThat(
			in_array(dirname(__FILE__), $paths),
			$this->isTrue(),
			'Line:'.__LINE__.' An added path should be in the returned array.'
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
			$this->isTrue(),
			'Line:'.__LINE__.' The libraries forms path should be included by default.'
		);

		// Test adding a custom folder.
		JForm::addFormPath(dirname(__FILE__));
		$paths = JForm::addFormPath();

		$this->assertThat(
			in_array(dirname(__FILE__), $paths),
			$this->isTrue(),
			'Line:'.__LINE__.' An added path should be in the returned array.'
		);
	}

	/**
	 * Tests the JForm::addRulePath method.
	 *
	 * This method is used to add additional lookup paths for form XML files.
	 */
	public function testAddRulePath()
	{
		// Check the default behaviour.
		$paths = JForm::addRulePath();

		// The default path is the class file folder/rules
		$valid = JPATH_LIBRARIES.'/joomla/form/rules';

		$this->assertThat(
			in_array($valid, $paths),
			$this->isTrue(),
			'Line:'.__LINE__.' The libraries rule path should be included by default.'
		);

		// Test adding a custom folder.
		JForm::addRulePath(dirname(__FILE__));
		$paths = JForm::addRulePath();

		$this->assertThat(
			in_array(dirname(__FILE__), $paths),
			$this->isTrue(),
			'Line:'.__LINE__.' An added path should be in the returned array.'
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
	 * Tests the JForm::bind method.
	 *
	 * This method is used to load data into the JForm object.
	 */
	public function testBind()
	{
		$form = new JFormInspector('form1');

		$xml = JFormDataHelper::$bindDocument;
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
			($data->get('title') == 'Joomla Framework'),
			$this->isTrue()
		);

		$this->assertThat(
			((bool) $data->get('author')),
			$this->isFalse()
		);
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
	 * Test for JForm::filter method.
	 *
	 * @return void
	 */
	public function testFilter()
	{
		$form = new JFormInspector('form1');

		$xml = JFormDataHelper::$filterDocument;

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
				'show_abstract'	=> false,
				'show_author'	=> '1',
			)
		);

		$filtered = $form->filter($data);

		// Filter the data.
		$this->assertThat(
			(bool)$filtered,
			$this->isTrue()
		);

		// Bind the data.
		$this->assertThat(
			($filtered['title'] === 'JoomlaFramework'),
			$this->isTrue()
		);

		// Bind the data.
		$this->assertThat(
			(!isset($filtered['author'])),
			$this->isTrue()
		);

		$this->assertThat(
			($filtered['params']['show_author'] === 1),
			$this->isTrue()
		);

		$this->assertThat(
			($filtered['params']['show_abstract'] === 0),
			$this->isTrue()
		);

		$this->markTestIncomplete('This test has not been implemented yet.');
	/*
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
	*/
	}

	/**
	 * Test for JForm::filterField method.
	 */
	public function testFilterField()
	{
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test the JForm::findField method.
	 */
	public function testFindField()
	{
		// Prepare the form.
		$form = new JFormInspector('form1');

		$xml = JFormDataHelper::$findFieldDocument;

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

		// Check for a field in a fieldset.
		$field = $form->findField('alias', null);
		$this->assertThat(
			(string) $field['name'],
			$this->equalTo('alias')
		);

		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test the JForm::findGroup method.
	 */
	public function testFindGroup()
	{
		// Prepare the form.
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$findGroupDocument),
			$this->isTrue(),
			'Line:'.__LINE__.' XML string should load successfully.'
		);

		$this->assertThat(
			count($form->findGroup('bogus')),
			$this->equalTo(0),
			'Line:'.__LINE__.' A group that does not exist should return an empty array.'
		);

		$this->assertThat(
			count($form->findGroup('params')),
			$this->equalTo(1),
			'Line:'.__LINE__.' The group should have one element.'
		);

		$this->assertThat(
			count($form->findGroup('bogus.data')),
			$this->equalTo(0),
			'Line:'.__LINE__.' A group path that does not exist should return an empty array.'
		);

		// Check that an existant field returns something.
		$this->assertThat(
			count($form->findGroup('params.cache')),
			$this->equalTo(1),
			'Line:'.__LINE__.' The group should have one element.'
		);

		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Tests the JForm::findFieldsByFieldset method.
	 */
	public function testFindFieldsByFieldset()
	{
		// Prepare the form.
		$form = new JFormInspector('form1');

		// Check the test data loads ok.
		$this->assertThat(
			$form->findFieldsByFieldset('foo'),
			$this->isFalse()
		);

		$xml = JFormDataHelper::$findFieldsByFieldsetDocument;

		// Check the test data loads ok.
		$this->assertThat(
			$form->load($xml),
			$this->isTrue()
		);

		// Check that a non-existant group returns nothing.
		$fields = $form->findFieldsByFieldset('foo');
		$this->assertThat(
			empty($fields),
			$this->isTrue()
		);

		// Check the valid fieldsets.
		$fields = $form->findFieldsByFieldset('params-basic');
		$this->assertThat(
			count($fields),
			$this->equalTo(3)
		);

		$fields = $form->findFieldsByFieldset('params-advanced');
		$this->assertThat(
			count($fields),
			$this->equalTo(2)
		);
	}

	/**
	 * Test the JForm::findFieldsByGroup method.
	 */
	public function testFindFieldsByGroup()
	{
		// Prepare the form.
		$form = new JFormInspector('form1');

		// Check the test data loads ok.
		$this->assertThat(
			$form->findFieldsByGroup('foo'),
			$this->isFalse()
		);

		$xml = JFormDataHelper::$findFieldsByGroupDocument;

		// Check the test data loads ok.
		$this->assertThat(
			$form->load($xml),
			$this->isTrue()
		);

		// Check that a non-existant group returns nothing.
		$fields = $form->findFieldsByGroup('foo');
		$this->assertThat(
			empty($fields),
			$this->isTrue()
		);

		// Check the valid groups.
		$fields = $form->findFieldsByGroup('details');
		$this->assertThat(
			count($fields),
			$this->equalTo(2)
		);

		$fields = $form->findFieldsByGroup('params');
		$this->assertThat(
			count($fields),
			$this->equalTo(3)
		);
	}

	/**
	 * Test for JForm::getErrors method.
	 */
	public function testGetErrors()
	{
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test the JForm::getField method.
	 */
	public function testGetField()
	{
		// Prepare the form.
		$form = new JFormInspector('form1');

		$xml = JFormDataHelper::$getFieldDocument;

		// Check the test data loads ok.
		$this->assertThat(
			$form->load($xml),
			$this->isTrue()
		);

		$field = $form->getField('show_title', 'params');
		$this->assertThat(
			$field->value,
			$this->equalTo(1)
		);

		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test for JForm::getFieldAttribute method.
	 */
	public function testGetFieldAttribute()
	{
		$this->markTestIncomplete('This test has not been implemented yet.');
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
	 * Test for JForm::getLabel method.
	 */
	public function testGetGroup()
	{
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test for JForm::getInput method.
	 */
	public function testGetInput()
	{
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test for JForm::getLabel method.
	 */
	public function testGetLabel()
	{
		$this->markTestIncomplete('This test has not been implemented yet.');
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
	 * Test for JForm::getValue method.
	 */
	public function testGetValue()
	{
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test for JForm::getFieldset method.
	 */
	public function testGetFieldset()
	{
		// Prepare the form.
		$form = new JFormInspector('form1');

		// Check the test data loads ok.
		$fields = $form->getFieldset('params-advanced');
		$this->assertThat(
			empty($fields),
			$this->isTrue()
		);

		// Check the test data loads ok.
		$this->assertThat(
			$form->load(JFormDataHelper::$getFieldsetDocument),
			$this->isTrue()
		);

		$fields = $form->getFieldset('params-basic');
		$this->assertThat(
			count($fields) == 4,
			$this->isTrue()
		);
	}

	/**
	 * Test for JForm::getFieldsets method.
	 */
	public function testGetFieldsets()
	{
		// Prepare the form.
		$form = new JFormInspector('form1');

		// Check the test data loads ok.
		$this->assertThat(
			$form->load(JFormDataHelper::$getFieldsetsDocument),
			$this->isTrue(),
			'Line:'.__LINE__.' XML string should load successfully.'
		);

		$sets = $form->getFieldsets('details');
		$this->assertThat(
			count($sets),
			$this->equalTo(1),
			'Line:'.__LINE__.' .'
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

		$this->assertThat(
			$form->load(JFormDataHelper::$loadDocument),
			$this->isTrue(),
			'Line:'.__LINE__.' XML string should load successfully.'
		);

		$this->assertThat(
			($form->getXML() instanceof JXMLElement),
			$this->isTrue(),
			'Line:'.__LINE__.' The internal XML should be a JXMLElement object.'
		);

		// Test reset false.

		$this->assertThat(
			$form->load(JFormDataHelper::$loadMergeDocument, false),
			$this->isTrue(),
			'Line:'.__LINE__.' XML string should load successfully.'
		);

		$this->assertThat(
			count($form->getXML()->xpath('/form/fields/field')),
			$this->equalTo(3),
			'Line:'.__LINE__.' There is 1 new ungrouped field and one existing field should merge, resulting in 3 total.'
		);

		// Test reset true (default).

		$this->assertThat(
			$form->load(JFormDataHelper::$loadMergeDocument),
			$this->isTrue(),
			'Line:'.__LINE__.' XML string should load successfully.'
		);

		$this->assertThat(
			count($form->getXML()->xpath('/form/fields/field')),
			$this->equalTo(2),
			'Line:'.__LINE__.' The XML resets to 2 ungrouped fields.'
		);

		$this->assertThat(
			count($form->getXML()->xpath('/form/fields/fields')),
			$this->equalTo(2),
			'Line:'.__LINE__.' The XML has 2 fields tags.'
		);

		$this->assertThat(
			count($form->getXML()->xpath('/form/fields/fields[@name="params"]/field')),
			$this->equalTo(1),
			'Line:'.__LINE__.' The params groups field has been replaced.'
		);

		$this->assertThat(
			count($form->getXML()->xpath('/form/fields/fields[@name="params"]/field[@name="show_abstract"]')),
			$this->equalTo(1),
			'Line:'.__LINE__.' The show_title in the params group has been replaced by show_abstract.'
		);
	}

	/**
	 * Test the JForm::load method for cases of unexpected or bad input.
	 *
	 * This method can load an XML data object, or parse an XML string.
	 */
	public function testLoad_BadInput()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(123),
			$this->isFalse(),
			'Line:'.__LINE__.' A non-string should return false.'
		);

		$this->assertThat(
			$form->load('junk'),
			$this->isFalse(),
			'Line:'.__LINE__.' An invalid string should return false.'
		);

		$this->assertThat(
			$form->getXml(),
			$this->isNull(),
			'Line:'.__LINE__.' The internal XML should be false as returned from simplexml_load_string.'
		);

		$this->assertThat(
			$form->load('<notform><test /></notform>'),
			$this->isTrue(),
			'Line:'.__LINE__.' Invalid root node name from string should still load.'
		);

		$this->assertThat(
			$form->getXml()->getName(),
			$this->equalTo('form'),
			'Line:'.__LINE__.' The internal XML should still be named "form".'
		);

		// Test for irregular object input.

		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFactory::getXml('<notform><test /></notform>', false)),
			$this->isTrue(),
			'Line:'.__LINE__.' Invalid root node name from XML object should still load.'
		);

		$this->assertThat(
			$form->getXml()->getName(),
			$this->equalTo('form'),
			'Line:'.__LINE__.' The internal XML should still be named "form".'
		);
	}

	/**
	 * Test the JForm::load method for merging data.
	 *
	 * This method can load an XML data object, or parse an XML string.
	 */
	public function testLoad_Merging()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load('<form><fields><field /></fields></form>'),
			$this->isTrue(),
			'Line:'.__LINE__.' XML string should load successfully.'
		);
		$data2 = clone $form->getXML();

		$this->assertThat(
			$form->getXML(),
			$this->equalTo(JFactory::getXml('<form><fields><field /></fields></form>', false)),
			'Line:'.__LINE__.' The XML data should reset after second load.'
		);

		// Test merging.

		$form = new JFormInspector('form1');

		// Load the data (checking it was ok).
		$this->assertThat(
			$form->load(JFormDataHelper::$loadDocument),
			$this->isTrue(),
			'Line:'.__LINE__.' XML string should load successfully.'
		);

		// Merge in the second batch of data (checking it was ok).
		$this->assertThat(
			$form->load(JFormDataHelper::$loadMergeDocument, false),
			$this->isTrue(),
			'Line:'.__LINE__.' XML string should load successfully.'
		);

		$this->markTestIncomplete('Need to test that the merge was ok.');

		/*$dom = new DOMDocument('1.0');
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($form->getXml()->asXML());
		echo $dom->saveXML();*/
	}

	/**
	 * Test the JForm::load method for XPath data.
	 *
	 * This method can load an XML data object, or parse an XML string.
	 */
	public function testLoad_XPath()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$loadXPathDocument, true, '/extension/fields'),
			$this->isTrue(),
			'Line:'.__LINE__.' XML string should load successfully.'
		);

		/*$dom = new DOMDocument('1.0');
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($form->getXml()->asXML());
		echo $dom->saveXML();*/


		$this->assertThat(
			$form->getXml()->getName(),
			$this->equalTo('form'),
			'Line:'.__LINE__.' The internal XML should still be named "form".'
		);

		$this->assertThat(
			count($form->getXml()->fields),
			$this->equalTo(2),
			'Line:'.__LINE__.' The test data has 2 fields.'
		);
		$zml = $form->getXml();
		//print_r($zml);
	}

	/**
	 * Test for JForm::loadField method.
	 */
	public function testLoadField()
	{
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test the JForm::getField method.
	 */
	public function testLoadFieldType()
	{
		$this->assertThat(
			JFormInspector::loadFieldType('bogus'),
			$this->isFalse(),
			'Line:'.__LINE__.' loadFieldType should return false if class not found.'
		);

		$this->assertThat(
			(JFormInspector::loadFieldType('list') instanceof JFormFieldList),
			$this->isTrue(),
			'Line:'.__LINE__.' loadFieldType should return the correct class.'
		);

		// Add custom path.
		JForm::addFieldPath(dirname(__FILE__).'/_testfields');

		$this->assertThat(
			(JFormInspector::loadFieldType('test') instanceof JFormFieldTest),
			$this->isTrue(),
			'Line:'.__LINE__.' loadFieldType should return the correct custom class.'
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

		// Test for files that don't exist.

		$this->assertThat(
			$form->loadFile('/tmp/example.xml'),
			$this->isFalse(),
			'Line:'.__LINE__.' A file path that does not exist should return false.'
		);

		$this->assertThat(
			$form->loadFile('notfound'),
			$this->isFalse(),
			'Line:'.__LINE__.' A file name that does not exist should return false.'
		);

		// Testing loading a file by full path.

		$this->assertThat(
			$form->loadFile(dirname(__FILE__).'/example.xml'),
			$this->isTrue(),
			'Line:'.__LINE__.' XML file by full path should load successfully.'
		);

		$this->assertThat(
			($form->getXML() instanceof JXMLElement),
			$this->isTrue(),
			'Line:'.__LINE__.' XML string should parse successfully.'
		);

		// Testing loading a file by file name.

		$form = new JFormInspector('form1');
		JForm::addFormPath(dirname(__FILE__));

		$this->assertThat(
			$form->loadFile('example'),
			$this->isTrue(),
			'Line:'.__LINE__.' XML file by name should load successfully.'
		);

		$this->assertThat(
			($form->getXML() instanceof JXMLElement),
			$this->isTrue(),
			'Line:'.__LINE__.' XML string should parse successfully.'
		);
	}

	/**
	 * Test for JForm::loadRuleType method.
	 */
	public function testLoadRuleType()
	{
		$this->markTestIncomplete('This test has not been implemented yet.');
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
			$this->fail('Line:'.__LINE__.' Error in text XML data');
		}

		JFormInspector::mergeNode($xml1->field, $xml2->field);

		$fields = $xml1->xpath('field[@name="foo"] | field[@type="text"]');
		$this->assertThat(
			count($fields),
			$this->equalTo(1),
			'Line:'.__LINE__.' Existing attribute "name" should merge, new attribute "type" added.'
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
			$this->fail('Line:'.__LINE__.' Error in text XML data');
		}

		JFormInspector::mergeNodes($xml1->fields, $xml2->fields);

		$this->assertThat(
			count($xml1->xpath('fields/field')),
			$this->equalTo(2),
			'Line:'.__LINE__.' The merge should have two field tags, one existing, one new.'
		);

		$this->assertThat(
			count($xml1->xpath('fields/field[@name="foo"] | fields/field[@type="text"]')),
			$this->equalTo(1),
			'Line:'.__LINE__.' A field of the same name should merge.'
		);

		$this->assertThat(
			count($xml1->xpath('fields/field[@name="soap"]')),
			$this->equalTo(1),
			'Line:'.__LINE__.' A new field should be added.'
		);
	}

	/**
	 * Test for JForm::removeField method.
	 */
	public function testRemoveField()
	{
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test for JForm::removeGroup method.
	 */
	public function testRemoveGroup()
	{
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test for JForm::setField method.
	 */
	public function testSetField()
	{
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test for JForm::setFieldAttribute method.
	 */
	public function testSetFieldAttribute()
	{
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test for JForm::setFields method.
	 */
	public function testSetFields()
	{
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test for JForm::setValue method.
	 */
	public function testSetValue()
	{
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test for JForm::validate method.
	 */
	public function testValidate()
	{
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test for JForm::validateFields method.
	 */
	public function testValidateFields()
	{
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}
