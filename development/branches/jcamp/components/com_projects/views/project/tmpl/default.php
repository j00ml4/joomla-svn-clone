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
<div class="item-page<?php echo $params->get('pageclass_sfx')?>">
	<h1><?php echo $this->item->title; ?></h1>
	<div class="project-desc">
		<?php echo $this->item->description; ?>
	</div>
	<?php dump($this->item); ?>
	<?php echo $this->loadTemplate('buttons'); ?>
</div>
</div>