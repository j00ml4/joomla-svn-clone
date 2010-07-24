<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	Projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * @package		Joomla.Site
 * @subpackage	Projects
 * @since		1.6
 */

class ProjectsControllerProjects extends JController{

	/**
	 * Constructor.
	 *
	 * @param	array An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask('back',		'back');
	}
	
	/**
	 * Method to go back to list of portfolios
	 * 
	 */
	public function back()
	{
		$app = JFactory::getApplication();
		$app->setUserState('portfolio.id', null);

		$this->setRedirect(JRoute::_('index.php?option=com_projects&view=portfolios&layout=gallery&Itemid='.ProjectsHelper::getMenuItemId(),false));
	}
}