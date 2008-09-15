<?php
/**
 * Joomla! v1.5 UnitTest Platform Configuration.
 *
 * @version		$Id$
 * @package		Joomla.UnitTest
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU General Public License
 *
 * Edit TestConfiguration.php, not TestConfiguration-dist.php. Never commit
 * plaintext passwords to the source code repository!
 *
 */

/*
 * Fullly qualified path of your Joomla! installation. Defaults to the parent
 * folder of this file. DO NOT INCLUDE A TRAILING SLASH.
 */
if (!defined( 'JPATH_BASE' )) {
	define('JPATH_BASE', dirname(dirname(__FILE__)));
}

/*
 * Fullly qualified path of your UnitTest installation. DO NOT INCLUDE A
 * TRAILING SLASH.
 */
if (!defined( 'JUNIT_ROOT' )) {
	define('JUNIT_ROOT', dirname(__FILE__));
}

class JUnit_Config
{
	/**
	 * Location of test configuration. Must be writiable. Relative to the root
	 * unit test directory, or start with a slash for an absolute path.
	 *
	 * @var string
	 */
	static static public $configDir = 'config';

	/**
	 * Location of libraries. Relative to the root unit test directory, or start
	 * with a slash for an absolute path.
	 *
	 * @var string
	 */
	static public $libDir = 'libraries';

	/**
	 * Location of test logs. Must be writiable. Relative to the root unit test
	 * directory, or start with a slash for an absolute path.
	 *
	 * @var string
	 */
	static public $logDir = '';

    /**
     * Location of PEAR libraries, including PHPUnit. Relative to the root unit
     * test directory, or start with a slash for an absolute path.
     *
     * @var string
     */
    static public $pearDir = 'libraries/pear';

	/**
	 * Location of the unit test files. Relative to the root unit test
	 * directory, or start with a slash for an absolute path.
	 *
	 * @var string
	 */
	static public $testDir = 'tests';

    /**
     * Version override. If set, this version is used as the Joomla version.
     *
     * @var string
     */
    static public $versionOverride = '';

	/**
	 * Default output format for command line. Standard Joomla reporters are:
	 * html, xml, php, json, text.
	 */
	static public $viewCli = 'text';

	/**
	 * Location of views. Relative to the root unit test directory, or start
	 * with a slash for an absolute path.
	 *
	 * @var string
	 */
	static public $viewDir = 'views';

	/**
	 * Default output format for HTTP requests. Standard Joomla reporters are:
	 * html, xml, php, json, text.
	 */
	static public $viewHttp = 'html';
}
