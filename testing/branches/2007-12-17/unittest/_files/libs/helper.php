<?php
/**
 * Joomla! v1.5 UnitTest Platform Helper class.
 *
 * @version $Id: $
 * @package Joomla
 * @subpackage UnitTest
 * @copyright     Copyright (C) 2007 Rene Serradeil. All rights reserved.
 * @license        GNU/GPL
 */

/**
 * constants for $is_test property of the info object
 * @see UnitTestHelper::getInfoObject()
 */
define('JUNIT_IS_TESTSUITE', 1);
define('JUNIT_IS_TESTCASE',  2);
define('JUNIT_IS_FRAMEWORK', 4);

class UnitTestHelper
{
	/**
	 * Returns the Singleton instance of the active UnitTest Renderer for $output.
	 *
	 * @param string $output any of html,xml,php,json,text, custom
	 * @return object Instance of JoomlaXXX view or the 'custom' renderer.
	 */
	function &getReporter($output = null)
	{
		static $instance;

		if ($instance !== null) {
			return $instance;
		}

		if ($output == null) {
			$input  =& UnitTestHelper::getProperty('Controller', 'Input');
			$output = $input->reporter['output'];
		}

		switch(strtolower($output))
		{
		case 'xml':
		case 'php':
		case 'json':
		case 'text':
			$renderer = 'Joomla'.ucfirst($output);
			require_once JUNIT_VIEWS.'/'.$renderer.'.php';
			break;
		case 'html':
			# fall thru to default case
			$renderer = 'Joomla'.ucfirst($output);
		default:
			if ($output == 'html') {
				require_once JUNIT_VIEWS.'/'.$renderer.'.php';
			} else {
				require_once JUNIT_REPORTER_CUSTOM_PATH;
				$renderer = JUNIT_REPORTER_CUSTOM_CLASS;
			}

			break;
		}

		$instance =& new $renderer();
		return $instance;
	}

	/**
	 * Returns class name and output format of the current reporter.
	 *
	 * If JUNIT_REPORTER is 'custom' and JUNIT_REPORTER_CUSTOM_FORMAT
	 * is not provided, $output defaults to 'text'
	 *
	 * @return array ('class'=>$name, 'format'=>$output)
	 * @see getReporter()
	 * @uses JUNIT_REPORTER, JUNIT_REPORTER_CUSTOM_CLASS, JUNIT_REPORTER_CUSTOM_FORMAT
	 */
	function getReporterInfo()
	{
		$input =& UnitTestHelper::getProperty('Controller', 'Input');
		if (isset($input->output)) {
			$output = $input->output;
		} else {
			$output = (JUNIT_CLI)
					? strtolower(JUNIT_REPORTER_CLI)
					: strtolower(JUNIT_REPORTER);
		}

		if ($output == 'custom') {
			$format = JUNIT_REPORTER_CUSTOM_FORMAT;
			if (strstr(' html xml text php json ', $format) === false) {
				$format = 'text';
				$name   = 'Joomla'.ucfirst($format);
			} else {
				$name   = JUNIT_REPORTER_CUSTOM_CLASS;
			}
		} else {
			$format = $output;
			$name   = 'Joomla'.ucfirst($output);
		}
		return array('class'=>$name, 'format'=>$format, 'output'=>$output);
	}

	/**
	 * Tells whether the provided test is enabled via its config setting.
	 *
	 * Testsuites (AllTests.php) are enabled by default unless explicitly
	 * disabled using their *_ALL directive in `TestConfiguration.php`.
	 *
	 * @param  object $infoobj as returned from {@link getInfoObject()}
	 * @return int 0 = disabled, 1 = enabled
	 */
	function isTestEnabled($infoobj)
	{
		$constant   = UnitTestHelper::getTestConfigVar($infoobj);
		if ($infoobj->is_test == JUNIT_IS_TESTSUITE) {
			// mark AllTest enabled by default
			$configured = true;
			if (defined($constant)) {
				$configured = (bool)@constant($constant);
			} else {
				$available = true;
			}
		} else {
			$available  = defined($constant);
			$configured = (bool)@constant($constant);
		}

		return (int)($available && $configured);
	}

