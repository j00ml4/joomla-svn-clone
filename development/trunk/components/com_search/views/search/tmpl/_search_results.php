<table class="searchintro<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<tr>
	<td colspan="3" >
		<?php echo JText::_( 'Search Keyword' ) .' <b>'. stripslashes($this->request->searchword) .'</b>'; ?>
	</td>
</tr>
<tr>
	<td>
		<br />
		<?php eval ('echo "'. $this->data->result .'";'); ?>
		<a href="http://www.google.com/search?q=<?php echo $this->request->searchword; ?>" target="_blank">
			<?php echo $this->data->image; ?>
		</a>
	</td>
</tr>
</table>
<br />
<div align="center">
	<div style="float: right;">
		<label for="limit">
			<?php echo JText::_( 'Display Num' ); ?>
		</label>
		<?php echo $this->pagination->getLimitBox( $this->data->link ); ?>
	</div>
	<div>
		<?php echo $this->pagination->writePagesCounter(); ?>
	</div>
</div>
<table class="contentpaneopen<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<tr>
	<td>
	<?php
	foreach( $this->data->rows as $row ) : ?>
		<fieldset>
			<div>
				<span class="small<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
					<?php echo $row->count.'. ';?>
				</span>
				<?php if ( $row->href ) : 
					$row->href = ampReplace( $row->href );
					if ($row->browsernav == 1 ) : ?>
						<a href="<?php echo sefRelToAbs($row->href); ?>" target="_blank">
					<?php else : ?>
						<a href="<?php echo sefRelToAbs($row->href); ?>">
					<?php endif; 

					echo $row->title;

					if ( $row->href ) : ?>
						</a>
					<?php endif; 
					if ( $row->section ) : ?>
						<br />
						<span class="small<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
							(<?php echo $row->section; ?>)
						</span>
					<?php endif; ?>
				<?php endif; ?>
			</div>
			<div>
				<?php echo ampReplace( $row->text );?>
			</div>
			<?php if ( !$mainframe->getCfg( 'hideCreateDate' )) : ?>
			<div class="small<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
				<?php echo $row->created; ?>
			</div>
			<?php endif; ?>
		</fieldset>	
	<?php endforeach; ?>
	</td>
</tr>		
<tr>
	<td colspan="3">
		<div align="center">
			<?php $this->pagination->writePagesLinks( $this->data->link ); ?>
		</div>
	</td>
</tr>
</table>				