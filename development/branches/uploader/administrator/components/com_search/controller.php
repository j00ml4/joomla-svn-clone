<?php
/**
 * @version		$Id: controller.php 11952 2009-06-01 03:21:19Z robs $
 * @package		Joomla.Administrator
 * @subpackage	Search
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * @package		Joomla.Administrator
 * @subpackage	Search
 */
class SearchController extends JController
{
	/**
	 * Show Search Statistics
	 */
	function display()
	{
		$model	= &$this->getModel('Search');
		$view   = &$this->getView('Search');
		$view->setModel($model, true);
		$view->display();
	}

	/**
	 * Reset Statistics
	 */
	function reset()
	{
		$model	= &$this->getModel('Search');
		$model->reset();
		$this->setRedirect('index.php?option=com_search');
	}
}