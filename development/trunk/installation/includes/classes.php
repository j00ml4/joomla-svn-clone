<?php
/**
* @version $Id$
* @package Joomla
* @subpackage Installation
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

/**
* @package Joomla
* @subpackage Installation
*/
class installationTasks {
	/**
	 * @param patTemplate A template object
	 */
	function chooseLanguage() {

		$native = detectLanguage();

		$lists = array();
		$lists['langs'] = JLanguageHelper::buildLanguageList( 2, $native );

		installationScreens::chooseLanguage( $lists );
	}

	/**
	 * @param patTemplate A template object
	 */
	function preInstall() {
    	global $_LANG;

		$vars = mosGetParam( $_POST, 'vars', array() );
		$lists = array();

		$phpOptions[] = array(
			'label' => $_LANG->_( 'PHP version' ) .' >= 4.1.0',
			'state' => phpversion() < '4.1' ? 'No' : 'Yes'

		);
		$phpOptions[] = array(
			'label' => '- '. $_LANG->_( 'zlib compression support' ),
			'state' => extension_loaded('zlib') ? 'Yes' : 'No'
		);
		$phpOptions[] = array(
			'label' => '- '. $_LANG->_( 'XML support' ),
			'state' => extension_loaded('xml') ? 'Yes' : 'No',
			'statetext' => extension_loaded('xml') ? 'Yes' : 'No'
		);
		$phpOptions[] = array(
			'label' => '- '. $_LANG->_( 'MySQL support' ),
			'state' => function_exists( 'mysql_connect' ) ? 'Yes' : 'No'
		);
		$sp = '';
		$phpOptions[] = array(
			'label' => $_LANG->_( 'Session path set' ),
			'state' =>  ($sp = ini_get( 'session.save_path' )) ? 'Yes' : 'No'
		);
		$phpOptions[] = array(
			'label' => $_LANG->_( 'Session path writeable' ),
			'state' =>  is_writable( $sp ) ? 'Yes' : 'No'
		);
		$cW = (@file_exists('../configuration.php') &&  @is_writable( '../configuration.php' ))
			|| is_writable( '..' );
		$phpOptions[] = array(
			'label' => 'configuration.php '. $_LANG->_( 'writeable' ),
			'state' =>  $cW ? 'Yes' : 'No',
			'notice' => $cW ? '' : $_LANG->_( 'NOTICEYOUCANSTILLINSTALL' )
		);
		$lists['phpOptions'] =& $phpOptions;

		$phpRecommended = array(
			array( $_LANG->_( 'Safe Mode' ), 'safe_mode', 'OFF' ),
			array( $_LANG->_( 'Display Errors' ), 'display_errors', 'ON' ),
			array( $_LANG->_( 'File Uploads' ), 'file_uploads', 'ON' ),
			array( $_LANG->_( 'Magic Quotes GPC' ), 'magic_quotes_gpc', 'ON' ),
			array( $_LANG->_( 'Magic Quotes Runtime' ), 'magic_quotes_runtime', 'OFF' ),
			array( $_LANG->_( 'Register Globals' ), 'register_globals', 'OFF' ),
			array( $_LANG->_( 'Output Buffering' ), 'output_buffering', 'OFF' ),
			array( $_LANG->_( 'Session auto start' ), 'session.auto_start', 'OFF' )
		);

		foreach ($phpRecommended as $setting) {
			$lists['phpSettings'][] = array(
				'label' => $setting[0],
				'setting' => $setting[2],
				'actual' => get_php_setting( $setting[1] ),
				'state' => get_php_setting( $setting[1] ) == $setting[2] ? 'Yes' : 'No'
			);
		}

		$folders = array(
			'administrator/backups',
			'administrator/components',
			'administrator/language',
			'administrator/modules',
			'administrator/templates',
			'cache',
			'components',
			'images',
			'images/banners',
			'images/stories',
			'language',
			'mambots',
			'mambots/content',
			'mambots/editors',
			'mambots/search',
			'media',
			'modules',
			'templates',
		);
		foreach ($folders as $folder) {
			$lists['folderPerms'][] = array(
				'label' => $folder,
				'state' => is_writeable( JPATH_SITE . '/' . $folder ) ? 'Writeable' : 'Unwriteable'
			);
		}

		installationScreens::preInstall( $vars, $lists );
	}

