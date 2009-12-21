<?php
/**
 * jUpgrade
 *
 * @author      Matias Aguirre
 * @email       maguirre@matware.com.ar
 * @url         http://www.matware.com.ar
 * @license     GNU/GPL
 */


function insertObjectList( $db, $table, &$object, $keyName = NULL ) {

	//print_r($db);

	$count = count($object);

	for ($i=0; $i<$count; $i++) {
		$db->insertObject($table, $object[$i]);
	}

	$ret = $db->getErrorNum();

	//print_r($db);

	if($ret = 0) {
		return true;
	}else{
	  return $ret;
	}
}

define( '_JEXEC', 1 );
define( 'JPATH_BASE', dirname(__FILE__) );
define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE .DS.'defines.php' );
require_once ( JPATH_LIBRARIES .DS.'joomla'.DS.'methods.php' );
require_once ( JPATH_LIBRARIES .DS.'joomla'.DS.'factory.php' );
require_once ( JPATH_LIBRARIES .DS.'joomla'.DS.'import.php' );
require_once ( JPATH_LIBRARIES .DS.'joomla'.DS.'error'.DS.'error.php' );
require_once ( JPATH_LIBRARIES .DS.'joomla'.DS.'base'.DS.'object.php' );
require_once ( JPATH_LIBRARIES .DS.'joomla'.DS.'database'.DS.'database.php' );
require_once ( JPATH_LIBRARIES .DS.'joomla'.DS.'html'.DS.'parameter.php' );
//require_once ( JPATH_ADMINISTRATOR .DS.'components'.DS.'com_jupgrade'.DS.'helpers'.DS.'extendeddb.php' );
require(JPATH_ROOT.DS."configuration.php");

$jconfig = new JConfig();

$config = array();
$config['driver']   = 'mysql';
$config['host']     = $jconfig->host;
$config['user']     = $jconfig->user; 
$config['password'] = $jconfig->password;
$config['database'] = $jconfig->db;  
$config['prefix']   = $jconfig->dbprefix;
//print_r($config);

$config_new = $config;
$config_new['prefix'] = "j16_";

$db = JDatabase::getInstance( $config );
$db_new = JDatabase::getInstance( $config_new );
//print_r($db_new);
//print_r($db2);

// Migrating Users
$query = "SELECT `id`, `name`, `username`, `email`, `password`, `usertype`, `block`,"
		." `sendEmail`, `registerDate`, `lastvisitDate`, `activation`, `params`"
		." FROM " . $config['prefix'] . "users";

//echo $query;

$db->setQuery( $query );
$users = $db->loadObjectList();
//print_r($users);


for($i=0;$i<count($users);$i++){
	$p = explode("\n", $users[$i]->params);
	$params = array();
	for($y=0;$y<count($p);$y++){
		$ex = explode("=",$p[$y]);
		if($ex[0] != ""){
			$params[$ex[0]] = $ex[1];
		}
	}
	$parameter = new JParameter($params);
	$parameter->loadArray($params);
	$users[$i]->params = $parameter->toString();
	//echo $parameter->toString() . "\n";
}

echo insertObjectList($db_new, '#__users', $users);

// Migrating Groups
$query = "SELECT id, title FROM #__usergroups";
$db_new->setQuery( $query );
$gids = $db_new->loadAssocList();		
$newgids = array();
for($i=0;$i<count($gids);$i++) {
	$newgids[$gids[$i]['title']] = $gids[$i]['id'];
}

//print_r($newgids);

$query = "SELECT u.id AS user_id, u.usertype AS group_id"
." FROM {$config['prefix']}users AS u";
$db->setQuery( $query );
//echo $query;

$user_usergroup_map = $db->loadObjectList();
for($i=0;$i<count($user_usergroup_map);$i++) {
	if ($user_usergroup_map[$i]->group_id == "Super Administrator") {
		$user_usergroup_map[$i]->group_id = "Super Users";
	}
	$user_usergroup_map[$i]->group_id = $newgids[$user_usergroup_map[$i]->group_id];
}
//print_r($user_usergroup_map);
$ret = insertObjectList($db_new, '#__user_usergroup_map', $user_usergroup_map);	

?>
