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
jx('jx.client.http');

/**
 * Class to implement the CAPTCHA functions of the reCAPTCHA Web service.
 *
 * @see	http://www.recaptcha.net
 *
 * @package		JXtended.Libraries
 * @subpackage	Webservices
 * @since		1.1
 */
class JRecaptcha
{
	/**
	 * reCAPTCHA API Server.
	 *
	 * @since	1.1
	 */
	const SERVER = 'http://api.recaptcha.net';

	/**
	 * reCAPTCHA SSL API Server.
	 *
	 * @since	1.1
	 */
	const SERVER_SSL = 'https://api-secure.recaptcha.net';

	/**
	 * reCAPTCHA API Verification Server.
	 *
	 * @since	1.1
	 */
	const SERVER_VERIFY = 'http://api-verify.recaptcha.net';

	/**
	 * Public key
	 *
	 * @var		string
	 * @since	1.1
	 */
	protected $_publicKey;

	/**
	 * Private key
	 *
	 * @var		string
	 * @since	1.1
	 */
	protected $_privateKey;

	/**
	 * JHttp client object for sending requests to the reCAPTCHA servers.
	 *
	 * @var		object
	 * @since	1.1
	 */
	protected $_http;

	/**
	 * Constructor.
	 *
	 * @param	string	reCAPTCHA public key.
	 * @param	string	reCAPTCHA private key.
	 * @param	array	A configuration array.
	 * @return	void
	 * @since	1.1
	 */
	protected function __construct($public, $private, $config = array())
	{
		// Create the HTTP client object with a default timeout of 5 seconds.
		$this->_http = new JHttp(array('timeout' => 5));

		// Set the public and private keys.
		$this->_publicKey = $public;
		$this->_privateKey = $private;
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
	 * Returns the global JRecaptcha object, only creating it if it doesn't already exist.
	 *
	 * <code>
	 *	<?php
	 *	jx('jx.webservices.recaptcha');
	 *	$recaptcha = JRecaptcha::getInstance({PUBLIC_KEY}, {PRIVATE_KEY});
	 *	?>
	 * </code>
	 *
	 * @param	string	reCAPTCHA public key.
	 * @param	string	reCAPTCHA private key.
	 * @param	array	A configuration array.
	 * @return	object	A JRecaptcha object.
	 * @since	1.1
	 */
	public static function getInstance($public, $private, $config = array())
	{
		static $instance;

		// Only create the instance if it doesn't already exist.
		if (empty($instance)) {
			$instance = new JRecaptcha($public, $private, $config);
		}

		return $instance;
	}

	/**
	 * Gets a URL where the user can sign up for reCAPTCHA. If your application
	 * has a configuration page where you enter a key, you should provide a link
	 * using this function.
	 *
	 * <code>
	 *	<?php
	 *	jx('jx.webservices.recaptcha');
	 *	$url = JRecaptcha::getSignupURL({DOMAIN}, {APP_NAME});
	 *	?>
	 * </code>
	 *
	 * @param	string	The domain where the page is hosted.
	 * @param	string	The name of your application.
	 * @return	string	The URL string.
	 * @since	1.1
	 */
	public static function getSignupURL($domain = null, $appname = null)
	{
		return 'http://recaptcha.net/api/getkey?'.http_build_query(array('domain'=>$domain, 'app'=>$appname));
	}

	/**
	 * Method to get the URL to the reCAPTCHA AJAX JavaScript file.
	 *
	 * <code>
	 *	<?php
	 *	jx('jx.webservices.recaptcha');
	 *	$url = JRecaptcha::getAjaxScriptUrl({USE_SSL});
	 *	?>
	 * </code>
	 *
	 * @param	boolean	True to get the SSL version.
	 * @return	string	URL to file.
	 * @since	1.1
	 */
	public static function getAjaxScriptUrl($ssl = false)
	{
		return (($ssl) ? self::SERVER_SSL : self::SERVER).'/js/recaptcha_ajax.js';
	}

	/**
	 * Set the reCAPTCHA public/private key pair.
	 *
	 * <code>
	 *	<?php
	 *	jx('jx.webservices.recaptcha');
	 *	$recaptcha = JRecaptcha::getInstance({PUBLIC_KEY}, {PRIVATE_KEY});
	 *
	 *	$url = $recaptcha->setKeyPair({NEW_PUBLIC_KEY}, {NEW_PRIVATE_KEY});
	 *	?>
	 * </code>
	 *
	 * @param	string	reCAPTCHA public key.
	 * @param	string	reCAPTCHA private key.
	 * @return	void
	 * @since	1.1
	 */
	public function setKeyPair($public, $private)
	{
		$this->_publicKey = $public;
		$this->_privateKey = $private;
	}

	/**
	 * Method to get the challenge HTML, both JavaScript and non-JavaScript version, to
	 * generate the reCAPTCHA widget in an HTML form.
	 *
	 * <code>
	 *	<?php
	 *	jx('jx.webservices.recaptcha');
	 *	$recaptcha = JRecaptcha::getInstance({PUBLIC_KEY}, {PRIVATE_KEY});
	 *
	 *	$html = $recaptcha->getCaptcha();
	 *	?>
	 * </code>
	 *
	 * @param	boolean	True to get the SSL version.
	 * @param	string	An optional error message to show in case of failure.
	 * @return	string	The HTML to be embedded in the form.
	 * @since	1.1
	 */
	public function getCaptcha($ssl = false, $errorMessage = null)
	{
		// Get the server URL based on the SSL state.
		$server = ($ssl) ? self::SERVER_SSL : self::SERVER;

		// Get the error request variable based on the error message.
		$error = ($errorMessage) ? '&amp;error='.$errorMessage : '';

		// Build the CAPTCHA string to return.
		$captcha = array();
		$captcha[] = '<script type="text/javascript" src="'.$server.'/challenge?k='.$this->_publicKey.$error.'"></script>';
		$captcha[] = '<noscript>';
		$captcha[] = ' <iframe src="'.$server.'/noscript?k='.$this->_publicKey.$error.'" height="300" width="500" frameborder="0"></iframe><br />';
		$captcha[] = ' <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>';
		$captcha[] = ' <input type="hidden" name="recaptcha_response_field" value="manual_challenge">';
		$captcha[] = '</noscript>';

		return implode("\n", $captcha);
	}

	/**
	 * Method to verify a reCAPTCHA challenge/response.
	 *
	 * <code>
	 *	<?php
	 *	jx('jx.webservices.recaptcha');
	 *	$recaptcha = JRecaptcha::getInstance({PUBLIC_KEY}, {PRIVATE_KEY});
	 *
	 *	$valid = $recaptcha->verifyCaptcha();
	 *	?>
	 * </code>
	 *
	 * @param	string	The reCAPTCHA challenge.
	 * @param	string	The reCAPTCHA response.
	 * @param	string	The IP address of the client to verify the CAPTCHA for.
	 * @return	boolean	True if verified.
	 * @since	1.1
	 */
	public function verifyCaptcha($challenge = null, $response = null, $remoteIP = null)
	{
		// Get the IP address of the client.
		$remoteIP = ($remoteIP) ? $remoteIP : $_SERVER['REMOTE_ADDR'];

		// If the challenge or response are missing get them from the request.
		if (!$challenge || !$response) {
			$challenge = JRequest::getString('recaptcha_challenge_field', null);
			$response  = JRequest::getString('recaptcha_response_field', null);
		}

		// If the challenge or response is empty consider it spam and return false.
		if (empty($challenge) || empty($response)) {
			return false;
		}

		// Build the verification payload.
		$payload = array(
			'privatekey' => $this->_privateKey,
			'remoteip' => $remoteIP,
			'challenge' => $challenge,
			'response' => $response
		);

		// Post the request to the server.
		$response = $this->_http->post(self::SERVER_VERIFY.'/verify', $payload);

		// If the server response is valid check the body.
		if ($response && ($response->code == 200))
		{
			// Get the response lines.
			$answers = explode("\n", $response->body);
			$message = isset($answers[1]) ? $answers[1] : null;

			return ((trim($answers[0]) == 'true'));
		}

		return false;
	}
}
