
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
		echo ("testUnpublishModule"."\n");
		$this->doAdminLogin();
		$this->gotoSite();
		echo ("Check that login form is present"."\n");
		$this->assertTrue($this->isTextPresent("Login Form"));
		$this->gotoAdmin();
		echo ("Goto module manager and disable login module"."\n");
		$this->click("link=Module Manager");
		$this->waitForPageToLoad("30000");

		$this->type("filter_search", "Login Form");
		$this->click("//button[@type='submit']");
		$this->waitForPageToLoad("30000");
		$this->click("//img[@alt='Published']");
		$this->waitForPageToLoad("30000");

		echo ("Go back to front end and check that login is not shown"."\n");
		$this->gotoSite();
		$this->assertFalse($this->isTextPresent("Login Form"));

		echo ("Go back to module manager and enable login module"."\n");
		$this->gotoAdmin();
		$this->click("link=Module Manager");
		$this->waitForPageToLoad("30000");

		$this->type("filter_search", "Login Form");
		$this->click("//button[@type='submit']");
		$this->waitForPageToLoad("30000");
		$this->click("//img[@alt='Unpublished']");
		$this->waitForPageToLoad("30000");
		$this->doAdminLogout();
	}	

	function testPublishModule()
	{
		echo ("testPublishModule"."\n");
		$this->doAdminLogin();
		$this->gotoAdmin();
		$this->click("link=Module Manager");
		$this->waitForPageToLoad("30000");

		echo ("Go to back end and disable login module"."\n");
		$this->type("filter_search", "Login Form");
		$this->click("//button[@type='submit']");
		$this->waitForPageToLoad("30000");
		$this->click("//img[@alt='Published']");
		$this->waitForPageToLoad("30000");

		$this->gotoSite();
		echo ("Go to front and check that login form is not shown"."\n");
		$this->assertFalse($this->isTextPresent("Login Form"));

		echo ("Go to module manager and enable login module"."\n");
		$this->gotoAdmin();
		$this->click("link=Module Manager");
		$this->waitForPageToLoad("30000");

		$this->type("filter_search", "Login Form");
		$this->click("//button[@type='submit']");
		$this->waitForPageToLoad("30000");
		$this->click("//img[@alt='Unpublished']");
		$this->waitForPageToLoad("30000");

		echo ("Go to front end and check that login form is present"."\n");
		$this->gotoSite();
		$this->assertTrue($this->isTextPresent("Login Form"));

		$this->doAdminLogout();
	}	

}

