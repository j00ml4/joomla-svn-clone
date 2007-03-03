<?php
/**
 * Joomla! v1.5 UnitTest Platform
 *
 * @version	$Id$
 * @package 	Joomla
 * @subpackage 	UnitTest
 */

/**
 * Make sure we get all errors.
 */
error_reporting(E_ALL);

define('JUNITTEST_MAIN_METHOD', '-stub-');

require_once( dirname(__FILE__) . '/UnitTestController.php' );

// If run thru browser get REQUEST
if (JUNITTEST_CLI == false )
{
	$path   = @$_REQUEST[ 'path' ];
	$output = @$_REQUEST['output'];
	if (get_magic_quotes_gpc()) {
		$path = stripslashes($path);
	}
}
// If run from the command line get the args
else if ( count($_SERVER['argv']) > 1 )
{
	// kick scriptname
	array_shift($_SERVER['argv']);

    while ($token = array_shift($_SERVER['argv'])) {
    	switch ($token) {
    	case '-path':
    		$path   = trim(array_shift($_SERVER['argv']));
    		break;
    	case '-output':
    		$output = trim(array_shift($_SERVER['argv']));
    		break;
    	case '-list':
    		$list   = true;
    		break;
    	}
    }

}

/* no output format given, use default reporter */
if ( empty($output) ) {
	$output = JUNITTEST_CLI ? 'text' : JUNITTEST_REPORTER;
}

/* If a path is provided run the test */
if( !empty($path) )
{
    UnitTestController::main( urldecode( $path ), $output, $path );
    exit();
}
/* Otherwise show the fancy default start page */
else
{
    $testCases = array();
    $disabled  = array();
    $tests     = UnitTestController::getUnitTestsList( JUNITTEST_ROOT .DIRECTORY_SEPARATOR. JUNITTEST_BASE);

	$s = ($output != 'xml') ? (JUNITTEST_CLI ? '>' : '&raquo;') : '&gt;';

    foreach( $tests as $t => $path ) {
        if ( count( explode( DIRECTORY_SEPARATOR, $path ) ) > 1 )
        {
        	// from here on we use '/' rather than DIRECTORY_SEPARATOR

        	$path  = preg_replace('#[/\\\\]+#', '/', $path);
            $path  = implode( '/', array_slice( explode( '/', $path ), 1 ) );
	        $pinfo = pathinfo( $path );

	        // first entry will be '.'
	        $pinfo['dirname'] = ltrim($pinfo['dirname'], '.');
	        $pinfo['screen']  = str_replace('/', $s, $pinfo['dirname']);

	        if ( $pinfo['basename'] == 'AllTests.php') {
	        	$pinfo['screen'] .= "$s All Tests";
	        } else {
	        	$pinfo['screen'] .= "$s ". basename($pinfo['basename'], 'Test.php') ;
	        }

			// presuming "text" is used in a cron job so we wanna
			// know EVERYTHING, whereas others run interactive
			# {{ FIX
			if ( !JUNITTEST_CLI || $output != 'text') {
				if ( false == UnitTestHelper::isTestCaseEnabled( $pinfo ) ) {
					$disabled[JUNITTEST_BASE."/$path"] = 'disabled';
					// disable parent folder
					$parent = str_replace('/'.basename($path), '', $path);
					$disabled[JUNITTEST_BASE."/$parent"] = 'disabled';
				}
			}
			# }}
			$testCases[JUNITTEST_BASE."/$path"] = $pinfo;
        }
    }

    ksort($testCases);

    if ( isset($list) || JUNITTEST_CLI ) {
        foreach( $testCases as $path => $test ) {
            echo "$path\n";
        }
    } else {
        include( JUNITTEST_VIEWS.'/default.html' );
    }

}
