<?php
/**
* @version        $Id$
* @package        Joomla-Framework
* @subpackage   Joda
* @copyright    Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license        GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*
 * @author      Plamen Petkov <plamendp@zetcom.bg>
*/

//  Set flag that this is a parent file
define( '_JEXEC', 1 );
define( 'JPATH_BASE', "../../../" );
define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );


//FIXME Create Joda CHANGELOG.php (inside Joda directory OR in Joomla Top dir?)

JDEBUG ? $_PROFILER->mark( 'afterLoad' ) : null;

/**
 * CREATE THE APPLICATION
 *
 * NOTE :
 */
$mainframe =& JFactory::getApplication('site');

/**
 * INITIALISE THE APPLICATION
 *
 * NOTE :
 */
// set the language
$mainframe->initialise();

JPluginHelper::importPlugin('system');

// trigger the onAfterInitialise events
JDEBUG ? $_PROFILER->mark('afterInitialise') : null;
$mainframe->triggerEvent('onAfterInitialise');

/**
 * ROUTE THE APPLICATION
 *
 * NOTE :
 */
$mainframe->route();

// authorization
$Itemid = JRequest::getInt( 'Itemid');
$mainframe->authorize($Itemid);

// trigger the onAfterRoute events
JDEBUG ? $_PROFILER->mark('afterRoute') : null;
$mainframe->triggerEvent('onAfterRoute');

/**
 * DISPATCH THE APPLICATION
 *
 * NOTE :
 */
$option = JRequest::getCmd('option');
$mainframe->dispatch($option);

// trigger the onAfterDispatch events
JDEBUG ? $_PROFILER->mark('afterDispatch') : null;
$mainframe->triggerEvent('onAfterDispatch');

/**
 * RENDER  THE APPLICATION
 *
 * NOTE :
 */
$mainframe->render();

// trigger the onAfterRender events
JDEBUG ? $_PROFILER->mark('afterRender') : null;
$mainframe->triggerEvent('onAfterRender');

/**
 * RETURN THE RESPONSE
 */
//echo JResponse::toString($mainframe->getCfg('gzip'));


function test( $test) {


    echo "<B><div style='padding:5px;color:yellow;background:red'>QUERY BUILDER</div><BR>";
    echo "<TABLE>";


    echo "<TR><TD><P><B><I>Use dataset to obtain a query builder object</I></B></TD>";
    $dataset1 = JFactory::getDBSet();
    $qb1 = $dataset1->getQueryBuilder();
    $qb1->select("*")->from("#__test");

    echo "<TD>";
    echo $qb1->getSQL();
    echo "</TD></TR>";

    echo "<TR><TD><P><B><I>Use JFactory to obtain a query builder object</I></B></TD>";
    $qb2 = JFactory::getQueryBuilder("mysql");
    $qb2->select("*")->from("#__test");

    echo "<TD>";
    echo $qb2->getSQL();
    echo "</TD></TR>";


    echo "</TABLE>"; // end of QUERYbuilder section
    echo "<BR><BR>";

    echo "<B><div style='padding:5px;color:yellow;background:red'>DATASET</div><BR>";

    $dataset = JFactory::getDBSet("main2");
    echo "Connection name:" . $dataset->connection->getName();
    $users = JFactory::getDBRelation("user");
    $sections = JFactory::getDBRelation("section");

    echo "<P><B>Use dataset</B><HR>";
    $qb1 = $dataset->getQueryBuilder();
    $qb1->select('menutype')->from('#__menu');
    $dataset->addSQL($qb1->getSQL());
    $dataset->connection->enableTransactions();
    $dataset->setFetchStyle(PDO::FETCH_OBJ);
    if ($dataset->open()) {
        $data = $dataset->fetchAll();
        print_r($data);
    }


    echo "<P><B>Insert duplicate key</B><HR>";
    $qb1->resetQuery();
    $qb1->delete()->from("test");
    $dataset->addSQL($qb1->getSQL());

    $qb1->resetQuery();
    $qb1->insertinto("test")->fields(array("field1","field3"))->values(array(11,"'TESTME'"));
    $dataset->addSQL($qb1->getSQL());

    $dataset->open();



    echo "<P><B>Check JConnections Debug</B><HR>";
    $conn = JFactory::getDBConnection("main2");
    echo "Connection name: " . $conn->getName();
    $log = $conn->getLog();
    print_r($log);






    echo "<P><B>Open sections</B><HR>";
    $sections->open();
    //print_r($sections->_data);


    echo "<P><B>Open Users</B><HR>";
    $users->open();
    //print_r($users->_data);


    echo "<P><B>Transaction</B><HR>";
    //$query_array = array($qb1->replacePrefix( "insert into #__groups values(4,'test')"));
    //$dataset->setSQL($query_array);
    //$dataset->open();



    echo "<P><B>All datasets using the same Connection Instance</B><HR>";
    //$dataset2 = JFactory::getDBSet();
    //if ($users->connection === $sections->connection)  echo "EQ 1<BR>";
    //if ($dataset->connection === $dataset2->connection)  echo "EQ 2<BR>";
    //if ($users->connection === $dataset->connection)  echo "EQ 3<BR>";
    //if ($users->getQueryBuilder()  === $sections->getQueryBuilder())  echo "EQ 4<BR>";


}


/****************************************************************/
/****************************************************************/
/****************************************************************/
/****************************************************************/
/****************************************************************/



echo "<HR><B><U>Joda Test Drive</U></B><HR><BR>";

test( 'mysql');


?>
<jdoc:include type="modules" name="debug" />
