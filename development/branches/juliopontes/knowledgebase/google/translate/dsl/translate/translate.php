<?php
/**
 * Google Translate Knowledge using YQL
 * 
 * @author Julio Pontes
 * @package Google Knowledge Translate
 * @version 1.6
 */
class GoogleTranslateDslTranslate extends JKnowledgeDSL
{
	/**
	 * Instance a Model from this DSL
	 */
	protected function _factoryModel()
	{
		return JKnowledgeModel::getInstance('GoogleTranslateModelTranslate');
	}
    
	/**
     * Filter search term
     * 
     * @param $text
     * @return GoogleTranslateDslTranslate
     */
    public function text($text) {
    	$this->JDatabaseQuery->where('q="'. addslashes($text) .'"');
 		
        return $this;
    }
    
    /**
     * Specific translate to language
     * 
     * @param $name
     * @return GoogleTranslateDslTranslate
     */
    public function toLanguage($name)
    {
    	$this->JDatabaseQuery->where('target="'. addslashes($name) .'"');
    	
    	return $this;
    }
}