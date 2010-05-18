<?php

JKnowledgeDSL::requireKnowledge( 'WordpressUserDslUser' );

/**
 * Added method AndPost() to back to scope of DSL_POST
 * 
 * @author Julio Pontes
 * @namespace Catalog\Wordpress\
 */
class WordpressPostDslPost_Author extends WordpressUserDslUser
{
    /**
     * Change reference to Content
     * 
     * @return DSL_Post
     */
	public function andPost() {
		return $this->_reference('DSL_Post',false);
    }
}