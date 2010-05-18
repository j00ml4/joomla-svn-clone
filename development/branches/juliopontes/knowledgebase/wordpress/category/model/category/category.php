<?php

class WordpressCategoryModelCategory extends JKnowledgeModel
{
	/**
	 * Table from categories catalog
	 * 
	 * @var	String
	 */
	protected $_table = '#__terms';
	
	protected $_pk = 'term_id';
	
	protected $_fields = 'term_id, name, slug, term_group';

	protected $_count = 'count(term_id)';
	
	/**
	 * Reference create auto JOIN reletion between catalogs
	 * 
	 * @var	Array
	 */
	protected $_referenceMap = array();	
}