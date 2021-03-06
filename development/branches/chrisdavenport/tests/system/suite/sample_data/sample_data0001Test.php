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
class SampleData0001 extends SeleniumJoomlaTestCase
{
	function testModuleOrder()
	{
		$this->setUp();
		$this->gotoAdmin();
		$this->doAdminLogin();
		print("Open up category manager" . "\n");
		$this->click("link=Category Manager");
		$this->waitForPageToLoad("30000");
		print("Move Modules category up one" . "\n");
		$this->click("//a[@title='Move Up' and @onclick=\"return listItemTask('cb4','categories.orderup')\"]");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("Item successfully reordered"));
		print("Move Modules category down one" . "\n");
		$this->click("//a[@title='Move Down' and @onclick=\"return listItemTask('cb3','categories.orderdown')\"]");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("Item successfully reordered"));
		$this->doAdminLogout();
		print("Finish testModuleOrder" . "\n");
	}

	function testMenuItems()
	{
		$this->setUp();
		$this->gotoAdmin();
		$this->doAdminLogin();
		print("Go to front end" . "\n");
		$this->gotoSite();
		$this->click("link=Home");
		$this->waitForPageToLoad("30000");
		print("Go to login" . "\n");
		$this->click("link=Login");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("Username"));
		$this->assertTrue($this->isTextPresent("Password"));
		$this->assertTrue($this->isElementPresent("//button[@type='submit']"));
		$this->click("link=Home");
		$this->waitForPageToLoad("30000");
		print("Go to Sample Data" . "\n");
		$this->click("link=Sample Data");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("Sample Sites"));
		$this->click("link=Home");
		print("Load search" . "\n");
		$this->click("link=Search");
		$this->waitForPageToLoad("30000");

		$this->click("link=Home");
		$this->waitForPageToLoad("30000");

		print("Go to Site Map" . "\n");
		$this->click("link=Site Map");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("Site Map"));

		$this->click("link=Home");
		$this->waitForPageToLoad("30000");
		print("Go to Using Joomla!" . "\n");
		$this->click("link=Using Joomla!");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("Using Joomla!"));
		print("Go to Extensions" . "\n");
		$this->click("link=Extensions");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("Extensions"));
		$this->assertTrue($this->isElementPresent("link=Components"));
		$this->assertTrue($this->isElementPresent("link=Languages"));
		$this->assertTrue($this->isElementPresent("link=Templates"));
		$this->assertTrue($this->isElementPresent("link=Modules"));
		$this->assertTrue($this->isElementPresent("link=Components"));

		print("Go to The Joomla! Community" . "\n");
		$this->click("link=The Joomla! Community");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("The Joomla! Community"));

		print("Go to The Joomla! Project" . "\n");
		$this->click("link=The Joomla! Project");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("The Joomla! Project"));
		print("Go to Using Joomla!" . "\n");
		$this->click("link=Using Joomla!");
		$this->waitForPageToLoad("30000");
		print("Go to Extensions" . "\n");
		$this->click("link=Extensions");
		$this->waitForPageToLoad("30000");
		print("Go to Components" . "\n");
		$this->click("link=Components");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isElementPresent("link=Contact Component"));
		$this->assertTrue($this->isElementPresent("link=Content Component"));
		$this->assertTrue($this->isElementPresent("link=Weblinks Component"));

		$this->assertTrue($this->isElementPresent("link=News Feeds Component"));
		$this->assertTrue($this->isElementPresent("link=Users Component"));
		$this->assertTrue($this->isElementPresent("link=Administrator Components"));
		$this->assertTrue($this->isElementPresent("link=Search Component"));
		$this->click("link=Home");
		$this->waitForPageToLoad("30000");
		$this->gotoAdmin();
		$this->doAdminLogout();
		print("Finish testMenuItems" . "\n");
	}

}
?>
