<?php

/**
*
* Parse for links to wiki pages.
*
* @category Text
*
* @package Text_Wiki
*
* @author Paul M. Jones <pmjones@php.net>
*
* @license LGPL
*
* @version $Id: Wikilink.php,v 1.1 2005/07/21 20:56:14 justinpatrin Exp $
*
*/

/**
*
* Parse for links to wiki pages.
*
* Wiki page names are typically in StudlyCapsStyle made of
* WordsSmashedTogether.
*
* You can also create described links to pages in this style:
* [WikiPageName nice text link to use for display]
*
* The token options for this rule are:
*
* 'page' => the wiki page name.
*
* 'text' => the displayed link text.
*
* 'anchor' => a named anchor on the target wiki page.
*
* @category Text
*
* @package Text_Wiki
*
* @author Paul M. Jones <pmjones@php.net>
*
*/

class Text_Wiki_Parse_Wikilink extends Text_Wiki_Parse {

    var $conf = array (
    	'ext_chars' => false
    );


    /**
    *
    * First parses for described links, then for standalone links.
    *
    * @access public
    *
    * @return void
    *
    */

    function parse()
    {
        if ($this->getConf('ext_chars')) {
			$either = "A-Za-z0-9\xc0-\xfe";
		} else {
			$either = "A-Za-z0-9";
		}

        // described wiki links
        $tmp_regex = '/\[\[(['.$either.'\s:\.]*?)'. //beginning and page name
            '(\#['.$either.'\s:\.]+?)?'. //anchor
            '(\|([^'.$this->wiki->delim.'\]]+?))?\]\]/'; //description
        $this->wiki->source = preg_replace_callback(
            $tmp_regex,
            array(&$this, 'processDescr'),
            $this->wiki->source
        );
    }


    /**
    *
    * Generate a replacement for described links.
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

        // set the options
        $options = array(
            'page'   => $matches[1],
            'text'   => @strlen($matches[4]) ? $matches[4] : $matches[1],
            'anchor' => @$matches[2]
        );
        if ($options['text'] == $options['page']) {
            $options['text'] = '';
        }

        // create and return the replacement token and preceding text
        return $this->wiki->addToken($this->rule,
                                     array_merge(array('type' => 'start'), $options)).
            $options['text'].
            $this->wiki->addToken($this->rule,
                                  array_merge(array('type' => 'end'), $options));

    }
}
?>