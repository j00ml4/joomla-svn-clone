<?php
/**
 * @version		$Id: helper.php 14276 2010-05-13 14:20:28Z Joomila $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Service helper class provides some utility functions
 * 
 * @author Julio Pontes
 * @version 0.1
 */
class JServiceHelper
{
	/**
	 * Check api key
	 * 
	 * @param unknown_type $credentials
	 */
	public static function checkApiKey($credentials)
	{	
		$pathXmlKeys = dirname(__FILE__).DS.'apikeys.xml';
		$xmlApiKeys = simplexml_load_file($pathXmlKeys);
		
		foreach($xmlApiKeys->children() as $apikey){
			if($apikey->key == $credentials['api_key']) return true;
		}
		
		return false;
	}
	
	/**
	 * Convert response type
	 * 
	 * @param unknown_type $data
	 * @param unknown_type $format
	 */
	public static function response($data,$format)
	{
		switch($format)
		{
			case 'json':
				$response = self::json_encode($data);
				break;
			default:
			case 'xml':
				$response = self::xml_encode($data,'response');
				break;
		}
		
		return $response;
	}
	
	/**
	 * Convert data to json
	 * 
	 * @param unknown_type $data
	 */
	private static function json_encode($data)
	{
		return json_encode($data);
	}
	
	/**
	 * Returns the XML representation of a value
	 *
	 * @param $to_encode mixed The value to encode. This can be either an array or an object
	 * @param $root string (default "array") The name of the root element
	 * @param $encoding string (default "UTF-8") The encoding type to use for XML document
	 * @param $_level int (private) Used for recursion to indicate the current level within the XML document
	 */
	private static function xml_encode($to_encode, $root = 'array', $encoding = 'UTF-8', $_level = 1)
	{
		// If this is the first call, then start with a new XML tag
		$xml = $_level == 1 ? "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>\n<{$root}>\n" : '';
	 
		// If the given content is an object, convert it to an array so that we can loop through all the values
		if (is_object($to_encode))
		{
			$to_encode = get_object_vars($to_encode);
		}
	 
		// Loop through each value in the array and add it to the current level if it is a single value, or make a
		// recursive call and indent the level by one if the value contains a collection of sub values
		foreach ($to_encode as $key => $value)
		{
			if (is_array($value) || is_object($value))
			{
				if( is_int($key) ){ $key = 'row'.$key; }
				$xml .= str_repeat("\t", $_level)."<{$key}>\n".self::xml_encode($value, $root, $encoding, $_level + 1)
					.str_repeat("\t", $_level)."</{$key}>\n";
			}
			else
			{
				// Trim the data since XML ignores whitespace, and convert entities to an appropriate form so that the XML
				// remains valid
				$value = htmlentities(trim($value));
				$xml .= str_repeat("\t", $_level)."<{$key}>{$value}</{$key}>\n";
			}
		}
	 
		// Close the XML tag if this is the last recursive call
		return $_level == 1 ? "{$xml}</{$root}>\n" : $xml;
	}
}