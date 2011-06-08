<?php
class MediaTypeYoutubecom extends MediaType {

    /**
     * Get the media Code
     * @return string $embed_html HTML code to embed the video
     */
    public function getMedia() {
        $params = $this->params;
        $video = $this->media;

        
        // Return the embed code
        $player = 'http://www.youtube.com/v/' . $this->getV() . '&' . implode('&', $vparams);
        return $this->html4Player($player, $params);
    }

    /**
     * Return the thumbnail
     * @return string $html
     */
    public function getThumb() {
        $this->thumb = 'http://i.ytimg.com/vi/' . $this->getV() . '/default.jpg';
        return parent::getThumb();
    }

    /**
     * Extract the video id from the media url
     * this is a helper method used by both the media and thumb
     * @staticvar type $v
     * @return string $v 
     */
    private function getV() {
        static $v;
        if ($v) {
            return $v;
        }
        
        $media = $this->media;
        if (strpos($media, '/v/')) {// If yes, New way
            $video = substr(strstr($media, '/v/'), 3);
            $video = explode('/', $video);
            $video = $video[0];
        } else {
            $video = substr(strstr($media, 'v='), 2); // Else, Old way	
            $video = explode('&', $video);
            $video = $video[0];
        }
        
        return $video;
    }

}

function addVideoMegavideo($video, $width='', $height='',  $autostart='0' ) {	
	$video = substr( stristr( $video, 'v=' ), 2 );		
	$player = 'http://wwwstatic.megavideo.com/mv_player.swf?v='.$video;
	$vars = 'v='. $video .'&autoplay='. ($autostart? 1: 0);
	
	
	$a = '';
	$p = '<param name="allowFullScreen" value="true" />
		<param name="FlashVars" value="'. $vars .'" />
		<param name="allowScriptAccess" value="always" />';

	$replace = 	addMediaSWF( $player, $width, $height, $a, $p );
	return $replace;
}
