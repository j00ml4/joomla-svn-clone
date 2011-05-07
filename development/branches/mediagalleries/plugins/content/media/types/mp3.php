<?php 
class MediaTypemp3_old extends MediaType{
	
	public function getMedia($media='', $params=array()){
		$document = &JFactory::getDocument();
		$document->addScript( 'media/mediagalleries/player/flowplayer-3.2.6.min.js' );
		//fix autostart
		$params['autostart'] = isset($params['autostart'])?'false':(boolean) $params['autostart'];
		
    	$text='<script language="JavaScript">
				$f("audio", "http://releases.flowplayer.org/swf/flowplayer-3.2.7.swf", {

				// fullscreen button not needed here
				plugins: {
					controls: {
						fullscreen: false,
						height: 30,
						autoHide: false
					}
				},

				clip: {
					autoPlay : false
					//autoPlay: '.$params['autostart'].'
				}

				});
					</script>';
    	//echo $text;
    	
    	return "$text <div id='audio' style='display:block;width:800px;height:50px' href='$media'></div> ";
    	//return "<div id='audio' style='display:block;".$params['width'].";".$params['height']."' href='".$media."' ></div> $text" ;
    }

}

class MediaTypemp3 extends MediaType {

    /**
     * Get the media Code
     * @return string $embed_html HTML code to embed the video
     */
    public function getMedia() {
        /*
    	$params = $this->params;
        $media = $this->media;
        //Add the player js
		
		//fix autostart
		var_dump($this->params);
		$this->params['autostart'] = isset($this->params['autostart'])?'false':(boolean) $this->params['autostart'];
		*/
    	$document = &JFactory::getDocument();
		$document->addScript( 'media/mediagalleries/player/flowplayer-3.2.6.min.js' );
    	$text='<script language="JavaScript">
				$f("audio", "http://releases.flowplayer.org/swf/flowplayer-3.2.7.swf", {

				// fullscreen button not needed here
				plugins: {
					controls: {
						fullscreen: false,
						height: 30,
						autoHide: false
					}
				},

				clip: {
					autoPlay: '.$this->params['autostart'].'
				}

				});
					</script>';
    	//echo $text;
    	
    	return "$text <div id='audio' style='display:block;width:800px;height:50px' href='http://www.desiweb.net/mp3/Bolly/Ready_(2011)/DesiWEB.net_(Character_Dheela).mp3'></div> ";

        // Return the embed code
       
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

}


?>