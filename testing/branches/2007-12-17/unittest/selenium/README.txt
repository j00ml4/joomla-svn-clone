
This folder contains scripts in "HTML format" for the Selenium IDE.

The scripts can be used as a "remote control" or to run "serious" tests of:
 - Joomla! CMS JSite              located in /site/
 - Joomla! CMS JAdministrator     located in /admin/
 - Joomla! CMS JInstaller         located in /installer/
 - Joomla! CMS XML-RPC            located in /xmlrpc/

You need to install the Selenium IDE extension for Firefox 1.5+ located 
in /unittest/_files/ and available for download at:
	http://www.openqa.org/selenium-ide/

For other browsers see:
	http://www.openqa.org/selenium-core/

Recommended readings
====================
	http://www.openqa.org/selenium-core/documentation.html
	http://release.openqa.org/selenium-core/0.8.0/reference.html

AJAX testing with Selenium
	http://agiletesting.blogspot.com/2006/01/testing-commentary-and-thus-ajax-with.html

Q: How the $%^@$ do I locate an element?
	http://wiki.openqa.org/display/SEL/Help+With+XPath
A: use XPath in lack of an ID attribute
//div[@class="foobar"]/a
- select the A element being a child of a DIV element 
  with class attribute "foobar"


Joomla! l10n issues
====================
If you do a test recording be aware that Selenium IDE will use the
literal link and caption to generate "click" actions:
	<a href="/foo.php">Next</a>
	clickAndWait(link=Next)

	<button>Confirm</button>
	clickAndWait(link=Confirm)

Should Joomla! on some develper's host happen to use a language other 
than the one *YOU* have used at recording time, such actions may fail. 
You may however use this "feature" on purpose to test the output of 
JLanguage, language files, or JText::_ ;)

