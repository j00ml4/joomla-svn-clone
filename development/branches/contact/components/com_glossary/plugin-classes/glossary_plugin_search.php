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

class glossary_plugin_search {

	public function onSearch ($pluginParams, $searchword, $phrase, $ordering) {
		$limit = $pluginParams->def( 'search_limit', 50 );

		$text = trim($searchword);
		if (empty($text)) return array();

		$db = cmsapiInterface::getInstance()->getDB();
		$text = $db->getEscaped($text);
		$text = addcslashes($text, '%_');

		switch ($phrase) {
			case 'exact':
				$where = "a.tterm LIKE '%$text%' OR a.tdefinition LIKE '%$text%'";
				break;

			// Must be any or all, otherwise assume all
			default:
				$words 	= explode( ' ', $text );
				foreach ($words as $word) $wheres[] = "(a.tterm LIKE '%$word%' OR a.tdefinition LIKE '%$word%')";
				$where = implode(('any' == $phrase ? ' OR ' : ' AND '), $wheres);
				break;
		}

		switch ($ordering) {
			case 'oldest':
				$order = 'a.tdate ASC';
				break;

			case 'popular':

			case 'alpha':
				$order = 'a.tterm ASC';
				break;

			case 'category':

			case 'newest':
	            $order = 'a.tdate DESC';
	            break;

	        default:
				$order = 'a.tterm ASC';
				break;
		}

		// Perform database query and return result list

		$query = "SELECT DISTINCT "
		. "\n concat('index.php?option=com_glossary&id=', a.id ) AS href, "
	    . "\n a.tterm AS title,"
		. "\n b.name AS section,"
	    . "\n a.tdate AS created,"
	    . "\n a.tdefinition AS text,"
	    . "\n '2' AS browsernav"
	    . "\n FROM #__glossary AS a"
	    . "\n INNER JOIN #__glossaries AS b ON b.id = a.catid AND b.published = 1 "
	    . "\n WHERE ($where) AND a.published = 1"
	    . "\n ORDER BY $order"
	    ;

		return $db->doSQLget($query, 'stdClass', '', $limit );
	}
}
