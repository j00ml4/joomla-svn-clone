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
	<div class="projects-left-column">
		<div class="projects-content">
			<h1><?php echo $this->item->title; ?></h1>
			<div class="category-desc">
				<?php echo JText::_('COM_PROJECTS_FIELD_DESCRIPTION_LABEL')?>:
				<div class="projects-content projects-frame projects-space">
					<?php echo $this->item->description; ?>
				</div>
				<div class="clr">&nbsp;</div>
			</div>
			
			<form class="projects-frame" id="adminForm">
				<div class="projects-content">
					<div class="formelm"><label><?php echo JText::_('COM_PROJECTS_FIELD_START_AT_LABEL')?>:</label>
						<?php echo JFactory::getDate($this->item->start_at)->toFormat('%d.%m.%Y');?>					
					</div>
					<div class="formelm"><label><?php echo JText::_('COM_PROJECTS_FIELD_FINISH_AT_LABEL')?>:</label>
						<?php echo JFactory::getDate($this->item->finish_at)->toFormat('%d.%m.%Y');?>
					</div>
					<div class="formelm"><label><?php echo JText::_('COM_PROJECTS_FIELD_STATUS_LABEL')?>:</label>50 %</div>
				</div>
			</form>
			<?php echo $this->loadTemplate('buttons'); ?>
		</div>
	</div>

	<div class="projects-right-column">
		<?php echo $this->loadTemplate('documents');?>
		<?php echo $this->loadTemplate('team');?>
	</div>
	<?php echo $this->loadTemplate('buttons'); ?>
</div>
</div>