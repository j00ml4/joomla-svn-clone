<?php
/**
 * @version		$Id: edit_options.php 14276 2010-01-18 14:20:28Z louis $
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<?php
	$fieldSets = $this->paramsform->getFieldsets();
	foreach ($fieldSets as $name => $fieldSet) :
		if (isset($fieldSet['hidden']) && $fieldSet['hidden'] == true) :
			continue;
		endif;
		$label = isset($fieldSet['label']) ? $fieldSet['label'] : 'Config_'.$name;
		echo JHtml::_('sliders.panel',JText::_($label), $name.'-options');
			if (isset($fieldSet['description'])) :
				echo '<p class="tip">'.JText::_($fieldSet['description']).'</p>';
			endif;
			?>
		<fieldset class="panelform">
		<legend><?php echo JText::_('Options'); ?></legend>
			<?php
				foreach ($this->paramsform->getFields($name) as $field) :
			?>

				<?php echo $field->label; ?>
				<?php echo $field->input; ?>

			<?php
				endforeach;
			?>
		</fieldset>
<?php endforeach;?>
