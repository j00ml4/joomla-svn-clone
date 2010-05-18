<?php
/**
 * Photo Info Model
 * 
 * @author Julio Pontes
 * @package Flickr Knowledge Photo Info
 * @version 1.6
 */
class FlickrPhotoModelPhoto_Info extends JKnowledgeModel
{
	protected $_fields = ' * ';
	protected $_table = 'flickr.photos.info';
	protected $_pk = 'photo_id';
}