	/**
	 * Allows a Reporter class to query testcase configuration.
	 *
	 * @param  SimpleReporter $reporter
	 * @return bool true if a testcase is enabled
	 */
	function shouldInvoke(&$reporter, $class_name)
	{
		$UnitTests =& UnitTestHelper::getProperty('Reporter', 'UnitTests');
		$class_name = strtolower($class_name);
		if (isset($UnitTests[$class_name])) {
			$invoke = (bool)$UnitTests[$class_name]->enabled;
		} else {
			$invoke = false;
		}

		return $invoke;
	}

	/**
	 * @param  object $infoobj as returned from {@link getInfoObject()}
	 * @return string name of the configuration
	 */
	function getTestConfigVar(&$infoobj)
	{
		switch ($infoobj->is_test) {
		case JUNIT_IS_TESTSUITE:
			switch (trim($infoobj->dirname,'\\/')) {
			case '':
				$constant = JUNIT_PREFIX . 'FRAMEWORK';
				break;
			case 'libraries':
				$constant = JUNIT_PREFIX . 'LIBRARIES';
				break;
			default:
				$constant = JUNIT_PREFIX . $infoobj->package;
			}

			$constant .= '_ALL';
			break;

		case JUNIT_IS_TESTCASE:
			$constant = JUNIT_PREFIX . $infoobj->package .'_'. $infoobj->classname;
			break;

		default:
			$constant = '';
		}

		/* allow hook function to fix nameing scheme violations */
		if (!defined($constant)) {
			$infoobj->violation = true;
			# TODO: implement hook for config variables
			// someHackishConstantsHook(&$out)
		}

		return strtoupper($constant);
	}

	/**
	 * Returns the line number of a configuration directive (constant) in
	 * `TestConfiguration.php`
	 */
	function getConfigLine($directive, $source='TestConfiguration.php') {
		$lines = file($source, true);
		$line  = preg_grep('#'. strtoupper($directive) .'#', $lines);
		return key($line) + 1;
	}

	/**
	 * Returns the estimated amount of possible testcases based on the
	 * predefined configuration settings.
	 */

