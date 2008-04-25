<?php

class Text_Wiki_Render_Mediawiki_Interwiki extends Text_Wiki_Render {

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
    	settype($options['site'], 'array');
    	if (count($options['site']) == 1) {
    		if ($options['site'][0] == '.') {
	    		$options['site'] = '';
    		} else if ($options['site'][0] == 'reference') {
	    		$options['site'] = 'Framework:';
    		} else {
	    		$options['site'] = ucwords($options['site'][0]);
    		}
    		if (!empty($options['site'])) {
    			$options['site'] = 'Framework:Packages/'.$options['site'] .'/';
    		}
    	} elseif (count($options['site']) > 1) {
	    	$options['site'] = 'Framework:'.implode('/', @$options['site']);
    	}

    	$options['page'] = str_replace('-', '/', $options['page']);

        $result = '[['.$options['site'].''.$options['page'].
        		(strlen($options['text']) ? '|'.$options['text'] : '')
        		.']]';

		return $result;
    }
}
?>