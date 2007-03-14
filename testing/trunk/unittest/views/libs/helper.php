<?php
/**
 * Joomla! v1.5 UnitTest Platform Helper class.
 *
 * @version	$Id$
 * @package 	Joomla
 * @subpackage 	UnitTest
 */

class UnitTestHelper
{
	function &getReporter($output = null, $testfile = null)
	{
		if ( null === $output ) {
			$output = JUNITTEST_CLI
					? (getenv('JUNITTEST_CLI') ? getenv('JUNITTEST_CLI') : 'text')
					: JUNITTEST_REPORTER;
		}
		if ( strstr(' custom html json php text xml ', strtolower( $output )) === false ) {
			$output = JUNITTEST_CLI
					? (getenv('JUNITTEST_CLI') ? getenv('JUNITTEST_CLI') : 'text')
					: JUNITTEST_REPORTER;
		}

	    switch( strtolower( $output ) )
	    {
        case 'xml':
        case 'php':
        case 'json':
        case 'text':
	        $renderer = 'Joomla'.ucwords($output);
            require_once JUNITTEST_VIEWS.'/'.$renderer.'.php';
            break;
        case 'html':
        	# fall thru to default case
	        $renderer = 'Joomla'.ucwords($output);
        default:
        	if ( $output == 'html' ) {
            	require_once JUNITTEST_VIEWS.'/'.$renderer.'.php';
			} else {
				require_once JUNITTEST_REPORTER_CUSTOM_PATH;
				$renderer = JUNITTEST_REPORTER_CUSTOM_CLASS;
			}

            break;
	    }

        $reporter =& new $renderer();
	    return $reporter;
	}

	function &getTestcaseHelper($testfile)
	{
    	$path   = implode(DIRECTORY_SEPARATOR, array(JUNITTEST_ROOT,JUNITTEST_BASE, dirname($testfile), '_files'));
		$class  = basename($testfile, 'Test.php');

    	$helper =& UnitTestHelper::getProperty('Helper', $class);
    	$helper = array(
					'path'    => $path,
					'basename'=> strtolower($class . '_helper.php'),
					'class'   => $class.'TestHelper',
					'script'  => $testfile
					);
		$helper['location'] = realpath($helper['path'] .DIRECTORY_SEPARATOR. $helper['basename']);
    	return $helper;
	}

	/**
	 * @param array $pathinfo pathinfo() array
	 * @todo: FIX
	 */
	function isTestCaseEnabled( $pathinfo )
	{
		return true; # Unreachable code message in Eclipse

		if (empty($pathinfo['dirname'])) return true;

		$constant = UnitTestHelper::getTestCaseConfigVar( $pathinfo );
		return defined($constant) && @constant($constant);
	}

	/**
	 * @param array $pathinfo pathinfo() array
	 */
	function getTestCaseConfigVar( $pathinfo )
	{
		if ( !is_array($pathinfo)) $pathinfo = pathinfo($pathinfo);

		$name = basename($pathinfo['basename'], 'Test.php');
		if ( $name == 'AllTests.php' ) {
			$name = basename($name, '.php');
		}

		$folder = substr($pathinfo['dirname'], strlen('joomla/'));
		if ( !empty($folder) ) {
			$folder = str_replace(DIRECTORY_SEPARATOR, '_', $folder) . '_';
		}

		$constant = JUNITTEST_PREFIX . strtoupper($folder . $name);
		return $constant;
	}

