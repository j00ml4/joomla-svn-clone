<?php
/**
 * @version $Id: modules.class.php,v 1.2 2005/08/27 15:13:19 pasamio Exp $
 * @package Mambo
 * @subpackage Modules
 * @copyright (C) 2000 - 2005 Miro International Pty Ltd
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * Mambo is Free Software
 */

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Template factory class
 * @package Mambo
 * @subpackage Modules
 */
class mosModuleFactory {
	/**
	 * @return object A module installer object
	 */
	function &createInstaller() {
		mosFS::load( '#mambo.installers' );
		return mosInstallerFactory::createClass('module');
	}
}

/**
 * @package Mambo
 * @subpackage Modules 
 */
class mosModuleViews extends mosModule {
	/**
	 * Retrieves a data view.
	 * @param string The view name: items|positions|modules
	 * @param array
	 * @param boolean
	 * @return mixed array of results or count
	 */
	function getView( $view, $options=array(), $countOnly=false ) {
		global $my;

		$wheres = array();

		// Search
		if ($search = mosGetParam( $options, 'search' )) {
			$wheres[] = "LOWER( m.name ) LIKE '%$search%'";
		}

		// Differentiate between Site & Admin
		if (($val = mosGetParam( $options, 'client' )) !== null) {
			$wheres[] = 'm.client_id = ' . $this->_db->Quote( $val );
		}

		// Filtering
		if ($val = mosGetParam( $options, 'position' )) {
			$wheres[] = 'm.position = ' . $this->_db->Quote( $val );
		}
		if ($val = mosGetParam( $options, 'module' )) {
			if ($val == 'Custom Modules') {
				$val = '';
			}
			$wheres[] = 'm.module = ' . $this->_db->Quote( $val );
		}
		if ($val = mosGetParam( $options, 'state' )) {
			$wheres[] = 'm.published = ' . $this->_db->Quote( $val );
		}
		if ($val = mosGetParam( $options, 'access' )) {
			$wheres[] = 'm.access = ' . $this->_db->Quote( $val );
		}
		
		switch ($view) {
			case 'items':
				$where = (count( $wheres ) > 0 ? "\n WHERE " . implode( ' AND ', $wheres ) : '');
				if ($countOnly) {
					$query = "SELECT COUNT( m.id )"
					. "\n FROM #__modules AS m "
					. $where
					;
					$this->_db->setQuery( $query );

					return $this->_db->loadResult();
				}

				$orderby = mosGetParam( $options, 'orderby' );

				if (empty( $orderby )) {
					$orderby = 'm.ordering';
				} else {
					$orderby .= ', m.ordering';
				}

				$query = "SELECT m.*, u.name AS editor, g.name AS groupname, COUNT( mm.menuid ) AS pages"
				. "\n FROM #__modules AS m"
				. "\n LEFT JOIN #__users AS u ON u.id = m.checked_out"
				. "\n LEFT JOIN #__groups AS g ON g.id = m.access"
				. "\n LEFT JOIN #__modules_menu AS mm ON mm.moduleid = m.id"
				. $where
				. "\n GROUP BY m.id"
				. ($orderby ? "\n ORDER BY " . $orderby : '')
				;
				break;

			case 'positions':
				$wheres[] 	= "m.position <> ''";
				$where 		= ( count( $wheres ) > 0 ? "\n WHERE " . implode( ' AND ', $wheres ) : '' );

				$query = "SELECT t.position AS value, t.position AS text"
				. "\n FROM #__template_positions as t"
				. "\n LEFT JOIN #__modules AS m ON m.position = t.position"
				. $where
				. "\n GROUP BY t.position"
				. "\n ORDER BY t.position"
				;
				break;

			case 'modules':
				$wheres[] 	= "m.module <> ''";
				$where 		= ( count( $wheres ) > 0 ? "\n WHERE " . implode( ' AND ', $wheres ) : '' );
				
				$query = "SELECT m.module AS value, m.module AS text"
				. "\n FROM #__modules AS m"
				. $where
				. "\n GROUP BY m.module"
				. "\n ORDER BY m.module"
				;
				break;

			case 'noncore':
				$query = "SELECT *"
				. "\n FROM #__modules"
				. "\n WHERE iscore = 0 AND module <> ''" 
				. "\n GROUP BY module";
				break;

			default:
				break;
		}

		$limitstart = mosGetParam( $options, 'limitstart', 0 );
		$limit 		= mosGetParam( $options, 'limit', 0 );

		$this->_db->setQuery( $query, $limitstart, $limit );
		$result = $this->_db->loadObjectList();

		return $result;
	}
}
?>
