<?php 
class MediaTypemp3_old extends MediaType{
	
	public function getMedia($media='', $params=array()){
				
        return $this->html4Player($this->media, $this->params);
		$document = &JFactory::getDocument();
		
    	//	return "$text <div id='audio' style='display:block;width:800px;height:50px' href='$media'></div> ";
    	//return "<div id='audio' style='display:block;".$params['width'].";".$params['height']."' href='".$media."' ></div> $text" ;
    }

}

class MediaTypemp3 extends MediaType {

    /**
     * Get the media Code
     * @return string $embed_html HTML code to embed the video
     */
    public function getMedia() {
      
       
        return $this->html4Player($this->media, $this->params);
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
		$params['autostart'] = isset($params['autostart'])?'false':(boolean) $params['autostart'];
		
		$html ='<div
				href="'.$this->media.'" 
				style="display:block;'.$params['width'].';'.$params['height'].';" 
				id="mp3player"></div>';
		
		$tag='<script language="JavaScript">
				$f("mp3player", "media/mediagalleries/player/flowplayer-3.2.7.swf", {
				plugins: {
					controls: {
						fullscreen: true,
						height: 30,
						autoHide: false
					}
			},				
				clip: {					
					autoPlay: '.$params['autostart'].'
				}

				});
					</script>';

		return $html.$tag;
		
    }
}


?>