	/**
	 * Gets the parameters for database creation
	 */
	function license() {
		$vars = mosGetParam( $_POST, 'vars', array() );
		installationScreens::license( $vars );
	}

	/**
	 * Gets the parameters for database creation
	 */
	function dbConfig() {

		$vars = mosGetParam( $_POST, 'vars', array() );
		if (!isset( $vars['DBPrefix'] )) {
			$vars['DBPrefix'] = 'jos_';
		}

		$lists = array();
		$files = array(
			'mysql',
			'mysqli',
		);
		$db = mosInstallation::detectDB();
		foreach ($files as $file) {
			$option = array();
			$option['text'] = $file;
			if (strcasecmp( $option['text'], $db ) == 0) {
				$option['selected'] = 'selected="true"';
			}
			$lists['dbTypes'][] = $option;
		}

		installationScreens::dbConfig( $vars, $lists );
	}

	/**
	 * Gets the parameters for database creation
	 * @return boolean True if successful
	 */
	function makeDB() {
		global $_LANG;

		$vars = mosGetParam( $_POST, 'vars', array() );

		$DBcreated = mosGetParam( $vars, 'DBcreated', '0' );

		$DBtype		= mosGetParam( $vars, 'DBtype', 'mysql' );
		$DBhostname = mosGetParam( $vars, 'DBhostname', '' );
		$DBuserName = mosGetParam( $vars, 'DBuserName', '' );
		$DBpassword = mosGetParam( $vars, 'DBpassword', '' );
		$DBname  	= mosGetParam( $vars, 'DBname', '' );
		$DBPrefix  	= mosGetParam( $vars, 'DBPrefix', 'jos_' );
		$DBDel  	= mosGetParam( $vars, 'DBDel', 0 );
		$DBBackup  	= mosGetParam( $vars, 'DBBackup', 0 );
		$DBSample  	= mosGetParam( $vars, 'DBSample', 1 );
	
		if($DBtype == '') {
			installationScreens::error( $vars, $_LANG->_( 'validType' ), 'dbconfig' );
			return false;
		}
		if (!$DBhostname || !$DBuserName || !$DBname) {
			installationScreens::error( $vars, $_LANG->_( 'validDBDetails' ), 'dbconfig' );
			return false;
		}
		if($DBname == '') {
			installationScreens::error( $vars, $_LANG->_( 'emptyDBName' ), 'dbconfig' );
			return false;
		}

		if (!$DBcreated) {
			
			jimport('joomla.database.'.$DBtype);
			$database =& new database( $DBhostname, $DBuserName, $DBpassword, $DBname, $DBPrefix );
			
			if ($err = $database->getErrorNum()) {
				if ($err == 3) {
					// connection ok, need to create database
					if (mosInstallation::createDatabase( $database, $DBname )) {
						// make the new connection to the new database
						$database =& new database( $DBhostname, $DBuserName, $DBpassword, $DBname, $DBPrefix );
					} else {
						$error = $database->getErrorMsg();
						installationScreens::error( $vars, array( $_LANG->_( 'WARNCREATEDB' ) .' '. $DBname ), 'dbconfig', $error );
						return false;
					}
				} else {
					// connection failed
					//installationScreens::error( $vars, array( 'Could not connect to the database.  Connector returned', $database->getErrorNum() ), 'dbconfig', $database->getErrorMsg() );
					installationScreens::error( $vars, array( $_LANG->_( 'WARNNOTCONNECTDB' ) .' '. $database->getErrorNum() ), 'dbconfig', $database->getErrorMsg() );
					return false;
				}
			}
			
			$database =& new database( $DBhostname, $DBuserName, $DBpassword, $DBname, $DBPrefix );

			if ($DBBackup) {
				if (mosInstallation::backupDatabase( $database, $DBname, $DBPrefix, $errors )) {
					installationScreens::error( $vars, $_LANG->_( 'WARNBACKINGUPDB' ), 'dbconfig', mosInstallation::errors2string( $errors ) );
					return false;
				}
			}
			if ($DBDel) {
				if (mosInstallation::deleteDatabase( $database, $DBname, $DBPrefix, $errors )) {
					installationScreens::error( $vars, 'Some errors occurred deleting the database.', 'dbconfig', mosInstallation::errors2string( $errors ) );
					return false;
				}
			}

			
			// checks if language depend files do exist
			$dbscheme = 'joomla.sql';
			if (mosInstallation::populateDatabase( $database, $dbscheme, $errors )) {
				installationScreens::error( $vars, 'Some errors occurred populating the database.', 'dbconfig', mosInstallation::errors2string( $errors ) );
				return false;
			}

			if ($DBSample) {
				$dbsample = 'sample_data.sql';
				mosInstallation::populateDatabase( $database, $dbsample, $errors);
				return true;
			}
		}

		return true;
	}

