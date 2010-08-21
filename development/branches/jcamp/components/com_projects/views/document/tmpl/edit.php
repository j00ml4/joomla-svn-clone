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
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.calendar');

// Create shortcut to parameters.
$params = $this->state->get('params');

$app = &JFactory::getApplication();
?>
<div class="project-item <?php echo $this->escape($params->get('pageclass_sfx')); ?>">
<div class="item-page edit">
<form action="<?php echo ProjectsHelper::getLink('document'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
	<fieldset>
		<legend><?php echo JText::_('JEDITOR'); ?></legend>

		<div class="formelm">
			<?php echo $this->form->getLabel('title'); ?>
			<?php echo $this->form->getInput('title'); ?>
		</div>

		<div class="formelm">
			<?php echo $this->form->getLabel('catid'); ?>
			<?php echo $this->form->getInput('catid'); ?>
		</div>
		
		<div class="formelm">
			<?php echo $this->form->getInput('text'); ?>
		</div>
	</fieldset>

	<fieldset>
		<legend><?php echo JText::_('JDETAILS'); ?></legend>

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

		<div class="formelm">
			<?php echo $this->form->getLabel('access'); ?>
			<?php echo $this->form->getInput('access'); ?>
		</div>
		<div class="formelm">
			<?php echo $this->form->getLabel('ordering'); ?>
			<?php echo $this->form->getInput('ordering'); ?>
		</div>

		<div class="formelm_area">
			<?php echo $this->form->getLabel('language'); ?>
			<?php echo $this->form->getInput('language'); ?>
		</div>
	</fieldset>

	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
</div>