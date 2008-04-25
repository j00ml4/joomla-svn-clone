<?php

// stub, as this requires a complex plugin which generates a FORM
// and JS and much more ;)
class Text_Wiki_Render_Doku_Discussion extends Text_Wiki_Render {

    /**
    * Renders a token into text matching the requested format.
    *
    * @access public
    * @param array $options The "options" portion of the token (second
    * element).
    * @return string The text rendered from the token options.
    */

    function token($options)
    {
            return '<html><span>~~DISCUSSION~~</span></html>';
    }
}

