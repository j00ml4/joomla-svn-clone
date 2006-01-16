<?php
/**
* @version $Id$
* @package Joomla
* @subpackage Installation
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( 'includes/framework.php' );

/**
* Joomla! Application class
*
* Provide many supporting API functions
* 
* @package Joomla
* @final
*/
class JInstallation extends JApplication {

	/**
	* Class constructor
	*/
	function __construct( ) {

		$this->_client = 2;

		$this->_createConfiguration();
	}

	/**
	 * Create the configuration registry
	 *
	 * @access private
	 */
	function _createConfiguration() 
	{	
		jimport( 'joomla.registry.registry' );
		
		// Create the registry with a default namespace of config which is read only
		$this->_registry =& new JRegistry( 'config' );
	}
	
	/**
	* Get the template
	* 
	* @return string The template name
	*/
	function getTemplate()
	{
		return 'template';
	}

	/**
	 * Create the user session
	 *
	 * @access private
	 * @param string	The sessions name
	 * @param boolean 	Use cookies to store the session on the client
	 */
	function _createSession( $name, $useCookies = true)
	{
		JSession::useCookies(true);
		JSession::start(md5( $name ));
		
		JSession::get('registry', new JRegistry('application'));

		JSession::setIdle(900);

		if (JSession::isIdle()) {
			JSession::destroy();
		}

		JSession::updateIdle();
	}
}

/** 
 * @global $_VERSION 
 */
$_VERSION = new JVersion();

?>