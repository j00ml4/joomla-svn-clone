<?php
/**
 * @version		$Id$
 * @package		JXtended.Libraries
 * @subpackage	Webservices
 * @copyright	Copyright (C) 2008 - 2009 JXtended, LLC. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://jxtended.com
 */

defined('JPATH_BASE') or die;

jx('joomla.environment.request');
jx('joomla.environment.uri');
jx('joomla.utilities.string');

/**
 * Trackback Class.
 *
 * @see http://www.sixapart.com/pronet/docs/trackback_spec
 *
 * @package		JXtended.Libraries
 * @subpackage	Webservices
 * @version		1.1
 */
class JTrackback
{
	/**
	 * Static container for the XML entity translation table.
	 *
	 * @var		array
	 * @since	1.1
	 */
	protected static $table = array();

	/**
	 * JHttp client object for sending ping requests.
	 *
	 * @var		object
	 * @since	1.1
	 */
	protected $_http;

	/**
	 * Constructor.
	 *
	 * @return	void
	 * @since	1.1
	 */
	protected function __construct()
	{
		// Create the HTTP client object with a default timeout of 5 seconds.
		$this->_http = new JHttp(array('timeout' => 5));
	}

	/**
	 * Destructor.
	 *
	 * @return	void
	 * @since	1.1
	 */
	public function __destruct()
	{
		unset($this->_http);
	}

	/**
	 * Returns the global JTrackback object, only creating it if it doesn't already exist.
	 *
	 * <code>
	 *	<?php
	 *	jx('jx.webservices.trackback');
	 *	$trackback = JTrackback::getInstance();
	 *	?>
	 * </code>
	 *
	 * @return	object	A JTrackback object.
	 * @since	1.1
	 */
	public static function getInstance()
	{
		static $instance;

		// Only create the instance if it doesn't already exist.
		if (empty($instance)) {
			$instance = new JTrackback();
		}

		return $instance;
	}

	/**
	 * Method to generate an RDF string representing metadata about the entry,
	 * allowing clients to auto-discover the TrackBack Ping URL.
	 *
	 * NOTE: The page date must be in RFC 822 datetime format, it is easiest
	 * to use JDate::toRFC822().
	 *
	 * @see http://www.faqs.org/rfcs/rfc822.html
	 *
	 * <code>
	 *	<?php
	 *	// Get data about the page to generate the RDF string for including trackback URI.
	 *
	 *	// Get a JDate object representing the page publishing date.
	 *	$date = JFactory::getDate({PAGE_PUBLISHED_DATE});
	 *
	 *	// Generate the trackback RDF string.
	 *	jx('jx.webservices.trackback');
	 *	$rdf = JTrackback::getDiscoveryRdf({PAGE_URL}, {PAGE_TRACKBACK_URL}[, {PAGE_TITLE}[, $date->toRFC822()[, {POST_AUTHOR}]]]);
	 *	?>
	 * </code>
	 *
	 * @param	string	The url of the page for which to get the RDF string.
	 * @param	string	The trackback url of the page for which to get the RDF string.
	 * @param	string	The title of the page for which to get the RDF string.
	 * @param	string	The publishing date of the page for which to get the RDF string.
	 * @param	string	The author of the page for which to get the RDF string.
	 * @return	string	The RDF trackback string.
	 * @since	1.1
	 */
	public static function getDiscoveryRdf($url, $trackback, $title = null, $date = null, $author = null)
	{
		$data[] = '<!-- ';
		$data[] = '<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"';
		$data[] = '	xmlns:dc="http://purl.org/dc/elements/1.1/"';
		$data[] = '	xmlns:trackback="http://madskills.com/public/xml/rss/module/trackback/">';
		$data[] = '	<rdf:Description';
		$data[] = '	 rdf:about="'.self::xmlEntities($url).'"';
		$data[] = '	 dc:identifier="'.self::xmlEntities($url).'"';
		$data[] = '	 trackback:ping="'.self::xmlEntities($trackback).'"';

		// Add the title if available.
		if (!empty($title)) {
			$data[] = '	 dc:title="'.self::xmlEntities($title).'"';
		}

		// Add the date if available.
		if (!empty($date)) {
			$data[] = '	 dc:date="'.$date.'"';
		}

		// Add the author if available.
		if (!empty($author)) {
			$data[] = '	 dc:creator="'.self::xmlEntities($author).'"';
		}

		$data[] = ' />';
		$data[] = '</rdf:RDF>';
		$data[] = '-->';
		$data[] = null;

		return implode("\n", $data);
	}

