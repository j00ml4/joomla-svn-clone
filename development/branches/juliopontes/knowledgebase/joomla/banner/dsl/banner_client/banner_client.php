<?php
/**
 * Banner Client Knowledge
 * 
 * @author Julio Pontes
 * @package Joomla Knowledge Banner
 * @version 1.6
 */
class JoomlaBannerDslBanner_Client extends JKnowledgeDSL
{
	/**
	 * 
	 * @param unknown_type $id
	 * @return JoomlaBannerDslBannerClient
	 */
	public function withId($id)
	{
		$this->JDatabaseQuery->where($this->model()->getTableName().'.id = '.$id);
		$this->JArrayConfig->add('id',$id);
		$this->JStringConfig->add('with id '.$id);
		
		return $this;
	}
	
	/**
	 * 
	 * @param unknown_type $name
	 * @return JoomlaBannerDslBannerClient
	 */
	public function withName($name)
	{
		$this->JDatabaseQuery->where($this->model()->getTableName().'.name = '.$name);
		$this->JArrayConfig->add('clientName',$name);
		$this->JStringConfig->add('with name '.$name);
		
		return $this;
	}
	
	/**
	 * 
	 * @param unknown_type $name
	 * @return JoomlaBannerDslBannerClient
	 */
	public function haveNameLike($name)
	{
		$this->JDatabaseQuery->where($this->model()->getTableName().'.name LIKE "%'.$name.'%"');
		$this->JArrayConfig->add('namelike',$name);
		$this->JStringConfig->add('have name like '.$name);
		
		return $this;
	}
	
	/**
	 * 
	 * @param unknown_type $email
	 * @return JoomlaBannerDslBannerClient
	 */
	public function withEmail($email)
	{
		$this->JDatabaseQuery->where($this->model()->getTableName().'.email = "'.$email.'"');
		$this->JArrayConfig->add('withEmail',$email);
		$this->JStringConfig->add('with email '.$email);
		
		return $this;
	}
}