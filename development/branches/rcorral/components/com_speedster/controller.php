<?php
if ( !defined('_JEXEC') ) { die( 'Direct Access to this location is not allowed.' ); }
/**
 * @version		$Id: controller.php 1 2009-10-27 20:56:04Z rafael $
 * @package		JPhoto
 * @copyright	Copyright (C) 2009 'corePHP' / corephp.com. All rights reserved.
 * @license		GNU/GPL, see LICENSE.txt
 */

/*
DELETE FROM jos_extensions WHERE `folder` = 'joomla';
DELETE FROM jos_extensions WHERE `folder` = 'hook';
DELETE FROM jos_extensions WHERE `folder` = 'hookinit';
DELETE FROM jos_extensions WHERE `folder` = 'hook2';
DELETE FROM jos_extensions WHERE `folder` = 'hook2init';
DELETE FROM `jos_extensions` WHERE `extension_id` > 10000;
ALTER TABLE `jos_extensions` AUTO_INCREMENT = 10000;
*/

jimport('joomla.application.component.controller');

class SpeedsterController extends JController
{
	var $_counter = 0;

	function display()
	{
		parent::display();
	}

	function joomla()
	{
		// Load Plugins
		JPluginHelper::importPlugin( 'joomla' );

		$dispatcher =& JDispatcher::getInstance();
		$dispatcher->trigger( 'do_test', array( &$this->_counter ) );

		echo $this->_counter;

		return;
	}

	function hook()
	{
		require 'hook.php';

		// Load Plugins
		$some_object = new stdClass();
		JPluginHelper::importPlugin( 'hook', null, true, $some_object );

		$hook = JHook::getInstance();
		$hook->trigger( 'hook_do_test', array( &$this->_counter ) );

		echo $this->_counter;

		return;
	}

	function hookinit()
	{
		require 'hook.php';

		// Load Plugins
		$some_object = new stdClass();
		JPluginHelper::importPlugin( 'hookinit', null, true, $some_object );

		$hook = JHook::getInstance();
		$hook->trigger( 'hook_do_test', array( &$this->_counter ) );

		echo $this->_counter;

		return;
	}

	function hook2()
	{
		require 'hook2.php';

		// Load Plugins
		$some_object = new stdClass();
		JPluginHelper::importPlugin( 'hook2', null, true, $some_object );

		JHook::trigger( 'hook2_do_test', array( &$this->_counter ) );

		echo $this->_counter;

		return;
	}

	function hook2init()
	{
		require 'hook2.php';

		// Load Plugins
		$some_object = new stdClass();
		JPluginHelper::importPlugin( 'hook2init', null, true, $some_object );

		JHook::trigger( 'hook2_do_test', array( &$this->_counter ) );

		echo $this->_counter;

		return;
	}

