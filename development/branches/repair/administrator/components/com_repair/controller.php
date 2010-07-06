<?php
/**
 * @version		$Id$
 * @package		Repair
 * @subpackage	com_repair
 * @copyright	Copyright 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Repair Component Controller
 *
 * @package		Repair
 * @subpackage	com_repair
 * @since		1.0
 */
class RepairController extends JController
{
	/**
	 * @var		string	The default view.
	 * @since	1.0
	 */
	protected $default_view = 'about';

	/**
	 * Override the display method for the controller.
	 *
	 * @return	void
	 * @since	1.0
	 */
	function display()
	{
		// Load the component helper.
		require_once JPATH_COMPONENT.'/helpers/repair.php';

		// Display the view.
		parent::display();
	}
}