	/**
	 * Method to get verified trackback data from the post data.  If a link cannot be found
	 * at the posted URL then the trackback is considered invalid and the method will return
	 * false.  Otherwise the method will return an array of data from the trackback request.
	 *
	 * <code>
	 *	<?php
	 *	jx('jx.webservices.trackback');
	 *	$trackback = new JTrackback();
	 *
	 *	if ($trackback->getVerifiedData({LOCAL_PAGE_URL}[, {REMOTE_PAGE_URL}])) {
	 *		echo 'Verified!';
	 *	}
	 *	else {
	 *		echo 'Fail!';
	 *	}
	 *	?>
	 * </code>
	 *
	 * @param	string	The url of the page for which the trackback was sent. [Local page url used for verification]
	 * @param	string	The url of the trackback page.
	 * @return	mixed	Boolean false for unverifiable trackback or an array of trackback data.
	 * @since	1.1
	 */
	public function getVerifiedData($target, $url = null)
	{
		// Make sure we have the URL from the trackback post.
		$url = (empty($url)) ? JRequest::getString('url', null, 'post') : $url;

		// Attept to get the data from the URL.
		$response = $this->_http->get($url);

		// If the request was sent and the response was valid process it.
		if ($response && ($response->code == 200))
		{
			// Discover all unique URIs from the text.
			$matches = array();
			if (preg_match_all('/<a\s+href=["\']([^"\']+)["\']/i', $response->body, $matches, PREG_PATTERN_ORDER))
			{
				// Get all the unique URIs.
				$uris = array_unique($matches[1]);

				// Check to see if the target is in the found URIs.
				if (in_array($target, $uris))
				{
					// Build the trackback data array.
					$data = array();
					$data['url']		= $url;
					$data['title']		= JRequest::getString('title', null, 'post');
					$data['excerpt']	= JRequest::getString('excerpt', null, 'post');
					$data['siteName']	= JRequest::getString('blog_name', null, 'post');

					return $data;
				}

				// Check to see if the target is in the found URIs while escaping &s.
				elseif (in_array(preg_replace("/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,3};)/", '&amp;' , $target), $uris))
				{
					// Build the trackback data array.
					$data = array();
					$data['url']		= $url;
					$data['title']		= JRequest::getString('title', null, 'post');
					$data['excerpt']	= JRequest::getString('excerpt', null, 'post');
					$data['siteName']	= JRequest::getString('blog_name', null, 'post');

					return $data;
				}
			}
		}

		return false;
	}

	/**
	 * Method to generate the XML response message to send for a trackback ping request.
	 *
	 * <code>
	 *	<?php
	 *	jx('jx.webservices.trackback');
	 *	$trackback = new JTrackback();
	 *
	 *	// Verify and attempt to store the trackback data.
	 *
	 *	$response = $trackback->getResponseXml({SUCCESS}[, {ERROR_MESSAGE}]);
	 *
	 *	// Echo the response to the browser and quit, but do not forget to set the mime type to 'text/xml'.
	 * </code>
	 *
	 * @param	boolean Trackback success state.
	 * @param	string	Optional error message.
	 * @return	string	The XML response.
	 * @since	1.1
	 */
	public function getResponseXml($success = false, $error = '')
	{
		// Default error response in case of problems...
		if (!$success && empty($error)) {
			$error = 'Unable to log trackback.';
		}

		// Start XML response
		$response[] = '<?xml version="1.0"?>';
		$response[] = '<response>';
		// Add response state
		if ($success) {
			// Successful trackback
			$response[] = '	<error>0</error>';
		} else {
			// Trackback failure
			$response[] = '	<error>1</error>';
			$response[] = '	<message>'.self::xmlEntities($error).'</message>';
		}
		// End XML response
		$response[] = '</response>';

		return implode("\n", $response);
	}

	/**
	 * Method to search text for trackback URIs.  This method will extract a list
	 * of all links from the text and search the documents located at the URIs for trackback
	 * RDF strings and return a list of trackback URIs.
	 *
	 * <code>
	 *	<?php
	 *	jx('jx.webservices.trackback');
	 *	$trackback = new JTrackback();
	 *
	 *	// Get possible trackback URIs from a page's text
	 *	$uris = $trackback->discovery({PAGE_TEXT});
	 *	?>
	 * </code>
	 *
	 * @param	string	The text within which to discover trackback enabled URIs.
	 * @return	array	Trackback URIs.
	 * @since	1.1
	 */
	public function discovery($text)
	{
		// Initialize variables.
		$trackbacks = false;

		// Discover all unique URIs from the text.
		$matches = array();
		if (preg_match_all('/<a\s+href=["\']([^"\']+)["\']/i', $text, $matches, PREG_PATTERN_ORDER)) {
			$uris = array_unique($matches[1]);
		}
		// No links found so return false.
		else {
			return false;
		}

		// Get all trackback URIs from the contents of the document located at the discovered URIs.
		foreach($uris as $uri)
		{
			if ($trackback = $this->_findTrackbackFromRdfAtUri($uri)) {
				$trackbacks[] = $trackback;
			}
		}

		return (!empty($trackbacks)) ? $trackbacks : false;
	}

