<?php
/* com_menu
 * Verifies the functionality of creating and removing a menu item
 */
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class ComMenu0001 extends PHPUnit_Extensions_SeleniumTestCase
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
    $this->click("link=exact:Main Menu *");
    $this->waitForPageToLoad("30000");
    $this->click("//td[@id='toolbar-new']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("content");
    $this->waitForPageToLoad("30000");
    $this->click("link=Article Layout");
    $this->waitForPageToLoad("30000");
    $this->click("link=Select");
    sleep(3);
    $this->click("link=Support and Documentation");
    $this->type("name", "Support");
    $this->click("//td[@id='toolbar-save']/a/span");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isTextPresent("Support"));
    $this->open($cfg->path);
    $this->assertTrue($this->isElementPresent("//div[@id='leftcolumn']/div[1]/div/div/div/ul/li[9]/a/span"));
    $this->click("//div[@id='leftcolumn']/div[2]/div/div/div/ul/li[7]/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=exact:Main Menu *");
    $this->waitForPageToLoad("30000");
    $this->click("link=Support");
    $this->waitForPageToLoad("30000");
    $this->click("//td[@id='toolbar-cancel']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("cb9");
    $this->click("//td[@id='toolbar-trash']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Menu Trash");
    $this->waitForPageToLoad("30000");
    $this->click("cb10");
    $this->click("//td[@id='toolbar-delete']/a/span");
    $this->click("link=Delete");
    $this->waitForPageToLoad("30000");
    $this->click("link=Logout");
    $this->waitForPageToLoad("30000");
    $this->open($cfg->path."administrator/index.php");
    $this->click("link=Return to site Home Page");
    $this->waitForPageToLoad("30000");
    $this->assertFalse($this->isElementPresent("//div[@id='leftcolumn']/div[1]/div/div/div/ul/li[9]/a/span"));
  }
}
?>
