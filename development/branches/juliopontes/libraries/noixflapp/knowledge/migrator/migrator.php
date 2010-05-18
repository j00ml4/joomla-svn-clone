<?php
/**
 * @version		$Id: migrator.php 14583 2010-05-16 07:16:48Z joomila $
 * @package		NoixFLAPP.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */

/**
 * Migrator Class
 * 
 * @package		NoixFLAPP.Framework
 * @subpackage 	knowledge migrator
 * @author 		Julio Pontes
 * @since 		1.0
 */
abstract class JKnowledgeMigrator
{
	/**
	 * 
	 * 
	 * @var JKnowledge Object
	 */
	protected $_knowledge;
	
	/**
	 * Connector instance
	 * 
	 * @var unknown_type
	 */
	protected $_connect;
	
	/**
	 * Return a instance of migration Object
	 * 
	 * @param unknown_type $format
	 * @param unknown_type $basePath
	 * @since 1.0
	 */
	public function getInstance($format,$basePath)
	{
		$origBasePath = $basePath;
		$basePath .= DS.$format.DS.'migrator'.DS.$format; 
		$filePath = JFolder::files($basePath,'php',true,true);
		if( !empty($filePath) ){
			require_once $filePath[0];
			$application = str_replace(JPATH_ROOT.DS.'knowledgebase'.DS,'',$origBasePath);
			$className = $application.$format.'Migrator'.$format;
			return new $className();
		}
	}
	
	/**
	 * Config a connector
	 * 
	 * @param unknown_type $connector
	 * @since 1.0
	 */
	public function setConnector( $connector )
	{
		$this->_connect = $connector;
	}
	
	/**
	 * Check if exists an method with name 
	 * 
	 * @param unknown_type $name
	 * @since 1.0
	 */
	public function checkKnowledge( $name )
	{	
		if( method_exists($this,$name) ){
			return $this->$name();
		}
	}
	
	/**
	 * Register knowledge object
	 * 
	 * @param JKnowledge $knowledge
	 * @since 1.0
	 */
	public function setKnowledge(JKnowledge $knowledge)
	{
		$this->_knowledge = $knowledge;
	}
	
	/**
	 * Return knowledge object
	 * 
	 * @return JKnowledge Object
	 * @since 1.0
	 */
	public function getKnowledge()
	{
		return $this->_knowledge;
	}
	
	/**
	 * This will be responsable to convert your data if needs to specific format
	 * 
	 * @param $knowledge
	 * @param $params
	 * @since 1.0
	 */
	abstract function convert($knowledge,$params=null);
	
	/**
	 * Migration proccess
	 * 
	 * @since 1.0
	 */
	abstract function proccess();
}