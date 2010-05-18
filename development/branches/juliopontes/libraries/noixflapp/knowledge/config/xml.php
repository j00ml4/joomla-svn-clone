<?php
class XML
{
	public static function loadFile($file) {
		
		libxml_use_internal_errors(true);
		
		return simplexml_load_file($file, 'JFlappXmlElement');
		
	}
	
	public static function loadString($string) {
		
		libxml_use_internal_errors(true);
		
		return simplexml_load_string($string, 'JFlappXmlElement');
		
	}
}