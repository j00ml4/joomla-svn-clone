/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */


var JXComments=new Class({initialize:function()
{this.respondTrigger=$E('#leave-response a');if($chk(this.respondTrigger))
{this.respondTrigger.addEvent('click',function(e)
{new Event(e).stop();JX.Comments.getform(Json.evaluate(this.rel.match(/{[^}]*}/)[0]));});}
else{if($chk($('respond-form'))){this.decorateform();}}},getform:function(data)
{if($chk(this.formSlide))
{this.formSlide.toggle();return;}
var request=new Ajax(data.base+'index.php?option=com_comments&task=comment.getForm&protocol=ajax&thread_id='+data.thread_id,{method:'get',update:$('respond-container'),onComplete:function(r)
{if($chk($('respond-form'))){this.decorateform();}}.bind(this)}).request();},decorateform:function()
{this.formContainer=$('respond-container');this.formSlide=new Fx.Slide(this.formContainer);this.form=$('respond-form');var query='protocol=json&tmpl=component&format=raw';var action=this.form.getProperty('action');this.form.setProperty('action',action.contains('?')?action+'&'+query:action+'?'+query);refresher=$E('a.captcha-image-refresh',this.form);if($chk(refresher))
{refresher.addEvent('click',function(e)
{new Event(e).stop();JX.Comments.refreshcaptcha();});}
if((window.Recaptcha!=undefined)){Recaptcha.create(JX.Options.Comments.reCaptchaPubKey,'recaptchaContainer',{theme:'clean'});}
if((window.JXBBCodeEditor!=undefined))
{var el=$('comment_body');if($chk(JXEditors.get(el.id)))
{$(el).setProperties({'disabled':'disabled','readonly':'readonly'});}
else{JXEditors.set(el.id,new JXBBCodeEditor(el));}}
if((window.postEditor!=undefined)){new postEditor.create('comment_body','captcha',postlanguage.DEFAULT);}
this.form.addEvent('submit',function(e)
{new Event(e).stop();if(document.formvalidator.isValid(this)==false)
{if(this.name&&this.name.hasClass('invalid')){alert(JX.JText._('INVALID_NAME_TXT','Invalid Name.'));}
else if(this.email&&this.email.hasClass('invalid')){alert(JX.JText._('INVALID_EMAIL_TXT','Invalid Email.'));}
else if(this.body.hasClass('invalid')){alert(JX.JText._('INVALID_BODY_TXT','Invalid Comment.'));}
return false;}
$('respond-container').addClass('ajax-loading');this.send({onComplete:function(responseText)
{$('respond-container').removeClass('ajax-loading');response=Json.evaluate(responseText);JX.replaceTokens(response.token);if(response.error==true){alert(response.message);}
else
{n=$('commentlist').getElements('li').length;comment=new Element('li').setProperty('id','comment-'+response.id);comment.addClass('comment');if(response.position=='top')
{if($chk($('commentlist').getFirst())){comment.addClass($('commentlist').getFirst().hasClass('odd')?'even':'odd');}
else{comment.addClass('odd');}
comment.setHTML(response.body);comment.injectTop($('commentlist'));}
else
{comment.addClass(((n%2)==0)?'odd':'even');comment.setHTML(response.body);comment.injectInside($('commentlist'));}
$('respond-form').reset();JX.Comments.refreshcaptcha();comment.setOpacity(0);new Fx.Style(comment,'opacity',{duration:1500}).start(0,1);numContainer=$('comments-num');if($chk(numContainer)){numContainer.setHTML(response.num_text);}}},onFailure:function(x)
{$('respond-container').removeClass('ajax-loading');response=Json.evaluate(x.responseText);JX.replaceTokens(response.token);alert(response.message);}});});},refreshcaptcha:function()
{if(window.Recaptcha!=undefined){Recaptcha.reload();}
else if(JX.Options.Comments!=undefined&&JX.Options.Comments.captcha){var img=$E('img.captcha-image',this.form);img.src=img.src+'#';}},});window.addEvent('domready',function()
{JX.Comments=new JXComments();});