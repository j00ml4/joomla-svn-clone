/**
* @version		$Id$
* @package		Joomla
* @subpackage	Installation
* @copyright	Copyright (C) 2005 - 2009 Open Source Matters. All rights reserved.
* @license		GNU/GPL
*/

 /**
* @param object A form element
* @param string The name of the element to find
*/
function getElementByName( f, name ) {
	if (f.elements) {
		for (i=0, n=f.elements.length; i < n; i++) {
			if (f.elements[i].name == name) {
				return f.elements[i];
			}
		}
	}
	return null;
}

/**
 * Generic submit form
 */

function submitForm( frm, task ) {
	frm.task.value = task;
	frm.submit();
}

