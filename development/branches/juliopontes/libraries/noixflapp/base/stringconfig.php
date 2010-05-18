<?php
/**
 * @version		$Id: import.php 14549 2010-05-16 05:17:22Z joomila $
 * @package		NoixFLAPP.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * String Config Class.
 *
 * @package		NoixFLAPP.Framework
 * @subpackage	base
 * @since		1.0
 */
class JStringConfig
{
	/**
	 * Config String
	 * 
	 * @var unknown_type
	 */
	private $_config;
	
	/**
	 * Initialize String
	 */
	public function __construct()
	{
		$this->_config = '';
	}
	
	/**
	 * Concat string
	 */
	public function add($string)
	{
		$this->_config .= $string;
		
		return $this;
	}
	
	/**
	 * print config string
	 * 
	 */
	public function __toString(){
		return $this->_config;
	}
}