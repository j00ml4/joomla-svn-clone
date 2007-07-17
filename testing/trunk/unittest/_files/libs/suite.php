<?php
/**
 * Description
 *
 * @package 	Joomla Unittest
 * @subpackage 	package_name
 * @author 		Rene Serradeil <serradeil@webmechanic.biz>
 * @copyright 	Copyright (c)2007, media++|webmechanic.biz
 * @license 	http://creativecommons.org/licenses/by-nd/2.5/ Creative Commons
 * @version 	0.0.1 $Rev$ $Date$
 * @filesource
 */

// $Id$

class JoomlaTestSuite extends TestSuite
{

	function JoomlaTestSuite($label = false) {
		parent::TestSuite($label);
	}

	function addClassTest($test_file)
	{
		$finfo  = UnitTestHelper::getInfoObject($test_file);

		if ( $finfo->enabled ) {
			if ($finfo->helper['location']) {
				require_once $finfo->helper['location'];
			}

			/* load testcase */
			require_once JUNITTEST_BASE .DIRECTORY_SEPARATOR. ltrim($test_file);

			/* add testclass */
			parent::addTestClass($finfo->testclass);
		}
	}

}