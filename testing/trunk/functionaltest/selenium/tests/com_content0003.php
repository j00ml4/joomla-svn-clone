<?php
/* com_content
 * This test will create a section, a category and an article
 * and deletes them afterward.
 */

require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class ComContent0003 extends PHPUnit_Extensions_SeleniumTestCase
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

    $this->open($cfg->path);
    $this->click("//div[@id='leftcolumn']/div[1]/div/div/div/ul/li[2]/a/span");
    $this->waitForPageToLoad("30000");
    $this->assertEquals("Joomla! Overview", $this->getTitle());
    $this->assertEquals("Joomla! Overview", $this->getText("//div[@id='maincolumn']/table[2]/tbody/tr/td/table[1]/tbody/tr/td[1]"));
    $this->assertEquals("To get the perfect Web site with all the functionality that you require for your particular application may take additional time and effort, but with the Joomla! Community support that is available and the many Third Party Developers actively creating and releasing new Extensions for the 1.5 platform on an almost daily basis, there is likely to be something out there to meet your needs. Or you could develop your own Extensions and make these available to the rest of the community.", $this->getText("//div[@id='maincolumn']/table[2]/tbody/tr/td/table[2]/tbody/tr[3]/td/p[7]"));
    $this->assertTrue($this->isElementPresent("//img[@alt='PDF']"));
    $this->assertTrue($this->isElementPresent("//img[@alt='Print']"));
    $this->assertTrue($this->isElementPresent("//img[@alt='E-mail']"));
    $this->click("//div[@id='leftcolumn']/div[1]/div/div/div/ul/li[1]/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("//div[@id='leftcolumn']/div[2]/div/div/div/ul/li[7]/a/span");
    $this->waitForPageToLoad("30000");
    $this->type("modlgn_username", $cfg->username);
    $this->type("modlgn_passwd", $cfg->password);
    $this->click("//input[@value='Login']");
    $this->waitForPageToLoad("30000");
    $this->click("link=Article Manager");
    $this->waitForPageToLoad("30000");
    $this->click("link=Joomla! Overview");
    $this->waitForPageToLoad("30000");
    $this->click("//h3[@id='params-page']/span");
    $this->select("paramsshow_title", "label=No");
    $this->click("//td[@id='toolbar-save']/a/span");
    $this->waitForPageToLoad("30000");
    $this->open($cfg->path);
    $this->click("//div[@id='leftcolumn']/div[1]/div/div/div/ul/li[2]/a/span");
    $this->waitForPageToLoad("30000");
    $this->assertNotEquals("Joomla! Overview", $this->getText("//div[@id='maincolumn']/table[2]/tbody/tr/td/table[1]/tbody/tr/td[1]"));
    $this->click("//div[@id='leftcolumn']/div[1]/div/div/div/ul/li[1]/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("//div[@id='leftcolumn']/div[2]/div/div/div/ul/li[7]/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Article Manager");
    $this->waitForPageToLoad("30000");
    $this->click("link=Joomla! Overview");
    $this->waitForPageToLoad("30000");
    $this->click("//h3[@id='params-page']/span");
    $this->select("paramsshow_title", "label=Use Global");
    $this->click("//td[@id='toolbar-save']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Logout");
  }
}
?>
