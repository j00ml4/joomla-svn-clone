<?php
define( '_JEXEC'       , 1 );
define( 'JPATH_BASE'   , dirname( dirname(__FILE__) ) );

require_once( JPATH_BASE.'/includes/defines.php' );
require_once( JPATH_BASE.'/libraries/loader.php' );
require_once( JPATH_BASE.'/unittest/UnitTestController.php' );

/**
 * TODO: Check why loader.php needed require_once?
 * TODO: Confirm if we can move these to a general location, or think about the use of extends?
 */
require_once( JPATH_BASE.'/libraries/joomla/common/abstract/object.php' ); // JObject

// If a path is provided run the test
if( $path = @$_REQUEST[ 'path' ] )
{
    switch( strtolower( @$_REQUEST['output'] ) )
    {
        case 'xml':
            require_once( JPATH_BASE.'/unittest/views/JoomlaXml.php' );
            new UnitTestController( urldecode( $path ), new JoomlaXml(), $path );
            exit();
        case 'php':
            require_once( JPATH_BASE.'/unittest/views/JoomlaPhp.php' );
            new UnitTestController( urldecode( $path ), new JoomlaPhp(), $path );
            exit();
        case 'json':
            require_once( JPATH_BASE.'/unittest/views/JoomlaJson.php' );
            new UnitTestController( urldecode( $path ), new JoomlaJson(), $path );
            exit();
        case 'text':
            require_once( JPATH_BASE.'/unittest/views/JoomlaText.php' );
            new UnitTestController( urldecode( $path ), new JoomlaText(), $path );
            exit();
        default:
            require_once( JPATH_BASE.'/unittest/views/JoomlaHtml.php' );
            new UnitTestController( urldecode( $path ), new JoomlaHtml(), $path );
            exit();
    }
}
// Show the default start page
else
{
    $testCases = array();
    $tests = UnitTestController::getUnitTestsList();
    
    foreach( $tests as $path )
        if( count( explode( DIRECTORY_SEPARATOR, $path ) ) > 1 )
            $testCases[$path] = implode( '->', array_slice( explode( DIRECTORY_SEPARATOR, $path ), 1 ) );
    
    include( JPATH_BASE.'/unittest/views/default.html' );
}
?>