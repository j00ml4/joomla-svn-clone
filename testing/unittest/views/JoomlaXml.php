<?php
class JoomlaXml extends SimpleReporter
{
    function paintHeader( $test_name )
    {
        header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
        header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
        header( "Cache-Control: no-store, no-cache, must-revalidate" );
        header( "Cache-Control: post-check=0, pre-check=0", false );
        header( "Pragma: no-cache" );
        header( "Content-type: application/xhtml+xml" );
        
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        echo "<unittests>";
        echo "<testcase>{$test_name}</testcase>";
    }
    
    function paintFooter( $test_name )
    {
        $colour = ($this->getFailCount() + $this->getExceptionCount() > 0 ? "red" : "green");
        
        echo "<result>";
        echo "<total>".$this->getTestCaseCount()."</total>";
        echo "<completed>".$this->getTestCaseProgress()."</completed>";
        echo "<pass>".$this->getPassCount()."</pass>";
        echo "<fail>".$this->getFailCount()."</fail>";
        echo "<exception>".$this->getExceptionCount()."</exception>";
        echo "</result>";
        echo "</unittests>";
    }
    
    function paintPass( $message )
    {
        parent::paintPass( $message );
        
        $breadcrumb = $this->getTestList();
        array_shift( $breadcrumb );
        
        echo "<pass>";
        echo "<filepath>".$breadcrumb[0]."</filepath>";
        echo "<class>".$breadcrumb[1]."</class>";
        echo "<method>".$breadcrumb[2]."</method>";
        echo "<message>".$this->_htmlEntities( $message )."</message>";
        echo "</pass>";
    }
    
    function paintFail( $message )
    {
        parent::paintFail( $message );
        
        $breadcrumb = $this->getTestList();
        array_shift( $breadcrumb );
        
        echo "<fail>";
        echo "<filepath>".$breadcrumb[0]."</filepath>";
        echo "<class>".$breadcrumb[1]."</class>";
        echo "<method>".$breadcrumb[2]."</method>";
        echo "<message>".$this->_htmlEntities( $message )."</message>";
        echo "</fail>";
    }
    
    function paintException( $message )
    {
        parent::paintException( $message );
        
        $breadcrumb = $this->getTestList();
        array_shift( $breadcrumb );
        
        echo "<exception>";
        echo "<filepath>".$breadcrumb[0]."</filepath>";
        echo "<class>".$breadcrumb[1]."</class>";
        echo "<method>".$breadcrumb[2]."</method>";
        echo "<message>".$this->_htmlEntities( $message )."</message>";
        echo "</exception>";
    }
    
    function _htmlEntities( $message )
    {
        return htmlentities( $message, ENT_COMPAT, 'UTF-8' );
    }
}
?>