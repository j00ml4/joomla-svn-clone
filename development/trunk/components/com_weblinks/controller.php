<?php
/**
 * @version		$Id$
 * @package		Joomla
 * @subpackage	Content
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Weblinks Component Controller
 *
 * @package		Joomla
 * @subpackage	Weblinks
 * @since 1.5
 */
class WeblinksController extends JController
{
	/**
	 * Method to show a weblinks view
	 *
	 * @access	public
	 * @since	1.5
	 */
	function display()
	{
		// Set a default view if none exists
		if (! JRequest::getCmd('view')) {
			JRequest::setVar('view', 'categories');
		}

		//update the hit count for the weblink
		if (JRequest::getCmd('view') == 'weblink')
		{
			$model = &$this->getModel('weblink');
			$model->hit();
		}

		parent::display();
	}

	/**
	* Report a weblink
	*
	* @acces public
	* @since 1.6
	*/

	function report()
	{
		$model = &$this->getModel('weblink');
		if ($model->report()) {
			$msg = JText::_("Link Reported");
		}
		else {
			$msg = JText::_("Error reporting link");
		}

		$this->setRedirect(JRoute::_('index.php?option=com_weblinks'), $msg);
	}
}