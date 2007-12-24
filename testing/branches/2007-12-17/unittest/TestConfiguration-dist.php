<?php
/**
 * Joomla! v1.5 UnitTest Platform Configuration.
 *
 * This file contains configuration directives to run the unit tests of the
 * Joomla! 1.5 Framework.  Some tests have dependencies to PHP extensions
 * or databases which may not necessary be installed/available on your system.
 * For these cases, the ability to disable or configure testing is provided
 * below.
 *
 *       Edit TestConfiguration.php, not TestConfiguration-dist.php.
 *       Never commit plaintext passwords to the source code repository!
 *
 * @package Joomla
 * @subpackage UnitTest
 * @version $Id$
 */

/**
 * Full qualified path of your Joomla! 1.5 installation.
 * NO TRAILING SLASH.
 * Defaults to the parent folder of this file.
 */
define('JPATH_BASE', dirname(dirname(__FILE__)));

/**
 * General configuration.
 * Nomenclature:
 * - JUNIT_  constant applies to UnitTest Platform && local machine
 * - JUT_file    constant applies to TestCase in "file"
 */

/**
 * Full qualified paths of your UnitTest installation.
 * NO TRAILING SLASH.
 */
define('JUNIT_ROOT', dirname(__FILE__));
define('JUNIT_BASE', 'tests');

/**
 * Templates and renderer
 */
define('JUNIT_VIEWS', JUNIT_ROOT.'/views');

/**
 * UnitTest Framework libraries and tests
 */
define('JUNIT_LIBS',   JUNIT_ROOT.'/_files/libs');

/**
 * This file should be writable by the web server's user.
 */
define('JUNIT_PHP_ERRORLOG', JUNIT_ROOT . '/php'.PHP_VERSION.'_errors.log');

/**
 * Enable the skeletal builder from CLI. PHP 5 only.
 */
define('JUNIT_SKELETAL_BUILDER', JUNIT_CLI && ((int)PHP_VERSION >= 5));

/**
 * Whether to add stub code to generated Skeleton tests
 */
define('JUNIT_ADD_STUBS', true);

/**
 * Used (soon) by the non-text reporters to render additional
 * links to toggle between PHP4 and PHP5 enabled hosts.
 */
define('JUNIT_HOME_PHP4', 'http://php4.example.com/unittest/');
define('JUNIT_HOME_PHP5', 'http://php5.example.com/unittest/');

/**
 * Default $output format for index.php unless provided by URL or argument.
 * Standard Joomla reporters are: html, xml, php, json, text.
 *
 * Use 'custom' for another renderer/output format.
 * @see JUNIT_REPORTER_CLI
 */
define('JUNIT_REPORTER', 'html');

/**
 * Default $output format for index.php in CLI mode.
 * Standard Joomla reporters are: text, html, xml, php, json.
 *
 * Use 'custom' for another renderer/output format.
 * @see JUNIT_REPORTER
 */
define('JUNIT_REPORTER_CLI', 'text');

/**
 * Full qualified path, classname, and output format of the 'custom' reporter.
 * Only used if JUNIT_REPORTER == 'custom'
 */
define('JUNIT_REPORTER_CUSTOM_PATH',  JUNIT_VIEWS.'/WebMechanicRenderer.php');
define('JUNIT_REPORTER_CUSTOM_CLASS', 'WebMechanicRenderer');
define('JUNIT_REPORTER_CUSTOM_FORMAT', 'html');

/**
 * Whether passed tests should be rendered, not available with every reporter.
 * This will generate a lot of "noise", hence the default is false.
 */
define('JUNIT_REPORTER_RENDER_PASSED', false);

define('JUNIT_LISTMODE_HEADER', true);
define('JUNIT_LISTMODE_FOOTER', true);
define('JUNIT_LISTMODE_STATS', true);

