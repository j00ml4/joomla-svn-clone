<?php

class MediaTypeflv extends MediaType {

    /**
     * Get the media Code
     * @return string $embed_html HTML code to embed the video
     */
    public function getMedia() {
        $params =$this->params;
        $video = &$this->media;
		
        return $this->html4Player($video, $params);
    }

    /**
     * Return the thumbnail
     * @return string $html
     */
    public function getThumb() {
        $this->thumb = 'http://i.ytimg.com/vi/' . $this->getV() . '/default.jpg';
        return parent::getThumb();
    }

       
    
	public function html4Player($video, $params){
		$document = &JFactory::getDocument();
		$document->addScript( 'media/mediagalleries/player/flowplayer-3.2.6.min.js' );
		$html ='<div
				href="'.$video.'" 
				style="display:block;'.$params['width'].';'.$params['height'].';" 
				id="flvplayer"></div>';
		$tag=	'<script language="JavaScript">
				flowplayer("player", "media/mediagalleries/player/sflowplayer-3.2.7.swf");
				</script>';

		return $html;
		
    }

}
