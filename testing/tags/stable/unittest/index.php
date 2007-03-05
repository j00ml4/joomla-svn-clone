<?php
define( '_JEXEC'       , 1 );
define( 'JPATH_BASE'   , dirname( dirname(__FILE__) ) );
define( 'UNITTEST_ROOT', dirname( __FILE__ ) );
define( 'UNITTEST_BASE', 'tests' );

require_once( JPATH_BASE.'/libraries/loader.php' );
require_once( JPATH_BASE.'/includes/defines.php' );
require_once( JPATH_BASE.'/unittest/UnitTestController.php' );

/**
 * TODO: Check why loader.php needed require_once?
 * TODO: Confirm if we can move these to a general location, or think about the use of extends?
 */
require_once( JPATH_BASE.'/libraries/joomla/base/object.php' ); // JObject

$path = @$_REQUEST[ 'path' ];
$output = @$_REQUEST['output'];
if (get_magic_quotes_gpc()) {
	$path = stripslashes($path);
}

// If run from the command line get the args
if( in_array( @$argv[1], array( '-path', '-output' ) ) )
{
    for($i=0;$i<count($argv);$i++)
    {
        if( $argv[$i] == '-path' )
            $path = trim( @$argv[$i+1] );
        if( $argv[$i] == '-output' )
            $output = trim( @$argv[$i+1] );
    }
    
    if( !$output ) $output = 'text';
}

// If a path is provided run the test
if( $path )
{
    $path = UNITTEST_BASE.DIRECTORY_SEPARATOR.$path;
    
    switch( strtolower( $output ) )
    {
        case 'xml':
            require_once( JPATH_BASE.'/unittest/views/JoomlaXml.php' );
            $reporter =& new JoomlaXml();
            break;

        case 'php':
            require_once( JPATH_BASE.'/unittest/views/JoomlaPhp.php' );
            $reporter =& new JoomlaPhp();
            break;

        case 'json':
            require_once( JPATH_BASE.'/unittest/views/JoomlaJson.php' );
            $reporter =& new JoomlaJson();
            break;

        case 'text':
            require_once( JPATH_BASE.'/unittest/views/JoomlaText.php' );
            $reporter =& new JoomlaText();
            break;

        default:
            require_once( JPATH_BASE.'/unittest/views/JoomlaHtml.php' );
            $reporter =& new JoomlaHtml();
            break;
    }

    new UnitTestController( urldecode( $path ), $reporter, $path );
    exit();
}
// Otherwise show the default start page
else
{
    $testCases = array();
    $tests = UnitTestController::getUnitTestsList();
    
    foreach( $tests as $path )
        if( count( explode( DIRECTORY_SEPARATOR, $path ) ) > 1 )
        {
            $path = implode( DIRECTORY_SEPARATOR, array_slice( explode( DIRECTORY_SEPARATOR, $path ), 1 ) );
            $testCases[$path] = implode( '->', explode( DIRECTORY_SEPARATOR, $path ) );
        }
    
    if( @$argv[1] == '-list' )
        foreach( $testCases as $path => $test )
            echo "$path\n";
    else
        include( JPATH_BASE.'/unittest/views/default.html' );
}
?>