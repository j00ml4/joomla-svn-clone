<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	com_installer
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<tr class="<?php echo "row".$this->item->index % 2; ?>" <?php echo $this->item->style; ?>>
	<td><?php echo $this->pagination->getRowOffset($this->item->index); ?></td>
	<td>
			<input type="checkbox" id="cb<?php echo $this->item->index;?>" name="eid[]" value="<?php echo $this->item->extension_id; ?>" onclick="isChecked(this.checked);" <?php echo $this->item->cbd; ?> />
<!--		<input type="checkbox" id="cb<?php echo $this->item->index;?>" name="eid" value="<?php echo $this->item->extension_id; ?>" onclick="isChecked(this.checked);" <?php echo $this->item->cbd; ?> />-->
		<span class="bold"><?php echo $this->item->name; ?></span>
	</td>
	<td>
		<?php echo JText::_('INSTALLER_' . $this->item->type); ?>
	</td>
	<td class="center">
		<?php echo @$this->item->version;?>
	</td>
	<td class="center"><?php echo @$this->item->folder != '' ? $this->item->folder : JText::_('INSTALLER_NONAPPLICABLE'); ?></td>
	<td class="center"><?php echo $this->item->client; ?></td>
	<td>
		<span class="editlinktip hasTip" title="<?php echo JText::_('AUTHOR_INFORMATION');?>::<?php echo $this->item->author_info; ?>">
			<?php echo @$this->item->author != '' ? $this->item->author : '&nbsp;'; ?>
		</span>
	</td>
</tr>
