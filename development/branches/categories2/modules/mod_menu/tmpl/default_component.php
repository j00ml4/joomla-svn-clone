<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_menu
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.
$class = $item->params->get('menu-anchor_css', '') ? 'class="'.$item->params->get('menu-anchor_css', '').'" ' : ''; 
switch ($item->browserNav) :
	default:
	case 0:
?><a <?php echo $class; ?>href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a><?php
		break;
	case 1:
		// _blank
?><a <?php echo $class; ?>href="<?php echo $item->link; ?>" target="_blank"><?php echo $item->title; ?></a><?php
		break;
	case 2:
		// window.open
		$attribs = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes';
?><a <?php echo $class; ?>href="<?php echo $item->link.'&tmpl=component'; ?>" onclick="window.open(this.href,'targetWindow','<?php echo $attribs;?>');return false;"><?php echo $item->title; ?></a><?php
		break;
endswitch;
