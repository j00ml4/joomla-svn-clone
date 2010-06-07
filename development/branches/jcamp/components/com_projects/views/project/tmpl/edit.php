<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	com_projects
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die;

// HTML Helpers
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.calendar');
JHtml::_('behavior.formvalidation');

$script = "function submitbutton(task) {".
					"if (task == 'project.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {".
					"		submitform(task);".
					"	}".
				  "}";

$document = &JFactory::getDocument();
$document->addScriptDeclaration($script);

//print_r($this->form->getFieldset());
?>
<div class="componentheading"><?php echo JText::_('COM_PROJECTS_PROJECT_EDIT_FORM_TITLE');?></div>
<form action="<?php echo JRoute::_('index.php?option=com_projects'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
	<fieldset>
		<legend><?php echo JText::_('COM_PROJECTS_PROJECT_FORM_LEGEND'); ?></legend>
		<ul class="adminformlist">
			<?php 
			  $fields = $this->form->getFieldset(); 
				foreach($fields as $name=>$field)	{
					if($this->form->getFieldAttribute($name,'type') != 'hidden')	{
						echo '<li>'.$this->form->getLabel($name).$this->form->getInput($name).'</li>';
					}
				}
			?>		
			</ul>
	</fieldset>
	<fieldset>
		<button type="button" onclick="submitbutton('weblink.save')">
			<?php echo JText::_('JSAVE') ?>
		</button>
		<button type="button" onclick="submitbutton('weblink.cancel')">
			<?php echo JText::_('JCANCEL') ?>
		</button>
	</fieldset>

	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
