<?php
/**
 * @version $Id$
 * @package Joomla
 * @subpackage Content
 * @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

define( 'JPATH_COM_CONTENT', dirname( __FILE__ ) );

// Require the com_content helper library
require_once (JPATH_COM_CONTENT . '/controller.php');
require_once (JApplicationHelper::getPath('helper', 'com_content'));

// Require the MVC libraries
jimport('joomla.application.model');
jimport('joomla.application.view');
jimport('joomla.application.extension.component');



// Create the controller
$cParams 	= JComponentHelper::getControlParams();
$controller = & new JContentController( $mainframe, 'view' );

// need to tell the controller where to look for views and models
$controller->setViewPath( dirname( __FILE__ ) . DS . 'view' );
$controller->setModelPath( dirname( __FILE__ ) . DS . 'model' );

// Set the default view name from the Request
$viewName = JRequest::getVar( 'view', $cParams->get( 'view_name', 'article' ) );

// URL converter/interceptor
switch ($task)
{
	case 'section':
		$newTask = '';
		$viewName = 'section';
		$tmplName = 'list';
		break;

	case 'blogsection':
		$newTask = '';
		$viewName = 'section';
		$tmplName = 'blog';
		break;

	case 'blogcategorymulti': // ??
	case 'category':
		$newTask = '';
		$viewName = 'category';
		$tmplName = 'table';
		break;

	case 'blogcategory':
		$newTask = '';
		$viewName = 'category';
		$tmplName = 'blog';
		break;

	default:
		$newTask = $task;
		$tmplName = '';
}

$controller->setViewName( $viewName, 'com_content', 'JContentView' );
// Register Extra tasks
$controller->registerTask( 'new', 				'edit' );
$controller->registerTask( 'apply', 			'save' );
$controller->registerTask( 'apply_new', 		'save' );

// Perform the Request task
$controller->execute( $newTask );

// Redirect if set by the controller
$controller->redirect();
?>