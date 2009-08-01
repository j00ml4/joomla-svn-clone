<?php
/* com_media
 * Verifies the proper values for the path names for image directories
 */
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class ComMedia0001 extends PHPUnit_Extensions_SeleniumTestCase
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
    $this->click("link=Example Pages and Menu Links");
    $this->waitForPageToLoad("30000");
    $this->click("link=Image");
    sleep(2);
    $this->assertTrue($this->isElementPresent("//option[@value='']"));
    $this->assertTrue($this->isElementPresent("//option[@value='food']"));
    $this->assertTrue($this->isElementPresent("//option[@value='fruit']"));
    $this->click("//button[@type='button' and @onclick=\"window.parent.document.getElementById('sbox-window').close();\"]");
    $this->click("//td[@id='toolbar-cancel']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Logout");
    $this->waitForPageToLoad("30000");
  }
}
?>
