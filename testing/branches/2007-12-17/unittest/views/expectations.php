<?php
/**
 * Some derived SimpleExpectation classes to validate complex
 * data structures using assertExpectation()
 *
 * PHP 5 only!
 *
 * <code>
 *     $this->assertExpectation(
 *            new MyExpectation($data),
 *            $compare,
 *            $message);
 * </code>
 *
 * If you can't test for true/false and none of the available assertXxx()
 * methods make sense, wite a new Expectation class to teach Simpletest
 * a new assertion.
 * - <var>__constructor()</var> should usually accept the expected result
 *                                 as it's first and only argument.
 * - <var>test()</var> must return true/false.
 * - <var>testMessage()</var> formats a nice Fehlermeldung
 *
 * More information {@link http://www.simpletest.org/} and the Simpletest sources.
 *
 * @package Joomla
 * @subpackage UnitTest
 * @author         CirTap <cirtap-joomla@webmechanic.biz>
 * @version $Id$
 */

// $Id$

/**
 * Test if an error is any JError.
 */
class JErrorAnyExpectation extends SimpleExpectation
{

/* EXPECTED JError taken from the constructor for compare */
private $_value = null;

/* list of 'failures' */
private $_failures = array();

/* expected error items for strict expectations */
private $_has_items   = array();

/* expected error values for strict expectations */
private $_value_items = array();

	/**
	 * @param array $jerror the expected JError
	 */
	function __construct($jerror = array(), $message = '%s') {
		parent::SimpleExpectation($message);
		$this->_value   = $jerror;
		$this->_message = $message;
	}

	function test($compare) {
		$isError = JError::isError($compare);

		if (count($this->_has_items) > 0) {
			if ($s = $this->_hasItems($compare)) {
				$isError &= $s;
			}
		}
		if (count($this->_value_items) > 0) {
			if ($v = $this->_hasValues($compare)) {
				$isError &= $v;
			}
		}

		return $isError;
	}

	function testMessage(&$compare) {
		if ($this->test($compare)) {
			$message = 'JError [' . $this->describeValue($this->_value) . ']';
		} else {
			$message = 'JError failure ' . $this->describeValue($compare);
		}
		if (strpos($this->_message, '%s')) {
			$message = sprintf($this->_message, $message);
		}
		return $message;
	}

	function describeValue(&$error) {
		$message = '';
		foreach ($error as $item => $value) {
			$message .= $item . ' => ' . $value . ', ';
		}
		if (count($this->_failures)) {
			$message .= implode('; ', $this->_failures);
		}
		return rtrim($message, ',;');
	}
}

/**
 * Test a specific JError incl. code and message.
 * Existance: level, code, message, info
 * Values:    level, code
 */
class JErrorStrictExpectation extends JErrorAnyExpectation
{
private $_has_items   = array('level','code','message','info');
private $_value_items = array('level','code');

	private function _hasItems(&$compare) {
		// required elements of the error
		$c = 0;
		foreach ($this->_has_items as $item) {
			if (!isset($compare[$item])) {
				$this->_failures[] = 'missing \'' . $item . '\'';
				$c++;
			}
		}
		return ($c == 0);
	}

	private function _wrongValues(&$compare) {
		$c = 0;
		foreach ($this->_value_items as $item) {
			if ( $compare[$item] != $this->_value[$item]) {
				$this->_failures[] = sprintf('expected %s [%s] not [%s]',
								$item, $this->_value[$item], $compare[$item]);
				$c++;
			}
		}
		return ($c == 0);;
	}

}

/**
 * Test if an error is any JException.
 */
class JExceptionAnyExpectation extends JErrorAnyExpectation
{

private $_has_items   = array('level');
private $_value_items = array('level');

}

