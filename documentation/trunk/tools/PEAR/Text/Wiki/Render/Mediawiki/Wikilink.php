<?php

class Text_Wiki_Render_Mediawiki_Wikilink extends Text_Wiki_Render {

    /**
    *
    * Renders a token into XHTML.
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
		if (strpos($options['page'], ':', 1) > 0) {
			$options['path'] = explode(':', $options['page']);
			$options['path'][0] = strtolower($options['path'][0]);
			if ($options['path'][0] == 'joomla.framework') {
				unset($options['path'][0]);
			}
			// Package name
			$options['path'][0] = ucwords($options['path'][0]);
		}

        if ($options['type'] == 'start') {
            $output = '[['.$options['page'].
            	(strlen($options['anchor']) ? '#'.$options['anchor'] : '').
                (strlen($options['text']) /*&& $options['page'] != $options['text']*/ ? '|' : '');
        } else {
            $output = ']]';
        }

        return $output;
    }
}
?>