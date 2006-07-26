<?php
/**
 * @version $Id$
 * @package		Joomla.Framework
 * @subpackage	Utilities
 * @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

/**
* Makes a variable safe to display in forms
*
* Object parameters that are non-string, array, object or start with underscore
* will be converted
*
* @package Joomla.Framework
* @param object An object to be parsed
* @param int The optional quote style for the htmlspecialchars function
* @param string|array An optional single field name or array of field names not
*					 to be parsed (eg, for a textarea)
* @since 1.0
*/
function mosMakeHtmlSafe( &$mixed, $quote_style=ENT_QUOTES, $exclude_keys='' ) {
	if (is_object( $mixed )) {
		foreach (get_object_vars( $mixed ) as $k => $v) {
			if (is_array( $v ) || is_object( $v ) || $v == NULL || substr( $k, 1, 1 ) == '_' ) {
				continue;
			}
			if (is_string( $exclude_keys ) && $k == $exclude_keys) {
				continue;
			} else if (is_array( $exclude_keys ) && in_array( $k, $exclude_keys )) {
				continue;
			}
			$mixed->$k = htmlspecialchars( $v, $quote_style );
		}
	}
}

/**
* Replaces &amp; with & for xhtml compliance
*
* Needed to handle unicode conflicts due to unicode conflicts
*
* @package Joomla.Framework
* @since 1.0
*/
function ampReplace( $text ) {
	$text = str_replace( '&&', '*--*', $text );
	$text = str_replace( '&#', '*-*', $text );
	$text = str_replace( '&amp;', '&', $text );
	$text = preg_replace( '|&(?![\w]+;)|', '&amp;', $text );
	$text = str_replace( '*-*', '&#', $text );
	$text = str_replace( '*--*', '&&', $text );

	return $text;
}

/**
 * Maps an object to an array
 * @param object	The source object
 * @param boolean	True to recurve through multi-level objects
 * @param string	An optional regular expression to match on field names
 */
function mosObjectToArray($p_obj, $recurse = true, $regex = null)
{
	$result = null;
	if (is_object( $p_obj ))
	{
		$result = array();
		foreach (get_object_vars($p_obj) as $k => $v)
		{
			if ($regex)
			{
				if (!preg_match( $regex, $k ))
				{
					continue;
				}
			}
			if (is_object( $v ))
			{
				if ($recurse)
				{
					$result[$k] = mosObjectToArray($v);
				}
			}
			else
			{
				$result[$k] = $v;
			}
		}
	}
	return $result;
}

/**
 * Utility function redirect the browser location to another url
 *
 * @package Joomla.Framework
 * Can optionally provide a message.
 * @param string $url The URL to redirect to
 * @param string $msg A message to display on redirect
 * @since 1.0
 */
function josRedirect( $url, $msg='' )
{
   global $mainframe;

    /*
     * Instantiate an input filter and process the URL and message
     */
	jimport( 'phpinputfilter.inputfilter' );
	$iFilter = new InputFilter();
	$url = $iFilter->process( $url );
	if (!empty($msg)) {
		$msg = $iFilter->process( $msg );
	}

	if ($iFilter->badAttributeValue( array( 'href', $url ))) {
		$url = $mainframe->getBasePath();
	}

	/*
	 * If the message exists, prepare it (url encoding)
	 */
	if (trim( $msg )) {
	 	if (strpos( $url, '?' )) {
			$url .= '&josmsg=' . urlencode( $msg );
		} else {
			$url .= '?josmsg=' . urlencode( $msg );
		}
	}

	/*
	 * If the headers have been sent, then we cannot send an additional location header
	 * so we will output a javascript redirect statement.
	 */
	if (headers_sent()) {
		echo "<script>document.location.href='$url';</script>\n";
	} else {
		//@ob_end_clean(); // clear output buffer
		header( 'HTTP/1.1 301 Moved Permanently' );
		header( "Location: ". $url );
	}
	exit();
}

