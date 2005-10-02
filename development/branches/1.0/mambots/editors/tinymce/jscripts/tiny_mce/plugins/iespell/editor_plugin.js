/* Import plugin specific language pack */
 tinyMCE.importPluginLanguagePack('iespell','cs,el,en,fr_ca,it,ko,sv,zh_cn,fr,de,pl,pt_br,nl,da,he,no');function TinyMCE_iespell_getInfo(){return{longname:'IESpell',author:'Moxiecode Systems',authorurl:'http://tinymce.moxiecode.com',infourl:'http://tinymce.moxiecode.com/tinymce/docs/plugin_iespell.html',version:tinyMCE.majorVersion+"."+tinyMCE.minorVersion};};function TinyMCE_iespell_getControlHTML(control_name){if(control_name=="iespell"&&tinyMCE.isMSIE)return '<a href="javascript:tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceIESpell\');" target="_self" onmousedown="return false;"><img id="{$editor_id}_iespell" src="{$pluginurl}/images/iespell.gif" title="{$lang_iespell_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" /></a>';return "";}function TinyMCE_iespell_execCommand(editor_id,element,command,user_interface,value){if(command=="mceIESpell"){try{var ieSpell=new ActiveXObject("ieSpell.ieSpellExtension");ieSpell.CheckDocumentNode(tinyMCE.getInstanceById(editor_id).contentDocument.documentElement);}catch(e){if(e.number==-2146827859){if(confirm(tinyMCE.getLang("lang_iespell_download","",true)))window.open('http://www.iespell.com/download.php','ieSpellDownload','');}else alert("Error Loading ieSpell: Exception "+e.number);}return true;}return false;}