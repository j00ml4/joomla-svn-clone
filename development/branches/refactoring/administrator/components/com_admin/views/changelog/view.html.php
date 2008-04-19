<?php
/**
* @version		$Id$
* @package		Joomla
* @subpackage	Admin
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Admin component
 *
 * @static
 * @package		Joomla
 * @subpackage	Admin
 * @since 1.5
 */
class AdminViewChangelog extends JView
{
	function display($tpl = null)
	{
		$this->assign( 'changelog', $this->get( 'Changelog' ) );
		parent::display($tpl);
	}
}