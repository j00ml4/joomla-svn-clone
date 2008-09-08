<?php
/**
 * Test class for JFactory.
 *
 * @package Joomla
 * @subpackage UnitTest
 * @version     $Id$
 */

// Call TestOfJFactory::main() if this source file is executed directly.
if (!defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'TestOfJFactory::main');
	$JUnit_root = substr(__FILE__, 0, strpos(__FILE__, DIRECTORY_SEPARATOR.'unittest'));
	require_once $JUnit_root . '/unittest/setup.php';
}

/*
 * Now load the Joomla environment
 */
if (! defined('_JEXEC')) {
	define('_JEXEC', 1);
}
require_once JPATH_BASE . '/includes/defines.php';
/*
 * Mock classes
 */
// Include mocks here
/*
 * We now return to our regularly scheduled environment.
 */
require_once JPATH_LIBRARIES . '/joomla/import.php';

/* class to test */
require_once 'libraries/joomla/factory.php';

# TODO: should be handled by factory.php
require_once 'libraries/joomla/filter/input.php';

class TestOfJFactory extends UnitTestCase
{
var $have_db = false;

	/**
	 * Runs the test methods of this class.
	 *
	 * @access public
	 * @static
	 */
	function main() {
		$self = new TestOfJFactory;
		return $self->run(JUnit_Setup::getReporter());
	}

	function tearDown()
	{
		$config =& JFactory::getConfig();
		$config = null;
	}

	function testGetConfig()
	{
		$obj =& JFactory::getConfig();
		$this->assertIsA($obj, 'JRegistry');
		$this->assertIsA($obj, 'JObject');
	}

	function testGetConfig_reference()
	{
		$first  =& JFactory::getConfig();
		$second =& JFactory::getConfig();
		$this->assertReference($first, $second);

		// creates namespace 'foo' with item 'bar' = 'baz'
		$second->setValue('foo.bar', 'baz');
		$this->assertIdentical($first, $second);

	}
	function testGetConfig_unset()
	{
		$config =& JFactory::getConfig();
		$config->setValue('foo.bar', null);
		$this->assertNull($config->getValue('foo.bar'));

		// how do you remove namespace 'foo' thru the interface ??
	}

	function testGetSession()
	{
		if (headers_sent()) {
			return $this->_reporter->setMissingTestCase('Test unreliable: headers_sent()');
		}
		if (!class_exists('JFilterInput')) {
			return $this->_reporter->setMissingTestCase('Unhandled class dependency: JFilterInput');
		}

		$obj =& JFactory::getSession();
		$this->assertIsA($obj, 'JSession');
		$this->assertIsA($obj, 'JObject');
	}

	function testGetLanguage()
	{
		$obj =& JFactory::getLanguage();
		$this->assertIsA($obj, 'JLanguage');
		$this->assertIsA($obj, 'JObject');
	}

	function testGetDocument()
	{
		$obj =& JFactory::getDocument();
		$this->assertIsA($obj, 'JDocumentHTML');
		$this->assertIsA($obj, 'JObject');
	}

	function testGetCache()
	{
		$obj =& JFactory::getCache();
		$this->assertIsA($obj, 'JCacheCallback');
		$this->assertIsA($obj, 'JObject');
	}

	function testGetTemplate()
	{
		// TODO: Fix undefined index 'option' / 'Itemid'
		settype($GLOBALS['option'], 'string');
		settype($GLOBALS['Itemid'], 'string');

		$obj =& JFactory::getTemplate();
		$this->assertIsA($obj, 'JTemplate');
		$this->assertIsA($obj, 'patTemplate');
	}

