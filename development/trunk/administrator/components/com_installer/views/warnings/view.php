<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	Menus
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 */

// no direct access
defined('_JEXEC') or die;

/**
 * Extension Manager Templates View
 *
 * @package		Joomla.Administrator
 * @subpackage	Installer
 * @since		1.5
 */

include_once(dirname(__FILE__).DS.'..'.DS.'default'.DS.'view.php');

class InstallerViewWarnings extends InstallerViewDefault
{
	function display($tpl=null)
	{
		$items		= &$this->get('Items');
		$this->assignRef('messages', $items);
		parent::display($tpl);
	}
}