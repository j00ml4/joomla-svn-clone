<?php

/**
 * Model Posts class
 *
 * @package		WJA
 * @since		1.0
 */
class WordpressPostModelPost extends JKnowledgeModel
{
	/**
	 * Table from posts catalog
	 * 
	 * @var	String
	 */
	protected $_table = '#__posts';
	
	protected $_pk = 'ID';
	
	protected $_fields = 'ID, post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, guid, menu_order, post_type, post_mime_type, comment_count';
	
	protected $_count = ' count(ID) ';
	
	/**
	 * Reference create auto JOIN reletion between catalogs
	 * 
	 * @var	Array
	 */
	protected $_referenceMap = array();	
}