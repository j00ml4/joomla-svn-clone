<?php
/**
 * jUpgrade
 *
 * @version		  $Id$
 * @package		  MatWare
 * @subpackage	com_jupgrade
 * @author      Matias Aguirre <maguirre@matware.com.ar>
 * @link        http://www.matware.com.ar
 * @license		  GNU General Public License version 2 or later; see LICENSE.txt
 */

define('_JEXEC',		1);
define('JPATH_BASE',	dirname(__FILE__));
define('DS',			DIRECTORY_SEPARATOR);

require_once JPATH_BASE.DS.'defines.php';
require_once JPATH_BASE.DS.'jupgrade.class.php';

/**
 * Upgrade class for content
 *
 * This class takes the content from the existing site and inserts them into the new site.
 *
 * @since	0.4.5
 */
class jUpgradeContent extends jUpgrade
{
	/**
	 * @var		string	The name of the source database table.
	 * @since	0.4.5
	 */
	protected $source = '#__content AS c';

	/**
	 * @var		string	The name of the destination database table.
	 * @since	0.4.5
	 */
	protected $destination = '#__content';

	/**
	 * Get the raw data for this part of the upgrade.
	 *
	 * @return	array	Returns a reference to the source data array.
	 * @since	0.4.5
	 * @throws	Exception
	 */
	protected function &getSourceData()
	{
		$rows = parent::getSourceData(
			'`title`, NULL AS `alias`, `title_alias`, `introtext`, `fulltext`, `state`, '
		 .'`sectionid`, `mask`, o.new AS catid, `created`, `created_by`, `created_by_alias`, '
		 .'`modified`, `modified_by`, `checked_out`, `checked_out_time`, `publish_up`, `publish_down`, '
		 .'`images`, `urls`, `attribs`, `version`, `parentid`, `ordering`, `metakey`, `metadesc`, `access`, `hits`, NULL',
		 'LEFT JOIN j16_jupgrade_categories AS o ON o.old = c.catid',
			null,
			'id'
		);

		// Do some custom post processing on the list.
		foreach ($rows as &$row)
		{
			$row['attribs'] = $this->convertParams($row['attribs']);
			$row['access'] = $row['access']+1;
			$row['language'] = '*';
		}

		return $rows;
	}
}

// Migrate the Content.
$content = new jUpgradeContent;
$content->upgrade();

?>
