<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	com_media
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;
?>
		<tr>
			<td class="imgTotal">
				<a href="<?php echo JRoute::_('index.php?option=com_media&view=folder&tmpl=component&folder='.$this->state->get('media.folder.parent')); ?>" target="folderframe">
					<img src="../media/media/images/folderup_16.png" width="16" height="16" border="0" alt=".." /></a>
			</td>
			<td class="description">
				<a href="<?php echo JRoute::_('index.php?option=com_media&view=folder&tmpl=component&folder='.$this->state->get('media.folder.path')); ?>" target="folderframe">..</a>
			</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
