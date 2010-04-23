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
JHtml::_('stylesheet','mod_languages/template.css', array(), true);
?>
<div class="mod_languages<?php echo $params->get('moduleclass_sfx') ?>">
<?php if ($headerText) : ?>
	<div class="header"><?php echo $headerText; ?></div>
<?php endif; ?>
	<ul>
<?php foreach($list as $tag=>$language):?>
	<?php if ($tag!='default'):?>
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_users&task=profile.language&redirect='.base64_encode($language['redirect']).'&language='.$tag.'&'.JUtility::getToken().'=1');?>">
				<span title="<?php echo $language['text'];?>" class="icon <?php echo $tag;?> <?php echo substr($tag,0,strpos($tag,'-'));?>" />
			</a>
		</li>
		<?php endif;?>
	<?php endforeach;?>
	</ul>
<?php if ($footerText) : ?>
	<div class="footer"><?php echo $footerText; ?></div>
<?php endif; ?>
</div>

