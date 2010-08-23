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
$canCheckin = $canChange = true;
?>
<ol class="todo-list">
<?php foreach ($this->items as $i => $item): ?>
	<?php if(!empty($parent) && $item->parent_id != $parent->id){
		$parent = null;
		echo '</ol>';
	}?>	 
	<?php if($i && empty($parent) && $this->items[$i-1]->id == $item->parent_id){
		$parent = $this->items[$i-1];
		echo '<ol><li>';
		/* Create tjhe parrent */
	}else{
		echo '<li>';
	}?> 
		<?php echo JHtml::_('grid.id', $i, $item->id); ?>
		<?php if ($item->checked_out) :
			echo JHtml::_('jgrid.checkedout', $item->i, $item->editor, $item->checked_out_time, 'categories.', $canCheckin);
		endif; ?>	
		<?php echo $item->title; ?>	
	
		<span title="<?php echo sprintf('%d-%d', $item->lft, $item->rgt);?>">
			<?php echo (int) $item->id; ?></span>
	</li>
<?php endforeach; ?>
</ol>