	/**
	 * Plucks $path into pieces and returns a standard object with all kind
	 * of stuff in it. The value of $path is the "path" input argument
	 * provided via $_REQUEST (browser mode) or the command-line (CLI Mode).
	 *
	 * See UnitTestHelper_examples.txt for more information about this array and it's drawbacks.
	 *
	 * @param  string $path relative of a framework or testcase file/dir
	 * @return array
	 * @uses getTestConfigVar(), isTestEnabled(), getTestDocs(), getTestHelper()
	 */
	function getInfoObject($path)
	{

		if (strrpos(basename($path), '.') === false) {
			if (strrpos($path, '/') < strlen($path)-1) {
				$path = rtrim($path, '/') . '/';
			}
		}

		$path   = preg_replace('#[/\\\\]+#', '/', $path);
		$slash  = strrpos($path, '/') + 1;
		$sbase  = ($slash > 1)
					? basename(substr($path, $slash), '.php')
					: basename($path, '.php');

		$out       = new stdClass;
		$out->path = rtrim($path, '/');
		if ($slash > 1) {
			$out->dirname  = substr($path, 0, $slash);
			$out->filename  = substr($path, $slash);
		} else {
			$out->dirname  = '';
			$out->filename  = $path;
		}

		// Modified below
		$out->basename = $sbase;
		$out->classname = $sbase;
		$out->testclass = $sbase;
		$out->package   = 'Joomla';
		$out->enabled   = false;
		$out->is_test   = 0;

		// determine "TestSuite Package" 'libraries/joomla/package/subpackage/'
		// not necessarily identical to the J!F Package the tested file belongs to
		$parts = explode('/', rtrim($out->dirname, '\\/'));
		if (count($parts) > 1) {
			if (isset($parts[2])) {
				$out->package = ucwords($parts[2]);
				if (isset($parts[3])) {
					$out->package .= '_'.ucwords($parts[3]);
				}
			} else {
				// Libraries
				$out->package = ucwords($parts[0]);
			}
		} else {
			if (empty($sbase)) {
				$out->package = 'UnitTests';
			} else {
				$out->package = 'Framework';
			}
		}

		/* let's have a first peak of what this might be */
		$file = ' '.strtolower($out->filename);
		if (strpos($file, 'alltests.php') !== false) {
			$out->is_test   = JUNIT_IS_TESTSUITE;
			$out->classname = false;
			$out->config    = UnitTestHelper::getTestConfigVar($out);
		} elseif (strpos($file, 'test.php') !== false) {
			$out->is_test   = JUNIT_IS_TESTCASE;
			$out->classname = basename($sbase, 'Test');
			$out->config    = UnitTestHelper::getTestConfigVar($out);
		}
		if ($out->package == 'Framework') {
			$out->is_test   = $out->is_test | JUNIT_IS_FRAMEWORK;
			$out->basename = $sbase;
		}

		unset($file);

		/* allow hook function to fix naming scheme violations */
		if (isset($out->config) && !defined($out->config)) {
			$out->violation = true;
			# TODO: implement hook for path+class+whatever variables
			// someHackishNamesHook(&$out)
		}

		if (!is_dir(JPATH_BASE .'/'. $out->dirname)) {
			$out->enabled = false;
			$out->task    = 'error';
			$out->error   = sprintf('Directory does not exist in JPATH_BASE: "%1$s"', $out->dirname);
			return $out;
		}

		$out->enabled = UnitTestHelper::isTestEnabled($out);

		switch ($out->is_test) {
		case JUNIT_IS_TESTSUITE:
			$out->testclass = $out->package .'_'. $sbase;
			break;
		case JUNIT_IS_TESTCASE:
			$out->testclass = 'TestOf'.basename($sbase, 'Test');
			break;
		case JUNIT_IS_FRAMEWORK:
			$out->testclass = $sbase;
			break;
		}

		if ($out->is_test != JUNIT_IS_FRAMEWORK) {
			$out->docs = UnitTestHelper::getTestDocs($out);
		}

		if ($out->enabled == false) {
			return $out;
		}

		if (is_dir(JUNIT_ROOT .'/'. JUNIT_BASE .'/'. $out->dirname .'_files')) {
			if ($out->is_test === JUNIT_IS_TESTCASE) {
				$out->helper = UnitTestHelper::getTestHelper($out);
			}
		}

		$testcase =& UnitTestHelper::getProperty('Reporter', 'UnitTests', 'array');
		$testcase[strtolower($out->testclass)] =& $out;

		// JUNIT_CLI

		return $out;
	}

	/**
	 * @ignore
	 * @return array
	 */
	function &getTestHelper($infoobj)
	{
		$helper =& UnitTestHelper::getProperty('Helper', $infoobj->classname);
		$helper = array(
					'dirname'   => $infoobj->dirname . '_files/',
					'filename'  => $infoobj->classname . '_helper.php',
					'classname' => $infoobj->basename . 'Helper',
					'script'    => $infoobj->path,
					'location'  => false,
					);

		$location = $helper['dirname'] . $helper['filename'];
		if (file_exists(JUNIT_ROOT .'/'. JUNIT_BASE .'/'. $location)) {
			$helper['location'] = preg_replace('#[/\\\\]+#', '/', JUNIT_ROOT .'/'. JUNIT_BASE .'/'. $location);
		}
		return $helper;
	}

	/**
	 * @ignore
	 * @return array
	 */
	function &getTestDocs($infoobj)
	{
		$docs = glob(JUNIT_ROOT .'/'.
					JUNIT_BASE .'/'.
					$infoobj->dirname .'_files/'.
					$infoobj->classname.'_*.txt');

		if (count($docs) > 0) {
			$l = strlen(JUNIT_ROOT .'/'. JUNIT_BASE .'/');
			foreach (array_keys($docs) as $i) {
				$docs[$i] = substr($docs[$i], $l);
			}
		}
		return $docs;
	}

