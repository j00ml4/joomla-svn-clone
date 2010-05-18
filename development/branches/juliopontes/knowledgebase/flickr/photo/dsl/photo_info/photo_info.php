<?php
/**
 * Photo Info Knowledge
 * 
 * @author Julio Pontes
 * @package Flickr Knowledge Photo
 * @version 1.6
 */
class FlickrPhotoDslPhoto_Info extends JKnowledgeDSL
{
	/**
	 * Instance a Model from this DSL
	 */
	protected function _factoryModel()
	{
		return JKnowledgeModel::getInstance('FlickrPhotoModelPhoto_Info');
	}
	
	/**
	 * 
	 * @param $id
	 * @return FlickrPhotoDslPhoto_Info
	 */
	public function withId($id)
	{
		$this->JDatabaseQuery->where($this->model()->getPrimaryKey().'="'. addslashes($id) .'"');
		$this->JArrayConfig->add('photo_id',$id);
		
		return $this;
	}
	
	/**
     * Filter search term
     * 
     * @param $id
     * @return FlickrPhotoDslPhoto_Info
     */
    public function fromPhotoset($id)
    {
    	$photoSetTable = JKnowledgeModel::getInstance('FlickrPhotosetModelPhotoset');
    	$where = 'select id from '.$photoSetTable->getTableName().' where '.$photoSetTable->getPrimarykey().'='.$id;
    	$this->JDatabaseQuery->where($this->_model->getPrimaryKey().' IN ( '.$where.' )');
 		
        return $this;
    }
	
	/**
	 * Config a list for 
	 */
	public function getList()
	{
		if( is_object($this->JDatabaseQuery) ){
			$this->JDatabaseQuery->select($this->model()->getListFields());
			$this->JDatabaseQuery->from($this->model()->getTableName());
		}
		
		$string = Util::fromCamelCase(get_class($this));
		$className = end(explode(' ',$string));
		
		if( is_object($this->JArrayConfig) ){
			
			$this->JArrayConfig->add( 'method','flickr.photos.getInfo' );
		}
	}
}