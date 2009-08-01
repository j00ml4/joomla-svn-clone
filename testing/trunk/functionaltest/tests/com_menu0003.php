<?php
/* com_menu
 * Creating and removing menu types and menu items
 */
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class ComMenu0003 extends PHPUnit_Extensions_SeleniumTestCase
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
    $this->type("menutype", "mytestmenu");
    $this->type("title", "My Test Menu");
    $this->type("description", "A Test Menu");
    $this->type("module_title", "Test Menu");
    $this->click("//td[@id='toolbar-save']/a/span");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isElementPresent("link=My Test Menu"));
    $this->assertTrue($this->isTextPresent("mytestmenu"));
    $this->click("link=Module Manager");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isElementPresent("link=Test Menu"));
    $this->open($cfg->path);
    $this->assertNotEquals("Test Menu", $this->getText("//div[@id='leftcolumn']/div[1]/div/div/div/h3"));
    $this->click("//div[@id='leftcolumn']/div[2]/div/div/div/ul/li[7]/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Module Manager");
    $this->waitForPageToLoad("30000");
    $this->click("//img[@alt='Disabled']");
    $this->waitForPageToLoad("30000");
    $this->open($cfg->path);
    $this->assertEquals("Test Menu", $this->getText("//div[@id='leftcolumn']/div[1]/div/div/div/h3"));
    $this->click("//div[@id='leftcolumn']/div[3]/div/div/div/ul/li[7]/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Module Manager");
    $this->waitForPageToLoad("30000");
    $this->click("cb3");
    $this->click("//td[@id='toolbar-delete']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Menu Manager");
    $this->waitForPageToLoad("30000");
    $this->click("cb6");
    $this->click("//td[@id='toolbar-delete']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("//td[@id='toolbar-delete']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Logout");
    $this->waitForPageToLoad("30000");
  }
}
?>
