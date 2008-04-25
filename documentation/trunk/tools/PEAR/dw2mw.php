<?php
/**
 * Description
 *
 * @package 	Joomla-Wiki
 * @subpackage 	Migration
 * @author 		Rene Serradeil <serradeil@webmechanic.biz>
 * @copyright 	Copyright (c)2007, media++|webmechanic.biz
 * @version 	0.0.1 $Id$
 * @filesource
 */

/* home of PEAR.php */
define('PEAR_PATH', dirname(__FILE__));

require_once 'Text/Wiki.php';
/* DokuWiki Plugins */
$rules = array(
	'Discussion', 	// this is to REMOVE the ~~DISCUSSION~~ tag
	'Japi', 		// contains some valuable information ;)
	);
$wiki = &Text_Wiki::factory($parser, $rules);

$api = new stdClass;
$wiki->setRenderConf('Mediawiki', 'Japi', 'API', $api );

$wiki->addPath('parse',  PEAR_PATH . '/Text/Wiki/Parse/Doku');
$wiki->addPath('render', PEAR_PATH . '/Text/Wiki/Render/Mediawiki');

# adjust or put in a loop to read multiple files
$filesource = file_get_contents('../references/joomla.framework/jfactory.txt');

$result = $wiki->transform($filesource, 'Mediawiki');
if ( $result instanceOf PEAR_Error ) {
	echo $result->message, PHP_EOL, get_include_path(), PHP_EOL;
	exit;
}

// remove trailing crap
$wiki->output = trim($wiki->output);
$wiki->output = trim($wiki->output, '-'); // final horiz. rule

/* API ref cleanup */
// misc. table misbehavior
$wiki->output = str_replace(
			array('Method| Description',  PHP_EOL.PHP_EOL.'|-', PHP_EOL.PHP_EOL.'|}'),
			array('Method !! Description', PHP_EOL.'|-',         PHP_EOL.'|}'),
			$wiki->output);


file_put_contents('../_cache/Framework/JFactory.wikitxt');

// eof.
