<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * The JXtended Social block controller
 *
 * @package		Joomla.Administrator
 * @subpackage	com_social
 * @since		1.6
 */
class SocialControllerBlock extends JController
{
	/**
	 * Dummy method to redirect the display method.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function display()
	{
		$this->setRedirect(JRoute::_('index.php?option=com_social'));
	}

	/**
	 * Method to block an IP or email address from posting.
	 *
	 * @return	void
	 * @since	2.0
	 */
	public function ip()
	{
		$type	= JRequest::getWord('block');
		$cid	= JRequest::getVar('cid', null, '', 'array');

		$model	= &$this->getModel('config');
		$result	= $model->block($type, $cid);
		if (JError::isError($result)) {
			$msg = $result->getMessage();
		} else {
			$msg = JText::sprintf('COM_SOCIAL_Items_Blocked', count($cid));
		}
		$this->setRedirect('index.php?option=com_social&view=comments', $msg);
	}

	/**
	 * Method to block an IP or email address from posting.
	 *
	 * @return	void
	 * @since	2.0
	 */
	public function user()
	{
		$type	= JRequest::getWord('block');
		$cid	= JRequest::getVar('cid', null, '', 'array');

		$model	= &$this->getModel('config');
		$result	= $model->block($type, $cid);
		if (JError::isError($result)) {
			$msg = $result->getMessage();
		} else {
			$msg = JText::sprintf('COM_SOCIAL_Items_Blocked', count($cid));
		}
		$this->setRedirect('index.php?option=com_social&view=comments', $msg);
	}
}