	function createjoomla()
	{
		$files = array(
			'php' => '<?php
defined(\'_JEXEC\') or die;

jimport( \'joomla.plugin.plugin\' );

class plgJoomlaJoomlaNUMBER extends JPlugin
{
	function __construct( &$subject, $config )
	{	
		parent::__construct( $subject, $config );
	}

	function do_test( &$counter )
	{
		$counter = $counter + $this->params->get( \'value\', false );
	}
}
			',
			'xml' => '<?xml version="1.0" encoding="utf-8"?>
<extension version="1.7" type="plugin" group="joomla" method="upgrade">
	<name>plg_joomla_joomlaNUMBER</name>
    <author>Rafael Corral</author>
    <authorEmail>support@corephp.com</authorEmail>
    <authorUrl>http://www.corephp.com/</authorUrl>
    <copyright></copyright>
    <license>GNU/GPL</license>
    <creationDate>24-January-2010</creationDate>
	<version>1.0.0</version>
	<description>some description, lulz</description>
	<files>
		<filename plugin="joomlaNUMBER">joomlaNUMBER.php</filename>
	</files>
	<config>
		<fields name="params">
			<fieldset name="basic">
				<field name="value" type="text"
					default="1"
					description="Value"
					label="Value" />
			</fieldset>
		</fields>
	</config>
</extension>'
		);

		$sql = "
		INSERT INTO `jos_extensions` (`name`, `type`, `element`, `folder`, `client_id`, `enabled`, `access`, `protected`, `manifest_cache`, `params`, `custom_data`, `system_data`, `checked_out`, `checked_out_time`, `ordering`, `state`)
		VALUES ('plg_joomla_joomlaNUMBER', 'plugin', 'joomlaNUMBER', 'joomla', 0, 1, 1, 0, '', '{\"value\":\"1\"}', '', '', 0, '0000-00-00 00:00:00', 0, 0)";

		$this->create_files( $files, $sql, 'joomla' );
	}

	function createhook()
	{
		$files = array(
			'php' => '<?php
defined(\'_JEXEC\') or die;

jimport( \'joomla.plugin.pluginconstruct\' );

class plghookhookNUMBER extends JPluginConstruct
{
	function __construct( $config )
	{
		parent::__construct( $config );

		$hook = JHook::getInstance();
		$hook->addFilter( \'hook_do_test\', array( $this, \'do_test\' ) );
	}

	function do_test( &$counter )
	{
		$counter = $counter + $this->params->get( \'value\', false );
	}
}
			',
			'xml' => '<?xml version="1.0" encoding="utf-8"?>
<extension version="1.7" type="plugin" group="hook" method="upgrade">
	<name>plg_hook_hookNUMBER</name>
    <author>Rafael Corral</author>
    <authorEmail>support@corephp.com</authorEmail>
    <authorUrl>http://www.corephp.com/</authorUrl>
    <copyright></copyright>
    <license>GNU/GPL</license>
    <creationDate>24-January-2010</creationDate>
	<version>1.0.0</version>
	<description>some description, lulz</description>
	<files>
		<filename plugin="hookNUMBER">hookNUMBER.php</filename>
	</files>
	<config>
		<fields name="params">
			<fieldset name="basic">
				<field name="value" type="text"
					default="1"
					description="Value"
					label="Value" />
			</fieldset>
		</fields>
	</config>
</extension>'
		);

		$sql = "
		INSERT INTO `jos_extensions` (`name`, `type`, `element`, `folder`, `client_id`, `enabled`, `access`, `protected`, `manifest_cache`, `params`, `custom_data`, `system_data`, `checked_out`, `checked_out_time`, `ordering`, `state`)
		VALUES ('plg_hook_hookNUMBER', 'plugin', 'hookNUMBER', 'hook', 0, 1, 1, 0, '', '{\"value\":\"1\"}', '', '', 0, '0000-00-00 00:00:00', 0, 0)";

		$this->create_files( $files, $sql, 'hook' );
	}

	function createhookinit()
	{
		$files = array(
			'php' => '<?php
defined(\'_JEXEC\') or die;

jimport( \'joomla.plugin.plugininit\' );

class plghookinithookinitNUMBER extends JPluginInit
{
	function init( $config )
	{
		parent::init( $config );

		$hook = JHook::getInstance();
		$hook->addFilter( \'hook_do_test\', array( $this, \'do_test\' ) );
	}

	function do_test( &$counter )
	{
		$counter = $counter + $this->params->get( \'value\', false );
	}
}
			',
			'xml' => '<?xml version="1.0" encoding="utf-8"?>
<extension version="1.7" type="plugin" group="hookinit" method="upgrade">
	<name>plg_hookinit_hookinitNUMBER</name>
    <author>Rafael Corral</author>
    <authorEmail>support@corephp.com</authorEmail>
    <authorUrl>http://www.corephp.com/</authorUrl>
    <copyright></copyright>
    <license>GNU/GPL</license>
    <creationDate>24-January-2010</creationDate>
	<version>1.0.0</version>
	<description>some description, lulz</description>
	<files>
		<filename plugin="hookinitNUMBER">hookinitNUMBER.php</filename>
	</files>
	<config>
		<fields name="params">
			<fieldset name="basic">
				<field name="value" type="text"
					default="1"
					description="Value"
					label="Value" />
			</fieldset>
		</fields>
	</config>
</extension>'
		);

		$sql = "
		INSERT INTO `jos_extensions` (`name`, `type`, `element`, `folder`, `client_id`, `enabled`, `access`, `protected`, `manifest_cache`, `params`, `custom_data`, `system_data`, `checked_out`, `checked_out_time`, `ordering`, `state`)
		VALUES ('plg_hookinit_hookinitNUMBER', 'plugin', 'hookinitNUMBER', 'hookinit', 0, 1, 1, 0, '', '{\"value\":\"1\"}', '', '', 0, '0000-00-00 00:00:00', 0, 0)";

		$this->create_files( $files, $sql, 'hookinit' );
	}

	function createhook2()
	{
		$files = array(
			'php' => '<?php
defined(\'_JEXEC\') or die;

jimport( \'joomla.plugin.pluginconstruct\' );

class plghook2hook2NUMBER extends JPluginConstruct
{
	function __construct( $config )
	{
		parent::__construct( $config );

		JHook::addFilter( \'hook2_do_test\', array( $this, \'do_test\' ) );
	}

	function do_test( &$counter )
	{
		$counter = $counter + $this->params->get( \'value\', false );
	}
}
			',
			'xml' => '<?xml version="1.0" encoding="utf-8"?>
<extension version="1.7" type="plugin" group="hook2" method="upgrade">
	<name>plg_hook2_hook2NUMBER</name>
    <author>Rafael Corral</author>
    <authorEmail>support@corephp.com</authorEmail>
    <authorUrl>http://www.corephp.com/</authorUrl>
    <copyright></copyright>
    <license>GNU/GPL</license>
    <creationDate>24-January-2010</creationDate>
	<version>1.0.0</version>
	<description>some description, lulz</description>
	<files>
		<filename plugin="hook2NUMBER">hook2NUMBER.php</filename>
	</files>
	<config>
		<fields name="params">
			<fieldset name="basic">
				<field name="value" type="text"
					default="1"
					description="Value"
					label="Value" />
			</fieldset>
		</fields>
	</config>
</extension>'
		);

		$sql = "
		INSERT INTO `jos_extensions` (`name`, `type`, `element`, `folder`, `client_id`, `enabled`, `access`, `protected`, `manifest_cache`, `params`, `custom_data`, `system_data`, `checked_out`, `checked_out_time`, `ordering`, `state`)
		VALUES ('plg_hook2_hook2NUMBER', 'plugin', 'hook2NUMBER', 'hook2', 0, 1, 1, 0, '', '{\"value\":\"1\"}', '', '', 0, '0000-00-00 00:00:00', 0, 0)";

		$this->create_files( $files, $sql, 'hook2' );
	}

	function createhook2init()
	{
		$files = array(
			'php' => '<?php
defined(\'_JEXEC\') or die;

jimport( \'joomla.plugin.plugininit\' );

class plghook2inithook2initNUMBER extends JPluginInit
{
	function init( $config )
	{
		parent::init( $config );

		JHook::addFilter( \'hook2_do_test\', array( $this, \'do_test\' ) );
	}

	function do_test( &$counter )
	{
		$counter = $counter + $this->params->get( \'value\', false );
	}
}
			',
			'xml' => '<?xml version="1.0" encoding="utf-8"?>
<extension version="1.7" type="plugin" group="hook2init" method="upgrade">
	<name>plg_hook2init_hook2initNUMBER</name>
    <author>Rafael Corral</author>
    <authorEmail>support@corephp.com</authorEmail>
    <authorUrl>http://www.corephp.com/</authorUrl>
    <copyright></copyright>
    <license>GNU/GPL</license>
    <creationDate>24-January-2010</creationDate>
	<version>1.0.0</version>
	<description>some description, lulz</description>
	<files>
		<filename plugin="hook2initNUMBER">hook2initNUMBER.php</filename>
	</files>
	<config>
		<fields name="params">
			<fieldset name="basic">
				<field name="value" type="text"
					default="1"
					description="Value"
					label="Value" />
			</fieldset>
		</fields>
	</config>
</extension>'
		);

		$sql = "
		INSERT INTO `jos_extensions` (`name`, `type`, `element`, `folder`, `client_id`, `enabled`, `access`, `protected`, `manifest_cache`, `params`, `custom_data`, `system_data`, `checked_out`, `checked_out_time`, `ordering`, `state`)
		VALUES ('plg_hook2init_hook2initNUMBER', 'plugin', 'hook2initNUMBER', 'hook2init', 0, 1, 1, 0, '', '{\"value\":\"1\"}', '', '', 0, '0000-00-00 00:00:00', 0, 0)";

		$this->create_files( $files, $sql, 'hook2init' );
	}

	function create_files( $files, $sql, $type )
	{
		$amount = JRequest::getInt( 'amount' );

		if ( !$amount ) {
			die( '0' );
		}

		jimport( 'joomla.filesystem.file' );
		$db = JFactory::getDBO();

		// Delete all records in the DB for this plugin
		$db->setQuery("DELETE FROM #__extensions WHERE `type` = 'plugin' AND `folder` = '{$type}'");
		$db->query();

		// Delete plugin type folder
		JFolder::delete( JPATH_PLUGINS .DS. $type );

		for ( $i = 0; $i < $amount; $i++ ) {
			// Create files
			foreach ( $files as $ext => $content ) {
				$file = JPATH_PLUGINS .DS. $type .DS. $type .$i .DS. $type .$i. '.' . $ext;
				if ( JFile::exists( $file ) ) {
					continue;
				}
				JFile::write( $file, str_replace( 'NUMBER', $i, $content ) );
			}

			// Write sql
			$db->setQuery( str_replace( 'NUMBER', $i, $sql ) );
			$db->query();
		}

		die( '1' );
	}
}

function timer_start() {
	global $jd_timestart;

	$mtime = explode( ' ', microtime() );
	$mtime = $mtime[1] + $mtime[0];
	$jd_timestart = $mtime;

	return true;
}

function timer_stop( $display = 0, $precision = 3 ) {
	global $jd_timestart, $jd_timeend;

	$mtime = microtime();
	$mtime = explode( ' ', $mtime );
	$mtime = $mtime[1] + $mtime[0];
	$jd_timeend = $mtime;
	$timetotal = $jd_timeend - $jd_timestart;
	$r = number_format( $timetotal, $precision );

	if ( $display ) {
		echo $r;
	}

	return $r;
}

?>