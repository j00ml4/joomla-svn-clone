<?php
/**
 * @version		$Id$
 * @package		Joomla.Framework
 * @subpackage	Cache
 * @copyright	Copyright (C) 2010 Klas Berlič
 */

// No direct access
defined('JPATH_BASE') or die();

/**
 * Client helper class
 *
 * @static
 * @package		Joomla.Framework
 * @subpackage	Client
 * @since		1.6
 */

class JCacheStorageHelper
{

	public $group = '';
	public $size = 0;
	public $count = 0;


	public function updateSize($size,$group)
	{	
		$this->group = $group;
		$this->size = number_format($this->size + $size, 2);
		$this->count++;
	}
	
}