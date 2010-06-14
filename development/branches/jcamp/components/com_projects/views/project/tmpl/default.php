<?php
/**
 * @version		$Id:
 * @package		Joomla.Site
 * @subpackage	com_projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Vars
$params =  $this->params;
$pageClass = $this->params->get('pageclass_sfx');
?>
<div class="projects<?php echo $pageClass?>">
	<div class="projects-left-column-3">
		<div class="projects-content">
			<h1><?php echo $this->item->title; ?></h1>
			<?php echo $this->loadTemplate('buttons'); ?>
		</div>
	</div>
	<div class="projects-middle-column-3">
		<div class="projects-content">
			<h1><?php echo $this->item->title; ?></h1>
			<?php echo $this->loadTemplate('buttons'); ?>
		</div>
	</div>
	<div class="projects-right-column-3">
		<div class="projects-content">
			<h1><?php echo $this->item->title; ?></h1>
			<?php echo $this->loadTemplate('buttons'); ?>
		</div>
	</div>
</div>