	/**
	 * poor man's registry to prevent clutter of the global variable namespace.
	 * <code>
	 * function foo() {
	 *   // initialize property
	 *   $stuff =& UnitTestController::getProperty('my', 'stuff');
	 *   $stuff =  new JConfig;
	 * }
	 *
	 * function bar() {
	 *   // retrieve property
	 *   $stuff =& UnitTestController::getProperty('my', 'stuff');
	 *   echo $stuff->language;
	 * }
	 * </code>
	 *
	 * @param string $namespace   used to prevent clashes, usually a class name
	 * @param string $prop        name of the "virtual" property
	 * @param string [$forcetype] PHP typename, i.e. 'array'
	 * @see unsetProperty()
	 */
	function &getProperty($namespace, $prop, $forcetype = null, $destroy = false)
	{
		static $properties = array();
		if ($destroy === true) {
			unset($properties[$namespace][$prop]);
			return $destroy;
		}
		if (!isset($properties[$namespace])) {
			$properties[$namespace] = array();
		}
		else if (null === $prop) {
			foreach (array_keys($properties[$namespace]) as $p) {
				unset($properties[$namespace][$p]);
			}
			return $namespace;
		}
		if (!isset($properties[$namespace][$prop]) && $forcetype !== null) {
			@settype($properties[$namespace][$prop], $forcetype);
		}
		return $properties[$namespace][$prop];
	}

	function unsetProperty($namespace, $prop)
	{
		UnitTestHelper::getProperty($namespace, $prop, null, true);
	}

	function toggleHostUrl()
	{
		if (JUNIT_HOME_PHP4 != JUNIT_HOME_PHP5) {
			if (strpos(JUNIT_HOME_PHP4, $_SERVER['HTTP_HOST']) === false) {
				$php = 'PHP4';
				$url = JUNIT_HOME_PHP4;
			}
			if (strpos(JUNIT_HOME_PHP5, $_SERVER['HTTP_HOST']) === false) {
				$php = 'PHP5';
				$url = JUNIT_HOME_PHP5;
			}
		} else {
			$php = $url = $output = '';
		}
		return array($php, $url);
	}

	/**
	 * Parse URI schemes into JConfig compliant properties:
	 * - scheme://username:prot@hostspec/resource_path?query#fragment
	 *
	 * You may use the query part to set additional properties.
	 *
	 * Pseudo-protocol targets, special query parameters and #fragments
	 * Example   : mysql://user:pass@localhost/testdb1?dbprefix=jos_
	 * - scheme   ~ dbtype
	 *
	 * Example   : smtp://user:pass@localhost/inbox?smtpauth=false
	 * - scheme   ~ mailer interface
	 *
	 * @param  string $value
	 * @param  bool   $merge whether to merge config with configuration.php-dist
	 * @return JConfig or stdClass on success, array parsed from input string on error
	 * @throws E_USER_WARNING if scheme is missing
	 * @throws E_USER_NOTICE  if scheme is not supported
	 * @uses parseDSN(), loadFile(), JConfig
	 */
	function makeCfg($value, $merge = false, $asArray = false)
	{
		static $map = array(
			# database
			'mysql'  => array('scheme'=>'dbtype', 'host'=>'host', 'user'=>'user', 'pass'=>'password', 'path'=>'db'),
			'mysqli' => array('scheme'=>'dbtype', 'host'=>'host', 'user'=>'user', 'pass'=>'password', 'path'=>'db'),
			# SMTP server credentials
			'smtp'   => array('scheme'=>'mailer', 'host'=>'smtphost', 'user'=>'smtpuser', 'pass'=>'smtppass'),
			# FTP  server credentials
			'ftp'    => array('scheme'=>'ftp', 'host'=>'ftp_host','port'=>'ftp_port','user'=>'ftp_user', 'pass'=>'ftp_pass', 'path'=>'ftp_root'),
			# LDAP server connection ONLY, NOT to authenticate a user
			'ldap'   => array('scheme'=>'ldap', 'host'=>'host', 'port'=>'port', 'user'=>'username', 'pass'=>'password'),
		);

		static $cache = array();

		$hash = (int)$merge . (string)$value;
		if (isset($cache[$hash])) {
			return $asArray ? $cache[$hash]['a'] : $cache[$hash]['o'];
		}

		$config = new stdClass;
		if ($merge != false) {
			// this gives us some defaults
			if (UnitTestHelper::loadFile('configuration.php-dist', JPATH_BASE)) {
				$config = new JConfig;
			}
		}

		$parsed = UnitTestHelper::parseDSN($value);

		if (empty($parsed['scheme'])) {
			trigger_error(__FUNCTION__.'() missing scheme: "'. $value .'"', E_USER_WARNING);
			return $parsed;
		}
		if (!isset($map[$parsed['scheme']])) {
			trigger_error(__FUNCTION__.'() unknown mapping scheme: "'. $parsed['scheme'] .'"', E_USER_NOTICE);
			return $parsed;
		}

		$query = array();
		switch ($parsed['scheme'])
		{
		case 'mysql':
		case 'mysqli':
			$parsed['path'] = trim($parsed['path'], '/');
			parse_str((string)@$parsed['query'], $query);
			unset($parsed['query']);
			break;

		case 'ftp':
			unset($parsed['dbsyntax']);
			if (empty($parsed['port'])) $parsed['port'] = '21';

			if (empty($parsed['path'])) {
				# this is fine for WIN, too
				$parsed['root'] = '/';
			} else {
				$parsed['root'] = '/' . ltrim($parsed['path'], '\\/');
			}
			unset($parsed['path']);
			break;
		}

		foreach ($query as $key => $prop) {
			$config->$key = $prop;
		}

		foreach ($map[ $parsed['scheme'] ] as $key => $prop) {
			if (isset($parsed[$key])) {
				$config->$prop = $parsed[$key];
			}
		}
		$cache[$hash]['a'] = $parsed;
		$cache[$hash]['o'] = $config;

		return $asArray ? $cache[$hash]['a'] : $cache[$hash]['o'];

	}

