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

    /**
     * Paints the top of the web page setting the
     * title to the name of the starting test.
     * @param string $test_name      Name class of test.
     */
    function paintHeader( $test_name )
    {
        header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
        header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
        header( "Cache-Control: no-store, no-cache, must-revalidate" );
        header( "Cache-Control: post-check=0, pre-check=0", false );
        header( "Pragma: no-cache" );

        flush();

		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">', PHP_EOL
			, '<html><head>', PHP_EOL
			, '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">', PHP_EOL
			, '<title>Testcase:', $test_name, '</title>', PHP_EOL
			, '<link rel="stylesheet" type="text/css" href="views/style.css" />', PHP_EOL
			, '</head><body>', PHP_EOL
			, '<h1 class="header" onclick="location.href=\'/unittest/\';">Testcase: ', $test_name,'</h1>'
			, PHP_EOL;
    }

    /**
     * Paints the end of the test with a summary of
     * the passes and failures.
     * @param string $test_name        Name class of test.
     */
    function paintFooter( $test_name )
    {
        $state = ($this->getFailCount() + $this->getExceptionCount() > 0 ? "fail" : "pass");

		echo '<div class="footer_', $state, '>'
			, $this->getTestCaseProgress(), '/', $this->getTestCaseCount()
			, ' test cases complete:', PHP_EOL
			, '<strong>' , $this->getPassCount() , '</strong> passes, ', PHP_EOL
			, '<strong>' , $this->getFailCount() , '</strong> fails and ', PHP_EOL
			, '<strong>' , $this->getExceptionCount() , '</strong> exceptions.', PHP_EOL
			, '</div>', PHP_EOL
			, '<div class="footer_footer"><a href="../">&lt;= Home</a></div>', PHP_EOL
			, '</body>', PHP_EOL, '</html>';
    }

    function paintPass( $message )
    {
        parent::paintPass( $message );

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
        parent::paintFail( $message );

        $breadcrumb = $this->getTestList();
        array_shift( $breadcrumb );

        $this->_paintHelper('fail', $breadcrumb, $message);
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
	 * stub for the alternative HTML reporter (CirTap)
	 */
	function setMissingTestCase($method_name, $reason='') {
		$this->_methods[$method_name]['skip']++;
		// flag as failed
		$this->assertTrue( false, "$method_name needs implementation. " . $reason );
	}

}
