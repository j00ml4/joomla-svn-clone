var url = tinyMCE.getParam("flash_external_list_url");
if (url != null) {
	// Fix relative
	if (url.charAt(0) != '/')
		url = tinyMCE.documentBasePath + "/" + url;

	document.write('<sc'+'ript language="javascript" type="text/javascript" src="' + url + '"></sc'+'ript>');
}

function init() {
	tinyMCEPopup.resizeToInnerSize();

	var formObj = document.forms[0];
	var swffile   = tinyMCE.getWindowArg('swffile');
	var swfwidth  = '' + tinyMCE.getWindowArg('swfwidth');
	var swfheight = '' + tinyMCE.getWindowArg('swfheight');

	if (swfwidth.indexOf('%')!=-1) {
		formObj.width2.value = "%";
		formObj.width.value  = swfwidth.substring(0,swfwidth.length-1);
	} else {
		formObj.width2.value = "px";
		formObj.width.value  = swfwidth;
	}

	if (swfheight.indexOf('%')!=-1) {
		formObj.height2.value = "%";
		formObj.height.value  = swfheight.substring(0,swfheight.length-1);
	} else {
		formObj.height2.value = "px";
		formObj.height.value  = swfheight;
	}

	formObj.file.value = swffile;
	formObj.insert.value = tinyMCE.getLang('lang_' + tinyMCE.getWindowArg('action'), 'Insert', true);

	selectByValue(formObj, 'link_list', swffile);

	// Handle file browser
	if (isVisible('filebrowser'))
		document.getElementById('file').style.width = '230px';

	// Auto select flash in list
	if (typeof(tinyMCEFlashList) != "undefined" && tinyMCEFlashList.length > 0) {
		for (var i=0; i<formObj.link_list.length; i++) {
			if (formObj.link_list.options[i].value == tinyMCE.getWindowArg('swffile'))
				formObj.link_list.options[i].selected = true;
		}
	}
}

function renderFlashList() {
	if (typeof(tinyMCEFlashList) != "undefined" && tinyMCEFlashList.length > 0) {
		var html = "";

		html += '<tr><td><label for="link_list">{$lang_flash_list}</label></td>';
		html += '<td><select id="link_list" name="link_list" style="width: 250px" onfocus="tinyMCE.addSelectAccessibility(event, this, window);" onchange="this.form.file.value=this.options[this.selectedIndex].value;">';
		html += '<option value="">---</option>';

		for (var i=0; i<tinyMCEFlashList.length; i++)
			html += '<option value="' + tinyMCEFlashList[i][1] + '">' + tinyMCEFlashList[i][0] + '</option>';

		html += '</select></td></tr>';

		document.write(html);
	}
}

function insertFlash() {
	var formObj = document.forms[0];
	var html      = '';
	var file      = formObj.file.value;
	var width     = formObj.width.value;
	var height    = formObj.height.value;
	if (formObj.width2.value=='%') {
		width = width + '%';
	}
	if (formObj.height2.value=='%') {
		height = height + '%';
	}

	if (width == "")
		width = 100;

	if (height == "")
		height = 100;

	html += ''
		+ '<img src="' + (tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" '
		+ 'width="' + width + '" height="' + height + '" '
		+ 'border="0" alt="' + file + '" title="' + file + '" class="mceItemFlash" />';

	tinyMCEPopup.execCommand("mceInsertContent", true, html);
	tinyMCE.selectedInstance.repaint();

	tinyMCEPopup.close();
}
