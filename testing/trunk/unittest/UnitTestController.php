<?php
/**
 * Joomla! v1.5 UnitTest Platform.
 *
 * @version	$Id$
 * @package 	Joomla
 * @subpackage 	UnitTest
 */

require_once( dirname(__FILE__) . '/prepend.php');

!defined('JUNITTEST_MAIN_METHOD') && define('JUNITTEST_MAIN_METHOD', 'UnitTestController::main');

/* called from a TestCase */
if ( JUNITTEST_MAIN_METHOD !== 'UnitTestController::main') {
	return 1;
}

/**
 * The Controller ...
 */
class UnitTestController
{
	/**
	 * @param string $path   filepath in JUNITTEST_BASE
	 * @param string $output renderer [html|xml|json|php|text|custom]
	 * @param string $label  TestCase caption, defaults to $path
	 */
    function main( $path, $output, $label='' )
    {
    	$ds     = DIRECTORY_SEPARATOR;
		$source = JPATH_BASE     .$ds. $path;
    	$target = JUNITTEST_ROOT .$ds. JUNITTEST_BASE .$ds. $path;
        $files  = array();

print_r( array(
$target,
$source
)
);
exit;
		/* run an existing TestCase? */
		if ( is_file($target) ) {
            $files = array('RUN'=>$target);
		}
		elseif ( is_file($source) || is_dir($source) ) {
            $files = array('MAKE'=>$source);
		}

        if ( JUNITTEST_CLI && isset($files['MAKE']) ) {
	    	/* create Skeleton(s), CLI + PHP5 only! */
			$target = dirname(JUNITTEST_ROOT .$ds. JUNITTEST_BASE . str_replace(JPATH_BASE, '', $source));
			echo PHP_EOL, 'SOURCE: ', $source, PHP_EOL, 'TARGET: ', $target, PHP_EOL, PHP_EOL;

die(PHP_EOL.'Sorry! Calling Skeleton builder from CLI is currently broken.'.PHP_EOL);

			$files = UnitTestController::makeUnitTestFiles( $source );
//			return UnitTestController::main( $path, $output, $label );
        }

        $suite = new TestSuite( (count($files) ? $label : 'Empty TestSuite') );

        foreach( $files as $file )
        {
        	$const = UnitTestHelper::getTestCaseConfigVar($file);

        	if ( !@constant($const) ) continue;

			$helper = str_replace(basename($file), '_files'.$ds.basename($file,'.php').'_helper.php', $file);
			include_once( $helper );

            include_once( str_replace( JUNITTEST_BASE.$ds, '..'.$ds, $file ) );
            $suite->addTestFile( $file );
        }

        $suite->run( UnitTestHelper::getReporter($output) );

    }

    function makeUnitTestFiles( $path )
    {
        $files  = array();
		$folder = array();

        if ( is_dir( $path ) ) {
            $files = UnitTestController::_files( $path, true);
        } elseif( is_file( $path ) ) {
            $files = array($path);
        }

        foreach( $files as $file ) {
        	$folder[] = UnitTestController::makeUnitTestFile( $file );
        }
        return array_merge($files, $folder);
    }

    function makeUnitTestFile( $sourceFile )
    {
		$files = array();
        // TODO: legacy will need to be done by hand
        // NOTE: probably solved since legacy code moved to CMS /plugins/system/legacy
        if ( strpos( $sourceFile, 'legacy' ) ) return $files;
        // TODO: patTemplate will need to be done by hand
        if ( strpos( $sourceFile, 'template' ) ) return $files;

		// make absolute paths
		$target     = $sourceFile;
		$sourceFile = $sourceFile;


        // Create the dir if not found
        $dir = dirname( $target );
//print_r( array($sourceFile, $target, $dir) );
if (1) return array();

        if ( !is_dir( $dir ) ) mkdir( $dir, 0777, true );

        $classes = UnitTestController::_getClassFromFile( $sourceFile );

		foreach ($classes as $class => $extends) {
			$testfile = dirname($target) .DIRECTORY_SEPARATOR. $class . 'Test.php';

			// Check if the file is already in the test dir, if it is continue
			if ( file_exists( $testfile ) ) continue;
			$handle = fopen( $testfile, 'wb' );
			if ( class_exists('Reflection') ) {
				$skeleton = UnitTestController::getUnitTestTemplateReflection( $class, $sourceFile, $extends );
	        } else {
	        	$skeleton = UnitTestController::getUnitTestTemplate( $class, get_class_methods( $class ), $extends );
	        }
	        fwrite( $handle, $skeleton);
	        fclose( $handle );
	        $files[] = $testfile;
		}
		return $files;
    }

