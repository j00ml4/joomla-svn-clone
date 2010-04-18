<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_rating
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Invalid Request.');

// Build a URL for the item.
$uri	= &JURI::getInstance();
$base	= $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
$url	= urlencode($params->get('link', modSocialShareHelper::getCurrentPageURL()));
if (strpos('://', $url) === false) {
	$url = $base.$url;
}
?>

<script src="http://feeds.feedburner.com/~s/<?php echo $params->get('feedflarepath', 'joomla'); ?>?i=<?php  echo $url; ?>" type="text/javascript" charset="utf-8"></script>