	/**
	 * getMailer() mutations
	 */
	function testGetMailer()
	{
		$obj =& JFactory::getMailer();
		$this->assertIsA($obj, 'JMail');
		$this->assertIsA($obj, 'PHPMailer');
	}
	function testGetMailer_smpt()
	{
		JFactoryTestHelper::GetMailer_smpt();

		$obj =& JFactory::getMailer();
		$this->assertEqual($obj->Mailer, 'smtp');
	}
	function testGetMailer_sendmail()
	{
		$config =& JFactory::getConfig();
		$config->setValue('config.mailer', $config->getValue('sendmail'));

		# FIX: JFactory::_createMailer() call to useSendmail()
		$obj =& JFactory::getMailer();
		$this->assertEqual($obj->Mailer, 'sendmail');
	}

	/**
	 * getXMLParser() mutations
	 */
	function testGetXMLParser()
	{
		$obj =& JFactory::getXMLParser();
		$this->assertIsA($obj, 'DOMIT_Lite_Document');
	}

	/**
	 * @todo: fix JFactory::getXMLParser(), see TODO.txt
	 */
	function testGetXMLParser_feeds()
	{
		$expectedError = new PatternExpectation('/Undefined index/', 'JFactory::getXMLParser() %s');

		$this->expectError($expectedError);
		$rss    =& JFactory::getXMLParser('RSS');
		$this->assertNull($rss);

		$this->expectError($expectedError);
		$atom   =& JFactory::getXMLParser('Atom');
		$this->assertNull($atom);
	}

	/**
	 * @todo: find some example live-feeds to validate objects (Devel Google group?)
	 */
	function testGetXMLParser_feeds_live()
	{
		return $this->_reporter->setMissingTestCase('TODO: find some example live-feeds to validate objects');

		$options = array('rssUrl' => 'http://blabla/feed.xml');
		$rss    =& JFactory::getXMLParser('RSS', $options);
		$atom   =& JFactory::getXMLParser('Atom', $options);
		$this->assertIsA($rss,  'SimplePie');
		$this->assertIsA($atom, 'SimplePie');
	}

	function testGetXMLParser_xml()
	{
		$simple =& JFactory::getXMLParser('Simple');
		$this->assertIsA($simple, 'JSimpleXML');
		$this->assertIsA($simple, 'JObject');
	}
	function testGetXMLParser_domdefault()
	{
		$dom    =& JFactory::getXMLParser('DOM');
		$std    =& JFactory::getXMLParser();
	}

	function testGetEditor()
	{
		$obj =& JFactory::getEditor();
		$this->assertIsA($obj,'JEditor');
		$this->assertIsA($obj, 'JObject');
	}

	function testGetURI()
	{
		$obj =& JFactory::getURI();
		$this->assertIsA($obj, 'JURI');
		$this->assertIsA($obj, 'JObject');
	}

	/**
	 * Database related stuff require a working database setup which is
	 * prepared in {@link JFactoryTestHelper::GetDBO()}
	 */
	function testGetDBO()
	{
		JFactoryTestHelper::GetDBO();

		return $this->_reporter->setMissingTestCase('cannot test now, need to resolve prerequisites');

		$obj =& JFactory::getDBO();
		// flag testcase to skip
		$this->_should_skip = $this->assertIsA($obj, 'JDatabaseMySQL');

		if (!$this->_should_skip) {
			return $this->_reporter->setMissingTestCase('getDBO() failure, expect skip');
		}

		$this->assertIsA($obj, 'JDatabase');
		$this->assertIsA($obj, 'JObject');
	}

	function testGetUser()
	{
		return $this->_reporter->setMissingTestCase('depends on testGetDBO()');

		$obj =& JFactory::getUser();
		$this->assertIsA($obj, 'JUser');
		$this->assertIsA($obj, 'JObject');
	}

	function testGetACL()
	{
		return $this->_reporter->setMissingTestCase('depends on testGetDBO()');

		$obj =& JFactory::getACL();
		$this->assertIsA($obj, 'JAuthorization');
		$this->assertIsA($obj, 'JObject');
	}

}

// Call TestOfJFactory::main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'TestOfJFactory::main') {
	TestOfJFactory::main();
}
