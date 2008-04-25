<?php

class Text_Wiki_Render_Mediawiki_Code extends Text_Wiki_Render {

    /**
    *
    * Renders a token into text matching the requested format.
    *
    * @access public
    *
    * @param array $options The "options" portion of the token (second
    * element).
    *
    * @return string The text rendered from the token options.
    *
    */

    function token($options)
    {
        $output = '<source lang="'.$options['attr']['type'].'">'
        	.PHP_EOL. $options['text']
        	.PHP_EOL. '</source>';

		return $output;
    }
}
