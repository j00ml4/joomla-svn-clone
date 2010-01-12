
<?php
/**
 * @version		$Id: control_panel0001Test.php 13064 2009-10-05 17:58:54Z dextercowley $
 * @package		Joomla.FunctionalTest
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * checks that all menu choices are shown in back end
 */

require_once 'SeleniumJoomlaTestCase.php';

/**
 * @group ControlPanel
 */
class Article0001 extends SeleniumJoomlaTestCase
{
	function testUnpublishArticle()
	{
		echo "Starting testUnpublishArticle.\n";
		$this->doAdminLogin();
		
		echo "Go to front end and check that Joomla! Beginners is shown"."\n";
		$this->gotoSite();
		$this->assertTrue($this->isTextPresent("Joomla! Beginners"));
		
		echo "Go to back end and unpublish Joomla! Beginners is shown"."\n";
		$this->gotoAdmin();
		$this->click("link=Article Manager");
		$this->waitForPageToLoad("30000");
		$this->type("filter_search", "Joomla! Beginners");
		$this->click("//button[@type='submit']");
		$this->waitForPageToLoad("30000");
		$this->click("//img[@alt='Published']");
		$this->waitForPageToLoad("30000");

		echo "Go to front end and check that Joomla! Beginners is not shown"."\n";
		$this->gotoSite();
		$this->assertFalse($this->isTextPresent("Joomla! Beginners"));

		echo "Go to back end and publish Joomla! Beginners"."\n";
		$this->gotoAdmin();
		$this->click("link=Article Manager");
		$this->waitForPageToLoad("30000");

		$this->click("//img[@alt='Unpublished']");
		$this->waitForPageToLoad("30000");
		$this->doAdminLogout();
	}	

	function testPublishArticle()
	{
		echo "Starting testPublishArticle.\n";
		$this->doAdminLogin();

		echo "Go to back end and unpublish Joomla! Beginners"."\n";
		$this->gotoAdmin();
		$this->click("link=Article Manager");
		$this->waitForPageToLoad("30000");
		$this->type("filter_search", "Joomla! Beginners");
		$this->click("//button[@type='submit']");
		$this->waitForPageToLoad("30000");
		$this->click("//img[@alt='Published']");
		$this->waitForPageToLoad("30000");

		echo "Go to front end and check that Joomla! Beginners is not shown"."\n";
		$this->gotoSite();
		$this->assertFalse($this->isTextPresent("Joomla! Beginners"));

		echo "Go to back end and publish Joomla! Beginners"."\n";
		$this->gotoAdmin();
		$this->click("link=Article Manager");
		$this->waitForPageToLoad("30000");

		$this->click("//img[@alt='Unpublished']");
		$this->waitForPageToLoad("30000");

		echo "Go to front end and check that Joomla! Beginners is shown"."\n";
		$this->gotoSite();
		$this->assertTrue($this->isTextPresent("Joomla! Beginners"));

		$this->doAdminLogout();
	}	

}

