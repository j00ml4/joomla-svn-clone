<?php
/**
 * @version		$Id$
 * @package		JXtended.Comments
 * @subpackage	plgSystemComments
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<fieldset>
<legend>Comments</legend>
<table class="adminform">
<tr>
	<td valign="top" class="key">
		<span class="editlinktip"><label id="paramsenable_comments-lbl" for="paramsenable_comments" class="hasTip" title="Enable Comments::...">Enable Comments</label></span>
	</td>

	<td>
		<select name="params[enable_comments]" id="paramsenable_comments" class="inputbox">
			<option value=""<?php echo ($params->get('enable_comments') === null) ? ' selected="selected"' : null; ?>>Inherit</option>
			<option value="0"<?php echo ($params->get('enable_comments') == '0') ? ' selected="selected"' : null; ?>>No</option>
			<option value="1"<?php echo ($params->get('enable_comments') == '1') ? ' selected="selected"' : null; ?>>Yes</option>
		</select>
	</td>
</tr>
<tr>
	<td  valign="top" class="key">
		<span class="editlinktip"><label id="paramsenable_ratings-lbl" for="paramsenable_ratings" class="hasTip" title="Enable Ratings::...">Enable Ratings</label></span>
	</td>

	<td>
		<select name="params[enable_ratings]" id="paramsenable_ratings" class="inputbox">
			<option value=""<?php echo ($params->get('enable_ratings') === null) ? ' selected="selected"' : null; ?>>Inherit</option>
			<option value="0"<?php echo ($params->get('enable_ratings') == '0') ? ' selected="selected"' : null; ?>>No</option>
			<option value="1"<?php echo ($params->get('enable_ratings') == '1') ? ' selected="selected"' : null; ?>>Yes</option>
		</select>
	</td>
</tr>
<tr>
	<td  valign="top" class="key">
		<span class="editlinktip"><label id="paramsenable_bookmarks-lbl" for="paramsenable_bookmarks" class="hasTip" title="Enable Bookmarks::...">Enable Bookmarks</label></span>
	</td>

	<td>
		<select name="params[enable_bookmarks]" id="paramsenable_bookmarks" class="inputbox">
			<option value=""<?php echo ($params->get('enable_bookmarks') === null) ? ' selected="selected"' : null; ?>>Inherit</option>
			<option value="0"<?php echo ($params->get('enable_bookmarks') == '0') ? ' selected="selected"' : null; ?>>No</option>
			<option value="1"<?php echo ($params->get('enable_bookmarks') == '1') ? ' selected="selected"' : null; ?>>Yes</option>
		</select>
	</td>
</tr>
</table>
</fieldset>
