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

jx('jx.client.http');

/**
 * Ping class for sending ping requests to XML-RPC ping servers.
 *
 * @package		JXtended.Libraries
 * @subpackage	Webservices
 * @version		1.1
 */
class JPing
{
	/**
	 * Static container for the XML entity translation table.
	 *
	 * @var		array
	 * @since	1.1
	 */
	protected static $table = array();

	/**
	 * Ping server array.
	 *
	 * @var		array
	 * @since	1.1
	 */
	protected $_servers = array();

	/**
	 * JHttp client object for sending ping requests.
	 *
	 * @var		object
	 * @since	1.1
	 */
	protected $_http;

	/**
	 * Object Constructor.
	 *
	 * @return	void
	 * @since	1.1
	 */
	public function __construct()
	{
		// Create the HTTP client object with a default timeout of 5 seconds.
		$this->_http = new JHttp(array('timeout' => 5));

		// Add the Google Blog ping server.
		$this->registerServer('google', 'blogsearch.google.com', '/ping/RPC2', 'weblogUpdates.extendedPing');

		// Add the Weblogs.com ping server.
		$this->registerServer('weblogs', 'rpc.weblogs.com', '/RPC2');

		// Add the Blo.gs ping server.
		$this->registerServer('blo.gs', 'ping.blo.gs');

		// Add the Ping-o-Matic ping server.
		$this->registerServer('pingomatic', 'rpc.pingomatic.com', '/RPC2');

		// Add the Technorati ping server.
		$this->registerServer('technorati', 'rpc.technorati.com', '/rpc/ping');

		// Add the Audio.Weblogs.com ping server.
		$this->registerServer('weblogs.audio', 'audiorpc.weblogs.com', '/RPC2');
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
	 * Method to get the list of available ping servers.
	 *
	 * <code>
	 *	<?php
	 *	jx('jx.webservices.ping');
	 *	$ping = new JPing();
	 *
	 *	// Get the list of available ping servers.
	 *	$servers = $ping->getAvailableServers();
	 *	?>
	 * </code>
	 *
	 * @return	array	Array of available ping servers.
	 * @since	1.1
	 */
	public function getAvailableServers()
	{
		return array_keys($this->_servers);
	}

	/**
	 * Method to set the details for a specific ping server.
	 *
	 * <code>
	 *	<?php
	 *	jx('jx.webservices.ping');
	 *	$ping = new JPing();
	 *
	 *	// Register the Google Blog ping server.
	 *	$ping->registerServer('Google', 'blogsearch.google.com', '/ping/RPC2', 'weblogUpdates.extendedPing');
	 *	?>
	 * </code>
	 *
	 * @param	string	The name of the ping server.
	 * @param	string	The host name where the server resides.
	 * @param	string	The path on the host to use.
	 * @param	string	The XML-RPC method to call.
	 * @param	integer	The port to connect on.
	 * @return	boolean	True on success.
	 * @since	1.1
	 */
	public function registerServer($name, $host, $path = '/', $method = 'weblogUpdates.ping', $port = 80)
	{
		$this->_servers[$name] = array('host' => $host, 'port' => $port, 'path' => $path, 'method' => $method);
	}

	/**
	 * Method to send a ping to an XML-RPC ping server which supports the weblogs.com interface.
	 *
	 * <code>
	 *	<?php
	 *	jx('jx.webservices.ping');
	 *	$ping = new JPing();
	 *
	 *	// Get the list of available ping servers.
	 *	$servers = $ping->getServerList();
	 *
	 *	// Ping all servers.
	 *	foreach ($servers as $server)
	 *	{
	 *		$ping->send($server, {SITE_NAME}, {SITE_URL} [, {PAGE_URL}] [, {PAGE_FEED}] [, {CATEGORIES}]);
	 *	}
	 *	?>
	 * </code>
	 *
	 * @param	string	The name of the server to ping
	 * @param	string	The name of the site.
	 * @param	string	The site URL.
	 * @param	string	The page URL.
	 * @param	string	The page feed URL.
	 * @param	array	An array of categories to apply to the ping.
	 * @return	boolean	True on success
	 * @since	1.1
	 */
	public function send($serverName, $siteName, $siteURL, $pageURL = null, $pageFeed = null, $categories = array())
	{
		// Get the appropriate server data if it exists.
		if (isset($this->_servers[$serverName])) {
			$server = $this->_servers[$serverName];
		}
		else {
			return false;
		}

		// Get the ping server url.
		$url = 'http://'.$server['host'].':'.$server['port'].$server['path'];
		$payload = $this->_buildRequest($server['method'], $siteName, $siteURL, $pageURL, $pageFeed, $categories);

		// Post the request to the server.
		$response = $this->_http->post($url, $payload, array('Content-Type' => 'text/xml'));

		// If the request was sent and the response was valid process it.
		if ($response && ($response->code == 200))
		{
			return $this->_parseResponse($response->body);
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
	 * Method to build the XML-RPC request payload given a set of ping arguments.
	 *
	 * @param	string	The ping method.
	 * @param	string	The name of the site.
	 * @param	string	The site URL.
	 * @param	string	The page URL.
	 * @param	string	The page feed URL.
	 * @param	array	An array of categories to apply to the ping.
	 * @return	string	The request payload.
	 * @since	1.1
	 */
	protected function _buildRequest($method, $siteName, $siteURL, $pageURL = null, $pageFeed = null, $categories = array())
	{
		// Initialize variables.
		$request  = array();
		$extended = ($method == 'weblogUpdates.extendedPing');

		// Open the request.
		$request[] = '<?xml version="1.0"?>';
		$request[] = '<methodCall>';
		$request[] = '<methodName>'.self::xmlEntities($method).'</methodName>';
		$request[] = '<params>';

		// REQUIRED: Name of the site.
		$request[] = '<param>';
		$request[] = '<value>'.self::xmlEntities($siteName).'</value>';
		$request[] = '</param>';

		// REQUIRED: URL of the site or RSS feed.
		$request[] = '<param>';
		$request[] = '<value>'.self::xmlEntities((!$extended && !empty($pageURL)) ? $pageURL : $siteURL).'</value>';
		$request[] = '</param>';

		// REQUIRED FOR EXTENDED: URL of the page to be checked for changes.
		if ($extended || !empty($pageURL) || !empty($pageFeed) || !empty($categories))
		{
			$request[] = '<param>';
			$request[] = '<value>'.self::xmlEntities($pageURL).'</value>';
			$request[] = '</param>';
		}

		// REQUIRED FOR EXTENDED: URL of an RSS, ATOM or RDF feed.
		if ($extended || !empty($pageFeed) || !empty($categories))
		{
			$request[] = '<param>';
			$request[] = '<value>'.self::xmlEntities($pageFeed).'</value>';
			$request[] = '</param>';
		}

		// Categories.
		if (!empty($categories))
		{
			$request[] = '<param>';
			$request[] = '<value>'.self::xmlEntities(implode('|', $categories)).'</value>';
			$request[] = '</param>';
		}

		// Close the request.
		$request[] = '</params>';
		$request[] = '</methodCall>';

		return implode($request);
	}

	/**
	 * Metho to parse the XML-RPC response body looking for the error state to return.
	 *
	 * @param	string	XML-RPC response body.
	 * @return	boolean	True if no errors present.
	 * @since	1.1
	 */
	protected function _parseResponse($response)
	{
		// Parse the document body.
		if (!$xml = simplexml_load_string($response)) {
			return false;
		}

		// Process the document.
		if ($xml->getName() != 'methodResponse') {
			return false;
		}

		// Get the response value struct.
		if (isset($xml->params[0]->param[0]->value[0]->struct[0])) {
			$struct = $xml->params[0]->param[0]->value[0]->struct[0];
		}
		else {
			return false;
		}

		// Look for the error state in the struct.
		if ($struct instanceof SimpleXMLElement)
		{
			foreach ($struct->children() as $member)
			{
				if ((string)$member->name == 'flerror') {
					return ((string)$member->value == '1');
				}
			}
		}

		return false;
	}
}
