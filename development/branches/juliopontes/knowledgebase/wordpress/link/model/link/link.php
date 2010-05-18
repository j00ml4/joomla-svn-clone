<?php

class WordpressLinkModelLink extends JKnowledgeModel
{
	/**
	 * Table from link catalog
	 * 
	 * @var	String
	 */
	protected $_table = '#__links';
	
	protected $_pk = 'link_id';
	
	protected $_fields = 'link_id, link_url, link_name, link_image, link_target, link_description, link_visible, link_owner, link_rating, link_updated, link_rel, link_notes, link_rss';

	protected $_count = 'count(link_id)';
	/**
	 * Reference create auto JOIN reletion between catalogs
	 * 
	 * @var	Array
	 */
	protected $_referenceMap = array();	
}