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
	<div class="item-page">
		<div class="item">
			<h1><?php echo $this->item->title; ?></h1>
			<div class="category-desc">
				<?php echo $this->item->description; ?>
			</div>
			
			<?php echo $this->loadTemplate('buttons'); ?>
		</div>
	</div>

	<div class="projects-right-column">
		<ul>
		<li>what here?</li>
		<li>what here?</li>
		<li>what here?</li>
		<li>what here?</li>
		</ul>
	</div>
</div>