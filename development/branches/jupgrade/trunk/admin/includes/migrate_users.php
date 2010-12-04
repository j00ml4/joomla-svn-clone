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

define('_JEXEC',		1);
//define('JPATH_BASE',	dirname(dirname(dirname(dirname(dirname(__FILE__))))));
define('JPATH_BASE',	dirname(__FILE__));
define('DS',			DIRECTORY_SEPARATOR);

require_once JPATH_BASE.'/defines.php';
require_once JPATH_BASE.'/jupgrade.class.php';

/**
 * Upgrade class for Users
 *
 * This class takes the users from the existing site and inserts them into the new site.
 *
 * @since	0.4.4
 */
class jUpgradeUsers extends jUpgrade
{
	/**
	 * @var		string	The name of the source database table.
	 * @since	0.4.4
	 */
	protected $source = '#__users';

	/**
	 * Get the raw data for this part of the upgrade.
	 *
	 * @return	array	Returns a reference to the source data array.
	 * @since	0.4.4
	 * @throws	Exception
	 */
	protected function &getSourceData()
	{
		$rows = parent::getSourceData(
			'*',
			null,
			'id'
		);

		// Do some custom post processing on the list.
		foreach ($rows as &$row)
		{
			$row['params'] = $this->convertParams($row['params']);

			// Remove unused fields.
			unset($row['gid']);
		}

		return $rows;
	}
}

/**
 * Upgrade class for Usergroups
 *
 * This class maps the old 1.5 usergroups to the new 1.6 system.
 *
 * @package		MatWare
 * @subpackage	com_jupgrade
 * @since		0.4.4
 */
class jUpgradeUsergroups extends jUpgrade
{
	/**
	 * @var		string	The name of the source database table.
	 * @since	0.4.4
	 */
	protected $source = '#__core_acl_aro_groups';

	/**
	 * @var		string	The name of the destination database table.
	 * @since	0.4.4
	 */
	protected $destination = '#__usergroups';

	/**
	 * Get the mapping of the old usergroups to the new usergroup id's.
	 *
	 * @return	array	An array with keys of the old id's and values being the new id's.
	 * @since	0.4.4
	 */
	public static function getUsergroupIdMap()
	{
		$map = array(
			// Old	=> // New
			28		=> 1,	// USERS
			29		=> 1,	// Public Frontend
			18		=> 2,	// Registered
			19		=> 3,	// Author
			20		=> 4,	// Editor
			21		=> 5,	// Publisher
			30		=> 1,	// Public Backend
			23		=> 6,	// Manager
			24		=> 7,	// Administrator
			25		=> 8,	// Super Administrator
		);

		return $map;
	}

	/**
	 * Get the raw data for this part of the upgrade.
	 *
	 * @return	array
	 * @since	0.4.4
	 * @throws	Exception
	 */
	protected function &getSourceData()
	{
		$rows = parent::getSourceData(
			// Custom where clause.
			// We only want to get groups that we don't know about from custom group management extensions.
			// Our assumption is the core groups have not been tampered with (if they were, Joomla would not run well).
			$this->db_old->nameQuote('id').' > 30'
		);

		// Set up the mapping table for the old groups to the new groups.
		$map = self::getUsergroupIdMap();

		// Do some custom post processing on the list.
		// The schema for old groups is: id, parent_id, name, lft, rgt, value
		// The schema for new groups is: id, parent_id, lft, rgt, title
		foreach ($rows as &$row)
		{
			// Note, if we are here, these are custom groups we didn't know about.
			if ($row['parent_id'] <= 30) {
				$row['parent_id'] = $map[$row['parent_id']];
			}

			// Use the old groups name for the new title.
			$row['title'] = $row['name'];

			// Remove unused fields.
			unset($row['name']);
			unset($row['value']);
			unset($row['lft']);
			unset($row['rgt']);
		}

		// TODO: Don't forget to do a rebuild on the groups table!

		return $rows;
	}

	/**
	 * The public entry point for the class.
	 *
	 * @return	void
	 * @since	0.4.4
	 * @throws	Exception
	 */
	public function upgrade()
	{
		if (parent::upgrade()) {
			// Rebuild the usergroup nested set values.
			$table = JTable::getInstance('Usergroup', 'JTable', array('dbo' => $this->db_new));

			if (!$table->rebuild()) {
				echo JError::raiseError(500, $table->getError());
			}
		}
	}
}

/**
 * Upgrade class for the Usergroup Map
 *
 * This translates the group mapping table from 1.5 to 1.6.
 * Group id's up to 30 need to be mapped to the new group id's.
 * Group id's over 30 can be used as is.
 * User id's are maintained in this upgrade process.
 *
 * @package		MatWare
 * @subpackage	com_jupgrade
 * @since		0.4.4
 */
class jUpgradeUsergroupMap extends jUpgrade
{
	/**
	 * @var		string	The name of the source database table.
	 * @since	0.4.4
	 */
	protected $source = '#__core_acl_groups_aro_map';

	/**
	 * @var		string	The name of the destination database table.
	 * @since	0.4.4
	 */
	protected $destination = '#__user_usergroup_map';

	/**
	 * Get the raw data for this part of the upgrade.
	 *
	 * @return	array
	 * @since	0.4.4
	 * @throws	Exception
	 */
	protected function &getSourceData()
	{
		$rows = parent::getSourceData();

		// Set up the mapping table for the old groups to the new groups.
		$groupMap = jUpgradeUsergroups::getUsergroupIdMap();

		// Set up the mapping table for the ARO id's to the new user id's.
		$userMap = $this->getUserIdAroMap();

		// Do some custom post processing on the list.
		// The schema for old group map is: group_id, section_value, aro_id
		// The schema for new groups is: user_id, group_id
		foreach ($rows as &$row)
		{
			$row['user_id'] = $userMap[$row['aro_id']];

			// Note, if we are here, these are custom groups we didn't know about.
			if ($row['group_id'] <= 30) {
				$row['group_id'] = $groupMap[$row['group_id']];
			}

			// Remove unused fields.
			unset($row['section_value']);
			unset($row['aro_id']);
		}

		return $rows;
	}

	/**
	 * Method to get a map of the User id to ARO id.
	 *
	 * @returns	array	An array of the user id's keyed by ARO id.
	 * @since	0.4.4
	 * @throws	Exception on database error.
	 */
	protected function getUserIdAroMap()
	{
		$this->db_old->setQuery(
			'SELECT id, value' .
			' FROM #__core_acl_aro' .
			' ORDER BY id'
		);

		$map	= $this->db_old->loadAssocList('id', 'value');
		$error	= $this->db_old->getErrorMsg();

		// Check for query error.
		if ($error) {
			throw new Exception($error);
		}

		return $map;
	}
}

// TODO: Maybe these should be 3 migration files.

// Migrate the users.
$u1 = new jUpgradeUsers;
$u1->upgrade();

// Migrate the usergroups.
$u2 = new jUpgradeUsergroups;
$u2->upgrade();

// Migrate the user-to-usergroup mapping.
$u2 = new jUpgradeUsergroupMap;
$u2->upgrade();

