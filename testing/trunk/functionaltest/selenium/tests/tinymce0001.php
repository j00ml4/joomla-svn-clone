<?php
/* TinyMCE
 * Tests typing in the editor and asserts basic text.
 */
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class TinyMCE0001 extends PHPUnit_Extensions_SeleniumTestCase
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
    $this->click("//td[@id='toolbar-new']/a/span");
    $this->waitForPageToLoad("30000");
    $this->selectFrame("text_ifr");
    $this->typeKeys("tinymce", "This text is normal text.");
    $this->selectFrame("relative=top");
    $this->runScript("alert(tinyMCE.activeEditor.getContent())");
    $this->assertEquals("<p>This text is normal text</p>", $this->getAlert());
    $this->click("//td[@id='toolbar-cancel']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Logout");
  }
}
?>
