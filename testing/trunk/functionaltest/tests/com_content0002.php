<?php
/* com_content
 * Verifies that all the toolbar icons are present.
 */

require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class ComContent0002 extends PHPUnit_Extensions_SeleniumTestCase
{
  function setUp()
  {
    $cfg = new SeleniumConfig();
    $this->setBrowser("*chrome");
    $this->setBrowserUrl($cfg->host.$cfg->path.'administrator/');
  }

  function testMyTestCase()
  {
    $cfg = new SeleniumConfig();

    $this->open($cfg->path."administrator/index.php");
    $this->type("modlgn_username", $cfg->username);
    $this->type("modlgn_passwd", $cfg->password);
    $this->click("//input[@value='Login']");
    $this->waitForPageToLoad("30000");

    $this->assertTrue($this->isElementPresent("link=Article Manager"));
    $this->assertTrue($this->isElementPresent("link=Article Trash"));
    $this->assertTrue($this->isElementPresent("link=Section Manager"));
    $this->assertTrue($this->isElementPresent("link=Category Manager"));
    $this->assertTrue($this->isElementPresent("link=Front Page Manager"));
    $this->click("link=Article Manager");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-unarchive']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-archive']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-publish']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-unpublish']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-move']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-copy']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-trash']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-edit']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-new']/a/span"));
    $this->assertTrue($this->isElementPresent("link=Parameters"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-help']/a/span"));
    $this->click("link=Example Pages and Menu Links");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-popup-Popup']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-save']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-apply']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-cancel']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-help']/a/span"));
    $this->click("//td[@id='toolbar-cancel']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("//td[@id='toolbar-new']/a/span");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-popup-Popup']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-save']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-apply']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-cancel']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-help']/a/span"));
    $this->click("//td[@id='toolbar-cancel']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Article Trash");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-restore']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-delete']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-help']/a/span"));
    $this->click("link=Section Manager");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-publish']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-unpublish']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-copy']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-delete']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-edit']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-new']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-help']/a/span"));
    $this->click("link=Category Manager");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-publish']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-unpublish']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-move']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-copy']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-delete']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-edit']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-new']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-help']/a/span"));
    $this->click("//td[@id='toolbar-new']/a/span");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-save']/a/span"));
    $this->assertTrue($this->isElementPresent("link=Apply"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-cancel']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-help']/a/span"));
    $this->click("//td[@id='toolbar-cancel']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=The Project");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-save']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-apply']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-cancel']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-help']/a/span"));
    $this->click("//td[@id='toolbar-cancel']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Section Manager");
    $this->waitForPageToLoad("30000");
    $this->click("link=About Joomla!");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-save']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-apply']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-cancel']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-help']/a/span"));
    $this->click("//td[@id='toolbar-cancel']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("//td[@id='toolbar-new']/a/span");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-save']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-apply']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-cancel']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-help']/a/span"));
    $this->click("//td[@id='toolbar-cancel']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Front Page Manager");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-archive']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-publish']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-unpublish']/a/span"));
    $this->assertTrue($this->isElementPresent("//td[@id='toolbar-delete']/a/span"));
    $this->assertTrue($this->isElementPresent("//a[@onclick=\"popupWindow('http://help.joomla.org/index2.php?option=com_content&task=findkey&tmpl=component;1&keyref=screen.frontpage.15', 'Help', 640, 480, 1)\"]"));
    $this->click("link=Logout");
    $this->waitForPageToLoad("30000");
  }
}
?>
