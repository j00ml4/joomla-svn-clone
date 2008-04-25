<?php

/**
*
* Parses for text marked as a code example block.
*
* @category Text
*
* @package Text_Wiki
*
* @author Paul M. Jones <pmjones@php.net>
*
* @license LGPL
*
* @version $Id: Code.php,v 1.1 2005/07/21 20:56:13 justinpatrin Exp $
*
*/

/**
*
* Parses for text marked as a code example block.
*
* This class implements a Text_Wiki_Parse to find sections marked as code
* examples.  Blocks are marked as the string <code> on a line by itself,
* followed by the inline code example, and terminated with the string
* </code> on a line by itself.  The code example is run through the
* native PHP highlight_string() function to colorize it, then surrounded
* with <pre>...</pre> tags when rendered as XHTML.
*
* @category Text
*
* @package Text_Wiki
*
* @author Paul M. Jones <pmjones@php.net>
*
*/

class Text_Wiki_Parse_Code extends Text_Wiki_Parse {


    /**
    *
    * The regular expression used to find source text matching this
    * rule.
    *
    * @access public
    *
    * @var string
    *
    */
	var $regex = '/^(<code( (.+))?>)\n(.+)\n(<\/code>)$/Umsi';
/*
	var $regex = '/^(<code[^\r\n\<]*?>)\n(.+)?\n(<\/code>)/msi';
*/

/*
'<code(?=[^\r\n]*?>.*?\x3C/code>)'
'</code>'
// will include everything from <code ... to ... </code >
// e.g. ... [lang] [|title] > [content]
list($attr, $content) = preg_split('/>/u',$match,2);
list($lang, $title) = preg_split('/\|/u',$attr,2);
*/

    /**
    *
    * Generates a token entry for the matched text.  Token options are:
    *
    * 'text' => The full matched text, not including the <code></code> tags.
    *
    * @access public
    *
    * @param array &$matches The array of matches from parse().
    *
    * @return A delimited token number to be used as a placeholder in
    * the source text.
    *
    */

    function process(&$matches)
    {
        // are there additional attribute arguments?
        $args = trim($matches[2]);
        if ($args == '') {
            $options = array(
                'text' => $matches[4],
                'attr' => array('type' => '')
            );
        } else {
        	// get the attributes...
        	if (strpos($args, '|') !== false) {
        		list($type, $title) = explode('|', $args);
	        	$attr['type'] = $type;
	        	$attr['title'] = str_replace('>', '', $title);
        	} else {
	        	$attr['type'] = $args;
        	}
        	// retain the options
            $options = array(
                'text' => $matches[4],
                'attr' => $attr
            );
        }

        return $this->wiki->addToken($this->rule, $options);
    }
}
?>