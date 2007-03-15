<?php
/**
 * Joomla! v1.5 UnitTest Platform Configuration.
 *
 * This file defines configuration for running the unit tests for the
 * Joomla! 1.5 Framework.  Some tests have dependencies to PHP extensions
 * or databases which may not necessary be installed on the target system.
 * For these cases, the ability to disable or configure testing is provided
 * below.
 *
 *       Edit TestConfiguration.php, not TestConfiguration-dist.php.
 *       Never commit plaintext passwords to the source code repository!
 *
 * @version	$Id$
 * @package 	Joomla
 * @subpackage 	UnitTest
 *
 * Last review: 2007-03-01 CirTap
 */

/**
 * Full qualified path of your Joomla! 1.5 installation.
 * NO TRAILING SLASH.
 * Defaults to the parent folder of this file.
 */
define('JPATH_BASE', dirname( dirname(__FILE__) ));

/**
 * General configuration.
 * Nomenclature:
 * - JUNITTEST_  constant applies to UnitTest Platform && local machine
 * - JUT_file    constant applies to TestCase in "file"
 */

/**
 * Full qualified paths of your UnitTest installation.
 * NO TRAILING SLASH.
 */
define('JUNITTEST_ROOT',  dirname( __FILE__ ));
define('JUNITTEST_BASE',  'tests');

define('JUNITTEST_VIEWS',  JUNITTEST_ROOT.'/views');
# TODO: find a better location ...
define('JUNITTEST_LIBS',   JUNITTEST_ROOT.'/views/libs');

/**
 * This file should be writable by the web server's user.
 */
define('JUNITTEST_PHP_ERRORLOG', JUNITTEST_ROOT . '/php'.PHP_VERSION.'_errors.log');

/**
 * Whether to add stub code to generated Skeleton tests
 */
define('JUNITTEST_ADD_STUBS', true);

/**
 * Used (soon) by the non-text reporters to render additional
 * links to toggle between PHP4 and PHP5 enabled hosts.
 */
define('JUNITTEST_HOME_PHP4', 'http://php4.example.com/unittest/');
define('JUNITTEST_HOME_PHP5', 'http://php5.example.com/unittest/');

/**
 * Default $output format for index.php unless provided by URL or argument.
 * Standard Joomla reporters are: html, xml, php, json, text
 * Use 'custom' for another renderer/output format.
 */
define('JUNITTEST_REPORTER', 'html');

/**
 * Full qualified path and classname of the 'custom' reported.
 * Only used if JUNITTEST_REPORTER == 'custom'
 */
define('JUNITTEST_REPORTER_CUSTOM_PATH',  JUNITTEST_VIEWS.'/WebMechanicRenderer.php');
define('JUNITTEST_REPORTER_CUSTOM_CLASS', 'WebMechanicRenderer');

/**
 * Whether passed tests should be rendered, not available with every reporter.
 */
define('JUNITTEST_REPORTER_RENDER_PASSED', true);

/**
 * Database credentials for MySQL 3/4/5 servers.
 * These MUST point to the standard installation of Joomla! 1.5 incl. all
 * example data. Tests that require a special or "quirky" database
 * environment SHOULD provide their own connection string.
 * - driver://username:password@hostspec/database_name
 *
 * Edit TestConfiguration.php, not TestConfiguration-dist.php.
 * Never commit plaintext passwords to the source code repository!
 */
define('JUNITTEST_DATABASE_MYSQL3', 'mysql://username:password@localhost:3303/database_name');
define('JUNITTEST_DATABASE_MYSQL4', 'mysql://username:password@localhost/database_name');
define('JUNITTEST_DATABASE_MYSQL5', 'mysql://username:password@localhost:3307/database_name');

/**@#+
 * Joomla Unit Test (JUT) configuration.
 */

/**
 * /libraries/joomla
 */
define('JUT_JVERSION', true);
define('JUT_JCONFIG', false);
define('JUT_JLOADER', true);

/*
 * Edit TestConfiguration.php, not TestConfiguration-dist.php.
 * Never commit plaintext passwords to the source code repository!
 */
define('JUT_JFACTORY', true);
define('JUT_FACTORY_DBO',          JUNITTEST_DATABASE_MYSQL4);
define('JUT_FACTORY_SMTP_NOAUTH', 'smtp://someone@example.com:secret@localhost/?smtpauth=0');
define('JUT_FACTORY_SMTP_AUTH',   'smtp://someone@example.com:secret@localhost/?smtpauth=1');

/**
 * /libraries/joomla/application
 */
