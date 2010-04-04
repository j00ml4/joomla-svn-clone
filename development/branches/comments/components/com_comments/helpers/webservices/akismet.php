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
 * Akismet class
 *
 * @package		JXtended.Libraries
 * @subpackage	Webservices
 * @since		1.1
 */
class JAkismet
{
	/**
	 * Host to connect to for the service.
	 *
	 * @since	1.1
	 */
	const API_HOST = 'rest.akismet.com';

	/**
	 * API version for the service.
	 *
	 * @since	1.1
	 */
	const API_VERSION = '1.1';

	/**
	 * Site URL
	 *
	 * @var		string
	 * @since	1.1
	 */
	protected $_siteURL;

	/**
	 * Akismet API Key
	 *
	 * @var		string
	 * @since	1.1
	 */
	protected $_apiKey;

	/**
	 * Is the key valid?
	 *
	 * @var		boolean
	 * @since	1.1
	 */
	protected $_keyValid;

	/**
	 * Associative array of payload data for processing by the Akismet service.
	 *
	 * @var		array
	 * @since	1.1
	 */
	protected $_payload = array();

	/**
	 * JHttp client object for sending requests to the Akismet server.
	 *
	 * @var		object
	 * @since	1.1
	 */
	protected $_http;

	/**
	 * Server variables to ignore when sending Akismet requests.
	 *
	 * @var		array
	 * @since	1.1
	 */
	protected $_ignore = array(
			'HTTP_COOKIE',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED_HOST',
			'HTTP_MAX_FORWARDS',
			'HTTP_X_FORWARDED_SERVER',
			'REDIRECT_STATUS',
			'SERVER_PORT',
			'PATH',
			'DOCUMENT_ROOT',
			'SERVER_ADMIN',
			'QUERY_STRING',
			'PHP_SELF',
			'argv'
		);

	/**
	 * Static array of JAkismet instances.
	 *
	 * @var		array
	 * @since	1.1
	 */
	protected static $instances = array();

