<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	com_projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

?>
<li class="items-row item">
	<h3><a href="<?php echo ProjectsHelper::getLink('project',$this->item->id); ?>">
		<?php echo $this->item->title;?>
	</a></h3>
	<div class="cetegory-desc">	
			<dl class="projects-info projects-space projects-frame">
                    <dt><?php echo JText::_('COM_PROJECTS_FIELD_START_AT_LABEL') ?>:</dt>
                    <dd><?php echo JFactory::getDate($this->item->start_at)->toFormat('%d.%m.%Y'); ?></dd>
                    <dt><?php echo JText::_('COM_PROJECTS_FIELD_FINISH_AT_LABEL') ?>:</dt>
                    <dd><?php echo JFactory::getDate($this->item->finish_at)->toFormat('%d.%m.%Y'); ?></dd>
                </dl>		
		<div class="projects-space">
            <?php echo JHTML::_('tool.progressBar', $this->item->progress); ?>
        </div>
		<div class="clear"></div>
		<a class="readmore" href="<?php echo ProjectsHelper::getLink('project',$this->item->id);?>">
			<?php echo JText::_('COM_PROJECTS_PROJECTS_SEE_PROJECT'); ?>
		</a>
	</div>
</li>