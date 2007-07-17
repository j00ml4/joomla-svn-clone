<?php
/**
 * WebMechanicRenderer für UnitTests
 *
 * @package 	ScriptBase
 * @subpackage 	UnitTests
 * @author 		Rene Serradeil <serradeil@webmechanic.biz>
 * @copyright 	Copyright (c)2006-2007, media++|webmechanic.biz
 * @version 	$Rev$ $Date$
 * @filesource
 */

// $Id$

/**
 * Output format provided by the WebMechanicRenderer class.
 */
define('JUNITTEST_REPORTER_CUSTOM_FORMAT', 'html');

/**
 * Ein gegenüber HtmlReporter() übersichtlicherer Renderer.
 * Auf die Instanz kann in den TestCases via <var>$this->_reporter</var>
 * zugegriffen werden.
 *
 * @package 	ScriptBase
 * @subpackage 	UnitTests
 */
class WebMechanicRenderer extends SimpleReporter
{
var $_character_set;
var $test_name;
var $method_name;

/**
 * stats.
 * keys: start (bool), end (bool), pass (int), fail (int), miss (bool), skip (bool)
 */
var $_methods = array();

/** @see setMissingTestCase() */
var $_missing_tests = array();

var $_skipped = array();

	function WebMechanicRenderer($character_set = 'UTF-8') {
		$this->__construct($character_set);
	}

	function __construct($character_set = 'UTF-8') {
		$this->SimpleReporter();
		$this->_character_set = $character_set;
		$this->_env = 'PHP '.PHP_VERSION .' as '. PHP_SAPI .' on '. PHP_OS;
		$this->sendNoCacheHeaders();
	}

	function sendNoCacheHeaders() {
		if (! headers_sent() ) {
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header("Content-Type: text/html; charset=".$this->_character_set);
			header("Content-Encoding: ".$this->_character_set);
		}
		ob_start();
	}

    function paintHeader($test_name) {
    	$this->test_name = $test_name;
    	$verbose = (JUNITTEST_REPORTER_RENDER_PASSED)
    			 ? '<small title="shows passed tests (JUNITTEST_REPORTER_RENDER_PASSED = true)">(verbose)</small>'
    			 : '<small title="hides passed tests (JUNITTEST_REPORTER_RENDER_PASSED = false)">(compact)</small>';
		$title = <<<HTML
	<h1 style="cursor:pointer" onclick="location.href='/unittest/'">
	<span>{$test_name}</span> {$verbose}
	</h1>
HTML;

		if (! headers_sent() ) {
    	echo <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
<title>{$this->test_name}</title>
<meta http-equiv="Content-Type" content="text/html; charset={$this->_character_set}"/>
<meta http-equiv="Content-Encoding" content="{$this->_character_set}"/>
<link rel="StyleSheet" href="/unittest/views/renderer.css" type="text/css" />
</head>
<body>
<div class="Header">
	{$title}
	<p><small>{$this->_env}</small></p>
</div>
<div id="Content" class="MainContent">

HTML;
		# echo $this->testgroup->generateMenu();

		} else {
			echo $title;
		}
    }

    function paintFooter($test_name) {
    	$p = $this->getTestCaseProgress();
    	$c = $this->getTestCaseCount();
		// amount
        $pc = $this->getPassCount();
        $fc = $this->getFailCount();
        $ec = $this->getExceptionCount();
		// percent
		$tc = $pc + $fc;
		if ($tc > 0) {
			$pp = (int)((100 / $tc) * $pc);
			$fp = (int)((100 / $tc) * $fc);
		} else {
			$pp = $fp = 0;
		}
		// methods

//	print_r($this->_skipped);

    	echo <<<HTML

	<div class="tests-total">
	$p / $c Test Cases run.
	Tests: $tc
	</div>
</div>
<div id="Footer">
	<p>{$this->_env}</p>
</div>
HTML;

		echo '</body></html>';

		while (@ob_end_flush());
    }

	/**
	 * Paints the start of a test case (file).
	 */
	function paintCaseStart($case_name) {
		parent::paintCaseStart($case_name);
		$cp = $this->getTestCaseProgress() + 1;
		$cc = $this->getTestCaseCount();

		echo <<<HTML

	<table class="testcase">
	<thead>
	<tr><th colspan="2">({$cp}/$cc) {$case_name}</th></tr>
	</thead>
HTML;

	}

