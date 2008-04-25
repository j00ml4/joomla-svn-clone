<?php
/**
*
* Parses for DISCUSSION tag (Plugin).
*
* @category Text
* @package Text_Wiki
*/

class Text_Wiki_Parse_Discussion extends Text_Wiki_Parse {

    /**
    * The regular expression used to parse the source text and find
    * matches conforming to this rule.  Used by the parse() method.
    *
    * @access public
    * @var string
    * @see parse()
    */

    var $regex = "/~~DISCUSSION~~/";


    /**
    * Generates a replacement for the matched text.  Token options are:
    *
    * 'type' => ['start'|'end'] The starting or ending point of the
    * emphasized text.  The text itself is left in the source.
    *
    * @access public
    * @param array &$matches The array of matches from parse().
    * @return string A pair of delimited tokens to be used as a
    * placeholder in the source text surrounding the text to be
    * emphasized.
    *
    */

    function process(&$matches)
    {
        return $this->wiki->addToken(
            $this->rule,
            array(
                'type' => 'plugin',
                'text' => 'DISCUSSION',
                'delim' => '~~',
            )
        );
    }
}

