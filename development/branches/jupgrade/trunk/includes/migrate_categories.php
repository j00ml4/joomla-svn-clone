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
require_once ( JPATH_LIBRARIES.DS.'joomla'.DS.'database'.DS.'table'.DS.'category.php' );
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


// Component name
$query = "SELECT id, `option`"
." FROM {$config['prefix']}components";
$db->setQuery( $query );
$compName = $db->loadAssocList();

//print_r($compName);


/* Getting old values */
$query = "SELECT *"
." FROM {$config['prefix']}sections"
." ORDER BY id ASC";
$db->setQuery( $query );
$sections = $db->loadObjectList();
//print_r($sections);

for($i=0;$i<count($sections);$i++) {
	//echo $sections[$i]->id . "<br>";

	$category = new JTableCategory($db_new);
	$category->title = $sections[$i]->title;
	$category->alias = $sections[$i]->alias;
	$category->description = $sections[$i]->description;
	$category->published = $sections[$i]->published;
	$category->checked_out = $sections[$i]->checked_out;
	$category->checked_out_time = $sections[$i]->checked_out_time;
	$category->access = $sections[$i]->access;
	$category->params = $sections[$i]->params;
	$category->level = 1;
	$category->extension = "com_{$sections[$i]->scope}";
	$category->setRules('{"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}');
	$category->store();

	//print_r($category);

	/*
	 * CHILDREN CATEGORIES
	 */

	$query = "SELECT *"
	." FROM {$config['prefix']}categories"
	." WHERE section = {$sections[$i]->id}"
	." ORDER BY id ASC";
	$db->setQuery( $query );
	$categories = $db->loadObjectList();

	for($y=0;$y<count($categories);$y++){

		//echo $categories[$y]->title."<br>";
		$child = new JTableCategory($db_new);
		$child->setLocation($category->id);
		//$child->parent_id = $category->id;
		$child->title = $categories[$y]->title;
		$child->alias = $categories[$y]->alias;
		$child->description = $categories[$y]->description;
		$child->published = $categories[$y]->published;
		$child->checked_out = $categories[$y]->checked_out;
		$child->checked_out_time = $categories[$y]->checked_out_time;
		$child->access = $categories[$y]->access;
		$child->params = $categories[$y]->params;
		//$child->extension = $compName[$categories[$y]->section-1]['option'];
		$child->setRules('{"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}');
		$child->store();

	}
}

//echo "\n\n";

sleep(1);
?>
