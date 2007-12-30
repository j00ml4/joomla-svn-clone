<?php

// $Id$

// Call TestOfJVersion::main() if this source file is executed directly.
if (!defined('JUNIT_MAIN_METHOD')) {
    define('JUNIT_MAIN_METHOD', 'TestOfJVersion::main');
    $JUNIT_ROOT = substr(__FILE__, 0, strpos(__FILE__, DIRECTORY_SEPARATOR.'unittest'));
    require_once($JUNIT_ROOT.'/unittest/setup.php');
}

require_once('libraries/joomla/version.php');

class TestOfJVersion extends UnitTestCase
{
    var $class = null;

    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    function main() {
        $self = new TestOfJVersion;
        $self->run(JUnit_Setup::getReporter());
    }

    function setUp()
    {
        $this->class = new JVersion();
    }

    function tearDown()
    {
        $this->class = null;
        unset($this->class);
    }

    function testJVERSION()
    {
        $this->assertEqual(JVERSION, $this->class->RELEASE.'.'.$this->class->DEV_LEVEL);
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

        $this->assertEqual($this->class->getLongVersion(), $version);
    }

    function testGetShortVersion()
    {
        $this->assertEqual($this->class->getShortVersion(), $this->class->RELEASE.'.'.$this->class->DEV_LEVEL);
    }

    function testGetHelpVersion()
    {
        $this->assertEqual($this->class->getHelpVersion(), '.'.str_replace('.', '', $this->class->RELEASE));
    }

    function testIsCompatible()
    {
        $this->assertTrue($this->class->isCompatible ($this->class->RELEASE.'.'.$this->class->DEV_LEVEL));
    }

    /*
     * how do you define compatibility?
     * will 1.5.1 be incompatible with 1.5.0 ?
     */
    function testIsCompatible_minor()
    {
        $minor = $this->class->RELEASE.'.'. ($this->class->DEV_LEVEL + 1);
        $this->assertTrue($this->class->isCompatible ($minor),
                    "$minor not compatible to ". JVERSION ."\n %s");
    }

}

// Call TestOfJVersion::main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == "TestOfJVersion::main") {
    TestOfJVersion::main();
}
