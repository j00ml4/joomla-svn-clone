/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 */

// Only define the Joomla namespace if not defined.
if (typeof(Joomla) === 'undefined') {
	var Joomla = {};
};

Joomla.editors = {};
Joomla.editors.utils = {};
Joomla.editors.instances = {};

Joomla.editors.utils.insertAtCursor = function(myField, myValue) {
	if (document.selection) {
		// IE support
		myField.focus();
		sel = document.selection.createRange();
		sel.text = myValue;
	}
	else if (myField.selectionStart || myField.selectionStart == '0') {
		// MOZILLA/NETSCAPE support
		var startPos = myField.selectionStart;
		var endPos = myField.selectionEnd;
		myField.value = myField.value.substring(0, startPos)
		+ myValue
		+ myField.value.substring(endPos, myField.value.length);
	}
	else {
	myField.value += myValue;
	}
};

/**
 * Generic submit form
 */
Joomla.submitform = function(task, form) {
	if (typeof(form) === 'undefined') {
		form = document.adminForm;
	}
	form.task.value = task;

	// Submit the form.
	if (typeof form.onsubmit == 'function') {
		form.onsubmit();
	}
	form.submit();
};

/**
 * Custom behavior for JavaScript I18N in Joomla! 1.6
 * 
 * Allows you to call JText._() to get a translated JavaScript string pushed in with JText::script() in Joomla.
 */
Joomla.JText = {
	strings: {},
	'_': function(key, def) {
		return typeof this.strings[key.toUpperCase()] !== 'undefined' ? this.strings[key.toUpperCase()] : def;
	},
	load: function(object) {
		for (var key in object) {
			this.strings[key.toUpperCase()] = object[key];
		}
		return this;
	}
};

/**
 * Method to replace all request tokens on the page with a new one.
 */
Joomla.replaceTokens = function(n) {
	var els = document.getElementsByTagName('input');
	for (var i=0; i < els.length; i++)
	{
		if ((els[i].type == 'hidden') && (els[i].name.length == 32) && els[i].value == '1') {
			els[i].name = n;
		}
	}
};


/**
 * USED IN: administrator/components/com_modules/views/module/tmpl/default.php
 * 
 * Writes a dynamically generated list
 * 
 * @param string
 *            The parameters to insert into the <select> tag
 * @param array
 *            A javascript array of list options in the form [key,value,text]
 * @param string
 *            The key to display for the initial state of the list
 * @param string
 *            The original key that was selected
 * @param string
 *            The original item value that was selected
 */
function writeDynaList(selectParams, source, key, orig_key, orig_val) {
	var html = '\n	<select ' + selectParams + '>';
	var i = 0;
	for (x in source) {
		if (source[x][0] == key) {
			var selected = '';
			if ((orig_key == key && orig_val == source[x][1])
					|| (i == 0 && orig_key != key)) {
				selected = 'selected="selected"';
			}
			html += '\n		<option value="' + source[x][1] + '" ' + selected
					+ '>' + source[x][2] + '</option>';
		}
		i++;
	}
	html += '\n	</select>';

	document.writeln(html);
}

/**
 * USED IN: administrator/components/com_content/views/article/view.html.php
 * 
 * Changes a dynamically generated list
 * 
 * @param string
 *            The name of the list to change
 * @param array
 *            A javascript array of list options in the form [key,value,text]
 * @param string
 *            The key to display
 * @param string
 *            The original key that was selected
 * @param string
 *            The original item value that was selected
 */
function changeDynaList(listname, source, key, orig_key, orig_val) {
	var list = eval('document.adminForm.' + listname);

	// empty the list
	for (i in list.options.length) {
		list.options[i] = null;
	}
	i = 0;
	for (x in source) {
		if (source[x][0] == key) {
			opt = new Option();
			opt.value = source[x][1];
			opt.text = source[x][2];

			if ((orig_key == key && orig_val == opt.value) || i == 0) {
				opt.selected = true;
			}
			list.options[i++] = opt;
		}
	}
	list.length = i;
}

/**
 * USED IN: administrator/components/com_menus/views/menus/tmpl/default.php
 * 
 * @param radioObj
 * @return
 */
// return the value of the radio button that is checked
// return an empty string if none are checked, or
// there are no radio buttons
function radioGetCheckedValue(radioObj) {
	if (!radioObj) {
		return '';
	}
	var n = radioObj.length;
	if (n == undefined) {
		if (radioObj.checked) {
			return radioObj.value;
		} else {
			return '';
		}
	}
	for ( var i = 0; i < n; i++) {
		if (radioObj[i].checked) {
			return radioObj[i].value;
		}
	}
	return '';
}

/**
 * USED IN: administrator/components/com_banners/views/banner/tmpl/default/php
 * administrator/components/com_categories/views/category/tmpl/default.php
 * administrator/components/com_categories/views/copyselect/tmpl/default.php
 * administrator/components/com_content/views/copyselect/tmpl/default.php
 * administrator/components/com_massmail/views/massmail/tmpl/default.php
 * administrator/components/com_menus/views/list/tmpl/copy.php
 * administrator/components/com_menus/views/list/tmpl/move.php
 * administrator/components/com_messages/views/message/tmpl/default_form.php
 * administrator/components/com_newsfeeds/views/newsfeed/tmpl/default.php
 * components/com_content/views/article/tmpl/form.php
 * templates/beez/html/com_content/article/form.php
 * 
 * @param frmName
 * @param srcListName
 * @return
 */
