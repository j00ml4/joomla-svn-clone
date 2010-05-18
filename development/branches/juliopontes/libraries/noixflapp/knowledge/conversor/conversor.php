<?php
/**
 * @version		$Id: conversor.php 14583 2010-05-16 07:16:48Z joomila $
 * @package		NoixFLAPP.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */

/**
 * Knowledge Data Conversor Class
 * 
 * This class is reponsable to convert data from dsl reciveing another knowledge.
 * 
 * @package		NoixFLAPP.Framework
 * @subpackage 	knowledge
 * @author 		Julio Pontes
 * @since 		1.0
 */
class JKnowledgeDataConversor
{
	/**
	 * Instances of conversos 
	 * 
	 * @var unknown_type
	 */
	private static $_instances;
	/**
	 * Knowledge
	 * 
	 * @var unknown_type
	 */
	protected $_knowledge;
	/**
	 * Aditional params to config converted object
	 * 
	 * @var unknown_type
	 */
	protected $_extraParams;
	
	/**
	 * Set an knowledge for convert
	 * 
	 * @param unknown_type $knowledge
	 * @since 1.0
	 */
	public function addKnowledge($knowledge)
	{
		$this->_knowledge = $knowledge;
		
		return $this;
	}
	
	/**
	 * Return instance(s) of conversor(s) data
	 * 
	 * You can get an array or instances of conversor data
	 * 
	 * @since 1.0
	 */
	public function getInstance()
	{
		$arguments = func_get_args();
		
		$return = array();
		
		foreach($arguments as $argument)
		{
			if( !isset(self::$_instances[$argument]) ){
				self::requireDataConversor($argument);
				self::$_instances[$argument] = new $argument();
			}
			
			$return[] = self::$_instances[$argument];
		}
		
		
		return ( count($return) == 1 ) ? $return[0] : $return ;
	}
	
	/**
	 * Require knowledge conversor
	 * 
	 * @since 1.0
	 */
	public function requireDataConversor()
	{
		$arguments = func_get_args();
    	foreach($arguments as $argument){
    		if( is_string($argument) ){
    				$fileName = strtolower( str_replace(' ',DS, Util::fromCamelCase($argument)) );
    				$createFileName = end(explode(DS,$fileName));
    				$filePath = JPATH_ROOT.DS.'knowledgebase'.DS . $fileName;
    				$filePath .= DS.$createFileName.'.php';
    				require_once $filePath;
    		}
    		if( is_array($argument) ){
    			foreach($argument as $subArgument){
    				$fileName = strtolower( str_replace(' ',DS, Util::fromCamelCase($subArgument)) );
    				$createFileName = end(explode(DS,$fileName));
    				$filePath = JPATH_ROOT.DS.'knowledgebase'.DS . $fileName;
    				$filePath .= DS.$createFileName.'.php';
    				require_once $filePath;
    			}
    		}
    	}
	}
	
	/**
	 * Call to convert data prococcess
	 * 
	 * @param $extraParams
	 * @since 1.0
	 */
	public function convertData( $extraParams = array() )
	{
		$this->_extraParams = $extraParams;
		$parsedString = $this->_knowledge->getParserString();
		if( !empty($parsedString) ){
			$parserString = explode(' ',$this->_knowledge->getParserString());
			$dataType = $parserString[0];
			if( method_exists($this,$dataType) ){
				return $this->$dataType();
			}
		}
		
		return false;
	}
}