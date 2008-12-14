<?php



// Call JObjectTest::main() if this source file is executed directly.
if (!defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'Archive_Tar::main');
	$JUnit_home = DIRECTORY_SEPARATOR . 'unittest' . DIRECTORY_SEPARATOR;
	if (($JUnit_posn = strpos(__FILE__, $JUnit_home)) === false) {
		die('Unable to find ' . $JUnit_home . ' in path.');
	}
	$JUnit_posn += strlen($JUnit_home) - 1;
	$JUnit_root = substr(__FILE__, 0, $JUnit_posn);
	$JUnit_start = substr(
		__FILE__,
		$JUnit_posn + 1,
		strlen(__FILE__) - strlen(basename(__FILE__)) - $JUnit_posn - 2
	);
	require_once $JUnit_root . DIRECTORY_SEPARATOR . 'setup.php';
}

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

jimport( 'pear.archive_tar.Archive_Tar' );

require_once 'Archive_Tar-helper-dataset.php';

class Archive_TarTest extends PHPUnit_Framework_TestCase
{
	var $instance = null;

	/**
	 * Runs the test methods of this class.
	 *
	 * @access public
	 * @static
	 */
	function main() {
		$suite  = new PHPUnit_Framework_TestSuite(__CLASS__);
		$result = PHPUnit_TextUI_TestRunner::run($suite);
	}

	function setUp()
	{
		//$this->instance = new JObjectExtend;
	}

	function tearDown()
	{
		$this->instance = null;
	}

	static public function archive_TarData() {
		return archive_TarTest_DataSet::$archive_TarData;
	}

	static public function createModifyData() {
		return archive_TarTest_DataSet::$createModifyData;
	}


	/**
	 * @dataProvider archive_TarData
	 */
	function testArchive_TarFromDataSet($fileName, $compress, $expectCompress, $expectCompressType) {
		$archive = new Archive_Tar($fileName, $compress);
		$this->assertEquals($archive->_tarname, $fileName);
		$this->assertEquals($archive->_compress, $expectCompress);
		$this->assertEquals($archive->_compress_type, $expectCompressType);
	}

	/**
	 * @dataProvider createModifyData
	 */
	function testCreateModifyFromDataSet($p_filelist, $p_add_dir, $p_remove_dir='') {
		$archive = new Archive_Tar('test.tar.gz');
		chdir( 'files' );
		$archive->createModify($p_filelist, $p_add_dir, $p_remove_dir);
		$fileDataBefore = array();
		if (!is_array($p_filelist)) {
			$p_filelist = explode(' ', $p_filelist);
		}

		foreach($p_filelist AS $file) {
			Archive_TarTest::buildFileData($fileDataBefore, $file);
		}
		
		$archiveNew = new Archive_Tar('test.tar.gz');
		mkdir('../tmp');
		$archiveNew = $archiveNew->extractModify('../tmp', '', '');
		$fileDataAfter = array();
		$prefix = '../tmp/';
		foreach($p_filelist AS $file) {
			Archive_TarTest::buildFileData($fileDataAfter, $file, $prefix.$p_add_dir, true);
		}
		$this->assertEquals($fileDataBefore, $fileDataAfter);
		unlink('test.tar.gz');
		Archive_TarTest::unlinkRecursive( '../tmp', true);
		chdir('..');
	}

	function unlinkRecursive($dir, $deleteRootToo)
	{
	    if(!$dh = @opendir($dir))
	    {
		return;
	    }
	    while (false !== ($obj = readdir($dh)))
	    {
		if($obj == '.' || $obj == '..')
		{
		    continue;
		}

		if (!@unlink($dir . '/' . $obj))
		{
		    Archive_TarTest::unlinkRecursive($dir.'/'.$obj, true);
		}
	    }

	    closedir($dh);
	   
	    if ($deleteRootToo)
	    {
		@rmdir($dir);
	    }
	   
	    return;
	} 

	function buildFileData(&$fileDataBefore, $p_file, $prefix = '', $delete = false) {
		if(!$dh = @opendir($prefix.$p_file)) {
			$fh = file_get_contents($prefix.$p_file);
			$fileInfo = new stdClass();
			$fileInfo->md5sum = md5($fh);
			$statData = stat($prefix.$p_file);
			$fileInfo->statdata = array();
			$fileInfo->statdata[7] = $statData[7];
			// two of these should probably work, but they don't
			//$fileInfo->statdata[8] = $statData[8];
			//$fileInfo->statdata[9] = $statData[9];
			//$fileInfo->statdata[10] = $statData[10];
			$fileDataBefore[] = $fileInfo;
			if ($delete) {
				unlink($prefix.$p_file);
			}
		} else {
			while (false !== ($obj = readdir($dh)))
			{
				if($obj == '.' || $obj == '..')
				{
					continue;
				}

				Archive_TarTest::buildFileData($fileDataBefore, $p_file.'/'.$obj, $prefix);
			}
			closedir($dh);
			if ($delete) {
				@rmdir($prefix.$p_file);
			}
		}	
		
	}

}




// Call JObjectTest::main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'Archive_TarTest::main') {
	Archive_TarTest::main();
}
