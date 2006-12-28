<?php
define( '_JEXEC'       , 1 );
define( 'JPATH_BASE'   , dirname( dirname(__FILE__) ) );

require_once( JPATH_BASE.'/includes/defines.php' );
require_once( JPATH_BASE.'/libraries/loader.php' );
require_once( JPATH_BASE.'/unittest/UnitTestController.php' );

// If a path is provided run the test
if( $path = @$_REQUEST[ 'path' ] )
{
    new UnitTestController( urldecode( $path ), new TextReporter(), 'Unit Tests for Joomla: '.$path );
    //new UnitTestController( urldecode( $path ), new HtmlReporter(), 'Unit Tests for Joomla: '.$path );
    exit();
}
?>
<html>
    <head>
        <title>Unit Tests for Joomla</title>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    </head>
    <body>
        <h1>Select Joomla! Unit Tests to Perform</h1>
        <!--Get a alist of all tests we can run-->
        <?$tests = UnitTestController::getUnitTestsList()?>

        <!--Loop through the tests and print a link for each one-->
        <?foreach( $tests as $test ):?>
        <div><a href="?path=<?=urlencode($test)?>"><?=$test?></a></div>
        <?php endforeach; ?>
    </body>
</html>