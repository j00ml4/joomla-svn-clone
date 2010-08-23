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
<div class="project-item <?php echo $pageClass?>">
<form action="<?php echo ProjectsHelper::getLink('task', $this->item->id); ?>" method="post" id="adminForm" name="adminForm">
	<div class="item-page">
		<div class="item">
			<div class="category-desc">
				<?php echo $this->item->description; ?>
			</div>
		</div>
	</div>
	
	<?php if(count($this->items)): ?>
	<fieldset>
		<legend><?php JText::_(''); ?></legend>
		<?php echo $this->loadTemplate('table'); ?>
	</fieldset>
	<?php endif; ?>
		
	
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
    <input type="hidden" name="boxchecked" value="1" />     
    <?php echo JHTML::_('form.token'); ?>
</form>		
</div>