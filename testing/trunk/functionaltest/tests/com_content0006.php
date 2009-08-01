<?php
/* com_content
 * This test will verify that archiving and unarchiving works properly.
 */
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class ComContent0006 extends PHPUnit_Extensions_SeleniumTestCase
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
    $this->open($cfg->path);
    $this->assertTrue($this->isTextPresent("Joomla! License Guidelines"));
    $this->assertEquals("Welcome to the Frontpage", $this->getTitle());
    $this->click("//div[@id='leftcolumn']/div[2]/div/div/div/ul/li[7]/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Article Manager");
    $this->waitForPageToLoad("30000");
    $this->click("cb9");
    $this->click("//td[@id='toolbar-archive']/a/span");
    $this->waitForPageToLoad("30000");
    $this->open($cfg->path);
    $this->assertNotEquals("Joomla! License Guidelines", $this->getText("//div[@id='maincolumn']/table[2]/tbody/tr/td[1]/table/tbody/tr[1]/td/div/table[1]/tbody/tr/td[1]"));
    $this->assertFalse($this->isTextPresent("Joomla! License Guidelines"));
    $this->click("//div[@id='leftcolumn']/div[2]/div/div/div/ul/li[7]/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Article Manager");
    $this->waitForPageToLoad("30000");
    $this->click("cb9");
    $this->click("//td[@id='toolbar-unarchive']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("//img[@alt='Unpublished']");
    $this->waitForPageToLoad("30000");
    $this->open($cfg->path);
    $this->assertTrue($this->isTextPresent("Joomla! License Guidelines"));
    $this->click("//div[@id='leftcolumn']/div[2]/div/div/div/ul/li[7]/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Logout");
  }
}
?>