	/**
	 * poor man's registry
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
	 */
	function &getProperty($namespace, $prop, $forcetype = null)
	{
		static $properties = array();
		if ( !isset($properties[$namespace]) ) {
			$properties[$namespace] = array();
		}
		if ( !isset($properties[$namespace][$prop]) && $forcetype !== null ) {
			@settype($properties[$namespace][$prop], $forcetype);
		}
		return $properties[$namespace][$prop];
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
		if ( isset($cache[$hash]) ) {
			return $asArray ? $cache[$hash]['a'] : $cache[$hash]['o'];
		}

		$config = new stdClass;
		if ( $merge != false ) {
			// this gives us some defaults
			if ( (include_once(JPATH_BASE . '/configuration.php-dist')) ) {
				$config = new JConfig;
			}
		}

		$parsed = UnitTestHelper::_parseDSN($value);

		if ( empty( $parsed['scheme'] ) ) {
			trigger_error(__FUNCTION__.'() missing scheme: "'. $value .'"', E_USER_WARNING);
			return $parsed;
		}
		if ( !isset( $map[$parsed['scheme']] ) ) {
			trigger_error(__FUNCTION__.'() unknown mapping scheme: "'. $parsed['scheme'] .'"', E_USER_NOTICE);
			return $parsed;
		}

		$query = array();
		switch ($parsed['scheme'])
		{
		case 'mysql':
		case 'mysqli':
			$parsed['path'] = trim($parsed['path'], '/');
			parse_str( (string)@$parsed['query'], $query);
			unset($parsed['query']);
			break;

		case 'ftp':
			unset($parsed['dbsyntax']);
			if ( empty($parsed['port']) ) $parsed['port'] = '21';

			if ( empty($parsed['path']) ) {
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
			if ( isset($parsed[$key]) ) {
				$config->$prop = $parsed[$key];
			}
		}
		$cache[$hash]['a'] = $parsed;
		$cache[$hash]['o'] = $config;

		return $asArray ? $cache[$hash]['a'] : $cache[$hash]['o'];

	}

    /**
     * Loads a class from a PHP file.  The filename must be formatted
     * as "$class.php".
     *
     * If $dirs is a string or an array, it will search the directories
     * in the order supplied, and attempt to load the first matching file.
     *
     * If $dirs is null, it will split the class name at underscores to
     * generate a path hierarchy (e.g., "Zend_Example_Class" will map
     * to "Zend/Example/Class.php").
     *
     * If the file was not found in the $dirs, or if no $dirs were specified,
     * it will attempt to load it from PHP's include_path.
     *
     * @param string $class      - The full class name of a Zend component.
     * @param string|array $dirs - OPTIONAL either a path or array of paths to search
     * @throws E_USER_ERROR if $dirs is not a string or array of strings
     * @throws E_USER_ERROR if file was loaded but class name does not match
     * @return void
     * @author Zend Framework
     *
     * @todo implement to handle CamelCaseClassNames
     * @todo check if classname/filename contraint worx with J!F at all
     */
    function loadClass($class, $dirs = null)
    {
    	die('loadClass() not implemented');

        if (class_exists($class, false)) {
            return;
        }

        if ((null !== $dirs) && !is_string($dirs) && !is_array($dirs)) {
            trigger_error('Directory argument must be a string or an array', E_USER_ERROR);
        }
        if (null === $dirs) {
            $dirs = array();
        }
        if (is_string($dirs)) {
            $dirs = (array) $dirs;
        }

        // autodiscover the path from the class name
        $path = str_replace('_', DIRECTORY_SEPARATOR, $class);
        if ($path != $class) {
            // use the autodiscovered path
            $dirPath = dirname($path);
            if (0 == count($dirs)) {
                $dirs = array($dirPath);
            } else {
                foreach ($dirs as $key => $dir) {
                    $dir = rtrim($dir, '\\/');
                    $dirs[$key] = $dir . DIRECTORY_SEPARATOR . $dirPath;
                }
            }
            $file = basename($path) . '.php';
        } else {
            $file = $class . '.php';
        }

        UnitTestHelper::loadFile($file, $dirs, true);

        if (!class_exists($class, false)) {
            trigger_error("File \"$file\" was loaded "
                           . "but class \"$class\" was not found within.", E_USER_ERROR);
        }
    }

    /**
     * Loads a PHP file.  This is a wrapper for PHP's include() function.
     *
     * $filename must be the complete filename, including any
     * extension such as ".php".  Note that a security check is performed that
     * does not permit extended characters in the filename.  This method is
     * intended for loading Zend Framework files.
     *
     * If $dirs is a string or an array, it will search the directories
     * in the order supplied, and attempt to load the first matching file.
     *
     * If the file was not found in the $dirs, or if no $dirs were specified,
     * it will attempt to load it from PHP's include_path.
     *
     * If $once is TRUE, it will use include_once() instead of include().
     *
     * @param  string        $filename
     * @param  string|array  $dirs - OPTIONAL either a path or array of paths to search
     * @param  boolean       $once
     * @throws E_USER_ERROR if file was not found
     * @return mixed
     * @author Zend Framework
     */
    function loadFile($filename, $dirs = null, $once = false)
    {
        // security check
        if (preg_match('/[^a-z0-9\-_.]/i', $filename)) {
            trigger_error('Security check: Illegal character in filename.', E_USER_ERROR);
        }

        /**
         * Determine if the file is readable, either within just the include_path
         * or within the $dirs search list.
         */
        $filespec = $filename;
        if (empty($dirs)) {
            $dirs = null;
        }
        if ($dirs === null) {
            $found = UnitTestHelper::isReadable($filespec);
        } else {
            foreach ((array)$dirs as $dir) {
                $filespec = rtrim($dir, '\\/') . DIRECTORY_SEPARATOR . $filename;
                $found = UnitTestHelper::isReadable($filespec);
                if ($found) {
                    break;
                }
            }
        }

        /**
         * Throw an exception if the file could not be located
         */
        if (!$found) {
            trigger_error("File \"$filespec\" was not found.", E_USER_ERROR);
        }

        /**
         * Attempt to include() the file.
         *
         * include() is not prefixed with the @ operator because if
         * the file is loaded and contains a parse error, execution
         * will halt silently and this is difficult to debug.
         *
         * Always set display_errors = Off on production servers!
         */
        if ($once) {
            return include_once $filespec;
        } else {
            return include $filespec ;
        }
    }


    /**
     * Returns TRUE if the $filename is readable, or FALSE otherwise.  This
     * function uses the PHP include_path, where PHP's is_readable() does not.
     *
     * @param string $filename
     * @return boolean
     */
    function isReadable($filename)
    {
        if (is_readable($filename)) {
            return true;
        }

        $path = get_include_path();
        $dirs = explode(PATH_SEPARATOR, $path);

        foreach ($dirs as $dir) {
            // No need to check against current dir -- already checked
            if ('.' == $dir) {
                continue;
            }

            if (is_readable($dir . DIRECTORY_SEPARATOR . $filename)) {
                return $dir . DIRECTORY_SEPARATOR . $filename;
            }
        }

        return false;
    }

	/**
	 * Parse a data source name, interntionally inspired by PEAR::DB
	 *
	 * Additional keys can be added by appending a URI query string to the
	 * end of the DSN.
	 *
	 * The format of the supplied DSN is in its fullest form:
	 * <code>
	 *  scheme(dbsyntax)://user:pass@protocol+host:port/target?option=8&another=true
	 * </code>
	 *
	 * Most variations are allowed:
	 * <code>
	 *  scheme://user:pass@prot+host:110//usr/db_file.db?mode=0644
	 *  scheme://user:pass@host/database_name
	 *  scheme://user:pass@host
	 *  scheme://user@host
	 *  scheme://host/database
	 *  scheme://host
	 *  scheme(dbsyntax)
	 *  scheme
	 * </code>
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
	function _parseDSN($dsn)
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

	/** @TODO: add optional file logging */
	function log($label, $testfile, $message = '')
	{
		$logfile = ini_get('error_log');
		return error_log('(', $testfile, ') '. $message. PHP_EOL, 0);
	}

}
