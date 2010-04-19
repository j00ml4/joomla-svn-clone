<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_rating
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// attach the comments stylesheet to the document head
JHtml::stylesheet('social/comments.css', array(), true);

// add the appropriate include paths for helpers
JHtml::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_social'.DS.'helpers'.DS.'html');

$style		= $params->get('style', 0);
$heading	= $params->get('heading', 0);
?>

<div class="sharing">
<?php if ($heading > 0) : ?>
	<div class="sharethis">
<?php if ($heading == 1 or $heading == 2) : ?>
		<img src="<?php echo $baseurl; ?>modules/mod_social_share/images/share-icon-16x16.gif" alt="<?php echo JText::_('Comments_Share_This');?>" />
<?php endif; ?>
		<?php if ($heading == 2 or $heading == 3) : ?>
		<span><?php echo JText::_('Comments_Share_This');?></span>
<?php endif; ?>
	</div>
<?php endif; ?>

	<ul class="sharelist">
<?php if ($params->get('sharing_delicious')) : ?>
		<li>
			<a href="<?php echo modSocialShareHelper::getBookmark('delicious', $params); ?>" target="_blank" title="<?php echo JText::sprintf('Comments_Share_This_With', JText::_('DELICIOUS'));?>">
<?php if ($style == 0 or $style == 1) : ?>
				<img src="<?php echo $baseurl; ?>modules/mod_social_share/images/delicious.gif" alt="<?php echo JText::_('DELICIOUS');?>" />
<?php endif; ?>
<?php if ($style == 2 or $style == 1) : ?>
				<span><?php echo JText::_('DELICIOUS'); ?></span>
<?php endif; ?></a>
		</li>
<?php
	endif;
	if ($params->get('sharing_furl')) : ?>
		<li>
			<a href="<?php echo modSocialShareHelper::getBookmark('furl', $params); ?>" target="_blank" title="<?php echo JText::sprintf('Comments_Share_This_With', JText::_('FURL'));?>">
<?php if ($style == 0 or $style == 1) : ?>
				<img src="<?php echo $baseurl; ?>modules/mod_social_share/images/furl.gif" alt="<?php echo JText::_('FURL');?>" />
<?php endif; ?>
<?php if ($style == 2 or $style == 1) : ?>
				<span><?php echo JText::_('FURL'); ?></span>
<?php endif; ?></a>
		</li>
<?php
	endif;
	if ($params->get('sharing_yahoo')) : ?>
		<li>
			<a href="<?php echo modSocialShareHelper::getBookmark('yahoo_myweb', $params); ?>" target="_blank" title="<?php echo JText::sprintf('Comments_Share_This_With', JText::_('YAHOO_MYWEB'));?>">
<?php if ($style == 0 or $style == 1) : ?>
				<img src="<?php echo $baseurl; ?>modules/mod_social_share/images/yahoo_myweb.gif" alt="<?php echo JText::_('YAHOO_MYWEB');?>" />
<?php endif; ?>
<?php if ($style == 2 or $style == 1) : ?>
				<span><?php echo JText::_('YAHOO_MYWEB'); ?></span>
<?php endif; ?></a>
		</li>
<?php
	endif;
	if ($params->get('sharing_google')) : ?>
		<li>
			<a href="<?php echo modSocialShareHelper::getBookmark('google_bmarks', $params); ?>" target="_blank" title="<?php echo JText::sprintf('Comments_Share_This_With', JText::_('GOOGLE_BMARKS'));?>">
<?php if ($style == 0 or $style == 1) : ?>
				<img src="<?php echo $baseurl; ?>modules/mod_social_share/images/google_bmarks.gif" alt="<?php echo JText::_('GOOGLE_BMARKS');?>" />
<?php endif; ?>
<?php if ($style == 2 or $style == 1) : ?>
				<span><?php echo JText::_('GOOGLE_BMARKS'); ?></span>
<?php endif; ?></a>
		</li>
<?php
	endif;
	if ($params->get('sharing_blinklist')) : ?>
		<li>
			<a href="<?php echo modSocialShareHelper::getBookmark('blinklist', $params); ?>" target="_blank" title="<?php echo JText::sprintf('Comments_Share_This_With', JText::_('BLINKLIST'));?>">
<?php if ($style == 0 or $style == 1) : ?>
				<img src="<?php echo $baseurl; ?>modules/mod_social_share/images/blinklist.gif" alt="<?php echo JText::_('BLINKLIST');?>" />
<?php endif; ?>
<?php if ($style == 2 or $style == 1) : ?>
				<span><?php echo JText::_('BLINKLIST'); ?></span>
<?php endif; ?></a>
		</li>
<?php
	endif;
	if ($params->get('sharing_magnolia')) : ?>
		<li>
			<a href="<?php echo modSocialShareHelper::getBookmark('magnolia', $params); ?>" target="_blank" title="<?php echo JText::sprintf('Comments_Share_This_With', JText::_('MAGNOLIA'));?>">
<?php if ($style == 0 or $style == 1) : ?>
				<img src="<?php echo $baseurl; ?>modules/mod_social_share/images/magnolia.gif" alt="<?php echo JText::_('MAGNOLIA');?>" />
<?php endif; ?>
<?php if ($style == 2 or $style == 1) : ?>
				<span><?php echo JText::_('MAGNOLIA'); ?></span>
<?php endif; ?></a>
		</li>
<?php
	endif;
	if ($params->get('sharing_facebook')) : ?>
		<li>
			<a href="<?php echo modSocialShareHelper::getBookmark('facebook', $params); ?>" target="_blank" title="<?php echo JText::sprintf('Comments_Share_This_With', JText::_('FACEBOOK'));?>">
