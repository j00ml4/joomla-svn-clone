<?php
/**
* @version $Id$
* @package Joomla
* @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
* @license GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Get the user object for the logged in user
$db		=& JFactory::getDBO();
$user	=& JFactory::getUser();
$userId	= (int) $user->get('id');

$where	= 'WHERE a.state <> -2';

// User Filter
switch ($params->get( 'user_id' ))
{
	case 'by_me':
		$userId = (int) $user->get('id');
		$where .= ' AND (created_by = ' . $userId . ' OR modified_by = ' . $userId . ')';
		break;
	case 'not_me':
		$where .= ' AND (created_by <> ' . $userId . ' AND modified_by <> ' . $userId . ')';
		break;
}

// Ordering
switch ($params->get( 'ordering' ))
{
	case 'm_dsc':
		$ordering		= 'modified DESC, created DESC';
		$dateProperty	= 'modified';
		break;
	case 'c_dsc':
	default:
		$ordering		= 'created DESC';
		$dateProperty	= 'created';
		break;
}

$query = "SELECT a.id, a.sectionid, a.title, a.created, a.modified, u.name, a.created_by_alias, a.created_by"
. "\n FROM #__content AS a"
. "\n LEFT JOIN #__users AS u ON u.id = a.created_by"
. "\n $where"
. "\n ORDER BY $ordering"
;
$db->setQuery( $query, 0, 10 );
$rows = $db->loadObjectList();
?>

<table class="adminlist">
<?php
if (count( $rows ))
{
	foreach ($rows as $row)
	{
		$link = 'index2.php?option=com_content&amp;task=edit&amp;hidemainmenu=1&amp;id='. $row->id;

		if ( $user->authorize( 'administration', 'manage', 'components', 'com_users' ) ) {
			if ( $row->created_by_alias )
			{
				$author = $row->created_by_alias;
			}
			else
			{
				$linkA 	= 'index2.php?option=com_users&task=editA&amp;hidemainmenu=1&id='. $row->created_by;
				$author = '<a href="'. $linkA .'" title="'. JText::_( 'Edit User' ) .'">'. htmlspecialchars( $row->name, ENT_QUOTES ) .'</a>';
			}
		}
		else
		{
			if ( $row->created_by_alias )
			{
				$author = $row->created_by_alias;
			}
			else
			{
				$author = htmlspecialchars( $row->name, ENT_QUOTES );
			}
		}
		?>
		<tr>
			<td>
				<a href="<?php echo $link; ?>">
					<?php echo htmlspecialchars($row->title, ENT_QUOTES);?></a>
			</td>
			<td>
				<?php echo $row->$dateProperty;?>
			</td>
			<td>
				<?php echo $author;?>
			</td>
		</tr>
		<?php
	}
}
else
{
?>
		<tr>
			<td>
				<?php echo JText::_( 'No matching results' );?>
			</td>
		</tr>
<?php
}
?>
</table>