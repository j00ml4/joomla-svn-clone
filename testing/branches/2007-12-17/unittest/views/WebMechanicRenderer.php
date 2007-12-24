<?php
/**
 * WebMechanicRenderer für UnitTests
 *
 * @package Joomla
 * @subpackage UnitTest
 * @author  Rene Serradeil <serradeil@webmechanic.biz>
 * @copyright Copyright (c)2006-2007, media++|webmechanic.biz
 * @version $Id: $
 * @filesource
 */

require_once(TEST_LIBRARY.'reporter.php');

/**
 * Ein gegenüber HtmlReporter() übersichtlicherer Renderer.
 * Auf die Instanz kann in den TestCases via <var>$this->_reporter</var>
 * zugegriffen werden.
 *
 * @package Joomla
 * @subpackage UnitTest
 */
class WebMechanicRenderer extends SimpleReporter
{
var $_character_set;
var $test_name;
var $method_name;

/**
 * stats.
 * keys: start (bool), end (bool), pass (int), fail (int), miss (bool), skip (bool)
 * @var arrays
 */
var $_methods = array();

/**
 * @var array
 * @see setMissingTestCase()
 */
var $_missing_tests = array();

/** @var string method_name causing the testcase to skip */
var $_skipped = null;

/** @var string PHP environment */
var $_env = '';

	function WebMechanicRenderer($character_set = 'UTF-8')
	{
		$this->__construct($character_set);
	}

	function __construct($character_set = 'UTF-8')
	{
		$this->SimpleReporter();
		$this->_character_set = $character_set;
		$this->_env = '<b class="texception">PHP '.PHP_VERSION .'</b> as '. PHP_SAPI .' on '. PHP_OS;
		$this->sendNoCacheHeaders();
	}

	function sendNoCacheHeaders()
	{
		if (! headers_sent()) {
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header("Content-Type: text/html; charset=".$this->_character_set);
			header("Content-Encoding: ".$this->_character_set);
		}
		ob_start();

//    jutdump(get_class_methods($this));

	}

	/**
	 * Called at the start of each test method.
	 * Queries UnitTestHelper for the test configuration of the testclass.
	 *
	 * @todo implement, see SimpleReporterDecorator
	 */
	function shouldInvoke($class_name, $method_name)
	{
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

		if (!UnitTestHelper::shouldInvoke($this, $class_name)) {
			// store for possible serialization
			$this->_missing_tests[] = $this->method_name;
			$this->_methods[$this->method_name]['skip']   = 1;
			$this->_methods[$this->method_name]['reason'] = $class_name
							.' disabled in TestConfiguration';

			// skip whole test: SimpleTestContext
			$context  = &SimpleTest::getContext();
			$testcase = &$context->getTest();
			$testcase->skipIf(
					$this->_methods[$this->method_name]['skip'],
					$this->_methods[$this->method_name]['reason']
					);
		}
		return !$this->_methods[$method_name]['skip'];
	}

	function paintHeader($test_name)
	{
		$this->test_name = $test_name;

		$verbose = (JUNIT_REPORTER_RENDER_PASSED)
				 ? '<small title="Also displays messages of passed tests (JUNIT_REPORTER_RENDER_PASSED = true)">(verbose)</small>'
				 : '<small title="Only displays messages of failed tests (JUNIT_REPORTER_RENDER_PASSED = false)">(compact)</small>';
		$home_url = JUNIT_HOME_URL;
		$title = <<<HTML
	<h1 onclick="location.href='{$home_url}'">
	<span>{$test_name}</span> <span style="cursor:help">{$verbose}</span>
	</h1>
HTML;
		list($php, $url) = UnitTestHelper::toggleHostUrl();
		if (!empty($url)) {
			if (strpos(JUNIT_HOME_PHP4, $_SERVER['HTTP_HOST']) === false) {
				$php = '<b class="tpass" title="toggle environment">PHP4</b>';
			}
			if (strpos(JUNIT_HOME_PHP5, $_SERVER['HTTP_HOST']) === false) {
				$php = '<b class="tpass" title="toggle environment">PHP5</b>';
			}
			$this->_env .= " &bull; Switch to: <span><a href=\"{$url}\">{$php}</a></span>";
		}

		if (headers_sent()) {
			echo '<div class="Header">', $title, '</div>',PHP_EOL, $this->_env;
			return;
		}

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
<div id="Header">
	{$title}
	<p><small>{$this->_env}</small></p>
</div>
<div id="Content" class="MainContent">

HTML;
//         echo $this->testgroup->generateMenu();

	}

