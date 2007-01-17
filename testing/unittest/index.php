<?php
define( '_JEXEC'       , 1 );
define( 'JPATH_BASE'   , dirname( dirname(__FILE__) ) );

require_once( JPATH_BASE.'/includes/defines.php' );
require_once( JPATH_BASE.'/libraries/loader.php' );
require_once( JPATH_BASE.'/unittest/UnitTestController.php' );

// If a path is provided run the test
if( $path = @$_REQUEST[ 'path' ] )
{
    switch( strtolower( @$_REQUEST['output'] ) )
    {
        case 'xml':
            new UnitTestController( urldecode( $path ), new TextReporter(), 'Unit Tests for Joomla: '.$path );
            exit();
        case 'php':
            new UnitTestController( urldecode( $path ), new TextReporter(), 'Unit Tests for Joomla: '.$path );
            exit();
        case 'json':
            new UnitTestController( urldecode( $path ), new TextReporter(), 'Unit Tests for Joomla: '.$path );
            exit();
        case 'text':
            new UnitTestController( urldecode( $path ), new TextReporter(), 'Unit Tests for Joomla: '.$path );
            exit();
        default:
            new UnitTestController( urldecode( $path ), new HtmlReporter(), 'Unit Tests for Joomla: '.$path );
            exit();
    }
}
// Show the default start page
else
{
    $tests = UnitTestController::getUnitTestsList();
    include( JPATH_BASE.'/unittest/views/default.html' );
}
?>