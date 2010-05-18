<?php
/**
 * Category Knowledge
 * 
 * @author Julio Pontes
 * @package Wordpress Knowledge Category
 * @version 2.9.2
 */
class WordpressCategoryDslCategory extends JKnowledgeDSL
{
	/**
     * create a instance of Model Categories
     *
     * @return Catalog_Model
     */
	protected function _factoryModel() {
        return JKnowledgeModel::getInstance('DSL_Model_Category');
    }
    
	/**
     * Filter comments to specific id
     * 
     * @param $id
     * @return WordpressCategoryDslCategory
     */
    public function withId($id) {
    	$this->select()->where("{$this->model()->getTableName()}.{$this->_model->getPrimaryKey()} = {$id}");
 
        return $this;
    }
    
    /**
     * 
     * @param $categoryName
     * @return WordpressCategoryDslCategory
     */
    public function withName($categoryName) {
    	$this->select()->where("{$this->_model->getTableName()}.name = {$categoryName}");
 
        return $this;
    }
}