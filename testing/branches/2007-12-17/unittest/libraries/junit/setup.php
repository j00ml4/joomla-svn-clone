<?php
/**
 * Joomla! v1.5 Unit Test Facility
 *
 * @package Joomla
 * @subpackage UnitTest
 * @version $Id: helper.php 9752 2007-12-27 05:02:19Z instance $
 * @copyright Copyright (C) 2007 Rene Serradeil. All rights reserved.
 * @license GNU/GPL
 */

/**
 * constants for $is_test property of the info object
 * @see JUnit_Setup::getInfoObject()
 */
define('JUNIT_IS_TESTSUITE', 1);
define('JUNIT_IS_TESTCASE',  2);
define('JUNIT_IS_FRAMEWORK', 4);

/**
 * Unit Test Setup
 *
 * The setup class provides a registry for information that would otherwise
 * clutter the global name space. It also wraps various setup related functions.
 */
class JUnit_Setup
{
	/**
	 * Regex to determine which class files to process. If empty, no filtering
	 * is done.
	 *
	 * @var string
	 */
	protected $_classFilter = '';

	/**
	 * Option definitions are (default_value, value_required, help)
	 *
	 * @var array
	 */
	protected static $_optionDefs = array(
		'class-filter' => array('', true, 'Regular expression to select tests by class name.'),
		'debug' => array(false, false, 'Dump unit test diagnostics.'),
		'help' => array(false, false, 'Dump this help message.'),
		'sequence-filter' => array('', true, 'Regular expression to select tests by sequence ID.'),
		'test-filter' => array('', true, 'Regular expression to select tests by test name.'),
	);

	/**
	 * Option settings.
	 *
	 * @var array
	 */
	protected $_options;

	/**
	 * Repository for unit test operational data.
	 *
	 * @var array
	 */
	static protected $_properties = array();

	/**
	 * Regex to determine which test sequences to process. If empty, no
	 * filtering is done.
	 *
	 * @var string
	 */
	protected  $_sequenceFilter = '';

	/**
	 * Base directory for considering tests
	 *
	 * @var string
	 */
	protected $_startDir;

	/**
	 * Regex to determine which test names to process. If empty, no filtering is
	 * done.
	 *
	 * @var string
	 */
	protected $_testFilter = '';

	/**
	 * Line terminator.
	 *
	 * @var string
	 */
	static $eol = PHP_EOL;

	function __construct() {
		$this -> _options = array();
		foreach (self::$_optionDefs as $opt => $info) {
			$this -> _options[$opt] = $info[0];
		}
	}

	protected function _dirWalk(&$fileList, $dir) {
		if (strlen($dir) && ($dir[strlen($dir) - 1] != '/')) {
			$dir .= '/';
		}
		$path = $this -> _startDir . $dir;
		if (! @is_dir($path)) {
			throw new Exception('Scan error. ' . $path . ' is not a directory.', 1);
		}
		if(! ($dh = @opendir($path))) {
			throw new Exception('Scan error. Unable to open ' . $path, 2);
		}
		if ($this -> _options['debug']) {
			echo 'Processing directory "' . $dir . '"' . self::$eol;
		}
		/*
		 * Walk through the directory collecting files and subdirectories Then
		 * we sort them to get the correct sequence.
		 */
		$list = array();
		$subList = array();
		while (($fileName = readdir($dh)) !== false) {
			$fid = $path . $fileName;
			switch (filetype($fid)) {
				case 'dir': {
					if ($fileName != '.' && $fileName != '..') {
						$subList[] = $fileName;
					}
				} break;

				case 'file': {
					//echo $fileName . PHP_EOL;
					if (substr($fileName, -9) == '-test.php') {
						/*
						 * If there is an object match, add this file to the
						 * list of tests.
						 */
						if ($this -> _isFiltered($fileName)) {
							if ($this -> _options['debug']) {
								echo 'Filtered: ' . $fileName . self::$eol;
							}
							continue;
						}
						$list[] = $fileName;
					}
				} break;
			}
		}
		sort($list);

		foreach ($list as $fileName) {
			$fileList[] = $path . $fileName;
		}
		sort($subList);
		foreach ($subList as $subDir) {
			$this -> _dirWalk($fileList, $dir . $subDir);
		}
	}

