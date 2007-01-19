<?php
class TestJVersion extends UnitTestCase
{
    var $class = null;
    
    function TestJVersion()
    {
        $this->class = new JVersion();
    }
    
    function testGetLongVersion()
    {
        $version = $this->class->PRODUCT.' '.
            $this->class->RELEASE.'.'.
            $this->class->DEV_LEVEL.' '.
            $this->class->DEV_STATUS.
            ' [ '.$this->class->CODENAME.' ] '.
            $this->class->RELDATE.' '.
            $this->class->RELTIME.' '.
            $this->class->RELTZ;
        
        $this->assertTrue( $this->class->getLongVersion() == $version );
    }

    function testGetShortVersion()
    {
        $this->assertTrue( $this->class->getShortVersion() == $this->class->RELEASE.'.'.$this->class->DEV_LEVEL );
    }

    function testGetHelpVersion()
    {
        $this->assertTrue( $this->class->getHelpVersion() == '.'.str_replace( '.', '', $this->class->RELEASE ) );
    }

    function testIsCompatible()
    {
        $this->assertTrue( $this->class->isCompatible ( $this->class->RELEASE.'.'.$this->class->DEV_LEVEL ) );
    }

}
?>