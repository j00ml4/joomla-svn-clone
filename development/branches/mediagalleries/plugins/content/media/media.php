<?php
/**
 * @version		$Id: example.php 16807 2010-05-05 05:36:11Z eddieajau $
 * @package		Joomla
 * @subpackage	Content
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;


jimport('joomla.plugin.plugin');

// This lib adds the media 
include_once dirname(__FILE__) .DS.'media'.DS.'htmlembed.php';

/**
 * Media Content Plugin
 *
 * @package		Joomla
 * @subpackage	Content
 * @since		1.6
 */
 
class plgContentMedia extends JPlugin
{
	
		
	/**
	 * Example prepare content method
	 *
	 * Method is called by the view
	 *
	 * @param	string	The context of the content being passed to the plugin.
	 * @param	object	The content objec	
	 * @param	object	The content params
	 * @param	int		The 'page' number
	 * @since	1.6
	 */
	public function onContentPrepare($context, &$article, &$params, $limitstart)
	{
		$app = JFactory::getApplication();
		echo $this->get('width');
		// make it better 
		$row =& $article;
			
		// Regular Expression
		$regex = '/\{media(.*?)}/i';
		$total = preg_match_all( $regex, $row->text, $matches );
		if ( !$total ){
			return false;
		}
		
		// PARAMs
		$plgParams =& getDenVideoParams(); // no needed
		
		
		// Default	
		$w = (int)$params->get('width', 400);
		$h = (int)$params->get('height', 0 );
		$ast = (int)$params->get('autostart', 0 );
		
		// Loop
		for( $x=0; $x < $total; $x++ ){
			// General Params		
			$parts = preg_replace('/\<.*?\>/', '', trim($matches[1][$x])); // Dumies Prof
			$parts = explode( ' ', $parts); // Split	
	
			// Default Vaues
			$width = $w;
			$height = ($h > 0)? $h : ($width * 0.7);
			$autostart = $ast;
			
			// Params
			$pcount = count($parts);
			
			/**Width*/
			if($pcount > 1){
				($parts[1] > 0) // if true 
					&& $width = (int)$parts[1];
				$height = $width * 0.7;
				
				/**Height*/			
				if($pcount > 2){
					($parts[2] > 0) // if true 
						&& ($height = (int)$parts[2]);
					($parts[1] > 0) // if false
						|| $width = $height * 1.3;
									
					/**autoStart*/				
					if($pcount > 3){
						$autostart = (boolean)$parts[3];						
					}
				}
			}
			
			// Video Display
			$video = $parts[0];
	
			// Put Video inside the content
			$replace = self::addMedia( $video, $width, $height, $autostart );
			$replace = '<span id="denvideo_'. $x .'" class="denvideo" style="position:relative">'
				. $replace
			. '</span>';
			$row->text = str_replace( $matches[0][$x], $replace, $row->text );
		}
		return true;
	}	
	

