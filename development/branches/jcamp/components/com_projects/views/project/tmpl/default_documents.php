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
    <h4><?php echo JText::_('COM_PROJECTS_DOCUMENTS_LIST'); ?></h4>
	<?php
		$c = count($this->docs); 
		if($c) : // list tickets ?>
		<ul class="ulList">
		<?php
			for($i = 0; $i < $c; $i++) {
				?>
				<li><a href="<?php echo ProjectsHelper::getLink('document',$this->docs[$i]->id)?>"><?php echo $this->docs[$i]->title?></a></li>
				<?php
			} ?>
		</ul> <?php
		else:
			echo JText::_('COM_PROJECTS_PROJECT_NO_DOCUMENT').'<br /><br />';
		endif
	?>

    <a href="<?php echo ProjectsHelper::getLink('documents', $this->item->id); ?>" class="readmore">
	<?php echo JText::_('COM_PROJECTS_DOCUMENTS_LIST_LINK'); ?></a>
</div>
