<?php
/**
 * @version		$Id: default.php 14276 2010-01-18 14:20:28Z louis $
 * @package		Joomla.Administrator
 * @subpackage	com_languages
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Add specific helper files for html generation
JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers'.DS.'html');
?>
<form action="<?php echo JRoute::_('index.php?option=com_languages&view=installed'); ?>" method="post" name="adminForm" id="adminForm">

	<?php if ($this->ftp): ?>
		<?php echo $this->loadTemplate('ftp');?>
	<?php endif; ?>

	<fieldset id="filter-bar">
	<legend class="element-invisible"><?php echo JText::_('Filters'); ?></legend>
		<div class="filter-search">
			<label class="filter-search-lbl" for="filter_client_id">
				<?php echo JText::_('Langs_Filter_Client_Label'); ?>
			</label>
			<select id="filter_client_id" name="filter_client_id" class="inputbox">
				<?php echo JHtml::_('select.options', JHtml::_('languages.clients'), 'value', 'text', $this->state->get('filter.client_id'));?>
			</select>
			
			<button type="button" id="filter-go" onclick="this.form.submit();">
				<?php echo JText::_('Go'); ?></button>
			
		</div>
	</fieldset>

	<table class="adminlist">
		<thead>
			<tr>
				<th class="row-number-col">
					<?php echo JText::_('Langs_Num'); ?>
				</th>
				<th class="checkmark-col">
					&nbsp;
				</th>
				<th class="title">
					<?php echo JText::_('Langs_Language'); ?>
				</th>
				<th class="width-5">
					<?php echo JText::_('Langs_Default'); ?>
				</th>
				<th class="width-10">
					<?php echo JText::_('Langs_Version'); ?>
				</th>
				<th class="width-10">
					<?php echo JText::_('Langs_Date'); ?>
				</th>
				<th class="width-20">
					<?php echo JText::_('Langs_Author'); ?>
				</th>
				<th class="width-25">
					<?php echo JText::_('Langs_Author_Email'); ?>
				</th>
			</tr>
		</thead>
		
		<tbody>
		<?php foreach ($this->rows as $i => $row):?>
			<tr class="row<?php echo $i % 2; ?>">
				<th>
					<?php echo $this->pagination->getRowOffset($i); ?>
				</th>
				<td>
					<?php echo JHtml::_('languages.id',$i,$row->language);?>
				</td>
				<td>
					<?php echo $row->name;?>
				</td>
				<td class="center">
					<?php echo JHtml::_('languages.published',$row->published);?>
				</td>
				<td class="center">
					<?php echo $row->version; ?>
				</td>
				<td class="center">
					<?php echo $row->creationDate; ?>
				</td>
				<td class="center">
					<?php echo $row->author; ?>
				</td>
				<td class="center">
					<?php echo $row->authorEmail; ?>
				</td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
	
	<?php echo $this->pagination->getListFooter(); ?>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHtml::_('form.token'); ?>
</form>
