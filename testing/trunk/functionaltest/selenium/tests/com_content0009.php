<?php
/* com_content
 * Copy a single article
 */
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class ComContent0009 extends PHPUnit_Extensions_SeleniumTestCase
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
    $this->click("link=exact:Main Menu *");
    $this->waitForPageToLoad("30000");
    $this->click("//td[@id='toolbar-new']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("content");
    $this->waitForPageToLoad("30000");
    $this->click("link=Category List Layout");
    $this->waitForPageToLoad("30000");
    $this->select("urlparamsid", "label=About Joomla!/The CMS");
    $this->type("name", "The CMS");
    $this->click("//td[@id='toolbar-save']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("//td[@id='toolbar-new']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("content");
    $this->waitForPageToLoad("30000");
    $this->click("link=Category List Layout");
    $this->waitForPageToLoad("30000");
    $this->select("urlparamsid", "label=About Joomla!/The Community");
    $this->type("name", "The Community");
    $this->click("//td[@id='toolbar-save']/a/span");
    $this->waitForPageToLoad("30000");
    $this->open($cfg->path);
    $this->click("//div[@id='leftcolumn']/div[1]/div/div/div/ul/li[9]/a/span");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isTextPresent("Joomla! Features"));
    $this->click("//div[@id='leftcolumn']/div[1]/div/div/div/ul/li[10]/a/span");
    $this->waitForPageToLoad("30000");
    $this->assertFalse($this->isTextPresent("Joomla! Features"));
    $this->open($cfg->path."administrator/index.php");
    $this->click("link=Article Manager");
    $this->waitForPageToLoad("30000");
    $this->click("cb4");
    $this->click("//td[@id='toolbar-copy']/a/span");
    $this->waitForPageToLoad("30000");
    $this->select("sectcat", "label=About Joomla! / The Community");
    $this->click("//td[@id='toolbar-save']/a/span");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isTextPresent("1 Article(s) successfully copied to Section:"));
    $this->open($cfg->path);
    $this->click("//div[@id='leftcolumn']/div[1]/div/div/div/ul/li[10]/a/span");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isTextPresent("Joomla! Features"));
    $this->open($cfg->path."administrator/index.php");
    $this->click("link=Article Manager");
    $this->waitForPageToLoad("30000");
    $this->click("cb6");
    $this->click("//td[@id='toolbar-trash']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Article Trash");
    $this->waitForPageToLoad("30000");
    $this->click("cb0");
    $this->click("//td[@id='toolbar-delete']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Delete");
    $this->waitForPageToLoad("30000");
    $this->click("link=exact:Main Menu *");
    $this->waitForPageToLoad("30000");
    $this->click("cb9");
    $this->click("cb10");
    $this->click("//td[@id='toolbar-trash']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Menu Trash");
    $this->waitForPageToLoad("30000");
    $this->click("toggle1");
    $this->click("//td[@id='toolbar-delete']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Delete");
    $this->waitForPageToLoad("30000");
    $this->click("link=Control Panel");
    $this->waitForPageToLoad("30000");
    $this->click("link=Logout");
    $this->waitForPageToLoad("30000");
  }
}
?>
