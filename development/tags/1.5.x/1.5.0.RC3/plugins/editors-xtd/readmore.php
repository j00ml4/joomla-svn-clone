<?php
/**
* @version		$Id$
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2007 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.event.plugin');

/**
 * Editor Readmore buton
 *
 * @author Johan Janssens <johan.janssens@joomla.org>
 * @package Editors-xtd
 * @since 1.5
 */
class plgButtonReadmore extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param 	object $subject The object to observe
	 * @param 	array  $config  An array that holds the plugin configuration
	 * @since 1.5
	 */
	function plgButtonReadmore(& $subject, $config) {
		parent::__construct($subject, $config);
	}

	/**
	 * readmore button
	 * @return array A two element array of ( imageName, textToInsert )
	 */
	function onDisplay($name)
	{
		global $mainframe;

		$doc 		=& JFactory::getDocument();
		$template 	= $mainframe->getTemplate();

		// button is not active in specific content components

		$getContent = $this->_subject->getContent($name);
		$present = JText::_('ALREADY EXISTS', true) ;
		$js = "
			function insertReadmore() {
				var content = $getContent
				if (content.match(/<hr id=\"system-readmore\" \/>/)) {
					alert('$present');
					return false;
				} else {
					jInsertEditorText('<hr id=\"system-readmore\" />');
				}
			}
			";

		$doc->addScriptDeclaration($js);

		$button = new JObject();
		$button->set('modal', false);
		$button->set('onclick', 'insertReadmore();');
		$button->set('text', JText::_('Readmore'));
		$button->set('name', 'readmore');
		$button->set('link', 'javascript:void();');

		return $button;
	}
}
?>