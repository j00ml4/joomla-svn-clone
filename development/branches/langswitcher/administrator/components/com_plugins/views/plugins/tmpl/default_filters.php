<?php
/**
 * @version		$Id: default.php 16413 2010-04-24 07:13:53Z chdemko $
 * @package		Joomla.Administrator
 * @subpackage	com_banners
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<fieldset id="filter-bar">
	<div class="filter-search fltlft">
		<?php foreach($this->form->getFieldSet('search') as $field): ?>
			<?php if (!$field->hidden): ?>
				<?php echo $field->label; ?>
			<?php endif; ?>
			<?php echo $field->input; ?>
		<?php endforeach; ?>
	</div>
	<div class="filter-select fltrt">
		<?php foreach($this->form->getFieldSet('select') as $field): ?>
			<?php if (!$field->hidden): ?>
				<?php echo $field->label; ?>
			<?php endif; ?>
			<?php echo $field->input; ?>
		<?php endforeach; ?>
	</div>
</fieldset>
<div class="clr"></div>

