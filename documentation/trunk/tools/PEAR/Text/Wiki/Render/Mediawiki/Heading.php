<?php

class Text_Wiki_Render_Mediawiki_Heading extends Text_Wiki_Render {
    function token($options)
    {

        return ($options['type'] == 'end' ? ' ' : PHP_EOL).
            str_pad('', $options['level'], '=').
            ($options['type'] == 'start' ? ' ' : PHP_EOL);
    }
}
?>