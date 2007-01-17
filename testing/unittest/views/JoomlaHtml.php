<?php
class JoomlaHtml extends SimpleReporter
{
    /**
     *    Paints the top of the web page setting the
     *    title to the name of the starting test.
     *    @param string $test_name      Name class of test.
     *    @access public
     */
    function paintHeader( $test_name )
    {
        header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
        header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
        header( "Cache-Control: no-store, no-cache, must-revalidate" );
        header( "Cache-Control: post-check=0, pre-check=0", false );
        header( "Pragma: no-cache" );
        
        echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">";
        echo "<html>\n<head>\n<title>Testcase: $test_name</title>\n";
        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
        echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"views/style.css\" />\n";
        echo "</head>\n<body>\n";
        echo "<h1 class=\"header\">Testcase: $test_name</h1>\n";
    }
    
    /**
     *    Paints the end of the test with a summary of
     *    the passes and failures.
     *    @param string $test_name        Name class of test.
     *    @access public
     */
    function paintFooter( $test_name )
    {
        $state = ($this->getFailCount() + $this->getExceptionCount() > 0 ? "fail" : "pass");
        
        echo "<div class=\"footer_{$state}\">";
        echo $this->getTestCaseProgress() . "/" . $this->getTestCaseCount();
        echo " test cases complete:\n";
        echo "<strong>" . $this->getPassCount() . "</strong> passes, ";
        echo "<strong>" . $this->getFailCount() . "</strong> fails and ";
        echo "<strong>" . $this->getExceptionCount() . "</strong> exceptions.";
        echo "</div>\n";
        echo "</body>\n</html>\n";
    }
    
    function paintPass( $message )
    {
        parent::paintPass( $message );
        
        echo "<div class=\"pass\">";
        
        $breadcrumb = $this->getTestList();
        array_shift( $breadcrumb );
        
        echo implode(" -&gt; ", $breadcrumb );
        
        echo " -&gt; " . $this->_htmlEntities( $message );
        echo "</div>";
    }
    /**
     *    Paints the test failure with a breadcrumbs
     *    trail of the nesting test suites below the
     *    top level test.
     *    @param string $message    Failure message displayed in
     *                              the context of the other tests.
     *    @access public
     */
    function paintFail( $message )
    {
        parent::paintFail( $message );
        
        echo "<div class=\"fail\">";
        
        $breadcrumb = $this->getTestList();
        array_shift( $breadcrumb );
        
        echo implode(" -&gt; ", $breadcrumb );
        
        echo " -&gt; " . $this->_htmlEntities( $message );
        echo "</div>";
    }
    
    /**
     *    Paints a PHP error or exception.
     *    @param string $message        Message is ignored.
     *    @access public
     *    @abstract
     */
    function paintException( $message )
    {
        parent::paintException( $message );
        
        echo "<div class=\"exception\">";
        
        $breadcrumb = $this->getTestList();
        array_shift( $breadcrumb );
        
        echo implode( " -&gt; ", $breadcrumb );
        echo " -&gt; " . $this->_htmlEntities($message);
        echo "</div>";
    }
    
    /**
     *    Paints formatted text such as dumped variables.
     *    @param string $message        Text to show.
     *    @access public
     */
    function paintFormattedMessage( $message )
    {
        echo '<pre class="message">'.$this->_htmlEntities( $message ).'</pre>';
    }
    
    /**
     *    Character set adjusted entity conversion.
     *    @param string $message    Plain text or Unicode message.
     *    @return string            Browser readable message.
     *    @access protected
     */
    function _htmlEntities( $message )
    {
        return htmlentities( $message, ENT_COMPAT, 'UTF-8' );
    }
}
?>