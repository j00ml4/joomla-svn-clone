
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
		$this->doAdminLogin();
		$this->gotoSite();
		$this->assertElementContainsText("//div[@id='maincolumn']/table/tbody/tr/td/div/ul/li/h3", 'Welcome to Joomla!');
		$this->gotoAdmin();
		$this->click("link=Article Manager");
		$this->waitForPageToLoad("30000");
		$this->type("filter_search", "Welcome to Joomla!");
		$this->click("//button[@type='submit']");
		$this->waitForPageToLoad("30000");
		$this->click("//img[@alt='Published']");
		$this->waitForPageToLoad("30000");

		$this->gotoSite();
		$this->assertElementNotPresent("//div[@id='maincolumn']/table/tbody/tr/td/div/ul/li/h3");

		$this->gotoAdmin();
		$this->click("link=Article Manager");
		$this->waitForPageToLoad("30000");

		$this->click("//img[@alt='Unpublished']");
		$this->waitForPageToLoad("30000");
		$this->doAdminLogout();
	}	

	function testPublishArticle()
	{
		$this->doAdminLogin();

		$this->gotoAdmin();
		$this->click("link=Article Manager");
		$this->waitForPageToLoad("30000");
		$this->type("filter_search", "Welcome to Joomla!");
		$this->click("//button[@type='submit']");
		$this->waitForPageToLoad("30000");
		$this->click("//img[@alt='Published']");
		$this->waitForPageToLoad("30000");

		$this->gotoSite();
		$this->assertElementNotPresent("//div[@id='maincolumn']/table/tbody/tr/td/div/ul/li/h3");

		$this->gotoAdmin();
		$this->click("link=Article Manager");
		$this->waitForPageToLoad("30000");

		$this->click("//img[@alt='Unpublished']");
		$this->waitForPageToLoad("30000");

		$this->gotoSite();
		$this->assertElementContainsText("//div[@id='maincolumn']/table/tbody/tr/td/div/ul/li/h3", 'Welcome to Joomla!');

		$this->doAdminLogout();
	}	

}

