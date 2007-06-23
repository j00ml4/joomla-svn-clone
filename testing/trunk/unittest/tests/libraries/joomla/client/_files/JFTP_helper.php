<?php
/**
 * Helper for ftp.php
 *
 * @package 	Joomla
 * @subpackage 	UnitTest
 */

// $Id$

!defined('FTP_NATIVE') && define('FTP_NATIVE', JUT_FTP_NATIVE);

class JFTPTestHelper
{

	function setUpTestCase()
	{
		$handler =& UnitTestHelper::getProperty('TestOfJFTP', 'errorhandler');
		jimport('joomla.utilities.error');

		// backup
		$handler[E_NOTICE]  = JError::getErrorHandling(E_NOTICE);
		$handler[E_WARNING] = JError::getErrorHandling(E_WARNING);
		$handler[E_ERROR]   = JError::getErrorHandling(E_ERROR);

		JError::setErrorHandling(E_NOTICE, 'trigger');
		JError::setErrorHandling(E_WARNING, 'trigger');
		JError::setErrorHandling(E_ERROR, 'trigger');

		/** @TODO: Fix this in JFTP!!! */
		/*
		 * temporary workaround!!!! otherwise the destructor test will fail,
		 * as there is a reference to the object laying around in the unit tester's error handler
		 */
		!defined('JPATH_ISWIN') && define('JPATH_ISWIN', true);

	}

	function tearDownTestCase()
	{
		$handler =& UnitTestHelper::getProperty('TestOfJFTP', 'errorhandler');
		// restore
		JError::setErrorHandling(E_NOTICE,  $handler[E_NOTICE]['mode']);
		JError::setErrorHandling(E_WARNING, $handler[E_WARNING]['mode']);
		JError::setErrorHandling(E_ERROR,   $handler[E_ERROR]['mode']);
	}

	// Helper function, PHP4
	function microtime_float()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

	function binaryToString($binary)
	{
		$string = '';
		for($i=0, $n=strlen($binary); $i<$n; $i++) {
			$string .= ord($binary{$i}).'-';
		}
		return $string;
	}

}
