<?php

/**
*
* Parses for interwiki links.
*
* @category Text
*
* @package Text_Wiki
*
* @author Paul M. Jones <pmjones@php.net>
*
* @license LGPL
*
* @version $Id: Interwiki.php,v 1.1 2005/07/21 20:56:13 justinpatrin Exp $
*
*/

/**
*
* Parses for interwiki links.
*
* This class implements a Text_Wiki_Parse to find source text marked as
* an Interwiki link.  See the regex for a detailed explanation of the
* text matching procedure; e.g., "InterWikiName:PageName".
*
* @category Text
*
* @package Text_Wiki
*
* @author Paul M. Jones <pmjones@php.net>
*
*/

class Text_Wiki_Parse_Interwiki extends Text_Wiki_Parse {

    // double-colons wont trip up now
    var $regex = ''; // '([A-Za-z0-9_\s]+)>((?!>)[A-Za-z0-9_\/=&~#.:;-\s]+)';


    /**
    *
    * Parser.  We override the standard parser so we can
    * find both described interwiki links and standalone links.
    *
    * @access public
    *
    * @return void
    *
    */

    function parse()
    {
        // described interwiki links
//        $tmp_regex = '/\[\[' . $this->regex . '(\|(.+?))?\]\]/';
        $tmp_regex = '/\[\[(.+?)\]\]/';
        $this->wiki->source = preg_replace_callback(
            $tmp_regex,
            array(&$this, 'processDescr'),
            $this->wiki->source
        );
    }


    /**
    *
    * Generates a replacement for described interwiki links. Token
    * options are:
    *
    * 'site' => The key name for the Text_Wiki interwiki array map,
    * usually the name of the interwiki site.
    *
    * 'page' => The page on the target interwiki to link to.
    *
    * 'text' => The text to display as the link.
    *
    * @access public
    *
    * @param array &$matches The array of matches from parse().
    *
    * @return A delimited token to be used as a placeholder in
    * the source text, plus any text priot to the match.
    *
    */

    function processDescr(&$matches)
    {

		$site = $page = $text = '';

		$m = explode('|', $matches[1]);

		if (count($m) == 1) {
			if (strpos($m[0], ':') > 1) {
				$m = explode(':', $m[0]);
				$text = array_pop($m);
				$page = $text;
				$site = $m;
			} else {
				$page = $text = $m[0];
				$site = array('.');
			}
		}
		if (count($m) == 2) {
			$page = $m[0];
			$text = $m[1];
		}

        $options = array(
            'site' => $site,
            'page' => $page,
            'text' => $text
        );

        return $this->wiki->addToken($this->rule, $options);
    }
}
?>