<?php
/**
 * @version		$Id: banner.php 11952 2009-06-01 03:21:19Z robs $
 * @package		Joomla.Administrator
 * @subpackage	Banners
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * @package		Joomla.Administrator
 * @subpackage	Banners
 */
class TableBanner extends JTable
{
	/** @var int */
	var $bid				= null;
	/** @var int */
	var $cid				= null;
	/** @var string */
	var $type				= '';
	/** @var string */
	var $name				= '';
	/** @var string */
	var $alias				= '';
	/** @var int */
	var $imptotal			= 0;
	/** @var int */
	var $impmade			= 0;
	/** @var int */
	var $clicks				= 0;
	/** @var string */
	var $imageurl			= '';
	/** @var string */
	var $clickurl			= '';
	/** @var date */
	var $date				= null;
	/** @var int */
	var $showBanner			= 0;
	/** @var int */
	var $checked_out		= 0;
	/** @var date */
	var $checked_out_time	= 0;
	/** @var string */
	var $editor				= '';
	/** @var string */
	var $custombannercode	= '';
	/** @var int */
	var $catid				= null;
	/** @var string */
	var $description		= null;
	/** @var int */
	var $sticky				= null;
	/** @var int */
	var $ordering			= null;
	/** @var date */
	var $publish_up			= null;
	/** @var date */
	var $publish_down		= null;
	/** @var string */
	var $tags				= null;
	/** @var string */
	var $params				= null;

	function __construct(&$_db)
	{
		parent::__construct('#__banner', 'bid', $_db);


		$now = &JFactory::getDate();
		$this->set('date', $now->toMySQL());
	}

	function clicks()
	{
		$query = 'UPDATE #__banner'
		. ' SET clicks = (clicks + 1)'
		. ' WHERE bid = ' . (int) $this->bid
		;
		$this->_db->setQuery($query);
		$this->_db->query();
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
		// check for valid client id
		if (is_null($this->cid) || $this->cid == 0) {
			$this->setError(JText::_('BNR_CLIENT'));
			return false;
		}

		// check for valid name
		if (trim($this->name) == '') {
			$this->setError(JText::_('BNR_NAME'));
			return false;
		}

		if (empty($this->alias)) {
			$this->alias = $this->name;
		}
		$this->alias = JFilterOutput::stringURLSafe($this->alias);

		/*if (trim($this->imageurl) == '') {
			$this->setError(JText::_('BNR_IMAGE'));
			return false;
		}
		if (trim($this->clickurl) == '' && trim($this->custombannercode) == '') {
			$this->setError(JText::_('BNR_URL'));
			return false;
		}*/

		return true;
	}
}
?>