function josErrorAlert( $text, $action='window.history.go(-1);', $mode=1 ) {
	$text = nl2br( $text );
	$text = addslashes( $text );
	$text = strip_tags( $text );

	switch ( $mode ) {
		case 2:
			echo "<script>$action</script> \n";
			break;

		case 1:
		default:
			echo "<script>alert('$text'); $action</script> \n";
			echo '<noscript>';
			echo "$text\n";
			echo '</noscript>';
			break;
	}

	exit;
}

/**
 * Set the available masks for cleaning variables
 */
define("_J_NOTRIM"   , 1);
define("_J_ALLOWRAW" , 2);
define("_J_ALLOWHTML", 4);

/**
 * Utility method to clean a string variable using input filters
 *
 * Available Options masks:
 * 		_J_NOTRIM 		: Prevents the trimming of the variable
 * 		_J_ALLOWHTML	: Allows safe HTML in the variable
 * 		_J_ALLOWRAW		: Allows raw input
 *
 * @static
 * @param mixed $var The variable to clean
 * @param int $mask An options mask
 * @return mixed The cleaned variable
 * @since 1.5
 */
function josFilterValue( &$var, $mask = 0 )
{
	// Static input filters for specific settings

	static $noHtmlFilter = null;
	static $safeHtmlFilter = null;

	// Initialize variables
	$return = null;

	// Ensure the variable to clean is a string
	if (is_string($var))
	{
		// If the no trim flag is not set, trim the variable
		if (!($mask & 1))
		{
			$var = trim($var);
		}

		// Now we handle input filtering
		if ($mask & 2)
		{
			// If the allow raw flag is set, do not modify the variable
			$return = $var;
		}
		elseif ($mask & 4)
		{
			// If the allow html flag is set, apply a safe html filter to the variable
			if (is_null($safeHtmlFilter))
			{
				jimport( 'phpinputfilter.inputfilter' );
				$safeHtmlFilter = new InputFilter(null, null, 1, 1);
			}
			$return = $safeHtmlFilter->process($var);
		}
		else
		{
			// Since no allow flags were set, we will apply the most strict filter to the variable
			if (is_null($noHtmlFilter))
			{
				jimport( 'phpinputfilter.inputfilter' );
				$noHtmlFilter = new InputFilter(/* $tags, $attr, $tag_method, $attr_method, $xss_auto */
				);
			}
			$return = $noHtmlFilter->process($var);
		}
	}
	elseif (is_array($var))
	{
		// If the variable to clean is an array, recursively iterate through it
		foreach ($var as $k => $v)
		{
			$var[$k] = josFilterValue( $v, $mask );
		}
		$return = $var;
	}
	else
	{
		// If the variable is neither an array or string just return the raw value
		$return = $var;
	}
	return $return;
}

function mosTreeRecurse( $id, $indent, $list, &$children, $maxlevel=9999, $level=0, $type=1 ) {
	if (@$children[$id] && $level <= $maxlevel) {
		foreach ($children[$id] as $v) {
			$id = $v->id;

			if ( $type ) {
				$pre 	= '<sup>L</sup>&nbsp;';
				$spacer = '.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			} else {
				$pre 	= '- ';
				$spacer = '&nbsp;&nbsp;';
			}

			if ( $v->parent == 0 ) {
				$txt 	= $v->name;
			} else {
				$txt 	= $pre . $v->name;
			}
			$pt = $v->parent;
			$list[$id] = $v;
			$list[$id]->treename = "$indent$txt";
			$list[$id]->children = count( @$children[$id] );
			$list = mosTreeRecurse( $id, $indent . $spacer, $list, $children, $maxlevel, $level+1, $type );
		}
	}
	return $list;
}

/**
 * @package Joomla.Framework
 * @param string SQL with ordering As value and 'name field' AS text
 * @param integer The length of the truncated headline
 * @since 1.0
 */
