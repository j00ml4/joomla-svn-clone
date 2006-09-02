<?php
/**
 * @version $Id$
 * @package Joomla.Framework
 * @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

/**
 * Joomla Framework Factory class
 *
 * @static
 * @package Joomla.Framework
 * @since	1.5
 */
class JFactory
{
	/**
	 * Get a configuration object
	 *
	 * Returns a reference to the global JRegistry object, only creating it
	 * if it doesn't already exist.
	 *
	 * @access public
	 * @param string	The path to the configuration file
	 * @param string	The type of the configuration file
	 * @return object JRegistry
	 */
	function &getConfig($file = null, $type = 'PHP')
	{
		static $instance;

		if(is_null($file)) {
			$file = dirname(__FILE__) .DS. 'config.php';
		}

		if (!is_object($instance)) {
			$instance = JFactory::_createConfig($file, $type);
		}

		return $instance;
	}

	/**
	 * Get a language object
	 *
	 * Returns a reference to the global JLanguage object, only creating it
	 * if it doesn't already exist.
	 *
	 * @access public
	 * @return object JLanguage
	 */
	function &getLanguage()
	{
		static $instance;

		if (!is_object($instance)) {
			$instance = JFactory::_createLanguage();
		}

		return $instance;
	}

	/**
	 * Get a document object
	 *
	 * Returns a reference to the global JDocument object, only creating it
	 * if it doesn't already exist.
	 *
	 * @access public
	 * @return object JLanguage
	 */
	function &getDocument($type = 'html')
	{
		static $instance;

		if (!is_object( $instance )) {
			$instance = JFactory::_createDocument($type);
		}

		return $instance;
	}

	/**
	 * Get an user object
	 *
	 * Returns a reference to the global JUser object, only creating it
	 * if it doesn't already exist.
	 *
	 * @access public
	 * @return object JUser
	 */
	function &getUser()
	{
		// If there is a userid in the session, load the application user
		// object with the logged in user.
		$instance =& JUser::getInstance(JSession::get('userid', 0));
		return $instance;
	}

	/**
	 * Get a cache object
	 *
	 * Returns a reference to the global JCache object, only creating it
	 * if it doesn't already exist.
	 *
	 * @access public
	 * @param string The cache group name
	 * @param string The cache class name
	 * @return object
	 */
	function &getCache($group='', $handler = 'function')
	{
		jimport('joomla.cache.cache');

		$conf =& JFactory::getConfig();

		// If we are in the installation application, we don't need to be
		// creating any directories or have caching on
		$options = array(
			'cacheDir' 		=> JPATH_BASE.DS.'cache'.DS,
			'caching' 		=> $conf->getValue('config.caching'),
			'defaultGroup' 	=> $group,
			'lifeTime' 		=> $conf->getValue('config.cachetime'),
			'fileNameProtection' => false
		);

		$cache =& JCache::getInstance( $handler, $options );

		return $cache;
	}

	/**
	 * Get an authorization object
	 *
	 * Returns a reference to the global JAuthorization object, only creating it
	 * if it doesn't already exist.
	 *
	 * @access public
	 * @return object
	 */
	function &getACL( )
	{
		static $instance;

		if (!is_object($instance)) {
			$instance = JFactory::_createACL();
		}

		return $instance;
	}

	/**
	 * Get an template object
	 *
	 * Returns a reference to the global JAuthorization object, only creating it
	 * if it doesn't already exist.
	 *
	 * @access public
	 * @return object
	 */
	function &getTemplate( )
	{
		static $instance;

		if (!is_object($instance)) {
			$instance = JFactory::_createTemplate();
		}

		return $instance;
	}

	/**
	 * Get th database object
	 *
	 * Returns a reference to the global JDatabase object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return object JDatabase based object
	 */
	function &getDBO()
	{
		static $instance;

		if (!is_object($instance))
		{
			//get the debug configuration setting
			$conf =& JFactory::getConfig();
			$debug = $conf->getValue('config.debug');

			$instance = JFactory::_createDBO();
			$instance->debug($debug);
		}

		return $instance;
	}

