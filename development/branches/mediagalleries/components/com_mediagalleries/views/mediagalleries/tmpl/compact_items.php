<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<?php 
	foreach ($this->items as $item){
		$date = JFactory::getDate($item->added);
		$item->added = $date->toFormat( $this->params->get('date_format') );
		$link =  JRoute::_('index.php?option=com_mediagalleries&view=media&layout=default&id=' . $item->id);
?>
<div class="compact" style="float:left; margin:4px;">
	<div class="mediathumb">
		<a href="<?php echo $link; ?>">
			<img src="<?php echo $item->thumbnail; ?>" />	
			<div><?php echo $item->title; ?></div>
		</a>
	</div>
</div>
<?php 
	} //[end] list
?>