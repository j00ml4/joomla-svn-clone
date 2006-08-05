<?php
/**
 * @version $Id$
 * @package Joomla
 * @subpackage MailTo
 * @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

require_once( JPATH_COM_MAILTO . '/view.php' );

/**
 * @package Joomla
 * @subpackage MailTo
 */
class JViewMailToDefault extends JViewMailTo {
	/** @var string The view name */
	var $_viewName = 'default';

	/**
	 * Get the data for the view
	 * @return array
	 */
	function &getData() 
	{
		$user =& JFactory::getUser();
		$data = array();

		$data['link'] = urldecode( JRequest::getVar( 'link' ) );
		if ($data['link'] == '') {
			JError::raiseError( 403, 'Link is missing' );
			$false = false;
			return $false;
		}

		if ($user->get('id') > 0) {
			$data['sender'] = $user->get('name');
			$data['from'] = $user->get('email');
		}

		$data['spoofKey'] = JUtility::spoofKey();

		return $data;
	}

	/**
	 * Display the view
	 */
	function display() {
		$controller = &$this->getController();

		$data = $this->getData();
		if ($data === false) {
			return false;
		}

		$tmpl = $this->createTemplate( 'default/tmpl/default.html' );

		// Menu Parameters
		$mParams= $controller->getVar( 'mParams' );

		$tmpl->addObject( 'body', $mParams->toObject(), 'param_' );
		$tmpl->addVars( 'body', $data );

		$tmpl->displayParsedTemplate( 'form' );
	}
}
?>