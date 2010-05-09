<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	com_languages
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers'.DS.'html');
JHtml::_('behavior.tooltip');
$n = count($this->items);

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
?>

<form action="<?php echo JRoute::_('index.php?option=com_languages&view=languages'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset class="filter">
		<div class="left">
			<label for="filter_search">
				<?php echo JText::_('JSearch_Filter'); ?>
			</label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" size="60" title="<?php echo JText::_('COM_LANGUAGES_SEARCH_IN_TITLE'); ?>" />

			<button type="submit">
				<?php echo JText::_('JSearch_Filter_Submit'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();">
				<?php echo JText::_('JSearch_Filter_Clear'); ?></button>
		</div>
		<div class="right">
			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOption_Select_Published');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('languages.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>
		</div>
	</fieldset>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="5">
					<?php echo JText::_('JGrid_Heading_Row_Number'); ?>
				</th>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
				</th>
				<th class="title">
					<?php echo JHtml::_('grid.sort',  'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
				</th>
				<th class="title">
					<?php echo JHtml::_('grid.sort',  'COM_LANGUAGES_HEADING_TITLE_NATIVE', 'a.title_native', $listDirn, $listOrder); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHtml::_('grid.sort',  'COM_LANGUAGES_HEADING_LANG_CODE', 'a.lang_code', $listDirn, $listOrder); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHtml::_('grid.sort',  'COM_LANGUAGES_HEADING_LANG_SEF', 'a.sef', $listDirn, $listOrder); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHtml::_('grid.sort',  'COM_LANGUAGES_HEADING_LANG_IMAGE', 'a.image', $listDirn, $listOrder); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHtml::_('grid.sort',  'JPUBLISHED', 'a.published', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" nowrap="nowrap">
					<?php echo JHtml::_('grid.sort',  'JGrid_Heading_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="9">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
		foreach ($this->items as $i => $item) :
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td>
					<?php echo $this->pagination->getRowOffset($i); ?>
				</td>
				<td>
					<?php echo JHtml::_('grid.id', $i, $item->lang_id); ?>
				</td>
				<td>
					<span class="editlinktip hasTip" title="<?php echo JText::_('JGLOBAL_EDIT_ITEM');?>::<?php echo $item->title; ?>">
						<a href="<?php echo JRoute::_('index.php?option=com_languages&task=language.edit&id='.(int) $item->lang_id); ?>">
							<?php echo $item->title; ?></a></span>
				</td>
				<td align="center">
					<?php echo $item->title_native; ?>
				</td>
				<td align="center">
					<?php echo $item->lang_code; ?>
				</td>
				<td align="center">
					<?php echo $item->sef; ?>
				</td>
				<td align="center">
					<?php echo $item->image; ?>
				</td>
				<td align="center">
					<?php echo JHtml::_('jgrid.published', $item->published, $i, 'languages.');?>
				</td>
				<td align="center">
					<?php echo $item->lang_id; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
