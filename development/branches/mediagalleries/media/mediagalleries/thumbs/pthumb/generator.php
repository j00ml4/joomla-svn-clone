<?php
/*
* Web Interface
*
* A web-based tool to resize images. Easy to use.
*
* To enable remote images, you might need to enable cache and set a cache dir. 
* Alternatively, to use the default, simply create a directory called "generate" and chmod it to 777.
*/
set_magic_quotes_runtime(0);
//Require Class
require_once("PThumb.php");

//Search for class_HTTPRetriever.php and include as neccessary
if (file_exists("class_HTTPRetriever.php")){
	require_once("class_HTTPRetriever.php");
}

error_reporting (E_ALL);

//Child Class for configuration
class pthumb_example extends PThumb{
	var $use_cache = true;
	var $clear_cache = true;
	var $cache_dir = "cache/";
	var $error_mode = 2;
	var $log_error = false;
	function thumb(){
		$this -> PThumb();
	}
}

$thumbnail = new pthumb_example;
if (isset($_POST["mode"])){
	$fp = "";
	//Handle Uploads
	if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0){
		$fp = $_FILES["file"]["tmp_name"];
	}
	elseif (isset($_POST["path"])){
		$fp = $_POST["path"];
	}
	else{
		die("You did not enter a file!");
	}
	$inline = true;
	if (isset($_POST["download"]) && $_POST["download"] == "true"){
		$inline = false;
	}
	if ($_POST["mode"] == "thumbnail"){
		$thumbnail -> print_thumbnail($fp, $_POST["width"], $_POST["height"], false, $inline);
	}
	elseif ($_POST["mode"] == "fit"){
		$dimensions = $thumbnail -> fit_thumbnail($fp, $_POST["width"], $_POST["height"], true);
		$thumbnail -> print_thumbnail($fp, $dimensions[0], $dimensions[1], false, $inline);
	}
	elseif ($_POST["mode"] == "scale"){
		$dimensions = $thumbnail -> scale_thumbnail($fp, $_POST["scale"], true);
		$thumbnail -> print_thumbnail($fp, $dimensions[0], $dimensions[1], false, $inline);
	}
	if ($thumbnail -> isError()){
		echo "The following errors occured while attempting to generate a thumbnail: <h4>Fatal Errors</h4><ul>";
		foreach ($thumbnail -> error() as $value){
			echo "<li>$value</li>";
		}
		echo "</ul><h4>Warnings</h4><ul>";
		foreach ($thumbnail -> error(false) as $value){
			echo "<li>$value</li>";
		}
		echo "</ul>";
		die();
	}
	die();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	    <title>Plottable Thumbnail Library Thumbnail Generator</title>
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css">
			body{
				font-family: tahoma;
				font-size: 14px;
			}
			a:link,
			a:visited{
				text-decoration: underline;
				color: #002D59;
			}
			a:hover{
				color: #ff0000;
			}
			input,
			select{
				font-size: 14px;
			}
		</style>
    </head>
	<body>
	<a name="top"></a><h1>Plottable Thumbnail Library Thumbnail Generator</h1>
	<a href="http://www.phpclasses.org/pthumb">Website</a >- <a href="http://www.phpclasses.org/browse/file/6732.html">Changelog</a> - Version 1.2.10
	<hr />
		<form enctype="multipart/form-data" action="" method="post">
			<h3>Generate Thumbnail</h3>
			<ul>
				<li>Upload File: <input type="file" name="file" /></li>
				<li>File Location: <input type="text" name="path" /></li>
				<li>Mode: 
					<select name="mode">
						<option value="thumbnail">Thumbnail Mode</option>
						<option value="fit">Fit Mode</option>
						<option value="scale">Scale Mode</option>
					</select>
				</li>
				<li>
					Width: <input type="text" name="width" /> px
				</li>
				<li>Height: <input type="text" name="height" /> px</li>
				<li>Scale: <input type="text" name="scale" /></li>
				
					
				</ul>
			<input type="checkbox" name="download" value="true" id="download" /> <label for="download">Download Thumbnail</label><br /><br />
			<input type="submit" value="Generate" /> <input type="reset" value="Clear Form" />
		</form>
	<hr />
	<small>
	<em>Last Updated: 28 October 2006</em>
	<br />&copy; 2004-2006 Chua Yong Wen [<a href="http://chuayw2000.6x.to">http://chuayw2000.6x.to</a>] [i.stole.your.precious[at]gmail.com]</small>
	</body>
</html>