define('JUT_APPLICATION_JAPPLICATION', false);
define('JUT_APPLICATION_JAPPLICATIONHELPER', false);
define('JUT_APPLICATION_JEVENTDISPATCHER', false);
define('JUT_APPLICATION_JMENU', false);
define('JUT_APPLICATION_JPATHWAY', false);
define('JUT_APPLICATION_JROUTER', false);
define('JUT_APPLICATION_JROUTE', false);
define('JUT_APPLICATION_JSEARCH', false);
define('JUT_APPLICATION_COMPONENT_JCONTROLLER', false);
define('JUT_APPLICATION_COMPONENT_JMODEL', false);
define('JUT_APPLICATION_COMPONENT_JVIEW', false);
define('JUT_APPLICATION_COMPONENT_JCOMPONENTHELPER', false);
define('JUT_APPLICATION_MODULE_JMODULEHELPER', false);
define('JUT_APPLICATION_PLUGIN_JPLUGINHELPER', false);

/**
 * /libraries/joomla/base
 */
define('JUT_BASE_JOBJECT', true);
define('JUT_BASE_JOBSERVER', false);
define('JUT_BASE_JTREE', false);

/**
 * /libraries/joomla/cache
 */
define('JUT_CACHE_JCACHE', false);
define('JUT_CACHE_HANDLERS_JCACHECALLBACK', false);
define('JUT_CACHE_HANDLERS_JCACHEOUTPUT', false);
define('JUT_CACHE_HANDLERS_JCACHEPAGE', false);
define('JUT_CACHE_HANDLERS_JCACHEVIEW', false);
define('JUT_CACHE_STORAGE_JCACHESTORAGE', false);
define('JUT_CACHE_STORAGE_JCACHESTORAGEAPC', @extension_loaded('apc'));
define('JUT_CACHE_STORAGE_JCACHESTORAGEEACCELERATOR', @extension_loaded('eAccelerator'));
define('JUT_CACHE_STORAGE_JCACHESTORAGEFILE', false);

/**
 * /libraries/joomla/client
 *
 * Edit TestConfiguration.php, not TestConfiguration-dist.php.
 * Never commit plaintext passwords to the source code repository!
 */
define('JUT_CLIENT_JFTP', false);
define('JUT_FTP_NATIVE', false);
/* if your ftp server runs on a windows box, use ?ftp_sys=WIN */
define('JUT_FTP_CREDENTIALS', 'ftp://username:password@localhost/?ftp_sys=');
define('JUT_FTP_CREDENTIALS_ROOT', '/htdocs/joomla');

define('JUT_CLIENT_JLDAP', false);
define('JUT_CLIENT_JCLIENTHELPER', false);

/**
 * /libraries/joomla/database
 *
 * Edit TestConfiguration.php, not TestConfiguration-dist.php.
 * Never commit plaintext passwords to the source code repository!
 */
define('JUT_DATABASE_JDATABASE', false);
define('JUT_DATABASE_JDATABASEMYSQL',  JUT_DATABASE_JDATABASE & @extension_loaded('mysql'));
define('JUT_DATABASE_JDATABASEMYSQLI', JUT_DATABASE_JDATABASE & @extension_loaded('mysqli'));
define('JUT_DATABASE_JRECORDSET', false);

define('JUT_DATABASE_USE_MYSQL3', false);		// to run agains MySQL 3
define('JUT_DATABASE_USE_MYSQL40', true);		// to run agains MySQL 4.0x
define('JUT_DATABASE_USE_MYSQL41', false);		// to run agains MySQL 4.1x
define('JUT_DATABASE_USE_MYSQL50', false);		// to run agains MySQL 5.0x
define('JUT_DATABASE_USE_MYSQL51', false);		// to run agains MySQL 5.1x

/**
 * /libraries/joomla/database/table
 */
define('JUT_DATABASE_JTABLE', false);
define('JUT_DATABASE_JTABLECATEGORY', false);
define('JUT_DATABASE_JTABLECOMPONENT', false);
define('JUT_DATABASE_JTABLECONTENT', false);
define('JUT_DATABASE_JTABLEMENU', false);
define('JUT_DATABASE_JTABLEMENUTYPES', false);
define('JUT_DATABASE_JTABLEMODULE', false);
define('JUT_DATABASE_JTABLEPLUGIN', false);
define('JUT_DATABASE_JTABLESECTION', false);
define('JUT_DATABASE_JTABLESESSION', false);
define('JUT_DATABASE_JTABLEUSER', false);

/**
 * /libraries/joomla/document
 */
define('JUT_DOCUMENT_JDOCUMENT', false);
define('JUT_DOCUMENT_JDOCUMENTRENDERER', false);

/**
 * /libraries/joomla/document/error
 */
define('JUT_DOCUMENT_ERROR_JDOCUMENTERROR', false);

/**
 * /libraries/joomla/document/raw
 */
define('JUT_DOCUMENT_RAW_JDOCUMENTRAW', false);