	/**
	 * Finishes configuration parameters
	 */
	function mainConfig( $DBcreated='0' ) {
	
		$vars = mosGetParam( $_POST, 'vars', array() );
		$vars['DBcreated'] = mosGetParam( $vars, 'DBcreated', $DBcreated );
		$strip = get_magic_quotes_gpc();

		if (!isset( $vars['siteUrl'] )) {
			$root = $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
			$root = str_replace( 'installation/', '', $root );
			$root = str_replace( '/index.php', '', $root );
			$vars['siteUrl'] = 'http://' . $root;
		}
		if (isset( $vars['sitePath'] )) {
			$vars['sitePath'] = stripslashes( stripslashes( $vars['sitePath'] ) );
		} else {
			$vars['sitePath'] = JPATH_SITE;
		}
		if (isset( $vars['siteName'] )) {
			$vars['siteName'] = stripslashes( stripslashes( $vars['siteName'] ) );
		}
		$vars['adminPassword'] = mosMakePassword( 8 );

		// CHMOD stuff
		$flags = 0664;
		if ($flags & 0400) {
			$vars['perm_fur'] = ' checked="checked"';
		}
		if ($flags & 0200) {
			$vars['perm_fuw'] = ' checked="checked"';
		}
		if ($flags & 0100) {
			$vars['perm_fue'] = ' checked="checked"';
		}
		if ($flags & 040) {
			$vars['perm_fgr'] = ' checked="checked"';
		}
		if ($flags & 020) {
			$vars['perm_fgw'] = ' checked="checked"';
		}
		if ($flags & 010) {
			$vars['perm_fge'] = ' checked="checked"';
		}
		if ($flags & 04) {
			$vars['perm_fwr'] = ' checked="checked"';
		}
		if ($flags & 02) {
			$vars['perm_fww'] = ' checked="checked"';
		}
		if ($flags & 01) {
			$vars['perm_fwe'] = ' checked="checked"';
		}
		$flags = 0775;
		if ($flags & 0400) {
			$vars['perm_dur'] = ' checked="checked"';
		}
		if ($flags & 0200) {
			$vars['perm_duw'] = ' checked="checked"';
		}
		if ($flags & 0100) {
			$vars['perm_due'] = ' checked="checked"';
		}
		if ($flags & 040) {
			$vars['perm_dgr'] = ' checked="checked"';
		}
		if ($flags & 020) {
			$vars['perm_dgw'] = ' checked="checked"';
		}
		if ($flags & 010) {
			$vars['perm_dge'] = ' checked="checked"';
		}
		if ($flags & 04) {
			$vars['perm_dwr'] = ' checked="checked"';
		}
		if ($flags & 02) {
			$vars['perm_dww'] = ' checked="checked"';
		}
		if ($flags & 01) {
			$vars['perm_dwe'] = ' checked="checked"';
		}

		installationScreens::mainConfig( $vars );
	}

	function saveConfig() {
		
		$vars = mosGetParam( $_POST, 'vars', array() );

		$vars['fileperms'] = mosInstallation::getFilePerms( $vars, 'file' );
		$vars['dirperms'] = mosInstallation::getFilePerms( $vars, 'dir' );

		$strip = get_magic_quotes_gpc();
		if (!$strip) {
			$vars['siteName'] = addslashes( $vars['siteName'] );
		}
		$vars['secret'] = mosMakePassword( 16 );
		$vars['hidePdf'] = intval( !is_writable( $vars['sitePath'] . '/media/' ) );

		switch ($vars['DBtype']) {
			case 'mssql':
				$vars['ZERO_DATE'] = '1/01/1990';
				break;
			default:
				$vars['ZERO_DATE'] = '0000-00-00 00:00:00';
				break;
		}

		mosInstallation::createAdminUser( $vars );

		$tmpl =& installationScreens::createTemplate();
		$tmpl->readTemplatesFromFile( 'configuration.html' );
		$tmpl->addVars( 'configuration', $vars, 'var_' );

		$buffer = $tmpl->getParsedTemplate( 'configuration' );
		$path = JPATH_SITE . '/configuration.php';

		if (file_exists( $path )) {
			$canWrite = is_writable( $path );
		} else {
			$canWrite = is_writable( JPATH_SITE);
		}
		if ($canWrite) {
			file_put_contents( $path, $buffer );
			return '';
		} else {
			return $buffer;
		}
	}

