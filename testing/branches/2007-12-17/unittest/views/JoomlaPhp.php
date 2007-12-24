<?php
/**
 * Joomla! v1.5 UnitTest Platform
 *
 * @version $Id$
 * @package Joomla
 * @subpackage UnitTest
 */

class JoomlaPhp extends SimpleReporter
{
var $_joomlaTests = array();
var $status = array('pass','fail','miss','skip','exception');
var $_character_set;
var $test_name;
var $method_name;

    function JoomlaPhp($character_set = 'UTF-8') {
        $this->__construct($character_set);
    }

    function __construct($character_set = 'UTF-8') {
        $this->SimpleReporter();
        $this->_character_set = $character_set;
        ini_set('default_charset', $character_set);
        $this->sendNoCacheHeaders();

        ob_start();
    }

    function sendNoCacheHeaders() {
        if (! headers_sent()) {
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: no-store, no-cache, must-revalidate', true);
            header('Cache-Control: post-check=0, pre-check=0', false);
            header('Pragma: no-cache');
        }
    }

    function paintHeader($test_name)
    {
        $this->test_name = $test_name;

        $this->_joomlaTests['unittests']['pass'] = array();
        $this->_joomlaTests['unittests']['fail'] = array();
        $this->_joomlaTests['unittests']['skip'] = array();
        $this->_joomlaTests['unittests']['miss'] = array();
        $this->_joomlaTests['unittests']['exception'] = array();
        $this->_joomlaTests['unittests']['result'] = array();

        $this->_joomlaTests['unittests']['testcase'] = $test_name;
    }

    function paintFooter($test_name)
    {
        $this->_joomlaTests['unittests']['result']['total'] = $this->getTestCaseCount();
        $this->_joomlaTests['unittests']['result']['completed'] = $this->getTestCaseProgress();
        $this->_joomlaTests['unittests']['result']['pass'] = $this->getPassCount();
        $this->_joomlaTests['unittests']['result']['fail'] = $this->getFailCount();
        # {{ TODO
        $this->_joomlaTests['unittests']['result']['skip'] = -1;
        $this->_joomlaTests['unittests']['result']['miss'] = -1;
        # }}
        $this->_joomlaTests['unittests']['result']['exception'] = $this->getExceptionCount();

        $this->joomlaOutput();
        while (@ob_end_flush());
    }

    function joomlaOutput()
    {
        echo serialize($this->_joomlaTests);
    }

    function paintPass($message)
    {
        parent::paintPass($message);
        $this->_methods[$this->method_name]['pass']++;
        $this->_store('pass', $message);
    }

    function paintFail($message)
    {
        parent::paintFail($message);
        $this->_methods[$this->method_name]['fail']++;
        $this->_store('fail', $message);
    }

    function paintSkip($message)
    {    # flagged as failure
        parent::paintFail($message);
        $this->_methods[$this->method_name]['skip']++;
        $this->_store('skip', $message);
    }

    function paintException($message)
    {
        parent::paintException($message);
        $this->_store('exception', $message);
    }

    function _store($state, $message)
    {
        $breadcrumb = $this->getTestList();
        array_shift($breadcrumb);

        $this->_joomlaTests['unittests'][$state][$breadcrumb[1]][$breadcrumb[2]] =
                        array(
                            'filepath' => $breadcrumb[0],
                            'message' => $message
                            );
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
                'start' => true,
                'pass' => 0,
                'fail' => 0,
                'skip' => 0,
                'miss' => false,
                'reason' => false,
                'end' => false
        );

        return ! $this->_is_dry_run;
    }

    function paintMethodEnd($method_name) {
        $this->method_name = null;
        $this->_methods[$method_name]['end'] = true;
    }

    function setMissingTestCase($method_name, $reason='') {
        $this->_missing_tests[] = $this->method_name;
        $this->_methods[$this->method_name]['miss'] = true;
        // flag as failed
        $this->assertTrue(false, "$method_name needs implementation. ".$reason);
    }

}
