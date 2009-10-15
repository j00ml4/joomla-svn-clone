<?php
/**
 * @version		$Id: view.php 12344 2009-06-24 11:28:31Z eddieajau $
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

include_once dirname(__FILE__).DS.'..'.DS.'default'.DS.'view.php';

/**
 * Extension Manager Templates View
 *
 * @package		Joomla.Administrator
 * @subpackage	com_installer
 * @since		1.5
 */
class InstallerViewWarnings extends InstallerViewDefault
{
	function display($tpl=null)
	{
		$items		= &$this->get('Items');
		$this->assignRef('messages', $items);
		parent::display($tpl);
	}
}