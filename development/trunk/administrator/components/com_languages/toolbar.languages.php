<?php
/**
* @version $Id$
* @package Joomla
* @subpackage Languages
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( JApplicationHelper::getPath( 'toolbar_html' ) );

$client	= JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));

switch ($task) {

	default:
		TOOLBAR_languages::_DEFAULT($client);
		break;
}
?>