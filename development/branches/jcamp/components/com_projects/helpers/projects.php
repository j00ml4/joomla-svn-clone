<?php
/**
 * @version		$Id: media.php 15757 2010-04-01 11:06:27Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	Media
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * @package		Joomla.Site
 * @subpackage	Media
 */
class ProjectsHelper
{
	/**
	 * Method to test whether a user can do an action in the record
	 *
	 * @param	object	A record object.
	 * @param	array	a list of {action => asset}
	 * @return	boolean	True if allowed to change the state of the record. Defaults to the permission set in the component.
	 * @since	1.6
	 */
	public function canDo($accesses=array(), $record=null, $user=null)
	{
		empty($user) &&	$user = JFactory::getUser();
		
		// Nothing set?
		if (empty($accesses)) return false;
		// Check if have access
		foreach($accesses as $role -> $asset){
			//Is the author?
			if (
				($asset=='owner') && 
				!empty($record->created_by) &&
				($record->created_by == $user->id)
			){
				return true;
			}

			// Has Permition?
			if ($user->authorise($role, $asset)){
				return true;	
			}
		}
		return false;
	}
	
	
}

/**
 * Simple debug function
 * @param $data
 */
function dump($data){
	echo '<pre>';
	print_r($data);
	echo '</pre>';
}
?>