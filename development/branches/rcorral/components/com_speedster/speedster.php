<?php
if ( !defined('_JEXEC') ) { die( 'Direct Access to this location is not allowed.' ); }
/**
 * @version		$Id: jphoto.php 1 2009-10-27 20:56:04Z rafael $
 * @package		JPhoto
 * @copyright	Copyright (C) 2009 'corePHP' / corephp.com. All rights reserved.
 * @license		GNU/GPL, see LICENSE.txt
 */

require_once JPATH_COMPONENT .DS. 'controller.php';

timer_start();

$classname = 'SpeedsterController';
$controller = new $classname();
$controller->execute( JRequest::getVar( 'task' ) );
$controller->redirect();

echo '<pre>Execution time (seconds): ' . timer_stop() . '</pre>';
?>