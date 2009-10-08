<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Example test case.
 */
class JFormTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		jimport('joomla.form.form');
		require_once 'inspectors.php';
	}

	function tearDown()
	{
	}

	//
	// Test the static methods.
	//

	public function testAddFormPath()
	{
		// Check the default behaviour.
		$paths = JForm::addFormPath();

		// The default path is the class file folder/forms
		$valid = array(
			JPATH_LIBRARIES.'/joomla/form/forms'
		);

		$this->assertThat(
			$paths,
			$this->equalTo($valid)
		);

		// Test adding a custom folder.
		JForm::addFormPath(dirname(__FILE__));
		$paths = JForm::addFormPath();

		// The valid will be added to the start of the stack.
		array_unshift($valid, dirname(__FILE__));

		$this->assertThat(
			$paths,
			$this->equalTo($valid)
		);
	}

	public function testGetInstance()
	{
		//$form = JFormInspector::getInstance
	}

	//
	// Test methods used by the instantiated object.
	//

	public function testConstruct()
	{
		// Check the empty contructor for basic errors.
		$form = new JFormInspector;

		$this->assertThat(
			($form instanceof JForm),
			$this->isTrue()
		);

		// Test that the default options sets array to false.
		$options = $form->getOptions();
		$valid = array('array' => false);

		$this->assertThat(
			$options,
			$this->equalTo($valid)
		);

		// Check that the constructor will process an a change in the array option.
		$input = array(
			'array' => true,
		);
		$form = new JFormInspector($input);
		$options = $form->getOptions();

		$this->assertThat(
			$options,
			$this->equalTo($input)
		);
	}

	public function testAddField()
	{
		$this->markTestIncomplete();
	}

	public function testAddFields()
	{
		$this->markTestIncomplete();
	}

	public function testBind()
	{
		$this->markTestIncomplete();
	}

	public function testFilter()
	{
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

		/*
		$this->assertThat(
			$result['f_usr_date'],
			$this->equalTo('2009-01-01 05:00:00')
		);
		*/

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

	public function testGetField()
	{
		$this->markTestIncomplete();
	}

	public function testGetFieldAttributes()
	{
		$this->markTestIncomplete();
	}

	public function testGetFields()
	{
		$this->markTestIncomplete();
	}

	public function testGetFieldsets()
	{
		$this->markTestIncomplete();
	}

	public function testGetGroups()
	{
		$this->markTestIncomplete();
	}

	public function testGetInput()
	{
		$this->markTestIncomplete();
	}

	public function testGetLabel()
	{
		$this->markTestIncomplete();
	}

	public function testGetNameSetName()
	{
		$form = new JForm;
		$input = 'j-form';

		// Check input = output.
		$form->setName($input);
		$name = $form->getName();

		$this->assertThat(
			$name,
			$this->equalTo($input)
		);
	}

	public function testGetValue()
	{
		$this->markTestIncomplete();
	}

	public function testLoad()
	{
		$form = new JFormInspector;

		// Check empty data returns false.
		$result = $form->load('');
		$this->assertThat(
			$result,
			$this->isFalse()
		);

		// Check poorly formed xml returns false.
		$result = $form->load('<fields><field /></fields>');
		$this->assertThat(
			$result,
			$this->isFalse()
		);

		$result = $form->load('<form><fields /></form>');
		$this->assertThat(
			$result,
			$this->isFalse()
		);

		// Check loading of good string data.
		$result = $form->load('<form><fields><field name="field_name" /></fields></form>', false);
		$this->assertThat(
			$result,
			$this->isTrue()
		);

		// Check non-existent file fails.
		$result = $form->load('not_found');
		$this->assertThat(
			$result,
			$this->isFalse()
		);

		// Check loading of good file.
		JForm::addFormPath(dirname(__FILE__));
		$result = $form->load('example');
		$this->assertThat(
			$result,
			$this->isTrue()
		);

		// Reassemble the XML from the form object and compare with the original.
		$groups = $form->getGroups();
		$xml = '<form><fields>';
		foreach ($groups['_default'] as $elem)
		{
			$xml .= $elem->toString();
		}
		$xml .= '</fields></form>';

		$original = new JSimpleXML;
		$original->loadFile(dirname(__FILE__).'/example.xml');

		$new = new JSimpleXML;
		$new->loadString($xml);

		$this->assertThat(
			$new->document->toString(),
			$this->equalTo($original->document->toString())
		);
	}

	public function testLoadFieldsXML()
	{
		$this->markTestIncomplete();
	}

	public function testLoadFieldType()
	{
		$this->markTestIncomplete();
	}

	public function testLoadFolder()
	{
		$this->markTestIncomplete();
	}

	public function testRemoveField()
	{
		$this->markTestIncomplete();
	}

	public function testRemoveGroup()
	{
		$this->markTestIncomplete();
	}

	public function testSetField()
	{
		$this->markTestIncomplete();
	}

	public function testSetFieldAttribute()
	{
		$this->markTestIncomplete();
	}

	public function testSetFields()
	{
		jimport('joomla.utilities.simplexml');

		// Prepare a sample XML document.
		$xml = new JSimpleXML;
		$xml->loadString('<fields><field name="field_name" /></fields>');
		$form = new JFormInspector;

		$fields = $xml->document->children();

		// Check the default group.
		$form->setFields($fields);

		// Use the inspector class to get the internal data.
		$groups = $form->getGroups();

		// Check the _default group has been added.
		$this->assertTrue(
			isset($groups['_default'])
		);

		// Check the field name has been added to the array.
		$this->assertTrue(
			isset($groups['_default']['field_name'])
		);

		// Check the field data.
		$this->assertThat(
			$groups['_default']['field_name'],
			$this->equalTo($fields[0])
		);
	}

	public function testSetValue()
	{
		$this->markTestIncomplete();
	}

	public function testValidate()
	{
		$this->markTestIncomplete();
	}
}
