<?php
/**
* @package domit-xmlparser
* @copyright (C) 2004 John Heinstein. All rights reserved
* @license http://www.gnu.org/copyleft/lesser.html LGPL License
* @author John Heinstein <johnkarl@nbnet.nb.ca>
* @link http://www.engageinteractive.com/domit/ DOMIT! Home Page
* DOMIT! is Free Software
**/

/** The file system path to the domit library */

if (!defined( 'DOMIT_INCLUDE_PATH' )) {
	define('DOMIT_INCLUDE_PATH', (dirname(__FILE__) . "/"));
}
require_once(DOMIT_INCLUDE_PATH . 'xml_domit_parser.php');
?>
