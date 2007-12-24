<?php
/**
 * Joomla! v1.5 UnitTest Platform
 *
 * @package Joomla
 * @subpackage UnitTest
 * @version $Id$
 */

class JoomlaText extends TextReporter {
	function paintHeader($test_name) {
		if (!SimpleReporter :: inCli()) {
			header('Content-type: text/plain');
		}

		flush();

		echo "Testcase: $test_name\n";
	}

	/**
	 * Called at the start of each test method.
	 *
	 * @todo implement, see SimpleReporterDecorator
	 */
	function shouldInvoke($class_name, $method_name) {
		$this->method_name= $method_name;
		$this->_methods[$method_name]= array (
			'start' => true,
			'pass' => 0,
			'fail' => 0,
			'skip' => 0,
			'miss' => false,
			'reason' => false,
			'end' => false
		);

		return !$this->_is_dry_run;
	}

	/**
	 * compat stub for the alternative HTML reporter (CirTap)
	 */
	function setMissingTestCase($method_name, $reason= '') {
		$this->_missing_tests[]= $this->method_name;
		$this->_methods[$this->method_name]['miss']= true;
		// flag as failed
		$this->assertTrue(false, "$method_name needs implementation. " . $reason);
	}

}

