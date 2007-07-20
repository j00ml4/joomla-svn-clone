<?php

// $Id$

// Call TestOfJObject::main() if this source file is executed directly.
if (!defined('JUNITTEST_MAIN_METHOD')) {
    define('JUNITTEST_MAIN_METHOD', 'TestOfJObject::main');
    $JUNITTEST_ROOT = substr(__FILE__, 0, strpos(__FILE__, DIRECTORY_SEPARATOR.'unittest'));
	require_once($JUNITTEST_ROOT.'/unittest/prepend.php');
}

require_once('libraries/joomla/base/object.php');

class TestOfJObject extends UnitTestCase
{
    var $class = null;

	/**
	 * Runs the test methods of this class.
	 *
	 * @access public
	 * @static
	 */
	function main() {
		$self = new TestOfJObject;
		$self->run( UnitTestHelper::getReporter() );
	}

	function setUp()
	{
        $this->class = new TestJObject;
	}

	function tearDown()
	{
		$this->class = null;
	}

    function testJObject()
    {
        $this->assertIsA( new JObject, 'JObject');
        $this->assertIsA( $this->class, 'TestJObject');
    }

    function test__construct()
    {
        $obj = new TestJObject( array('construcVar'=>'tested') );
        // $publicVar, $construcVar
        $this->assertEqual( $obj->construcVar, 'tested' );
        $this->assertEqual( $obj->get('construcVar'), 'tested' );
    }

    function testSet()
    {
    	$old = $this->class->set( 'test', 'data' );
        $this->assertNull( $old );
    }

    function testGet()
    {
    	$this->class->set( 'test', 'data' );

    	$compare = $this->class->get( 'test' );
        $this->assertEqual( $compare, 'data' );

    	$compare = $this->class->get( 'text', 'string' );
        $this->assertEqual( $compare, 'string' );
    }

    function testGetPublicProperties()
    {
        $properties = $this->class->getPublicProperties();

        $this->assertEqual( $properties[0], 'publicVar' );
    }

    function testGetPublicProperties_assoc()
    {
        $properties = $this->class->getPublicProperties(true);

        $this->assertTrue( isset($properties['publicVar']) );
        $this->assertEqual( $properties['publicVar'], 'Public' );
        $this->assertTrue( !isset($properties['_privateVar']) );
    }

    function testToString()
    {
    	$string = $this->class->toString();
    	if ((int)PHP_VERSION >= 5) {
	        $this->assertEqual( $string, 'TestJObject' );
    	} else {
	        $this->assertEqual( $string, strtolower('TestJObject') );
    	}
    }

}

/**
 * Class to test getPublicProperties()
 */
class TestJObject extends JObject
{
    var $_privateVar = 'Private';
    var $publicVar = 'Public';
    var $construcVar;

    function __construct($args = array()) {
    	if ( isset($args['construcVar']))
    		$this->construcVar = $args['construcVar'];
    	else
    		$this->construcVar = 'Constructor';
    }
}

/**
 * Class to test static useage of JObjects
 */
class TestJObjectStatics
{
var $data = array();

	/** stores an object */
	function set($name, &$data) {
		$this->data[$name] =& $data;
	}

	/** retrieves an object */
	function &get($name) {
		return $this->data[$name];
	}

	/** JObject::getPublicProperties() */
	function getArray($name) {
		return $this->data[$name]->getPublicProperties(true);
	}

	/**
	 * retrieves object's properties uncached
	 * stripped down version of JObject::getPublicProperties()
	 * @deprecated fixed in JObject
	 */
	function getPublicProperties_reprise($name, $assoc = false) {
		// make sure items 0 and 1 EXIST if object has NO properties
		$cache = array(array(),array());
		foreach ( get_object_vars($this->data[$name]) as $key => $val)
		{
			if (substr( $key, 0, 1 ) != '_')
			{
				$cache[0][] = $key;
				$cache[1][$key] = $val;
			}
		}
		return $cache[$assoc ? 1 : 0];
	}
}

// Call TestOfJObject::main() if this source file is executed directly.
if (JUNITTEST_MAIN_METHOD == "TestOfJObject::main") {
	TestOfJObject::main();
}
