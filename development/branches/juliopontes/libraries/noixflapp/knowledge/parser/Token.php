<?php
/**
 * @version		$Id: token.php 14583 2010-05-16 07:16:48Z joomila $
 * @package		NoixFLAPP.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */

/**
 * Parser Token
 * 
 * This class is reponsable to indentify a token that can be a class, method or parameter
 * 
 * @package		NoixFLAPP.Framework
 * @subpackage 	knowledge parser
 * @author 		Julio Pontes
 * @since 		1.0
 */
class JKnowledgeParserToken
{
    const TYPE_CLASS     = 'class';
    const TYPE_METHOD    = 'method';
    const TYPE_PARAMETER = 'parameter';
    
    protected $_type;
    protected $_value;
    protected $_identifier;
    
    public function __construct($type, $value, $identifier)
    {
        $this->_type       = $type;
        $this->_value      = $value;
        $this->_identifier = $identifier; 
    }
    
    public function getType()
    {
        return $this->_type;
    }
    
    public function getValue()
    {
        return $this->_value;
    }
    
    public function getIdentifier()
    {
        return $this->_identifier;
    }
}