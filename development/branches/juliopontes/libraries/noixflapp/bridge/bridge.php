<?php
/**
 * @version		$Id: bridge.php 14549 2010-05-16 05:17:22Z joomila $
 * @package		NoixFLAPP.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'exception.php';

/**
 * Bridge Exception Class.
 *
 * @package		NoixFLAPP.Framework
 * @subpackage	bridge
 * @since		1.0
 */
class JBridge
{
	private $_params = array();
	private $_bridges = array();
	private $_destiny = array();
	
	public function __construct($params)
	{
		$this->_params = $params;
	}
	
	public function from( JKnowledge $bridge, $parser )
	{
		$this->_bridges[] = array( 'instance' => $bridge, 'parser' => $parser );
		
		return $this;
	}
	
	public function destiny( JKnowledge $destiny, $format )
	{
		if( empty($this->_destiny) ){
			$this->_destiny = array( 'instance' => $destiny, 'format' => $format );
		}
		else{
			throw new JBridgeException('You cant add another destiny');
		}
		
		return $this;
	}
	
	public function transport()
	{
		foreach($this->_bridges as $bridge){
			$instance = $bridge['instance'];
			$parsers = $bridge['parser'];
			if( is_array($parsers) ){
				foreach($parsers as $parser){
					$instance->parser($parser);
					if( !$this->migrateProccess($instance) ){
						return false;
					}
				}
			}
			else{
				$instance->parser($parsers);
				if( !$this->migrateProccess($instance) ){
					return false;
				}
			}
		}
		
		return true;
	}
	
	private function migrateProccess($instance)
	{
		$destinyInstance = $this->_destiny['instance'];
		$objectFormat = $this->_destiny['format'];
		return $destinyInstance->proccess($instance,$objectFormat,$this->_params);
	}
}