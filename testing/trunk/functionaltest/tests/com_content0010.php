<?php
/* com_content
 * Copy a single article
 */
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class ComContent0010 extends PHPUnit_Extensions_SeleniumTestCase
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
    $this->assertEquals("Example Pages and Menu Links", $this->getText("link=Example Pages and Menu Links"));
    $this->click("link=Article Trash");
    $this->waitForPageToLoad("30000");
    $this->assertFalse($this->isTextPresent("Example Pages and Menu Links"));
    $this->click("link=Article Manager");
    $this->waitForPageToLoad("30000");
    $this->click("cb0");
    $this->click("//td[@id='toolbar-trash']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Article Trash");
    $this->waitForPageToLoad("30000");
    $this->assertEquals("Example Pages and Menu Links", $this->getText("//div[@id='tablecell']/table/tbody/tr/td[3]"));
    $this->click("cb0");
    $this->click("//td[@id='toolbar-restore']/a/span");
    $this->waitForPageToLoad("30000");
    $this->assertEquals("Example Pages and Menu Links", $this->getText("//div[@id='element-box']/div[2]/form/table/tbody/tr[1]/td[3]/ol/li"));
    $this->click("link=Restore");
    $this->waitForPageToLoad("30000");
    $this->assertTrue((bool)preg_match('/^Are you sure you want to restore the listed Items[\s\S]$/',$this->getConfirmation()));
    $this->click("link=Article Manager");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isTextPresent("Example Pages and Menu Links"));
    $this->click("//img[@alt='Unpublished']");
    $this->waitForPageToLoad("30000");
    $this->click("link=Logout");
  }
}
?>
