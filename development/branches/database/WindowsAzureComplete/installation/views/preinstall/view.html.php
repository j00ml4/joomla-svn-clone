<?php
/**
 * @version		$Id: view.html.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Installation
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');
jimport('joomla.html.html');

/**
 * The HTML Joomla Core Pre-Install View
 *
 * @package		Joomla.Installation
 * @since		1.6
 */
class JInstallationViewPreinstall extends JView
{
	/**
	 * Display the view
	 *
	 * @param	string
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->settings		= $this->get('PhpSettings');
		$this->options		= $this->get('PhpOptions');
		$this->sufficient	= $this->get('PhpOptionsSufficient');
		$this->version		= new JVersion;
		
		$arr = 0;
		$arr = ini_get_all('sqlsrv');
		if(count($arr) > 0) {
			$obj = new stdClass();
			$obj->label = 'SQLAzure Driver configured!';
			$obj->state = 1;
			$obj->notice = '';
			$this->options[] = $obj;
		}

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		parent::display($tpl);
	}
}