/**
 * /libraries/joomla/document/feed
 */
define('JUT_DOCUMENT_FEED_JDOCUMENTFEED', false);
define('JUT_DOCUMENT_FEED_JFEEDITEM', false);
define('JUT_DOCUMENT_FEED_JFEEDENCLOSURE', false);
define('JUT_DOCUMENT_FEED_JFEEDIMAGE', false);
define('JUT_DOCUMENT_FEED_JDOCUMENTRENDERER_ATOM', false);  # '_' in class name ?
define('JUT_DOCUMENT_FEED_JDOCUMENTRENDERER_RSS', false);  # '_' in class name ?

/**
 * /libraries/joomla/document/html
 */
define('JUT_DOCUMENT_HTML_JDOCUMENTHTML', false);
define('JUT_DOCUMENT_HTML_JDOCUMENTRENDERER_COMPONENT', false);  # '_' in class name ?
define('JUT_DOCUMENT_HTML_JDOCUMENTRENDERER_HEAD', false);  # '_' in class name ?
define('JUT_DOCUMENT_HTML_JDOCUMENTRENDERER_MESSAGE', false);  # '_' in class name ?
define('JUT_DOCUMENT_HTML_JDOCUMENTRENDERER_MODULE', false);  # '_' in class name ?
define('JUT_DOCUMENT_HTML_JDOCUMENTRENDERER_MODULES', false);  # '_' in class name ?

/**
 * /libraries/joomla/document/pdf
 */
define('JUT_DOCUMENT_PDF_JDOCUMENTPDF', false);

/**
 * /libraries/joomla/environment
 */
define('JUT_ENVIRONMENT_JURI', false);
define('JUT_ENVIRONMENT_JBROWSER', false);
define('JUT_ENVIRONMENT_JREQUEST', false);
define('JUT_ENVIRONMENT_JRESPONSE', false);

/**
 * /libraries/joomla/environment/sessionstorage
 */
define('JUT_ENVIRONMENT_JSESSION', false);
define('JUT_ENVIRONMENT_JSESSIONSTORAGE', false);
define('JUT_ENVIRONMENT_JSESSIONSTORAGENONE', false);
define('JUT_ENVIRONMENT_JSESSIONSTORAGEDATABASE', false);

/** requires APC extension */
define('JUT_ENVIRONMENT_JSESSIONSTORAGEAPC', @extension_loaded('apc'));

/** requires eAccellerator */
define('JUT_ENVIRONMENT_JSESSIONSTORAGEEACCELERATOR', false);

/**
 * /libraries/joomla/filesystem
 */
define('JUT_FILESYSTEM_JFILE', false);
define('JUT_FILESYSTEM_JFOLDER', false);
define('JUT_FILESYSTEM_JPATH', false);

/**
 * /libraries/joomla/filesystem/archive
 */
define('JUT_FILESYSTEM_JARCHIVE', false);
define('JUT_FILESYSTEM_JARCHIVEBZIP2', false);
define('JUT_FILESYSTEM_JARCHIVEGZIP', false);
define('JUT_FILESYSTEM_JARCHIVETAR', false);
define('JUT_FILESYSTEM_JARCHIVEZIP', false);

/**
 * /libraries/joomla/filter
 */
define('JUT_FILTER_JINPUTFILTER',  false); // "wrong" class name, should be JFilterInput
define('JUT_FILTER_JOUTPUTFILTER', false); // "wrong" class name, should be JFilterOutput

/**
 * /libraries/joomla/html
 */
define('JUT_HTML_JEDITOR', false);
define('JUT_HTML_JHTML', false);
define('JUT_HTML_JPAGINATION', false);
define('JUT_HTML_JPANE', false);
define('JUT_HTML_JTOOLTIPS', false);

/**
 * /libraries/joomla/html/parameter
 */
define('JUT_HTML_JPARAMETER', false);
define('JUT_HTML_PARAMETER_JELEMENT', false);
define('JUT_HTML_PARAMETER_JELEMENTCATEGORY', false);
define('JUT_HTML_PARAMETER_JELEMENTEDITORS', false);
define('JUT_HTML_PARAMETER_JELEMENTFILELIST', false);
define('JUT_HTML_PARAMETER_JELEMENTFOLDERLIST', false);
define('JUT_HTML_PARAMETER_JELEMENTHELPSITES', false);
define('JUT_HTML_PARAMETER_JELEMENTHIDDEN', false);
define('JUT_HTML_PARAMETER_JELEMENTIMAGELIST', false);
define('JUT_HTML_PARAMETER_JELEMENTLANGUAGES', false);
define('JUT_HTML_PARAMETER_JELEMENTLIST', false);
define('JUT_HTML_PARAMETER_JELEMENTMENU', false);
define('JUT_HTML_PARAMETER_JELEMENTMENUITEM', false);
define('JUT_HTML_PARAMETER_JELEMENTPASSWORD', false);
define('JUT_HTML_PARAMETER_JELEMENTRADIO', false);
define('JUT_HTML_PARAMETER_JELEMENTSECTION', false);
define('JUT_HTML_PARAMETER_JELEMENTSPACER', false);
define('JUT_HTML_PARAMETER_JELEMENTSQL', false);
define('JUT_HTML_PARAMETER_JELEMENTTEXT', false);
define('JUT_HTML_PARAMETER_JELEMENTTEXTAREA', false);
define('JUT_HTML_PARAMETER_JELEMENTTIMEZONES', false);

