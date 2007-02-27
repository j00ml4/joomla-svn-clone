<?php
require_once( 'JoomlaPhp.php' );
require_once( 'libs/JSON.php' );

class JoomlaJson extends JoomlaPhp
{
    function joomlaOutput()
    {
        $json = new Services_JSON();
        
        return $json->encode( $this->_joomlaTests );
    }
}
?>