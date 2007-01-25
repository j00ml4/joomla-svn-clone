<?php
/**
 * @version		$Id: weblink.php 6160 2007-01-03 07:43:52Z eddiea $
 * @package		Joomla
 * @subpackage	User
 * @copyright	Copyright (C) 2005 - 2007 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

jimport('joomla.application.component.model');

/**
 * User Component User Model
 *
 * @author Johan Janssens <johan.janssens@joomla.org>
 * @package		Joomla
 * @subpackage	User
 * @since 1.5
 */
class UserModelUser extends JModel
{
	/**
	 * User id
	 *
	 * @var int
	 */
	var $_id = null;

	/**
	 * User data
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct()
	{
		parent::__construct();

		$id = JRequest::getVar('id', 0, '', 'int');
		$this->setId($id);

	}

	/**
	 * Method to set the weblink identifier
	 *
	 * @access	public
	 * @param	int Weblink identifier
	 */
	function setId($id)
	{
		// Set weblink id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	/**
	 * Method to get a user
	 *
	 * @since 1.5
	 */
	function &getData()
	{
		// Load the weblink data
		if ($this->_loadData()) {
			//do nothing
		}

		return $this->_data;
	}

	/**
	 * Method to store the user data
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function store($data)
	{
		$row =& $this->getTable('user', 'JTable');
		
		$user =& JUser::getInstance($this->_id);
		$username = $user->get('username');

		// Bind the form fields to the user table
		if (!$user->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the web link table to the database
		if (!$user->save()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		// check if username has been changed
		if ( $username != $user->get('username') )
		{
			$session =& JFactory::getSession();
			$session->set('user', $user);
			
			$table = $this->getTable('session', 'JTable');
			$table->load($session->getId());
			$table->username = $user->get('username');
			$table->store();
		}
		
		return true;
	}

	/**
	 * Method to load user data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$this->_data =& JFactory::getUser();
			return (boolean) $this->_data;
		}
		return true;
	}
}
?>