<?php
/**
 * @version		$Id: dsl.php 14583 2010-05-16 07:16:48Z joomila $
 * @package		NoixFLAPP.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */

/**
 * Knowledge Domain Specific Language
 * 
 * @package		NoixFLAPP.Framework
 * @subpackage knowledge
 * @author Julio Pontes
 * @since 1.0
 */
class JKnowledgeDSL implements JKnowledgeDslInterface
{
	/**
     * Connector Object
     * 
     */
    private $_connector;
	/**
	 * Object configs
	 * 
	 * @var Array
	 */
	public static $_configMap = array('JDatabaseQuery','JArrayConfig','JStringConfig');
	/**
     * List to replace a refrence from Catalog Class
     * 
     * @var	Array
     */
    public static $_referenceMap = array();
    
	/**
	 * Can reciver another Knowledge by reference to create an sotisficated knowledge grammar
	 * 
	 * @param JKnowledgeDslInterface $reference
	 */
	public function __construct(JKnowledgeDslInterface $reference = null)
	{
		if( !isset($this->JDatabaseQuery) && !isset($this->JArrayConfig) && !isset($this->JStringConfig) )
		{
			$arr = self::$_configMap;
			for ( $i=0,$m=count($arr); $i<$m; $i++ )
			{
				$class_name = $arr[$i];
				$this->extendClass($class_name);
			}
		}
		
		/**
		 * this affect all DSL and type of configs
		 */
		if( $this->JDatabaseQuery instanceof JDatabaseQuery && $reference instanceof JKnowledgeDslInterface )
		{
			$this->_factoryModel();
			
			// 1 – record reference on catalog
            $this->_references[get_class($reference)] = $reference;
 
            // 2 – use same Select, configs
            foreach(self::$_configMap as $configVar){
            	$this->$configVar = $reference->$configVar;
            }
            
            // 3 - instance model
	        $this->model();
            
            // 4 – Do JOIN with between tables
            $fromTable = $reference->model()->getTableName();
            
            $fromModel = get_class($reference->model());
            $joinTable = $this->model()->getTableName();
            $referenceMap = $this->model()->getReference($fromModel);
			
            if( empty($referenceMap) ){
            	return false;
            }
            
            $clauses = array();
 
            foreach ($referenceMap['columns'] as $key => $column) {
                $refColumn = $referenceMap['refColumns'][$key];
                $clauses[] = "$fromTable.$refColumn = $joinTable.$column";
            }
 
            $clauses = ' ON ( '.implode(' AND ', $clauses).' )';
 
            $this->JDatabaseQuery->join($referenceMap['jointype'],$joinTable.$clauses);
            
            //check if has add new fields to select
            if ( isset($referenceMap['addfields']) && !empty($referenceMap['addfields']) ) {
            	$cleanTableName = !empty($this->model()->_alias) ? $this->model()->_alias : str_replace('#__','',$joinTable);
            	foreach ($referenceMap['addfields'] as $key => $column) {
                	$columnName = $referenceMap['addfields'][$key];
	            	$newFields[] = "$joinTable.$columnName AS {$cleanTableName}_{$columnName}";
            	}
            	
            	if( !empty($newFields) ){
            		$this->JDatabaseQuery->select(implode(',',$newFields));
            	}
            }
		}
		
	}
	
	/**
	 * change Connector
	 * 
	 * @param $connector
	 * @return unknown_type
	 */
    public function setConnector( $connector ) {
    	$this->_connector = $connector;
    }
    
    /**
     * return ConfigMap
     * 
     * @return array
     */
    public function getConfigMap()
    {
    	return self::$_configMap;
    }
	
	/**
	 * return an instance of Knowledge DSL
	 * 
	 * @param JKnowledgeDslInterface $dslName
	 */
	public function getInstance($dslName)
	{
		self::requireKnowledge($dslName);
		
		$instance = new $dslName();
    	
    	if ( !( $instance instanceof JKnowledgeDslInterface ) ) {
    		throw JFlappExeption($dslName.' must be implements JKnowledgeDslInterface');
    	}
    	
    	return $instance;
	}
	
	/**
	 * Config a list for 
	 */
	public function getList()
	{
		if( is_object($this->JDatabaseQuery) ){
			$this->JDatabaseQuery->select($this->model()->getListFields());
			$this->JDatabaseQuery->from($this->model()->getTableName());
		}
		
		$string = Util::fromCamelCase(get_class($this));
		$className = end(explode(' ',$string));
		
		if( is_object($this->JArrayConfig) ){
			
			$this->JArrayConfig->add( 'method',$className );
		}
		
		if( is_object($this->JStringConfig) ){
			$this->JStringConfig->add( $className.' ' );
		}
	}
	
	/**
	 * Create a instance of class name
	 * 
	 * @param String $class_name
	 */
	public function extendClass($class_name)
	{
		$this->$class_name = new $class_name;
		self::$_configMap[] = $class_name;
	}
	
    /**
     * Change a reference to another Catalog
     * 
     * @param $catalog
     * @param $autojoin
     * @return unknown_type
     */
    protected function _reference($catalog,$autojoin=true) {
    	//check for reference map
    	if( array_key_exists($catalog,self::$_referenceMap) ) {
    		// replace original catalog for reference map value if exists in keys with same name of catalog
    		$catalog = self::$_referenceMap[$catalog];
    	}
    	
    	//include catalog
    	self::requireKnowledge($catalog);
    	
    	//instace a catalgo if not exists in references
        if ( !isset($this->_references[$catalog]) ) {
        	$className = $catalog;
        	$this->_references[$catalog] = new $className($this,$autojoin);
        }  
		
        return $this->_references[$catalog];  
    } 
	
	
	/**
     * Instance a Model from Catalog
     * 
     */
    protected function model() {
    	
        if (null === $this->_model) {
            $this->_model = $this->_factoryModel();
        }
 
        return $this->_model;
    }
    
    public static function requireKnowledge()
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
     * 
     */
	public function rowset()
    {
    	if (null === $this->_rowset) {
            $this->_rowset = $this->model();
        }

        return $this->_rowset;
    }
    
	/**
     * Move to first element
     * Implementado da interface Iterator
     */
    public function rewind()
    {
        $this->rowset()->rewind();
    }

    /**
     * check if exists element
     * Implementado da interface Iterator
     */
    public function valid()
    {
        return $this->rowset()->valid();
    }

    /**
     * Return current element
     * Implementado da interface Iterator
     */
    public function current()
    {
        return $this->rowset()->current();
    }

    /**
     * Return index from current element
     * Implementado da interface Iterator
     */
    public function key()
    {
        return $this->rowset()->key();
    }

    /**
     * Next element iterator
     * Implementado da interface Iterator
     */
    public function next()
    {
        return $this->rowset()->next();
    }

    /**
     * Return value that showed by global functino count()
     * Implementado da interface Countable
     * 
     * @return int
     */
    public function count()
    {
    	return $this->rowset()->count();
    }
    
    
    public function getResult()
    {
    	if( empty($this->_connector) ){
    		throw new JFlappException('Connector not defined');
    	}
    	
    	return $this->_connector->getResult($this);
    }
}