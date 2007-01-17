<?php
class JoomlaPhp extends SimpleReporter
{
    var $unittests = array();
    
    function paintHeader( $test_name )
    {
        header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
        header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
        header( "Cache-Control: no-store, no-cache, must-revalidate" );
        header( "Cache-Control: post-check=0, pre-check=0", false );
        header( "Pragma: no-cache" );
        header( "Content-type: text/plain" );
        
        $this->unittests['unittests']['testcase'] = $test_name;
    }
    
    function paintFooter( $test_name )
    {
        $this->unittests['unittests']['result']['total'] = $this->getTestCaseCount();
        $this->unittests['unittests']['result']['completed'] = $this->getTestCaseProgress();
        $this->unittests['unittests']['result']['pass'] = $this->getPassCount();
        $this->unittests['unittests']['result']['fail'] = $this->getFailCount();
        $this->unittests['unittests']['result']['exceptions'] = $this->getExceptionCount();
        
        echo $this->output();
    }
    
    function output()
    {
        return serialize( $this->unittests );
    }
    
    function paintPass( $message )
    {
        parent::paintPass( $message );
        
        $breadcrumb = $this->getTestList();
        array_shift( $breadcrumb );
        
        $this->unittests['unittests']['pass'][$breadcrumb[1]][$breadcrumb[2]]['filepath'] = $breadcrumb[0];
        $this->unittests['unittests']['pass'][$breadcrumb[1]][$breadcrumb[2]]['message'] = $message;
    }
    
    function paintFail( $message )
    {
        parent::paintFail( $message );
        
        $breadcrumb = $this->getTestList();
        array_shift( $breadcrumb );
        
        $this->unittests['unittests']['fail'][$breadcrumb[1]][$breadcrumb[2]]['filepath'] = $breadcrumb[0];
        $this->unittests['unittests']['fail'][$breadcrumb[1]][$breadcrumb[2]]['message'] = $message;
    }
    
    function paintException( $message )
    {
        parent::paintException( $message );
        
        $breadcrumb = $this->getTestList();
        array_shift( $breadcrumb );
        
        $this->unittests['unittests']['exception'][$breadcrumb[1]][$breadcrumb[2]]['filepath'] = $breadcrumb[0];
        $this->unittests['unittests']['exception'][$breadcrumb[1]][$breadcrumb[2]]['message'] = $message;
    }
}
?>