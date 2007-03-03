<?php
/**
 * Joomla! v1.5 UnitTest Platform
 *
 * @version	$Id$
 * @package 	Joomla
 * @subpackage 	UnitTest
 */

require_once( JUNITTEST_VIEWS . '/JoomlaPhp.php' );

class JoomlaXml extends JoomlaPhp
{

	function JoomlaXml($character_set = 'UTF-8') {
		parent::__construct($character_set);
	}

    function paintHeader( $test_name )
    {
	    parent::paintHeader( $test_name );
        echo '<?xml version="1.0" encoding="UTF-8"?>', PHP_EOL;
        echo "<unittests>", PHP_EOL;
    }

    function paintFooter( $test_name )
    {
	    parent::paintFooter( $test_name );
        echo "</unittests>", PHP_EOL;
        while (@ob_end_flush());
    }

    function joomlaOutput()
    {
        echo "<testcase>", $this->_joomlaTests['unittests']['testcase'], "</testcase>", PHP_EOL;

        foreach ( $this->status as $state) {
	        foreach( $this->_joomlaTests['unittests'][$state] as $class => $value )
	        {
	            foreach( $value as $method => $data )
	            {
	                echo "<{$state}>",
	                $this->printEntry( $class, $method, $data['filepath'], $data['message'] ),
	                     "</{$state}>", PHP_EOL;
	            }
	        }
        }

        echo "<result>". PHP_EOL;
        echo "<total>", $this->_joomlaTests['unittests']['result']['total'], "</total>", PHP_EOL;
        echo "<completed>", $this->_joomlaTests['unittests']['result']['completed'], "</completed>", PHP_EOL;
        foreach ( $this->status as $state) {
	        echo "<{$state}>", $this->_joomlaTests['unittests']['result'][$state], "</{$state}>", PHP_EOL;
        }
        echo "</result>", PHP_EOL;
    }

    function printEntry( $class, $method, $filepath, $message )
    {
        echo "<class>", $class, "</class>", PHP_EOL;
        echo "<method>", $method, "</method>", PHP_EOL;
        echo "<filepath>", $filepath, "</filepath>", PHP_EOL;
        echo "<message>", htmlentities( $message, ENT_COMPAT, 'UTF-8' ), "</message>", PHP_EOL;
    }

}
