<?php
/*
 * File:        example.php
 *
 * Note the following:
 * File writing is only possible if cache is enabled. This is due to GD functions not returning image data when called
 * However, you are still able to "retrieve" the data of a generated thumbnail by using Output buffer.
 * 
 * 
 * Usage Instructions:
 * 
 * All parameters used by this script is set by GET variables.
 * To begin, access the script with a "mode" parameter. The "image" parameter is used to specify the image file.
 * If you want to output the thumbnail to a file, use the "to_file" parameter to set the output target and "overwrite" to true if you
 * want overwirting to occur.
 * 
 * In addition, each mode has it's own specific settings (they are self explanatory):
 * 
 *  -   "thumbnail" - "width", "height"
 *  -   "scale" - "magnitude"
 *  -   "fit" - "width", "height"
 *  
 * Examples:
 *  - example.php?mode=scale&image=example.jpg&magnitude=0.5&to_file=test.jpg
 *  - example.php?mode=fit&image=example.jpg&width=500&height=500&to_file=test.jpg&overwrite=true
*/
//Require Class
require_once('PThumb.php');

//Search for class_HTTPRetriever.php and include as neccessary
require_once('HTTPRetriever/class_HTTPRetriever.php');


error_reporting (E_ALL);

//Child Class for configuration
class pthumb_example extends PThumb{
    //Use Cache?
	var $use_cache = true;
    //Cache Dir. MUST be writable
	var $cache_dir = 'cache/';		//Make sure to include trailing slash!
    var $remote_check = false; 
	 //Error mode. Set to 2 to show a nicer error than the user-intruiguing error
	var $error_mode = 2;
	
	function pthumb_example(){
		$this -> PThumb();
	}
    //Custom method to display an 'X' in case of any errors
    function display_x(){
	    header('Content-type: image/png');
        echo base64_decode('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');
        die();
    }
}

$thumbnail = new pthumb_example();

// size
$w = isset($_GET['w'])? $_GET['w']: 100;
$h = isset($_GET['h'])? $_GET['h']: $w;

if (!isset($_GET['mode'])){
    $thumbnail -> display_x();
}
//Mode Thumbnail
elseif ($_GET['mode'] == 'thumbnail'){
    if ( !isset($_GET['image']) ){
        $thumbnail -> display_x();
    }
    else{
	    if (!isset($_GET['to_file'])){
		    if (!$thumbnail -> print_thumbnail($_GET['image'],$w,$h)){
		        $thumbnail -> display_x();
		    }
		}
		else{
		    $data = print_thumbnail($_GET['image'],$w,$h,true);
		    if (!$data){
		        $thumbnail -> display_x();
		    }
		    if (isset($_GET['overwrite']) && $_GET['overwrite'] == 'true'){
		        if (!$thumbnail -> image_to_file($data,$_GET['to_file'],true)){
		            $thumbnail -> display_x();
		        }
		        die('Image overwritten!');
		    }
		    else{
		        if (!$thumbnail -> image_to_file($data,$_GET['to_file'])){
		            $thumbnail -> display_x();
		        }
		        die('Image Written!');
		    }
		}

    }
}
//Mode Scale
elseif ($_GET['mode'] == 'scale'){
	if (!isset($_GET['image']) || !isset($_GET['magnitude'])){
		$thumbnail -> display_x();
	}
	else{
	    if (!isset($_GET['to_file'])){
		    if (!$thumbnail -> scale_thumbnail($_GET['image'],$_GET['magnitude'])){
		        $thumbnail -> display_x();
		    }
		}
		else{
		    $data = $thumbnail -> scale_thumbnail($_GET['image'],$_GET['magnitude'],true);
		    if (!$data){
		        $thumbnail -> display_x();
		    }
            $data = $thumbnail -> print_thumbnail($_GET['image'],$data[0],$data[1],true);
            if (!$data){
                $thumbnail -> display_x();
            }
		    if (isset($_GET['overwrite']) && $_GET['overwrite'] == 'true'){
		        if (!$thumbnail -> image_to_file($data,$_GET['to_file'],true)){
		            $thumbnail -> display_x();
		        }
		        die('Image overwritten!');
		    }
		    else{
		        if (!$thumbnail -> image_to_file($data,$_GET['to_file'])){
		            $thumbnail -> display_x();
		        }
		        die('Image Written!');
		    }
		}
	}
}
//Mode fit
elseif($_GET['mode'] == 'fit'){
	if (!isset($_GET['image']) || !isset($w) || !isset($h)){
		$thumbnail -> display_x();
	}
	else{
	    if (!isset($_GET['to_file'])){
		    if (!$thumbnail -> fit_thumbnail($_GET['image'],$w,$h)){
		        $thumbnail -> display_x();
		    }		
		}
		else{
		    $data = $thumbnail -> fit_thumbnail($_GET['image'],$w,$h,1,true);
		    if (!$data){
		        $thumbnail -> display_x();
		    }		
		    $data = $thumbnail -> print_thumbnail($_GET['image'],$data[0],$data[1],true);
		    if (!$data){
		        $thumbnail -> display_x();
		    }
		    if (isset($_GET['overwrite']) && $_GET['overwrite'] == 'true'){
		        if (!$thumbnail -> image_to_file($data,$_GET['to_file'],true)){
		            $thumbnail -> display_x();
		        }
		        die('Image Overwritten!');		    
		    }
		    else{
		        if (!$thumbnail -> image_to_file($data,$_GET['to_file'])){
		            $thumbnail -> display_x();
		        }
		        die('Image Written!');
		    }
		}
	}
}
//Unknown Modes
else{

	$thumbnail -> display_x();
}
//Other Errors
if ($thumbnail -> isError()){
    $thumbnail -> display_x();
}
?>