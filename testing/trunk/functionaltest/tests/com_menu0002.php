<?php
/* com_menu
 * Verifies the functionality of creating and removing menu types.
 */
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class ComMenu0002 extends PHPUnit_Extensions_SeleniumTestCase
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
    $this->click("link=Menu Manager");
    $this->waitForPageToLoad("30000");
    $this->click("//td[@id='toolbar-new']/a/span");
    $this->waitForPageToLoad("30000");
    $this->type("menutype", "selenium");
    $this->type("title", "Selenium Menu");
    $this->type("description", "Menu Description");
    $this->type("module_title", "selmenu");
    $this->click("//td[@id='toolbar-save']/a/span");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isTextPresent("Selenium Menu"));
    $this->assertTrue($this->isTextPresent("selenium"));
    $this->click("link=Module Manager");
    $this->waitForPageToLoad("30000");
    $this->select("limit", "label=100");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isTextPresent("selmenu"));
    $this->click("link=Menu Manager");
    $this->waitForPageToLoad("30000");
    $this->click("cb6");
    $this->click("//td[@id='toolbar-delete']/a/span");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isTextPresent("selmenu"));
    $this->click("//td[@id='toolbar-delete']/a/span");
    $this->waitForPageToLoad("30000");
    $this->assertFalse($this->isTextPresent("selenium"));
    $this->click("link=Module Manager");
    $this->waitForPageToLoad("30000");
    $this->assertFalse($this->isTextPresent("selmenu"));
    $this->click("link=Logout");
    $this->waitForPageToLoad("30000");
  }
}
?>
