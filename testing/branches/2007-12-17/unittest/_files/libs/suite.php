<?php
/**
 * Description
 *
 * @package Joomla
 * @subpackage UnitTest
 * @author         Rene Serradeil <serradeil@webmechanic.biz>
 * @copyright     Copyright (c)2007, media++|webmechanic.biz
 * @license     http://creativecommons.org/licenses/by-nd/2.5/ Creative Commons
 * @version $Id: $
 * @filesource
 */

// $Id$

class JUnit_Framework_TestSuite extends PHPUnit_Framework_TestSuite
{

	function addClassTest($test_file)
	{
		$finfo  = UnitTestHelper::getInfoObject($test_file);

		if ($finfo->enabled) {
			if ($finfo->helper['location']) {
				require_once $finfo->helper['location'];
			}

			/* load testcase */
			require_once JUNIT_BASE .DIRECTORY_SEPARATOR. ltrim($test_file);

			/* add testclass */
			parent::addTestClass($finfo->testclass);
		}
	}

}