	/**
	 * Due to the limitation in PHP4 it's HIGHLY recommended to use PHP 5
	 * to create new testcase Skeletons.
	 * @see getUnitTestTemplateReflection()
	 */
    function getUnitTestTemplate( $class, $methods, $extends=false )
    {
        if( !is_array( $methods ) ) return;

        // remove any overloaded methods
        $methods = array_unique( $methods );

        $line = "<?php\n";
        $line = "// \$Id\$\n";
        $line.= "class TestOf{$class} extends UnitTestCase\n";
        $line.= "{\n";
        $line.= "    function setUp()\n    {\n    }\n";
        $line.= "    function tearDown()\n    {\n    }\n";
        foreach( $methods as $method )
        {
            $line.= "    function test".ucfirst($method)."()\n";
            $line.= "    {\n";
            $line.= "        \$this->assertTrue( false );\n";
			$line.= "        \$this->_reporter->setMissingTestCase(__FUNCTION__, 'Implement');\n";
            $line.= "    }\n\n";
        }
        $line.= "}\n";
        $line.= "?>";

        return $line;
    }

	/**
	 * PHP5 only, but more accurate TestCase Skeletons -- thanx to PHPUnit :)
	 */
    function getUnitTestTemplateReflection( $class, $file, $extends=false )
    {
    	if ( !class_exists($class, false) ) return;
    	if ( !class_exists('PHPUnit_Util_Skeleton', false) ) {
			UnitTestHelper::loadFile('Skeleton.php', JUNITTEST_LIBS .'/Util', true);
    	}
    	$Skeleton = new PHPUnit_Util_Skeleton($class, $file);
    	return $Skeleton->generate( false );
    }

    function getUnitTestsList( $path )
    {
        return UnitTestController::_files( $path, true );
    }

	/**
	 * @param string $path full qualified path
	 */
    function _files( $path, $showFolders=false )
	{
		// Initialize variables
		$files   =
		$folder  = array();
		$xfolder = array('.', '..','.svn', '_files');

		// Is the path a folder?
		if ( !is_dir( $path ) ) return $files;

		// First get Files from the Dir
		foreach (glob($path.DIRECTORY_SEPARATOR.'*.php') as $filename) {
			if ( substr(basename($filename), 0, 1) != '_') {
			    $files[] = $filename;
			}
		}

		if ($showFolders) {
			foreach (glob($path.DIRECTORY_SEPARATOR.'*', GLOB_ONLYDIR) as $foldername) {
			    $path = $foldername;
			    if ( false === in_array(basename($path), $xfolder) ) {
				    $folder += UnitTestController::_files($foldername, $showFolders);
			    }
			}
			$files = array_merge($files, $folder);
		}

		return $files;
	}

	/**
	 * @todo: fix to read ALL classes incl. Helpers
	 */
	function _getClassFromFile( $path )
	{
        include_once( $path );

	    $lines   = file( $path );
	    $found   = array();

	    $classes = preg_grep('/^\s?class\sJ[A-Z][a-z]+/', $lines);
		foreach ($classes as $decl) {
			$tokens = preg_split('/\s+/', trim($decl));
			if ( isset($tokens[1]) ) {
				$found[ $tokens[1] ] = isset($tokens[3]) ? $tokens[3] : false;
			}
		}
		return $found;
	}
}


if ( JUNITTEST_MAIN_METHOD == 'UnitTestController::main') {
	UnitTestController::main( JUNITTEST_BASE , JUNITTEST_REPORTER );
}