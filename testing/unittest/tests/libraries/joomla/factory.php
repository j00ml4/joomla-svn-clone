<?php
class TestOfJFactory extends UnitTestCase
{
    var $class = null;
    
    function TestOfJFactory()
    {
        $this->class = new JFactory();
    }
    
    function testGetConfig()
    {
        $this->assertTrue( get_class( $this->class->getConfig() ) == 'JRegistry' );
    }

    function testGetSession()
    {
        // TODO: Die gracefully when headers sent 
        $this->assertTrue( get_class( $this->class->getSession() ) == 'JSession' );
    }

    function testGetLanguage()
    {
        $this->assertTrue( get_class( $this->class->getLanguage() ) == 'JLanguage' );
    }

    function testGetDocument()
    {
        include( JPATH_BASE.'/libraries/joomla/environment/request.php' ); //TODO: Factory should load this
        
        $this->assertTrue( get_class( $this->class->getDocument() ) == 'JDocumentHTML' );
    }

    function testGetUser()
    {
        include( JPATH_BASE.'/libraries/joomla/user/user.php' ); //TODO: Factory should load this
        
        $this->assertTrue( get_class( $this->class->getUser() ) == 'JUser' );
    }

    function testGetCache()
    {
        $this->assertTrue( get_class( $this->class->getCache() ) == 'JCacheFunction' );
    }

    function testGetACL()
    {
        include( JPATH_BASE.'/libraries/joomla/database/table.php' ); //TODO: Factory should load this
        
        $this->assertTrue( get_class( $this->class->getACL() ) == 'JAuthorization' );
    }

    function testGetTemplate()
    {
        $this->assertTrue( get_class( $this->class->getTemplate() ) == 'JTemplate' );
    }

    function testGetDBO()
    {
        $this->assertTrue( get_class( $this->class->getDBO() ) =='JDatabaseMySQL' );
    }

    function testGetMailer()
    {
        $this->assertTrue( get_class( $this->class->getMailer() ) == 'JMail' );
    }

    function testGetXMLParser()
    {
        $this->assertTrue( get_class( $this->class->getXMLParser() ) == 'DOMIT_Lite_Document' );
    }

    function testGetEditor()
    {
        include( JPATH_BASE.'/libraries/joomla/application/event.php' ); //TODO: Factory should load this
        
        $this->assertTrue( get_class( $this->class->getEditor() ) =='JEditor' );
    }

    function testGetURI()
    {
        $this->assertTrue( get_class( $this->class->getURI() ) == 'JURI' );
    }
}
?>