	protected function _doHelp() {
		echo 'Joomla Unit Test Runner options:' . self::$eol . self::$eol;
		foreach (self::$_optionDefs as $opt => $info) {
			echo $opt . ' ' . $info[2] . self::$eol;
		}
		echo self::$eol . self::$eol;
	}

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
	protected function _includeFile($filespec, $once = false)
	{
		if ($once) {
			return (bool)(@include_once $filespec);
		} else {
			return (bool)(@include $filespec);
		}
	}

	/**
	 * Determine if a file is excluded by a filter.
	 *
	 * This function applies any filters set for class name, sequence, and test
	 * name.
	 *
	 * @param string The candidate test file name.
	 * @return boolean True if the file is excluded by filters.
	 */
	protected function _isFiltered($fileName) {
		$parts = explode('-', $fileName);
		if ($this -> _classFilter) {
			if (! preg_match($this -> _classFilter, $parts[0])) {
				return true;
			}
		}
		if ($this -> _sequenceFilter) {
			if (! preg_match($this -> _sequenceFilter, $parts[1])) {
				return true;
			}
		}
		if ($this -> _testFilter) {
			if (! preg_match($this -> _testFilter, $parts[2])) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Return an option definitons array that is compatible with PHPUnit's
	 * getopt() function.
	 *
	 * @return array Long options, appended with = if a value is expected.
	 */
	static function getCliOptionDefs() {
		$defs = array();
		foreach (self::$_optionDefs as $opt => $info) {
			$defs[] = $opt . ($info[1] ? '=' : '');
		}
		return $defs;
	}

	/**
	 * Parses $path and returns a standard object with all kind of stuff in it.
	 * The value of $path is the "path" input argument provided via $_REQUEST
	 * (browser mode) or the command-line (CLI Mode).
	 *
	 * See JUnit_Setup_examples.txt for more information about this array and
	 * it's drawbacks.
	 *
	 * @param  string $path relative of a framework or testcase file/dir
	 * @return array
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

		if ($out->enabled == false) {
			return $out;
		}

		$testcase =& JUnit_Setup::getProperty('Reporter', 'UnitTests', 'array');
		$testcase[strtolower($out->testclass)] =& $out;

		// JUNIT_CLI

		return $out;
	}

	/**
	 * Return a copy of the option definitions
	 *
	 * @return array Option definitions.
	 */
	static function getOptionDefs() {
		return self::$_optionDefs;
	}

	/**
	 * Get a property from the setup regisrty
	 *
	 * @param string Used to prevent clashes, usually a class name.
	 * @param string Name of the property.
	 * @param string PHP type name, i.e. 'array'.
	 * @see unsetProperty()
	 */
	static function getProperty($namespace, $prop, $forcetype = null)
	{
		if ($namespace == '') {
			$namespace = __CLASS__;
		}
		if (! isset(self::$_properties[$namespace])) {
			return null;
		}
		if (! isset(self::$_properties[$namespace][$prop])) {
			return null;
		}
		$result = self::$_properties[$namespace][$prop];
		if (! is_null($forcetype)) {
			@settype($result, $forcetype);
		}
		return $result;
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
	 * Loads a PHP file.  This is a wrapper for PHP's include() function.
	 *
	 * $filename must be the complete filename, including any extension such
	 * as '.php'.  Note that a security check is performed that does not permit
	 * extended characters in the filename.  This method is intended for
	 * loading Mock Object for the UnitTest files.
	 *
	 * If $dirs is a string or an array, it will search the directories in the
	 * order supplied, and attempt to load the first matching file.
	 *
	 * If the file was not found in the $dirs, or if no $dirs were specified,
	 * it will attempt to load it from PHP's include_path.
	 *
	 * If $once is true, it will use include_once instead of include.
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
			if (JUnit_Setup::isReadable($filespec)) {
				return JUnit_Setup::_includeFile($filespec, $once);
			}
		}

		/**
		 * The file was not found in the $dirs specified.
		 * Try finding for the plain filename in the include_path.
		 */
		if (JUnit_Setup::isReadable($filename)) {
			return JUnit_Setup::_includeFile($filename, $once);
		}

		/**
		 * The file was not located anywhere.
		 */
jutdump(debug_backtrace());
		trigger_error('File "' . $filename . '" was not found', E_USER_ERROR);
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
			if (JUnit_Setup::loadFile('configuration.php-dist', JPATH_BASE)) {
				$config = new JConfig;
			}
		}

		$parsed = JUnit_Setup::parseDSN($value);

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

	/*
	 * Assemble a list of tests and run them.
	 */
	function run() {
		if ($this -> _options['help']) {
			$this -> _doHelp();
			return;
		}
		/*
		 * Find all the matching files
		 */
		if ($this -> _startDir) {
			$dir = $this -> _startDir;
		} else {
			$dir = '';
		}
		if ($this -> _options['debug']) {
			echo 'Discovering files in ' . $this -> _startDir . self::$eol;
		}
		$testFiles = array();
		$this -> _dirWalk($testFiles, '');
		if ($this -> _options['debug']) {
			echo 'Running ' . count($testFiles) . ' test files:' . self::$eol;
			foreach ($testFiles as $fid) {
				echo $fid . self::$eol;
			}
		}
		$suite  = new PHPUnit_Framework_TestSuite();
		$suite -> addTestFiles($testFiles);
		// This will change when we have more than CLI support
		$result = PHPUnit_TextUI_TestRunner::run($suite);
	}

	/**
	 * Set a property in the setup regisrty.
	 *
	 * @param string Used to prevent clashes, usually a class name.
	 * @param string Name of the virtual property.
	 * @param mixed The value to save.
	 */
	static function setProperty($namespace, $prop, $value)
	{
		if ($namespace == '') {
			$namespace = __CLASS__;
		}
		if (is_null($prop)) {
			unset(self::$_properties[$namespace]);
		} elseif (! isset(self::$_properties[$namespace])) {
			self::$_properties[$namespace] = array();
		}
		self::$_properties[$namespace][$prop] = $value;
	}

	function setOption($opt, $value) {
		if (isset($this -> _options[$opt])) {
			$info = self::$_optionDefs[$opt];
			if ($info[1]) {
				$this -> _options[$opt] = $value;
			} else {
				$this -> _options[$opt] = true;
			}
			switch ($opt) {
				case 'class-filter': {
					$this -> _classFilter = $value;
				}
				break;

				case 'sequence-filter': {
					$this -> _sequenceFilter = $value;
				}
				break;

				case 'test-filter': {
					$this -> _testFilter = $value;
				}
				break;

			}
			return true;
		}
		return false;
	}

	function setStartDir($dir) {
		if (strlen($dir) && ($dir[strlen($dir) - 1] != '/')) {
			$dir .= '/';
		}
		$this -> _startDir = $dir;
	}

	/**
	 * Allows a Reporter class to query testcase configuration.
	 *
	 * @param  SimpleReporter $reporter
	 * @return bool true if a testcase is enabled
	 */
	function shouldInvoke(&$reporter, $class_name)
	{
		$UnitTests = &JUnit_Setup::getProperty('Reporter', 'UnitTests');
		$class_name = strtolower($class_name);
		if (isset($UnitTests[$class_name])) {
			$invoke = (bool)$UnitTests[$class_name]->enabled;
		} else {
			$invoke = false;
		}

		return $invoke;
	}

	static function unsetProperty($namespace, $prop)
	{
		if ($namespace == '') {
			$namespace = __CLASS__;
		}
		unset(self::$_properties[$namespace][$prop]);
	}

}
