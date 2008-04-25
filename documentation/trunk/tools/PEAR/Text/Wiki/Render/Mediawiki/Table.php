<?php

class Text_Wiki_Render_Mediawiki_Table extends Text_Wiki_Render {

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

        switch ($options['type']) {

        case 'table_start':
            return PHP_EOL.'{| class="wikitable"' .PHP_EOL;
            break;

        case 'table_end':
            return '|}' .PHP_EOL;
            break;

        case 'row_start':
            return '|-'.PHP_EOL;
            break;

        case 'row_end':
            return PHP_EOL;
            break;

        case 'cell_start':
            // is this a TH or TD cell?
            if ($options['attr'] == 'header') {
                // start a header cell
                $output = '! ';
            } else {
                // start a normal cell
                $output = '| ';
            }

            // add alignment
            switch($options['attr']) {
            case 'left':
                break;
            case 'right':
            case 'center':
                $output .= ' align='.$options['attr'];
                break;
            }

            return $output;
            break;

        case 'cell_end':
            $output = PHP_EOL;
            return $output;
            break;

        default:
            return '';

        }
    }
}
?>