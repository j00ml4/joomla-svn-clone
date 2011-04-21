<?php

/**************************************************************
* This file is part of Glossary
* Copyright (c) 2008-9 Martin Brampton
* Issued as open source under GNU/GPL
* For support and other information, visit http://remository.com
* To contact Martin Brampton, write to martin@remository.com
*
* Please see glossary.php for more details
*/

class glossaryGlossary extends cmsapiDatabaseRow {
	protected $tableName = '#__glossaries';
	protected $rowKey = 'id';

	public function store ($updateNulls=false) {
		parent::store($updateNulls);
		glossaryGlossaryManager::getInstance()->clearCache(true);
	}
}