	/**
	 * Last exit: once this method is done, the $reporter object is
	 * destroyed by UnitTestCase::run()
	 */
	function paintFooter($test_name)
	{
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

		$skipped = empty($this->_skipped)
				? ''
				: '<span class="skip">Skipped by: <b>'. $this->_skipped . '</b></span>';

		echo <<<HTML
	<div class="tests-total">
	{$p} / {$c} Test Cases run.
	Tests: {$tc}
	{$skipped}
	</div>
</div>
<div id="Footer">
	<p>{$this->_env}</p>
</div>
HTML;
		echo '</body></html>';

		while (@ob_flush());
	}

	/**
	 * Paints the start of a test case (file).
	 */
	function paintCaseStart($case_name) {
		parent::paintCaseStart($case_name);
		$this->_opentable($case_name);
	}

	/**
	 * Paints the end of a test case (file).
	 */
	function paintCaseEnd($case_name)
	{
		parent::paintCaseEnd($case_name);

		$c = $this->getTestCaseCount();
		$p = $this->getTestCaseProgress();

		$mpass = array();
		$mfail = array();
		$mskip = array();

		/* create the list of testcases and the optional tooltip for skipped tests */
		foreach ($this->_methods as $method_name => $stats) {
			if (in_array($method_name, $this->_missing_tests) || $stats['skip']) {
				$reason = ($this->_methods[$method_name]['reason'])
						? ' style="cursor:help" title="'.$this->_methods[$method_name]['reason'].'"'
						: '' ;
				$method_name = '<span class="miss"'.$reason.'>'.$method_name.'</span>';
			}
			($stats['fail'] > 0)
					? array_push($mfail, $method_name)
					: array_push($mpass, $method_name);
			if ($stats['skip']) {
				array_push($mskip, $method_name);
			}
		}
		$passed  = implode(', ', $mpass);
		$failed  = implode(', ', $mfail);
		$skipped = implode(', ', $mskip);

		echo PHP_EOL, '<tbody class="methods-summary"><tr><td colspan="2">';
		if (count($mpass)) {
			echo PHP_EOL,'    <span class="pass">&nbsp;</span> <strong>passed: </strong> <tt>'
				, $passed ,' &nbsp;</tt>';
		}
		if (count($mfail)) {
			if (count($mpass)) echo '<br />';
			echo PHP_EOL, '<span class="fail">&nbsp;</span> <strong>failed: </strong> <tt>'
				, $failed ,'&nbsp;</tt>';
		}
		if (count($mskip)) {
			if (count($mpass)) echo '<br />';
			echo PHP_EOL, '<span class="skip">&nbsp;</span> <strong>skipped: </strong> <tt>'
				, $skipped ,'&nbsp;</tt>';
		}

		echo PHP_EOL, ' </td></tr></tbody>
	</table>';

		$this->paintCaseSummary($case_name);
	}

	function _opentable($case_name) {
		$cp = $this->getTestCaseProgress() + 1;
		$cc = $this->getTestCaseCount();

		echo <<<HTML
	<table class="testcase">
	<thead>
	<tr><th colspan="2">({$cp}/$cc) {$case_name}</th></tr>
	</thead>
HTML;

	}

