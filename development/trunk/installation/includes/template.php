<?php
/**
* @version $Id$
* @package Joomla
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
 * Initialise the document
 *
 * @param object $doc The document instance to initialise
 */
function initDocument(&$doc, $file = 'index.html')
{
	global $mainframe;

	$lang    =& $mainframe->getLanguage();
	
	$template = $mainframe->getTemplate();
	
	$version = new JVersion();

	$doc->setMetaContentType();

	$doc->setTitle( 'Joomla! - Web Installer' );

	$doc->setMetaData( 'Generator', $version->PRODUCT . " - " . $version->COPYRIGHT);
	$doc->setMetaData( 'robots', 'noindex, nofollow' );

	$doc->setBase( $mainframe->getBaseURL());

	$doc->addScript( 'includes/js/installation.js');
	
	if ($lang->isRTL()) {
		$doc->addStyleSheet( 'template/css/template_rtl.css' );
		$doc->addGlobalVar( 'lang_dir', 'rtl' );
	} else {
		$doc->addStyleSheet( 'template/css/template.css' );
		$doc->addGlobalVar( 'lang_dir', 'ltr' );
	}

	$doc->addGlobalVar('lang_tag', $lang->getTag());
	$doc->addGlobalVar( 'template', $template);
	
	$doc->addVar( $file, 'lang_isrtl', $lang->isRTL());

	$doc->addFavicon( 'favicon.ico' );
}
?>