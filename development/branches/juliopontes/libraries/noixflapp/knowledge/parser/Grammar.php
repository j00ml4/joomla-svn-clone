<?php
/**
 * @version		$Id: grammar.php 14583 2010-05-16 07:16:48Z joomila $
 * @package		NoixFLAPP.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

/**
 * Grammar representation of catalogs
 * 
 * Grammar is reponsable to get all grammar from specific domain
 * 
 * @package		NoixFLAPP.Framework
 * @subpackage 	knowledge parser
 * @author 		Julio Pontes
 * @since 		1.0
 */
class JKnowledgeParserGrammar
{
    /**
     * Catalogs represented by this grammar
     *
     * @var array DSL_Abstract
     */
    protected $catalogs;
    
    /**
     * Structure
     * 
     * @var unknown_type
     */
    protected $structure;
    
    /**
     * Add a catalog to this grammar
     *
     * @param string $catalog name of the catalog class
     */
    public function addCatalog($catalog)
    {
        $this->catalogs[$catalog] = array();   
    }
    
    /**
     * This is generic loader od DSL from application type
     * 
     * @return unknown_type
     */
	public function __construct($options)
	{
		if( !empty($options['basePath']) ){
			$this->_basePath = $options['basePath'];
		}
		else{
			$this->_basePath = dirname(__FILE__);
		}
		
		$filePaths = JFolder::files($this->_basePath,'.php',true,true,array('.svn', 'CVS','.DS_Store','__MACOSX','config.php'));
		if( !empty($filePaths) ) {
			foreach($filePaths as $filePath){
				$filePath = str_replace(JPATH_ROOT.DS.'knowledgebase','',$filePath);
				$fileName = JFile::getName( $filePath );
				$filePath = str_replace(DS,' ',str_replace($fileName,'',$filePath));
				$filePath = ucwords($filePath);
				$className = implode('', explode(' ',$filePath) );
				$arrDSL[] = $className;
			}
		}
		//JKnowledgeDsl::requireKnowledge( $arrDSL );
		
		foreach( $arrDSL as $DSL ) {
			$this->addCatalog( $DSL );
		}
	}
    
    /**
     * Returns an array representing the grammar tree
     *
     * @return array
     */
    public function getStructure()
    {
        foreach ($this->catalogs as $catalog => $structure) {
        	if( !class_exists($catalog) ){
        		JKnowledgeDsl::requireKnowledge( $catalog );
        	}
			$class = new ReflectionClass($catalog);
            
            // ignore it if it's a catalog
            if (!$class->implementsInterface('JKnowledgeDslInterface')) {
                continue;
            }
            
			$string = Util::fromCamelCase($catalog);
			
            $label = implode('_',array_slice(explode('Dsl',$catalog),1));
            $label = strtolower($label);
            
            $displayClass = null;
			// get the returning class from the phpDoc
			preg_match('/^\s*\*\s*@display\s+(\w+)/m', $class->getDocComment(), $matches);
			if (isset($matches[1])) {
				$displayClass = $matches[1];
			}
                        
            // TODO: move this structure to a specialized class
            $catalogStructure = array(
                'type'       => 'class',
                'identifier' => $catalog,
                'label'      => $label,
            	'display'	 => $displayClass,
                'methods'    => array(),
            );
            
            // list only public methods
            $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
            
            foreach ($methods as $method) {
                // don't export methods from the abstract class
                if ($method->getDeclaringClass()->getName() == 'JKnowledgeDSL') {
                    continue;
                }
        
                // get the returning class from the phpDoc
                preg_match('/^\s*\*\s*@return\s+(\w+)/m', $method->getDocComment(), $matches);
                if (isset($matches[1])) {
                    $return = $matches[1];
                } else {
                    // or assume it's the same catalog if it's available
                    $return = $catalog;
                }
                
                // FIXME: it doesn't work since the foreach has already started
                // make sure the grammar includes the returning catalog
                $this->addCatalog($return);
                
                //check extends another DSL
	            $extension = $class->getParentClass();
                if ( $extension->getName() != 'JKnowledgeDSL' && $extension->hasMethod($method->getName()) ) {
                	if( $extension->getName() == $return ) {
	                	$return = $catalog;
                	}
                }
                
                // change it from camelCase to lowercase separated words
                $label = preg_replace('/([a-z])([A-Z])/', '\1 \2', $method->getName());
                $label = strtolower($label);
                                
                // TODO: move this structure to a specialized class
                $methodStructure = array(
                    'type'       => 'method',
                    'identifier' => $method->getName(),
                    'label'      => $label,
                    'parameters' => array(),
                    'return'     => $return
                );
                 
                $parameters = $method->getParameters();
                
                foreach ($parameters as $parameter) {
                    // we don't have any structure for parameters yet
                    // so just add them as strings
                    $methodStructure['parameters'][] = $parameter->getName();
                }
                
                // when this method is configured, map it by its label
                $catalogStructure['methods'][$methodStructure['label']] = $methodStructure;
            }
            
            
            // when this class is configured, map it by its class name
            $this->structure[strtolower($catalog)] = $catalogStructure;
        }
        
        $return = array();
        
        // remap catalog classes to their labels
        // it hasn't been done earlier because we needed a hash to
        // easily access the catalogs
        foreach ($this->structure as $catalog => $structure)
        {
            foreach ($structure['methods'] as $method => $methodStructure) {
            	$this->structure[$catalog]['methods'][$method]['return'] = $this->structure[ strtolower($methodStructure['return']) ]['label'];
            }
            
            $return[$structure['label']] = $this->structure[$catalog];
        }
        
        return $return;
    }
    
    /**
     * Returns the grammar structure as a JSON encoded string
     *
     * @return string
     */
    public function getJson()
    {
        return json_encode($this->getStructure());
    }
    
    /**
     * Return the grammar structure as Array
     */
    public function getArray()
    {
    	return $this->getStructure();
    }
}