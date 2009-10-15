<?php
/**
 * @version		$Id: default_ftp.php 12874 2009-09-28 05:15:19Z eddieajau $
 * @package		Joomla.Administrator
 * @subpackage	com_languages
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 */

defined('_JEXEC') or die;
?>
	<fieldset title="<?php echo JText::_('Langs_Desc_FTP_Title'); ?>">
		<legend><?php echo JText::_('Langs_Desc_FTP_Title'); ?></legend>

		<?php echo JText::_('Langs_Desc_FTP'); ?>

		<?php if (JError::isError($ftp)): ?>
			<p><?php echo JText::_($ftp->message); ?></p>
		<?php endif; ?>

		<table class="adminform nospace">
			<tbody>
				<tr>
					<td width="120">
						<label for="username"><?php echo JText::_('Langs_Username'); ?>:</label>
					</td>
					<td>
						<input type="text" id="username" name="username" class="input_box" size="70" value="" />
					</td>
				</tr>
				<tr>
					<td width="120">
						<label for="password"><?php echo JText::_('Langs_Password'); ?>:</label>
					</td>
					<td>
						<input type="password" id="password" name="password" class="input_box" size="70" value="" />
					</td>
				</tr>
			</tbody>
		</table>
	</fieldset>