	/**
	 * Displays the finish screen
	 */
	function finish( $buffer='' ) {

		$vars = mosGetParam( $_POST, 'vars', array() );

		$vars['adminUrl'] = $vars['siteUrl'] . '/administrator';

		installationScreens::finish( $vars, $buffer );
	}
}

/**
* @package Joomla
* @subpackage Installation
*/
class mosInstallation {
	/**
	 * @return string A guess at the db required
	 */
	function detectDB() {
		$map = array(
			'mysql_connect' => 'mysql',
			'mysqli_connect' => 'mysqli',
			'mssql_connect' => 'mssql'
		);
		foreach ($map as $f => $db) {
			if (function_exists( $f )) {
				return $db;
			}
		}
		return 'mysql';
	}

	/**
	 * @param array
	 * @return string
	 */
	function errors2string( &$errors ) {
		$buffer = '';
		foreach ($errors as $error) {
			$buffer .=  'SQL=' . $error['msg'] . ":\n- - - - - - - - - -\n" . $error['sql'] . "\n= = = = = = = = = =\n\n";
		}
		return $buffer;
	}
	/**
	 * Creates a new database
	 * @param object Database connector
	 */
	function createDatabase( &$database, $DBname ) {
		
		$sql = "CREATE DATABASE `$DBname`";
		$database->setQuery( $sql );
		$database->query();
		$result = $database->getErrorNum();
		
		if($result != 0) {
			return false;
		}
		
		return true;
	}

	/**
	 * Backs up existing tables
	 * @param object Database connector
	 * @param array An array of errors encountered
	 */
	function backupDatabase( &$database, $DBname, $DBPrefix, &$errors ) {
		
		$query = "SHOW TABLES FROM `$DBname`";
		$database->setQuery( $query );
		$errors = array();
		if ($tables = $database->loadResultArray()) {
			foreach ($tables as $table) {
				if (strpos( $table, $DBPrefix ) === 0) {
						$butable = str_replace( $DBPrefix, $BUPrefix, $table );
						$query = "DROP TABLE IF EXISTS `$butable`";
						$database->setQuery( $query );
						$database->query();
						if ($database->getErrorNum()) {
							$errors[$database->getQuery()] = $database->getErrorMsg();
						}
						$query = "RENAME TABLE `$table` TO `$butable`";
						$database->setQuery( $query );
						$database->query();
						if ($database->getErrorNum()) {
							$errors[$database->getQuery()] = $database->getErrorMsg();
						}
				}
			}
		}
		
		return count( $errors );
	}
	/**
	 * Deletes all database tables
	 * @param object Database connector
	 * @param array An array of errors encountered
	 */
	function deleteDatabase( &$database, $DBname, $DBPrefix, &$errors ) {
		
		$query = "SHOW TABLES FROM `$DBname`";
		$database->setQuery( $query );
		$errors = array();
		if ($tables = $database->loadResultArray()) {
			foreach ($tables as $table) {
				if (strpos( $table, $DBPrefix ) === 0) {
					$query = "DROP TABLE IF EXISTS `$table`";
					$database->setQuery( $query );
					$database->query();
					if ($database->getErrorNum()) {
						$errors[$database->getQuery()] = $database->getErrorMsg();
					}
				}
			}
		}
		
		return count( $errors );
	}

	/**
	 *
	 */
	function populateDatabase( &$database, $sqlfile, &$errors ) {
		$buffer 	= file_get_contents( 'sql/' . $sqlfile );
		$queries 	= mosInstallation::splitSql( $buffer );

		foreach ($queries as $query) {
			$query = trim( $query );
			if ($query != '' && $query{0} != '#') {
				$database->setQuery( $query );
				$database->query();
				if ($database->getErrorNum() > 0) {
					$errors[] = array (
						'msg' => $database->getErrorMsg(),
						'sql' => $query
					);
				}
			}
		}
		return count( $errors );
	}

