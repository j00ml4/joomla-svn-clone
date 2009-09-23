<?php
/**
 * @version		$Id$
 * @package		Joomla.UnitTest
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU General Public License
 */

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

    function setUp()
    {
        // initialize fixture if required
    }

    function tearDown()
    {
        $this->instance = null;
        unset($this->instance);
    }

    static public function queryData()
    {
        $cases = array(
            array('mysql', '#__table1', "SELECT  * FROM jos_table1"),
            array('pgsql', '#__table2', "SELECT  * FROM jos_table2"),
        );
        return $cases;
    }


    /**
     * @dataProvider queryData
     */
    function testQueryBuild($driver, $table, $sql)
    {
        if (method_exists( 'JFactory', 'getQueryBuilder' )) {
	        $qb = JFactory::getQueryBuilder($driver);
	        $qb->select('*')->from($table);
	        $sql_array = $qb->getSQL();
	        $this->assertEquals($sql, trim($sql_array[0]));
        }
        else {
        	$this->markTestSkipped( 'The JODA dependancies are not available' );
        }
    }
}