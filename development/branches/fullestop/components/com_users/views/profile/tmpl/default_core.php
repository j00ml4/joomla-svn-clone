<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.6
 */

defined('_JEXEC') or die;

jimport('joomla.user.helper');
?>

<fieldset id="users-profile-core">
	<legend>
		<?php echo JText::_('COM_USERS_Profile_Core_Legend'); ?>
	</legend>
	<dl>
		<dt>
			<?php echo JText::_('COM_USERS_Profile_Name_Label'); ?>
		</dt>
		<dd>
			<?php echo $this->data->name; ?>
		</dd>
		<dt>
			<?php echo JText::_('COM_USERS_Profile_Username_Label'); ?>
		</dt>
		<dd>
			<?php echo $this->data->username; ?>
		</dd>
		<dt>
			<?php echo JText::_('COM_USERS_Profile_Registered_Date_Label'); ?>
		</dt>
		<dd>
			<?php echo JHTML::_('date',$this->data->registerDate); ?>
		</dd>
		<dt>
			<?php echo JText::_('COM_USERS_Profile_Last_Visited_Date_Label'); ?>
		</dt>

		<?php if ($this->data->lastvisitDate != '0000-00-00 00:00:00'){?>
			<dd>
				<?php echo JHTML::_('date',$this->data->lastvisitDate); ?>
			</dd>
		<?php }
		else {?>
			<dd>
				<?php echo JText::_('COM_USERS_Profile_Never_Visited'); ?>
			</dd>
		<?php } ?>

	</dl>
</fieldset>