	/**
	 * Method to send a trackback ping to a specified trackback URI.  This will notify
	 * the site to which you are sending the ping that a local page references and links
	 * to a page on the site.
	 *
	 * <code>
	 *	<?php
	 *	jx('jx.webservices.trackback');
	 *	$trackback = new JTrackback();
	 *
	 *	// Get possible trackback URIs from a page's text
	 *	$uris = $trackback->discovery({PAGE_TEXT});
	 *
	 *	// Send trackback pings to discovered trackback enabled URIs
	 *	if ($uris) {
	 *		foreach ($uris as $uri)
	 *		{
	 *			// Attempt to send a trackback ping
	 *			if ($trackback->send($uri, {PAGE_URL}[, {PAGE_TITLE}[, {PAGE_EXCERPT}[, {SITE_NAME}]]])) {
	 *				echo 'Trackback sent to: '.$link;
	 *			}
	 *			else {
	 *				echo 'Trackback failed to: '.$link;
	 *			}
	 *		}
	 *	}
	 *	else {
	 *		echo 'No trackbacks enabled URIs were discovered';
	 *	}
	 *	?>
	 * </code>
	 *
	 * @param	string	The target URI to post the trackback to.
	 * @param	string	The URI of the page to send the trackback regarding.
	 * @param	string	The title of the page to send the trackback regarding.
	 * @param	string	An excerpt from the page to send the trackback regarding.
	 * @param	string	The name of the Web site to send the trackback regarding.
	 * @return	boolean	True on success
	 * @since	1.1
	 */
	public function send($target, $url, $title = null, $excerpt = null, $siteName = null)
	{
		// Sanitize and prepare the excerpt if it exists.
		if (!empty($excerpt)) {
			$excerpt = $this->_getSnippet(strip_tags($excerpt));
		}

		// Build the post payload array to send.
		$payload = array('url' => $url);

		// If the title exists add it to the post payload.
		if (!empty($title)) {
			$payload['title'] = $title;
		}

		// If the excerpt exists add it to the post payload.
		if (!empty($excerpt)) {
			$payload['excerpt'] = $excerpt;
		}

		// If the site name exists add it to the post payload.
		if (!empty($siteName)) {
			$payload['blog_name'] = $siteName;
		}

		// Post the request to the server.
		$response = $this->_http->post($target, $payload);

		// If the request was sent and the response was valid process it.
		if ($response && ($response->code == 200)) {
			return strpos($response->body, '<error>0</error>') ? true : false;
		}
		else {
			return false;
		}
	}

	/**
	 * Method to convert translate XML entities to their escaped (numeric) form.
	 *
	 * @param	string	The input string to translate.
	 * @return	string	The translated string.
	 * @since	1.1
	 */
	static protected function xmlEntities($input)
	{
		// Only build the translation table if it doesn't already exist.
		if (empty(self::$table)) {
			// Remove the special characters from the entities and set the translation table.
			self::$table = array_diff(get_html_translation_table(HTML_ENTITIES), get_html_translation_table(HTML_SPECIALCHARS));
		}

		// Translate the input string with the entity translation table.
		$input = strtr($input, self::$table);

		// Replace & with &amp;
		$input = preg_replace("/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,3};)/", '&amp;' , $input);

		return $input;
	}

	/**
	 * Truncates text blocks over the specified character limit. The
	 * behavior will not truncate an individual word, it will find the first
	 * space that is within the limit and truncate at that point. This
	 * method is UTF-8 safe.
	 *
	 * @param	string	The text to truncate.
	 * @param	integer	The maximum length of the text.
	 * @return	string	The truncated text.
	 * @since	1.1
	 */
	protected function _getSnippet($text, $length = 255)
	{
		// Truncate the item text if it is too long.
		if ($length > 0 && JString::strlen($text) > $length)
		{
			// Find the first space within the allowed length.
			$tmp = JString::substr($text, 0, $length);
			$tmp = JString::substr($tmp, 0, JString::strrpos($tmp, ' '));

			// If we don't have 3 characters of room, go to the second space within the limit.
			if (JString::strlen($tmp) >= $length - 3) {
				$tmp = JString::substr($tmp, 0, JString::strrpos($tmp, ' '));
			}

			$text = $tmp.'...';
		}

		return $text;
	}

	/**
	 * Method to search for a trackback RDF tag within a document found at a given URI.
	 *
	 * @param	string	URI of the document to search.
	 * @return	mixed	Boolean false if no RDF tag found or the found RDF string.
	 * @since	1.1
	 */
	protected function _findTrackbackFromRdfAtUri($uri)
	{
		// Initialize variables.
		$rdfs = array();

		// Attept to get the data from the URI.
		$response = $this->_http->get($uri);

		// If the request was sent and the response was valid process it.
		if ($response && ($response->code == 200))
		{
			// Extract any <rdf> tags from the document and look for a trackback URI match.
			preg_match_all('/(<rdf:RDF.*?<\/rdf:RDF>)/sm', $response->body, $rdfs, PREG_SET_ORDER);
			for ($i=0, $n=count($rdfs[1]); $i < $n; $i++)
			{
				// Check to see if we have a trackback URI match in the <rdf> tag.
				if (preg_match('|dc:identifier="'.preg_quote($uri).'"|ms', $rdfs[$i][1]))
				{
					// Do we have a trackback ping url in the RDF?
					$matches = array();
					if (preg_match('/trackback:ping="([^"]+)"/', trim($rdfs[$i][1]), $matches)) {
						return trim($matches[1]);
					}
				}
			}
		}

		return false;
	}
}
