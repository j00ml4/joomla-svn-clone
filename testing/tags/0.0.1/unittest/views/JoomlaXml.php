<?php
require_once( 'JoomlaPhp.php' );

class JoomlaXml extends JoomlaPhp
{
    function paintHeader( $test_name )
    {
        header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
        header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
        header( "Cache-Control: no-store, no-cache, must-revalidate" );
        header( "Cache-Control: post-check=0, pre-check=0", false );
        header( "Pragma: no-cache" );
        header( "Content-type: application/xhtml+xml" );
        
        flush();
        
        $this->joomlaHeader( $test_name );
    }
    
    function joomlaOutput()
    {
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        echo "<unittests>";
        echo "<testcase>".$this->_joomlaTests['unittests']['testcase']."</testcase>";
        
        foreach( $this->_joomlaTests['unittests']['pass'] as $class => $value )
        {
            foreach( $value as $method => $data )
            {
                echo "<pass>";
                $this->printEntry( $class, $method, $data['filepath'], $data['message'] );
                echo "</pass>";
            }
        }
        
        foreach( $this->_joomlaTests['unittests']['fail'] as $class => $value )
        {
            foreach( $value as $method => $data )
            {
                echo "<fail>";
                $this->printEntry( $class, $method, $data['filepath'], $data['message'] );
                echo "</fail>";
            }
        }
        
        foreach( $this->_joomlaTests['unittests']['exception'] as $class => $value )
        {
            foreach( $value as $method => $data )
            {
                echo "<exceptions>";
                $this->printEntry( $class, $method, $data['filepath'], $data['message'] );
                echo "</exceptions>";
            }
        }
        
        echo "<result>";
        echo "<total>".$this->_joomlaTests['unittests']['result']['total']."</total>";
        echo "<completed>".$this->_joomlaTests['unittests']['result']['completed']."</completed>";
        echo "<pass>".$this->_joomlaTests['unittests']['result']['pass']."</pass>";
        echo "<fail>".$this->_joomlaTests['unittests']['result']['fail']."</fail>";
        echo "<exceptions>".$this->_joomlaTests['unittests']['result']['exceptions']."</exceptions>";
        echo "</result>";
        
        echo "</unittests>";
    }
    
    function printEntry( $class, $method, $filepath, $message )
    {
        echo "<class>".$class."</class>";
        echo "<method>".$method."</method>";
        echo "<filepath>".$filepath."</filepath>";
        echo "<message>".htmlentities( $message, ENT_COMPAT, 'UTF-8' )."</message>";
    }
}
?>