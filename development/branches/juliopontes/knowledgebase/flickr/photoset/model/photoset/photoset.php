<?php
/**
 * Photoset Model
 * 
 * @author Julio Pontes
 * @package Flickr Knowledge Photoset
 * @version 1.6
 */
class FlickrPhotosetModelPhotoset extends JKnowledgeModel
{
	protected $_fields = ' * ';
	protected $_table = 'flickr.photosets.photos';
	protected $_pk = 'photoset_id';
}