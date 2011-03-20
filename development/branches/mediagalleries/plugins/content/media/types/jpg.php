<?php 
class MediaTypejpg extends MediaType{
	
	public function getMedia($media='', $width='', $height='', $params=array()){
    	
    	return "<img src='".$media."'". "style='".$width." ".$height."' >";
    }
    
	public function getThumb($media='', $width='', $height=''){
		return "here";
		return "<img src='".$media."'". "style='".$width." ".$height."' >";
    }

}

?>