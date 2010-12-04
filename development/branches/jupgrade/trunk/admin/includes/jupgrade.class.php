<?php
/**
 * jUpgrade
 *
 * @version		$Id$
 * @package		MatWare
 * @subpackage	com_jupgrade
 * @author      Matias Aguirre <maguirre@matware.com.ar>
 * @link        http://www.matware.com.ar
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

// Make sure we can see all errors.
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * jUpgrade utility class for migrations
 *
 * @package		MatWare
 * @subpackage	com_jupgrade
 */
class jUpgrade
{
	/**
	 * @var		string	The name of the source database table.
	 * @since	0.4.
	 */
	protected $source = null;

	public $config = array();
	public $db_old = null;
	public $db_new = null;

	function __construct()
	{
		// Base includes
		require_once JPATH_LIBRARIES.'/joomla/import.php';
		require_once JPATH_LIBRARIES.'/joomla/methods.php';
		require_once JPATH_LIBRARIES.'/joomla/factory.php';
		require_once JPATH_LIBRARIES.'/joomla/import.php';
		require_once JPATH_LIBRARIES.'/joomla/config.php';
		require_once JPATH_ROOT.'/jupgrade/configuration.php';

		// Base includes
		jimport('joomla.base.object');
		jimport('joomla.base.adapter');

		// Application includes
		jimport('joomla.application.helper');
		jimport('joomla.application.application');
		jimport('joomla.application.component.modellist');

		// Error includes
		jimport('joomla.error.error');
		jimport('joomla.error.exception');

		// Database includes
		jimport('joomla.database.database');
		jimport('joomla.database.table');
		jimport('joomla.database.tablenested');
		jimport('joomla.database.table.asset');
		jimport('joomla.database.table.category');

		// Update and installer includes for 3rd party extensions
		jimport('joomla.installer.installer');
		jimport('joomla.updater.updater');
		jimport('joomla.updater.update');

		// Other stuff
		jimport('joomla.utilities.string');
		jimport('joomla.filter.filteroutput');
		jimport('joomla.html.parameter');

		// Echo all errors, otherwise things go really bad.
		JError::setErrorHandling(E_ALL, 'echo');

		// Manually
		//JTable::addIncludePath(JPATH_LIBRARIES.'/joomla/database/table');

		$jconfig = new JConfig();

		$this->config['driver']   = 'mysql';
		$this->config['host']     = $jconfig->host;
		$this->config['user']     = $jconfig->user;
		$this->config['password'] = $jconfig->password;
		$this->config['database'] = $jconfig->db;
		$this->config['prefix']   = $jconfig->dbprefix;
		//print_r($config);
		$config_old = $this->config;
		$config_old['prefix'] = "jos_";

		$this->db_new = JDatabase::getInstance($this->config);
		$this->db_old = JDatabase::getInstance($config_old);
	}

	/**
	 * Converts the params fields into a JSON string.
	 *
	 * @param	string	$params	The source text definition for the parameter field.
	 *
	 * @return	string	A JSON encoded string representation of the parameters.
	 * @since	0.4.
	 * @throws	Exception from the convertParamsHook.
	 */
	protected function convertParams($params)
	{
		$temp	= new JParameter($params);
		$object	= $temp->toObject();

		// Fire the hook in case this parameter field needs modification.
		$this->convertParamsHook($object);

		return json_encode($object);
	}

	/**
	 * A hook to be able to modify params prior as they are converted to JSON.
	 *
	 * @param	object	$object	A reference to the parameters as an object.
	 *
	 * @return	void
	 * @since	0.4.
	 * @throws	Exception
	 */
	protected function convertParamsHook(&$object)
	{
		// Do customisation of the params field here for specific data.
	}