function mosGetOrderingList( $sql, $chop='30' ) {

	$db =& JFactory::getDBO();
	$order = array();
	$db->setQuery( $sql );
	if (!($orders = $db->loadObjectList())) {
		if ($db->getErrorNum()) {
			echo $db->stderr();
			return false;
		} else {
			$order[] = mosHTML::makeOption( 1, JText::_( 'first' ) );
			return $order;
		}
	}
	$order[] = mosHTML::makeOption( 0, '0 '. JText::_( 'first' ) );
	for ($i=0, $n=count( $orders ); $i < $n; $i++) {

		if (JString::strlen($orders[$i]->text) > $chop) {
			$text = JString::substr($orders[$i]->text,0,$chop)."...";
		} else {
			$text = $orders[$i]->text;
		}

		$order[] = mosHTML::makeOption( $orders[$i]->value, $orders[$i]->value.' ('.$text.')' );
	}
	$order[] = mosHTML::makeOption( $orders[$i-1]->value+1, ($orders[$i-1]->value+1).' '. JText::_( 'last' ) );

	return $order;
}

/**
* Returns formated date according to current local and adds time offset
*
* @package Joomla.Framework
* @param string date in datetime format
* @param string format optional format for strftime
* @param offset time offset if different than global one
* @returns formated date
* @since 1.0
*/
function mosFormatDate( $date, $format="", $offset="" )
{
	global $mainframe;

	$lang = $mainframe->getLanguage();
	if ( $format == '' ) {
		// %Y-%m-%d %H:%M:%S
		$format = JText::_( 'DATE_FORMAT_LC' );
	}
	if ( $offset == '' ) {
		$offset = $mainframe->getCfg('offset');
	}
	if ( $date && ereg( "([0-9]{4})-([0-9]{2})-([0-9]{2})[ ]([0-9]{2}):([0-9]{2}):([0-9]{2})", $date, $regs ) ) {
		$date = mktime( $regs[4], $regs[5], $regs[6], $regs[2], $regs[3], $regs[1] );
		$date = $date > -1 ? strftime( $format, $date + ($offset*60*60) ) : '-';
	}

	// for Windows there is a need to convert the date string to utf-8.
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' && function_exists('iconv')) {
		return iconv($lang->getWinCP(), "UTF-8", $date);
	}

	return $date;
}

/**
* Returns current date according to current local and time offset
*
* @package Joomla.Framework
* @param string format optional format for strftime
* @returns current date
* @since 1.0
*/
function mosCurrentDate( $format="" )
{
	global $mainframe;

	if ($format=="") {
		$format = JText::_( 'DATE_FORMAT_LC' );
	}
	$date = strftime( $format, time() + ($mainframe->getCfg('offset')*60*60) );
	return $date;
}

/**
* Utility function to provide ToolTips
*
* @package Joomla.Framework
* @param string ToolTip text
* @param string Box title
* @returns HTML code for ToolTip
* @since 1.0
*/
function mosToolTip( $tooltip, $title='', $width='', $image='tooltip.png', $text='', $href='', $link=1 )
{
	global $mainframe;

	$tooltip = addslashes(htmlspecialchars($tooltip));
	$title   = addslashes(htmlspecialchars($title));

	$url = $mainframe->isAdmin() ? $mainframe->getSiteURL() : $mainframe->getBaseURL();

	if ( $width ) {
		$width = ', WIDTH, \''.$width .'\'';
	}

	if ( $title ) {
		$title = ', CAPTION, \''. JText::_( $title ) .'\'';
	}

	if ( !$text ) {
		$image 	= $url . 'includes/js/ThemeOffice/'. $image;
		$text 	= '<img src="'. $image .'" border="0" alt="'. JText::_( 'Tooltip' ) .'"/>';
	} else {
		$text 	= JText::_( $text, true );
    }

	$style = 'style="text-decoration: none; color: #333;"';

	if ( $href ) {
		$href = ampReplace( $href );
		$style = '';
	}

	$mousover = 'return overlib(\''. JText::_( $tooltip, true ) .'\''. $title .', BELOW, RIGHT'. $width .');';

	$tip = '<!--'. JText::_( 'Tooltip' ) .'--> \n';
	if ( $link ) {
		$tip = '<a href="'. $href .'" onmouseover="'. $mousover .'" onmouseout="return nd();" '. $style .'>'. $text .'</a>';
	} else {
		$tip = '<span onmouseover="'. $mousover .'" onmouseout="return nd();" '. $style .'>'. $text .'</span>';
	}

	return $tip;
}