/**
 * /libraries/joomla/html/toolbar
 */
define('JUT_HTML_JTOOLBAR', false);
define('JUT_HTML_TOOLBAR_JBUTTON', false);
define('JUT_HTML_TOOLBAR_JBUTTONCONFIRM', false);
define('JUT_HTML_TOOLBAR_JBUTTONCUSTOM', false);
define('JUT_HTML_TOOLBAR_JBUTTONHELP', false);
define('JUT_HTML_TOOLBAR_JBUTTONLINK', false);
define('JUT_HTML_TOOLBAR_JBUTTONPOPUP', false);
define('JUT_HTML_TOOLBAR_JBUTTONSEPARATOR', false);
define('JUT_HTML_TOOLBAR_JBUTTONSTANDARD', false);

/**
 * /libraries/joomla/i18n
 */
define('JUT_I18N_JHELP', false);
define('JUT_I18N_JLANGUAGE', false);

define('JUT_LANGUAGE_HELP',     'en-GB'); // context help (admin)
define('JUT_LANGUAGE_FRONTEND', 'en-GB'); // JSite
define('JUT_LANGUAGE_BACKEND',  'en-GB'); // JAdministrator
define('JUT_LANGUAGE_INSTALL',  'en-GB'); // JInstaller

/**
 * /libraries/joomla/installer
 */
define('JUT_INSTALLER_JINSTALLER', false);

define('JUT_INSTALLER_ADAPTERS_JINSTALLERPLUGIN', false);
define('JUT_INSTALLER_ADAPTERS_JINSTALLERMODULE', false);
define('JUT_INSTALLER_ADAPTERS_JINSTALLERLANGUAGE', false);
define('JUT_INSTALLER_ADAPTERS_JINSTALLERTEMPLATE', false);
define('JUT_INSTALLER_ADAPTERS_JINSTALLERCOMPONENT', false);

/**
 * /libraries/joomla/registry/format
 */
define('JUT_REGISTRY_JREGISTRY', false);

/**
 * /libraries/joomla/registry/format
 */
define('JUT_REGISTRY_JREGISTRYFORMAT', false);
define('JUT_REGISTRY_JREGISTRYFORMATINI', false);
define('JUT_REGISTRY_JREGISTRYFORMATPHP', false);
define('JUT_REGISTRY_JREGISTRYFORMATXML', false);

/**
 * /libraries/joomla/template
 */
define('JUT_TEMPLATE_JTEMPLATE', false);

/**
 * /libraries/joomla/user
 *
 * Edit TestConfiguration.php, not TestConfiguration-dist.php.
 * Never commit plaintext passwords to the source code repository!
 */
define('JUT_USER_JUSER', false);
define('JUT_USER_JUSERHELPER', false);

define('JUT_USER_JAUTHENTICATE', false);
define('JUT_USER_JAUTHENTICATERESPONSE', false);

define('JUT_USER_JAUTHORIZATION', false);
define('JUT_USER_JTABLEARO',      false); // what are they doin' here?
define('JUT_USER_JTABLEAROGROUP', false); // -> /database/table/aro.php, arogroup.php

/**
 * /libraries/joomla/utilities
 */
define('JUT_UTILITIES_JARRAYHELPER', true);
define('JUT_UTILITIES_JBUFFERSTREAM', false);
define('JUT_UTILITIES_JDATE', false);
define('JUT_UTILITIES_JERROR', false);
define('JUT_UTILITIES_JEXCEPTION', false);
define('JUT_UTILITIES_FUNCTIONS', false);  # no class found
define('JUT_UTILITIES_JLOG', false);
define('JUT_UTILITIES_JMAIL', false);
define('JUT_UTILITIES_JMAILHELPER', false);
define('JUT_UTILITIES_JPROFILER', false);
define('JUT_UTILITIES_JSIMPLEXML', false);
define('JUT_UTILITIES_JSIMPLEXMLELEMENT', false);
define('JUT_UTILITIES_JSTRING', false);
define('JUT_UTILITIES_JUTILITY', false);

/**@#- */
