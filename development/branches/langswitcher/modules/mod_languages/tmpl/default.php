<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_languages
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

//$uri = JFactory::getURI();
?>
<form action="<?php echo JRoute::_('index.php?option=com_users'); ?>" method="post" id="mod_languages-form">
	<div class="mod_languages<?php echo $params->get('moduleclass_sfx') ?>">
	<?php if ($headerText) : ?>
		<div class="header"><?php echo $headerText; ?></div>
	<?php endif; ?>
	<?php echo JHtml::_('select.genericlist', $list, 'language', ' onchange="this.form.submit();"','value','text',$tag);?>
	<?php if ($footerText) : ?>
		<div class="footer"><?php echo $footerText; ?></div>
	<?php endif; ?>
	</div>
	<?php foreach($list as $language):?>
	<input type="hidden" name="redirect" value="<?php echo base64_encode($language['redirect']);?>" />
	<?php endforeach;?>
	<input type="hidden" name="task" value="profile.language" />
</form>

