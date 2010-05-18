<?php
/**
 * @version		$Id: yql.php 14583 2010-05-16 07:16:48Z joomila $
 * @package		NoixFLAPP.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */

/**
 * Connector YQL Handler Interface.
 *
 * @package	NoixFLAPP.Framework
 * @base connector handler
 * @since	1.0
 */
class JFlappConnectorHandlerYQL implements JFlappConnectorHandlerInterface
{
	/**
	 * String protocol of YQL
	 * 
	 * @var unknown_type
	 */
	private $_protocol;
	
	/**
	 * URL of YQL
	 * 
	 * @var unknown_type
	 */
	private $_url;
	
	/**
	 * Params of URL
	 * 
	 * @var unknown_type
	 */
	private $_params;
	
	/**
	 * Proxy Object
	 * 
	 * @var unknown_type
	 */
	private $_proxy;
	
	/**
	 * Singleton instance
	 * 
	 * @var unknown_type
	 */
	private $_instance;
	
	/**
	 * Config the YQL Class
	 * 
	 * @param unknown_type $config
	 * @since 1.0
	 */
	public function __construct($config=null)
	{
		$this->_proxy = new JObject();
		$this->_params = Array();
		
		if( is_object($config) || is_array($config) ) {
			foreach($config as $method => $value) {
				if( method_exists($this,$method) ) {
					$this->$method($value);
				}
			}
		}
	}
	
	/**
	 * Define protocol HTTP/HTTPS
	 * 
	 * @param unknown_type $protocol
	 * @since 1.0
	 */
	public function protocol($protocol)
	{
		$this->_protocol = $protocol;
	}
	
	/**
	 * Define URL
	 * 
	 * @param unknown_type $url
	 * @since 1.0
	 */
	public function url($url)
	{
		$this->_url = $url;
		
		return $this;
	}
	
	
	
	/**
	 * add params
	 * 
	 * @param unknown_type $params
	 * @since 1.0
	 */
	public function params($params)
	{
		$this->_params = array();
		foreach($params as $key => $val){
			if($key == 'objects'){
				foreach($val as $skey => $sval){
					if($skey == 'JArrayConfig'){
						$this->_params = array_merge($this->_params,$sval->getConfig());
					}
				}
			}
			else{
				$this->_params[$key] = $val;
			}
		}
	}
	
	/**
	 * Set proxy URL
	 * 
	 * @param $proxy
	 * @since 1.0
	 */
	public function proxy($proxy)
	{
		$this->_proxy->url = $proxy;
		
		return $this;
	}
	
	/**
	 * Set proxy port
	 * 
	 * @param unknown_type $port
	 * @since 1.0
	 */
	public function proxy_port($port)
	{
		$this->_proxy->port = $port;
		
		return $this;
	}
	
	/**
	 * Set proxy user
	 * 
	 * @param unknown_type $user
	 * @since 1.0
	 */
	public function proxy_user($user)
	{
		$this->_proxy->user = $user;
		
		return $this;
	}

	/**
	 * Set proxy password
	 * 
	 * @param unknown_type $password
	 * @since 1.0
	 */
	public function proxy_password($password)
	{
		$this->_proxy->password = $password;
		
		return $this;
	}
	
	/**
	 * Connect to YQL using CURL
	 * 
	 * @since 1.0
	 */
	public function connect()
	{
		if( empty($this->_protocol) ){
			throw new JFlapp_Exception('You must set the protocol of URL');
		}
	
		$this->_url = $this->_protocol.'://'.$this->_url;
		
		foreach($this->_params as $paramKey => $paramVal){
			if( !empty($paramVal) ){
				$urlParams[] = $paramKey.'='.$paramVal;
			}
		}
		
		$this->_url .= '?'.implode('&',$urlParams);
		
		
		if( !is_object($this->_instance) ) {
			$options = array(
				'url' => $this->_url
			);
			$this->_instance = JFlappConnectorTypeCurl::getInstance( $options );
			$this->_instance->setProxy($this->_proxy);
		}
		
		return $this->_instance;
	}
}