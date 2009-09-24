<?php
/**
 * @version		$Id$
 * @package		Joomla.FunctionalTest
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'TestSuite::main');
}
set_include_path(get_include_path() . PATH_SEPARATOR . './PEAR/');
 
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'control_panel_menu.php';
//require_once 'com_content0001.php';
//require_once 'com_content0002.php';
//require_once 'com_content0003.php';
//require_once 'com_content0004.php';
//require_once 'com_content0005.php';
//require_once 'com_content0006.php';
//require_once 'com_content0007.php';
//require_once 'com_content0008.php';
//require_once 'com_content0009.php';
//require_once 'com_content0010.php';
//require_once 'com_media0001.php';
//require_once 'com_menu0001.php';
//require_once 'com_menu0002.php';
//require_once 'com_menu0003.php';
//require_once 'tinymce0001.php';
//require_once 'tinymce0002.php';
//require_once 'tinymce0003.php';
//require_once 'tinymce0004.php';
//require_once 'tinymce0005.php';
//require_once 'tinymce0006.php';
 
class TestSuite
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }
 
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit Framework');
        $suite->addTestSuite('ControlPanelMenu');
//        $suite->addTestSuite('ComContent0001');
//        $suite->addTestSuite('ComContent0002');
//        $suite->addTestSuite('ComContent0003');
//        $suite->addTestSuite('ComContent0004');
//        $suite->addTestSuite('ComContent0005');
//        $suite->addTestSuite('ComContent0006');
//        $suite->addTestSuite('ComContent0007');
//        $suite->addTestSuite('ComContent0008');
//        $suite->addTestSuite('ComContent0009');
//        $suite->addTestSuite('ComContent0010');
//        $suite->addTestSuite('ComMedia0001');
//        $suite->addTestSuite('ComMenu0001');
//        $suite->addTestSuite('ComMenu0002');
//        $suite->addTestSuite('ComMenu0003');
//        $suite->addTestSuite('TinyMCE0001');
//        $suite->addTestSuite('TinyMCE0002');
//        $suite->addTestSuite('TinyMCE0003');
//        $suite->addTestSuite('TinyMCE0004');
//        $suite->addTestSuite('TinyMCE0005');
//        $suite->addTestSuite('TinyMCE0006');

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
	TestSuite::main();
}