	/** The most important function! 
	 * Show denVideo
	 * @return str
	 * @param string $video Media URL // Rename to media
	 * @param int $width [optional]
	 * @param int $width [optional]
	 * @param boolean $autoplay True if yes [optional]
	 */
	public function addMedia( $video, $width=0, $height =0, $autostart=0 )
	{	
		
		
		// The propose of this is to get the defaults set by the admin 
		$pparams =$this; // make it work
		
		// Fix Video UrL
		$video = preg_match("http:'/'/", $video)? 
			$video: // Custom PATH
			$pparams->get('uri_img').$video; // Default PATH
		
		// Size Style
		if( $width ){			
			$height = ($height)?
				'height:'. $height .'px;': // Manual H
					'height:'. $width .'px;';// Auto H		
							
			$width = 'width:'. $width .'px;';
		}elseif( $height ){		
			$height = 'height:'. $height .'px;';
			$width = 'width:'. $height .'px;';
		}else{ 
			$height=''; 
			$width ='';
		}
		
		// AutoStart
		$autostart = (boolean)$autostart;
		
		
		/* ***************************************************
		 * The Show Begins
		 *****************************************************/	
		 
		/* YouTube Video 
		*****************************************************/
		//preg_match('@^(?:youtube.com)?([^/]+)@i',$video);
		if ( @(preg_match('/youtube\.com/i', $video)) ){
			$vparams = array();
			$vparams[] = 'autoplay='.$autostart;
			$vparams[] = 'rel='.$pparams->get('youtube_rel');//, 'advanced');
			$vparams[] = 'loop='.$pparams->get('youtube_loop');//, 'advanced');
			$vparams[] = 'enablejsapi='.$pparams->get('youtube_enablejsapi', '', 'advanced');
			$vparams[] = 'playerapiid='.$pparams->get('youtube_playerapiid', 'advanced');
			$vparams[] = 'disablekb='.$pparams->get('youtube_disablekb');//, 'advanced');
			$vparams[] = 'egm='.$pparams->get('youtube_egm');//, 'advanced');
			$vparams[] = 'border='.$pparams->get('youtube_border');//, 'advanced');
			$vparams[] = 'color1=0x'.$pparams->get('youtube_color1');//, 'advanced');
			$vparams[] = 'color2=0x'.$pparams->get('youtube_color2');//, 'advanced');
	
			$replace = addVideoYoutube($video, $width, $height, $vparams );
		}
		
		/* Yahoo Video 
		*****************************************************/
		elseif( eregi('video.yahoo', $video) ){	
			
			$replace = addVideoYahoo($video, $width, $height, $autostart );
		}
		
		/* Google Video 
		*****************************************************/
		elseif( eregi('video.google',$video) ){
			
			$replace = addVideoGoogle($video, $width, $height, $autostart);
			
		}
		
		/* Brightcove Video 
		*****************************************************/
		elseif( eregi('brightcove.tv', $video) ){
	
			$replace = addVideoBrightcove( $video, $width, $height, $autostart );
		}
	
		/* Metacafe.com
		*****************************************************/
		elseif( eregi('metacafe.com', $video) ){
	
			$replace = addVideoMetacafe($video, $width, $height, $autostart );
		}
		
		/* Tangle.com
		*****************************************************/
		elseif( eregi('tangle.com', $video) ){
	
			$replace = addVideoTangle($video, $width, $height, $autostart );
		}
		
		/* Megavideo.com
		*****************************************************/
		elseif( eregi('megavideo.com', $video) ){
	
			$replace = addVideoMegavideo($video, $width, $height, $autostart );
		}
		
		/* Video from files
		******************************************************/ 
		else{			
			$type = substr( $video, strrpos($video, '.') );			
			$type = strtolower($type);
			switch( $type ){
				
			/* Flash .SWF 
			*****************************************************/
			case '.swf':		    
				$replace = addMediaSWF($video, $width, $height);
				break;				
			
			/* Music .MP3
			*****************************************************/
			case '.mp3': 	
				$vparams = array();
				$vparams['path_player'] = $pparams->get('uri_plg');
				switch( $pparams->get('mp3_player') ){
					case 'jwplayer':// Play with JWPLAYER
						$vparams['flashvars'][] = 'autostart='. ( ($autostart)? 'true': 'false' );
						$vparams['flashvars'][] = 'showeq=true';
						$vparams['flashvars'][] = 'showstop=true';
						$vparams['flashvars'][] = 'fullscreen=false';
						$vparams['flashvars'][] = 'quality='. ( $pparams->get('jw_quality')? 'true': 'false' );
						$vparams['flashvars'][] = 'backcolor=0x'. $pparams->get('jw_backcolor');//, 'advanced');

						$vparams['flashvars'][] = 'frontcolor=0x'. $pparams->get('jw_frontcolor');//, 'advanced');
						$vparams['flashvars'][] = 'lightcolor=0x'. $pparams->get('jw_lightcolor');//, 'advanced');
						$vparams['flashvars'][] = 'screencolor=0x'. $pparams->get('jw_screencolor');//, 'advanced');
						if( $pparams->get('jw_logo') )	{		
							$vparams['flashvars'][] = 'logo='. JURI::base().'images/'.$pparams->get('jw_logo', 'advanced');
						}
								
						$height = 'height:100px;';
						//$width = 'width: 290px;';		
						$replace = addVideoJWPlayer($video, $width, $height, $vparams);
						break;
					
					case '1pixelout':// Play with 1PIXELOUT
					default:
						$vparams['autostart'] = ($autostart)? 'yes': 'no';
						$height = 'height:24px;';
						//$width = 'width: 290px;';	
						$replace = addMusic1Pixelout($video, $width, $height, $vparams);
						break;
						
				}			
				break;
			
			/* JPG, GIF, PNG
			*****************************************************/
			case '.jpg':
			case '.gif':
			case '.png':
				$replace = addPicture($video, $width, $height);
			 	break;
				
			/* JPG, GIF, PNG, H264
			*****************************************************/
			case '.h264':
				$vparams = array();
				$vparams['path_player'] = $pparams->get('uri_plg');
				// JW PLAYER
				$vparams['flashvars'][] = 'autostart='. ( ($autostart)? 'true': 'false' );
				$vparams['flashvars'][] = 'showstop=true';
				$vparams['flashvars'][] = 'stretching=fill';
				$vparams['flashvars'][] = 'fullscreen=true';
				$vparams['flashvars'][] = 'quality='. ( $pparams->get('jw_quality')? 'true': 'false' );
				$vparams['flashvars'][] = 'backcolor=0x'. $pparams->get('jw_backcolor');//, 'advanced');
				$vparams['flashvars'][] = 'frontcolor=0x'. $pparams->get('jw_frontcolor');//, 'advanced');
				$vparams['flashvars'][] = 'lightcolor=0x'. $pparams->get('jw_lightcolor');//, 'advanced');
				$vparams['flashvars'][] = 'screencolor=0x'. $pparams->get('jw_screencolor');//, 'advanced');
				if( $pparams->get('jw_logo', '') )	{		
					$vparams['flashvars'][] = 'logo='. JURI::base().'images/'.$pparams->get('jw_logo', 'advanced');
				}
				
				$replace = addVideoJWPlayer($video, $width, $height, $vparams);
				break;
				
			/* Video .FLV
			*****************************************************/
			case '.flv': 	
				$vparams = array();
				$vparams['path_player'] = $pparams->get('uri_plg');
				switch( $pparams->get('flv_player') ){
					case 'jwplayer':// Play with JWPLAYER
						$vparams['flashvars'][] = 'autostart='. ( ($autostart)? 'true': 'false' );
						$vparams['flashvars'][] = 'showstop=true';
						$vparams['flashvars'][] = 'stretching=fill';
						$vparams['flashvars'][] = 'fullscreen=true';
						$vparams['flashvars'][] = 'quality='. ( $pparams->get('jw_quality')? 'true': 'false' );
						$vparams['flashvars'][] = 'backcolor=0x'. $pparams->get('jw_backcolor');//, 'advanced');
						$vparams['flashvars'][] = 'frontcolor=0x'. $pparams->get('jw_frontcolor');//, 'advanced');
						$vparams['flashvars'][] = 'lightcolor=0x'.$pparams->get('jw_lightcolor');//, 'advanced');
						$vparams['flashvars'][] = 'screencolor=0x'. $pparams->get('jw_screencolor');//, 'advanced');
						if( $pparams->get('jw_logo', '') )	{		
							$vparams['flashvars'][] = 'logo='. JURI::base().'images/'.$pparams->get('jw_logo', 'advanced');
						}
						
						$replace = addVideoJWPlayer($video, $width, $height, $vparams);
						break;
						
					case '2kplayer':// Play with simple FLVPlayer
					default:
						$vparams['autostart'] = ($autostart)? '1': '0';
						
						$replace = addVideo2KPlayer($video, $width, $height, $vparams);
						break;
				}			
				break;				
	
						
			/* Quicktime MOV,  MP4
			************************************************/
			case '.mov':
			case '.3gp':		
			case '.mp4':
				$replace = addVideoQuicktime($video, $width, $height, $autostart);
				break;
				
			/* Realmedia .RM & .RAM
			*****************************************************/
			case '.rm':
			case '.rmvb':
			case '.ram': 	
				$replace = addVideoRealmedia($video, $width, $height, $autostart);
				break;
				
			/* Applet .CLASS
			*****************************************************/
			case '.class':				
				$replace = addAppletJava( $video, $width, $height);
				break;
			
			/* DivX
			*****************************************************/
			case '.div':
			case '.avi':
			case '.divx':
				$replace = addVideoDivx($video, $width, $height,  $autostart);
				break;
			
			/* Windows Media
			*****************************************************/
			case '.asx':
			case '.wma':
			case '.wmv':
			case '.mpg':
			case '.mpeg':
				$replace =addVideoWindows($video, $width, $height,  $autostart);
				break;
	
			/* Error
			*****************************************************/
			default: 
				$replace = addVideoError($video, 'Invalid Video');
				break;
			}
		}
			
		// Return video
		return $replace;
	}	
	
