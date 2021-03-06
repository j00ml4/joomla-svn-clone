
<?php
/**
 * @version		$Id$
 * @package		Joomla.FunctionalTest
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
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
		$this->setUp();
		echo "Starting testUnpublishArticle.\n";
		$this->gotoAdmin();
		$this->doAdminLogin();

		echo "Go to front end and check that Designers and Developers is shown" . "\n";
		$this->gotoSite();
		$this->assertTrue($this->isTextPresent("Designers and Developers"));

		echo "Go to back end and unpublish" . "\n";
		$this->gotoAdmin();
		$this->click("link=Article Manager");
		$this->waitForPageToLoad("30000");
		$this->type("filter_search", "Designers and Developers");
		$this->click("//button[@type='submit']");
		$this->waitForPageToLoad("30000");
		$this->click("//img[@alt='Published']");
		$this->waitForPageToLoad("30000");

		echo "Go to front end and check that Designers and Developers is not shown" . "\n";
		$this->gotoSite();
		$this->assertFalse($this->isTextPresent("Designers and Developers"));

		echo "Go to back end and publish Designers and Developers" . "\n";
		$this->gotoAdmin();
		$this->click("link=Article Manager");
		$this->waitForPageToLoad("30000");

		$this->click("//img[@alt='Unpublished']");
		$this->waitForPageToLoad("30000");
		$this->gotoAdmin();
		$this->doAdminLogout();
	}

	function testPublishArticle()
	{
		$this->setUp();
		echo "Starting testPublishArticle.\n";
		$this->gotoAdmin();
		$this->doAdminLogin();

		echo "Go to back end and unpublish Designers and Developers" . "\n";
		$this->gotoAdmin();
		$this->click("link=Article Manager");
		$this->waitForPageToLoad("30000");
		$this->type("filter_search", "Designers and Developers");
		$this->click("//button[@type='submit']");
		$this->waitForPageToLoad("30000");
		$this->click("//img[@alt='Published']");
		$this->waitForPageToLoad("30000");

		echo "Go to front end and check that Designers and Developers is not shown" . "\n";
		$this->gotoSite();
		$this->assertFalse($this->isTextPresent("Designers and Developers"));

		echo "Go to back end and publish Designers and Developers" . "\n";
		$this->gotoAdmin();
		$this->click("link=Article Manager");
		$this->waitForPageToLoad("30000");

		$this->click("//img[@alt='Unpublished']");
		$this->waitForPageToLoad("30000");

		echo "Go to front end and check that Designers and Developers is shown" . "\n";
		$this->gotoSite();
		$this->assertTrue($this->isTextPresent("Designers and Developers"));
		$this->gotoAdmin();
		$this->doAdminLogout();
	}

	function testEditPermission()
	{
		echo "Starting testEditPermission" . "\n";
		echo "Go to front end and login as admin" . "\n";
		$this->gotoSite();
		$this->doFrontEndLogin();
		echo "Go to Home and check that edit icon is visible" . "\n";
		$this->click("link=Home");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isElementPresent("//img[@alt='edit']"));
		echo "Drill to Sample Data article and check that edit icon is visible" . "\n";
		$this->click("link=Home");
		$this->click("link=Sample Data");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isElementPresent("//img[@alt='edit']"));
		$this->click("link=Home");
		$this->waitForPageToLoad("30000");
		echo "Logout of front end." . "\n";
		$this->doFrontEndLogout();
		echo "Go to home and check that edit icon is not visible." . "\n";
		$this->click("link=Home");
		$this->assertFalse($this->isElementPresent("//img[@alt='edit']"));
		echo "Drill to Sample Data article and check that edit icon is not visible." . "\n";
		$this->click("link=Sample Data");
		$this->waitForPageToLoad("30000");
		$this->assertFalse($this->isElementPresent("//img[@alt='edit']"));
		$this->click("link=Home");
		$this->waitForPageToLoad("30000");

	}

}
