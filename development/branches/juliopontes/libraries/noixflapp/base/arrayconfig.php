<?php
/**
 * @version		$Id: import.php 14549 2010-05-16 05:17:22Z joomila $
 * @package		NoixFLAPP.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Array Config Class.
 *
 * @package		NoixFLAPP.Framework
 * @subpackage	base
 * @since		1.0
 */
class JArrayConfig
{
	/**
	 * Config var
	 * 
	 * @var unknown_type
	 */
	private $_config;
	
	/**
	 * Initialize config object
	 */
	public function __construct()
	{
		$this->_config = Array();
	}
	
	/**
	 * Add an specific value to array
	 * 
	 * @param unknown_type $key
	 * @param unknown_type $value
	 */
	public function add($key,$value)
	{
		$this->_config[$key] = $value;
		
		return $this;
	}
	
	/**
	 * Remove specific config
	 * 
	 * @param unknown_type $key
	 */
	public function remove($key)
	{
		unset($this->_config[$key]);
		
		return $this;
	}
	
	/**
	 * return a config array 
	 */
	public function getConfig()
	{
		return $this->_config;
	}
	
	/**
	 * return an array config string
	 */
	public function __toString()
	{
		foreach($this->_config as $key => $val){
			$params[] = $key.'='.$val;
		}
		return implode('&',$params);
	}
}