<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	com_projects
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die;

// Vars
$params =  $this->params;
$pageClass = $this->escape($params->get('pageclass_sfx'));
?>

<div class="projects<?php echo $pageClass;?> blog<?php echo $pageClass;?>">
	<div class="projects-left-column">
		<div class="projects-content">
			<h1><?php echo JText::_('COM_PROJECTS_PORFOLIO_LIST_HEADER');?></h1>
				<?php foreach ($this->items as $item) :
					$this->item = $item;
					echo $this->loadTemplate('item');
			endforeach; ?>
			<?php if(($params->def('show_pagination', 1) == 1 || ($params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) { ?>
			<div class="pagination">
				<?php  if ($this->params->def('show_pagination_results', 1)) { ?>
				<p class="counter">
					<?php echo $this->pagination->getPagesCounter(); ?>
				</p>
			<?php } ?>
			<?php echo $this->pagination->getPagesLinks(); ?>
			</div>
			<?php  } ?>
		</div>
	</div>
	
	<div class="projects-right-column">
		<div class="projects-content">
			<?php echo JText::_('COM_PROJECTS_PROJECTS_GREETING');?><br /><br />
			<a href="<?php echo JRoute::_('index.php?option=com_projects&view=project&layout=form'); ?>">
			<?php echo JText::_('COM_PROJECTS_PROJECT_ADD');?></a><br />
		</div>
	</div>
</div>