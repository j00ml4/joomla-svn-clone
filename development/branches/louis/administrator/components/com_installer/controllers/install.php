<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */

// No direct access
defined('_JEXEC') or die;

class InstallerControllerInstall extends JController {
	/**
	 * Install an extension
	 *
	 * @return	void
	 * @since	1.5
	 */
	public function install()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$model	= &$this->getModel('install');
		if ($model->install())
		{
			$cache = &JFactory::getCache('mod_menu');
			$cache->clean();
			// TODO: Reset the users acl here as well to kill off any missing bits
		}
		$this->setRedirect(JRoute::_('index.php?option=com_installer&view=install',false));
	}
}
