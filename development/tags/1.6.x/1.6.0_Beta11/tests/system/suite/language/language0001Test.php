<?php
/**
 * @version		$Id$
 * @package		Joomla.SystemTest
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * Tests error messages associated with actions performed when nothing is selected.
 */
require_once 'SeleniumJoomlaTestCase.php';

class Language0001Test extends SeleniumJoomlaTestCase
{
	function testCheckNotSelectedErrorMessages()
	{
	$this->setUp();
	$this->gotoAdmin();
	$this->doAdminLogin();
	$filterOn='doesNotExist';

	$this->jClick('User Manager');
	$screen="User Manager: Users";
	$this->filterView($filterOn);
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-publish']/a/span");
	$button='Activate';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Users selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-unpublish']/a/span");
	$button='Block';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Users selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-delete']/a/span");
	$button='Delete';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Users selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }

	$this->jClick('Groups');
	$screen="User Manager: Groups";
	$this->filterView($filterOn);
    $this->click("checkall-toggle");
    $this->click("link=Delete");
	$button='Delete';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Groups selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }

	$this->jClick('Access Levels');
	$screen='Access Levels';
	$this->filterView($filterOn);
    $this->click("checkall-toggle");
    $this->click("link=Delete");
	$button='Delete';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Access Levels selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
	$this->jClick('Menu Manager');
    $this->click("link=Menu Items");
    $this->waitForPageToLoad("30000");
	$screen='Menu Manager: Menu Items';
	$this->filterView($filterOn);
    $this->click("checkall-toggle");
    $this->click("link=Publish");
	$button='Publish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No menu items selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Unpublish");
	$button='Unpublish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No menu items selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Trash");
	$button='Trash';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No menu items selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Check In");
	$button='Check In';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No menu item successfully checked in"),$screen.'check in error message not displayed or wrong');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-default']/a/span");
	$button='Default';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No menu items selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->jClick('Article Manager');
	$screen='Article Manager: Articles';
	$this->filterView($filterOn);
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-publish']/a/span");
	$button='Publish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";

    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No articles selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-unpublish']/a/span");
	$button='Unpublish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No articles selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-archive']/a/span");
	$button='Archive';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No articles selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Check In");
	$button='Check In';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No article successfully checked in"),$screen.' check in error message not displayed or wrong');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Trash");
	$button='Trash';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No articles selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("link=Categories");
	$screen='Category Manager';
    $this->waitForPageToLoad("30000");
	$this->filterView($filterOn);
    $this->click("checkall-toggle");
    $this->click("link=Publish");
	$button='Publish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Categories selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-unpublish']/a/span");
	$button='Unpublish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Categories selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Archive");
	$button='Archive';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Categories selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Check In");
	$button='Check In';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No category successfully checked in"),$screen.' check in error message not displayed or wrong');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-trash']/a/span");
	$button='Trash';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Categories selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("link=Featured Articles");
	$screen='Featured Articles';
    $this->waitForPageToLoad("30000");
	$this->filterView($filterOn);
    $this->click("checkall-toggle");
    $this->click("link=Publish");
	$button='Publish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No articles selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Unpublish");
	$button='Unpublish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No articles selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Archive");
	$button='Archive';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No articles selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-checkin']/a/span");
	$button='Check in';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No article successfully checked in"),$screen.' check in error message not displayed or wrong');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Trash");
	$button='Trash';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No articles selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }

    $this->click("//ul[@id='menu-banners']/li[1]/a");
	$screen='Banner Manager: Banners';
    $this->waitForPageToLoad("30000");
	$this->filterView($filterOn);
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-publish']/a/span");
	$button='Publish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Banners selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-unpublish']/a/span");
	$button='Unpublish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Banners selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Archive");
	$button='Archive';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Banners selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Check In");
	$button='Check In';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No banner successfully checked in"),$screen.' check in error message not displayed or wrong');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Trash");
	$button='Trash';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Banners selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("//ul[@id='submenu']/li/a[contains(., 'Clients')]");
	$screen='Banner Manager: Clients';
    $this->waitForPageToLoad("30000");
	$this->filterView($filterOn);
    $this->click("checkall-toggle");
    $this->click("link=Publish");
	$button='Publish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No clients selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-unpublish']/a/span");
	$button='Unpublish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No clients selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Archive");
	$button='Archive';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No clients selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Check In");
	$button='Check In';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No client successfully checked in"),$screen.' check in error message not displayed or wrong');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Trash");
	$button='Trash';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No clients selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("//ul[@id='submenu']/li/a[contains(., 'Categories')]");
	$screen='Category Manager: Banners';
    $this->waitForPageToLoad("30000");
	$this->filterView($filterOn);
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-publish']/a/span");
	$button='Publish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Categories selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Unpublish");
	$button='Unpublish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Categories selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Archive");
	$button='Archive';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Categories selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-checkin']/a/span");
	$button='Check in';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No category successfully checked in"),$screen.' check in error message not displayed or wrong');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Trash");
	$button='Trash';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Categories selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("//ul[@id='menu-contacts']/li[1]/a");
	$screen='Contact Manager: Contacts';
    $this->waitForPageToLoad("30000");
	$this->filterView($filterOn);
    $this->click("checkall-toggle");
    $this->click("link=Publish");
	$button='Publish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No contacts selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Unpublish");
	$button='Unpublish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No contacts selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-archive']/a/span");
	$button='Archive';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No contacts selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-checkin']/a/span");
	$button='Check in';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No contact successfully checked in"),$screen.' check in error message not displayed or wrong');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Trash");
	$button='Trash';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No contacts selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("//ul[@id='submenu']/li/a[contains(., 'Categories')]");
	$screen='Category Manager: Contacts';
    $this->waitForPageToLoad("30000");
	$this->filterView($filterOn);
    $this->click("checkall-toggle");
    $this->click("link=Publish");
	$button='Publish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Categories selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Unpublish");
	$button='Unpublish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Categories selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-archive']/a/span");
	$button='Archive';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Categories selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-checkin']/a/span");
	$button='Check in';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No category successfully checked in"),$screen.' check in error message not displayed or wrong');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Trash");
	$button='Trash';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Categories selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("link=Feeds");
	$screen='News Feed Manager';
    $this->waitForPageToLoad("30000");
	$this->filterView($filterOn);
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-publish']/a/span");
	$button='Publish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No news feeds selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Unpublish");
	$button='Unpublish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No news feeds selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Archive");
	$button='Archive';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No news feeds selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-checkin']/a/span");
	$button='Check in';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No news feed successfully checked-in"));
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Trash");
	$button='Trash';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No news feeds selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("//ul[@id='submenu']/li/a[contains(., 'Categories')]");
	$screen='Category Manager: Newsfeeds';
	$this->waitForPageToLoad("30000");
	$this->filterView($filterOn);
    $this->click("checkall-toggle");
    $this->click("link=Publish");
	$button='Publish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Categories selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Unpublish");
	$button='Unpublish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Categories selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Archive");
	$button='Archive';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Categories selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-checkin']/a/span");
	$button='Check in';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No category successfully checked in"),$screen.' check in error message not displayed or wrong');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Trash");
	$button='Trash';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Categories selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("link=Links");
	$screen='Web Links Manager: Web Links';
    $this->waitForPageToLoad("30000");
	$this->filterView($filterOn);
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-publish']/a/span");
	$button='Publish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No weblinks selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Unpublish");
	$button='Unpublish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No weblinks selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-archive']/a/span");
	$button='Archive';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No weblinks selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-checkin']/a/span");
	$button='Check in';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No weblink successfully checked in"),$screen.' check in error message not displayed or wrong');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Trash");
	$button='Trash';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No weblinks selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("//ul[@id='submenu']/li/a[contains(., 'Categories')]");
	$screen='Category Manager: Weblinks';
    $this->waitForPageToLoad("30000");
	$this->filterView($filterOn);
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-publish']/a/span");
	$button='Publish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Categories selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-unpublish']/a/span");
	$button='Unpublish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Categories selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-archive']/a/span");
	$button='Archive';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Categories selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Check In");
	$button='Check In';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No category successfully checked in"),$screen.' check in error message not displayed or wrong');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Trash");
	$button='Trash';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No Categories selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("link=Redirect");
	$screen='Redirect Manager: Links';
	$this->waitForPageToLoad("30000");
	$this->filterView($filterOn);
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-publish']/a/span");
	$button='Publish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No links selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-unpublish']/a/span");
	$button='Unpublish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No links selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-archive']/a/span");
	$button='Archive';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No links selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Trash");
	$button='Trash';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No links selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("link=Extension Manager");
    $this->waitForPageToLoad("30000");
    $this->click("link=Manage");
	$screen='Extension Manager: Manage';
    $this->waitForPageToLoad("30000");
    $this->type("filters_search", $filterOn);
    $this->click("//button[@type='submit']");
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("There are no extensions installed matching your query"),$screen .' screen error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("//li[@id='toolbar-publish']/a/span");
	$button='Publish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    try
	{
    	$this->assertEquals("Please first make a selection from the list", $this->getAlert());
	}
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("//li[@id='toolbar-unpublish']/a/span");
	$button='Unpublish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    try
	{
    	$this->assertEquals("Please first make a selection from the list", $this->getAlert());
	}
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("link=Uninstall");
	$button='Uninstall';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    try
	{
    	$this->assertEquals("Please first make a selection from the list", $this->getAlert());
	}
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
	$this->click("link=Module Manager");
    $screen='Module Manager: Modules';
	$this->waitForPageToLoad("30000");
	$this->filterView($filterOn);
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-copy']/a/span");
	$button='Copy';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No module selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-publish']/a/span");
	$button='Publish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No modules selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Unpublish");
	$button='Unpublish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No modules selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Check In");
	$button='Check In';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No module successfully checked in"),$screen.' check in error message not displayed or wrong');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Trash");
	$button='Trash';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No modules selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("link=Plug-in Manager");
	$screen='Plug-in Manager: Plug-ins';
    $this->waitForPageToLoad("30000");
	$this->filterView($filterOn);
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-publish']/a/span");
	$button='Publish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No plugins selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-unpublish']/a/span");
	$button='Unpublish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No plugins selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("//li[@id='toolbar-checkin']/a/span");
	$button='Check in';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No plugin successfully checked in"),$screen.' check in error message not displayed or wrong');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("link=Template Manager");
	$screen='Template Manager: Styles';
    $this->waitForPageToLoad("30000");
	$this->filterView($filterOn);
    $this->click("//li[@id='toolbar-default']/a/span");
	$button='Default';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    try
	{
    	$this->assertEquals("Please first make a selection from the list", $this->getAlert());
	}
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("//li[@id='toolbar-edit']/a/span");
	$button='Edit';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    try
	{
    	$this->assertEquals("Please first make a selection from the list", $this->getAlert());
	}
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("link=Duplicate");
	$button='Duplicate';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No template selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("link=Delete");
	$button='Delete';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    try
	{
    	$this->assertEquals("Please first make a selection from the list", $this->getAlert());
	}
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
	$this->click("link=Language Manager");
    $this->waitForPageToLoad("30000");
    $this->click("//ul[@id='submenu']/li/a[contains(., 'Content')]");
	$screen='Language Manager: Content Languages';
    $this->waitForPageToLoad("30000");
	$this->filterView($filterOn);
    $this->click("checkall-toggle");
    $this->click("link=Publish");
	$button='Publish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No languages selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Unpublish");
	$button='Unpublish';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No languages selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("checkall-toggle");
    $this->click("link=Trash");
	$button='Trash';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No languages selected"),$screen .' '. $button .' button with nothing selected error message not displayed or changed');
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->click("link=Mass Mail Users");
	$screen='Mass Mail';
    $this->waitForPageToLoad("30000");
	$this->click("link=Send email");
	try
	{
	    $this->assertEquals("Please enter a subject", $this->getAlert());
	}
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
	    array_push($this->verificationErrors, $this->getTraceFiles($e));
	}
	$this->type("jform_subject", "test");
	$this->click("link=Send email");
    try
	{
	     $this->assertEquals("Please enter a message", $this->getAlert());
	}
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
	    array_push($this->verificationErrors, $this->getTraceFiles($e));
	}
	$this->type("jform_message", "test");
    $this->click("//li[@id='toolbar-send']/a/span");
	$button='Send';
    echo "Testing error message when clicking $button button with nothing selected at $screen screen.\n";
    $this->waitForPageToLoad("30000");
    try
	{
        $this->assertTrue($this->isTextPresent("No users could be found in this group."));
    }
	catch (PHPUnit_Framework_AssertionFailedError $e)
	{
        array_push($this->verificationErrors, $this->getTraceFiles($e));
    }
    $this->gotoAdmin();
    $this->doAdminLogout();
  }
}
?>