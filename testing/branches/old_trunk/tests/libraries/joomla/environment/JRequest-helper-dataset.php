<?php
/**
 * @version		$Id$
 * @package		Joomla.UnitTest
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU General Public License
 */

class JRequestTest_DataSet {
	/**
	 * Tests for getVar.
	 *
	 * Each element contains $name, $default, $hash, $type, $mask, $expect,
	 * array of JFilterInput expectations.
	 *
	 * Note that this is a JRequest test, not a JFilterInput test. Cases
	 * that exersize data types and string cleaning belong in a test of the
	 * filtering code.
	 *
	 * @var array
	 */
	static public $getVarTests = array(
		//
		// Default values tests
		//
		array(
			'missing',    null,       'default',  'none', 0, null, array(),
		),
		array(
			'missing',    'absent',   'default',  'none', 0, 'absent',
		),
		/*
		 * Data source tests
		 */
		array(
			'tag',  null,       'default',  'none', 0, 'from _REQUEST',
		),
		array(
			'tag',  null,       'post',     'none', 0, 'from _POST',
		),
		array(
			'tag',  null,       'method',   'none', 0, 'from _POST',
		),
		array(
			'tag',  null,       'request',  'none', 0, 'from _REQUEST',
		),
		array(
			'tag',  null,       'invalid',  'none', 0, 'from _REQUEST',
		),
		array(
			'tag',  null,       'cookie',   'none', 0, 'from _COOKIE',
		),
		array(
			'tag',  null,       'files',    'none', 0, 'from _FILES',
		),
		array(
			'tag',  null,       'env',      'none', 0, 'from _ENV',
		),
		array(
			'tag',  null,       'server',   'none', 0, 'from _SERVER',
		),
		/*
		 * Test flags
		 */
		array(
			'trim_test',  null,       'default',  'none', 0, 'has  whitespace',
		),
		array(
			'trim_test',  null,       'default',  'none', JREQUEST_NOTRIM, ' has  whitespace ',
		),
		array(
			'raw_test',  null,       'default',  'none', JREQUEST_ALLOWRAW, '<body>stuff</body>',
		),
		array(
			'html_test',  null,       'default',  'none', JREQUEST_ALLOWHTML, '/* Script Code */<h1>stuff</h1>',
		),
	);

	static function initSuperGlobals()
	{
		$_GET = array(
			'tag' => 'from _GET',
		);
		$_COOKIE = array(
			'tag' => 'from _COOKIE',
		);
		$_ENV = array(
			'tag' => 'from _ENV',
		);
		$_FILES = array(
			'tag' => 'from _FILES',
		);
		$_POST = array(
			'tag' => 'from _POST',
		);
		$_SERVER = array(
			'tag' => 'from _SERVER',
			'REQUEST_METHOD' => 'POST',
		);
		/**
		 * Merge get and post into request.
		 */
		$_REQUEST = array_merge($_GET, $_POST);
		$_REQUEST['tag'] = 'from _REQUEST';
		$_REQUEST['raw_test'] = '<body>stuff</body>';
		$_REQUEST['html_test'] = '<script>/* Script Code */</script><h1 onclick="alert(document.cookie);">stuff</h1>';
		$_REQUEST['trim_test'] = ' has  whitespace ';
	}
}