function getSelectedValue(frmName, srcListName) {
	var form = eval('document.' + frmName);
	var srcList = eval('form.' + srcListName);

	i = srcList.selectedIndex;
	if (i != null && i > -1) {
		return srcList.options[i].value;
	} else {
		return null;
	}
}

/**
 * USED IN: all list forms.
 * 
 * Toggles the check state of a group of boxes
 * 
 * Checkboxes must have an id attribute in the form cb0, cb1...
 * 
 * @param The
 *            number of box to 'check'
 * @param An
 *            alternative field name
 */
function checkAll(n, fldName) {
	if (!fldName) {
		fldName = 'cb';
	}
	var f = document.adminForm;
	var c = f.toggle.checked;
	var n2 = 0;
	for (i = 0; i < n; i++) {
		cb = eval('f.' + fldName + '' + i);
		if (cb) {
			cb.checked = c;
			n2++;
		}
	}
	if (c) {
		document.adminForm.boxchecked.value = n2;
	} else {
		document.adminForm.boxchecked.value = 0;
	}
}

/**
 * USED IN: all over :)
 * 
 * @param id
 * @param task
 * @return
 */
function listItemTask(id, task) {
	var f = document.adminForm;
	cb = eval('f.' + id);
	if (cb) {
		for (i = 0; true; i++) {
			cbx = eval('f.cb' + i);
			if (!cbx)
				break;
			cbx.checked = false;
		} // for
		cb.checked = true;
		f.boxchecked.value = 1;
		submitbutton(task);
	}
	return false;
}

/**
 * USED IN: administrator/components/com_cache/views/cache/tmpl/default.php
 * administrator/components/com_installer/views/components/tmpl/default_item.php
 * administrator/components/com_installer/views/discover/tmpl/default_item.php
 * administrator/components/com_installer/views/languages/tmpl/default_item.php
 * administrator/components/com_installer/views/libraries/tmpl/default_item.php
 * administrator/components/com_installer/views/modules/tmpl/default_item.php
 * administrator/components/com_installer/views/packages/tmpl/default_item.php
 * administrator/components/com_installer/views/plugins/tmpl/default_item.php
 * administrator/components/com_installer/views/templates/tmpl/default_item.php
 * administrator/components/com_installer/views/update/tmpl/default_item.php
 * administrator/components/com_languages/views/languages/tmpl/default.php
 * administrator/components/com_localise/views/translations/tmpl/files.php
 * administrator/components/com_menus/views/list/tmpl/default.php
 * administrator/components/com_menus/views/menus/tmpl/default.php
 * administrator/components/com_templates/views/csschose/tmpl/default.php
 * administrator/components/com_templates/views/templates/tmpl/default.php
 * administrator/components/com_trash/admin.trash.html.php
 * administrator/components/com_update/views/update/tmpl/default_item.php
 * libraries/joomla/html/html/grid.php
 * 
 * @param isitchecked
 * @return
 */
function isChecked(isitchecked) {
	if (isitchecked == true) {
		document.adminForm.boxchecked.value++;
	} else {
		document.adminForm.boxchecked.value--;
	}
}

/**
 * Default function. Usually would be overriden by the component
 */
function submitbutton(pressbutton) {
	submitform(pressbutton);
}

/**
 * Submit the admin form
 */
function submitform(pressbutton) {
	if (pressbutton) {
		document.adminForm.task.value = pressbutton;
	}
	if (typeof document.adminForm.onsubmit == "function") {
		document.adminForm.onsubmit();
	}
	document.adminForm.submit();
}

/**
 * USED IN: libraries/joomla/html/toolbar/button/help.php
 * 
 * Pops up a new window in the middle of the screen
 */
function popupWindow(mypage, myname, w, h, scroll) {
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height=' + h + ',width=' + w + ',top=' + wint + ',left=' + winl
			+ ',scrollbars=' + scroll + ',resizable'
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) {
		win.window.focus();
	}
}

// needed for Table Column ordering
/**
 * USED IN: libraries/joomla/html/html/grid.php
 */
function tableOrdering(order, dir, task) {
	var form = document.adminForm;

	form.filter_order.value = order;
	form.filter_order_Dir.value = dir;
	submitform(task);
}

/**
 * USED IN: libraries/joomla/html/html/grid.php
 */
function saveorder(n, task) {
	checkAll_button(n, task);
}
function checkAll_button(n, task) {

	if (!task) {
		task = 'saveorder';
	}

	for ( var j = 0; j <= n; j++) {
		box = eval("document.adminForm.cb" + j);
		if (box) {
			if (box.checked == false) {
				box.checked = true;
			}
		} else {
			alert("You cannot change the order of items, as an item in the list is `Checked Out`");
			return;
		}
	}
	submitform(task);
}

/**
 * USED IN: administrator/components/com_banners/views/client/tmpl/default.php
 * 
 * Verifies if the string is in a valid email format
 * 
 * @param string
 * @return boolean
 */
function isEmail(text) {
	var pattern = "^[\\w-_\.]*[\\w-_\.]\@[\\w]\.+[\\w]+[\\w]$";
	var regex = new RegExp(pattern);
	return regex.test(text);
}
