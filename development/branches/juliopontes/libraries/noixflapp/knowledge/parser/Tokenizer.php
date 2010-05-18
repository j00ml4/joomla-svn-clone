<?php
/**
 * @version		$Id: tokenizer.php 14583 2010-05-16 07:16:48Z joomila $
 * @package		NoixFLAPP.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */

require_once 'exception.php';
require_once 'token.php';

/**
 * Tokenizer parser
 * 
 * Tokenizer will validate knowledge string
 * 
 * @package		NoixFLAPP.Framework
 * @subpackage 	knowledge parser
 * @author 		Julio Pontes
 * @since 		1.0
 */
class JKnowledgeParserTokenizer implements Iterator
{    
    /**
     * The query string provided by the end user
     *
     * @var string
     */
    protected $query;
    
    /**
     * The query string length
     *
     * @var int
     */
    protected $length;
    
    /**
     * The next expected token (class, method, parameter)
     *
     * @var string
     */
    protected $expecting;
    
    /**
     * The current class context
     *
     * @var string
     */
    protected $class;
    
    /**
     * When the context is changed the previous class is kept here.
     * This allows us to fallback if a method isn't found.
     * 
     * @var array 
     */
    protected $contextStack = array();
    
    /**
     * How many contexts we have (so we don't need to count it all the time)
     * 
     * @var int
     */
    protected $contextStackSize = 0;
    
    /**
     * For when we're falling back contexts
     * 
     * @var int
     */
    protected $contextStackPosition = 0;
    
    /**
     * The stop char for reading tokens
     * 
     * @var string
     */
    protected $needle;
    
    /**
     * Start position of the current token
     *
     * @var int
     */
    protected $offset;
    
    /**
     * Position of the last valid token position
     * We get back here if we need to fallback to previous context
     * 
     * @var int
     */
    protected $lastValidOffset;
    
    /**
     * When we're reading multiple-words tokens they're partialy kept here.
     * If the iteration gets over and we still have unfinished token parts we
     * throw an exception.
     *
     * @var string
     */
    protected $tokenPart;
    
    /**
     * Last selected DSL to when edit a Query you can continue
     * 
     * @var String
     */
    protected $lastSelectedDSL;
    
    /**
     * Define the user query string
     *
     * @param string $query
     * @since 1.0
     */
    public function setQuery($query)
    {
        $this->query  = trim($query);
        $this->length = strlen($this->query);
    }
    
    /**
     * Define our business grammar read from catalogs
     * TODO: change it from array to the grammar object itself 
     * 
     * @param array $grammar
     * @since 1.0
     */
    public function setGrammar(array $grammar)
    {
        $this->grammar = $grammar;
    }
    
    /**
     * Called by PHP when the iteration starts
     * Implemented from Iterator 
     *
     * @since 1.0
     */
    public function rewind()
    {
        // currently our query always starts with a catalog class
        $this->expecting = JKnowledgeParserToken::TYPE_CLASS;
        
        $this->offset = 0;
        $this->needle = ' ';
        $this->class  = null;
        $this->method = null;
        $this->tokenPart = '';
    }
    
    /**
     * Called by PHP before each iteration step as a foreach statement
     * Implemented from Iterator
     *
     * @return bool Whether or not the iteration continues
     * @since 1.0
     */
    public function valid()
    {
        // did we reach the query end?
        $valid = $this->length > $this->offset;
        
        // if we reached the query end checks if it ended correctly
        if (!$valid) {
            if( JKnowledgeParserToken::TYPE_PARAMETER == $this->expecting) {
                // we were reading a parameter (unclosed quote?)
                throw new JKnowledgeParserException(
                    "Unexpected end of query, {$this->expecting} expected"
                );
            } else if ($this->tokenPart) {
                // we couldn't match a method on this context
                // can we fallback to the previous context?
                if ( $this->contextStackPosition >= 0 ) {
                    // try again on the previous class from the last valid position
                    $this->class = $this->contextStack[$this->contextStackPosition];
                    $this->contextStackPosition--;
                    $this->offset = $this->lastValidOffset;
                    $this->tokenPart = '';
                    $valid = true;
                } else {
                    $lastValidOffset = (int) $this->lastValidOffset;
                    // there's no where to fallback to
                    throw new JKnowledgeParserException(
                        "Undefined {$this->expecting} {$this->tokenPart} at "
                      . "offset $lastValidOffset"
                    );
                }
            }
        }
        
        return $valid;
    }
    
