<?php
/**
 * @version		$Id$
 * @package		Joomla
 * @subpackage	Content
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class plgContentKeyword extends JPlugin
{
	static $_map_table = '#__content_keyword_article_map';
	static $_authorTag = 'authid::';
	static $_aliasTag = 'alias::';
	static $_categoryTag = 'catid::';

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param object $subject The object to observe
	 * @param object $params  The object that holds the plugin parameters
	 * @since 1.5
	 */
	function plgContentKeyword ( &$subject, $params )
	{
		parent::__construct( $subject, $params );
	}

	/**
	 * Example before save content method
	 *
	 * Method is called right before content is saved into the database.
	 * Article object is passed by reference, so any changes will be saved!
	 * NOTE:  Returning false will abort the save with an error.
	 * 	You can set the error by calling $article->setError($message)
	 *
	 * @param 	object		A JTableContent object
	 * @param 	bool		If the content is just about to be created
	 * @return	bool		If false, abort the save
	 */
	function onAfterContentSave( $article, $isNew )
	{
		global $mainframe;
		$db	=& JFactory::getDBO();
		// delete the old rows
		self::_deleteOldRows($article->id);
		$result = true;
		if ($article->metakey)
		{
			$keyArray = explode(',', $article->metakey);
			$keysInserted = array();
			foreach ($keyArray as $thisKey)
			{
				$thisKey = trim($thisKey);
				if (!in_array(strtoupper($thisKey), $keysInserted))
				{
					$object = new KeywordMapRow($thisKey, $article->id);
					$result = ($db->insertObject(self::$_map_table, $object) && $result);
					$keysInserted[] = strtoupper($thisKey);
				}
			}
		}
		// insert author, author alias, and category rows into keyword table
		$object = new KeywordMapRow(self::$_categoryTag . $article->catid, $article->id);
		$db->insertObject(self::$_map_table, $object);
		$object = new KeywordMapRow(self::$_authorTag . $article->created_by, $article->id);
		$db->insertObject(self::$_map_table, $object);
		if ($article->created_by_alias)
		{
			$object = new KeywordMapRow(self::$_aliasTag . $article->created_by_alias, $article->id);
			$result = ($db->insertObject(self::$_map_table, $object) && $result);
		}
		return $result;
	}

	static function _deleteOldRows($id) {
		global $mainframe;
		$db	=& JFactory::getDBO();
		$query = 'DELETE FROM '. self::$_map_table .
				' WHERE article_id = ' . $db->Quote($id);
		$db->setQuery($query);
		if ($db->query($query)) {
			$result = true;
		} else {
			$result = false;
		}
		return $result;
	}
}

class KeywordMapRow {

	public $keyword = '';
	public $article_id = '';

	function KeywordMapRow ($keyword, $id) {
		$this->keyword = $keyword;
		$this->article_id = $id;
	}

}


