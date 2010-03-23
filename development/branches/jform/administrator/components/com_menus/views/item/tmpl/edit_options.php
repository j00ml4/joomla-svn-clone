<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<?php
	$fieldSets = $this->form->getFieldsets('request');

	if (!empty($fieldSets)) {
		$fieldSet = array_shift($fieldSets);
		echo JHtml::_('sliders.panel',JText::_($fieldSet->label), 'request-options');
		if (isset($fieldSet->description) && trim($fieldSet->description)) :
			echo '<p class="tip">'.$this->escape(JText::_($fieldSet->description)).'</p>';
		endif;
		?>
		<fieldset class="panelform">
			<?php foreach ($this->form->getFieldset('request') as $field) : ?>
				<?php echo $field->label; ?>
				<?php echo $field->input; ?>
			<?php endforeach; ?>
		</fieldset>
<?php
	}


	$fieldSets = $this->form->getFieldsets('params');

	foreach ($fieldSets as $name => $fieldSet) :
		$label = isset($fieldSet->label) ? $fieldSet->label : 'Config_'.$name;
		echo JHtml::_('sliders.panel',JText::_($label), $name.'-options');
			if (isset($fieldSet->description) && trim($fieldSet->description)) :
				echo '<p class="tip">'.$this->escape(JText::_($fieldSet->description)).'</p>';
			endif;
			?>
		<fieldset class="panelform">
			<?php foreach ($this->form->getFieldset($name) as $field) : ?>
				<?php echo $field->label; ?>
				<?php echo $field->input; ?>
			<?php endforeach; ?>
		</fieldset>
<?php endforeach;?>
