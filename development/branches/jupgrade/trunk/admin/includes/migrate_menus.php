<?php
/**
 * jUpgrade
 *
 * @author      Matias Aguirre
 * @email       maguirre@matware.com.ar
 * @url         http://www.matware.com.ar
 * @license     GNU/GPL
 */

define( '_JEXEC', 1 );
define( 'JPATH_BASE', dirname(__FILE__) );
define( 'DS', DIRECTORY_SEPARATOR );
require_once ( JPATH_BASE .DS.'defines.php' );

require_once ( JPATH_LIBRARIES.DS.'joomla'.DS.'methods.php' );
require_once ( JPATH_LIBRARIES.DS.'joomla'.DS.'factory.php' );
require_once ( JPATH_LIBRARIES.DS.'joomla'.DS.'import.php' );
require_once ( JPATH_LIBRARIES.DS.'joomla'.DS.'error'.DS.'error.php' );
require_once ( JPATH_LIBRARIES.DS.'joomla'.DS.'base'.DS.'object.php' );
require_once ( JPATH_LIBRARIES.DS.'joomla'.DS.'database'.DS.'database.php' );
require_once ( JPATH_LIBRARIES.DS.'joomla'.DS.'database'.DS.'table.php' );
require_once ( JPATH_LIBRARIES.DS.'joomla'.DS.'database'.DS.'tablenested.php' );
require_once ( JPATH_LIBRARIES.DS.'joomla'.DS.'database'.DS.'table'.DS.'menu.php' );
require_once ( JPATH_LIBRARIES.DS.'joomla'.DS.'database'.DS.'table'.DS.'menutype.php' );
require(JPATH_ROOT.DS."configuration.php");

$jconfig = new JConfig();
//print_r($jconfig);

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

$query = "SELECT *"
." FROM {$config['prefix']}menu"
." ORDER BY id ASC";
$db->setQuery( $query );
$menu = $db->loadObjectList();
//echo $db->errorMsg();
//print_r($content[0]);

for($i=0;$i<count($menu);$i++) {
	//echo $sections[$i]->id . "<br>";

	$new = new JTableMenu($db_new);
	//print_r($new);
	//$new->id = $menu[$i]->
	$new->menutype = $menu[$i]->menutype;
	$new->title = $menu[$i]->name;
	$new->alias = $menu[$i]->alias;
	$new->link = $menu[$i]->link;
	$new->type = $menu[$i]->type;
	$new->published = $menu[$i]->published;
	$new->parent_id = $menu[$i]->parent;
	$new->level = $menu[$i]->sublevel;
	$new->component_id = $menu[$i]->componentid;
	$new->ordering = $menu[$i]->ordering;
	$new->checked_out = $menu[$i]->checked_out;
	$new->checked_out_time = $menu[$i]->checked_out_time;
	$new->browserNav = $menu[$i]->browserNav;
	$new->access = $menu[$i]->access+1;
	$new->params = $menu[$i]->params;
	$new->lft = $menu[$i]->lft;
	$new->rgt = $menu[$i]->rgt;
	$new->store();

}

$query = "SELECT *"
." FROM {$config['prefix']}menu_types"
." WHERE id > 1";
$db->setQuery( $query );
$menutype = $db->loadObjectList();
//echo $db->errorMsg();

for($i=0;$i<count($menutype);$i++) {
	//echo $sections[$i]->id . "<br>";

	$new = new JTableMenuType($db_new);
	//print_r($new);
	$new->menutype = $menutype[$i]->menutype;
	$new->title = $menutype[$i]->title;
	$new->description = $menutype[$i]->description;
	$new->store();

}


sleep(1);
?>
