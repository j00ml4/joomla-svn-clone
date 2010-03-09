<?php
	/**
 * JFormTest.php -- unit testing file for JForm
 *
 * @version		$Id: JFormTest.php 15140 2010-03-02 15:08:04Z ian $
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
class JForm_LoadTest extends JoomlaTestCase
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
	public function tearDown()
	{
		$this->restoreFactoryState();
	}

	public function testLoad_Integer()
	{
		// pass bad data and make sure we return false
		$form = new JForm('form1');
		
		$result = $form->load(21);

		$this->assertThat(
			$result,
			$this->equalTo(false)
		);
	}

	public function testLoad_BadString()
	{
		// pass bad data and make sure we return false
		$form = new JForm('form1');
		
		$result = $form->load('junk');

		$this->assertThat(
			$result,
			$this->equalTo(false)
		);
	}

	public function testLoad_XmlString()
	{
		$form = new JFormInspector('form1');

		// Test an XML string.
		$form->load('<form><fields /></form>');
		$data1 = clone $form->getXML();

		$this->assertThat(
			($data1 instanceof JXMLElement),
			$this->isTrue()
		);
	}

	public function testLoad_ImpliedReset()
	{
		$form = new JFormInspector('form1');

		// Test an XML string.
		$form->load('<form><fields /></form>');
		$data1 = clone $form->getXML();

		// Test implied reset.
		$form->load('<form><fields><field /><fields></form>');
		$data2 = clone $form->getXML();

		$this->assertThat(
			$data1,
			$this->logicalNot($this->identicalTo($data2))
		);
	}


	public function testLoad_BadStructure()
	{
		$form = new JFormInspector('form1');

		// Test bad structure.
		$this->assertThat(
			$form->load('<foobar />'),
			$this->isFalse()
		);
	}

	public function testLoad_NonFormRoot()
	{
		$form = new JFormInspector('form1');

		// Test bad structure.
		$this->assertThat(
			$form->load('<foobar><fields></fields></foobar>'),
			$this->isTrue()
		);

		$this->assertThat(
			$form->getXml()->getName(),
			$this->equalTo('form')
		);
		
	}


	public function testLoad_XpathNoExistingXmlNoReset()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$loadXPathDocument, false, '/form/fields/fields[@name=\'details\']'),
			$this->isTrue()
		);

		$expectedXml = <<<EXPECTED
<form>
	<fields>
		<fields name="details">
			<field name="title" />
			<field name="abstract" />
		</fields>
		<fields name="params">
			<field name="outlier" />
			<fieldset name="params-basic">
				<field name="show_title" />
				<field name="show_abstract" />
				<field name="show_author" />
			</fieldset>
			<fieldset name="params-advanced">
				<field name="module_prefix" />
				<field name="caching" />
			</fieldset>
		</fields>
	</fields>
</form>
EXPECTED;
		$this->assertThat(
			trim($form->getXml()->asFormattedXml()),
			$this->equalTo(trim($expectedXml))
		);
	}		

	public function testLoad_XpathAppendToGroupNoReset()
	{
		$form = new JFormInspector('form1');

		$this->assertThat(
			$form->load(JFormDataHelper::$loadXPathDocument, false, '/extension/fields'),
			$this->isTrue()
		);

		$expectedXml = <<<EXPECTED
<form>
	<fields name="details">
		<field name="title" />
		<field name="abstract" />
	</fields>
	<fields name="params">
		<field name="outlier" />
		<fieldset name="params-basic">
			<field name="show_title" />
			<field name="show_abstract" />
			<field name="show_author" />
		</fieldset>
		<fieldset name="params-advanced">
			<field name="module_prefix" />
			<field name="caching" />
		</fieldset>
	</fields>
</form>
EXPECTED;
		$this->assertThat(
			trim($form->getXml()->asFormattedXml()),
			$this->equalTo(trim($expectedXml))
		);
	}		


	public function testLoad_XpathAppendToGroupReset()
	{
		$form = new JFormInspector('form1');

		$form->load(JFormDataHelper::$loadBeforeXpathResetDocument);

		$this->assertThat(
			$form->load(JFormDataHelper::$loadXPathDocument, true, '/extension/'),
			$this->isTrue()
		);

		$expectedXml = <<<EXPECTED
<form>
	<fields name="details">
		<field name="title" />
		<field name="abstract" />
	</fields>
	<fields name="params">
		<field name="outlier" />
		<fieldset name="params-basic">
			<field name="show_title" />
			<field name="show_abstract" />
			<field name="show_author" />
		</fieldset>
		<fieldset name="params-advanced">
			<field name="module_prefix" />
			<field name="caching" />
		</fieldset>
	</fields>
</form>
EXPECTED;
		$this->assertThat(
			trim($form->getXml()->asFormattedXml()),
			$this->equalTo(trim($expectedXml))
		);
	}		


}
