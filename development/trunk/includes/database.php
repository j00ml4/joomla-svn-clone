<?php // compatibility
require_once( dirname(__FILE__)  .'/../libraries/loader.php' );

/**
* Legacy class, derive from JTable instead
* @deprecated As of version 1.5
*/
jimport( 'joomla.database.database' );
jimport( 'joomla.database.database.mysql' );
/**
 * @package Joomla
 * @deprecated As of version 1.5
 */
class database extends JDatabaseMySQL {
	function __construct ($host='localhost', $user, $pass, $db='', $table_prefix='', $offline = true) {
		parent::__construct( $host, $user, $pass, $db, $table_prefix );
	}
}
?>