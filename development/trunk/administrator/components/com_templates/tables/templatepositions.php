<?php
/**
* @version		$Id$
* @package		Joomla
* @subpackage	Templates
* @copyright	Copyright (C) 2005 - 2007 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
* @package		Joomla
* @subpackage	Templates
*/
class TableTemplatePositions extends JTable
{
	var $id				= null;
	var $position		= null;
	var $description	= null;

	function __construct(&$db) {
		parent::__construct( '#__template_positions', 'id', $db );
	}
}
?>
