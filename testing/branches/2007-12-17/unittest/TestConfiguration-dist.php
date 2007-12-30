<?php
/**
 * Joomla! v1.5 UnitTest Platform Configuration.
 *
 * Edit TestConfiguration.php, not TestConfiguration-dist.php. Never commit
 * plaintext passwords to the source code repository!
 *
 * @package Joomla
 * @subpackage UnitTest
 * @version $Id$
 */

/**
 * Fullly qualified path of your Joomla! installation. Defaults to the parent
 * folder of this file. DO NOT INCLUDE A TRAILING SLASH.
 */
define('JPATH_BASE', dirname(dirname(__FILE__)));

/**
 * Fullly qualified path of your UnitTest installation. DO NOT INCLUDE A
 * TRAILING SLASH.
 */
define('JUNIT_ROOT', dirname(__FILE__));

class JUnit_Config {
	/**
	 * Location of test configuration. Must be writiable. Relative to the root
	 * unit test directory, or start with a slash for an absolute path.
	 *
	 * @var string
	 */
	public $configDir = 'config';

	/**
	 * Location of libraries. Relative to the root unit test directory, or start
	 * with a slash for an absolute path.
	 *
	 * @var string
	 */
	public $libDir = 'libraries';

	/**
	 * Location of test logs. Must be writiable. Relative to the root unit test
	 * directory, or start with a slash for an absolute path.
	 *
	 * @var string
	 */
	public $logDir = '';

    /**
     * Location of PEAR libraries, including PHPUnit. Relative to the root unit
     * test directory, or start with a slash for an absolute path.
     *
     * @var string
     */
    public $pearDir = 'libraries/pear';

	/**
	 * Location of the unit test files. Relative to the root unit test
	 * directory, or start with a slash for an absolute path.
	 *
	 * @var string
	 */
	public $testDir = 'tests';

	/**
	 * Default output format for command line. Standard Joomla reporters are:
	 * html, xml, php, json, text.
	 */
	public $viewCli = 'text';

	/**
	 * Location of views. Relative to the root unit test directory, or start
	 * with a slash for an absolute path.
	 *
	 * @var string
	 */
	public $viewDir = 'views';

	/**
	 * Default output format for HTTP requests. Standard Joomla reporters are:
	 * html, xml, php, json, text.
	 */
	public $viewHttp = 'html';

}
