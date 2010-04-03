<?php
 /**
 * $Id$
 * @package		Joomla.Site
 * @subpackage	Contact
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$cparams = JComponentHelper::getParams ('com_media');
?>

<?php if ($this->params->get('show_page_title', 1)) : ?>
<h1>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php endif; ?>
<div class="contact<?php echo $this->params->get('pageclass_sfx')?>">

	<?php if ($this->contact->name && $this->params->get('show_name')) : ?>
		<h2>
			<span class="contact-name"><?php echo $this->contact->name; ?></span>
		</h2>
	<?php endif; ?>
	<?php if ($this->params->get('show_contact_category') == 'show_no_link') : ?>
		<h3>
			<span class="contact-category"><?php echo $this->contact->category_name; ?></span>
		</h3>
	<?php endif; ?>
	<?php if ($this->params->get('show_contact_category') == 'show_with_link') : ?>
		<?php $contactLink = ContactHelperRoute::getCategoryRoute($this->contact->catslug);?>
		<h3>
			<span class="contact-category"><a href="<?php echo $contactLink; ?>">
				<?php echo $this->escape($this->contact->category_name); ?></a>
			</span>
		</h3>
	<?php endif; ?>
	<?php if ($this->contact->image && $this->params->get('show_image')) : ?>
		<span class="contact-image">
			<?php echo JHTML::_('image','images/'.$this->contact->image, JText::_('Contact'), array('align' => 'middle')); ?>
		</span>
	<?php endif; ?>
<?php echo  JHtml::_('sliders.start', 'contact-slider'); ?>
	<?php echo JHtml::_('sliders.panel',JText::_('CONTACT_DETAILS'), 'basic-details'); ?>
	<?php if ($this->params->get('show_contact_list') && count($this->contacts) > 1) : ?>
		<form action="<?php echo JRoute::_('index.php') ?>" method="post" name="selectForm" id="selectForm">
			<?php echo JText::_('CONTACT_SELECT_CONTACT'); ?>:
			<?php echo JHtml::_('select.genericlist',  $this->contacts, 'id', 'class="inputbox" onchange="this.form.submit()"', 'id', 'name', $this->contact->id);?>
			<input type="hidden" name="option" value="com_contact" />
		</form>
	<?php endif; ?>

	<?php if ($this->contact->con_position && $this->params->get('show_position')) : ?>
		<p class="contact-position"><?php echo $this->contact->con_position; ?></p>
	<?php endif; ?>

	<?php echo $this->loadTemplate('address'); ?>

	<?php if ($this->params->get('allow_vcard')) :	//TODO either reimplement vcard or delete this.?>
		<?php echo JText::_('DOWNLOAD_INFORMATION_AS');?>
			<a href="<?php echo JURI::base(); ?>index.php?option=com_contact&amp;task=vcard&amp;contact_id=<?php echo $this->contact->id; ?>&amp;format=raw&amp;tmpl=component">
				<?php echo JText::_('VCard');?></a>
	<?php endif; ?>

	<?php if ($this->params->get('show_email_form') && ($this->contact->email_to )) : ?>
		<?php echo JHtml::_('sliders.panel', JText::_('CONTACT_EMAIL_FORM'), 'display-form'); ?>
			<?php echo $this->loadTemplate('form');  ?>
	<?php endif; ?>

	<?php if ($this->params->get('show_links')) : ?>
	<?php echo $this->loadTemplate('links'); ?>
	<?php endif; ?>
	<?php if ($this->params->get('show_articles') &&  $this->contact->user_id) : ?>
	<?php echo JHtml::_('sliders.panel', JText::_('CONTACT_ARTICLES'), 'display-articles'); ?>
		<?php echo $this->loadTemplate('articles'); ?>
	<?php endif; ?>
	<?php if ($this->params->get('show_profile') &&  $this->contact->user_id) : ?>
	<?php echo JHtml::_('sliders.panel', JText::_('Contact_Profile'), 'display-profile'); ?>
		<?php echo $this->loadTemplate('profile'); ?>
	<?php endif; ?>
	<?php if ($this->contact->misc && $this->params->get('show_misc')) : ?>
			<?php echo JHtml::_('sliders.panel', JText::_('Contact_Other_Information'), 'display-misc'); ?>
				<div class="contact-miscinfo">
					<span class="<?php echo $this->params->get('marker_class'); ?>">
						<?php echo $this->params->get('marker_misc'); ?>
					</span>
					<span class="contact-misc">
						<?php echo $this->contact->misc; ?>
					</span>
				</div>
	<?php endif; ?>

			<?php echo JHtml::_('sliders.end'); ?>
</div>
