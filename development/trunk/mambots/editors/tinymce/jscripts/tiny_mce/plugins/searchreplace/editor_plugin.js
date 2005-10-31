/* Import theme	specific language pack */
 tinyMCE.importPluginLanguagePack('searchreplace','en,sv,zh_cn,fa,fr_ca,fr,de,pl,pt_br,cs,nl,da,he,no,hu,ru,ru_KOI8-R,ru_UTF-8,fi,cy,es,is');function TinyMCE_searchreplace_getInfo(){return{longname:'Search/Replace',author:'Moxiecode Systems',authorurl:'http://tinymce.moxiecode.com',infourl:'http://tinymce.moxiecode.com/tinymce/docs/plugin_searchreplace.html',version:tinyMCE.majorVersion+"."+tinyMCE.minorVersion};};function TinyMCE_searchreplace_getControlHTML(control_name){switch(control_name){case "search":var cmd='tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceSearch\',true);return false;';return '<a href="javascript:'+cmd+'" onclick="'+cmd+'" target="_self" onmousedown="return false;"><img id="{$editor_id}_search" src="{$pluginurl}/images/search.gif" title="{$lang_searchreplace_search_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" /></a>';case "replace":var cmd='tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceSearchReplace\',true);return false;';return '<a href="javascript:'+cmd+'" onclick="'+cmd+'" target="_self" onmousedown="return false;"><img id="{$editor_id}_replace" src="{$pluginurl}/images/replace.gif" title="{$lang_searchreplace_replace_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" /></a>';}return "";}function TinyMCE_searchreplace_execCommand(editor_id,element,command,user_interface,value){function defValue(key,default_value){value[key]=typeof(value[key])=="undefined"?default_value:value[key];}function replaceSel(search_str,str){if(!tinyMCE.isMSIE){var sel=instance.contentWindow.getSelection();var rng=sel.getRangeAt(0);}else{var rng=instance.contentWindow.document.selection.createRange();}if(!tinyMCE.isMSIE){var doc=instance.contentWindow.document;if(str.indexOf(search_str)==-1){rng.deleteContents();rng.insertNode(rng.createContextualFragment(str));rng.collapse(false);}else{doc.execCommand("insertimage",false,"#mce_temp_url#");var elm=tinyMCE.getElementByAttributeValue(doc.body,"img","src","#mce_temp_url#");elm.parentNode.replaceChild(doc.createTextNode(str),elm);}}else{if(rng.item)rng.item(0).outerHTML=str;else rng.pasteHTML(str);}}var instance=tinyMCE.getInstanceById(editor_id);if(!value)value=new Array();defValue("editor_id",editor_id);defValue("searchstring","");defValue("replacestring",null);defValue("replacemode","none");defValue("casesensitive",false);defValue("backwards",false);defValue("wrap",false);defValue("wholeword",false);defValue("inline","yes");switch(command){case "mceResetSearch":tinyMCE.lastSearchRng=null;return true;case "mceSearch":if(user_interface){var template=new Array();if(value['replacestring']!=null){template['file']='../../plugins/searchreplace/replace.htm';template['width']=320;template['height']=120+(tinyMCE.isNS7?20:0);template['width']+=tinyMCE.getLang('lang_searchreplace_replace_delta_width',0);template['height']+=tinyMCE.getLang('lang_searchreplace_replace_delta_height',0);}else{template['file']='../../plugins/searchreplace/search.htm';template['width']=310;template['height']=105+(tinyMCE.isNS7?25:0);template['width']+=tinyMCE.getLang('lang_searchreplace_search_delta_width',0);template['height']+=tinyMCE.getLang('lang_searchreplace_replace_delta_height',0);}tinyMCE.openWindow(template,value);}else{var win=tinyMCE.getInstanceById(editor_id).contentWindow;var doc=tinyMCE.getInstanceById(editor_id).contentWindow.document;var body=tinyMCE.getInstanceById(editor_id).contentWindow.document.body;if(body.innerHTML==""){alert(tinyMCE.getLang('lang_searchreplace_notfound'));return true;}if(value['replacemode']=="current"){replaceSel(value['string'],value['replacestring']);value['replacemode']="none";tinyMCE.execInstanceCommand(editor_id,'mceSearch',user_interface,value,false);return true;}if(tinyMCE.isMSIE){var rng=tinyMCE.lastSearchRng?tinyMCE.lastSearchRng:doc.selection.createRange();var flags=0;if(value['wholeword'])flags=flags|2;if(value['casesensitive'])flags=flags|4;if(!rng.findText){alert('This operation is currently not supported by this browser.');return true;}if(value['replacemode']=="all"){while(rng.findText(value['string'],value['backwards']?-1:1,flags)){rng.scrollIntoView();rng.select();rng.collapse(false);replaceSel(value['string'],value['replacestring']);}alert(tinyMCE.getLang('lang_searchreplace_allreplaced'));return true;}if(rng.findText(value['string'],value['backwards']?-1:1,flags)){rng.scrollIntoView();rng.select();rng.collapse(value['backwards']);tinyMCE.lastSearchRng=rng;}else alert(tinyMCE.getLang('lang_searchreplace_notfound'));}else{if(value['replacemode']=="all"){while(win.find(value['string'],value['casesensitive'],value['backwards'],value['wrap'],value['wholeword'],false,false))replaceSel(value['string'],value['replacestring']);alert(tinyMCE.getLang('lang_searchreplace_allreplaced'));return true;}if(!win.find(value['string'],value['casesensitive'],value['backwards'],value['wrap'],value['wholeword'],false,false))alert(tinyMCE.getLang('lang_searchreplace_notfound'));}}return true;case "mceSearchReplace":value['replacestring']="";tinyMCE.execInstanceCommand(editor_id,'mceSearch',user_interface,value,false);return true;}return false;}function TinyMCE_searchreplace_handleNodeChange(editor_id,node,undo_index,undo_levels,visual_aid,any_selection){return true;}