<?php
/**
 * @version		$Id$
 * @package		Joomla.UnitTest
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU General Public License
 */

require 'j.php';

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
JLoader::injectMock(
	dirname(__FILE__) . DS . 'JText-mock-general.php',
	'joomla.text'
);

/**
 * Dummy JHTML class inline
 *
 * @TODO: Use correct mock object syntax
 */
class JHTML
{
	function _() {
		return '';
	}
}

/*
 * We now return to our regularly scheduled environment.
 */
require_once JPATH_LIBRARIES . '/joomla/import.php';

require_once JPATH_BASE . '/administrator/components/com_modules/admin.modules.html.php';

class HTML_modulesTest_Add extends PHPUnit_Framework_TestCase
{
	/**
	 * Faked client object.
	 *
	 * @var stdclass
	 */
	public $client;

	/**
	 * Sample data
	 *
	 * @var array
	 */
	public $samples = array();

	/**
	 * Version mask
	 *
	 * @var array
	 */
	public $versionMask = array('jver_min' => '1.5');

	function checkLinks($modules, $result)
	{
		// Get contents of all links
		if (! preg_match_all('|<a .*module=(.*)&.*>(.*)</a>|U', $result, $matches)) {
			$this->fail('No links found.');
		}
		$this->assertEquals(count($modules), count($matches[0]), 'Number of links.');
		for ($ind = 0; $ind < count($matches[0]); ++$ind) {
			$this->assertEquals(
				$matches[1][$ind][0],
				$matches[2][$ind][0],
				$matches[1][$ind][0] . ' does not match ' . $matches[2][$ind][0]
			);
			$found = false;
			foreach ($modules as $key => $item) {
				if ($item->module == $matches[1][$ind]) {
					unset($modules[$key]);
					$found = true;
					break;
				}
			}
			if (! $found) {
				$this->fail('Duplicate module in output ' . $matches[1][$ind]);
			}
		}
		$this->assertEquals(0, count($modules), 'Missing modules.');
	}

	function checkRadios($modules, $result)
	{
		// Get contents of all radio buttons
		preg_match_all('|<input .*type="radio".*>|U', $result, $matches);
		// Filter for name=module
		foreach ($matches[0] as $index => $subs) {
			if (strpos($subs, 'name="module"') === false) {
				unset($matches[0][$index]);
			}
		}
		$this->assertEquals(count($modules), count($matches[0]), 'Number of radio buttons.');
	}

	function htmlWrap($html)
	{
		$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"'
			. ' "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . chr(10)
			. '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb">' . chr(10)
			. '<head><title>hello</title></head>' . chr(10)
			. '<body>' . chr(10)
			. $html . chr(10)
			. '</body></html>' . chr(10);
		return $html;
	}

	function setUp()
	{
		$this->client = new stdclass;
		$this->client->id = 'clientid';
		$this->sampes = array();
		$item = new stdclass;
		$item->name = 'Atest';
		$item->descrip = 'Adesc';
		$item->module = 'Amod';
		$this->samples[] = clone $item;
		$item->name = 'Btest';
		$item->descrip = 'Bdesc';
		$item->module = 'Bmod';
		$this->samples[] = clone $item;
		$item->name = 'Ctest & html';
		$item->descrip = 'Cdesc & html';
		$item->module = 'Cmod';
		$this->samples[] = clone $item;
	}

	function testAddZero()
	{
		if (! JUnit_Setup::isTestEnabled(JVERSION, $this->versionMask)) {
			$this->markTestSkipped('Test disabled for this version.');
			return;
		}
		$modules = array();
		ob_start();
		HTML_modules::add($modules, $this->client);
		$result = ob_get_contents();
		ob_end_clean();
		$this->checkRadios($modules, $result);
	}

	function testAddOne()
	{
		if (! JUnit_Setup::isTestEnabled(JVERSION, $this->versionMask)) {
			$this->markTestSkipped('Test disabled for this version.');
			return;
		}
		$modules = array($this->samples[0]);
		ob_start();
		HTML_modules::add($modules, $this->client);
		$result = ob_get_contents();
		ob_end_clean();
		$this->checkRadios($modules, $result);
		$this->checkLinks($modules, $result);
	}

