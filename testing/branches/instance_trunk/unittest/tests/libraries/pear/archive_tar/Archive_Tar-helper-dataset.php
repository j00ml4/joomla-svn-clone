<?php
/**
 * Joomla! v1.5 Unit Test Facility
 *
 * @package Joomla
 * @subpackage UnitTest
 * @copyright Copyright (C) 2005 - 2008 Open Source Matters, Inc.
 * @version $Id: $
 *
 */

class Archive_TarTest_DataSet {
	/**
	 * Tests for JString::strpos.
	 *
	 * Each element contains $haystack, $needle, $offset, $expect,
	 *
	 * @var array
	 */
	static public $archive_TarData = array(
		array('test.tar.gz', null, true, 'gz' ),
		array('test.tar.gz', 'bz2', true, 'bz2' ),
		array('test.tar', 'bz2', true, 'bz2' ),
		array('test.tar.bz2', null, true, 'bz2' ),
		array('testdetect.tar', null, true, 'gz' ),
	);

	static public $createModifyData = array(
		array(
			array(
				'en-GB.plg_authentication_jfoobar_joomlaemail.ini',
				'jfoobar_joomlaemail.php',
				'jfoobar_joomlaemail.xml',
				'plgJFooBarAuthJoomlaEmail.tar.gz'
			), '', ''
		),
		array(
			array(
				'en-GB.plg_authentication_jfoobar_joomlaemail.ini',
				'jfoobar_joomlaemail.php',
				'jfoobar_joomlaemail.xml',
				'semantics.jpg',
				'index.html',
				'plgJFooBarAuthJoomlaEmail.tar.gz'
			), '', ''
		),
		array(
			'en-GB.plg_authentication_jfoobar_joomlaemail.ini jfoobar_joomlaemail.php jfoobar_joomlaemail.xml semantics.jpg index.html',
			'', ''
		),
		array(
			'en-GB.plg_authentication_jfoobar_joomlaemail.ini jfoobar_joomlaemail.php jfoobar_joomlaemail.xml semantics.jpg index.html',
			'test/', '/var/'
		),
		array(
			'fileicons',
			'test/', ''
		)

	);

}
