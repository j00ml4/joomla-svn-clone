<?php
class TestOfJObject extends UnitTestCase
{
    var $class = null;
    
    function TestOfJObject()
    {
        $this->class = new TestJObject;
    }
    
    function testJObject()
    {
        $this->assertTrue( get_class( new JObject ) == 'JObject' );
        $this->assertTrue( get_class( $this->class ) == 'TestJObject' );
    }

    function test__construct()
    {
        $this->assertTrue( true );
    }

    function testSet()
    {
        $this->assertTrue( $this->class->set( 'test', 'data' ) == null );
    }

    function testGet()
    {
        $this->assertTrue( $this->class->get( 'test' ) == 'data' );
        $this->assertTrue( $this->class->get( 'text', 'string' ) == 'string' );
    }

    function testGetPublicProperties()
    {
        $properties = $this->class->getPublicProperties();
        
        $this->assertTrue( $properties[0] == 'publicVar' );
    }

    function testToString()
    {
        $this->assertTrue( $this->class->toString() == 'TextOfJObject' );
    }

}

/**
 * Class to test getPublicProperties()
 */
class TestJObject extends JObject
{
    var $_privateVar = 'Private';
    var $publicVar = 'Public';
}
?>