	/**
	 * Get the raw data for this part of the upgrade.
	 *
	 * @param	string 	$select	A select condition to add to the query.
	 * @param	string 	$join	 A select condition to add to the query.
	 * @param	string	$where	A where condition to add to the query.
	 * @param	string	$order	The ordering for the source data.
	 *
	 * @return	array	Returns a reference to the source data array.
	 * @since	0.4.
	 * @throws	Exception
	 */
	protected function &getSourceData($select = '*', $join = null, $where = null, $order = null)
	{
		// Error checking.
		if (empty($this->source)) {
			throw new Exception('Source table not specified.');
		}

		// Prepare the query for the source data.
		$query = $this->db_old->getQuery(true);

		$query->select($select);
		$query->from($this->source);

		// Check if 'where' clause is set
		if (!empty($where))
			$query->where($where);

		// Check if 'join' clause is set 
		if (!empty($join) && strpos($join, 'JOIN') !== false)
		{
			$pieces = explode("JOIN", $join);
			$type = trim($pieces[0]);
			$conditions = trim($pieces[1]);

			$query->join($type, $conditions);
		}

		// Check if 'order' clause is set
		if (!empty($order))
			$query->order($this->db_old->nameQuote($order));

		$this->db_old->setQuery((string)$query);

		// Getting data
		$rows	= $this->db_old->loadAssocList();
		$error	= $this->db_old->getErrorMsg();

		// Check for query error.
		if ($error) {
			throw new Exception($error);
		}

		return $rows;
	}

	/**
	 * Sets the data in the destination database.
	 *
	 * @return	void
	 * @since	0.4.
	 * @throws	Exception
	 */
	protected function setDestinationData()
	{
		// Get the source data.
		$rows	= $this->getSourceData();
		$table	= empty($this->destination) ? $this->source : $this->destination;

		// TODO: this is ok for proof of concept, but add some batching for more efficient inserting.
		foreach ($rows as $row)
		{
			// Convert the array into an object.
			$row = (object) $row;

			if (!$this->db_new->insertObject($table, $row)) {
				throw new Exception($this->db_new->getErrorMsg());
			}
		}
	}

	/**
	 * The public entry point for the class.
	 *
	 * @return	boolean
	 * @since	0.4.
	 */
	public function upgrade()
	{
		try
		{
			$this->setDestinationData();
		}
		catch (Exception $e)
		{
			echo JError::raiseError(500, $e->getMessage());

			return false;
		}

		return true;
	}

	/**
	 * Inserts a category
	 *
	 * @access  public
	 * @param   object  An object whose properties match table fields
	 * @since	0.4.
	 */
	public function insertCategory($object, $parent)
	{
		
		// Get data for category
		$query = "SELECT rgt FROM #__categories"
		." WHERE title = 'ROOT' AND extension = 'system'"
		." LIMIT 1";
		$this->db_new->setQuery($query);
		$lft = $this->db_new->loadResult();

		$rgt = $lft+1;
		$title = $object->title;
		$alias = $object->alias;
		$published = $object->published;
		$access = $object->access + 1;
		$extension = $object->section;


		// Correct extension
		if (is_numeric($extension) || $extension == "") {
			$extension = "com_content";
		}
		if ($extension == "com_banner") {
			$extension = "com_banners";
		}
		if ($extension == "com_contact_detail") {
			$extension = "com_contact";
		}

		// Get parent
		if ($parent != "") {
			$path = JFilterOutput::stringURLSafe($parent)."/".$alias;

			$query = "SELECT id FROM #__categories WHERE title = '{$parent}' LIMIT 1";
			$this->db_new->setQuery($query);
			$parent = $this->db_new->loadResult();
			//echo $this->db_new->getError();

			$level = 2;
			$old = $object->id;
		}
		else {
			$parent = 1;
			$level = 1;
			$path = $alias;
			$old = 0;
		}

		// Insert Category
		$query = "INSERT INTO #__categories"
		." (`parent_id`,`lft`,`rgt`,`level`,`path`,`extension`,`title`,`alias`,`published`, `access`, `language`)"
		." VALUES({$parent}, {$lft}, {$rgt}, {$level}, '{$path}', '{$extension}', '{$title}', '{$alias}', {$published}, {$access}, '*') ";
		$this->db_new->setQuery($query);
		$this->db_new->query();	echo $this->db_new->getError();
		$new = $this->db_new->insertid();

		// Update ROOT rgt
		$query = "UPDATE #__categories SET rgt=rgt+2"
		." WHERE title = 'ROOT' AND extension = 'system'";
		$this->db_new->setQuery($query);
		$this->db_new->query();	echo $this->db_new->getError();

		// Save old id and new id
		$query = "INSERT INTO #__jupgrade_categories"
		." (`old`,`new`)"
		." VALUES({$old}, {$new}) ";
		$this->db_new->setQuery($query);
		$this->db_new->query();

	 	return true;
	}

