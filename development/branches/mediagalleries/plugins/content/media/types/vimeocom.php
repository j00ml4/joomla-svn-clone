<?php

class MediaTypevimeocom extends MediaType {

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
    	$tag = '<iframe src='.$this->getSource().' style="' .$params['width'].$params['height'].';"></iframe>';
    			
    			
    	return $tag;
    }
    
    private function getSource(){
    	$temp=explode("/",$this->media);
    	$videoId=end($temp);
    	return "http://player.vimeo.com/video/".$videoId;
    }
}
