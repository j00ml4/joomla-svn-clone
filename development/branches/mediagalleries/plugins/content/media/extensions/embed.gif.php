<?php 
function extension( $media, $params )
{
	
	return "<img src='".$media."'". "style='".$params['width']." ".$params['height']."' >";
}

?>