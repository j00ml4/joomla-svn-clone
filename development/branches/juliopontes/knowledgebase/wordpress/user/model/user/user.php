<?php

class WordpressUserModelUser extends JKnowledgeModel
{
	/**
	 * Table from link catalog
	 * 
	 * @var	String
	 */
	protected $_table = 'users';
	
	protected $_pk = 'ID';
	
	protected $_fields = 'ID, user_login, user_nicename, user_email, user_url, user_registered, user_activation_key, user_status, display_name';

	protected $_count = 'count(ID)';
	/**
	 * Reference create auto JOIN reletion between catalogs
	 * 
	 * @var	Array
	 */
	protected $_referenceMap = array();	
}