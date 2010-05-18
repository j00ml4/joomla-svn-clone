<?php
/**
 * 
 * @author Julio Pontes
 * @display true
 * @namespace Catalog\Wordpress\
 */
class WordpressCommentDslComment extends JKnowledgeDSL
{
	/**
     * create a instance of Model Comments
     *
     * @return Catalog_Model
     */
	protected function _factoryModel() {
        return JKnowledgeModel::getInstance('WordpressCommentModelComment');
    }
    
	/**
     * Filter comments to specific id
     * 
     * @param $id
     * @return WordpressCommentDslComment
     */
    public function withId($id) {
    	$this->select()->where("{$this->_model->getTableName()}.{$this->_model->getPrimaryKey()} = {$id}");
 
        return $this;
    }
    
    /**
     * Filter comments by author name
     * 
     * @param $authorName
     * @return WordpressCommentDslComment
     */
    public function fromAuthorName($authorName) {
    	$authorName = '"'.$authorName.'"';
    	$this->select()->where("{$this->_model->getTableName()}.comment_author = {$authorName}");
 
        return $this;
    }
    
    /**
     * Filter comments by url
     * 
     * @param $ahutorUrl
     * @return WordpressCommentDslComment
     */
    public function withContainURL($ahutorUrl) {
        $this->select()->where("{$this->_model->getTableName()}.comment_url = {$ahutorUrl}");
 
        return $this;
    }

}