<?php
/**
* @version $Id: index.php,v 1.1 2005/08/25 14:21:20 johanjanssens Exp $
* @package Mambo
* @subpackage Installation
* @copyright (C) 2000 - 2005 Miro International Pty Ltd
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Mambo is Free Software
*/

define( '_VALID_MOS', 1 );

if (file_exists( '../configuration.php' ) && filesize( '../configuration.php' ) > 10) {
	header( 'Location: ../index.php' );
	exit();
}

error_reporting( E_ALL );

// include support libraries
require_once( 'installation.functions.php' );
require_once( 'installation.class.php' );
require_once( 'installation.html.php' );

$mosConfig_absolute_path = getAbsolutePath();
if (phpversion() < '4.2.0') {
	require_once( $mosConfig_absolute_path . '/includes/compat.php41x.php' );
}
if (phpversion() < '4.3.0') {
	require_once( $mosConfig_absolute_path . '/includes/compat.php42x.php' );
}
if (in_array( 'globals', array_keys( array_change_key_case( $_REQUEST, CASE_LOWER ) ) ) ) {
	die( 'Fatal error.  Global variable hack attempted.' );
}
if (version_compare( phpversion(), '5.0' ) < 0) {
	require_once( $mosConfig_absolute_path . '/includes/compat.php50x.php' );
}

require_once( $mosConfig_absolute_path . '/includes/version.php' );
require_once( $mosConfig_absolute_path . '/includes/mambo.factory.php' );
require_once( $mosConfig_absolute_path . '/includes/mambo.files.php' );
require_once( $mosConfig_absolute_path . '/includes/mamboxml.php' );

$vars = mosGetParam( $_POST, 'vars', array() );
$_LANG =& mosFactory::getLanguage();

$mosConfig_lang = mosGetParam( $vars, 'lang', detectLanguage() );
$_LANG->_load( $mosConfig_absolute_path . '/installation/language/' . $mosConfig_lang . '.ini' );

$task = mosGetParam( $_REQUEST, 'task', '' );

header( 'Cache-Control: no-cache, must-revalidate');	// HTTP/1.1
header( 'Pragma: no-cache');	// HTTP/1.0

switch ($task) {
	case 'preinstall':
		installationTasks::preInstall();
		break;

	case 'license':
		installationTasks::license();
		break;

	case 'dbconfig':
		installationTasks::dbConfig();
		break;

	case 'makedb':
		if (installationTasks::makeDB()) {
			installationTasks::mainConfig( 1 );
		}
		break;

	case 'mainconfig':
		installationTasks::mainConfig();
		break;

	case 'saveconfig':
		$buffer = installationTasks::saveConfig();
		installationTasks::finish( $buffer );
		break;

	case 'lang':
	default:
		installationTasks::chooseLanguage();
		break;
}
?>