<?php
/**
 * Joomla! v1.5 UnitTest Platform
 *
 * @version $Id$
 * @package Joomla
 * @subpackage UnitTest
 */

require_once TEST_LIBRARY.'xml.php';

class JoomlaXml extends XmlReporter
{
var $_character_set = 'UTF-8';
var $_namespace;
var $_indent = '    ';

	function JoomlaXml($namespace=false, $indent='    ') {
		$this->SimpleReporter();
		if ($namespace) {
			$this->_namespace = trim($namespace, ':');
			if (!empty($this->_namespace)) {
				$this->_namespace .= ':';
			}
		}
		$this->_indent = $indent;
		$this->sendNoCacheHeaders();
	}

	function sendNoCacheHeaders() {
		if (! SimpleReporter::inCli() && ! headers_sent()) {
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
			header('Cache-Control: no-store, no-cache, must-revalidate');
			header('Cache-Control: post-check=0, pre-check=0', false);
			header('Pragma: no-cache');
			header('Content-Type: text/xml; charset=UTF-8', true);
			header('Content-Encoding: '.$this->_character_set, true);
		}
		ob_start();
	}

	function paintHeader($test_name) {
		$xmlns = ($this->_namespace)
				? ' xmlns:'. rtrim($this->_namespace, ':') .'="http://www.joomla.org/unittests"'
				: '';
		echo    '<?xml version="1.0" encoding="utf-8"', '?>',
				PHP_EOL,
				'<', $this->_namespace, 'run', $xmlns, '>', PHP_EOL;
	}

	function paintFooter($test_name) {
		echo '</', $this->_namespace, 'run>', PHP_EOL;
	}

	function toParsedXml($text) {
		$text = parent::toParsedXml($text);
		return str_replace(array('['.JUNIT_ROOT.'/', '['.JUNIT_ROOT.'\\'), '[', $text);
	}
}

