<?php
/**
 * @version		$Id $
 * @package		Joomla.Site
 * @subpackage	com_projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.calendar');
JHtml::_('behavior.formvalidation');

// Create shortcut to parameters.
$params = $this->state->get('params');

$app = &JFactory::getApplication();
?>

<script type="text/javascript">
function submitbutton(task) {
	if (task == 'document.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
		<?php //echo $this->form->fields['introtext']->editor->save('jform[introtext]'); ?>
		submitform(task);
	}
}
</script>
<div class="projects edit item-page<?php echo $this->escape($params->get('pageclass_sfx')); ?>">
<?php if ($params->get('show_page_heading', 1)) : ?>
<h1>
	<?php echo JTEXT::_('COM_PROJECTS_DOCUMENT_FORM_TITLE'); ?>
</h1>
<?php endif; ?>

<form action="<?php echo JRoute::_('index.php?option=com_projects'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
	<?php  echo $this->loadTemplate('buttons');?>
	<fieldset>
		<legend><?php echo JText::_('JEDITOR'); ?></legend>
		<div class="formelm">
			<?php echo $this->form->getLabel('title'); ?>
			<?php echo $this->form->getInput('title'); ?>
		</div>

		<?php echo $this->form->getInput('text'); ?>
	</fieldset>

	<fieldset>
		<legend><?php echo JText::_('COM_PROJECTS_DOCUMENT_PUBLISH_FIELDSET'); ?></legend>
		<div class="formelm">
		<?php echo $this->form->getLabel('created_by_alias'); ?>
		<?php echo $this->form->getInput('created_by_alias'); ?>
		</div>

	<?php if ($this->user->authorise('core.edit.state', 'com_content.article.'.$this->item->id)): ?>
		<div class="formelm">
		<?php echo $this->form->getLabel('state'); ?>
		<?php echo $this->form->getInput('state'); ?>
		</div>
		<div class="formelm">
		<?php echo $this->form->getLabel('publish_up'); ?>
		<?php echo $this->form->getInput('publish_up'); ?>
		</div>
		<div class="formelm">
		<?php echo $this->form->getLabel('publish_down'); ?>
		<?php echo $this->form->getInput('publish_down'); ?>
		</div>

	<?php endif; ?>
		<div class="formelm">
		<?php echo $this->form->getLabel('access'); ?>
		<?php echo $this->form->getInput('access'); ?>
		</div>
		<div class="formelm">
		<?php echo $this->form->getLabel('ordering'); ?>
		<?php echo $this->form->getInput('ordering'); ?>
		</div>
	</fieldset>

	<fieldset>
		<legend><?php echo JText::_('JFIELD_LANGUAGE_LABEL'); ?></legend>
		<div class="formelm_area">
		<?php echo $this->form->getLabel('language'); ?>
		<?php echo $this->form->getInput('language'); ?>
		</div>
	</fieldset>
	<div>
		<input type="hidden" name="task" value="" />
		<?php echo JHTML::_( 'form.token' ); ?>
		<?php  echo $this->loadTemplate('buttons');?>
	</div>
</form>
</div>