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
JHtml::_('behavior.formvalidation');

// Vars
$params = $this->params;
?>
<fieldset>
	<legend><?php echo JText::_('JGLOBAL_DESCRIPTION'); ?></legend>

	<div class="formelm">
		<?php echo $this->form->getLabel('parent_id'); ?> <?php echo $this->form->getInput('parent_id', null, $this->params->get('parent_id')); ?>
	</div>
	
	<div class="formelm">
		<?php echo $this->form->getLabel('title'); ?> <?php echo $this->form->getInput('title'); ?>
		<?php echo $this->form->getInput('catid', null, $this->params->get('catid')); ?>
	</div>
	
	<div class="formelm">
		<?php echo $this->form->getInput('description'); ?>
	</div>
</fieldset>
	
<fieldset>
	<legend><?php echo JText::_('JDETAILS'); ?></legend>
	
	<?php /* ?>
	<div class="formelm">
		<?php echo $this->form->getLabel('ordering'); ?> <?php echo $this->form->getInput('ordering'); ?>
	</div>
	<?php */ ?>
	<div class="formelm">
		<?php echo $this->form->getLabel('state'); ?> <?php echo $this->form->getInput('state'); ?>
	</div>
	
	<div class="formelm">
		<?php echo $this->form->getLabel('access'); ?> <?php echo $this->form->getInput('access'); ?>
	</div>
	
	<div class="formelm">
		<?php echo $this->form->getLabel('estimate'); ?> <?php echo $this->form->getInput('estimate'); ?>
	</div>
	
	<div class="formelm">
		<?php echo $this->form->getLabel('start_at'); ?> <?php echo $this->form->getInput('start_at'); ?>
	</div>
	<div class="formelm">
		<?php echo $this->form->getLabel('finish_at'); ?> <?php echo $this->form->getInput('finish_at'); ?>
	</div>
</fieldset>
