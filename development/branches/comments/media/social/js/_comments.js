/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Comments behavior class.
 * @since		1.1
 */
var JXComments = new Class({

	/**
	 * Object Constructor.
	 *
	 * @return	void
	 * @since	1.1
	 */
	initialize : function()
	{
		// Get the respond trigger.
		this.respondTrigger = $E('#leave-response a');
		if ($chk(this.respondTrigger))
		{
			this.respondTrigger.addEvent('click', function(e)
			{
				// Stop the event and get the form using the JSON data in the rel attribute.
				new Event(e).stop();
				JX.Comments.getform(Json.evaluate(this.rel.match(/{[^}]*}/)[0]));
			});
		}
		else {
			if ($chk($('respond-form'))) {
				this.decorateform();
			}
		}
	},

	/**
	 * Load the form if not already loaded.
	 *
	 * @param	object	The data object for loading the form.
	 * @return	void
	 * @since	1.1
	 */
	getform : function(data)
	{
		// Check if the form has been loaded.
		if ($chk(this.formSlide))
		{
			this.formSlide.toggle();
			return;
		}

		// Load the form via AJAX.
		var request = new Ajax(data.base+'index.php?option=com_comments&task=comment.getForm&protocol=ajax&thread_id='+ data.thread_id, {
			method : 'get',
			update : $('respond-container'),
			onComplete : function(r)
			{
				if ($chk($('respond-form'))) {
					this.decorateform();
				}
			}.bind(this)
		}).request();
	},

	/**
	 * Object Constructor.
	 *
	 * @return	void
	 * @since	1.1
	 */
	decorateform : function()
	{
		// Initialize variables.
		this.formContainer = $('respond-container');
		this.formSlide = new Fx.Slide(this.formContainer);
		this.form = $('respond-form');
		var query = 'protocol=json&tmpl=component&format=raw';
		var action = this.form.getProperty('action');

		// Convert the action to a JSON request.
		this.form.setProperty('action', action.contains('?') ? action+'&'+query : action+'?'+query);

		// Add the CAPTCHA refresh behavior.
		refresher = $E('a.captcha-image-refresh', this.form);
		if ($chk(refresher))
		{
			refresher.addEvent('click', function(e)
			{
				new Event(e).stop();
				JX.Comments.refreshcaptcha();
			});
		}

		// Create the reCAPTCHA challenge if necessary.
		if ((window.Recaptcha != undefined)) {
			Recaptcha.create(JX.Options.Comments.reCaptchaPubKey, 'recaptchaContainer', {theme : 'clean'});
		}

		// Create a BBCode editor object instance if necessary.
		if ((window.JXBBCodeEditor != undefined))
		{
			var el = $('comment_body');
			if ($chk(JXEditors.get(el.id)))
			{
				$(el).setProperties({
					'disabled' : 'disabled',
					'readonly' : 'readonly'
				});
			}
			else {
				JXEditors.set(el.id, new JXBBCodeEditor(el));
			}
		}

		// Create a Post editor object instance if necessary.
		if ((window.postEditor != undefined)) {
			new postEditor.create('comment_body', 'captcha', postlanguage.DEFAULT);
		}

		// Add the form submit handler.
		this.form.addEvent('submit', function(e)
		{
			new Event(e).stop();

			// Check form field validation.
			if (document.formvalidator.isValid(this) == false)
			{
				if (this.name && this.name.hasClass('invalid')) {
					alert(JX.JText._('INVALID_NAME_TXT', 'Invalid Name.'));
				}
				else if (this.email && this.email.hasClass('invalid')) {
					alert(JX.JText._('INVALID_EMAIL_TXT', 'Invalid Email.'));
				}
				else if (this.body.hasClass('invalid')) {
					alert(JX.JText._('INVALID_BODY_TXT', 'Invalid Comment.'));
				}

				return false;
			}

			// Add the loading animation class while sending AJAX request.
			$('respond-container').addClass('ajax-loading');

			// Send the form post via AJAX request.
			this.send({
				onComplete : function(responseText)
				{
					// Remove the loading animation.
					$('respond-container').removeClass('ajax-loading');

					// Decode the response object.
					response = Json.evaluate(responseText);

					// Replace the request validation tokens.
					JX.replaceTokens(response.token);

					// If there was an error alert the user of the message.
					if (response.error == true) {
						alert(response.message);
					}

					// If no error, process the response.
					else
					{
						// Get the current number of comments
						n = $('commentlist').getElements('li').length;

						comment = new Element('li').setProperty('id', 'comment-'+response.id);
						comment.addClass('comment');

						// Check if the comment goes at the top or the bottom.
						if (response.position == 'top')
						{
							// Set the comment row class.
							if ($chk($('commentlist').getFirst())) {
								comment.addClass($('commentlist').getFirst().hasClass('odd') ? 'even' : 'odd');
							}
							else {
								comment.addClass('odd');
							}

							// Build up your comment list element's body
							comment.setHTML(response.body);

							// Add the comment to the list.
							comment.injectTop($('commentlist'));
						}
						else
						{
							// Set the comment row class.
							comment.addClass(((n % 2) == 0) ? 'odd' : 'even');

							// Build up your comment list element's body
							comment.setHTML(response.body);

							// Add the comment to the list.
							comment.injectInside($('commentlist'));
						}

						// Clear out the form
						$('respond-form').reset();

						// Also, we need to refresh the captcha stuff
						JX.Comments.refreshcaptcha();

						// Show the comment.
						comment.setOpacity(0);
						new Fx.Style(comment, 'opacity', {duration : 1500}).start(0, 1);

						// Increase the number of comments
						numContainer = $('comments-num');
						if ($chk(numContainer)) {
							numContainer.setHTML(response.num_text);
						}
					}
				},

				onFailure : function(x)
				{
					// Remove the loading animation.
					$('respond-container').removeClass('ajax-loading');

					// Decode the response object.
					response = Json.evaluate(x.responseText);

					// Replace the request validation tokens.
					JX.replaceTokens(response.token);

					// Alert the user of the response message.
					alert(response.message);
				}
			});
		});
	},

	/**
	 * Method to refresh the CAPTCHA validator for the form.
	 *
	 * @return	void
	 * @since	1.2
	 */
	refreshcaptcha : function()
	{
		// If reCAPTCHA is loaded reload it.
		if (window.Recaptcha != undefined) {
			Recaptcha.reload();
		}
		// If CAPTCHA is enabled, refresh the image.
		else if (JX.Options.Comments != undefined && JX.Options.Comments.captcha) {
			var img = $E('img.captcha-image', this.form);
			img.src = img.src+'#';
		}
	},
});

window.addEvent('domready', function()
{
	JX.Comments = new JXComments();
});