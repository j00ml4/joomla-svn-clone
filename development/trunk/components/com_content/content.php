<?php
/**
 * @version $Id$
 * @package Joomla
 * @subpackage Content
 * @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Require the com_content helper library
require_once (JPATH_COMPONENT . '/controller.php');
require_once (JPATH_SITE . '/components/com_content/content.helper.php');

// Component Helper
jimport('joomla.application.extension.component');

// Create the controller
$cParams 	= JSiteHelper::getControlParams();
$controller = new JContentController( 'display' );

// need to tell the controller where to look for views and models
$controller->setViewPath( dirname( __FILE__ ).DS.'views' );
$controller->setModelPath( dirname( __FILE__ ).DS.'models' );

// Set the default view name from the Request
$viewName = JRequest::getVar( 'view', $cParams->get( 'view_name', 'article' ) );

$controller->setViewName( $viewName, 'JContentView' );
// Register Extra tasks
$controller->registerTask( 'new', 		'edit' );
$controller->registerTask( 'apply', 	'save' );
$controller->registerTask( 'apply_new', 'save' );

// Perform the Request task
$controller->execute( $task );

// Redirect if set by the controller
$controller->redirect();
?>