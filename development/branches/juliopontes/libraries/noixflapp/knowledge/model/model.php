<?php
/**
 * @version		$Id: model.php 14583 2010-05-16 07:16:48Z joomila $
 * @package		NoixFLAPP.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */

/**
 * Knowledge Model Class
 * 
 * On this classes you will config table name, alias, fields to list, 
 * count field and relation between anothers models.
 * 
 * @package		NoixFLAPP.Framework
 * @subpackage 	knowledge model
 * @author 		Julio Pontes
 * @since 		1.0
 */
class JKnowledgeModel
{
	/**
	 * Name of the Table
	 * 
	 * @var	String
	 */
	protected $_table;
	
	/**
	 * Alias of table
	 * 
	 * @var String
	 */
	protected $_alias;
	
	/**
	 * Primary Key
	 * 
	 * @var String
	 */
	protected $_pk;
	
	/**
	 * List of fields
	 * 
	 * @var String
	 */
	protected $_fields;
	
	/**
	 * Count fields
	 * 
	 * @var String
	 */
	protected $_count;
	
	/**
	 * Connect Instance
	 * 
	 * @var unknown_type
	 */
	protected $_connect;
	
	/**
     * Path to include Catalogs
     * 
     * @var unknown_type
     */
    protected $_basePaths = array();
    
    /**
     * Refencemap to create autojoin with another tables
     * 
     * @var unknown_type
     */
    protected $_referenceMap = array();
	
    /**
     * Return an instance of model
     * 
     * @param unknown_type $modelName
     */
    public static function getInstance($modelName)
    {
    	if( !class_exists($modelName) ){
    		self::requiredModel($modelName);
    	}
    	$instance = new $modelName();
    	
    	if ( !( $instance instanceof JKnowledgeModel ) ) {
    		throw new JFlappException(get_class($instance).' must be implements JKnowledgeModel.');
    	}
    	
    	return $instance;
    }
    
	/**
     * this method include Model from String/Array
     * 
     * @param	Array	$model
     */
    public static function requiredModel( $models ) {
    	
    	if( !is_array( $models ) )
    	{
    		self::_include_Model( $models );
    	}
    	else {
	    	// loop through the path directories
			foreach ($models as $model) {
				self::_include_Model($model);
			}
    	}
    }
    
    /**
     * Config a connector
     * 
     * @param $connector
     */
    public function setConnector( $connector )
    {
    	$this->_connect = $connector;
    }
    
	/**
     * Load files form Dsl
     * 
     * @param $model
     */
    protected static function _include_Model($model) {
    	$_basePaths = self::addIncludePath();
    	
        $fileName = strtolower( str_replace(' ',DS, Util::fromCamelCase($model)) );
    	$createFileName = end(explode(DS,$fileName));
    	$filePath = JPATH_ROOT.DS.'knowledgebase'.DS . $fileName;
    	$filePath .= DS.$createFileName.'.php';
    	require_once $filePath;
    }
    
	/**
	 * return name of table
	 * 
	 * @return unknown_type
	 */
	public function getTableName()
	{
		return $this->_table;
	}
	
	/**
	 * return name of pk field
	 * 
	 */
	public function getPrimaryKey()
	{
		return $this->_pk;
	}
	
	/**
	 * return fields to select
	 */
	public function getListFields()
	{
		return $this->_fields;
	}
	
	/**
	 * Return coundable fields
	 */
	public function getCountFields()
	{
		return $this->_count;
	}
	
	/**
     * Check if exists any reference from table
     * 
     * @return unknown_type
     */
    public function getReference( $name )
    {
    	$modelClass = preg_replace('/[^A-Z0-9_\.-]/i', '', $name);
    	
    	if( empty($this->_referenceMap) || array_key_exists($modelClass,$this->_referenceMap) == false ) {
    		return false;
    	}
    	
    	return $this->_referenceMap[$modelClass];
    }
    
	/**
     * Add path to includde others catalogs
     * 
     * @param $path
     * @return unknown_type
     */
    public static function addIncludePath( $path='' ) {
		static $_basePaths;
		
		// force path to array
		settype($_basePaths, 'array');
		
		$cleanPath = preg_replace('#[/\\\\]+#', DS, $path);
		
		if (!empty($cleanPath) && !in_array($cleanPath, $_basePaths)) {
			array_unshift($_basePaths, $cleanPath);
		}

		return $_basePaths;
    }
    
    /**
     * Reference map
     * 
     * @param unknown_type $map
     */
	public static function addReferenceMap( $map ) {
		if( is_array($map) ) {
    		if( empty($this->_referenceMap) ){
    			$this->_referenceMap = $map;	
    		}
    		else{
    			$this->_referenceMap = array_merge($this->_referenceMap,$map);
    		}
    	}
    	
    	if( is_string($map) ){
	    	if ( !in_array($map, $this->_referenceMap)) {
				array_unshift($this->_referenceMap, $map);
			}
    	}
    }
}