	/**
	 * In this needed?
	 *
	 * @param $params
	 */
	protected function _getParams(){
		// PARAMs
		$plugin =& JPluginHelper::getPlugin('content', 'denvideo');
		$plgParams = new JParameter( $plugin->params );
		
		// Path to plugin folder
		$plgParams->set('dir_plg', 
			 JPATH_PLUGINS.DS.'content'.DS.'denvideo' .DS );			
		$plgParams->set( 'uri_plg', 
			JURI::base().'plugins/content/denvideo/' );
	
		// Path to default videos folder	
		$defdir = $plgParams->get('defaultdir', 'images/stories');
		if(!eregi('http://', $defdir)){
			$defdir = JURI::base().$defdir;
			$plgParams->get('defaultdir', $defdir);
		}	
		$plgParams->set('uri_img', $defdir);
	
		return $plgParams;
	}
}



// this goes to the onContentPreparplgContentMediae
function plgContentDenVideo( &$row, &$params, $page=0 ){		
	
}

/** 
 * Get denVideo params object
 * 
 * @return JParameter
 */
function & getDenVideoParams(){
	return $this;
}

/** do we need this?
 * Get one single param:defined by the $key
 *
 * 'uri_plg' =  Path to denvideo plugin folder
 * 'path_img' = Path to denvideo default video folder * 
 * 'width' = default widht value
 * 'height' = default height value
 * 'autostart' = defalt autostart value (true/false)
 * 
 * @return String
 * @param $key String
 * @param $default String[optional]
 * @param $group String[optional]
 */
function getDenVideoParam($key, $default='', $group = '_default'){
	$plgParams =& getDenVideoParams();
	return $plgParams->get( $key, $default, $group = '_default' );
}

function ShowDenVideo($video, $width, $height, $autostart)
	{}
?>