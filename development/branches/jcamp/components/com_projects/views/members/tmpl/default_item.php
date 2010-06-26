<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	com_projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Create a shortcut for params.
$item	=&$this->item;
?>
<tr>
	<?php if($this->getModel()->getState('type') != 'list') :?>
	<td>
		<input type="checkbox" value="<?php echo $item->id;?>" name="usr[]" id="user-<?php echo $item->id;?>"/>
	</td>
	<?php endif; ?>
	<td>
		<label for="user-<?php echo $item->id;?>"><?php echo $item->name;?></label>
	</td>
</tr>