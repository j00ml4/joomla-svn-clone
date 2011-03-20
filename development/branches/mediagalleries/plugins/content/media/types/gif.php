<?php 
class MediaTypegif extends MediaType{
	
	public function getMedia($media='', $width='', $height='', $params=array()){
    	
    	return "<img src='".$media."'". "style='".$width." ".$height."' >";
    }
    
	public function getThumb($media='', $width='', $height=''){
	
		return "<img src='".$media."'". "style='".$width." ".$height."' >";
    }

}

?>