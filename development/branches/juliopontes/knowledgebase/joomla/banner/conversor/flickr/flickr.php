<?php
class JoomlaBannerConversorFlickr extends JKnowledgeDataConversor
{
	/**
	 * Flickr Conversor Data
	 * 
	 */
	public function photo_info()
	{
		$resultData = $this->_knowledge->result();
		
		if( $this->_knowledge->getConnectorType() == 'yql' ){
			$yql = simplexml_load_string( $resultData );
			$result = $yql->results;
		}
		$rows = array();
		
		foreach($result->children() as $photo)
		{
			$metakeys = array();
			foreach($photo->tags->children() as $tag){
				$metakeys[] = (string)$tag;
			}
			
			$title = (string)$photo->title;
			if( empty($title) ){
				$title = 'photo '.$photo['id'];
			}
			
			$row = new stdClass();
			$row->name = $title;
			$row->alias = str_replace(' ','-',$title);
			$row->clickurl = (string)$photo->urls->url[0];
			$row->impmade = (string)$photo['views'];
			$row->description = (string)$photo->description;
			$row->metakey = implode(',',$metakeys);
			$row->created = (string)$photo->dates['taken'];
			$row->type = 1;
			$row->custombannercode = addslashes('<img src="http://farm'.$photo['farm'].'.static.flickr.com/'.$photo['server'].'/'.$photo['id'].'_'.$photo['secret'].'_m.jpg" alt="'.(string)$photo->title.'" />');
			
			if( !empty($this->_extraPrams) ){
				foreach($this->_extraPrams as $paramKey => $paramValue){
					$row->$paramKey = $paramValue;
				}
			}
			$rows[] = $row;
		}
		
		return $rows;
	}
}