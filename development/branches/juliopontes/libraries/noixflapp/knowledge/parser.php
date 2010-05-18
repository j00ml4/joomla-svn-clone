<?php

require_once 'parser'.DS.'grammar.php';
require_once 'parser'.DS.'exception.php';
require_once 'parser'.DS.'tokenizer.php';
require_once 'parser'.DS.'token.php';

/**
 * Takes a language grammar, parses the user defined query with a tokenizer and
 * generates a tree. This tree is then interpreted returning a catalog.
 *
 */
class JKnowledgeParser
{
    /**
     * Language grammar
     *
     * @var JKnowledgeParserGrammar
     */
    private $_grammar;
    
    /**
     * Tokenizer
     *
     * @var JKnowledgeParserTokenizer
     */
    private $_tokenizer;
    
    /**
     * Classname
     * 
     * @var unknown_type
     */
    private $_className;
    
    /**
     * Connector of catalog
     * 
     * @var Application_Connector
     */
    private $_connector;
    
    /**
     * Addds a language grammar to the parser
     *
     * @param JKnowledgeParserGrammar $grammar
     */
    public function setGrammar(JKnowledgeParserGrammar $grammar)
    {
        $this->_grammar = $grammar;
    }
    
    /**
     * Conenctor Type
     * 
     * @param $connector
     * @return unknown_type
     */
    public function setConnector($connector)
    {
    	$this->_connector = $connector;
    }
    
    /**
     * Lazy tokenizer
     *
     * @return DslCatalog_Parser_Tokenizer
     */
    protected function _tokenizer()
    {
        if (null === $this->_tokenizer) {
            $this->_tokenizer = new JKnowledgeParserTokenizer();
        }
        
        return $this->_tokenizer;
    }

    /**
     * Parse a user provided query and returns its configured catalog
     *
     * @param string $query
     * @return DslCatalog_Abstract
     */
    public function parse($query)
    {
        if (null === $this->_grammar) {
            throw new JKnowledgeParserException("Undefined grammar");
        }
        
        $query = trim($query);
        if ('' == $query) {
            throw new JKnowledgeParserException("Empty query");
        }
        
        $tokenizer = $this->_tokenizer();
        $tokenizer->setQuery($query);
        $tokenizer->setGrammar($this->_grammar->getStructure());
        
        // this tree is gonna hold the class, methods and parameters that we
        // will execute later
        $parseTree = array();
        $parseTreeLength = 0;
        
        // This is gonna be our catalog
        $catalog = null;
        
        foreach ($tokenizer as $token) {
            switch ($token->getType()) {
                // tokens are most likely to be method calls so we check for it first
                case JKnowledgeParserToken::TYPE_METHOD:
                    $node = array(
                        'identifier' => $token->getIdentifier(),
                        'parameters' => array(),
                    );
                    
                    // add this method to the tree and increse the tree size
                    $parseTree[] = $node;
                    $parseTreeLength++;

                    break;                    
                case JKnowledgeParserToken::TYPE_PARAMETER:
                    // append it to the last inserted method
                    $parseTree[$parseTreeLength-1]['parameters'][] = $token->getValue();
                    
                    break;
                case JKnowledgeParserToken::TYPE_CLASS:
                    // the class currently doesn't belong to the tree
                    // instead it's instantiated as a catalog and used later
                    $className = $token->getIdentifier();
                    if( !class_exists($className) ) {
                    	throw new JKnowledgeParserException(
			                "Parsed class is not a catalog"
			            );
                    }
           			if( empty($this->_className) ){
                    	$this->_className = $className;
           			}
           			$catalog = JKnowledgeDSL::getInstance($className);
                    break;
                default:
                    // The tokenizer returned a grammar component we can't parse
                    throw new JKnowledgeParserException(
                        "Unknown token type " . $token->getType()
                    );
            }
        }
        
        if (!$catalog instanceof JKnowledgeDSLInterface) {
            throw new JKnowledgeParserException(
                "Parsed class is not a DSL"
            );
        }
        
        $catalog->getList();
        
        // execute each method in the class with its parameters
        foreach ($parseTree as $node) {
            // the returning object becomes the new context for further methods
            $catalog = call_user_func_array(array($catalog, $node['identifier']),$node['parameters']);
        }
        
        return $catalog;
    }
    
    public function getLastDSLSelected()
    {
    	return $this->_tokenizer->lastSelectedDSL();
    }
    
    public function getClassName()
    {
    	return $this->_className;
    }
}