	/**
	 * Constructor.
	 *
	 * @param	string	The site URL
	 * @return	void
	 * @since	1.1
	 */
	protected function __construct($siteURL)
	{
		// Set the site URL.
		$this->_siteURL = $siteURL;

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
	 * Returns a JAkismet object for a given site and API key, only creating it
	 * if it doesn't already exist.
	 *
	 * <code>
	 *	<?php
	 *	jx('jx.webservices.akismet');
	 *	$akismet = JAkismet::getInstance({SITE_URL}, {API_KEY});
	 *	?>
	 * </code>
	 *
	 * @param	string	The site URL.
	 * @param	string	Akismet API key.
	 * @return	object	A JAkismet object.
	 * @since	1.1
	 */
	public static function getInstance($siteURL, $apiKey)
	{
		// Build the instance key.
		$key = md5($siteURL.$apiKey);

		// Only create the instance if it doesn't already exist.
		if (!isset(self::$instances[$key]))
		{
			// Instantiate a new JAkismet object
			$instance = new JAkismet($siteURL);

			// Set the API key.
			$valid = $instance->setApiKey($apiKey);
			if (!$valid) {
				JError::raiseWarning(1000, 'Your Akismet API Key is invalid');
			}
		}

		return self::$instances[$key];
	}

	/**
	 * Method to check if the set API key is valid.
	 *
	 * <code>
	 *	<?php
	 *	jx('jx.webservices.akismet');
	 *	$akismet = JAkismet::getInstance({SITE_URL}, {API_KEY});
	 *
	 *	// Is the API key valid?
	 *	if ($akismet->isApiKeyValid()) {
	 *		echo 'Yes!';
	 *	}
	 *	else {
	 *		echo 'No!';
	 *	}
	 *	?>
	 * </code>
	 *
	 * @return	boolean	True if valid.
	 * @since	1.1
	 */
	public function isApiKeyValid()
	{
		return $this->_keyValid;
	}

	/**
	 * Method to set the API key for the object.
	 *
	 * <code>
	 *	<?php
	 *	jx('jx.webservices.akismet');
	 *	$akismet = JAkismet::getInstance({SITE_URL}, {API_KEY});
	 *
	 *	// Set the new API key.
	 *	$valid = $akismet->setApiKey({NEW_API_KEY});
	 *	?>
	 * </code>
	 *
	 * @param	string	Akismet API key.
	 * @return	boolean	True if valid.
	 * @since	1.1
	 */
	public function setApiKey($key)
	{
		// If the key is already set and the valid state is set just return the valid state.
		if (($key == $this->_apiKey) && ($this->_keyValid !== null)) {
			return $this->_keyValid;
		}
		// Verify the key with the Akismet server.
		else
		{
			// Build the request URL and body.
			$requestURL = 'http://'.self::API_HOST.'/'.self::API_VERSION.'/verify-key';
			$requestBody = array('key'=>$key, 'blog'=>$this->_siteURL);

			// Post the request to the server.
			$response = $this->_http->post($requestURL, $requestBody);
			if ($response && ($response->code == 200)) {
				return ($response->body == 'valid');
			}
			else {
				return false;
			}
		}
	}

	/**
	 * Method to set comment data to the Akismet client for processing.
	 *
	 * <code>
	 *	<?php
	 *	jx('jx.webservices.akismet');
	 *	$akismet = JAkismet::getInstance({SITE_URL}, {API_KEY});
	 *
	 *	// Create and populate the data array.
	 *	$data = array(
	 *		'type' => {POST_TYPE},
	 *		'author' => {COMMENT_AUTHOR},
	 *		'email' => {COMMENT_AUTHOR_EMAIL},
	 *		'website' => {COMMENT_AUTHOR_WEBSITE},
	 *		'body' => {COMMENT_BODY},
	 *		'permalink' => {POST_URL}
	 *	);
	 *
	 *	// Set the comment to the Akismet client.
	 *	$akismet->setData($data);
	 *	?>
	 * </code>
	 *
	 * @param	mixed	Object or array to set as the payload data.
	 * @return	boolean	True on success
	 * @since	1.1
	 */
	public function setData($comment)
	{
		// Cast the input as an array.
		$comment = (array) $comment;

		// Reset the internal comment data array.
		$this->_payload = array();

		// Optional fields.
		$this->_payload['comment_type'] = (isset($comment['type'])) ? $comment['type'] : null;
		$this->_payload['comment_author'] = (isset($comment['author'])) ? $comment['author'] : null;
		$this->_payload['comment_author_email'] = (isset($comment['email'])) ? $comment['email'] : null;
		$this->_payload['comment_author_url'] = (isset($comment['website'])) ? $comment['website'] : null;
		$this->_payload['comment_content'] = (isset($comment['body'])) ? $comment['body'] : null;
		$this->_payload['permalink'] = (isset($comment['permalink'])) ? $comment['permalink'] : null;

		// Required fields.
		$this->_payload['user_ip'] = (isset($comment['user_ip'])) ? $comment['user_ip'] : ($_SERVER['REMOTE_ADDR'] != getenv('SERVER_ADDR')) ? $_SERVER['REMOTE_ADDR'] : getenv('HTTP_X_FORWARDED_FOR');
		$this->_payload['user_agent'] = (isset($comment['user_agent'])) ? $comment['user_agent'] : $_SERVER['HTTP_USER_AGENT'];
		$this->_payload['referrer'] = (isset($comment['referrer'])) ? $comment['referrer'] : $_SERVER['HTTP_REFERER'];
		$this->_payload['blog'] = (isset($comment['blog'])) ? $comment['blog'] : $this->_siteURL;

		// Iterate over the server variables to add useful information.
		foreach($_SERVER as $key => $value)
		{
			if(!in_array($key, $this->_ignore))
			{
				if($key == 'REMOTE_ADDR') {
					$this->_payload[$key] = $this->_payload['user_ip'];
				}
				else {
					$this->_payload[$key] = $value;
				}
			}
		}

		return true;
	}

	/**
	 * Query the Akismet and determine if the comment is spam or not
	 *
	 * <code>
	 *	<?php
	 *	jx('jx.webservices.akismet');
	 *	$akismet = JAkismet::getInstance({SITE_URL}, {API_KEY});
	 *
	 *	// Set the comment to the Akismet client.
	 *	$akismet->setData($comment);
	 *
	 *	// Lets see if the comment is spam.
	 *	if ($akismet->isSpam()) {
	 *		echo 'Yes! This is a spam comment';
	 *	} else {
	 *		echo 'No! This is NOT a spam comment';
	 *	}
	 *	?>
	 * </code>
	 *
	 * @return	boolean	True if the comment is spam.
	 * @since	1.1
	 */
	public function isSpam()
	{
		// Build the request URL.
		$url = 'http://'.$this->_apiKey.'.'.self::API_HOST.'/'.self::API_VERSION.'/comment-check';

		// Post the request to the server.
		$response = $this->_http->post($url, $this->_payload);
		if ($response && ($response->code == 200)) {
			return ($response->body == 'true');
		}
		else {
			return new JException('Invalid Server Response.', 1000, E_WARNING);
		}
	}

	/**
	 * Submit the comment as a spam comment to Akismet's server.
	 *
	 * <code>
	 *	<?php
	 *	jx('jx.webservices.akismet');
	 *	$akismet = JAkismet::getInstance({SITE_URL}, {API_KEY});
	 *
	 *	// Set the comment to the Akismet client.
	 *	$akismet->setData($comment);
	 *
	 *	// Submit the comment as spam to Akismet.
	 *	$akismet->submitSpam();
	 *	?>
	 * </code>
	 *
	 * @return	boolean	True on success.
	 * @since	1.1
	 */
	public function submitSpam()
	{
		// Build the request URL.
		$url = 'http://'.$this->_apiKey.'.'.self::API_HOST.'/'.self::API_VERSION.'/submit-spam';

		// Post the request to the server.
		$response = $this->_http->post($url, $this->_payload);
		return ($response && ($response->code == 200));
	}

	/**
	 * Submit the comment as a false-positive spam comment to Akismet's server.
	 *
	 * <code>
	 *	<?php
	 *	jx('jx.webservices.akismet');
	 *	$akismet = JAkismet::getInstance({SITE_URL}, {API_KEY});
	 *
	 *	// Set the comment to the Akismet client.
	 *	$akismet->setData($comment);
	 *
	 *	// Submit the comment as ham (false-positive) to Akismet.
	 *	$akismet->submitHam();
	 *	?>
	 * </code>
	 *
	 * @return	boolean	True on success.
	 * @since	1.1
	 */
	public function submitHam()
	{
		// Build the request URL.
		$url = 'http://'.$this->_apiKey.'.'.self::API_HOST.'/'.self::API_VERSION.'/submit-ham';

		// Post the request to the server.
		$response = $this->_http->post($url, $this->_payload);
		return ($response && ($response->code == 200));
	}
}
