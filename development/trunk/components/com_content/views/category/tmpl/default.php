<?php // no direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.caption');
$cparams =& JComponentHelper::getParams('com_media');
?>
<?php if ($this->params->get('show_page_title', 1)) : ?>
<div class="componentheading<?php echo $this->params->get('pageclass_sfx');?>">
	<?php echo $this->escape($this->params->get('page_title')); ?>
</div>
<?php endif; ?>
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="contentpane<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<tr>
	<td width="60%" valign="top" class="contentdescription<?php echo $this->params->get( 'pageclass_sfx' ); ?>" colspan="2">
	<?php echo $this->category->description; ?>
</td>
</tr>
<tr>
	<td>
	<ul>
	<?php
	foreach($this->children as $child)
	{
		echo '<li><a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($child->id)).'">'.$child->title.'</a> ('.$child->numitems.')</li>';
	}
	?>
	</ul>
	</td>
</tr>
<tr>
	<td>
	<?php
		$this->items =& $this->getItems();
		if(count($this->items))
		{
			echo $this->loadTemplate('items');
		}
	?>

	<?php if ($this->access->canEdit || $this->access->canEditOwn) :
			echo JHtml::_('icon.create', $this->category  , $this->params, $this->access);
	endif; ?>
	</td>
</tr>
</table>
