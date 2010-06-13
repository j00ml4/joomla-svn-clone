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
$uri = &JFactory::getURI();
?>
<div class="formelm_buttons">
	<?php if ($this->canDo->get('project.edit', 1)): ?>
		<?php 
			$link = JRoute::_('index.php?task=project.edit&cid='.$this->item->id);
		?>
		<a href="<?php echo $link; ?>">
			<button type="button"><?php echo JText::_('JGLOBAL_EDIT'); ?></button>
		</a>
	<?php endif; ?>
	<?php if ($this->canDo->get('project.edit.state', 1)): ?>
		<?php 
			$link = JRoute::_('index.php?task=project.edit&id='.$this->item->id);
		?>
		<a href="<?php echo $link; ?>">
			<button type="button"><?php echo JText::_('JGLOBAL_PUBLISH'); ?></button>
		</a>
	<?php endif; ?>
	
</div>
