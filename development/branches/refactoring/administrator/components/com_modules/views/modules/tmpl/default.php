<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php
	$user =& JFactory::getUser();

	//Ordering allowed ?
	$ordering = (($this->filter->order == 'm.position'));

	JHTML::_('behavior.tooltip');
?>

<form action=<?php echo JRoute::_("index.php?option=com_modules"); ?> method="post" name="adminForm">

	<table>
	<tr>
		<td align="left" width="100%">
			<?php echo JText::_( 'Filter' ); ?>:
			<input type="text" name="search" id="search" value="<?php echo $this->filter->search;?>" class="text_area" onchange="document.adminForm.submit();" />
			<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
			<button onclick="document.getElementById('search').value=''; this.form.getElementById('filter_assigned').value='0'; this.form.getElementById('filter_position').value='0'; this.form.getElementById('filter_type').value='0'; this.form.getElementById('filter_state').value=''; this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
		</td>
		<td nowrap="nowrap">
			<?php
				echo JHTML::_('filter.assigned', $this->client, $this->filter->assigned );
				echo JHTML::_('filter.position', $this->client, $this->filter->position );
				echo JHTML::_('filter.type', $this->client, $this->filter->type );
				echo JHTML::_('grid.state', $this->filter->state );
			?>
		</td>
	</tr>
	</table>

	<table class="adminlist" cellspacing="1">
	<thead>
	<tr>
		<th width="20">
			<?php echo JText::_( 'NUM' ); ?>
		</th>
		<th width="20">
			<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->rows );?>);" />
		</th>
		<th class="title">
			<?php echo JHTML::_('grid.sort', 'Module Name', 'm.title', @$this->filter->order_Dir, @$this->filter->order ); ?>
		</th>
		<th nowrap="nowrap" width="7%">
			<?php echo JHTML::_('grid.sort', 'Published', 'm.published', @$this->filter->order_Dir, @$this->filter->order ); ?>
		</th>
		<th width="80" nowrap="nowrap">
			<?php echo JHTML::_('grid.sort', 'Order', 'm.position', @$this->filter->order_Dir, @$this->filter->order ); ?>
		</th>
		<th width="1%">
			<?php echo JHTML::_('grid.order',  $this->rows ); ?>
		</th>
		<?php
		if ( $this->client->id == 0 ) {
			?>
			<th nowrap="nowrap" width="7%">
				<?php echo JHTML::_('grid.sort', 'Access', 'groupname', @$this->filter->order_Dir, @$this->filter->order ); ?>
			</th>
			<?php
		}
		?>
		<th nowrap="nowrap" width="7%">
			<?php echo JHTML::_('grid.sort',   'Position', 'm.position', @$this->filter->order_Dir, @$this->filter->order ); ?>
		</th>
		<th nowrap="nowrap" width="5%">
			<?php echo JHTML::_('grid.sort',   'Pages', 'pages', @$this->filter->order_Dir, @$this->filter->order ); ?>
		</th>
		<th nowrap="nowrap" width="10%"  class="title">
			<?php echo JHTML::_('grid.sort',   'Type', 'm.module', @$this->filter->order_Dir, @$this->filter->order ); ?>
		</th>
		<th nowrap="nowrap" width="1%">
			<?php echo JHTML::_('grid.sort',   'ID', 'm.id', @$this->filter->order_Dir, @$this->filter->order ); ?>
		</th>
	</tr>
	</thead>
	<tfoot>
	<tr>
		<td colspan="12">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
	</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->rows ); $i < $n; $i++) {
		$row 	= &$this->rows[$i];

		$link 		= JRoute::_( 'index.php?option=com_modules&client='. $this->client->id .'&task=edit&cid[]='. $row->id );

		$access 	= JHTML::_('grid.access',   $row, $i );
		$checked 	= JHTML::_('grid.checkedout',   $row, $i );
		$published 	= JHTML::_('grid.published', $row, $i );
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td align="right">
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td width="20">
				<?php echo $checked; ?>
			</td>
			<td>
			<?php
			if (  JTable::isCheckedOut($user->get ('id'), $row->checked_out ) ) {
				echo $row->title;
			} else {
				?>
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit Module' );?>::<?php echo $row->title; ?>">
				<a href="<?php echo $link; ?>">
					<?php echo $row->title; ?></a>
				</span>
				<?php
			}
			?>
			</td>
			<td align="center">
				<?php echo $published;?>
			</td>
			<td class="order" colspan="2">
				<span><?php echo $this->pagination->orderUpIcon( $i, ($row->position == @$this->rows[$i-1]->position), 'orderup', 'Move Up', $ordering ); ?></span>
				<span><?php echo $this->pagination->orderDownIcon( $i, $n, ($row->position == @$this->rows[$i+1]->position),'orderdown', 'Move Down', $ordering ); ?></span>
				<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
			</td>
			<?php
			if ( $this->client->id == 0 ) {
				?>
				<td align="center">
					<?php echo $access;?>
				</td>
				<?php
			}
			?>
			<td align="center">
				<?php echo $row->position; ?>
			</td>
			<td align="center">
				<?php
				if (is_null( $row->pages )) {
					echo JText::_( 'None' );
				} else if ($row->pages > 0) {
					echo JText::_( 'Varies' );
				} else {
					echo JText::_( 'All' );
				}
				?>
			</td>
			<td>
				<?php echo $row->module ? $row->module : JText::_( 'User' );?>
			</td>
			<td>
				<?php echo $row->id;?>
			</td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	</tbody>
	</table>

<input type="hidden" name="option" value="com_modules" />
<input type="hidden" name="client" value="<?php echo $this->client->id;?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->filter->order; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->filter->order_Dir; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