	function paintCaseSummary($case_name)
	{
		static $pc = 0;
		static $fc = 0;
		static $ec = 0;

		$cp  = $this->getTestCaseProgress();
		$cc  = $this->getTestCaseCount();

		$pc  = $this->getPassCount() - $pc;
		$fc  = $this->getFailCount() - $fc;
		$ec  = $this->getExceptionCount() - $ec;
		$sc  = !empty($this->_skipped);
		$mc  = count($this->_missing_tests);

		$tmc = count($this->_methods);
		$css = ($fc + $ec > 0) ? 'fail' : 'pass';

		$pcss  = ($pc > 0) ? ' class="pass"' : '';
		$fcss  = ($fc > 0) ? ' class="fail"' : '';
		$ecss  = ($ec > 0) ? ' class="exception"' : '';
		$scss  = ($sc)     ? ' class="fail skip"' : '';

		$missing = ($mc > 0)
				 ? '<span class="miss"><b>'. $mc .'</b> OPEN</span>'
				 : '';

		$skipped = ($sc)
				 ? '<span class="skip">SKIPPED IN: <b>'. $this->_skipped .'</b></span>'
				 : '';

		// leerer UnitTestCase Klasse (keine test_xx Methoden) ?
		if (($pc+$fc+$ec) == 0) {
			$css = 'fail';
		} else {
			if ($sc) $css = 'exception';
		}

		echo <<<HTML
	<div class="tests-total {$css}"><b>{$case_name}</b>
	run: {$cp}/{$cc}
	using <b>{$tmc}</b> cases
	&mdash;
	# assertions:
	<span{$pcss}><b>{$pc}</b> passed</span>
	{$missing}
	<span{$fcss}><b>{$fc}</b> failed</span>
	and
	<span{$ecss}><b>{$ec}</b> exceptions</span>
	{$skipped}
	</div>

HTML;

		$this->_methods = array();
		$this->_missing_tests = array();

	}

