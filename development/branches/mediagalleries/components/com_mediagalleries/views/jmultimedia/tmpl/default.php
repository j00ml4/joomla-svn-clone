<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<script language="javascript" type="text/javascript">

	function tableOrdering( order, dir, task )
	{
		var form = document.adminForm;

		form.filter_order.value 	= order;
		form.filter_order_Dir.value	= dir;
		document.adminForm.submit( task );
	}
</script>
<!-- Content Items -->
<div id="mediagalleries" class="contentpane<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<form action="<?php echo $this->action; ?>" method="post" name="adminForm">	
	<?php if ($this->params->get('show_page_title')) : ?>
		<h1 class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
			<?php echo $this->params->get('page_title'); ?>
		</h1>
	<?php endif; ?>
	
	<!-- Description -->
	<?php if(!empty($category)): ?>
	<div class="contentdescription<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
		<?php if ($this->category->image) : ?>
			<img src="<?php echo $this->baseurl . '/' . $cparams->get('image_path') . '/'. $this->category->image;?>" align="<?php echo $this->category->image_position;?>" hspace="6" alt="<?php echo $this->category->image;?>" />
		<?php endif; ?>
		<?php echo $this->category->description; ?>
	</div>
	<?php endif; ?>

	<!-- Filters -->
	<table width="100%">
	<tr>
		<!-- Search -->	
		<?php if ($this->params->get('filter')) : ?>
		<td align="left" nowrap="nowrap">
			<?php echo JText::_('Search').'&nbsp;'; ?>
			<input type="text" id="search" name="search" value="<?php echo $this->lists['filter'];?>" class="inputbox" onchange="document.adminForm.submit();" />
		</td>
		<?php endif; ?>	
			
		<!-- Category -->	
		<?php if ($this->params->get('filter_category')) : ?>
		<td align="left" nowrap="nowrap">
		<?php 
			echo JText::_('Category').'&nbsp;'; 
			echo $this->lists['catid'];
		?>
		</td>
		<?php endif; ?>	
		
		<!-- Display -->
		<?php if ($this->params->get('show_pagination_limit')) : ?>
		<td align="right" nowrap="nowrap">
		<?php
			echo JText::_('Display Num').'&nbsp;'
				. $this->pagination->getLimitBox();
		?>
		</td>
		<?php endif; ?>
	</tr>
	</table>	


	<!-- Content -->
	<div>
	<?php
		// Empty List
		if( empty($this->items) ){
			echo JText::_('RESOURCE NOT FOUND');
		}
		//Has Items
		else{
			echo $this->loadTemplate( 'items' );
		}		
	?>
	</div>


	<!-- Pagination -->
	<?php if ($this->params->get('show_pagination')) : ?>
	<table width="100%">	
		<tr>
			<td align="center" class="sectiontablefooter<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
				<?php echo $this->pagination->getPagesLinks(); ?>
			</td>
		</tr>
		<tr>
			<td align="right">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</td>
		</tr>
	</table>
	<?php endif; ?>

<input type="hidden" name="id" value="<?php echo $this->category->id; ?>" />
<input type="hidden" name="section" value="<?php echo $this->category->section; ?>" />
<input type="hidden" name="task" value="<?php echo $this->lists['task']; ?>" />
<input type="hidden" name="c" value="media" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
</form>	
</div>