<?php
/**
 * @version		$Id$
 * @package		Joomla.UnitTest
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU General Public License
 */

class testCallbackHandler {

	public function instanceCallback($arg1, $arg2) {
		echo $arg1;
		return $arg2;
	}

	static function staticCallback($arg1, $arg2) {
		echo $arg1;
		return $arg2;
	}

}

function testCallbackHandlerFunc($arg1, $arg2) {
	echo $arg1;
	return $arg2;
}
