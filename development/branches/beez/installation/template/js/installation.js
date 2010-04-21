/**
 * @version		$Id$
 * @package		Joomla.Installation
 * @subpackage	JavaScript
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// Only define the Install namespace if not defined.
if (typeof(Install) === 'undefined') {
	var Install = {};
};

/**
 * Method to install sample data via AJAX request.
 */
Install.sampleData = function(el) {
	// make the ajax call
	el = $(el);
	var req = new Request({
		method: 'get',
		url: 'index.php?'+document.id(el.form).toQueryString(),
		data: {'task':'setup.loadSampleData', 'protocol':'json'},
		onRequest: function() { el.set('disabled', 'disabled'); },
		onComplete: function(response) {
			var r = JSON.decode(response);
			if (r)
			{
				Joomla.replaceTokens(r.token)
				if (r.error == false) {
					el.set('value', r.data.text);
				}
			}
			el.set('disabled', '');
		}
	}).send();
};

/**
 * Method to detect the FTP root via AJAX request.
 */
Install.detectFtpRoot = function(el) {
	// make the ajax call
	el = $(el);
	var req = new Request({
		method: 'get',
		url: 'index.php?'+document.id(el.form).toQueryString(),
		data: {'task':'setup.detectFtpRoot', 'format':'json'},
		onRequest: function() { el.set('disabled', 'disabled'); },
		onComplete: function(response) {
			var r = JSON.decode(response);
			if (r)
			{
				Joomla.replaceTokens(r.token)
				if (r.error == false) {
					document.id('ftproot').set('value', r.data.root);
				}
			}
			el.set('disabled', '');
		}
	}).send();
};

/**
 * Method to detect the FTP root via AJAX request.
 */
Install.verifyFtpSettings = function(el) {
	// make the ajax call
	el = $(el);
	var req = new Request({
		method: 'get',
		url: 'index.php?'+document.id(el.form).toQueryString(),
		data: {'task':'setup.verifyFtpSettings', 'format':'json'},
		onRequest: function() { el.set('disabled', 'disabled'); },
		onComplete: function(response) {
			var r = JSON.decode(response);
			if (r)
			{
				Joomla.replaceTokens(r.token)
				if (r.error == false) {
					alert('Settings Correct');
				}
				else {
					alert(r.message);
				}
			}
			el.set('disabled', '');
		}
	}).send();
};
