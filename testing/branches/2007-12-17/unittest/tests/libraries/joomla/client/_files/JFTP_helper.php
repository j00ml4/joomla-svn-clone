<?php
/**
 * Helper for ftp.php
 *
 * @package Joomla
 * @subpackage UnitTest
 * @version $Id$
 */

!defined('FTP_NATIVE') && define('FTP_NATIVE', JUT_JFTP_NATIVE);

class JFTPTestHelper
{

	function setUpTestCase()
	{
		require_once 'libraries/joomla/utilities/error.php';

		// backup
		$handler = array();
		$handler[E_NOTICE]  = JError::getErrorHandling(E_NOTICE);
		$handler[E_WARNING] = JError::getErrorHandling(E_WARNING);
		$handler[E_ERROR]   = JError::getErrorHandling(E_ERROR);
		JUnit_Setup::setProperty('JFTPTest', 'errorhandler', $handler);

		JError::setErrorHandling(E_NOTICE, 'trigger');
		JError::setErrorHandling(E_WARNING, 'trigger');
		JError::setErrorHandling(E_ERROR, 'trigger');

		/** @TODO: Fix this in JFTP!!! */
		/*
		 * Temporary workaround!!!! otherwise the destructor test will fail, as
		 * there is a reference to the object laying around in the unit tester's
		 * error handler
		 */
		!defined('JPATH_ISWIN') && define('JPATH_ISWIN', true);

		JFTPTestHelper::credentialsAvailable();
	}

	function tearDownTestCase()
	{
		$handler =& JUnit_Setup::getProperty('JFTPTest', 'errorhandler');
		// restore
		JError::setErrorHandling(E_NOTICE,  $handler[E_NOTICE]['mode']);
		JError::setErrorHandling(E_WARNING, $handler[E_WARNING]['mode']);
		JError::setErrorHandling(E_ERROR,   $handler[E_ERROR]['mode']);
	}

	// Not a real test. Only used to display some general information
	function credentialsAvailable()
	{
		$reporter = &JUnit_Setup::getReporter();

		if (($conf = JFTPTestHelper::getCredentials()) === false) {
			$reporter->skip();
			$reporter->ignore('JFTPTest');
			$reporter->paintMessage('Missing credentials. See JFTP_readme.txt for instructions.');
		} else {
			$msg = 'Testing host ['.$conf['host'].'] on port ['.$conf['port'].'] with user ['
				.$conf['user'].'] and pass ['.str_repeat('*', strlen($conf['pass'])).']. '
				.' FTP Root Path set to ['.$conf['root'].']'
			;
			$reporter->paintMessage($msg);
			$reporter->paintMessage('FTP_NATIVE mode is switched ' . (FTP_NATIVE? 'ON' : 'OFF'));
		}
	}

	function getCredentials()
	{
		$credentials = &JUnit_Setup::getProperty('JFTPTest', 'credentials');

		if ($credentials !== null) {
			return $credentials;
		}

		$config = JUnit_Setup::makeCfg(JUT_JFTP_CREDENTIALS, false, true);

		// Credentials valid?
		if (!($config['host'] && $config['port'] && $config['user'] && $config['pass'])) {
			$credentials = null;
		} else {
			$credentials = $config;
		}

		return $credentials;

	}

	function invalidateCredentials()
	{
		$reporter = &JUnit_Setup::getReporter();
		$reporter->paintMessage('Invalidating Credentials due to previous error');
		$credentials = &JUnit_Setup::getProperty('JFTPTest', 'credentials');
		$credentials = null;
	}


	// Helper function, PHP4
	function microtime_float()
	{
		list($usec, $sec) = explode(' ', microtime());
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
