<?php

class Text_Wiki_Render_Mediawiki_Japi extends Text_Wiki_Render {
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
		$WT = &WikiTransformer::getInstance($this->wiki);
		$API = $this->wiki->getRenderConf('Mediawiki', 'Japi', 'API');

		$catjoomla = $WT->wikiHeader($options);
        return $catjoomla;
    }
}