	function testAddTwo()
	{
		if (! JUnit_Setup::isTestEnabled(JVERSION, $this->versionMask)) {
			$this->markTestSkipped('Test disabled for this version.');
			return;
		}
		$modules = array();
		$modules[] = $this->samples[0];
		$modules[] = $this->samples[1];
		ob_start();
		HTML_modules::add($modules, $this->client);
		$result = ob_get_contents();
		ob_end_clean();
		$this->checkRadios($modules, $result);
		$this->checkLinks($modules, $result);
	}

	function testAddThree()
	{
		if (! JUnit_Setup::isTestEnabled(JVERSION, $this->versionMask)) {
			$this->markTestSkipped('Test disabled for this version.');
			return;
		}
		$modules = array();
		$modules[] = $this->samples[0];
		$modules[] = $this->samples[1];
		$modules[] = $this->samples[2];
		ob_start();
		HTML_modules::add($modules, $this->client);
		$result = ob_get_contents();
		ob_end_clean();
		$this->checkRadios($modules, $result);
		$this->checkLinks($modules, $result);
	}

	function testAddZeroTidy()
	{
		if (! JUnit_Setup::isTestEnabled(JVERSION, $this->versionMask)) {
			$this->markTestSkipped('Test disabled for this version.');
			return;
		}
		if (! function_exists('tidy_error_count')) {
			$this->markTestSkipped('Tidy not available.');
		}
		$modules = array();
		ob_start();
		HTML_modules::add($modules, $this->client);
		$result = ob_get_contents();
		ob_end_clean();
		$tidy = tidy_parse_string($this->htmlWrap($result));
		$this->assertEquals(0, tidy_error_count($tidy), 'Tidy errors ' . $tidy->errorBuffer);
		$this->assertEquals(0, tidy_warning_count($tidy), 'Tidy warnings ' . $tidy->errorBuffer);
	}

	function testAddOneTidy()
	{
		if (! JUnit_Setup::isTestEnabled(JVERSION, $this->versionMask)) {
			$this->markTestSkipped('Test disabled for this version.');
			return;
		}
		if (! function_exists('tidy_error_count')) {
			$this->markTestSkipped('Tidy not available.');
		}
		$modules = array($this->samples[0]);
		ob_start();
		HTML_modules::add($modules, $this->client);
		$result = ob_get_contents();
		ob_end_clean();
		$tidy = tidy_parse_string($this->htmlWrap($result));
		$this->assertEquals(0, tidy_error_count($tidy), 'Tidy errors ' . $tidy->errorBuffer);
		$this->assertEquals(2, tidy_warning_count($tidy), 'Tidy warnings ' . $tidy->errorBuffer);
	}

	function testAddTwoTidy()
	{
		if (! JUnit_Setup::isTestEnabled(JVERSION, $this->versionMask)) {
			$this->markTestSkipped('Test disabled for this version.');
			return;
		}
		if (! function_exists('tidy_error_count')) {
			$this->markTestSkipped('Tidy not available.');
		}
		$modules = array();
		$modules[] = $this->samples[0];
		$modules[] = $this->samples[1];
		ob_start();
		HTML_modules::add($modules, $this->client);
		$result = ob_get_contents();
		ob_end_clean();
		$tidy = tidy_parse_string($this->htmlWrap($result));
		$this->assertEquals(0, tidy_error_count($tidy), 'Tidy errors ' . $tidy->errorBuffer);
		$this->assertEquals(4, tidy_warning_count($tidy), 'Tidy warnings ' . $tidy->errorBuffer);
	}

	function testAddThreeTidy()
	{
		if (! JUnit_Setup::isTestEnabled(JVERSION, $this->versionMask)) {
			$this->markTestSkipped('Test disabled for this version.');
			return;
		}
		if (! function_exists('tidy_error_count')) {
			$this->markTestSkipped('Tidy not available.');
		}
		$modules = array();
		$modules[] = $this->samples[0];
		$modules[] = $this->samples[1];
		$modules[] = $this->samples[2];
		ob_start();
		HTML_modules::add($modules, $this->client);
		$result = ob_get_contents();
		ob_end_clean();
		$tidy = tidy_parse_string($this->htmlWrap($result));
		$this->assertEquals(0, tidy_error_count($tidy), 'Tidy errors ' . $tidy->errorBuffer);
		$this->assertEquals(6, tidy_warning_count($tidy), 'Tidy warnings ' . $tidy->errorBuffer);
	}

}