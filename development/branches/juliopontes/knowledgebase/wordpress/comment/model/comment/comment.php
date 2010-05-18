<?php

class WordpressCommentModelComment extends JKnowledgeModel
{
	/**
	 * Table from comments catalog
	 * 
	 * @var	String
	 */
	protected $_table = '#__comments';
	
	protected $_pk = 'comment_ID';
	
	protected $_fields = 'comment_ID, comment_post_ID, comment_author, comment_author_email, comment_author_url, comment_author_IP, comment_date, comment_date_gmt, comment_content, comment_karma, comment_approved, comment_agent, comment_type, comment_parent, user_id';
	
	protected $_count = ' count(comment_ID) ';
	/**
	 * Reference create auto JOIN reletion between catalogs
	 * 
	 * @var	Array
	 */
	protected $_referenceMap = array(
		'post' => array(
            'columns'       => array( 'comment_post_ID' ),
            'refColumns'    => array( 'post_ID' ),
			'jointype' => 'left',
			'addfields' => array( 'comment_author','comment_date','comment_content' )
		)
	);	
}