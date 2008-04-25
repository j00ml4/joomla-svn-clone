<?php
class Text_Wiki_Render_Mediawiki_Image extends Text_Wiki_Render {

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

        if (!isset($options['attr']['align'])) {
            $options['attr']['align'] = '';
        }
        if (@$options['attr']['width'] == 'nolink') {
            unset($options['attr']['width']);
        }

        $options['src'] = str_replace(array('wiki:','references:'), '', $options['src']);
		$caption = pathinfo($options['src']);
		$caption = basename($caption['basename'], '.'.$caption['extension']);

        $img = '[[Image:' . $options['src']
        	. (isset($options['attr']['width']) ? '|'.(int)$options['attr']['width'].'px' : '')
        	. ($options['attr']['align'] ? '|'.$options['attr']['align'] : '')
        	. '|'.$caption
            . ']]';
        return $img;
    }
}

