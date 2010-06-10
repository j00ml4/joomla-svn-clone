<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">	
<!-- Table Heading -->
<?php if ($this->params->get('show_headings')) : ?>
<thead>
	<tr>
		<th class="sectiontableheader<?php echo $this->params->get( 'pageclass_sfx' ); ?>" align="right" width="5%">
			<?php echo JText::_('Num'); ?>
		</th>
		
		<!-- Title -->
	 	<th class="sectiontableheader<?php echo $this->params->get( 'pageclass_sfx' ); ?>" width="45%">
			<?php echo JHTML::_('grid.sort',  'Title', 'title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		</th>	
		
		<!-- Date -->
		<?php if ($this->params->get('show_date')) : ?>
		<th class="sectiontableheader<?php echo $this->params->get( 'pageclass_sfx' ); ?>" width="25%">
			<?php echo JHTML::_('grid.sort',  'Added', 'added', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		</th>
		<?php endif; ?>
	
		<!-- Author -->
		<?php if ($this->params->get('show_author')) : ?>
		<th class="sectiontableheader<?php echo $this->params->get( 'pageclass_sfx' ); ?>"  width="20%">
			<?php echo JHTML::_('grid.sort',  'From', 'author', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		</th>
		<?php endif; ?>
		
		<!-- Hits -->
		<?php if ($this->params->get('show_views')) : ?>
		<th align="center" class="sectiontableheader<?php echo $this->params->get( 'pageclass_sfx' ); ?>" width="5%" nowrap="nowrap">
			<?php echo JHTML::_('grid.sort',  'Views', 'a.hits', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		</th>
		<?php endif; ?>	
	
		<!-- Rating -->
		<?php if ($this->params->get('show_rating')) : ?>
		<!--th align="center" class="sectiontableheader<?php echo $this->params->get( 'pageclass_sfx' ); ?>" width="5%" nowrap="nowrap">
			<?php echo JHTML::_('grid.sort',  'rating', 'rating', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		</th-->
		<?php endif; ?>
		
	</tr>
</thead>
<?php endif; ?>
	
<!-- Items -->	
<tbody>
<?php 
	$k = 1;
	$count=0;
	foreach ($this->items as $item){ 
		$date = JFactory::getDate($item->added);
		$item->added = $date->toFormat( $this->params->get('date_format') );
		if($item->author==''){ $item->author = JText::_('Guest'); }  
		$link =  JRoute::_('index.php?option=com_mediagalleries&view=media&layout=default&id='.$item->id);
		$k = 1 - $k; $count++
?>
	<tr class="sectiontableentry<?php echo ($k +1 ) . $this->params->get( 'pageclass_sfx' ); ?>" >
		<td>
		<?php // Show thumbnail
			if($this->params->get('show_thumbnail')){		
				echo '<div class="mediathumb">
					<a href="'. $link .'">
						<img title="'. $item->title .'" src="'. $item->thumbnail .'" />
					</a>
				</div>';				
			}else{ // Show counter
				echo $count;
			}
		?>		
		</td>
		
		<!-- title -->
		<td>
			<h4>
				<a href="<?php echo $link; ?>"><?php echo $item->title; ?></a>
			</h4>
		</td>

		<!-- added Date -->	
		<?php if ($this->params->get('show_date')) : ?>
		<td>
			<span class-"createdate"><?php echo $item->added; ?></span>
		</td>
		<?php endif; ?>
		
		<!-- From -->
		<?php if ($this->params->get('show_author')) : ?>
		<td>
			<span class-"createby"><?php echo $item->author; ?></span>
		</td>
		<?php endif; ?>
		
		<!-- Views -->
		<?php if ($this->params->get('show_views')) : ?>
		<td align="center">
			<?php echo $item->hits; ?>
		</td>
		<?php endif; ?>
		
		<!-- Rating -->
		<?php if ($this->params->get('show_rating')) : ?>
		<!--td align="center" class="rating">
			<?php echo sprintf("%01.2f", $item->rating); ?>
		</td-->
		<?php endif; ?>
	</tr>
<?php } ?>
</tbody>	
	
</table>