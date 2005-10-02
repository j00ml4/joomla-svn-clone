/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('advimage', 'en,de,sv,zh_cn,cs,fa,fr_ca,fr,pl,pt_br,nl,he,no');

function TinyMCE_advimage_getInfo() {
	return {
		longname : 'Advanced image',
		author : 'Moxiecode Systems',
		authorurl : 'http://tinymce.moxiecode.com',
		infourl : 'http://tinymce.moxiecode.com/tinymce/docs/plugin_advimage.html',
		version : tinyMCE.majorVersion + "." + tinyMCE.minorVersion
	};
};

function TinyMCE_advimage_getControlHTML(control_name) {
	switch (control_name) {
		case "image":
			return '<a href="javascript:tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceAdvImage\');" target="_self" onmousedown="return false;"><img id="{$editor_id}_advimage" src="{$themeurl}/images/image.gif" title="{$lang_image_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreClass(this);" /></a>';
	}

	return "";
}

function TinyMCE_advimage_execCommand(editor_id, element, command, user_interface, value) {
	switch (command) {
		case "mceAdvImage":
			var template = new Array();

			template['file']   = '../../plugins/advimage/image.htm';
			template['width']  = 480;
			template['height'] = 380;

			// Language specific width and height addons
			template['width']  += tinyMCE.getLang('lang_advimage_delta_width', 0);
			template['height'] += tinyMCE.getLang('lang_advimage_delta_height', 0);

			tinyMCE.openWindow(template, {editor_id : editor_id, inline : "yes"});

			return true;
	}

	return false;
}

function TinyMCE_advimage_cleanup(type, content) {
	switch (type) {
		case "insert_to_editor_dom":
			var imgs = content.getElementsByTagName("img");
			for (var i=0; i<imgs.length; i++) {
				var onmouseover = tinyMCE.cleanupEventStr(tinyMCE.getAttrib(imgs[i], 'onmouseover'));
				var onmouseout = tinyMCE.cleanupEventStr(tinyMCE.getAttrib(imgs[i], 'onmouseout'));

				if ((src = tinyMCE.getImageSrc(onmouseover)) != "") {
					src = tinyMCE.convertRelativeToAbsoluteURL(tinyMCE.settings['base_href'], src);
					imgs[i].setAttribute('onmouseover', "this.src='" + src + "';");
				}

				if ((src = tinyMCE.getImageSrc(onmouseout)) != "") {
					src = tinyMCE.convertRelativeToAbsoluteURL(tinyMCE.settings['base_href'], src);
					imgs[i].setAttribute('onmouseout', "this.src='" + src + "';");
				}
			}
			break;

		case "get_from_editor_dom":
			var imgs = content.getElementsByTagName("img");
			for (var i=0; i<imgs.length; i++) {
				var onmouseover = tinyMCE.cleanupEventStr(tinyMCE.getAttrib(imgs[i], 'onmouseover'));
				var onmouseout = tinyMCE.cleanupEventStr(tinyMCE.getAttrib(imgs[i], 'onmouseout'));

				if ((src = tinyMCE.getImageSrc(onmouseover)) != "") {
					src = eval(tinyMCE.settings['urlconverter_callback'] + "(src, null, true);");
					imgs[i].setAttribute('onmouseover', "this.src='" + src + "';");
				}

				if ((src = tinyMCE.getImageSrc(onmouseout)) != "") {
					src = eval(tinyMCE.settings['urlconverter_callback'] + "(src, null, true);");
					imgs[i].setAttribute('onmouseout', "this.src='" + src + "';");
				}
			}
			break;
	}

	return content;
}

function TinyMCE_advimage_handleNodeChange(editor_id, node, undo_index, undo_levels, visual_aid, any_selection) {
	tinyMCE.switchClassSticky(editor_id + '_advimage', 'mceButtonNormal');

	if (node == null)
		return;

	do {
		if (node.nodeName == "IMG" && tinyMCE.getAttrib(node, 'class').indexOf('mceItem') == -1)
			tinyMCE.switchClassSticky(editor_id + '_advimage', 'mceButtonSelected');
	} while ((node = node.parentNode));

	return true;
}