	/**
	 * Paints the end of a test case (file).
	 */
	function paintCaseEnd($case_name) {
		parent::paintCaseEnd($case_name);

		$mpass = array();
		$mfail = array();
		foreach ($this->_methods as $method_name => $stats) {
			if ( in_array($method_name, $this->_missing_tests) ) {
				$reason = ($this->_methods[$method_name]['reason'])
						? ' style="cursor:help" title="'.$this->_methods[$method_name]['reason'].'"'
						: '' ;
				$method_name = '<span class="miss"'.$reason.'>'.$method_name.'</span>';
			}
			($stats['fail'] > 0)
					? array_push($mfail, $method_name)
					: array_push($mpass, $method_name);
		}
		$passed = implode(', ', $mpass);
		$failed = implode(', ', $mfail);

		echo PHP_EOL, '<tbody class="methods-summary"><tr><td colspan="2">';
		if (count($mpass)) {
			echo PHP_EOL,'	<span class="pass">&nbsp;</span><strong>passed: </strong> <tt>'
				, $passed ,' &nbsp;</tt>';
		}
		if (count($mfail)) {
			if (count($mpass)) echo '<br />';
			echo PHP_EOL, '<span class="fail">&nbsp;</span><strong>failed: </strong> <tt>'
				, $failed ,'&nbsp;</tt>';
		}
		echo PHP_EOL, '</td></tr></tbody>
	</table>';

		$this->paintCaseSummary($case_name);
	}

	function paintCaseSummary($case_name) {
		static $pc = 0;
		static $fc = 0;
		static $ec = 0;

//		$testlist = $this->getTestList();

        $pc  = $this->getPassCount() - $pc;
        $fc  = $this->getFailCount() - $fc;
        $ec  = $this->getExceptionCount() - $ec;
		$sc  = $this->_skipped;
		$mc  = count($this->_missing_tests);

		$tmc = count($this->_methods);
        $css = ($fc + $ec > 0) ? 'fail' : 'pass';

        $pcss  = ($pc > 0) ? ' class="pass"' : '';
        $fcss  = ($fc > 0) ? ' class="fail"' : '';
        $ecss  = ($ec > 0) ? ' class="exception"' : '';

        $missing = ($mc > 0)
        		 ? '<span class="miss"><b>'. $mc .'</b> OPEN</span>'
        		 : '';

		// leerer UnitTestCase Klasse (keine test_xx Methoden) ?
		if ( ($pc+$fc+$ec) == 0) {
			$css = 'fail';
		}

        echo PHP_EOL, '<div class="tests-total ', $css, '"><b>', $case_name, '</b> run: ';
        echo $this->getTestCaseProgress() ,'/', $this->getTestCaseCount();
    	echo <<<HTML

	using <b>{$tmc}</b> cases
	&mdash;
	assertions:
	<span{$pcss}><b>{$pc}</b> passed</span>
	$missing
	<span{$fcss}><b>{$fc}</b> failed</span> and
	<span{$ecss}><b>{$ec}</b> exceptions</span>
	</div>

HTML;

		$this->_methods = array();
		$this->_missing_tests = array();

	}

	/**
	 * Called at the start of each test method.
	 *
	 * @todo implement, see SimpleReporterDecorator
	 */
	function shouldInvoke($class_name, $method_name) {
		$this->method_name = $method_name;
		$this->_methods[$method_name] = array(
				'start'  => true,
				'pass'   => 0,
				'fail'   => 0,
				'skip'   => 0,
				'miss'   => false,
				'reason' => false,
				'end'    => false
				);

		return true;
	}

	/* backs out of the test started with the same name. */
	function paintMethodEnd($method_name) {
		$this->method_name = null;
		$this->_methods[$method_name]['end'] = true;
	}

	/**
	 * In test_ Methoden die angelegt, aber noch nicht fertig geschrieben sind
	 * sollte diese Funktion aufgerufen werden, damit im Testergebnis angezeigt
	 * wird, welche Testcases noch unvollständig sind.
	 * <samp>
	 * function test_ifStuffIsImplemented() {
	 *     return $this->_reporter->setMissingTestCase(__FUNCTION__);
	 * }
	 * </samp>
	 */
	function setMissingTestCase($reason='Implement') {
		$this->_missing_tests[] = $this->method_name;
		$this->_methods[$this->method_name]['miss'] = true;
		if ($reason) {
			$this->_methods[$this->method_name]['reason'] = $this->_htmlEntities($reason);
		}
		return count($this->_missing_tests);
	}

	function getMissingTestCases() {
		if (($mc = count($this->_missing_tests)) == 0) return '';

		return '
	<p><span class="miss"><b>'. $mc .' OPEN:</b></span>
	<samp style="cursor:help">' . implode('(), ', $this->_missing_tests) . '()</samp>
	</p>';
	}

	/* Callable from inside a UnitTestCase using $this->sendMessage() */
	function PaintMessage($message) {
		echo $this->method_name ,': ', $message;
	}

