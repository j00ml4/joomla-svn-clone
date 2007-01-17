<?php
class JoomlaText extends TextReporter
{
    function paintHeader($test_name)
    {
        if ( !SimpleReporter::inCli() )
            header('Content-type: text/plain');
        
        echo "Testcase: $test_name\n";
    }
}
?>