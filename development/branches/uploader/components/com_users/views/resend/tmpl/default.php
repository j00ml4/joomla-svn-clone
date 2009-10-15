<?php
/**
 * @version		$Id: default.php 12432 2009-07-04 06:05:14Z eddieajau $
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<form action="<?php echo $this->form->getAction(); ?>" method="post">

	<?php foreach ($this->form->getGroups() as $group): ?>
	<fieldset>
		<dl>
		<?php foreach ($this->form->getFields($group, $group) as $name => $field): ?>
			<dt><?php echo $field->label; ?></dt>
			<dd><?php echo $field->field; ?></dd>
		<?php endforeach; ?>
		</dl>
	</fieldset>
	<?php endforeach; ?>

	<button type="submit">Submit</button>

	<input type="hidden" name="option" value="com_users" />
	<input type="hidden" name="task" value="registration.register" />
	<?php echo JHtml::_('form.token'); ?>
</form>