<?php if ($style == 0 or $style == 1) : ?>
				<img src="<?php echo $baseurl; ?>modules/mod_social_share/images/facebook.gif" alt="<?php echo JText::_('FACEBOOK');?>" />
<?php endif; ?>
<?php if ($style == 2 or $style == 1) : ?>
				<span><?php echo JText::_('FACEBOOK'); ?></span>
<?php endif; ?></a>
		</li>
<?php
	endif;
	if ($params->get('sharing_digg')) : ?>
		<li>
			<a href="<?php echo modSocialShareHelper::getBookmark('digg', $params); ?>" target="_blank" title="<?php echo JText::sprintf('Comments_Share_This_With', JText::_('DIGG'));?>">
<?php if ($style == 0 or $style == 1) : ?>
				<img src="<?php echo $baseurl; ?>modules/mod_social_share/images/digg.gif" alt="<?php echo JText::_('DIGG');?>" />
<?php endif; ?>
<?php if ($style == 2 or $style == 1) : ?>
				<span><?php echo JText::_('digg'); ?></span>
<?php endif; ?></a>
		</li>
<?php
	endif;
	if ($params->get('sharing_stumbleupon')) : ?>
		<li>
			<a href="<?php echo modSocialShareHelper::getBookmark('stumbleupon', $params); ?>" target="_blank" title="<?php echo JText::sprintf('Comments_Share_This_With', JText::_('STUMBLEUPON'));?>">
<?php if ($style == 0 or $style == 1) : ?>
				<img src="<?php echo $baseurl; ?>modules/mod_social_share/images/stumbleupon.gif" alt="<?php echo JText::_('STUMBLEUPON');?>" />
<?php endif; ?>
<?php if ($style == 2 or $style == 1) : ?>
				<span><?php echo JText::_('STUMBLEUPON'); ?></span>
<?php endif; ?></a>
		</li>
<?php
	endif;
	if ($params->get('sharing_technorati')) : ?>
		<li>
			<a href="<?php echo modSocialShareHelper::getBookmark('technorati', $params); ?>" target="_blank" title="<?php echo JText::sprintf('Comments_Share_This_With', JText::_('TECHNORATI'));?>">
<?php if ($style == 0 or $style == 1) : ?>
				<img src="<?php echo $baseurl; ?>modules/mod_social_share/images/technorati.gif" alt="<?php echo JText::_('TECHNORATI');?>" />
<?php endif; ?>
<?php if ($style == 2 or $style == 1) : ?>
				<span><?php echo JText::_('TECHNORATI'); ?></span>
<?php endif; ?></a>
		</li>
<?php
	endif;
	if ($params->get('sharing_newsvine')) : ?>
		<li>
			<a href="<?php echo modSocialShareHelper::getBookmark('newsvine', $params); ?>" target="_blank" title="<?php echo JText::sprintf('Comments_Share_This_With', JText::_('NEWSVINE'));?>">
<?php if ($style == 0 or $style == 1) : ?>
				<img src="<?php echo $baseurl; ?>modules/mod_social_share/images/newsvine.gif" alt="<?php echo JText::_('NEWSVINE');?>" />
<?php endif; ?>
<?php if ($style == 2 or $style == 1) : ?>
				<span><?php echo JText::_('NEWSVINE'); ?></span>
<?php endif; ?></a>
		</li>
<?php
	endif;
	if ($params->get('sharing_reddit')) : ?>
		<li>
			<a href="<?php echo modSocialShareHelper::getBookmark('reddit', $params); ?>" target="_blank" title="<?php echo JText::sprintf('Comments_Share_This_With', JText::_('REDDIT'));?>">
<?php if ($style == 0 or $style == 1) : ?>
				<img src="<?php echo $baseurl; ?>modules/mod_social_share/images/reddit.gif" alt="<?php echo JText::_('REDDIT');?>" />
<?php endif; ?>
<?php if ($style == 2 or $style == 1) : ?>
				<span><?php echo JText::_('REDDIT'); ?></span>
<?php endif; ?></a>
		</li>
<?php
	endif;
	if ($params->get('sharing_tailrank')) : ?>
		<li>
			<a href="<?php echo modSocialShareHelper::getBookmark('tailrank', $params); ?>" target="_blank" title="<?php echo JText::sprintf('Comments_Share_This_With', JText::_('TAILRANK'));?>">
<?php if ($style == 0 or $style == 1) : ?>
				<img src="<?php echo $baseurl; ?>modules/mod_social_share/images/tailrank.gif" alt="<?php echo JText::_('TAILRANK');?>" />
<?php endif; ?>
<?php if ($style == 2 or $style == 1) : ?>
				<span><?php echo JText::_('TAILRANK'); ?></span>
<?php endif; ?></a>
		</li>
<?php
	endif;
	if ($params->get('sharing_twitter')) : ?>
		<li>
			<a href="<?php echo modSocialShareHelper::getBookmark('twitter', $params); ?>" target="_blank" title="<?php echo JText::sprintf('Comments_Share_This_With', JText::_('TWITTER'));?>">
<?php if ($style == 0 or $style == 1) : ?>
				<img src="<?php echo $baseurl; ?>modules/mod_social_share/images/twitter.gif" alt="<?php echo JText::_('TWITTER');?>" />
<?php endif; ?>
<?php if ($style == 2 or $style == 1) : ?>
				<span><?php echo JText::_('TWITTER'); ?></span>
<?php endif; ?></a>
		</li>
<?php endif; ?>
	</ul>
<?php if ($params->get('sharing_email')) : ?>
	<div class="email">
		<?php echo JHtml::_('comments.email', JText::_('Comments_Email_Friend'), $params->get('link', modSocialShareHelper::getCurrentPageURL())); ?>
	</div>
<?php endif; ?>
</div>
