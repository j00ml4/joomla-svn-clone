<?php
/* com_content
 * This test will verify that moving articles works properly.
 */
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class ComContent0007 extends PHPUnit_Extensions_SeleniumTestCase
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
    $this->click("link=Article Manager");
    $this->waitForPageToLoad("30000");
    $this->click("link=Joomla! License Guidelines");
    $this->waitForPageToLoad("30000");
    $this->select("sectionid", "label=FAQs");
    $this->select("catid", "label=General");
    $this->click("//td[@id='toolbar-save']/a/span");
    $this->waitForPageToLoad("30000");
    $this->open($cfg->path);
    $this->click("//div[@id='leftcolumn']/div[1]/div/div/div/ul/li[5]/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=General");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isTextPresent("Joomla! License Guidelines"));
    $this->click("//div[@id='leftcolumn']/div[1]/div/div/div/ul/li[4]/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=The Project");
    $this->waitForPageToLoad("30000");
    $this->assertFalse($this->isTextPresent("Joomla! License Guidelines"));
    $this->open($cfg->path."administrator/index.php");
    $this->click("link=Article Manager");
    $this->waitForPageToLoad("30000");
    $this->click("link=Joomla! License Guidelines");
    $this->waitForPageToLoad("30000");
    $this->select("sectionid", "label=About Joomla!");
    $this->select("catid", "label=The Project");
    $this->click("//td[@id='toolbar-save']/a/span");
    $this->waitForPageToLoad("30000");
    $this->open("/selsampledata/");
    $this->click("//div[@id='leftcolumn']/div[1]/div/div/div/ul/li[5]/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=General");
    $this->waitForPageToLoad("30000");
    $this->assertFalse($this->isTextPresent("Joomla! License Guidelines"));
    $this->click("//div[@id='leftcolumn']/div[1]/div/div/div/ul/li[4]/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=The Project");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isTextPresent("Joomla! License Guidelines"));
    $this->open($cfg->path."administrator/index.php");
    $this->click("link=Logout");
    $this->waitForPageToLoad("30000");
  }
}
?>
