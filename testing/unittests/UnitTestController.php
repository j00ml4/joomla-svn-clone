<?php
require_once( dirname(__FILE__).'/simpletest/unit_tester.php' );
require_once( dirname(__FILE__).'/simpletest/reporter.php' );

if( !defined( 'TEST' ) )          define( 'TEST'         , __FILE__ );
if( !defined( 'UNITTEST_BASE' ) ) define( 'UNITTEST_BASE', 'tests' );

class UnitTestController extends GroupTest
{
    function UnitTestController( $path, $reporter, $label='' )
    {
        $this->GroupTest( $label );
        
        $files = array();
        
        if( is_dir( $path ) )
            $files = $this->_files( $path );
        elseif( is_file( $path ) )
            $files[] = $path;
        
        foreach( $files as $file )
        {
            include_once( str_replace( UNITTEST_BASE.DIRECTORY_SEPARATOR, '..'.DIRECTORY_SEPARATOR, $file ) );
            $this->addTestFile( $file );
        }
        
        $this->run( $reporter );
    }
    
    function getUnitTestsList()
    {
        $tests = UnitTestController::_files( UNITTEST_BASE, true );
        
        return array_merge( array( UNITTEST_BASE ), $tests );
    }
    
    function _files( $path, $showFolders=false )
	{
		// Initialize variables
		$files = array ();
        
		// Is the path a folder?
		if ( !is_dir( $path ) ) return $files;

		// read the source directory
		$handle = opendir( $path );
		
		while ( $file = readdir( $handle ) )
		{
			$fullpath = $path.DIRECTORY_SEPARATOR.$file;

			if ( is_dir( $fullpath ) && $file != '.' && $file != '..' && $file != '.svn' )
			{
			    if( $showFolders ) $files[] = $fullpath;
			    
				$folder = UnitTestController::_files( $fullpath, $showFolders );
				$files = array_merge( $files, $folder );
			}
			elseif( substr( $file, -4 ) == '.php' )
			{
				$files[] = $fullpath;
			}
		}
		
		closedir( $handle );
		
		asort( $files );
		
		return $files;
	}
}
?>