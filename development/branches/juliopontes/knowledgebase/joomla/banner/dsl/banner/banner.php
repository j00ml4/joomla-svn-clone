<?php
//include required knowledges
JKnowledgeDSL::requireKnowledge( array( 'JoomlaBannerDslBanner_Client', 'JoomlaBannerDslBanner_Category') );

/**
 * Banner Knowledge
 * 
 * @author Julio Pontes
 * @package Joomla Knowledge Banner
 * @version 1.6
 */
class JoomlaBannerDslBanner extends JKnowledgeDSL
{
	/**
	 * Instance a Model from this DSL
	 */
	protected function _factoryModel()
	{
		return JKnowledgeModel::getInstance('JoomlaBannerModelBanner');
	}
	
	/**
	 * 
	 * @param unknown_type $id
	 * @return JoomlaBannerDslBanner
	 */
	public function withId($id)
	{
		$id = intval($id);
		
		$this->JDatabaseQuery->where('id = '.$id);
		$this->JArrayConfig->add('id',$id);
		$this->JStringConfig->add('with id '.$id);
		
		return $this;
	}
	
	/**
	 * 
	 * @param String $name
	 * @return JoomlaBannerDslBanner
	 */
	public function withName($name)
	{
		$this->JDatabaseQuery->where('name = "'.$name.'"');
		$this->JArrayConfig->add('name',$name);
		$this->JStringConfig->add('with name '.$name);
		
		return $this;
	}
	
	/**
	 * 
	 * @param String $name
	 * @return JoomlaBannerDslBanner
	 */
	public function haveNameLike($name)
	{
		$this->JDatabaseQuery->where('name like "%'.$name.'%"');
		$this->JArrayConfig->add('namelike',$name);
		$this->JStringConfig->add('q','have name like'.$name);
		
		return $this;
	}
	
	/**
	 * return JoomlaBannerDslBanner
	 */
	public function published()
	{
		$this->withState(1);
		
		return $this;
	}
	
	/**
	 * return JoomlaBannerDslBanner
	 */
	public function unpublished()
	{
		$this->withState(0);
		
		return $this;
	}
	
	/**
	 * 
	 * @param $state
	 * @return JoomlaBannerDslBanner
	 */
	public function withState($state)
	{
		$this->JDatabaseQuery->where($this->model()->getTableName().'.state = '.$state);
		$this->JArrayConfig->add('state',$state);
		$this->JStringConfig->add('with state '.$state);
		
		return $this;
	}
	
	/**
	 * 
	 * @param unknown_type $metakey
	 * @return JoomlaBannerDslBanner
	 */
	public function withMetakeys($metakey)
	{
		$this->JDatabaseQuery->where('metakey like "%'.$metakey.'%"');
		$this->JArrayConfig->add('metakey',$metakey);
		$this->JStringConfig->add('metakey like '.$metakey);
		
		return $this;
	}
	
	/**
	 * 
	 * @param unknown_type $date
	 * @return JoomlaBannerDslBanner
	 */
	public function withPublishedUpDate($date)
	{
		$this->JDatabaseQuery->where('published_up = '.$date);
		$this->JArrayConfig->add('publishedupdate',$date);
		$this->JStringConfig->add('with published up date "'.$date.'"');
		
		return $this;
	}
	
	/**
	 * 
	 * @param unknown_type $date
	 * @return JoomlaBannerDslBanner
	 */
	public function withPublishedDownDate($date)
	{
		$this->JDatabaseQuery->where('published_down = '.$date);
		$this->JArrayConfig->add('publisheddowndate',$date);
		$this->JStringConfig->add('with published down date "'.$date.'"');
		
		return $this;
	}
	
	/**
	 * 
	 * @param unknown_type $startDate
	 * @param unknown_type $endDate
	 * @return JoomlaBannerDslBanner
	 */
	public function betweenPublishedDates($startDate,$endDate)
	{
		$this->JDatabaseQuery->where('published_up "%'.$metakey.'%"');
		$this->JArrayConfig->add('publisheddatebetween',$startDate.' '.$endDate);
		$this->JStringConfig->add('between published dates '.$startDate.' '.$endDate);
		
		return $this;
	}
	
	/**
	 * 
	 * @param String $lang
	 * @return JoomlaBannerDslBanner
	 */
	public function withLanguage($lang)
	{
		$this->JDatabaseQuery->where('language "'.$lang.'"');
		$this->JArrayConfig->add('q','with language '.$lang);
		
		return $this;
	}
	
	/**
	 * @return JoomlaBannerDslBanner_Client
	 */
	public function fromClient()
	{
		$this->JArrayConfig->add('useclient','true');
		$this->JStringConfig->add('from client ');
		
		return $this->_reference('JoomlaBannerDslBanner_Client');
	}
	
	/**
	 * @return JoomlaBannerDslBanner_Category
	 */
	public function fromCategory()
	{
		$this->JArrayConfig->add('usecategory','true');
		$this->JStringConfig->add('from category ');
		
		return $this->_reference('JoomlaBannerDslBanner_Category');
	}
}