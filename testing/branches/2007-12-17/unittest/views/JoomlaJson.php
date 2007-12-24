<?php
/**
 * Joomla! v1.5 UnitTest Platform
 *
 * @version $Id$
 * @package Joomla
 * @subpackage UnitTest
 */

require_once(JUNIT_VIEWS . '/JoomlaPhp.php');

class JoomlaJson extends JoomlaPhp
{
	function joomlaOutput()
	{
		if (function_exists('json_encode')) {
			return json_encode($this->_joomlaTests);
		}

		require_once(JUNIT_LIBS .'/JSON.php');
		$json = new Services_JSON();

		return $json->encode($this->_joomlaTests);
	}

}