	function paintFormattedMessage($message) {
		$this->dumpCode($message, 'Code');
	}

	/* Paints a formatted ASCII message such as a variable dump. */
	function dumpCode($data, $label='') {
	static $title = '<div title="Click the [+] to toggle debug output" class="DbgPrint" onclick="this.style.zIndex+=100" style="position:relative;text-align:left;line-height:normal;width:98%;background-color:#ddd;color:black;border:1px solid gray;margin:2px;padding:0;">{%click%}&nbsp;<samp class="DbgPrint" style="font-size:85%;color:inherit;cursor:help;" onclick="try{this.parentNode.firstChild.onclick()}catch(e){}">{%rem%}</samp> ';
	static $click = '<span title="Click here to toggle debug output" style="padding:0;color:#c00;cursor:help;" onclick="var cn=this.parentNode.childNodes;var s=cn[cn.length-1].style;s.display=(s.display==\'none\')?\'\':\'none\';">[+]</span>';
	static $lines = 14;

		echo str_replace(array('{%click%}','{%rem%}'), array($click,gettype($data).' '.$label), $title);
		echo "\n<textarea class='DbgPrint' title='' style='display:none;position:relative;font-family:monospace;font-size:95%;line-height:normal;width:100%;height:{$lines}em;background:white;color:black;border:1px solid gray;margin:0px;padding:0px 4px;'>";

		if ( is_scalar($data) ) {
			echo htmlentities($data, ENT_COMPAT, $this->_character_set);
		} else {
			print_r($data);
		}
		echo "\n</textarea>";
		echo "</div>\n";

	}

	function paintSkip($message) {
		$this->_methods[$this->method_name]['skip'] = true;

        echo <<<HTML

	<tr class="skip"><th>{$this->method_name}</th>
		<td><span class="skip">skipped: </span> {$message}</td></tr>
HTML;

	}

    function paintPass($message) {
    	// increment global counter
		parent::paintPass($message);

		$this->_methods[$this->method_name]['pass']++;

		if (JUNITTEST_REPORTER_RENDER_PASSED == false)
			return;

        echo <<<HTML

	<tr><th>{$this->method_name}</th>
		<td><span class="pass">&nbsp;</span> {$message}</td></tr>
HTML;

    }

    function paintFail($message) {
    	// increment global counter
		parent::paintFail($message);

		$this->_methods[$this->method_name]['fail']++;

		// title, file, test_class, test_method
		$breadcrumb = $this->getTestList();
		$crumbs     = implode(' :: ', $breadcrumb);
		@list ($title, $file, $test_class) = $breadcrumb;
		// original error message with some filename
		//.. at [filepath line nnn]
		if ( strpos($message, $file) !== false ) {
			list($realfile, $filepath) = $this->_filepath($file);
			$message  = str_replace(
							"at [$realfile",
							"<br />in <tt>$filepath</tt>",
							$message);
			$message  = str_replace(
							array('[', ']', '</tt> line '),
							array('<br />[<tt>', '</tt>]', '</tt> line <tt>'),
							$message);
			$message  = rtrim($message, ']');
        } else {
        	$message = $this->_htmlEntities($message);
        }

        echo <<<HTML

	<tr><th>{$this->method_name}</th>
		<td><span class="fail">&nbsp;</span> {$message}</td></tr>
HTML;

	}

	/* Deal with PHP 4 throwing an error or PHP 5 throwing an exception. */
	function paintError($message) {
		static $base;
		if (!isset($base)) $base = realpath(dirname(__FILE__));
		parent::paintError($message);

		$message = $this->_htmlEntities($message);
		$message = str_replace('called in', '<br/>called in: ', $message);
		$message = str_replace('severity', '<br/>severity: ', $message);
		$message = str_replace($base, '.', $message);

        echo '<tr class="phperror"><td colspan="2">', $message, '</td></tr>';
	}

	function paintException($message) {
		static $base;
		if (!isset($base)) $base = realpath(dirname(__FILE__));

		parent::paintException($message);

		$message = str_replace(']', ']<br/>', $message);

        echo '<tr class="phperror"><td colspan="2">', $message, '</td></tr>';

	}

	function _filepath($file) {
		static $case_nr, $files;
		if ( ($c = $this->getTestCaseProgress()) > $case_nr) {
			$files = array();
			$case_nr = $c;
		}
		if (isset($files[$file])) {
			return $files[$file];
		}

    	$files[$file][0] = realpath($file);
    	$files[$file][1] = substr($files[$file][0], strlen(JUNITTEST_ROOT)+1);
		return $files[$file];
	}

	function _htmlEntities($message) {
		return htmlentities($message, ENT_COMPAT, $this->_character_set);
	}

}
