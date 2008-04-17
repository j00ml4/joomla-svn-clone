<?php
/**
 * @version		$Id: $
 * @package		Joomla
 * @subpackage	Sections
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();


/**
 * @package		Joomla
 * @subpackage	Sections
 */
class TableSection extends JTable
{
	/** @var int Primary key */
	var $id 				= null;
	/** @var string */
	var $name 				= null;
	/** @var string */
	var $alias				= null;
	/** @var string */
	var $title 				= null;
	/** @var string */
	var $image 				= null;
	/** @var string */
	var $image_position		= null;
	/** @var string */
	var $scope	 			= null;
	/** @var string */
	var $description		= null;
	/** @var string */
	var $count 				= 0;
	/** @var int */
	var $published 			= 0;
	/** @var int */
	var $checked_out 		= 0;
	/** @var datetime */
	var $checked_out_time 	= 0;
	/** @var int */
	var $ordering 			= null;
	/** @var string */
	var $params 				= null;
	/** @var int */
	var $access 				= null;

	/**
	* @param database A database connector object
	*/
	function __construct(&$db)
	{
		parent::__construct( '#__sections', 'id', $db );
	}

	/**
	 * Overloaded check function
	 *
	 * @access public
	 * @return boolean
	 * @see JTable::check
	 * @since 1.5
	 */
	function check()
	{
		$alias = JFilterOutput::stringURLSafe($this->name);

		if(empty($this->alias) || $this->alias === $alias ) {
			$this->alias = $alias;
		}

		return true;
	}
}