	/* backs out of the test started with the same name. */
	function paintMethodEnd($method_name)
	{
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
	function setMissingTestCase($reason='Implement')
	{
		$this->_missing_tests[] = $this->method_name;
		$this->_methods[$this->method_name]['miss'] = true;
		if ($reason) {
			$this->_methods[$this->method_name]['reason'] = $this->_htmlEntities($reason).PHP_EOL;
		}
		return count($this->_missing_tests);
	}

	function getMissingTestCases()
	{
		if (($mc = count($this->_missing_tests)) == 0) return '';

		return '
	<p><span class="miss"><b>'. $mc .' OPEN:</b></span>
	<samp style="cursor:help">' . implode('(), ', $this->_missing_tests) . '()</samp>
	</p>';
	}

	function paintSkip($message)
	{
		// increment counter
		$this->_methods[$this->method_name]['skip'] = true;
		$short = explode('at [', $message);
		$this->_methods[$this->method_name]['reason'] = trim($short[0]);
		$this->_skipped = $this->method_name;

		echo <<<HTML

	<tr><th>{$this->method_name}</th>
		<td><span class="skip">&nbsp;</span> {$message}</td></tr>
HTML;

	}

	function paintPass($message)
	{
		// increment global counter
		parent::paintPass($message);
		$this->_methods[$this->method_name]['pass']++;

		if (JUNIT_REPORTER_RENDER_PASSED == false) {
			return;
		}
		list($expected, $file) = explode('at [', $message);
		$line = trim(array_pop(explode(' line ', $file)), '[]');

		echo <<<HTML

	<tr><th>{$this->method_name}</th>
		<td><span class="pass">&nbsp;</span> {$expected} at [line {$line}]</td></tr>
HTML;

	}

	function paintMessage($message)
	{
		$message = $this->_htmlEntities($message, ENT_COMPAT, $this->_character_set);

		echo <<<HTML
	<tr class="message">
		<td colspan="2"><span class="pass">&nbsp;</span> <b>{$this->method_name}</b>: {$message}</td>
	</tr>

HTML;
	}

	function paintFormattedMessage($message)
	{
		$this->dumpCode($message, 'Code');
	}

	function paintFail($message)
	{
		// increment global counter
		parent::paintFail($message);
		$this->_methods[$this->method_name]['fail']++;

		// title, file, test_class, test_method
		$breadcrumb = $this->getTestList();
		@list ($title, $file, $test_class) = $breadcrumb;
		// original error message with some filename
		//.. at [filepath line nnn]
		if (strpos($message, $file) !== false) {
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

	/**
	 * called from: $testcase->error($severity, $message, $file, $line);
	 * "Unexpected PHP error [$message] severity [$severity] in [$file line $line]"
	 */
	function paintError($message)
	{
		static $base;
		if (!isset($base)) $base = realpath(dirname(__FILE__));

		// increment global counter
		$this->_exceptions++;

		$message = $this->_htmlEntities($message);

		echo <<<HTML
	<tr class="phperror">
		<th><span class="fail">Error</span></th>
		<td><strong>{$message}</strong></td>
	</tr>

HTML;
	}

	/**
	 * PHP 5+ only
	 * called from: $testcase->exception($exception)
	 * @param Exception $exception
	 */
	function paintException($exception)
	{
		static $base;
		if (!isset($base)) $base = realpath(dirname(__FILE__));

		// increment global counter
		$this->_exceptions++;

		$breadcrumb = $this->getTestList();
		array_shift($breadcrumb);
		$ec = get_class($exception);
		$em = $exception->getMessage();
		$ef = $exception->getFile();
		$ef = substr($ef, strlen(JUNIT_ROOT)+1);
		$el = $exception->getLine();
		$message = $this->_htmlEntities($message);

		echo <<<HTML

	<tr class="phperror">
		<th><span class="fail">Exception</span></th>
		<td>Unexpected exception of type [{$ec}] with message [{$em}] in [{$ef} line {$el}]
		<br /><strong>{$message}</strong>
		</td>
	</tr>
HTML;

	}

	/**
	 * By default just ignores user generated events.
	 *
	 * @param string $type    Event type as text.
	 * @param mixed  $payload Message or object.
	 */
//    function paintSignal($type, $payload) {
//    }

	/* Paints a formatted ASCII message such as a variable dump. */
	function dumpCode($data, $label='')
	{
	static $title = '<div title="Click the [+] to toggle debug output" class="DbgPrint" onclick="this.style.zIndex+=100" style="position:relative;text-align:left;line-height:normal;width:98%;background-color:#ddd;color:black;border:1px solid gray;margin:2px;padding:0;">{%click%}&nbsp;<samp class="DbgPrint" style="font-size:85%;color:inherit;cursor:help;" onclick="try{this.parentNode.firstChild.onclick()}catch(e){}">{%rem%}</samp> ';
	static $click = '<span title="Click here to toggle debug output" style="padding:0;color:#c00;cursor:help;" onclick="var cn=this.parentNode.childNodes;var s=cn[cn.length-1].style;s.display=(s.display==\'none\')?\'\':\'none\';">[+]</span>';
	static $lines = 14;

		echo str_replace(array('{%click%}','{%rem%}'), array($click,gettype($data).' '.$label), $title);
		echo "\n<textarea class='DbgPrint' title='' style='display:none;position:relative;font-family:monospace;font-size:95%;line-height:normal;width:100%;height:{$lines}em;background:white;color:black;border:1px solid gray;margin:0px;padding:0px 4px;'>";

		if (is_scalar($data)) {
			echo htmlentities($data, ENT_COMPAT, $this->_character_set);
		} else {
			print_r($data);
		}
		echo PHP_EOL. '</textarea>',
			'</div>', PHP_EOL;

	}

	function _filepath($file)
	{
		static $case_nr, $files;
		if (($c = $this->getTestCaseProgress()) > $case_nr) {
			$files = array();
			$case_nr = $c;
		}
		if (isset($files[$file])) {
			return $files[$file];
		}

		$files[$file][0] = realpath($file);
		$files[$file][1] = substr($files[$file][0], strlen(JUNIT_ROOT)+1);
		return $files[$file];
	}

	function _htmlEntities($message)
	{
		$message = htmlentities($message, ENT_COMPAT, $this->_character_set);
		$message = $this->nl($message);
		return $message;
	}

	/**
	 * PHP_EOL to line-break
	 */
	function nl($message)
	{
		return str_replace(PHP_EOL, '<br />', $message);
	}

}

class WebMechanicObserver
{

	function atTestEnd($method, &$test_case) {
jutdump($method);
	}
}

