
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
class Module0001 extends SeleniumJoomlaTestCase
{
	function testUnpublishModule()
	{
		$this->doAdminLogin();
		$this->gotoSite();
		$this->assertTrue($this->isElementPresent("//div[@id='leftcolumn']/div[2]/div/div/div/h3"));
		$this->gotoAdmin();
		$this->click("link=Module Manager");
		$this->waitForPageToLoad("30000");

		$this->type("filter_search", "Login Form");
		$this->click("//button[@type='submit']");
		$this->waitForPageToLoad("30000");
		$this->click("//img[@alt='Published']");
		$this->waitForPageToLoad("30000");

		$this->gotoSite();
		$this->assertFalse($this->isElementPresent("//div[@id='leftcolumn']/div[2]/div/div/div/h3"));

		$this->gotoAdmin();
		$this->click("link=Module Manager");
		$this->waitForPageToLoad("30000");

		$this->type("search", "Login Form");
		$this->click("//button[@type='submit']");
		$this->waitForPageToLoad("30000");
		$this->click("//img[@alt='Unpublished']");
		$this->waitForPageToLoad("30000");
		$this->doAdminLogout();
	}	

	function testPublishModule()
	{
		$this->doAdminLogin();
		$this->gotoAdmin();
		$this->click("link=Module Manager");
		$this->waitForPageToLoad("30000");

		$this->type("search", "Login Form");
		$this->click("//button[@type='submit']");
		$this->waitForPageToLoad("30000");
		$this->click("//img[@alt='Published']");
		$this->waitForPageToLoad("30000");

		$this->gotoSite();
		$this->assertFalse($this->isElementPresent("//div[@id='leftcolumn']/div[2]/div/div/div/h3"));

		$this->gotoAdmin();
		$this->click("link=Module Manager");
		$this->waitForPageToLoad("30000");

		$this->type("search", "Login Form");
		$this->click("//button[@type='submit']");
		$this->waitForPageToLoad("30000");
		$this->click("//img[@alt='Unpublished']");
		$this->waitForPageToLoad("30000");

		$this->gotoSite();
		$this->assertTrue($this->isElementPresent("//div[@id='leftcolumn']/div[2]/div/div/div/h3"));

		$this->doAdminLogout();
	}	

}

