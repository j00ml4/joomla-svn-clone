<?php
/**
 * @version		$Id: curl.php 14583 2010-05-16 07:16:48Z joomila $
 * @package		NoixFLAPP.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */

/**
 * Connector type CURL
 *
 * @package	NoixFLAPP.Framework
 * @base connector type
 * @since	1.0
 */
class JFlappConnectorTypeCurl implements JFlappConnetorTypeInterface
{
	private static $_options = array(
		'url' => '',
		'response' => '',
		'callback' => '',
		'dispatch_result' => ''
	);
	
	private static $instance;
	
	/**
	 * Singleton instance
	 * 
	 * @param unknown_type $options
	 * @since 1.0
	 */
	public static function getInstance($options=array())
	{
		if ( !isset(self::$instance) ){
			self::$instance = new JFlappConnectorTypeCurl();
		}
		self::$instance->setOptions($options);
		return self::$instance;
	}
	
	/**
	 * Config request options
	 * 
	 * @param unknown_type $options
	 * @since 1.0
	 */
	public function setOptions($options=array())
	{
		self::$_options = $options;
	}
	
	/**
	 * Config proxy
	 * 
	 * @param unknown_type $proxy
	 * @since 1.0
	 */
	public function setProxy($proxy)
	{
		$this->_proxy = $proxy;
	}
	
	/**
	 * Return request config options
	 * 
	 * @since 1.0
	 */
	public static function getOptions()
	{
		return self::$_options;
	}
	
	/**
	 * Request service
	 * 
	 * @since 1.0
	 */
	private function _requestService()
	{
		$options = self::getOptions();
		$queryUrl = $options['url'];
		if( !empty($this->_params) ){
			$queryUrl .= $this->_params;
		}
		
		if( empty($this->_query) ){
			$this->rows = array();
		}
		
		$session = curl_init($queryUrl);
		if( !empty($this->_proxy->url) ) {
			curl_setopt($session, CURLOPT_PROXY, $this->_proxy->url);
			
			if( !empty($this->_proxy->port) && !empty($this->_proxy->password) ) {
				curl_setopt($session, CURLOPT_PROXYPORT, $this->_proxy->port);
			}
			
			if( !empty($this->_proxy->user) ) {
				curl_setopt ($session, CURLOPT_PROXYUSERPWD, $this->_proxy->user.':'.$this->_proxy->password);
			}
		}
		
		curl_setopt($session, CURLOPT_HEADER, false); 
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);  
		$curlRest = curl_exec($session);
		curl_close($session);
		
		$this->result = $curlRest;
	}
	
	/**
	 * Config params to requested url
	 * 
	 * @param unknown_type $params
	 * @since 1.0
	 */
	public function setParams($params)
	{
		$this->_params = $params;
		
		return $this;
	}
	
	/**
	 * return result from DSL
	 * 
	 * @param unknown_type $dsl
	 * @since 1.0
	 */
	public function getResult($dsl)
	{
		$this->_requestService();
		return $this->result;
	}
}