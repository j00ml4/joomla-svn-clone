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
		if (! headers_sent()) {
			header('Accept-Charset: '.$this->_character_set, true);
			header('Content-Type: text/xml; charset='.$this->_character_set, true);
			header('Content-Encoding: '.$this->_character_set, true);
			header('Encoding: '.$this->_character_set, true);
		}
	}

    function paintHeader( $test_name )
    {
	    parent::paintHeader( $test_name );
        echo '<?xml version="1.0" encoding="', $this->_character_set ,'"?>', PHP_EOL;
        echo '<unittests>', PHP_EOL;
    }

    function paintFooter( $test_name )
    {
	    parent::paintFooter( $test_name );
        echo '</unittests>', PHP_EOL;
        while (@ob_end_flush());
    }

    function joomlaOutput()
    {
		echo '<testcase>', $this->_joomlaTests['unittests']['testcase'], '</testcase>', PHP_EOL;

		foreach ( $this->status as $state) {
			foreach( $this->_joomlaTests['unittests'][$state] as $class => $value ) {
				foreach( $value as $method => $data ) {
					echo "<{$state}>"
						, $this->printEntry( $class, $method, $data['filepath'], $data['message'] )
						, "</{$state}>", PHP_EOL;
				}
			}
		}

		echo '<result>'. PHP_EOL
			, '<total>', $this->_joomlaTests['unittests']['result']['total'], '</total>', PHP_EOL
			, '<completed>', $this->_joomlaTests['unittests']['result']['completed'], '</completed>', PHP_EOL;
		foreach ( $this->status as $state) {
		    echo "<{$state}>"
		    	, $this->_joomlaTests['unittests']['result'][$state]
		    	, "</{$state}>"
		    	, PHP_EOL;
		}
		echo '</result>', PHP_EOL;
    }

    function printEntry( $class, $method, $filepath, $message )
    {
		echo '<class>', $class, "</class>", PHP_EOL
			, '<method>', $method, "</method>", PHP_EOL
			, '<filepath>', $filepath, "</filepath>", PHP_EOL
			, '<message><![CDATA['
			, htmlentities( $message, ENT_COMPAT, $this->_character_set )
			, ']]></message>'
			, PHP_EOL;
    }

}

