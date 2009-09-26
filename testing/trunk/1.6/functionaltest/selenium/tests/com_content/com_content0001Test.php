<?php
/**
 * @version		$Id$
 * @package		Joomla.FunctionalTest
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * that you can add, edit, and delete article from article manager
 */

require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class ComContent0001 extends SeleniumJoomlaTestCase
{

 
  function testMyTestCase()
  {
  	$this->setUp();
  	$this->doAdminLogin();
    $this->click("link=Control Panel");
    $this->waitForPageToLoad("30000");
    print("Load article manager." . "\n");
    $this->click("link=Article Manager");
    $this->waitForPageToLoad("30000");


    $this->click("//li[@id='toolbar-new']/a/span");
    $this->waitForPageToLoad("30000");
    
    print("Enter article title" . "\n");
    $this->type("jform_title", "Com_Content001 Test Article");
    
    print("Enter some text" . "\n");
    $this->typeKeys("tinymce", "This is test text for an article");
    print("Save the article" . "\n");
    $this->click("//li[@id='toolbar-save']/a/span");
    $this->waitForPageToLoad("30000");
    print("Check that article title is listed in Article Manager" . "\n");
    $this->assertEquals("Com_Content001 Test Article", $this->getText("link=Com_Content001 Test Article"));
    print("Open Article for editing" . "\n");
    $this->click("link=Com_Content001 Test Article");
    $this->waitForPageToLoad("30000");
    print("Check that title and text are correct" . "\n");
    $this->assertEquals("This is test text for an article", $this->getText("//body[@id='tinymce']/p"));
    $this->assertEquals("Com_Content001 Test Article", $this->getValue("jform_title"));
    print("Cancel edit" . "\n");
    $this->click("//li[@id='toolbar-cancel']/a/span");
    $this->waitForPageToLoad("30000");
    print("Send article to trash" . "\n");
    $this->click("cb0");
    $this->click("//li[@id='toolbar-trash']/a/span");
    $this->waitForPageToLoad("30000");
    print("Check that article is no longer shown in article manager" . "\n");
    $this->assertFalse($this->isTextPresent("Com_Content001 Test Article"));
    $this->doAdminLogout();
    
    print("Finished com_content0001.php." . "\n");
    
  }
}
?>
