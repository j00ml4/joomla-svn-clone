<?php
/**
 * @version		$Id: database.php 14583 2010-05-16 07:16:48Z joomila $
 * @package		NoixFLAPP.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */

/**
 * Connector Database Handler Interface.
 *
 * @package	NoixFLAPP.Framework
 * @base connector handler
 * @since	1.0
 */
class JFlappConnectorHandlerDatabase implements JFlappConnectorHandlerInterface
{
	/**
	 * driver name
	 * 
	 * @var unknown_type
	 */
	private $_driver;
	/**
	 * host of database
	 * 
	 * @var unknown_type
	 */
	private $_host;
	/**
	 * user of database
	 * 
	 * @var unknown_type
	 */
	private $_username;
	/**
	 * password of database
	 * 
	 * @var unknown_type
	 */
	private $_password;
	/**
	 * name of database
	 * 
	 * @var unknown_type
	 */
	private $_database;
	/**
	 * table prefix
	 * 
	 * @var unknown_type
	 */
	private $_prefix;
	/**
	 * instnace of DBO
	 * 
	 * @var unknown_type
	 */
	private $_instance;
	
	/**
	 * Construct of connector handler database
	 * 
	 * @param unknown_type $config
	 * @since 1.0
	 */
	public function __construct($config=null)
	{
		foreach($config as $method => $value) {
			if( method_exists($this,$method) ) {
				$this->$method($value);
			}
		}
	}
	
	/**
	 * Driver of conncetion
	 * 
	 * @param unknown_type $driver
	 * @since 1.0
	 */
	public function driver($driver)
	{
		$this->_driver = $driver;
	}
	
	/**
	 * set host of database
	 * 
	 * @param unknown_type $hostName
	 * @since 1.0
	 */
	public function host( $hostName ) {
		$this->_host = $hostName;
		
		return $this;
	}
	
	/**
	 * set username of database
	 * 
	 * @param unknown_type $userName
	 * @since 1.0
	 */
	public function username( $userName ) {
		$this->_username = $userName;
		
		return $this;
	}
	
	/**
	 * set a password
	 * 
	 * @param unknown_type $password
	 * @since 1.0
	 */
	public function password( $password ) {
		$this->_password = $password;
		
		return $this;
	}
	
	/**
	 * set a database name
	 * 
	 * @param unknown_type $database
	 * @since 1.0
	 */
	public function database( $database ) {
		$this->_database = $database;
		
		return $this;
	}
	
	/**
	 * set a prefix for tables
	 * 
	 * @param unknown_type $prefix
	 * @since 1.0
	 */
	public function table_prefix($prefix) {
		$this->_prefix = $prefix;
		
		return $this;
	}
	
	/**
	 * Connect on database and get a instance
	 * 
	 * @since 1.0
	 * @return database instance
	 */
	public function connect()
	{
		$options = array(
			'driver' => $this->_driver,
			'host' => $this->_host,
			'user' => $this->_username,
			'password' => $this->_password,
			'database' => $this->_database,
			'prefix' => $this->_prefix
		);
		
		$this->_instance = JFlappConnectorTypeDatabase::getInstance( $options );
	
		return $this->_instance;
	}
}