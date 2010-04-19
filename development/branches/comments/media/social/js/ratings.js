/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

var JRatings=new Class({decorate:function(forms)
{forms.each(function(form)
{var links=form.getElements('ul.rating-stars a');links.each(function(link)
{link.setProperty('form',form.getProperty('id'));link.setStyle('cursor','pointer');link.addEvent('click',function(e)
{e=new Event(e).stop();var form=$(e.target.getProperty('form'));var context=form.getProperty('id').replace('rate-','');var query='protocol=json&tmpl=component&format=raw';var action=form.getProperty('action');form.setProperty('action',action.contains('?')?action+'&'+query:action+'?'+query);form.score.value=e.target.getProperty('rel');form.send({onComplete:function(response)
{response=Json.evaluate(response);JX.replaceTokens(response.token);if(response.error==true){alert(response.message);}
else
{var counter=$('rating-count-'+context).getElement('span.count');var string=$('rating-count-'+context).getElement('span.string');counter.setText(response.pscore_count);string.setText(response.counter_text);stars=form.getElement('.current-rating');stars.setStyle('width',Math.floor(response.pscore*100)+'%');}},onFailure:function(response)
{response=Json.evaluate(response.responseText);JX.replaceTokens(response.token);alert(response.message);}});});});});}});window.addEvent('domready',function()
{J.Ratings=new JRatings();JX.Ratings.decorate($$('form.addrating'));});