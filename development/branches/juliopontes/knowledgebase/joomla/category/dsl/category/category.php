<?php
/**
 * Category Knowledge
 * 
 * @author Julio Pontes
 * @package Joomla Knowledge Banner
 * @version 1.6
 */

class JoomlaCategoryDslCategory extends JKnowledgeDSL
{
	/**
	 * Instance a Model from this DSL
	 */
	protected function _factoryModel()
	{
		return JKnowledgeModel::getInstance('JoomlaCategoryModelCategory');
	}
	
	/**
	 * 
	 * @param Interger $id
	 * @return JoomlaCategoryDslCategory
	 */
	public function withId($id)
	{
		$id = intval($id);
		
		$this->JDatabaseQuery->where($this->model()->getTableName().'.id = '.$id);
		$this->JArrayConfig->add('catid',$id);
		$this->JStringConfig->add('with id '.$id);
		
		return $this;
	}
	
	/**
	 * 
	 * @param String $title
	 * @return JoomlaCategoryDslCategory
	 */
	public function withTitle($title)
	{
		$this->JDatabaseQuery->where($this->model()->getTableName().'.title = "'.$title.'"');
		$this->JArrayConfig->add('title',$title);
		$this->JStringConfig->add('with title '.$title);
		
		return $this;
	}
	
	/**
	 * 
	 * @param String $title
	 * @return JoomlaCategoryDslCategory
	 */
	public function haveTitleLike($title)
	{
		$this->JDatabaseQuery->where($this->model()->getTableName().'.title LIKE "%'.$title.'%"');
		$this->JArrayConfig->add('titlelike',$title);
		$this->JStringConfig->add('have title like '.$title);
		
		return $this;
	}
	
	/**
	 * 
	 * @param unknown_type $title
	 * @return JoomlaCategoryDslCategory
	 */
	public function isChildOf($title)
	{
		$this->JDatabaseQuery->where($this->model()->getTableName().'.title = "'.$title.'"');
		$this->JArrayConfig->add('child',$title);
		$this->JStringConfig->add('is child of '.$title);
		
		return $this;
	}
	
	/**
	 * @return JoomlaCategoryDslCategory
	 */
	public function published()
	{
		$this->JDatabaseQuery->where($this->model()->getTableName().'.published = 1');
		$this->JArrayConfig->add('published',1);
		$this->JStringConfig->add('published');
		
		return $this;
	}
	
	/**
	 * @return JoomlaCategoryDslCategory
	 */
	public function unpublished()
	{
		$this->JDatabaseQuery->where($this->model()->getTableName().'.published = 0');
		$this->JArrayConfig->add('published',0);
		$this->JStringConfig->add('unpublished');
		
		return $this;
	}
	
	/**
	 * @return JoomlaCategoryDslCategory
	 */
	public function archived()
	{
		$this->JDatabaseQuery->where($this->model()->getTableName().'.title = "'.$title.'"');
		$this->JArrayConfig->add('published',-1);
		$this->JStringConfig->add('archived');
		
		return $this;
	}
	
	/**
	 * @return JoomlaCategoryDslCategory
	 */
	public function trash()
	{
		$this->JDatabaseQuery->where($this->model()->getTableName().'.published = -2');
		$this->JArrayConfig->add('published',-2);
		$this->JStringConfig->add('trash');
		
		return $this;
	}
	
	/**
	 * 
	 * @param unknown_type $extension
	 * @return JoomlaCategoryDslCategory
	 */
	public function fromExtension($extension)
	{
		$this->JDatabaseQuery->where($this->model()->getTableName().'.extension = "'.$extension.'"');
		$this->JArrayConfig->add('extension',$extension);
		$this->JStringConfig->add('from extension '.$extension);
		
		return $this;
	}
	
	/**
	 * @return JoomlaCategoryDslCategory
	 */
	public function inOrder()
	{
		$this->JDatabaseQuery->order($this->model()->getTableName().'.ordering ASC');
		$this->JArrayConfig->add('inorder','true');
		$this->JStringConfig->add('in order');
		
		return $this;
	}
}