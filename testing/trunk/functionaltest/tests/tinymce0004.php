<?php
/* TinyMCE
 * Tests underline
 */
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class TinyMCE0004 extends PHPUnit_Extensions_SeleniumTestCase
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
    $this->click("//a[@id='text_underline']/span");
    $this->selectFrame("text_ifr");
    $this->typeKeys("tinymce", "This text is underlined text.");
    $this->selectFrame("relative=top");
    $this->runScript("alert(tinyMCE.activeEditor.getContent())");
    $this->assertEquals("<p><span style=\"text-decoration: underline;\">This text is underlined text</span></p>", $this->getAlert());
    $this->click("//td[@id='toolbar-cancel']/a/span");
    $this->waitForPageToLoad("30000");
    $this->click("link=Logout");
  }
}
?>
