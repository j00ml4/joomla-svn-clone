<?php
/**
 * @version		$Id: application.php 2010-05-13 12:34:35Z joomila $
 * @package		Joomla.REST
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.helper');
jimport('joomla.document.document');

/**
 * Joomla! Service class
 *
 * Provide many supporting API functions
 *
 * @package		Joomla.REST
 * @final
 */
class JService extends JApplication
{
	/**
	 * Initialise the application.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 */
	public function initialise($options = array())
	{
		$config = &JFactory::getConfig();
		
		/**
		 * Create document to return data
		 * 
		 * @var String Type
		 */
		$formatType = JRequest::getWord('format','xml');
		$document = JDocument::getInstance($formatType);
		$this->_document = $document;
		$this->_format = $formatType;
		$this->_config = $config;
		
		//check if enabled
		if( !$this->_config->get('enabled',0) ){
			$this->enqueueMessage('Service is disabled');
			return false;
		}
		
		/**
		 * check api key
		 */
		if( $this->_config->get('require_apikey',0) ){
			$credentials = array(
				'api_key' => JRequest::getString('api_key')
			);
			
			if( !$this->check_apikey($credentials) ){
				$this->enqueueMessage('you must to check your api key');
				return;
			}
		}
		
		$this->dispatch();
	}
	
	public function dispatch()
	{
		$queryCommand = JRequest::getString('q');
		$methodCommand = JRequest::getString('method');
		
		if( empty($queryCommand) && empty($methodCommand) ){
			$this->enqueueMessage('dispatch command not found' );
		}
		
		if( $queryCommand ){
			try{
				$connection = array(
					'driver' => $this->_config->get('dbtype'),
					'host' => $this->_config->get('host'),
					'username' => $this->_config->get('user'),
					'password' => $this->_config->get('password'),
					'database' => $this->_config->get('db'),
					'table_prefix' => $this->_config->get('dbprefix')
				);
				
				$knowledge = new JKnowledge('joomla');
				$knowledge->connect('database',$connection);
				$knowledge->parser($queryCommand);
				$this->_data['result'] = $knowledge->result();
			}
			catch (Exception $e){
				$this->enqueueMessage( $e->getMessage() );
			}
		}
		
	}
	
	public function render()
	{
		$queueMessages = $this->getMessageQueue();
		
		if( !empty($queueMessages) ){
			foreach($queueMessages as $queueMessage){
				$errors = array( $queueMessage['type'] => $queueMessage['message'] );
			}
			
			$this->_data = array( 'errors' => $errors );
		}
		
		$response = JServiceHelper::response($this->_data,$this->_format);
		
		JResponse::setBody( $response );
	}
	
	/**
	 * Check if apikey is valid.
	 * 
	 * @param $credentials
	 * @return Boolean
	 */
	public function check_apikey($credentials)
	{
		if( empty($credentials['api_key']) ){
			return false;
		}
		
		return JServiceHelper::checkApiKey($credentials);
	}
}