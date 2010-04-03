<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<?php if ($this->params->get('show_page_title')) : ?>
<h1>
	<?php if ($this->escape($this->params->get('page_heading'))) :?>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	<?php else : ?>
		<?php echo $this->escape($this->params->get('page_title')); ?>
	<?php endif; ?>


</h1>
<?php endif; ?>

<?php if (trim($this->default_page_title) != '') : ?>
<h1>
<?php echo $this->default_page_title; ?>
</h1>
<?php endif; ?>
<div class="logout<?php echo $this->params->get('pageclass_sfx')?>">
<?php if ($this->params->get('logoutdescription_show')==1 || $this->params->get('logout_image')!='') : ?>
<div class="logout-description">
<?php endif ; ?>

<?php if($this->params->get('logoutdescription_show')==1) : ?>
<?php echo $this->params->get('logout_description'); ?>
<?php endif; ?>
<?php if (($this->params->get('logout_image')!='')) :?>
<img src="<?php echo $this->params->get('logout_image'); ?>" class="logout-image" alt="<?php echo JTEXT::_('COM_USER_LOGOUT_IMAGE_ALT')?>"/>
<?php endif; ?>

<?php if ($this->params->get('logoutdescription_show')==1 || $this->params->get('logout_image')!='') : ?>
</div>
<?php endif ; ?>

<form action="<?php echo JRoute::_('index.php?option=com_users&task=user.logout'); ?>" method="post">
	<button type="submit" class="button">Logout</button>

	<input type="hidden" name="option" value="com_users" />
	<input type="hidden" name="task" value="user.logout" />
	<?php echo JHtml::_('form.token'); ?>
</form>

</div>