<?php
/**
 * @version		$Id: mod_media.php 15620 2010-03-26 20:35:56Z amangautam $
 * @package		Joomla.Site
 * @subpackage	mod_denvideo_16
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// soon we'll need a helper copy from the mod_denvideo
//require_once dirname(__FILE__).DS.'helper.php';

// Standard for better reading
$media 		= $params->get('media','');// change the param name to media instead
$width		= $params->get('width');
$height		= $params->get('height');
$autostart 	= $params->get('autostart');


// every think that you print here is the out put of the module
/* you could 

echo '<p>video = '.$video.'</p>';
echo '<p>width = '.$width.'</p>';
// But dont do this!
*/



// You just forgot this magical line
require JModuleHelper::getLayoutPath('mod_media', $params->get('layout', 'default'));
/* this is the best think for designers, 
if if find on your template  /html/mod_media/layoutname.php it replace the core layout witout hacks
if not it you the core layout on /tmpl/layoutname.php
*/
