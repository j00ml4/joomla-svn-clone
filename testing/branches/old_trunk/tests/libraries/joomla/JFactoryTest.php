<?php
/**
 * @version		$Id$
 * @package		Joomla.UnitTest
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU General Public License
 */

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
require_once JPATH_LIBRARIES.'/joomla/factory.php';

# TODO: should be handled by factory.php
require_once JPATH_LIBRARIES.'/joomla/filter/filterinput.php';

class TestOfJFactory extends PHPUnit_Framework_TestCase
{
var $have_db = false;

	function tearDown()
	{
		$config =& JFactory::getConfig();
		$config = null;
	}

	function testGetConfig()
	{
		$obj =& JFactory::getConfig();
		$this->assertType($obj, 'JRegistry');
		$this->assertType($obj, 'JObject');
	}

	function testGetConfig_reference()
	{
		$first  =& JFactory::getConfig();
		$second =& JFactory::getConfig();
		$this->assertSame($first, $second);

		// creates namespace 'foo' with item 'bar' = 'baz'
		$second->setValue('foo.bar', 'baz');
		$this->assertSame($first, $second);

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
		$this->assertType($obj, 'JSession');
		$this->assertType($obj, 'JObject');
	}

	function testGetLanguage()
	{
		$obj =& JFactory::getLanguage();
		$this->assertType($obj, 'JLanguage');
		$this->assertType($obj, 'JObject');
	}

	function testGetDocument()
	{
		$obj =& JFactory::getDocument();
		$this->assertType($obj, 'JDocumentHTML');
		$this->assertType($obj, 'JObject');
	}

	function testGetCache()
	{
		$obj =& JFactory::getCache();
		$this->assertType($obj, 'JCacheCallback');
		$this->assertType($obj, 'JObject');
	}

	function testGetTemplate()
	{
		// TODO: Fix undefined index 'option' / 'Itemid'
		settype($GLOBALS['option'], 'string');
		settype($GLOBALS['Itemid'], 'string');

		$obj =& JFactory::getTemplate();
		$this->assertType($obj, 'JTemplate');
		$this->assertType($obj, 'patTemplate');
	}

	/**
	 * getMailer() mutations
	 */
	function testGetMailer()
	{
		$obj =& JFactory::getMailer();
		$this->assertType($obj, 'JMail');
		$this->assertType($obj, 'PHPMailer');
	}
	function testGetMailer_smpt()
	{
		require 'JFactory.helper.php';
		JFactoryTestHelper::GetMailer_smpt();

		$obj =& JFactory::getMailer();
		$this->assertEqualss($obj->Mailer, 'smtp');
	}
	function testGetMailer_sendmail()
	{
		$config =& JFactory::getConfig();
		$config->setValue('config.mailer', $config->getValue('sendmail'));

		# FIX: JFactory::_createMailer() call to useSendmail()
		$obj =& JFactory::getMailer();
		$this->assertEquals($obj->Mailer, 'sendmail');
	}

	/**
	 * getXMLParser() mutations
	 */
	function testGetXMLParser()
	{
		// @TODO: This doesn't work properly
		/*
		$obj =& JFactory::getXMLParser();
		$this->assertType($obj, 'DOMIT_Lite_Document');
		*/
	}

	/**
	 * @todo: fix JFactory::getXMLParser(), see TODO.txt
	 */
	function testGetXMLParser_feeds()
	{
		// @TODO: This doesn't work properly
		/*
		$expectedError = new PatternExpectation('/Undefined index/', 'JFactory::getXMLParser() %s');

		$this->expectError($expectedError);
		$rss    =& JFactory::getXMLParser('RSS');
		$this->assertNull($rss);

		$this->expectError($expectedError);
		$atom   =& JFactory::getXMLParser('Atom');
		$this->assertNull($atom);
		*/
	}

	/**
	 * @todo: find some example live-feeds to validate objects (Devel Google group?)
	 */
	function testGetXMLParser_feeds_live()
	{
		// @TODO: This doesn't work properly
		/*
		return $this->_reporter->setMissingTestCase('TODO: find some example live-feeds to validate objects');

		$options = array('rssUrl' => 'http://blabla/feed.xml');
		$rss    =& JFactory::getXMLParser('RSS', $options);
		$atom   =& JFactory::getXMLParser('Atom', $options);
		$this->assertType($rss,  'SimplePie');
		$this->assertType($atom, 'SimplePie');
		*/
	}

	function testGetXMLParser_xml()
	{
		// @TODO: This doesn't work properly
		/*
		$simple =& JFactory::getXMLParser('Simple');
		$this->assertType($simple, 'JSimpleXML');
		$this->assertType($simple, 'JObject');
		*/
	}
	function testGetXMLParser_domdefault()
	{
		// @TODO: This doesn't work properly
		/*
		$dom    =& JFactory::getXMLParser('DOM');
		$std    =& JFactory::getXMLParser();
		*/
	}
/*
	function testGetEditor()
	{
		$obj =& JFactory::getEditor();
		$this->assertType($obj,'JEditor');
		$this->assertType($obj, 'JObject');
	}

	function testGetURI()
	{
		$obj =& JFactory::getURI();
		$this->assertType($obj, 'JURI');
		$this->assertType($obj, 'JObject');
	}
*/
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
		$this->_should_skip = $this->assertType($obj, 'JDatabaseMySQL');

		if (!$this->_should_skip) {
			return $this->_reporter->setMissingTestCase('getDBO() failure, expect skip');
		}

		$this->assertType($obj, 'JDatabase');
		$this->assertType($obj, 'JObject');
	}

	function testGetUser()
	{
		return $this->_reporter->setMissingTestCase('depends on testGetDBO()');

		$obj =& JFactory::getUser();
		$this->assertType($obj, 'JUser');
		$this->assertType($obj, 'JObject');
	}

	function testGetACL()
	{
		return $this->_reporter->setMissingTestCase('depends on testGetDBO()');

		$obj =& JFactory::getACL();
		$this->assertType($obj, 'JAuthorization');
		$this->assertType($obj, 'JObject');
	}
}
