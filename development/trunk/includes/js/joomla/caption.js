/**
* @version $Id: modal.js 5263 2006-10-02 01:25:24Z webImagery $
* @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
* @license GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/**
 * JCaption javascript behavior
 *
 * Used for displaying image captions
 *
 * @author		Johan Janssens <johan.janssens@joomla.org>
 * @package		Joomla
 * @since		1.5
 * @version     1.0
 */
 
/* -------------------------------------------- */
/* -- JCaption prototype ------------------------ */
/* -------------------------------------------- */

//constructor
var JCaption = function() { this.constructor.apply(this, arguments);}
JCaption.prototype = {

	constructor: function(class) 
	{	
		this.class = class;
		
		var images = document.getElementsByClassName(class);
		for ( var i=0; i < images.length; i++) {
			this.createCaption(images[i]);
		}
	},
	
	createCaption: function(element)
	{
		var caption   = document.createTextNode(element.title);
		var container = document.createElement("div");
		var text      = document.createElement("p");
		var width     = element.getAttribute("width");
		var align     = element.getAttribute("align");
		
		if(!width) {
			width = element.width;
		} 
			
		text.appendChild(caption);
		element.parentNode.insertBefore(container, element);
		container.appendChild(element);
		if ( element.title != "" ) {
			container.appendChild(text);
		}
		container.className   = this.class;
		container.setAttribute("style","float:"+align);
		container.style.width = width + "px";
		
	}
}

document.popup = null
document.addLoadEvent(function() {
  var caption = new JCaption('caption')
  document.caption = caption
});
