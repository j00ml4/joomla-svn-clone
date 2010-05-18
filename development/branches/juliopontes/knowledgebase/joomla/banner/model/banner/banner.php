<?php
/**
 * Banner Client Model
 * 
 * @author Julio Pontes
 * @package Joomla Knowledge Banner
 * @version 1.6
 */
class JoomlaBannerModelBanner extends JKnowledgeModel
{
	protected $_fields = '*';
	protected $_count = 'id';
	protected $_pk = 'id';
	protected $_table = '#__banners';
	
	/**
	 * 
	 * 
	 * @param unknown_type $rows
	 */
	public function save( $rows )
	{
		if( empty($rows) ){
			return false;
		}
		
		$tuples = array();
		
		foreach($rows as $row){
			$tupleField = array();
			foreach($row as $rowField => $rowValue)
			{
				$tupleField[] = addslashes($rowValue);
			}
			$tuples[] = '("'.implode('","',$tupleField).'")';
		}
		$fields = implode(',',array_keys(get_object_vars($rows[0])));
		$tuplesString = implode(',',$tuples).';';
		
		$query = new JDatabaseQuery();
		$query->insert($this->getTableName().'  ('.$fields.') VALUES '.$tuplesString);
		$this->_connect->setQuery((string)$query);
		
		return $this->_connect->query();
	}
}