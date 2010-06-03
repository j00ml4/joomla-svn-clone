<?php
/**
 * @version		$Id: weblink.php 17248 2010-05-25 01:58:09Z eddieajau $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * @package		Joomla.Site
 * @subpackage	com_weblinks
 * @since		1.5
 */
class ProjectsControllerProject extends JControllerForm
{
	/**
	 * Overload Context
	 */
	protected $context = 'com_projects.edit.project';

	/**
	 * @since	1.6
	 */
	protected $view_item = 'project';

	/**
	 * @since	1.6
	 */
	protected $view_list = 'portfolio';

	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix;
	
	/**
	 * Constructor
	 *
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
}