<?php
/**
 * @version		$Id$
 * @package		Joomla.FunctionalTest
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * loads each menu choice in back end
 */

require_once 'SeleniumJoomlaTestCase.php';

/**
 * @group ControlPanel
 */
class ControlPanel0002 extends SeleniumJoomlaTestCase
{

	function testMenuCheck()
	{
		$this->setUp();
		$this->doAdminLogin();
		$this->gotoAdmin();
		echo "Navigate to Control Panel.\n";
		$this->click("link=Control Panel");
		$this->waitForPageToLoad("30000");
		echo "Navigate to Global Config.\n";
		$this->click("link=Global Configuration");
		$this->waitForPageToLoad("30000");
		$this->click("site");
		$this->assertTrue($this->isTextPresent("Site Settings"));
		$this->click("system");
		$this->assertTrue($this->isTextPresent("System Settings"));
		$this->click("server");
		$this->assertTrue($this->isTextPresent("Server Settings"));
		$this->click("//li[@id='toolbar-cancel']/a/span");
		$this->waitForPageToLoad("30000");
		echo "Navigate to Global Check-in.\n";
		$this->click("link=Global Check-in");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("Global Check-in"));
		echo "Navigate to Clear Cache.\n";
		$this->click("link=Clear Cache");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("Cache Manager: Clear Cache"));
		echo "Navigate to Purge Expired Cache.\n";
		$this->click("link=Purge Expired Cache");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("Cache Manager: Purge Expired Cache"));
		$this->click("link=System Information");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("System Information"));
		$this->click("phpsettings");
		$this->assertTrue($this->isTextPresent("Relevant PHP Settings"));
		$this->click("config");
		$this->assertTrue($this->isTextPresent("Configuration File"));
		$this->click("directory");
		$this->assertTrue($this->isTextPresent("Directory Permissions"));
		$this->click("phpinfo");
		$this->assertTrue($this->isTextPresent("PHP Information"));
		echo "Navigate to User Manager.\n";
		$this->click("link=User Manager");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("User Manager: Users"));
		$this->click("//ul[@id='submenu']/li[2]/a");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("User Manager: Groups"));
		$this->click("link=Access Levels");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("User Manager: Access Levels"));
		$this->click("link=Groups");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("User Manager: Groups"));
		$this->click("link=Access Levels");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("User Manager: Access Levels"));
		echo "Navigate to Add New User.\n";
		$this->click("link=Add New User");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("User Manager: Add New User"));
		$this->click("//li[@id='toolbar-cancel']/a/span");
		$this->waitForPageToLoad("30000");
		echo "Navigate to Add New Group.\n";
		$this->click("link=Add New Group");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("User Manager: Add New Group"));
		$this->click("//li[@id='toolbar-cancel']/a/span");
		$this->waitForPageToLoad("30000");
		echo "Navigate to Add New Access Level.\n";
		$this->click("link=Add New Access Level");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("User Manager: Add New Access Level"));
		$this->click("//li[@id='toolbar-cancel']/a/span");
		$this->waitForPageToLoad("30000");
		echo "Navigate to Mass Mail.\n";
		$this->click("link=Mass Mail Users");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("E-mail Groups"));
		$this->click("//li[@id='toolbar-cancel']/a/span");
		$this->waitForPageToLoad("30000");
		echo "Navigate to Read Private Messages.\n";
		$this->click("link=Read Private Messages");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("Read Private Messages"));
		echo "Navigate to New Private Message.\n";
		$this->click("link=New Private Message");
		$this->waitForPageToLoad("30000");
		try {
			$this->assertTrue($this->isTextPresent("New Private Message"));
		} catch (PHPUnit_Framework_AssertionFailedError $e) {
			array_push($this->verificationErrors, $e->toString());
		}

		echo "Navigate to Menu Manager.\n";
		$this->click("link=Menu Manager");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("Menu Manager: Menus"));
		echo "Navigate to Article Manager.\n";
		$this->click("link=Article Manager");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("Article Manager: Articles"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-new']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-edit']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-publish']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-unpublish']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-archive']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-trash']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-popup-Popup']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-help']/a/span"));
		echo "Navigate to Category Manager.\n";
		$this->click("link=Category Manager");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("Category Manager"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-new']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-edit']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-publish']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-unpublish']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-archive']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-trash']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-refresh']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-help']/a/span"));
		echo "Navigate to Featured Articles.\n";
		$this->click("link=Featured Articles");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("Article Manager: Featured Articles"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-new']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-edit']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-publish']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-unpublish']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-archive']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-delete']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-popup-Popup']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-help']/a/span"));
		echo "Navigate to Add New Article.\n";
		$this->click("link=Add New Article");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("Article Manager: Add New Article"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-save']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-apply']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-new']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-cancel']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-help']/a/span"));
		$this->click("//li[@id='toolbar-cancel']/a/span");
		$this->waitForPageToLoad("30000");
		echo "Navigate to Add New Category.\n";
		$this->click("link=Add New Category");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("Category Manager: Add New Category"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-save']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-apply']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-new']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-cancel']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-help']/a/span"));
		$this->click("//li[@id='toolbar-cancel']/a/span");
		$this->waitForPageToLoad("30000");

		echo "Navigate to Control Panel.\n";
		$this->gotoAdmin();
		echo "Navigate to Banner Manager.\n";
		$this->click("link=Banners");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("Banner Manager: Banners"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-publish']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-unpublish']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-copy']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-delete']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-edit']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-new']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-popup-Popup']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-help']/a/span"));
		echo "Navigate to Banner Clients.\n";
		$this->click("link=Clients");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("Banner Manager: Clients"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-delete']/a/span"));
		$this->assertTrue($this->isElementPresent("link=Edit"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-new']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-help']/a/span"));
		echo "Navigate to Banner Categories.\n";
		$this->click("link=Categories");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("Category Manager"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-new']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-edit']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-publish']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-unpublish']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-archive']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-trash']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-refresh']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-help']/a/span"));

		echo "Navigate to Contact Manager.\n";
		$this->click("//ul[@id='menu-contacts']/li[1]/a");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("Contact Manager"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-new']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-edit']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-publish']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-unpublish']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-archive']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-trash']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-help']/a/span"));
		echo "Navigate to Contact Category.\n";
		$this->click("//ul[@id='menu-contacts']/li[2]/a");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("Category Manager"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-new']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-edit']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-publish']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-unpublish']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-archive']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-trash']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-refresh']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-help']/a/span"));
		echo "Navigate to News Feed Manager.\n";
		$this->click("link=Feeds");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("News Feed Manager"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-new']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-edit']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-publish']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-unpublish']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-delete']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-popup-Popup']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-help']/a/span"));
		echo "Navigate to News Feed Categories.\n";
		$this->click("//ul[@id='menu-news-feeds']/li[2]/a");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("Category Manager"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-new']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-edit']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-publish']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-unpublish']/a/span"));

		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-archive']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-trash']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-refresh']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-help']/a/span"));
		echo "Navigate to Redirect.\n";
		$this->click("link=Redirect");
		$this->waitForPageToLoad("30000");
		$this->assertEquals("Redirect", $this->getText("//div[@id='toolbar-box']/div[2]/div[2]/h2"));

		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-new']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-edit']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-default']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-publish']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-unpublish']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-archive']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-Are you sure you want to remove these links?']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-help']/a/span"));
		echo "Navigate to Search Statistics.\n";
		$this->click("link=Search");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("Search Statistics"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-delete']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-popup-Popup']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-help']/a/span"));
		echo "Navigate to Weblinks Manager.\n";
		$this->click("link=Links");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-new']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-edit']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-publish']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-unpublish']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-trash']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-popup-Popup']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-help']/a/span"));
		echo "Navigate to Web Links Categories.\n";
		$this->click("//ul[@id='menu-web-links']/li[2]/a");
		$this->waitForPageToLoad("30000");
		$this->assertTrue($this->isTextPresent("Category Manager"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-new']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-edit']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-publish']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-unpublish']/a/span"));

		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-archive']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-trash']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-trash']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-refresh']/a/span"));
		$this->assertTrue($this->isElementPresent("//li[@id='toolbar-help']/a/span"));
		$this->gotoAdmin();
		$this->doAdminLogout();
		print("Finish control_panel0002Test.php." . "\n");
	}
}
?>
