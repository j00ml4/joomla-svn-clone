<?php
/**
 * Category Model
 * 
 * @author Julio Pontes
 * @package Joomla Knowledge Category
 * @version 1.6
 */
class JoomlaCategoryModelCategory extends JKnowledgeModel
{
	protected $_list = '*';
	protected $_count = 'id';
	protected $_key = 'id';
	protected $_table = '#__categories';
	protected $_referenceMap = array(
		'JoomlaBannerModelBanner' => array(
			'columns' => array( 'id'),
			'refColumns' => array( 'catid' ),
			'jointype' => 'left'
		)
	);
}