	/**
	 * @param string
	 * @return array
	 */
	function splitSql( $sql ) {
		$sql = trim( $sql );
		$sql = preg_replace( "/\n\#[^\n]*/", '', "\n" . $sql );

		$buffer = array();
		$ret = array();
		$in_string = false;

		for ($i = 0; $i < strlen( $sql )-1; $i++) {
			if($sql[$i] == ";" && !$in_string) {
				$ret[] = substr($sql, 0, $i);
				$sql = substr($sql, $i + 1);
				$i = 0;
			}

			if($in_string && ($sql[$i] == $in_string) && $buffer[1] != "\\") {
				$in_string = false;
			}
			elseif(!$in_string && ($sql[$i] == '"' || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\")) {
				$in_string = $sql[$i];
			}
			if(isset($buffer[1])) {
				$buffer[0] = $buffer[1];
			}
			$buffer[1] = $sql[$i];
		}

		if(!empty($sql)) {
			$ret[] = $sql;
		}
		return($ret);
	}

	/**
	 * Calculates the file/dir permissions mask
	 */
	function getFilePerms( $input, $type='file' ) {
		$perms = '';
		if (mosGetParam( $input, $type . 'PermsMode', 0 )) {
			$action = ($type=='dir') ? 'Search' : 'Execute';
			$perms = '0'.
				(mosGetParam( $input, $type . 'PermsUserRead', 0 ) * 4 +
				 	mosGetParam( $input, $type . 'PermsUserWrite', 0 ) * 2 +
				 	mosGetParam( $input, $type . 'PermsUser' . $action, 0 )
				).
				(mosGetParam( $input, $type . 'PermsGroupRead', 0 ) * 4 +
					mosGetParam( $input, $type . 'PermsGroupWrite', 0 ) * 2 +
					mosGetParam( $input, $type . 'PermsGroup' . $action, 0 )
				).
				(mosGetParam( $input, $type . 'PermsWorldRead', 0 ) * 4 +
				 	mosGetParam( $input, $type . 'PermsWorldWrite', 0 ) * 2 +
				 	mosGetParam( $input, $type . 'PermsWorld' . $action, 0 )
				);
		}
		return $perms;
	}

	/**
	 * Creates the admin user
	 */
	function createAdminUser( &$vars ) {

		$DBtype 	= mosGetParam( $vars, 'DBtype', 'mysql' );
		$DBhostname = mosGetParam( $vars, 'DBhostname', '' );
		$DBuserName = mosGetParam( $vars, 'DBuserName', '' );
		$DBpassword = mosGetParam( $vars, 'DBpassword', '' );
		$DBname  	= mosGetParam( $vars, 'DBname', '' );
		$DBPrefix  	= mosGetParam( $vars, 'DBprefix', '' );

		$adminPassword 	= mosGetParam( $vars, 'adminPassword', '' );
		$adminEmail 	= mosGetParam( $vars, 'adminEmail', '' );

		$cryptpass = md5( $adminPassword );

		jimport('joomla.database.'.$DBtype);
		$database = new database( $DBhostname, $DBuserName, $DBpassword, $DBname, $DBPrefix );

		// create the admin user
		$installdate = date( 'Y-m-d H:i:s' );
		$query = "INSERT INTO #__users VALUES (62, 'Administrator', 'admin', "
			. $database->Quote( $adminEmail ) . ", "
			. $database->Quote( $cryptpass )  . ", 'Super Administrator', 0, 1, 25, '$installdate', '0000-00-00 00:00:00', '', '')";
		$database->setQuery( $query );
		if ( !$database->query() ) {
			echo $database->getErrorMsg();
			return;
		}

		// add the ARO (Access Request Object)
		$query = "INSERT INTO #__core_acl_aro VALUES (10,'users','62',0,'Administrator',0)";
		$database->setQuery( $query );
		if ( !$database->query() ) {
			echo $database->getErrorMsg();
			return;
		}

		// add the map between the ARO and the Group
		$query = "INSERT INTO #__core_acl_groups_aro_map VALUES (25,'',10)";
		$database->setQuery( $query );
		if ( !$database->query() ) {
			echo $database->getErrorMsg();
			return;
		}
	}
}

?>
