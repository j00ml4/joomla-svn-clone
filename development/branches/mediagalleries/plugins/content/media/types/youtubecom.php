<?php
class MediaTypeYoutubecom extends MediaType {

   /*
    public function getMedia() {
        $vparams[] = 'autoplay='.$autostart;
        $vparams[] = 'rel=' . $params['youtube_rel']; //, 'advanced');
        $vparams[] = 'loop=' . $params['youtube_loop']; //, 'advanced');
        //$vparams[] = 'enablejsapi='.$params['youtube_enablejsapi'];
        //$vparams[] = 'playerapiid='.$params['youtube_playerapiid'];
        $vparams[] = 'disablekb=' . $params['youtube_disablekb']; //, 'advanced');
        $vparams[] = 'egm=' . $params['youtube_egm']; //, 'advanced');
        $vparams[] = 'border=' . $params['youtube_border']; //, 'advanced');
        $vparams[] = 'color1=0x' . $params['youtube_color1']; //, 'advanced');
        $vparams[] = 'color2=0x' . $params['youtube_color2']; //, 'advanced');

        if (!$width) {
            $width = 'width: 425px;';
            $height = 'height: 344px;'; // Auto H
        }
        if (strpos($media, '/v/')) {// If yes, New way
            $media = substr(strstr($media, '/v/'), 3);
            $media = explode('/', $media);
            $media = $media[0];
        } else {// Else, Old way
            $media = substr(strstr($media, 'v='), 2);
            $media = explode('&', $media);
            $media = $media[0];
        }

        $player = 'http://www.youtube.com/v/' . $media . '&' . implode('&', $params);

        $params['a'] = '';
        $params['p'] = '';

        //Call the SWF's extension function
        return $this->html4Player($player, $params);
    }
    */
	public function getMedia($video='',  $params=array()) {
    	$width=&$params['width'];
    	$height=&$params['height'];
    	$a=&$params['autostart'];
   		if( !$width ){
			$width='width: 425px;';
			$height='height: 344px;';// Auto H		
		
			}
		if( strpos( $video, '/v/' ) ) {// If yes, New way
			$video = substr( strstr( $video, '/v/' ), 3 );
			$video = explode( '/', $video);
			$video = $video[0];
		}
		else{// Else, Old way		
			$video = substr( strstr( $video, 'v=' ), 2 ) ; 
			$video = explode( '&', $video);
			$video = $video[0];
		}	
		
		$player = 'http://www.youtube.com/v/'. $video .'&'. implode('&', $params);
	
		$a = '';
		$p = '';
		return $this->html4Player($player, $params);
		//return addMediaSWF( $player, $width, $height, $a, $p );
	}
}
