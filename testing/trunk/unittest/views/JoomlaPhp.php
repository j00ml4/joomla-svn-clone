<?php
class JoomlaPhp extends SimpleReporter
{
    var $_joomlaTests = array();
    
    function paintHeader( $test_name )
    {
        header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
        header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
        header( "Cache-Control: no-store, no-cache, must-revalidate" );
        header( "Cache-Control: post-check=0, pre-check=0", false );
        header( "Pragma: no-cache" );
        header( "Content-type: text/plain" );
        
        flush();
        
        $this->joomlaHeader( $test_name );
    }
    
    function paintFooter( $test_name )
    {
        $this->_joomlaTests['unittests']['result']['total'] = $this->getTestCaseCount();
        $this->_joomlaTests['unittests']['result']['completed'] = $this->getTestCaseProgress();
        $this->_joomlaTests['unittests']['result']['pass'] = $this->getPassCount();
        $this->_joomlaTests['unittests']['result']['fail'] = $this->getFailCount();
        $this->_joomlaTests['unittests']['result']['exceptions'] = $this->getExceptionCount();
        
        echo $this->joomlaOutput();
    }
    
    function joomlaHeader( $test_name )
    {
        $this->_joomlaTests['unittests']['pass'] = array();
        $this->_joomlaTests['unittests']['fail'] = array();
        $this->_joomlaTests['unittests']['exception'] = array();
        $this->_joomlaTests['unittests']['result'] = array();
        
        $this->_joomlaTests['unittests']['testcase'] = $test_name;
    }
    
    function joomlaOutput()
    {
        return serialize( $this->_joomlaTests );
    }
    
    function paintPass( $message )
    {
        parent::paintPass( $message );
        
        $breadcrumb = $this->getTestList();
        array_shift( $breadcrumb );
        
        $this->_joomlaTests['unittests']['pass'][$breadcrumb[1]][$breadcrumb[2]]['filepath'] = $breadcrumb[0];
        $this->_joomlaTests['unittests']['pass'][$breadcrumb[1]][$breadcrumb[2]]['message'] = $message;
    }
    
    function paintFail( $message )
    {
        parent::paintFail( $message );
        
        $breadcrumb = $this->getTestList();
        array_shift( $breadcrumb );
        
        $this->_joomlaTests['unittests']['fail'][$breadcrumb[1]][$breadcrumb[2]]['filepath'] = $breadcrumb[0];
        $this->_joomlaTests['unittests']['fail'][$breadcrumb[1]][$breadcrumb[2]]['message'] = $message;
    }
    
    function paintException( $message )
    {
        parent::paintException( $message );
        
        $breadcrumb = $this->getTestList();
        array_shift( $breadcrumb );
        
        $this->_joomlaTests['unittests']['exception'][$breadcrumb[1]][$breadcrumb[2]]['filepath'] = $breadcrumb[0];
        $this->_joomlaTests['unittests']['exception'][$breadcrumb[1]][$breadcrumb[2]]['message'] = $message;
    }
}
?>