	/**
	 * Inserts asset
	 *
	 * @since	0.4.
	 * @access  public
	 */
	public function insertAsset($parent) {

		/*
		 * Get parent
		 */
		if ($parent != false) {
			$query = "SELECT id FROM #__assets WHERE title = '{$parent}' LIMIT 1";
			$this->db_new->setQuery($query);
			$parent = $this->db_new->loadResult();
			$level = 3;
		}
		else {
			$parent = 1;
			$level = 2;
		}

		/*
		 * Get data for asset
	 	 * @since	0.4.
		 */
		$query = "SELECT id FROM #__categories ORDER BY id DESC LIMIT 1";
		$this->db_new->setQuery($query);
		$cid = $this->db_new->loadResult();

		$query = "SELECT title FROM #__categories ORDER BY id DESC LIMIT 1";
		$this->db_new->setQuery($query);
		$title = $this->db_new->loadResult();

		$query = "SELECT rgt+1 FROM #__assets WHERE name LIKE 'com_content.category%'"
		." ORDER BY lft DESC LIMIT 1";
		$this->db_new->setQuery($query);
		$lft = $this->db_new->loadResult();
		if (!isset($lft)) {
			$lft = 34;
		}

		$rgt = $lft+1;
		$name = "com_content.category.{$cid}";
		$rules = '{"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}';

		// Update lft & rgt > cat
		$query = "UPDATE #__assets SET lft=lft+2"
		." WHERE lft >= {$lft}";
		$this->db_new->setQuery($query);
		$this->db_new->query();	echo $this->db_new->getError();

		$query = "UPDATE #__assets SET rgt=rgt+2"
		." WHERE rgt >= {$rgt}";
		$this->db_new->setQuery($query);
		$this->db_new->query();	echo $this->db_new->getError();

		/*
		 * Insert Asset
		 */
		$query = "INSERT INTO #__assets"
		." (`parent_id`,`lft`,`rgt`,`level`,`name`,`title`,`rules`)"
		." VALUES({$parent}, {$lft}, {$rgt}, {$level}, '{$name}', '{$title}', '{$rules}') ";
		$this->db_new->setQuery($query);
		$this->db_new->query();	echo $this->db_new->getError();
		//echo $query . "<br>";

		// Setting the asset id to category
		$query = "SELECT id FROM #__assets ORDER BY id DESC LIMIT 1";
		$this->db_new->setQuery($query);
		$assetid = $this->db_new->loadResult();

		$query = "UPDATE #__categories SET asset_id={$assetid}"
		." WHERE id = {$cid}";
		$this->db_new->setQuery($query);
		$this->db_new->query();	echo $this->db_new->getError();


		return true;
	}

	/**
	 * Insert an entire objectList
	 *
	 * @return	error?
	 * @since	0.4.
	 * @throws	Exception
	 */
	public function insertObjectList($db, $table, &$object, $keyName = NULL)
	{
		$count = count($object);

		for ($i=0; $i<$count; $i++)
		{
			$db->insertObject($table, $object[$i]);
			$ret = $db->getErrorMsg();
		}

		return $ret;
	}

	/**
	 * Internal function to debug
	 *
	 * @return	a better version of print_r
	 * @since	0.4.5
	 * @throws	Exception
	 */
	public function print_a($subject){
		echo str_replace("=>","&#8658;",str_replace("Array","<font color=\"red\"><b>Array</b></font>",nl2br(str_replace(" "," &nbsp; ",print_r($subject,true)))));
	}

}
