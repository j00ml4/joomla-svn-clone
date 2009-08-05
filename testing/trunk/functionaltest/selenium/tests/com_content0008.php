<?php
/* com_content
 * This test will verify that publishing and unpublishing works properly.
 */
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class ComContent0008 extends PHPUnit_Extensions_SeleniumTestCase
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
    $this->click("//div[@id='leftcolumn']/div[2]/div/div/div/ul/li[7]/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Article Manager");
    $this->waitForPageToLoad("30000");
    $this->click("//div[@id='element-box']/div[2]/form/table[2]/tbody/tr[10]/td[4]/span/a/img");
    $this->waitForPageToLoad("30000");
    $this->open($cfg->path);
    $this->assertFalse($this->isTextPresent("Joomla! License Guidelines"));
    $this->click("//div[@id='leftcolumn']/div[2]/div/div/div/ul/li[7]/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Article Manager");
    $this->waitForPageToLoad("30000");
    $this->click("//img[@alt='Unpublished']");
    $this->waitForPageToLoad("30000");
    $this->open($cfg->path);
    $this->assertTrue($this->isTextPresent("Joomla! License Guidelines"));
    $this->click("//div[@id='leftcolumn']/div[2]/div/div/div/ul/li[7]/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Article Manager");
    $this->waitForPageToLoad("30000");
    $this->click("cb9");
    $this->click("//td[@id='toolbar-unpublish']/a/span");
    $this->waitForPageToLoad("30000");
    $this->open($cfg->path);
    $this->assertFalse($this->isTextPresent("Joomla! License Guidelines"));
    $this->click("//div[@id='leftcolumn']/div[2]/div/div/div/ul/li[7]/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Article Manager");
    $this->waitForPageToLoad("30000");
    $this->click("cb9");
    $this->click("//td[@id='toolbar-publish']/a/span");
    $this->waitForPageToLoad("30000");
    $this->open($cfg->path);
    $this->assertTrue($this->isTextPresent("Joomla! License Guidelines"));
    $this->click("//div[@id='leftcolumn']/div[2]/div/div/div/ul/li[7]/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Logout");
    $this->waitForPageToLoad("30000");
  }
}
?>
