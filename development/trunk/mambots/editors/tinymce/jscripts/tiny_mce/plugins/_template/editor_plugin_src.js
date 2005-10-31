/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('template', 'en,he,no,ru,ru_KOI8-R,ru_UTF-8,fi,cy,es,is,pl'); // <- Add a comma separated list of all supported languages

/****
 * Steps for creating a plugin from this template:
 *
 * 1. Change all "template" to the name of your plugin.
 * 2. Remove all the callbacks in this file that you don't need.
 * 3. Remove the popup.htm file if you don't need any popups.
 * 4. Add your custom logic to the callbacks you needed.
 * 5. Write documentation in a readme.txt file on how to use the plugin.
 * 6. Upload it under the "Plugins" section at sourceforge.
 *
 ****/

/**
 * Information about the plugin.
 */
function TinyMCE_template_getInfo() {
	return {
		longname : 'Template plugin',
		author : 'Your name',
		authorurl : 'http://www.yoursite.com',
		infourl : 'http://www.yoursite.com/docs/template.html',
		version : "1.0"
	};
};

/**
 * Gets executed when a editor instance is initialized
 */
function TinyMCE_template_initInstance(inst) {
	// You can take out plugin specific parameters
	alert("Initialization parameter:" + tinyMCE.getParam("template_someparam", false));
}

/**
 * Gets executed when a editor needs to generate a button.
 */
function TinyMCE_template_getControlHTML(control_name) {
	switch (control_name) {
		case "template":
			var cmd = 'tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceTemplate\', true);return false;';
			return '<a href="javascript:' + cmd + '" onclick="' + cmd + '" target="_self" onmousedown="return false;"><img id="{$editor_id}_template" src="{$pluginurl}/images/template.gif" title="{$lang_template_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" /></a>';
	}

	return "";
}

/**
 * Gets executed when a command is called.
 */
function TinyMCE_template_execCommand(editor_id, element, command, user_interface, value) {
	// Handle commands
	switch (command) {
		// Remember to have the "mce" prefix for commands so they don't intersect with built in ones in the browser.
		case "mceTemplate":
			// Show UI/Popup
			if (user_interface) {
				// Open a popup window and send in some custom data in a window argument
				var template = new Array();

				template['file'] = '../../plugins/template/popup.htm'; // Relative to theme
				template['width'] = 300;
				template['height'] = 200;

				tinyMCE.openWindow(template, {editor_id : editor_id, some_custom_arg : "somecustomdata"});

				// Let TinyMCE know that something was modified
				tinyMCE.triggerNodeChange(false);
			} else {
				// Do a command this gets called from the template popup
				alert("execCommand: mceTemplate gets called from popup.");
			}

			return true;
	}

	// Pass to next handler in chain
	return false;
}

/**
 * Gets executed when the selection/cursor position was changed.
 */
function TinyMCE_template_handleNodeChange(editor_id, node, undo_index, undo_levels, visual_aid, any_selection) {
	// Deselect template button
	tinyMCE.switchClassSticky(editor_id + '_template', 'mceButtonNormal');

	// Select template button if parent node is a strong or b
	if (node.parentNode.nodeName == "STRONG" || node.parentNode.nodeName == "B")
		tinyMCE.switchClassSticky(editor_id + '_template', 'mceButtonSelected');

	return true;
}

/**
 * Gets executed when contents is inserted / retrived.
 */
function TinyMCE_template_cleanup(type, content) {
	switch (type) {
		case "get_from_editor":
			alert("[FROM] Value HTML string: " + content);

			// Do custom cleanup code here

			break;

		case "insert_to_editor":
			alert("[TO] Value HTML string: " + content);

			// Do custom cleanup code here

			break;

		case "get_from_editor_dom":
			alert("[FROM] Value DOM Element " + content.innerHTML);

			// Do custom cleanup code here

			break;

		case "insert_to_editor_dom":
			alert("[TO] Value DOM Element: " + content.innerHTML);

			// Do custom cleanup code here

			break;
	}

	return content;
}
