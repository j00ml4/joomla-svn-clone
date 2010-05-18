<?php
JKnowledgeDSL::requireKnowledge( 'WordpressCategoryDslCategory' );

/**
 * Added method AndPost() to back to scope of DSL_POST
 * 
 * @author Julio Pontes
 * @namespace Catalog\Wordpress\
 */
class WordpressPostDslPost_Category extends WordpressCategoryDslCategory
{
    /**
     * Change reference to Content
     * 
     * @return WordpressPostDslPost
     */
	public function andPost() {
    	return $this->_reference('DSL_Post',false);
    }
}