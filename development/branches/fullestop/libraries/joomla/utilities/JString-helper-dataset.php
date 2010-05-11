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

class JStringTest_DataSet {
	/**
	 * Tests for JString::strpos.
	 *
	 * Each element contains $haystack, $needle, $offset, $expect,
	 *
	 * @var array
	 */
	static public $strposTests = array(
		array('missing',    'sing', 0,  3),
		array('missing',    'sting', 0,  false),
		array('missing',    'ing', 0,  4),
		array(' объектов на карте с',    'на карте', 0,  10),
		array('на карте с',    'на карте', 0,  0),
		array('на карте с',    'на каррте', 0,  false),
		array('на карте с',    'на карте', 2,  false)
	);

	static public $strrposTests = array(
		array('missing',    'sing', 0,  3),
		array('missing',    'sting', 0,  false),
		array('missing',    'ing', 0,  4),
		array(' объектов на карте с',    'на карте', 0,  10),
		array('на карте с',    'на карте', 0,  0),
		array('на карте с',    'на каррте', 0,  false),
		array('на карте с',    'карт', 2,  3)
	);

	static public $substrTests = array(
		array('Mississauga', 4, false, 'issauga')
	);

	static public $strtolowerTests = array(
		array('Joomla! Rocks', 'joomla! rocks')
	);

	static public $strtoupperTests = array(
		array('Joomla! Rocks', 'JOOMLA! ROCKS')
	);

	static public $strlenTests = array(
		array('Joomla! Rocks', 13)
	);
	static public $substr_replaceTests = array(
		array('hello world',    'earth', 6,  'hello earth'),
		
	);
	
	static public $strIreplaceTests = array(
		array('hello',    'peter', 'hello world',  'peter world'),
		array('demo',     'Demo Again', 'demo Test', 'Demo Test'),
		
	);
	
	static public $StrsplitTests = array(
		array('hello', 1, array('h', 'e', 'l', 'l', 'o' )),
		array('test', 2, array('te', 'st'))
		);
		
	static public $StrcasecmpTests = array(
		array('testString', 'testString', 0),
		array('demoTest', 'demo', 4),
		array('test', 'test', 0)
		);
		
	static public $StrcspnTests = array(
		array('demo string', 't', 0, 5, 6),
		array('demo string new', 'i',NULL, NULL, 8)
		); 
		
	static public $StristrTests = array(
		array('demo text', 'TEXT', 'text'),
		array('demo test', 'emo', 'emo test')
	);
	
	static public $StrrevTests = array(
		array('demo', 'omed'),
		array('demo test', 'test')
		);
	static public $StrspnTests = array(
		array('abcdefgh', 'abc', 1, 4, 2),
		array('demo text', 'ktext', NULL, NULL, 4)
		); 
	static public $LtrimTests = array(
		array('  demo text', FALSE, 'demo text' ),
		array("\tdemo demo", "\\t", 'demo demo')
		);
		
	static public $RtrimTests = array(
		array('demo text  ', FALSE, 'demo text' ),
		array("demo demo\t", "\t", 'demo demo')
		);
		
	static public $TrimTests = array(
		array('  demo text  ', FALSE, 'demo text' ),
		array("demo demo\t", "\\t", 'demo demo')
		);
		
	static public $UcfirstTests = array(
		array('demo text', 'Demo text' ),
		array('test', 'test')
		);
	
	static public $UcwordsTests = array(
		array('demo text', 'Demo Text' ),
		array('testing demo', 'testing demo')
		);
	
	static public $compliantTests = array(	
		array('demo', true),
		array('\xf0\x90\x28\xbc',true),
	    );
}
