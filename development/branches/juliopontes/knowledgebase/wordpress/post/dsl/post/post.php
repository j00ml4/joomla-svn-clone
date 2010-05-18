<?php
JKnowledgeDSL::requireKnowledge( 'WordpressPostDslPost_Author','WordpressPostDslPost_Category' );

/**
 * DSL Post class
 *
 * @package		DSL
 * @since		1.0
 * @display true
 */
class WordpressPostDslPost extends JKnowledgeDSL
{
	/**
     * create a instance of Model Posts
     *
     * @return Catalog_Model
     */
	protected function _factoryModel() {
        return JKnowledgeModel::getInstance('DSL_Model_Post');
    }
    
	/**
     * Filter posts specific id
     * 
     * @param $id
     * @return DSL_Post
     */
    public function withId($id) {
    	$this->select()->where("{$this->_model->getTableName()}.{$this->_model->getPrimaryKey()} = {$id}");
 
        return $this;
    }
    
    /**
     * filter posts published
     * 
     * @return DSL_Post
     */
    public function published() {
    	$this->select()->where("{$this->_model->getTableName()}.post_status = 'publish'");
 
        return $this;
    }
    
    /**
     * filter posts pending
     * 
     * @return DSL_Post
     */
    public function pending() {
    	$this->select()->where("{$this->_model->getTableName()}.post_status = 'pending'");
 
        return $this;
    }
    
	/**
     * Filter posts with specific title
     * 
     * @param $title
     * @return DSL_Post
     */
    public function withTitle($title) {
        $this->select()->where("{$this->_model->getTableName()}.post_title = '{$title}'");
 
        return $this;
    }
    
    /**
     * Filter posts with specific title like
     * 
     * @param $title
     * @return DSL_Post
     */
    public function withTitleLike($title) {
    	$this->select()->where("{$this->_model->getTableName()}.post_title LIKE '%{$title}%'");
 
        return $this;
    }
    
    /**
     * Filter Posts from an Category
     * 
     * @return DSL_Post_Category
     */
    public function fromCategory()
    {
    	$this->select()->from('#__term_relationships');
    	$this->select()->from('#__terms');
    	
    	return $this->_reference('DSL_Post_Category',false);
    }
    
}