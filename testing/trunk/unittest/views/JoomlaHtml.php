<?php
/**
 * Joomla! v1.5 UnitTest Platform
 *
 * @version	$Id$
 * @package 	Joomla
 * @subpackage 	UnitTest
 */

class JoomlaHtml extends SimpleReporter
{
var $status = array('pass','fail','miss','skip','exception');
var $_character_set;
var $test_name;
var $method_name;
var $_methods = array();
var $_missing_tests = array();
var $_skipped = array();
var $_env = '';

	function JoomlaHtml($character_set = 'UTF-8') {
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

    /**
     * Paints the top of the web page setting the
     * title to the name of the starting test.
     * @param string $test_name      Name class of test.
     */
    function paintHeader( $test_name )
    {
    	$this->test_name = $test_name;
		$home_url = JUNITTEST_HOME_URL;
    	$title = <<<HTML
	<h1 class="header" onclick="location.href='{$home_url}';">Testcase: {$test_name} </h1>
HTML;

		if ( headers_sent() ) {
			echo $title;
			return;
		}

		echo <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset={$this->_character_set}" />
	<title>Testcase: {$test_name} </title>
	<link rel="stylesheet" type="text/css" href="views/style.css" />
</head>
<body>
{$title}

HTML;

	}

	/**
	 * Paints the end of the test with a summary of
	 * the passes and failures.
	 * @param string $test_name        Name class of test.
	 */
	function paintFooter( $test_name )
	{
		$state = ($this->getFailCount() + $this->getExceptionCount() > 0 ? "fail" : "pass");

		echo '<div class="footer_', $state, '">'
			, $this->getTestCaseProgress(), '/', $this->getTestCaseCount()
			, ' test cases complete:', PHP_EOL
			, '<strong>' , $this->getPassCount() , '</strong> passes, ', PHP_EOL
			, '<strong>' , $this->getFailCount() , '</strong> fails and ', PHP_EOL
			, '<strong>' , $this->getExceptionCount() , '</strong> exceptions.', PHP_EOL
			, '</div>', PHP_EOL
			, '<div class="footer_footer"><!-- statistics anyone? --></div>', PHP_EOL
			, '</body>', PHP_EOL, '</html>';
    }

    function paintPass( $message )
    {
    	// increment global counter
		parent::paintPass($message);

		if (JUNITTEST_REPORTER_RENDER_PASSED == false) {
			return;
		}

        $breadcrumb = $this->getTestList();
        array_shift( $breadcrumb );

        $this->_paintHelper('pass', $breadcrumb, $message);
    }

    /**
     * Paints the test failure with a breadcrumbs trail of the nesting
     * test suites below the top level test.
     *
     * @param string $message    Failure message displayed in
     *                              the context of the other tests.
     */
    function paintFail( $message )
    {
    	// increment global counter
        parent::paintFail( $message );

        $breadcrumb = $this->getTestList();
        array_shift( $breadcrumb );

        $this->_paintHelper('fail', $breadcrumb, $message);
    }

    function paintSkip( $message )
    {
    	// increment global counter
        parent::paintSkip( $message );

        $breadcrumb = $this->getTestList();
        array_shift( $breadcrumb );

        $this->_paintHelper('skip', $breadcrumb, $message);
    }

    /**
     * Paints a PHP error or exception.
     *
     * @param string $message Message is ignored.
     */
    function paintException( $message )
    {
        parent::paintException( $message );

        $breadcrumb = $this->getTestList();
        array_shift( $breadcrumb );

        $this->_paintHelper('exception', $breadcrumb, $message);
    }

    /**
     * Paints a simple supplementary message.
     * @param string $message        Text to display.
     * @access public
     */
    function paintMessage( $message )
    {
        parent::paintMessage( $message );

        $breadcrumb = $this->getTestList();
        array_shift( $breadcrumb );

		$this->_paintHelper('message', $breadcrumb, $message);
    }

    /**
     *  Paints formatted text such as dumped variables.
     * @param string $message        Text to show.
     */
    function paintFormattedMessage( $message )
    {
        echo '<pre class="message">', $this->_htmlEntities( $message ), '</pre>';
    }

	function _paintHelper($type, $breadcrumb, $message)
	{
        echo '<div class="', $type, '">'
        	, implode(' -&gt; ', $breadcrumb )
        	, ' -&gt; ', $this->_htmlEntities( $message )
        	, '</div>', PHP_EOL;
	}

    /**
     * Character set adjusted entity conversion.
     * @param string $message    Plain text or Unicode message.
     * @return string            Browser readable message.
     * @access protected
     */
    function _htmlEntities( $message )
    {
        return htmlentities( $message, ENT_COMPAT, 'UTF-8' );
    }

	/**
	 * Called at the start of each test method.
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

		return ! $this->_is_dry_run;
	}

	/**
	 * stubs for the alternative HTML reporter (CirTap)
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

}
