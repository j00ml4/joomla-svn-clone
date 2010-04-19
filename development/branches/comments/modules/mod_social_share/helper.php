<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_rating
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class modSocialShareHelper
{
	/**
	 * Method to return a formatted URL string for a social bookmarking site
	 *
	 * @param	string	$site	The social bookmarking site
	 * @param	string	$url	URL to the page to bookmark
	 * @param	string	$title	The title of the page to bookmark
	 * @return	mixed	The social bookmarking URL for the page or boolean false if the site doesn't exist
	 * @since	1.0
	 */
	public function getBookmark($site, $params)
	{
		$result	= false;

		$document	= &JFactory::getDocument();
		$uri		= &JURI::getInstance();
		$bookmarks	= modSocialShareHelper::addBookmarkArray();

		$base	= $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
		$url	= $params->get('route', modSocialShareHelper::getCurrentPageURL());
		$title	= $params->get('title', $document->getTitle());

		// Get the route if not an absolute path.
		if (strpos($url, '://') === false) {
			$url = $base.JRoute::_($url);
		}

		// URL encode the properties.
		$url	= urlencode($url);
		$title	= urlencode($title);

		if (!empty($bookmarks[$site])) {
			$result	= str_replace(array('{URI}', '{TITLE}'), array($url, $title), $bookmarks[$site]);
		}

		return JFilterOutput::ampReplace($result);
	}

	function getCurrentPageURL()
	{
		// get a URI object and URI string of the current page
		$uri	= &JFactory::getURI();
		$url	= $uri->toString(array('scheme', 'user', 'pass', 'host', 'port', 'path', 'query'));
		return $url;
	}

	function addBookmarkArray($array=null)
	{
		static $bookmarks;

		if (!is_array($bookmarks)) {
			$bookmarks = array(
				'delicious'=>'http://del.icio.us/post?url={URI}&title={TITLE}',
				'furl'=>'http://furl.net/storeIt.jsp?u={URI}&t={TITLE}',
				'yahoo_myweb'=>'http://myweb2.search.yahoo.com/myresults/bookmarklet?u={URI}&t={TITLE}',
				'google_bmarks'=>'http://www.google.com/bookmarks/mark?op=edit&bkmk={URI}&title={TITLE}',
				'blinklist'=>'http://blinklist.com/index.php?Action=Blink/addblink.php&Url={URI}&Title={TITLE}',
				'magnolia'=>'http://ma.gnolia.com/bookmarklet/add?url={URI}&title={TITLE}',
				'facebook'=>'http://www.facebook.com/share.php?u={URI}',
				'digg'=>'http://digg.com/submit?phase=2&url={URI}&title={TITLE}',
				'stumbleupon'=>'http://www.stumbleupon.com/submit?url={URI}&title={TITLE}',
				'technorati'=>'http://www.technorati.com/faves?add={URI}',
				'newsvine'=>'http://www.newsvine.com/_tools/seed&save?popoff=0&u={URI}&h={TITLE}',
				'reddit'=>'http://reddit.com/submit?url={URI}&title={TITLE}',
				'tailrank'=>'http://tailrank.com/share/?link_href={URI}&title={TITLE}',
				'twitter'=>'http://twitter.com/home?status=Reading {TITLE} {URI}'
			);
		}

		if (is_array($array)) {
			$bookmarks = array_merge($bookmarks, $array);
		}

		return $bookmarks;
	}

	function isBlocked($params)
	{
		// import library dependencies
		require_once JPATH_SITE.'/components/com_social/helpers/blocked.php';

		// run some tests to see if the comment submission should be blocked
		$blocked = (CommentHelper::isBlockedUser($params) or CommentHelper::isBlockedIP($params));

		return $blocked;
	}
}