	/**
	 * Get mailer object
	 *
	 * Returns a reference to the global Mailer object, only creating it
	 * if it doesn't already exist
	 *
	 * @access public
	 * @return object
	 */
	function &getMailer( )
	{
		static $instance;

		if (!is_object($instance)) {
			$instance = JFactory::_createMailer();
		}

		return $instance;
	}

	/**
	 * Get a XML document
	 *
	 * @access public
	 * @return object
	 * $param string The type of xml parser needed 'DOM', 'RSS' or 'Simple'
	 * @param array:
	 * 		boolean ['lite'] When using 'DOM' if true or not defined then domit_lite is used
	 * 		string  ['rssUrl'] the rss url to parse when using "RSS"
	 * 		string	['cache_time'] with 'RSS' - feed cache time. If not defined defaults to 3600 sec
	 */

	 function &getXMLParser( $type = 'DOM', $options = array())
	 {
		$doc = null;

		switch($type)
		{
			case 'RSS' :
			{
				if( is_null( $options['rssUrl']) ) {
					return false;
				}
				define('MAGPIE_OUTPUT_ENCODING', 'UTF-8');
				define('MAGPIE_CACHE_ON', true);
				define('MAGPIE_CACHE_DIR',JPATH_BASE.DS.'cache');
				if( !is_null( $options['cache_time'])){
					define('MAGPIE_CACHE_AGE', $options['cache_time']);
				}

				jimport('magpierss.rss_fetch');
				$doc = fetch_rss( $options['rssUrl'] );

			} break;

			case 'Simple' :
			{
				jimport('joomla.utilities.simplexml');
				$doc = new JSimpleXML();
			} break;

			case 'DOM'  :
			default :
			{
				if( !isset($options['lite']) || $options['lite']) {
					jimport('domit.xml_domit_lite_include');
					$doc = new DOMIT_Lite_Document();
				} else {
					jimport('domit.xml_domit_include');
					$doc = new DOMIT_Document();
				}
			}

		}

		return $doc;
	}

	/**
	* Get an JEditor object
	*
	* @access public
	* @return object A JEditor object
	*/
	function &getEditor()
	{
		jimport( 'joomla.presentation.editor' );

		//get the editor configuration setting
		$conf =& JFactory::getConfig();
		$editor = $conf->getValue('config.editor');

		$instance =& JEditor::getInstance($editor);

		return $instance;
	}

	/**
	 * Return a reference to the JURI object
	 *
	 * @access public
	 * @return juri 	JURI object
	 * @since 1.5
	 */
	function &getURI($uri = 'SERVER')
	{
		jimport('joomla.environment.uri');

		$instance =& JURI::getInstance();
		return $instance;
	}

	/**
	 * Create a configuration object
	 *
	 * @access private
	 * @param string	The path to the configuration file
	 * @param string	The type of the configuration file
	 * @return object
	 * @since 1.5
	 */
	function &_createConfig($file, $type = 'PHP')
	{
		jimport('joomla.registry.registry');

		require_once( $file );

		// Create the registry with a default namespace of config which is read only
		$registry = new JRegistry( 'config');

		// Create the JConfig object
		$config = new JFrameworkConfig();

		// Load the configuration values into the registry
		$registry->loadObject($config);

		return $registry;
	}

	/**
	 * Create an ACL object
	 *
	 * @access private
	 * @return object
	 * @since 1.5
	 */
	function &_createACL()
	{
		//TODO :: take the authorization class out of the application package
		jimport( 'joomla.application.user.authorization' );

		$db =&  JFactory::getDBO();

		$options = array(
			'db'				=> &$db,
			'db_table_prefix'	=> $db->getPrefix() . 'core_acl_',
			'debug'				=> 0
		);
		$acl = new JAuthorization( $options );

		return $acl;
	}

	/**
	 * Create an database object
	 *
	 * @access private
	 * @return object
	 * @since 1.5
	 */
	function &_createDBO()
	{
		jimport('joomla.database.database');

		$conf =& JFactory::getConfig();

		$host 		= $conf->getValue('config.host');
		$user 		= $conf->getValue('config.user');
		$password 	= $conf->getValue('config.password');
		$db   		= $conf->getValue('config.db');
		$dbprefix 	= $conf->getValue('config.dbprefix');
		$dbtype 	= $conf->getValue('config.dbtype');
		$debug 		= $conf->getValue('config.debug');

		$db =& JDatabase::getInstance( $dbtype, $host, $user, $password, $db, $dbprefix );

		if ($db->getErrorNum() > 0) {
			JError::raiseError('joomla.library:'.$db->getErrorNum(), 'JDatabase::getInstance: Could not connect to database <br/>' . $db->getErrorMsg() );
		}
		$db->debug( $debug );
		return $db;
	}

