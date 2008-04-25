<?php
/**
 * possible helpers for DokuWiki -> Mediawiki migration
 *
 * @package 	Joomla-Wiki
 * @author 		Rene Serradeil <serradeil@webmechanic.biz>
 * @copyright 	Copyright (c)2007, media++|webmechanic.biz
 * @version 	0.0.1 $Id$
 * @filesource
 */


// two from DokuWiki original parser
function internallink($match, $state, $pos) {

    // Strip the opening and closing markup
    $link = preg_replace(array('/^\[\[/','/\]\]$/u'),'',$match);

    // Split title from URL
    $link = preg_split('/\|/u',$link,2);
    if ( !isset($link[1]) ) {
        $link[1] = NULL;
    } else if ( preg_match('/^\{\{[^\}]+\}\}$/',$link[1]) ) {
        // If the title is an image, convert it to an array containing the image details
        $link[1] = Doku_Handler_Parse_Media($link[1]);
    }
    $link[0] = trim($link[0]);

    //decide which kind of link it is

    if ( preg_match('/^[a-zA-Z\.]+>{1}.*$/u',$link[0]) ) {
    // Interwiki
        $interwiki = preg_split('/>/u',$link[0]);
        $this->_addCall(
            'interwikilink',
            array($link[0],$link[1],strtolower($interwiki[0]),$interwiki[1]),
            $pos
            );
    }elseif ( preg_match('/^\\\\\\\\[\w.:?\-;,]+?\\\\/u',$link[0]) ) {
    // Windows Share
        $this->_addCall(
            'windowssharelink',
            array($link[0],$link[1]),
            $pos
            );
    }elseif ( preg_match('#^([a-z0-9\-\.+]+?)://#i',$link[0]) ) {
    // external link (accepts all protocols)
        $this->_addCall(
                'externallink',
                array($link[0],$link[1]),
                $pos
                );
    }elseif ( preg_match('<'.PREG_PATTERN_VALID_EMAIL.'>',$link[0]) ) {
    // E-Mail (pattern above is defined in inc/mail.php)
        $this->_addCall(
            'emaillink',
            array($link[0],$link[1]),
            $pos
            );
    }elseif ( preg_match('!^#.+!',$link[0]) ){
    // local link
        $this->_addCall(
            'locallink',
            array(substr($link[0],1),$link[1]),
            $pos
            );
    }else{
    // internal link
        $this->_addCall(
            'internallink',
            array($link[0],$link[1]),
            $pos
            );
    }

    return true;
}

function Doku_Handler_Parse_Media($match) {

    // Strip the opening and closing markup
    $link = preg_replace(array('/^\{\{/','/\}\}$/u'),'',$match);

    // Split title from URL
    $link = preg_split('/\|/u',$link,2);


    // Check alignment
    $ralign = (bool)preg_match('/^ /',$link[0]);
    $lalign = (bool)preg_match('/ $/',$link[0]);

    // Logic = what's that ;)...
    if ( $lalign & $ralign ) {
        $align = 'center';
    } else if ( $ralign ) {
        $align = 'right';
    } else if ( $lalign ) {
        $align = 'left';
    } else {
        $align = NULL;
    }

    // The title...
    if ( !isset($link[1]) ) {
        $link[1] = NULL;
    }

    //remove aligning spaces
    $link[0] = trim($link[0]);

    //split into src and parameters (using the very last questionmark)
    $pos = strrpos($link[0], '?');
    if($pos !== false){
        $src   = substr($link[0],0,$pos);
        $param = substr($link[0],$pos+1);
    }else{
        $src   = $link[0];
        $param = '';
    }

    //parse width and height
    if(preg_match('#(\d+)(x(\d+))?#i',$param,$size)){
        ($size[1]) ? $w = $size[1] : $w = NULL;
        ($size[3]) ? $h = $size[3] : $h = NULL;
    } else {
        $w = NULL;
        $h = NULL;
    }

    //get linking command
    if(preg_match('/nolink/i',$param)){
        $linking = 'nolink';
    }else if(preg_match('/direct/i',$param)){
        $linking = 'direct';
    }else{
        $linking = 'details';
    }

    //get caching command
    if (preg_match('/(nocache|recache)/i',$param,$cachemode)){
        $cache = $cachemode[1];
    }else{
        $cache = 'cache';
    }

    // Check whether this is a local or remote image
    if ( preg_match('#^(https?|ftp)#i',$src) ) {
        $call = 'externalmedia';
    } else {
        $call = 'internalmedia';
    }

    $params = array(
        'type'=>$call,
        'src'=>$src,
        'title'=>$link[1],
        'align'=>$align,
        'width'=>$w,
        'height'=>$h,
        'cache'=>$cache,
        'linking'=>$linking,
    );

    return $params;
}


