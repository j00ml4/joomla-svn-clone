<?php
/**
 * @version		$Id$
 * @package		JXtended.Comments
 * @subpackage	mod_social_rating
 * @copyright	Copyright (C) 2008 - 2009 JXtended, LLC. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://jxtended.com
 */

defined('_JEXEC') or die('Invalid Request.');

// Build a URL for the item.
$uri	= &JURI::getInstance();
$base	= $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
$url	= urlencode($params->get('link', modCommentsShareHelper::getCurrentPageURL()));
if (strpos('://', $url) === false) {
	$url = $base.$url;
}
?>

<script src="http://feeds.feedburner.com/~s/<?php echo $params->get('feedflarepath', 'joomla'); ?>?i=<?php  echo $url; ?>" type="text/javascript" charset="utf-8"></script>