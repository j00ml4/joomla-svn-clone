<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.html.pane');
$pane = &JPane::getInstance('sliders', array('allowAllClose' => true));


	$fieldSets = $this->paramsform->getFieldsets();
	foreach ($fieldSets as $name => $fieldSet) :
		if (isset($fieldSet['hidden']) && $fieldSet['hidden'] == true || $name == 'request') :
			continue;
		endif;

	$label = isset($fieldSet['label']) ? $fieldSet['label'] : 'Config_'.$name;
	echo $pane->startPanel(JText::_($label), 'publishing-details');

	if (isset($fieldSet['description'])) :
			echo '<p class="tip" style="float:right;">'.JText::_($fieldSet['description']).'</p>';
	endif;?>

		<?php
		foreach ($this->paramsform->getFields($name) as $field) :
		?>
					<div>	
						<?php echo $field->label; ?><br />
						<?php echo $field->input; ?>
					</div>			
		<?php
		endforeach;
		?>
<div class="clr" /></div>
<?php
		echo $pane->endPanel();
	endforeach;

?>
