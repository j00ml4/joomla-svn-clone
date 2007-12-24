<?php
/**
 * Joomla! v1.5 UnitTest Platform basic list mode view.
 *
 * @package Joomla
 * @subpackage UnitTest
 * @author         Rene Serradeil <serradeil@webmechanic.biz>
 * @copyright     Copyright (c)2007, media++|webmechanic.biz
 * @license     http://creativecommons.org/licenses/by-nd/2.5/ Creative Commons
 * @version $Id$
 * @filesource
 */

/**
 *
 */
class UnitTestView
{
var $title  = 'Joomla! v1.5 UnitTest Platform';
var $stats;
var $phpenv;

	function UnitTestView()
	{
		$this->stats  = array();
	}

	function &getInstance($format)
	{
		static $instances = array();

		if (!isset($instances['format'])) {
			$instances['format'] =& new UnitTestView();
		}
	}



}
