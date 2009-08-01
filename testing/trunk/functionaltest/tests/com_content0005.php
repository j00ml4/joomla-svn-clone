<?php
/* com_content
 * This test will verify that the alerts appear appropriately.
 */
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class ComContent0005 extends PHPUnit_Extensions_SeleniumTestCase
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
    $this->click("//td[@id='toolbar-unarchive']/a/span");
    $this->assertEquals("Please select an Article from the list to unarchive", $this->getAlert());
    $this->click("//td[@id='toolbar-archive']/a/span");
    $this->assertEquals("Please select an Article from the list to archive", $this->getAlert());
    $this->click("//td[@id='toolbar-publish']/a/span");
    $this->assertEquals("Please select an Article from the list to publish", $this->getAlert());
    $this->click("//td[@id='toolbar-unpublish']/a/span");
    $this->assertEquals("Please select an Article from the list to unpublish", $this->getAlert());
    $this->click("//td[@id='toolbar-move']/a/span");
    $this->assertEquals("Please select an Article from the list to move", $this->getAlert());
    $this->click("//td[@id='toolbar-copy']/a/span");
    $this->assertEquals("Please select an Article from the list to copy", $this->getAlert());
    $this->click("//td[@id='toolbar-trash']/a/span");
    $this->assertEquals("Please select an Article from the list to trash", $this->getAlert());
    $this->click("//td[@id='toolbar-edit']/a/span");
    $this->assertEquals("Please select an Article from the list to edit", $this->getAlert());
    $this->click("link=Logout");
  }
}
?>
