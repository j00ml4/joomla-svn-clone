<?php
/**
 * Joomla! Unit Test Facility.
 *
 * @version $Id: $
 * @package Joomla
 * @subpackage UnitTest
 */

!defined('JUNIT_MAIN_METHOD') && define('JUNIT_MAIN_METHOD', 'UnitTestController::main');

require_once (dirname(__FILE__) . '/setup.php');

/* called from a TestCase */
if (JUNIT_MAIN_METHOD !== 'UnitTestController::main') {
	return 1;
}

/**
 * The Controller ...
 */
class UnitTestController {

	/**
	 * @param object $fileinfo info object
	 */
	function main($fileinfo, $label='') {

		if (is_string($fileinfo)) {
			$fileinfo = UnitTestHelper::getInfoObject($fileinfo);
		}

		/* create Skeleton(s), CLI + PHP5 only! */
		if (JUNIT_CLI) {
			die(jutdump('Sorry! Skeleton builder is currently disabled.'));
//            $files = UnitTestController::makeUnitTestFiles($source);
//            return UnitTestController::main($path, $label);
		}

		$suite = new TestSuite(!empty($label) ? $label : $fileinfo->package .': '.$fileinfo->basename);

		$suite->addTestFile(JUNIT_ROOT .'/'. JUNIT_BASE .'/'. $fileinfo->path);

/*
require_once(TEST_LIBRARY.'collector.php');
$collector = &new SimplePatternCollector('/Test\.php$/');
$suite->collect($path, &$collector)
*/
		return $suite->run(UnitTestHelper::getReporter());

	}

	function makeUnitTestFiles($path) {
		$files  = array ();
		$folder = array ();

		if (is_dir($path)) {
			$files = UnitTestController::_files($path, true);
		}
		elseif (is_file($path)) {
			$files = array ($path);
		}

		foreach ($files as $file) {
			$folder[] = UnitTestController::makeUnitTestFile($file);
		}
		return array_merge($files, $folder);
	}

	function makeUnitTestFile($sourceFile) {
		$files= array ();
		// TODO: legacy will need to be done by hand
		// NOTE: probably solved since legacy code moved to CMS /plugins/system/legacy
		if (strpos($sourceFile, 'legacy'))
			return $files;
		// TODO: patTemplate will need to be done by hand
		if (strpos($sourceFile, 'template'))
			return $files;

		// make absolute paths
		$target     = $sourceFile;
		$sourceFile = $sourceFile;

		// Create the dir if not found
		$dir = dirname($target);

echo "<br>makeUnitTestFile($sourceFile)<br>";
print_r(array($sourceFile,$target,$dir));
if (1) return array ();

		if (!is_dir($dir)) {
			mkdir($dir, 0777, true);
		}

		$classes = UnitTestController::_getClassFromFile($sourceFile);

		foreach ($classes as $class => $extends) {
			$testfile = dirname($target) . DIRECTORY_SEPARATOR . $class . 'Test.php';

			// Check if the file is already in the test dir, if it is continue
			if (file_exists($testfile)) {
				continue;
			}
			$handle = fopen($testfile, 'wb');
			if (((int) PHP_VERSION >= 5) && class_exists('Reflection')) {
				$skeleton = UnitTestController::getUnitTestTemplate($class, $sourceFile, $extends);
			}
			fwrite($handle, $skeleton);
			fclose($handle);
			$files[] = $testfile;
		}
		return $files;
	}

	/**
	 * PHP5 only, but more accurate TestCase Skeletons -- thanx to PHPUnit :)
	 */
	function getUnitTestTemplate($class, $file, $extends= false) {
		if (!class_exists($class, false)) {
			return;
		}
		if (!class_exists('PHPUnit_Util_Skeleton', false)) {
			UnitTestHelper::loadFile('Skeleton.php', JUNIT_LIBS . '/Util', true);
		}
		$Skeleton = new PHPUnit_Util_Skeleton($class, $file);
		return $Skeleton->generate(false);
	}

	/**
	 * Retrieves all available testcases and testsuits.
	 *
	 * Creates and populates static properties:
	 * - 'Controller', 'Tests'
	 * - 'Controller', 'Disabled'
	 * - 'Controller', 'Enabled'
	 *
	 * @uses UnitTestHelper::getProperty()
	 * @return array all tests
	 */
	function &getUnitTestsList()
	{
		$path  = JUNIT_ROOT .'/'. JUNIT_BASE;
		$files = UnitTestController::_files($path, true);

		$tests    =& UnitTestHelper::getProperty('Controller', 'Tests', 'array');
		$disabled =& UnitTestHelper::getProperty('Controller', 'Disabled', 'array');
		$enabled  =& UnitTestHelper::getProperty('Controller', 'Enabled', 'array');

		foreach($files as $path) {
			$info = UnitTestHelper::getInfoObject($path);
			$tests[$path] = $info;
			if ($info->enabled) {
				$enabled[$path]  = &$tests[$path];
			} else {
				$disabled[$path] = &$tests[$path];
			}
		}
		ksort($tests);

		return $tests;
	}

	/**
	 * @param string $path full qualified path
	 */
	function _files($path, $showFolders=false) {

		// Initialize variables
		$path    = rtrim($path, '\\/');
		$files   = array();
		$folder  = array();
		$xfolder = array('.', '..', '.svn', '_files');

		// Is $path as file?
		if (!is_dir($path)) {
			return $files;
		}

		// First get Files from the Dir
		$l = strlen(JUNIT_ROOT .'/'. JUNIT_BASE) + 1;
		foreach (glob($path .'/*.php') as $filename) {
			if (substr(basename($filename), 0, 1) != '_') {
				$files[] = substr($filename, $l);
			}
		}

		if ($showFolders) {
			foreach (glob("$path/*", GLOB_ONLYDIR) as $foldername) {
				$path = substr($foldername, $l);

				if (false === in_array(basename($path), $xfolder)) {
					$folder = UnitTestController::_files($foldername, $showFolders);
				}
			$files = array_merge($files, $folder);
			}
		}

		return $files;
	}

	/**
	 * @todo: fix to read ALL classes incl. Helpers
	 */
	function _getClassFromFile($path) {
		include_once ($path);

		$lines = file($path);
		$found = array();

		$classes = preg_grep('/^\s?class\sJ[A-Z][a-z]+/', $lines);
		foreach ($classes as $decl) {
			$tokens = preg_split('/\s+/', trim($decl));
			if (isset ($tokens[1])) {
				$found[$tokens[1]]= isset ($tokens[3]) ? $tokens[3] : false;
			}
		}
		return $found;
	}
}

if (JUNIT_MAIN_METHOD == 'UnitTestController::main') {
	UnitTestController::main(JUNIT_BASE, JUNIT_REPORTER);
}

