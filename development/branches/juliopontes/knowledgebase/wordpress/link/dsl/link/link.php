<?php
/**
 * 
 * @author Julio Pontes
 * @display true
 * @namespace Catalog\Wordpress\
 */
class WordpressLinkDslLink extends JKnowledgeDSL
{
	/**
     * create a instance of Model Link
     *
     * @return Catalog_Model
     */
	protected function _factoryModel() {
        return JKnowledgeModel::getInstance('DSL_Model_Link');
    }
    
	/**
     * Filter comments to specific id
     * 
     * @param $id
     * @return DSL_Link
     */
    public function withId($id) {
    	$this->select()->where("{$this->_model->getTableName()}.{$this->_model->getPrimaryKey()} = {$id}");
 
        return $this;
    }
    
	/**
     * Filter comments to specific id
     * 
     * @param $url
     * @return DSL_Link
     */
    public function withURL($url) {
    	$this->select()->where("{$this->_model->getTableName()}.link_url = '{$url}'");
 
        return $this;
    }
    
	/**
     * Filter links by name
     * 
     * @param $name
     * @return DSL_Link
     */
    public function withName($name) {
    	$this->select()->where("{$this->_model->getTableName()}.link_name = '{$name}'");
 
        return $this;
    }
    
	/**
     * Filter link visibles
     * 
     * @return DSL_Link
     */
    public function visible() {
    	$this->select()->where("{$this->_model->getTableName()}.link_visible = 'Y'");
 
        return $this;
    }
    
	/**
     * Order links by name
     * 
     * @return DSL_Link
     */
    public function inOrder() {
    	$this->select()->order("{$this->_model->getTableName()}.link_name ASC");
 
        return $this;
    }
    
    /**
     * Order link by rating
     * 
     * @return DSL_Link
     */
    public function byRating()
    {
    	$this->select()->order("{$this->_model->getTableName()}.link_rating DESC");
 
        return $this;
    }
}