<?php
/* com_content
 * 
 * 
 */

require_once 'PHPUnit/Extensions/SeleniumTestCase.php';


class ComContent0001 extends PHPUnit_Extensions_SeleniumTestCase
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
    $this->assertEquals("Joomla! Community Portal", $this->getText("//div[@id='maincolumn']/table[2]/tbody/tr/td[1]/table/tbody/tr[1]/td/div/table[1]/tbody/tr/td[1]"));
    $this->click("//div[@id='leftcolumn']/div[2]/div/div/div/ul/li[7]/a/span");
    $this->waitForPageToLoad("30000");
    $this->type("modlgn_username", $cfg->username);
    $this->type("modlgn_passwd", $cfg->password);
    $this->click("link=Login");
    $this->waitForPageToLoad("30000");
    $this->click("link=Article Manager");
    $this->waitForPageToLoad("30000");
    $this->click("link=Next");
    $this->waitForPageToLoad("30000");
    $this->click("//div[@id='element-box']/div[2]/form/table[2]/tbody/tr[15]/td[5]/a/img");
    $this->waitForPageToLoad("30000");
    $this->open($cfg->path);
    $this->assertNotEquals("Joomla! Community Portal", $this->getText("//div[@id='maincolumn']/table[2]/tbody/tr/td[1]/table/tbody/tr[1]/td/div/table[1]/tbody/tr/td[1]"));
    $this->click("//div[@id='leftcolumn']/div[2]/div/div/div/ul/li[7]/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Article Manager");
    $this->waitForPageToLoad("30000");
    $this->click("//div[@id='element-box']/div[2]/form/table[2]/tbody/tr[15]/td[5]/a/img");
    $this->waitForPageToLoad("30000");
    $this->click("link=Logout");
    $this->waitForPageToLoad("30000");
  }
}
?>