	/**
	 * Create a mailer object
	 *
	 * @access private
	 * @return object
	 * @since 1.5
	 */
	function &_createMailer()
	{
		jimport('joomla.utilities.mail');

		$conf =& JFactory::getConfig();

		$sendmail 	= $conf->getValue('config.sendmail');
		$smtpauth 	= $conf->getValue('config.smtpauth');
		$smtpuser 	= $conf->getValue('config.smtpuser');
		$smtppass  	= $conf->getValue('config.smtppass');
		$smtphost 	= $conf->getValue('config.smtphost');
		$mailfrom 	= $conf->getValue('config.mailfrom');
		$fromname 	= $conf->getValue('config.fromname');
		$mailer 	= $conf->getValue('config.mailer');

		$mail = new JMail();

		$mail->PluginDir = JPATH_LIBRARIES.DS.'phpmailer'.DS;
		$mail->SetLanguage('en', JPATH_LIBRARIES.DS.'phpmailer'.DS.'language'.DS);
		$mail->CharSet = 'utf-8';

		// Set default sender
		$mail->setSender(array ($mailfrom, $fromname));

		// Default mailer is to use PHP's mail function
		switch ($mailer)
		{
			case 'smtp' :
				$mail->useSMTP($smtpauth, $smtphost, $smtpuser, $smtppass);
				break;
			case 'sendmail' :
				$mail->useSendmail();
				break;
			default :
				$mail->IsMail();
				break;
		}

		return $mail;
	}


	/**
	 * Create a mailer object
	 *
	 * @access private
	 * @param array An array of support template files to load
	 * @return object
	 * @since 1.5
	 */
	function &_createTemplate($files = array())
	{
		jimport('joomla.template.template');

		$conf =& JFactory::getConfig();

		$tmpl = new JTemplate;

		// patTemplate
		if ($conf->getValue('config.caching')) {
	   		 $tmpl->enableTemplateCache( 'File', JPATH_BASE.DS.'cache'.DS);
		}

		$tmpl->setNamespace( 'jtmpl' );

		// load the wrapper and common templates
		$tmpl->readTemplatesFromFile( 'page.html' );
		$tmpl->applyInputFilter('ShortModifiers');

		// load the stock templates
		if (is_array( $files ))
		{
			foreach ($files as $file) {
				$tmpl->readTemplatesFromInput( $file );
			}
		}

		$tmpl->addGlobalVar( 'option', 				$GLOBALS['option'] );
		$tmpl->addGlobalVar( 'self', 				$_SERVER['PHP_SELF'] );
		$tmpl->addGlobalVar( 'uri_query', 			$_SERVER['QUERY_STRING'] );
		$tmpl->addGlobalVar( 'itemid', 				$GLOBALS['Itemid'] );
		$tmpl->addGlobalVar( 'REQUEST_URL',			JRequest::getUrl() );

		return $tmpl;
	}

	/**
	 * Create a language object
	 *
	 * @access private
	 * @return object
	 * @since 1.5
	 */
	function &_createLanguage()
	{
		jimport('joomla.i18n.language');

		$conf =& JFactory::getConfig();

		$lang =& JLanguage::getInstance($conf->getValue('config.language'));
		$lang->setDebug($conf->getValue('config.debug'));

		return $lang;
	}

	/**
	 * Create a document object
	 *
	 * @access private
	 * @return object
	 * @since 1.5
	 */
	function &_createDocument($type)
	{
		jimport('joomla.document.document');

		$lang  =& JFactory::getLanguage();

		$attributes = array (
            'charset'  => 'utf-8',
           	'lineend'  => 'unix',
            'tab'  => '  ',
          	'language'  => $lang->getTag(),
			'direction' => $lang->isRTL() ? 'rtl' : 'ltr'
		);

		$doc =& JDocument::getInstance($type, $attributes);
		return $doc;
	}
}
?>