    /**
     * Returns the current iteration token
     * Implemented from Iterator
     *
     * @return JKnowledgeParserToken
     * @since 1.0
     */
    public function current()
    {
        // we're returning a class, method or parameter
        $returningToken = $this->expecting;
        
        // this is gonna be the real class or method name
        $identifier = null;
        
        // we must loop here in order to look ahead for more tokens
        while ($this->valid()) {
            // the end of the word position
            $end = strpos($this->query, $this->needle, $this->offset);
            
            // if it's not found then consider it's til the end of the string
            if ($end === false) {
                $end = $this->length;
            }
            
            // token boundaries
            $start  = $this->offset;
            $length = $end - $start;
            
            // offset for the next token
            $this->offset = $end + 1;

            $word = substr($this->query, $start, $length);

            // consider tokens formed by multiple words
            if ($this->tokenPart) {
                $token = $this->tokenPart . ' ' . $word;
            } else {
                $token = $word;
            }
            
            // keep the token for the next iteration if needed
            $this->tokenPart = $token;

            switch ($this->expecting) {
                case JKnowledgeParserToken::TYPE_CLASS:
                    // if this class is not defined
                    if (!isset($this->grammar[$token])) {
                        // try a token with another word
                        $this->next();
                        continue 2; // 1: switch, 2: while
                    }
                    
                    // take the real class name
                    $identifier = $this->grammar[$token]['identifier'];
                    
                    // keep the class context
                    $this->class = $token;
                    $this->lastSelectedDSL = $this->class;
                    // provide the context stack so we can fallback if needed
                    $this->contextStack[] = $token;
                    $this->contextStackSize++;
                    
                    // and now we expect a method from this class
                    $this->expecting = JKnowledgeParserToken::TYPE_METHOD;
                    
                    break;
                    
                case JKnowledgeParserToken::TYPE_METHOD:
                    // if the class of this context doesn't support this method
                    if (!isset($this->grammar[$this->class]['methods'][$token])) {
                        // try a token with another word
                        $this->next();
                        continue 2; // 1: switch, 2: while
                    }
                    
                    // just an alias
                    $method =& $this->grammar[$this->class]['methods'][$token];
                    
                    // take the real method name
                    $identifier = $method['identifier'];
                    
                    // get the returning class
                    $returningClass = $method['return'];
                    
                    // if we're moving to a different context
                    if ($returningClass != $this->class) {
                    	$this->lastSelectedDSL = $returningClass;
                        // keep the old context in a stack so we can fallback
                        $this->contextStack[] = $returningClass;
                        $this->contextStackSize++;
                        
                        // flow the chain to the method returned class
                        $this->class = $returningClass;
                    }
                    
                    $this->contextStackPosition = $this->contextStackSize;
                    
                    if (empty($method['parameters'])) {
                        // no param so we expect a method from the returned class
                        $this->expecting = JKnowledgeParserToken::TYPE_METHOD;
                    } else {
                        // read parameters for this method
                        $this->expecting = JKnowledgeParserToken::TYPE_PARAMETER;
                    }
                    
                    break;
    
                case JKnowledgeParserToken::TYPE_PARAMETER:
                    if ($token[0] === '"') {
                        if ($token[$length - 1] === '"') {
                            // it's a single word within quotes
                            $token = substr($token, 1, -1);
                        } else {
                            // removes the initial quote
                            $this->tokenPart = substr($this->tokenPart, 1);

                            // match the next word til the closing quote
                            $this->needle = '"';
                            $this->next();
                            continue 2; // 1: switch, 2: while
                        }
                    } else if ($this->needle === '"') {
                        // it matched the closing quote, get back to normal
                        $this->needle = ' ';
                    } else {
                        // if it's not a quoted parameter, the single word
                        // from this token is taken as the parameter
                        // (else kept here for clearness)
                    }

                    // we matched the parameter so now we expect another method
                    $this->expecting = JKnowledgeParserToken::TYPE_METHOD;
                    
                    break;
                    
                default:
                    // something got very wrong. run to the hills!
                    throw new JKnowledgeParserException('Unexpected expectation');
            }
            
            // our token is over
            $this->tokenPart = '';

            // keep the position where the last valid token was found
            $this->lastValidOffset = $this->offset;
            
            // return it to the parser
            return new JKnowledgeParserToken($returningToken, $token, $identifier);
        }
    }
    
    /**
     * Just a dummy method for the sake of the Iterator interface
     * Implemented from Iterator
     *
     * @return int
     * @since 1.0
     */
    public function key()
    {
        return $this->offset;
    }
    
    /**
     * Called after each iteration
     * Implemented from Iterator
     *
     * @since 1.0
     */
    public function next()
    {
        // the current() method already forwards to the next offset
        // is this a problem besides being wrong?
    }
    
    /**
	 * Return last DSL at parsed string
	 * 
	 * @since 1.0
     */
    public function lastSelectedDSL()
    {
    	return $this->lastSelectedDSL;
    }
}