/**
 * Method to process internal Joomla URLs
 *
 * @package Joomla.Framework
 * @param string $url Absolute or Relative URL to Joomla resource
 * @param int $ssl Secure state for the processed URL
 *    1: Make URL secure using global secure site URL
 *    0: Leave URL in the same secure state as it was passed to the function
 *   -1: Make URL unsecure using the global unsecure site URL
 * @param int $sef Search engine friendly state for the processed URL
 *    1: Make URL search engine friendly
 *    0: Leave URL in the same sef state as it was passed to the function
 * @since 1.5
 */
function josURL( $url, $ssl=0, $sef=1 ) {
	global $mainframe;

	/*
	 * Get the base request URL from the JApplication object
	 */
	$RURL = $mainframe->getBaseURL();

	/*
	 * First we need to get the secure/unsecure URLs.  To do this we get the
	 * request URL from the JApplication and do a quick test.  If the first 5
	 * characters of the RURL are 'https', then we are on an ssl connection over
	 * https and need to set our secure URL to the current request URL, if not,
	 * and the scheme is 'http', then we need to do a quick string manipulation
	 * to switch schemes.
	 */
	if ( substr( $RURL, 0, 5 ) == 'https' )
	{
		$secure 	= $RURL;
		$unsecure	= 'http'.substr( $RURL, 5 );
	} elseif ( substr( $RURL, 0, 4 ) == 'http' )
	{
		$secure		= 'https'.substr( $RURL, 4 );
		$unsecure	= $RURL;
	}

	/*
	 * If we want to SEF the url, and the SEF function exists... lets pass the
	 * url through it.
	 */
	if ( ( $sef == 1 ) && ( function_exists('sefRelToAbs' ) ) ) {
		$url = sefRelToAbs( $url );
	}

	/*
	 * Were we fed a relative URL?
	 */
	if ( substr( $url,0,4 ) != 'http' ) {
		$url = $RURL . $url;
	}

	/*
	 * Ensure that proper secure site url is used if ssl flag set and url
	 * doesn't already include it
	 */
	if ($ssl == 1 && strstr($url, $unsecure)) {
		$url = str_replace( $unsecure, $secure , $url );
	}

	/*
	 * Ok, now if the SSL flag is set to always unsecure, and we are in SSL
	 * mode, lets change the link to use the unsecure URL
	 */
	if ($ssl == -1 && strstr($url, $secure)) {
		$url = str_replace( $secure, $unsecure , $url );
	}

	return $url;
}

/**
* Prepares results from search for display
*
* @package Joomla.Framework
* @param string The source string
* @param int Number of chars to trim
* @param string The searchword to select around
* @return string
* @since 1.5
*/
function mosPrepareSearchContent( $text, $length=200, $searchword ) {
	// strips tags won't remove the actual jscript
	$text = preg_replace( "'<script[^>]*>.*?</script>'si", "", $text );
	$text = preg_replace( '/{.+?}/', '', $text);
	//$text = preg_replace( '/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is','\2', $text );
	// replace line breaking tags with whitespace
	$text = preg_replace( "'<(br[^/>]*?/|hr[^/>]*?/|/(div|h[1-6]|li|p|td))>'si", ' ', $text );

	return mosSmartSubstr( strip_tags( $text ), $length, $searchword );
}

/**
* returns substring of characters around a searchword
*
* @package Joomla.Framework
* @param string The source string
* @param int Number of chars to return
* @param string The searchword to select around
* @return string
* @since 1.0
*/
function mosSmartSubstr($text, $length=200, $searchword) {
  $wordpos = JString::strpos(JString::strtolower($text), JString::strtolower($searchword));
  $halfside = intval($wordpos - $length/2 - JString::strlen($searchword));
  if ($wordpos && $halfside > 0) {
	return '...' . JString::substr($text, $halfside, $length) . '...';
  } else {
	return JString::substr( $text, 0, $length);
  }
}
?>