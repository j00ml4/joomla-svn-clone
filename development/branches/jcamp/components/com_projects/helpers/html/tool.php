<?php
/**
 * @version		$Id: content.php 17085 2010-05-16 00:03:00Z severdia $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * @package		Joomla.Administrator
 * @subpackage	com_content
 */
abstract class JHtmlTool
{
	
	public function progressBar($percent, $text=null, $class_sfx='')
	{
		
		return '<div class="progress-bar'. $class_sfx .'">
			<div class="progress" style="width:'. $percent .'%;"></div>
			<div class="info">'. (empty($text)? $percent: $text) .'</div>
		</div>';
	}	
}