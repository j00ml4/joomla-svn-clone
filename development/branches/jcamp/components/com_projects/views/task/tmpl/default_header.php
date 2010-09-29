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
?>
<div class="projects-both-sides">
	<div class="projects-left-side">
		<span><?php echo JText::sprintf('COM_PROJECTS_CREATED_ON_BY',
							JHTML::_('date', $this->item->created, JText::_('DATE_FORMAT_LC1')),
							$this->item->author);?></span>
		<?php if(!empty($this->item->modified_by)): ?>
		<br>
		<span>
			<?php echo JText::sprintf('COM_PROJECTS_MODIFIED_ON_BY',
							JHTML::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC1')),
							$this->item->modified_last);?>		
		</span>
		<?php endif;?>
	</div>
	<?php if($this->item->category_title): ?>
	<span>
		<?php echo JText::_('JCATEGORY') .': '. $this->item->category_title; ?>
	</span>
	<?php endif; ?>
</div>