<?php
/**
 * @version		$Id: default_articles.php 17873 2010-06-25 18:24:21Z 3dentech $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

$params = $this->params;
?>
<?php if($this->canDo->get('project.edit')) :?>
<td>
	<?php echo JHTML::_('grid.id',count($this->items), $this->article->id);?>
</td>
<?php endif; ?>				

<td class="list-title">
	<?php if($this->article->state > 0) :?>
	<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->article->slug, $this->article->catid)); ?>">
	<?php echo $this->escape($this->article->title); ?></a>
	<?php
		else :
			echo $this->escape($this->article->title);
		endif;	
	?>
	
</td>

<td class="list-date">
	<?php echo JHTML::_('date',$this->article->displayDate, $this->escape(
	$params->get('date_format', JText::_('DATE_FORMAT_LC3')))); ?>
</td>

<td class="list-author">
	<?php echo $params->get('link_author', 0) ? JHTML::_('link',JRoute::_('index.php?option=com_users&view=profile&member_id='.$this->article->created_by),$this->article->author) : $this->article->author; ?>
</td>

<?php if($this->canDo->get('project.edit')) :?>
<td>
	<a href="<?php echo JRoute::_('index.php?option=com_projects&view=document&task=edit&id='.$this->article->id);?>"><?php echo JText::_('JGLOBAL_EDIT'); ?></a>
</td>
<?php endif; ?>				

