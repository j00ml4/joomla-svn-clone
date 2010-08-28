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
<div class="projects-content projects-frame projects-space">
	<h4><?php echo JText::_('COM_PROJECTS_PROJECT_MEMBERS_LIST');?></h4>
	<?php
		$c = count($this->members); 
		if($c) : // list members ?>
		<ul class="ulList">
		<?php
			for($i = 0; $i < $c; $i++) {
				?>
				<li><?php echo JHTML::_('link',JRoute::_('index.php?option=com_users&view=profile&member_id='.$this->members[$i]->id),$this->members[$i]->name.' ('.$this->members[$i]->role.')');?></li>
				<?php
			} ?>
		</ul> <?php
		else:
			echo JText::_('COM_PROJECTS_PROJECT_NO_MEMBER').'<br /><br />';
		endif
	?>
	<a href="<?php echo ProjectsHelper::getLink('members', $this->item->id); ?>" class="readmore">
		<?php echo JText::_('COM_PROJECTS_PROJECT_MEMBERS_LIST_LINK'); ?></a>
</div>
