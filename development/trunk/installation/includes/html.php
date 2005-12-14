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

/**
 * @package Joomla
 * @subpackage Installation
 */
class installationScreens {
	/**
	 * Static method to create the template object
	 * @return patTemplate
	 */
	function &createTemplate() {

		global $mainframe;

		$lang =& $mainframe->getLanguage();

		jimport('joomla.template.template');

		$tmpl = new JTemplate;
		$tmpl->setNamespace( 'jos' );

		// load the wrapper and common templates
		$tmpl->setRoot( JPATH_BASE . '/tmpl' );
		$tmpl->readTemplatesFromFile( 'page.html' );

		if ($lang->isRTL()) {
			$tmpl->addGlobalVar( 'installcss', 'install_dark_rtl.css' );
			$tmpl->addGlobalVar( 'installdir', 'rtl' );
		} else {
			$tmpl->addGlobalVar( 'installcss', 'install_dark.css' );
			$tmpl->addGlobalVar( 'installdir', 'ltr' );
		}

		return $tmpl;
	}

	/**
	 * Shows an error message and back link
	 * @param array Vars to add to the error form
	 * @param mixed A string or array of messages
	 * @param string The name of the step to go back to
	 * @param string An extra message to display in a text area
	 */
	function error( &$vars, $msg, $back, $xmsg='' ) {
		global $steps;

		$tmpl =& installationScreens::createTemplate();
		$tmpl->setAttribute( 'body', 'src', 'error.html' );

		$tmpl->addVars( 'stepbar', $steps, 		'step_' );
		$tmpl->addVar( 'messages', 'message', 	$msg );

		if ($xmsg) {
			$tmpl->addVar( 'xmessages', 'xmessage', $xmsg );
		}

		$tmpl->addVar( 'body', 'back', $back );
		$tmpl->addVars( 'body', $vars, 'var_' );

		$tmpl->displayParsedTemplate( 'form' );
	}

	/**
	 * The index page
	 * @param array An array of lists
	 */
	function chooseLanguage( &$lists ) {
		global $steps;

		$tmpl =& installationScreens::createTemplate();
		$tmpl->setAttribute( 'body', 'src', 'language.html' );

		$steps['lang'] = 'on';

		$tmpl->addVars( 'stepbar', $steps, 'step_' );
		$tmpl->addRows( 'lang-options', $lists['langs'] );

		$tmpl->displayParsedTemplate( 'form' );
	}

	/**
	 * The index page
	 * @param array An array of lists
	 */
	function preInstall( $vars, &$lists ) {
		global $steps, $_VERSION;

		$tmpl =& installationScreens::createTemplate();
		$tmpl->setAttribute( 'body', 'src', 'preinstall.html' );

		$steps['preinstall'] = 'on';

		$tmpl->addVars( 'stepbar', 	$steps, 	'step_' );
		$tmpl->addVar( 'body', 		'version', 	$_VERSION->getLongVersion() );
		$tmpl->addVars( 'body', 	$vars, 		'var_' );

		$tmpl->addRows( 'php-options', 	$lists['phpOptions'] );
		$tmpl->addRows( 'php-settings', $lists['phpSettings'] );
		$tmpl->addRows( 'folder-perms', $lists['folderPerms'] );

		$tmpl->displayParsedTemplate( 'form' );
	}

	/**
	 * The index page
	 * @param array An array of lists
	 */
	function license( &$vars ) {
		global $steps;

		$tmpl =& installationScreens::createTemplate();
		$tmpl->setAttribute( 'body', 'src', 'license.html' );

		$steps['license'] = 'on';

		$tmpl->addVars( 'stepbar', 	$steps, 'step_' );
		$tmpl->addVars( 'body', 	$vars, 	'var_' );

		$tmpl->displayParsedTemplate( 'form' );
	}

	/**
	 * The index page
	 * @param array An array of lists
	 */
	function dbConfig( &$vars, &$lists ) {
		global $steps;

		$tmpl =& installationScreens::createTemplate();
		$tmpl->setAttribute( 'body', 'src', 'dbconfig.html' );

		$steps['dbconfig'] = 'on';

		$tmpl->addVars( 'stepbar', $steps, 'step_' );
		$tmpl->addVars( 'body', 	$vars, 'var_' );

		$tmpl->addRows( 'dbtype-options', $lists['dbTypes'] );

		$tmpl->displayParsedTemplate( 'form' );
	}

	/**
	 * The index page
	 * @param array An array of lists
	 */
	function dbCollation( &$vars, &$collations ) {
		global $steps;

		$tmpl =& installationScreens::createTemplate();
		$tmpl->setAttribute( 'body', 'src', 'dbcollation.html' );

		$steps['dbcollation'] = 'on';

		$tmpl->addVars( 'stepbar', $steps, 'step_' );
		$tmpl->addVars( 'body', 	$vars, 'var_' );

		if ($vars['DButfSupport']){
			$tmpl->addVar( 'utf_text', 'utfsupport', 'true');
		} else {
			$tmpl->addVar( 'utf_text', 'utfsupport', 'false');
		}

		$tmpl->addRows( 'collation-options', $collations );

		$tmpl->displayParsedTemplate( 'form' );
	}

	/**
	 * The index page
	 * @param array An array of lists
	 */
	function ftpConfig( &$vars ) {
		global $steps;

		$tmpl =& installationScreens::createTemplate();
		$tmpl->setAttribute( 'body', 'src', 'ftpconfig.html' );

		$steps['ftpconfig'] = 'on';

		$tmpl->addVars( 'stepbar', $steps, 'step_' );
		$tmpl->addVars( 'body', 	$vars, 'var_' );

		$tmpl->displayParsedTemplate( 'form' );
	}

	/**
	 * The index page
	 * @param array An array of lists
	 */
	function mainConfig( &$vars ) {
		global $steps;

		$tmpl =& installationScreens::createTemplate();
		$tmpl->setAttribute( 'body', 'src', 'mainconfig.html' );

		$steps['mainconfig'] = 'on';

		$tmpl->addVars( 'stepbar', $steps, 'step_' );
		$tmpl->addVars( 'body', 	$vars, 'var_' );

		$tmpl->displayParsedTemplate( 'form' );
	}

	/**
	 * The finish page for the installer
	 * @param array An array of lists
	 * @param string The configuration file if it could not be saved
	 */
	function finish( &$vars, $buffer ) {
		global $steps;

		$tmpl =& installationScreens::createTemplate();
		$tmpl->setAttribute( 'body', 'src', 'finish.html' );

		$steps['finish'] = 'on';

		$tmpl->addVars( 'stepbar', $steps, 'step_' );
		$tmpl->addVars( 'body', 	$vars, 'var_' );

		if ($buffer) {
			$tmpl->addVar( 'configuration-error', 'buffer', $buffer );
		}

		$tmpl->displayParsedTemplate( 'form' );
	}
}
?>
