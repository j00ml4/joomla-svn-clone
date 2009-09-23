<?php
/**
 * Joomla! v1.5 Unit Test Facility
 *
 * @package Joomla
 * @subpackage UnitTest
 * @copyright Copyright (C) 2005 - 2008 Open Source Matters, Inc.
 * @version $Id: $
 *
 */

if (!defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'JURITest_IsInternal::main');
	$JUnit_home = DIRECTORY_SEPARATOR . 'unittest' . DIRECTORY_SEPARATOR;
	if (($JUnit_posn = strpos(__FILE__, $JUnit_home)) === false) {
		die('Unable to find ' . $JUnit_home . ' in path.');
	}
	$JUnit_posn += strlen($JUnit_home) - 1;
	$JUnit_root = substr(__FILE__, 0, $JUnit_posn);
	$JUnit_start = substr(
		__FILE__,
		$JUnit_posn + 1,
		strlen(__FILE__) - strlen(basename(__FILE__)) - $JUnit_posn - 2
	);
	require_once $JUnit_root . DIRECTORY_SEPARATOR . 'setup.php';
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
class JRegistry {

	static $liveSite;

	function getValue($regpath, $default = null) {
		if ($regpath == 'config.live_site') {
			return self::$liveSite;
		}
		return $default;
	}

}

class JFactory {
	function &getConfig($file = null, $type = 'PHP') {
		$config = new JRegistry();
		return $config;
	}
}

/*
 * We now return to our regularly scheduled environment.
 */
require_once JPATH_LIBRARIES . '/joomla/import.php';

jimport('joomla.environment.uri');

class JURITest_IsInternal extends PHPUnit_Framework_TestCase {
	/**
	 * Scratch storage for the environment's live_site
	 *
	 * @var string
	 */
	public $liveSiteSave;

	/**
	 * Runs the test methods of this class.
	 *
	 * @access public
	 * @static
	 */
	function main() {
		$suite  = new PHPUnit_Framework_TestSuite(__CLASS__);
		$result = PHPUnit_TextUI_TestRunner::run($suite);
	}

	/**
	 * Generate data set for isInternal.
	 *
	 * This test coded somewhat quickly, it should be extracted to a stand-alone
	 * class.
	 */
	static public function isInternalData() {
		/*
		 * Non-empty live sites will have http:// added.
		 */
		$liveSites = array(
			'',
			'www.example.com',
			'www.example.com/subdomain'
		);
		/*
		 * Base urls with expected result: 0 = false; 1 = true; 2 = true if
		 * live_site not empty and url begins with live_site;
		 */
		$base = array(
			array('/foo', 1),
			array ('foo/', 1),
			array('/foo/', 1),
			array('http://www.external.com/foo', 0),
			array ('http://www.external.com/foo/', 0),
			array('https://www.external.com/foo', 0),
			array ('https://www.external.com/foo/', 0),
			array('ftp://www.external.com/foo', 0),
			array ('ftp://www.external.com/foo/', 0),
			array('http://www.example.com', 2),
			array('http://www.example.com/subdomain', 2),
			array('https://www.example.com', 2),
			array('https://www.example.com/subdomain', 2),
			array('ftp://www.example.com', 0),
			array('ftp://www.example.com/subdomain', 0),
		);
		$requests = array(
			'',
			'?bar',
			'?bar=2',
			'?bar&snafu',
			'?bar=2&snafu',
			'?bar=2&snafu=4',
			'?bar&snafu=4',
			'?bar&amp;valid',
			'?bar=2&amp;valid',
			'?bar=2&amp;valid=4',
			'?bar&amp;valid=4',
			'?url=http://www.example.com/invalid',
			'?url=' . urlencode('http://www.example.com/valid'),
		);
		$dataSet = array();
		foreach ($liveSites as $site) {
			$dataSet[] = array($site, null, false);
			foreach ($base as $def) {
				switch($def[1]) {
					case 0: {
						$expect = false;
					}
					break;

					case 1: {
						$expect = true;
					}
					break;

					case 2: {
						if ($site == '') {
							$expect = false;
						} else {
							$scan = strpos($def[0], '//');
							$posn = strpos($def[0], $site, $scan + 2);
							//echo $site . ' def=' . $def[0] . ' scan=' . $scan . ' posn=' . $posn;
							//exit();
							$expect = ($posn === 0);
						}
					}
					break;
				}
				if ($site == '') {
					$start = $site;
				} else {
					$start = 'http://' . $site;
				}
				foreach ($requests as $req) {
					$dataSet[] = array($start, $def[0] . $req, $expect);
				}
			}
		}
		return $dataSet;
	}

	/**
	 * Test JURI::isInternal()
	 *
	 * @dataProvider isInternalData
	 */
	function testIsInternal($site, $url, $expect) {
		$_SERVER['HTTP_HOST'] = 'www.example.com';
		$_SERVER['PHP_SELF'] = '/index.php';
		JRegistry::$liveSite = $site;
		$actual = JURI::isInternal($url);
		$this -> assertEquals($actual, $expect, 'URL: ' . $url);
	}

}

// Call main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'JURITest_IsInternal::main') {
	JURITest_IsInternal::main();
}
