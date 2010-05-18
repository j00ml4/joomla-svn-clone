<?php
/**
 * @version		$Id: knowledge.php 14583 2010-05-16 07:16:48Z joomila $
 * @package		NoixFLAPP.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */

/**
 * JKnowledge Class
 * 
 * @package		NoixFLAPP.Framework
 * @subpackage 	knowledge
 * @author 		Julio Pontes
 * @since 		1.0
 */
class JKnowledge
{
	/**
	 * Name of Knowledge
	 * 
	 * @var String
	 */
	private $_name;
	
	/**
	 * String connector type
	 * 
	 * @var string
	 */
	private $_connectorType;
	
	/**
	 * Connector Instance
	 * 
	 * @var unknown_type
	 */
	private $_connector;
	
	/**
	 * Connector Type Instance;
	 */
	private $_connect;
	
	/**
	 * Instance of Grammar application
	 * 
	 * @var JKnowledgeParserGrammar
	 */
	private $_grammar;
	
	/**
	 * Instance of parser
	 * 
	 * @var JKnowledgeParser
	 */
	private $_parser;
	
	/**
	 * Instance of JKnowledgeDSL
	 * 
	 * @var JKnowledgeDSL
	 */
	private $_dsl;
	
	/**
	 * Config Options
	 * 
	 * @var unknown_type
	 */
	private $_options;
	
	/**
	 * Construct of knowledge base
	 * 
	 * @param unknown_type $knowledge
	 * @param unknown_type $option
	 * @since 1.0
	 */
	public function __construct($knowledge,$option=array())
	{
		$knowledge = strtolower(preg_replace('/[^A-Z0-9_\.-]/i', '', $knowledge));
		jimport('joomla.filesystem.path');
		$folderPath = JPATH_ROOT.DS.'knowledgebase'.DS.$knowledge;
		if( JFolder::exists($folderPath) ){
			$options = array(
				'basePath' => $folderPath
			);
			
			if( !empty($option) ){
				$options = array_merge($options,$option);
			}
			
			$this->_name = $knowledge;
			$this->_options = $options;
			$this->_grammar = new JKnowledgeParserGrammar($options);
		}
	}
	
	/**
	 * Return name of knowledge
	 * 
	 * @since 1.0
	 */
	public function getName()
	{
		return $this->_name;
	}
	
	/**
	 * Return DSL instnace
	 * 
	 * @since 1.0
	 */
	public function getDsl()
	{
		return $this->_dsl;
	}
	
	/**
	 * Parser a string to knowledge and validade this string
	 * 
	 * @param unknown_type $string
	 * @since 1.0
	 */
	public function parser($string)
	{
		try{
			if( !($this->_grammar instanceof JKnowledgeParserGrammar) ){
				throw new JFlappException('You must instance an grammar');
			}
			
			$this->_parser = new JKnowledgeParser();
			$this->_parser->setGrammar($this->_grammar);
			$this->_dsl = $this->_parser->parse($string);
			$this->_requestedString = $string;
		} catch (Exception $e){
			throw new JFlappException( $e->getMessage() );
		}
	}
	
	/**
	 * Return parser string
	 * 
	 * @since 1.0
	 */
	public function getParserString()
	{
		return $this->_requestedString;
	}
	
	/**
	 * Return connector type instance
	 * 
	 * @since 1.0
	 */
	public function getConnectorType()
	{
		return $this->_connectorType;
	}
	
	/**
	 * Config a connector type
	 * 
	 * @param unknown_type $name
	 * @param unknown_type $config
	 * @since 1.0
	 */
	public function connect($name,$config)
	{
		$this->_connectorType = $name;
		$this->_connect = JFlappConnector::getInstance('handler',$name,$config);
	}
	
	/**
	 * Load default configuration from knowledge
	 * 
	 * @param unknown_type $basePath
	 * @todo think on better solution for this
	 */
	private function loadConfiguration($basePath)
	{
		include $basePath.DS.'config.php';
		$this->connect($connectorType,$connectorParams);
	}
	
	
	public function proccess($knowledge,$format,$params=null)
	{
		$basePath = $this->_options['basePath'];
		$instanceProccess = JKnowledgeMigrator::getInstance($format,$basePath);
		$this->loadConfiguration($this->_options['basePath']);
		$instanceProccess->setConnector( $this->_connect );
		return $instanceProccess->convert($knowledge,$params)->proccess();
	}
	
	/**
	 * Create a bridge to initialize a migration tool 
	 * $format is a data type to be converted and
	 * $params is default config values to this data
	 * 
	 * @param string $format
	 * @param array $params
	 * @since 1.0
	 */
	public function createBridge($format, $params=null)
	{
		$this->_bridge = new JBridge($params);
		$this->_bridge->destiny($this,$format);
		
		return $this->_bridge;
	}
	
	/**
	 * Return result from parsed string
	 * 
	 * @since 1.0
	 */
	public function result()
	{	
		$this->loadConfiguration($this->_options['basePath']);
		
		if( !$this->_connect ){
			throw new JFlappException('You must set a connection');
		}
		
		$this->_dsl->setConnector( $this->_connect );
		return $this->_dsl->getResult();
	}
}