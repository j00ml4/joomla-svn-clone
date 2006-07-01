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

jimport('joomla.application.view');

/**
 * HTML View class for JamboShop
 * @package JamboShop
 */
class JViewMailTo extends JView {
	/**
	 * @param string The main template file to include for output
	 * @param array An array of other standard files to include
	 * @return patTemplate A template object
	 */
	function &createTemplate( $bodyHtml='', $files=null ) {

		$tmpl = new JTemplate();

		// patTemplate
		if ($mainframe->getCfg( 'caching' )) {
	   		 $tmpl->enableTemplateCache( 'File', $mainframe->getCfg('cachepath'));
		}

		$tmpl->setNamespace( 'jtmpl' );

		// load the wrapper and common templates
		$tmpl->readTemplatesFromFile( 'page.html' );
		$tmpl->applyInputFilter('ShortModifiers');

		// load the stock templates
		if (is_array( $files )) {
			foreach ($files as $file) {
				$tmpl->readTemplatesFromInput( $file );
			}
		}

		// TODO: Do the protocol better
		$tmpl->addVar( 'form', 'formAction', basename($_SERVER['PHP_SELF']) );
		$tmpl->addVar( 'form', 'formName', 'adminForm' );

		$tmpl->setRoot( JPATH_COM_MAILTO . '/views' );

		if ($bodyHtml) {
			$tmpl->setAttribute( 'body', 'src', $bodyHtml );
		}

		return $tmpl;
	}
}
?>