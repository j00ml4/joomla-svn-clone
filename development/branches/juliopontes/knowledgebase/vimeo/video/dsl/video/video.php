<?php
/**
 * Vimeo Video Knowledge
 * 
 * @author Julio Pontes
 * @package Vimeo Knowledge Video
 * @version 1.6
 */
class VimeoVideoDslVideo extends JKnowledgeDSL
{
	/**
	 * Instance a Model from this DSL
	 */
	protected function _factoryModel()
	{
		return JKnowledgeModel::getInstance('VimeoVideoModelVideo');
	}
	
	/**
     * Filter search term
     * 
     * @param $video_id
     * @return VimeoVideoDslVideo
     */
    public function withId($video_id)
    {
    	$video_id = intval($video_id);
    	$this->JDatabaseQuery->where('video_id="'. addslashes($video_id) .'"');
 		$this->JStringConfig->add($video_id.'/');
 		
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
		
		if( is_object($this->JStringConfig) ){
			$this->JStringConfig->add( 'clip/' );
		}
	}
}