	/**
	 * Loads a PHP file.  This is a wrapper for PHP's include() function.
	 *
	 * $filename must be the complete filename, including any extension such
	 * as ".php".  Note that a security check is performed that does not permit
	 * extended characters in the filename.  This method is intended for
	 * loading Mock Object for the UnitTest files.
	 *
	 * If $dirs is a string or an array, it will search the directories in the
	 * order supplied, and attempt to load the first matching file.
	 *
	 * If the file was not found in the $dirs, or if no $dirs were specified,
	 * it will attempt to load it from PHP's include_path.
	 *
	 * If $once is true, it will use include_once() instead of include().
	 *
	 * @param  string        $filename
	 * @param  string|array  $dirs - OPTIONAL either a path or array of paths
	 *                       to search.
	 * @param  boolean       $once
	 * @return boolean
	 * @throws E_USER_ERROR on illegal filename
	 * @throws E_USER_ERROR file was not found
	 * @uses isReadable(), _includeFile()
	 * @internal borrowed and adapted to PHP4 from Zend_Loader
	 */
	function loadFile($filename, $dirs = null, $once = false)
	{
		/**
		 * Security check
		 */
		if (preg_match('/[^a-z0-9\-_.]/i', $filename)) {
			trigger_error('Security check: Illegal character in filename', E_USER_ERROR);
		}

		/**
		 * Search for the file in each of the dirs named in $dirs.
		 */
		if (empty($dirs)) {
			$dirs = array();
		} elseif (is_string($dirs))  {
			$dirs = explode(PATH_SEPARATOR, $dirs);
		}
		foreach ($dirs as $dir) {
			$filespec = rtrim($dir, '\\/') . DIRECTORY_SEPARATOR . $filename;
			if (UnitTestHelper::isReadable($filespec)) {
				return UnitTestHelper::_includeFile($filespec, $once);
			}
		}

		/**
		 * The file was not found in the $dirs specified.
		 * Try finding for the plain filename in the include_path.
		 */
		if (UnitTestHelper::isReadable($filename)) {
			return UnitTestHelper::_includeFile($filename, $once);
		}

		/**
		 * The file was not located anywhere.
		 */
jutdump(debug_backtrace());
		trigger_error("File \"$filename\" was not found", E_USER_ERROR);
	}

