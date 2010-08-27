<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

/**
 * Description of newSeleneseTest
 *
 * @author Troy T. Hall
 */
class newSeleneseTest extends PHPUnit_Extensions_SeleniumTestCase {
    
    function setUp() {
        $this->setBrowser("*chrome");
        $this->setBrowserUrl("http://localhost/j16/");
    }

    function testMyTestCase() {
        
    }
}
?>