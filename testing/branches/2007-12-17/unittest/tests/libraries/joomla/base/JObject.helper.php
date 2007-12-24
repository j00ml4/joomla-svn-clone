<?php
/**
 * Supporting classes for JObject tests
 *
 * @package Joomla
 * @subpackage UnitTest
 * @copyright 2007 Open Source Matters, Inc.
 * @license GPL v2
 * @version $Id: $
 * @author Alan Langford <instance1@gmail.com>
 * @author Rene Serradeil
 */

/**
 * Test UnitTest Observer feature.
 *
 * A new testcase observer can be added using $testcase->tell (&$observer). One
 * can have multiple observers. They must implement the atTestEnd($method,
 * &$test_case) method, so an observer can be as simple as this one -- but
 * hopefully not as dumb. Observers DO NOT PERSIST and are destroyed and
 * recreated between each test method.
 */
class JObjectTestObserver
{
    /* called from Simpletest */
    function atTestEnd($method, &$test_case) {
        $context  = &SimpleTest::getContext();
        $reporter = &$context->getReporter();
        $reporter->paintMessage('did and done ' . $method);
    }

    /* get the instance of JObjectTestObserver */
    function &getInstance() {
        static $instance;
        if ($instance === null) {
            $instance = & new JObjectTestObserver();
        }
        return $instance;
    }
}

/**
 * Class to test getPublicProperties()
 */
class TestJObject extends JObject
{
    var $_privateVar = 'Private';
    var $publicVar = 'Public';
    var $constructVar;

    function __construct($args = array()) {
        if (isset($args['construcVar']))
            $this->construcVar = $args['construcVar'];
        else
            $this->constructVar = 'Constructor';
    }
}

/**
 * Class to test static usage of JObject
 */
class TestJObjectStatics
{
    var $data = array();

    /**
     * Stores an object.
     */
    function set($name, &$data) {
        $this->data[$name] =& $data;
    }

    /**
     * Retrieves an object.
     */
    function &get($name) {
        return $this->data[$name];
    }

    /**
     * JObject::getPublicProperties()
     */
    function getArray($name) {
        return $this->data[$name]->getPublicProperties(true);
    }

}

?>
