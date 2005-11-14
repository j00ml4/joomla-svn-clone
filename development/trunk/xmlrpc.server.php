<?php
/**
* @version $Id$
* @package Joomla
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/** Set flag that this is a parent file */
define( "_VALID_MOS", 1 );

// crank up Joomla!
require_once( 'configuration.php' );
//if (!$mosConfig_xmlrpc_server) {
//	die( 'XML-RPC server not enabled.' );
//}
require_once( JPATH_SITE .'includes/joomla.php' );

$mainframe = new mosMainFrame( $database, '' );
$mainframe->initSession();

/** get the information about the current user from the sessions table */
$my = $mainframe->getUser();


/**
* CUSTOM HANDLER FOR METHOD NOT FOUND
*/
function domXmlRpcFault( &$server, $methodName, &$params ) {
	//one option would be to return a custom fault
	//(implementation defined errors should be
	//in the range  -32099 .. -32000 according to
	//Specification for Fault Code Interoperability)
	$server->serverError = 123456;
	$server->serverErrorString = 'I don\'t know about ' . $methodName;
	return $server->raiseFault();
} //faultExample


// Includes the required class file for the XML-RPC Server
jimport('domit.dom_xmlrpc_server.php' );
jimport('domit.dom_xmlrpc_fault.php' );

$xmlrpcServer = new dom_xmlrpc_server();
$xmlrpcServer->setMethodNotFoundHandler( 'domXmlRpcFault' );

// load all available remote calls
$_MAMBOTS->loadBotGroup( 'xmlrpc' );
$allCalls = $_MAMBOTS->trigger( 'onGetWebServices' );

// add all calls to the connector object
foreach ($allCalls as $calls) {
    foreach ($calls as $call) {
	    $xmlrpcServer->addMethod( new dom_xmlrpc_method( $call ) );
	}
}

// pass individual arguments to the called method
$xmlrpcServer->tokenizeParams( true );

// process the call
$xmlrpcServer->receive();
?>
