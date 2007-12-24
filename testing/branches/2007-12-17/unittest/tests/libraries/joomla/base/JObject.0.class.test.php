<?php



// Call JObjectTest::main() if this source file is executed directly.
if (!defined('JUNIT_MAIN_METHOD')) {
    define('JUNIT_MAIN_METHOD', 'JObjectTest::main');
    $JUNIT_ROOT = substr(__FILE__, 0, strpos(__FILE__, DIRECTORY_SEPARATOR.'unittest'));
    require_once($JUNIT_ROOT.'/unittest/setup.php');
}

require_once('libraries/joomla/base/object.php');

class JObjectTest extends JUnit_Framework_TestCase
{
    var $instance = null;

    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    function main() {
        $self = new JObjectTest;
        $self->run(UnitTestHelper::getReporter());
    }
/*
    function before($method)
    {
        parent::before($method);
        $observer = JObjectTestObserver::getAlan();
        $this->tell($observer);
    }
*/
    function setUp()
    {
        $this->instance = new TestJObject;
    }

    function tearDown()
    {
        $this->instance = null;
    }

    function testJObject()
    {
        $this->assertIsA(new JObject, 'JObject');
        $this->assertIsA($this->instance, 'TestJObject');
    }

    function test__construct()
    {
        $obj = new TestJObject(array('construcVar'=>'tested'));
        // $publicVar, $construcVar
        $this->assertEqual($obj->construcVar, 'tested');
        $this->assertEqual($obj->get('construcVar'), 'tested');
    }

    function testSet()
    {
        $old = $this->instance->set('test', 'data');
        $this->assertNull($old);
    }

    function testGet()
    {
        $this->instance->set('test', 'data');

        $compare = $this->instance->get('test');
        $this->assertEqual($compare, 'data');

        $compare = $this->instance->get('text', 'string');
        $this->assertEqual($compare, 'string');
    }

    function testGetProperties()
    {
        $properties = $this->instance->getProperties(false);
        $expect = array(
            '_privateVar' => 'Private',
            'publicVar' => 'Public',
            'constructVar' => 'Constructor',
            '_errors' => array(),
        );
        $this->assertEqual($properties, $expect);
    }

    function testGetPublicProperties()
    {
        $properties = $this->instance->getPublicProperties();
        $expect = array(
            'publicVar' => 'Public',
            'constructVar' => 'Constructor',
        );
        $this->assertEqual($properties, $expect);
    }

    function testToString()
    {
        $string = $this->instance->toString();
        if ((int)PHP_VERSION >= 5) {
            $this->assertEqual($string, 'TestJObject');
        } else {
            $this->assertEqual($string, strtolower('TestJObject'));
        }
    }

}

// Call JObjectTest::main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == "JObjectTest::main") {
    JObjectTest::main();
}
