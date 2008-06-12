<?php
/**
 * Joomla! Unit Test Facility
 *
 * JQueryBuilder test
 *
 * @package Joomla
 * @subpackage UnitTest
 * @copyright Copyright (C) 2005 - 2008 Open Source Matters, Inc.
 * @version $Id: $
 *
 */

if (!defined('JUNIT_MAIN_METHOD')) {
    define('JUNIT_MAIN_METHOD', 'JQueryBuilderTest::main');
    $JUnit_home = DIRECTORY_SEPARATOR . 'unittest' . DIRECTORY_SEPARATOR;
    if (($JUnit_posn = strpos(__FILE__, $JUnit_home)) === false) {
        die('Unable to find ' . $JUnit_home . ' in path.');
    }
    $JUnit_posn += strlen($JUnit_home) - 1;
    $JUnit_root = substr(__FILE__, 0, $JUnit_posn);
    $JUnit_start = substr(
        __FILE__,
        $JUnit_posn + 1,
        strlen(__FILE__) - strlen(basename(__FILE__)) - $JUnit_posn - 2
    );
    require_once $JUnit_root . DIRECTORY_SEPARATOR . 'setup.php';
}

/*
 * Now load the Joomla environment
 */
if (! defined('_JEXEC')) {
    define('_JEXEC', 1);
}
require_once JPATH_BASE . '/includes/defines.php';
/*
 * Mock classes
 */
// Include mocks here
/*
 * We now return to our regularly scheduled environment.
 */
require_once JPATH_LIBRARIES . '/joomla/import.php';

// Include subject class here


/**
 * A unit test class for SubjectClass
 */
class JQueryBuilderTest extends PHPUnit_Framework_TestCase
{
    var $fixture = null;


    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    function main() {
        $suite  = new PHPUnit_Framework_TestSuite(__CLASS__);
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    function setUp()
    {
        // initialize fixture if required
    }

    function tearDown()
    {
        $this->instance = null;
        unset($this->instance);
    }

    static public function queryData() {
        $cases = array(
            array('mysql', 'table1', "SELECT  * FROM table1"),
            array('pgsql', 'table2', "SELECT  * FROM table2"),
        );
        return $cases;
    }


    /**
     * @dataProvider queryData
     */
    function testQueryBuild($driver, $table, $sql)
    {
        $qb = JFactory::getQueryBuilder($driver);
        $qb->select('*')->from($table);
        $sql_array = $qb->getSQL();
        $this->assertEquals($sql, trim($sql_array[0]));
    }



}

// Call main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'JQueryBuilderTest::main') {
    JQueryBuilderTest::main();
}

?>