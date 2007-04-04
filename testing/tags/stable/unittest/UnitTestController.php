<?php
require_once( dirname(__FILE__).'/simpletest/unit_tester.php' );
require_once( dirname(__FILE__).'/simpletest/reporter.php' );

class UnitTestController extends GroupTest
{
    function UnitTestController( $path, &$reporter, $label='' )
    {
        $this->GroupTest( $label );
        
        $files = array();
        
        if( is_dir( $path ) )
            $files = $this->_files( $path );
        elseif( is_file( $path ) )
            $files[] = $path;
        
        // Make new test files
        if( count( $files ) == 0 )
        {
            $this->makeUnitTestFiles( str_replace( UNITTEST_BASE.DIRECTORY_SEPARATOR, '..'.DIRECTORY_SEPARATOR, $path ) );
            return $this->UnitTestController( $path, $reporter, $label );
        }
            
        foreach( $files as $file )
        {
//            include_once( str_replace( UNITTEST_BASE.DIRECTORY_SEPARATOR, '..'.DIRECTORY_SEPARATOR, $file ) );
            $this->addTestFile( $file );
        }
        
        $this->run( $reporter );
    }
    
    function makeUnitTestFiles( $path )
    {
        $files = array();
        
        if( is_dir( $path ) )
            $files = $this->_files( $path );
        elseif( is_file( $path ) )
            $files[] = $path;
        
        foreach( $files as $file ) $this->makeUnitTestFile( $file );
    }
    
    function makeUnitTestFile( $sourceFile )
    {
        $file = UNITTEST_ROOT.DIRECTORY_SEPARATOR.UNITTEST_BASE . str_replace( '..', '', $sourceFile ); // create path in test dir
        
        // Check if the file is already in the test dir, if it is return
        if( file_exists( $file ) ) return;
        
        // TODO: legacy  will need to be done by hand
        if( strpos( $sourceFile, 'legacy' ) ) return;
        // TODO: patTemplate will need to be done by hand
        if( strpos( $sourceFile, 'template' ) ) return;
        
        $dir = dirname( $file );
        if( !is_dir( $dir ) ) mkdir( $dir, 0777, true ); // Create the dir if not found
        
        include_once( $sourceFile );
        $class = $this->_getClassFromFile( $sourceFile );
        
        $handle = fopen( $file, 'wb' );
        fwrite( $handle, $this->getUnitTestTemplate( $class, get_class_methods( $class ) ) );
        fclose( $handle );
    }
    
    function getUnitTestTemplate( $class, $methods )
    {
        if( !is_array( $methods ) ) return;
        
        // remove any overloaded methods
        $methods = array_unique( $methods );
        
        $line = "<?php\n";
        $line.= "class TestOf{$class} extends UnitTestCase\n";
        $line.= "{\n";
        foreach( $methods as $method )
        {
            $line.= "    function test".ucfirst($method)."()\n";
            $line.= "    {\n";
            $line.= "        \$this->assertTrue( false );\n";
            $line.= "    }\n\n";
        }
        $line.= "}\n";
        $line.= "?>";
        
        return $line;
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
		
		// First get Files fron the Dir
		while ( $file = readdir( $handle ) )
		{
			$fullpath = $path.DIRECTORY_SEPARATOR.$file;

			if ( is_file( $fullpath ) && substr( $file, -4 ) == '.php' )
			{
				$files[] = $fullpath;
			}
		}
		
		rewinddir( $handle );
		
		// Now check directories in the dir
	    while ( $file = readdir( $handle ) )
		{
			$fullpath = $path.DIRECTORY_SEPARATOR.$file;

			if ( is_dir( $fullpath ) && $file != '.' && $file != '..' && $file != '.svn' )
			{
			    if( $showFolders ) $files[] = $fullpath;
			    
				$folder = UnitTestController::_files( $fullpath, $showFolders );
				$files = array_merge( $files, $folder );
			}
		}
		
		closedir( $handle );
		
		return $files;
	}
	
	function _getClassFromFile( $path )
	{
	    $lines = file( $path );
        
        foreach( $lines as $line )
            if( strtolower( substr( $line, 0, 5 ) ) == 'class' )
            {
                $parts = explode( ' ', $line );
                return trim( @$parts[1] );
            }
	}
}
?>