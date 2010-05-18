<?php
/**
 * @version		$Id: database.php 14583 2010-05-16 07:16:48Z joomila $
 * @package		NoixFLAPP.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */

jimport('joomla.database.database');

/**
 * Connector type Database
 *
 * @package	NoixFLAPP.Framework
 * @base connector type
 * @since	1.0
 */
class JFlappConnectorTypeDatabase implements JFlappConnetorTypeInterface
{
	public static $instance;
	
	/**
	 * Define a instance of DBO
	 * 
	 * @param unknown_type $databaseInstance
	 * @since 1.0
	 */
	public function __construct($databaseInstance)
	{
		$this->_instance = $databaseInstance;
	}
	
	/**
	 * Singleton instance from database
	 * 
	 * @param unknown_type $options
	 * @since 1.0
	 */
	public static function getInstance($options=array())
	{
		$signature = serialize($options);
		
		if ( !isset(self::$instance[$signature]) ){
			$databaseInstance = JDatabase::getInstance( $options );
			if ( $databaseInstance->getErrorNum() ) {
				throw new JFlappException('Database Error: ' . $databaseInstance->toString() );
			}
	
			if ($databaseInstance->getErrorNum() > 0) {
				throw new JFlappException('Database::getInstance: Could not connect to database <br />'.$databaseInstance->getErrorNum().' - '.$databaseInstance->getErrorMsg() );
			}
			self::$instance[$signature] = new JFlappConnectorTypeDatabase($databaseInstance);
		}
				
		return self::$instance[$signature];
	}
	
	/**
	 * Return a result from specific DSL
	 * 
	 * @param unknown_type $dsl
	 * @since 1.0
	 */
	public function getResult($dsl)
	{
		$this->_instance->setQuery((string)$dsl->JDatabaseQuery);
		return $this->_instance->loadObjectList();
	}
	
	/**
	 * Try to call any function to instance object
	 * 
	 * @param unknown_type $name
	 * @param unknown_type $arguments
	 * @since 1.0
	 */
	public function __call($name,$arguments){
		$method = $name;
		if( method_exists($this->_instance,$method) ){
			$paramsString = null;
			if( !empty($arguments) ){
				$paramsString = implode('","',$arguments);	
			}
			return $this->_instance->$method($paramsString);
		}
	}
}