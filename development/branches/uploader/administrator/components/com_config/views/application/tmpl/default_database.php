<?php
/**
 * @version		$Id: default_database.php 12753 2009-09-16 03:39:18Z severdia $
 * @package		Joomla.Administrator
 * @subpackage	com_config
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;
?>
<div class="width-100">
<fieldset class="adminform">
	<legend><?php echo JText::_('Database Settings'); ?></legend>
			<?php
			foreach ($this->form->getFields('database') as $field):
			?>
					<?php echo $field->label; ?>
					<?php echo $field->input; ?>
			<?php
			endforeach;
			?>

</fieldset>
</div>