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
		<?php echo $this->form->getLabel('priority'); ?> <?php echo $this->form->getInput('priority'); ?>
	</div>

	<div class="formelm">
		<?php echo $this->form->getLabel('title'); ?> <?php echo $this->form->getInput('title'); ?>
	</div>

	<div class="formelm">
		<?php echo $this->form->getInput('description'); ?>
	</div>
</fieldset>


