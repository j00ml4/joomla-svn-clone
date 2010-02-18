<?php
/**
 * @version		$Id: default_ftp.php 14276 2010-01-18 14:20:28Z louis $
 * @package		Joomla.Administrator
 * @subpackage	com_languages
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
	<fieldset class="adminform">
		<legend><?php echo JText::_('Langs_Desc_FTP_Title'); ?></legend>

		<?php echo JText::_('Langs_Desc_FTP'); ?>

		<?php if (JError::isError($ftp)): ?>
			<p class="warning"><?php echo JText::_($ftp->message); ?></p>
		<?php endif; ?>
		
		<div>
			<label for="username"><?php echo JText::_('Langs_Username'); ?>:</label>
			<input type="text" id="username" name="username" class="inputbox" value="" />
		</div>
		<div>
			<label for="password"><?php echo JText::_('Langs_Password'); ?>:</label>
			<input type="password" id="password" name="password" class="inputbox" value="" />
		</div>
	</fieldset>
