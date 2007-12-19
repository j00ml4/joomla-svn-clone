/**
 * This are a bunch of handy extensions for the Selenium IDE
 * in conjuction with unit testing the Joomla! CMS and Framework.
 * Make sure to include this file in the HTML test case using
 * a RELATIVE URI:
 * <script src="../joomla-selenium.js"></script>
 */

/*
  Helpers to start a new script at a specific location.
  Add joomlaXxx as the first command of your Selenium test.
 */

/* Navigate to the Front-end */
PageBot.prototype.joomlaSite = function() {
	this.open('/');
	this.waitForPageToLoad(30000);
}

/* Navigate to the Back-end */
PageBot.prototype.joomlaAdmin = function() {
	this.open('/administrator/');
	this.waitForPageToLoad(30000);
}

/* Navigate to the Installer */
PageBot.prototype.joomlaInstall = function() {
	this.open('/installation/');
	this.waitForPageToLoad(30000);
}

/* Navigate to the UnitTest Framework */
PageBot.prototype.joomlaTests = function() {
	this.open('/unittest/');
	this.waitForPageToLoad(30000);
}
