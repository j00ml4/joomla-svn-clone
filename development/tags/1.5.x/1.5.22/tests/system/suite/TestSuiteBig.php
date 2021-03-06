<?php
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'TestSuiteBig::main');
}
set_include_path(get_include_path() . PATH_SEPARATOR . './PEAR/');
 
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'bigtest.php';
 
class TestSuiteBig
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }
 
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit Framework');
        $suite->addTestSuite('BigTest');
        return $suite;
    }
}
 
if (PHPUnit_MAIN_METHOD == 'Framework_AllTests::main') {
	print "running Framework_AllTests::main()";
    Framework_AllTests::main();
}
// the following section allows you to run this either from phpunit as
// phpunit.bat --bootstrap servers\configdef.php tests\testsuite.php
// or to run as a PHP Script from inside Eclipse. If you are running
// as a PHP Script, the SeleniumConfig class doesn't exist so you must import it
// and you must also run the TestSuite::main() method.
if (!class_exists('SeleniumConfig')) {
	require_once '../servers/configdef.php';
	TestSuiteBig::main();
}