	/**
	 * Returns true if the $filename is readable, or FALSE otherwise.
	 * This function uses the PHP include_path, where PHP's is_readable()
	 * does not.
	 *
	 * @param string   $filename
	 * @return boolean
	 * @internal borrowed from Zend_Loader
	 */
	function isReadable($filename)
	{
		if (@is_readable($filename)) {
			return true;
		}

		$path = get_include_path();
		$dirs = explode(PATH_SEPARATOR, $path);

		foreach ($dirs as $dir) {
			// No need to check against current dir -- already checked
			if ('.' == $dir) {
				continue;
			}

			if (@is_readable($dir . DIRECTORY_SEPARATOR . $filename)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Parse a data source name, inspired by and based on code in PEAR::DB
	 *
	 * Additional keys can be added by appending a URI query string to the
	 * end of the DSN.
	 *
	 * @param string $dsn Data Source Name to be parsed
	 *
	 * @return array an associative array with the following keys:
	 *  + scheme:   scheme name for use with makeCfg()
	 *  + dbsyntax: Database used with regards to SQL syntax etc.
	 *  + prot:     Communication protocol to use (tcp, unix etc.)
	 *  + host:     Host specification (hostname[:port])
	 *  + path:     Database to use on the DBMS server
	 *  + user:     User name for login
	 *  + pass:     Password for login
	 */
	function parseDSN($dsn)
	{
		$parsed = array(
			'scheme'   => false,
			'dbsyntax' => false,
			'user'     => false,
			'pass'     => false,
			'prot'     => false,
			'host'     => false,
			'port'     => false,
			'socket'   => false,
			'path'     => false,
		);

		if (is_array($dsn)) {
			$dsn = array_merge($parsed, $dsn);
			if (!$dsn['dbsyntax']) {
				$dsn['dbsyntax'] = $dsn['scheme'];
			}
			return $dsn;
		}

		// Find scheme and dbsyntax
		if (($pos = strpos($dsn, '://')) !== false) {
			$str = substr($dsn, 0, $pos);
			$dsn = substr($dsn, $pos + 3);
		} else {
			$str = $dsn;
			$dsn = null;
		}

		// Get scheme and dbsyntax
		// $str => scheme(dbsyntax)
		if (preg_match('|^(.+?)\((.*?)\)$|', $str, $arr)) {
			$parsed['scheme']  = $arr[1];
			$parsed['dbsyntax'] = !$arr[2] ? $arr[1] : $arr[2];
		} else {
			$parsed['scheme']  = $str;
			$parsed['dbsyntax'] = $str;
		}

		if (!count($dsn)) {
			return $parsed;
		}

		// Get (if found): user and pass
		// $dsn => user:pass@protocol+host/path
		if (($at = strrpos($dsn,'@')) !== false) {
			$str = substr($dsn, 0, $at);
			$dsn = substr($dsn, $at + 1);
			if (($pos = strpos($str, ':')) !== false) {
				$parsed['user'] = rawurldecode(substr($str, 0, $pos));
				$parsed['pass'] = rawurldecode(substr($str, $pos + 1));
			} else {
				$parsed['user'] = rawurldecode($str);
			}
		}

		// Find protocol and host

		if (preg_match('|^([^(]+)\((.*?)\)/?(.*?)$|', $dsn, $match)) {
			// $dsn => proto(proto_opts)/database
			$proto       = $match[1];
			$proto_opts  = $match[2] ? $match[2] : false;
			$dsn         = $match[3];

		} else {
			// $dsn => prot+host/database (old format)
			if (strpos($dsn, '+') !== false) {
				list($proto, $dsn) = explode('+', $dsn, 2);
			}
			if (strpos($dsn, '/') !== false) {
				list($proto_opts, $dsn) = explode('/', $dsn, 2);
			} else {
				$proto_opts = $dsn;
				$dsn = null;
			}
		}

		// process the different prot options
		$parsed['prot'] = (!empty($proto)) ? $proto : 'tcp';
		$proto_opts = rawurldecode($proto_opts);
		if (strpos($proto_opts, ':') !== false) {
			list($proto_opts, $parsed['port']) = explode(':', $proto_opts);
		}
		if ($parsed['prot'] == 'tcp') {
			$parsed['host'] = $proto_opts;
		} elseif ($parsed['prot'] == 'unix') {
			$parsed['socket'] = $proto_opts;
		}

		// Get target name (database) if any
		// $dsn => database
		if ($dsn) {
			if (($pos = strpos($dsn, '?')) === false) {
				// /path
				$parsed['path'] = rawurldecode($dsn);
			} else {
				// /path?param1=value1&param2=value2
				$parsed['path'] = rawurldecode(substr($dsn, 0, $pos));
				$dsn = substr($dsn, $pos + 1);
				if (strpos($dsn, '&') !== false) {
					$opts = explode('&', $dsn);
				} else { // path?param1=value1
					$opts = array($dsn);
				}
				foreach ($opts as $opt) {
					list($key, $value) = explode('=', $opt);
					if (!isset($parsed[$key])) {
						// don't allow params overwrite
						$parsed[$key] = rawurldecode($value);
					}
				}
			}
		}

		return $parsed;
	}

	/**
	 * @TODO: add optional file logging
	 * @see JUNIT_PHP_ERRORLOG
	 */
	function log($label, $testfile, $message = '')
	{
		$logfile = ini_get('error_log');
		return error_log($label . '(', $testfile, ') '. $message. PHP_EOL, 0);
	}

	/**#@+
	 * Shamelessly borrowed from PhpWiki stdlib.php, slightly
	 * modified to suite code-style
	 *
	 * stdlib.php,v 1.260 2007/02/17 14:15:21 rurban
	 * Licence: GPL
	 */

	/**
	 * Convert fileglob to regex style:
	 * Convert some wildcards to pcre style, escape the rest
	 * Escape . \\ + * ? [ ^ ] $ () { } = ! < > | : /
	 */
	function globToPcre($glob) {
		// check simple case: no need to escape
		$escape = '\[](){}=!<>|:/';
		if (strcspn($glob, $escape . ".+*?^$") == strlen($glob)) {
			return $glob;
		}
		// preg_replace cannot handle "\\\\\\2" so convert \\ to \xff
		$glob = strtr($glob, "\\", "\xff");
		$glob = str_replace("/", '\/', $glob);
		// first convert some unescaped expressions to pcre style: . => \.
		$special = ".^$";
		$re = preg_replace('/([^\xff])?(['.preg_quote($special).'])/',
						   "\\1\xff\\2", $glob);

		// * => .*, ? => .
		$re = preg_replace('/([^\xff])?\*/', '$1.*', $re);
		$re = preg_replace('/([^\xff])?\?/', '$1.', $re);
		if (!preg_match('/^[\?\*]/', $glob)) {
			$re = '^' . $re;
		}
		if (!preg_match('/[\?\*]$/', $glob)) {
			$re = $re . '$';
		}

		// .*? handled above, now escape the rest
		//while (strcspn($re, $escape) != strlen($re)) // loop strangely needed
		$re = preg_replace('/([^\xff])(['.preg_quote($escape, "/").'])/',
						   "\\1\xff\\2", $re);
		return strtr($re, "\xff", "\\");
	}

	function globMatch($glob, $against, $case_sensitive = true) {
		return preg_match('/' . UnitTestHelper::globToPcre($glob) . ($case_sensitive ? '/' : '/i'),
						  $against);
	}

	/**#@- */

	/**#@+
	 * @access private
	 * @ignore
	 */

	/**
	 * Attempt to include() the file.
	 *
	 * include() is not prefixed with the @ operator because if
	 * the file is loaded and contains a parse error, execution
	 * will halt silently and this is difficult to debug.
	 *
	 * @param  string  $filespec
	 * @param  boolean $once
	 * @return boolean
	 * @internal borrowed from Zend_Loader
	 */
	function _includeFile($filespec, $once = false)
	{
		if ($once) {
			return (bool)(@include_once $filespec);
		} else {
			return (bool)(@include $filespec);
		}
	}

	/**#@- */
}
