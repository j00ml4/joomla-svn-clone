<?php
/**
 * @version		$Id: rest.php 14583 2010-05-16 07:16:48Z joomila $
 * @package		NoixFLAPP.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */

/**
 * Connector REST Handler Interface.
 *
 * @package	NoixFLAPP.Framework
 * @base connector handler
 * @since	1.0
 */
class JFlappConnectorHandlerREST implements JFlappConnectorHandlerInterface
{
	/**
	 * Config the REST Class
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
	 * extra params to requested url
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
	 * Connect to web-service using CURL
	 * 
	 * @since 1.0
	 */
	public function connect()
	{
		if( empty($this->_protocol) ){
			throw new JFlappException('You must set the protocol of URL');
		}
	
		$this->_url = $this->_protocol.'://'.$this->_url;
		
		foreach($this->_params as $paramKey => $paramVal){
			if( !empty($paramVal) ){
				$urlParams[] = $paramKey.'='.$paramVal;
			}
		}
		
		if( !is_object($this->_instance) ) {
			$urlRequest = $this->_url;
			$urlRequest .= !empty($urlParams) ? '?'.implode('&',$urlParams) : '';
			$options = array(
				'url' => $urlRequest  ,
				'reponse' => $this->_params['format'],
				'callback' => $this->_params['callback']
			);
			$this->_instance = JFlappConnectorTypeCurl::getInstance( $options );
			$this->_instance->setProxy($this->_proxy);
		}
		
		return $this->_instance;
	}
}