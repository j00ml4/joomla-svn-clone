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

<div class="panel"><h3 class="jpane-toggler title" id="comments-page"><span>Comments Settings</span></h3><div class="jpane-slider content">
<table width="100%" class="paramlist admintable" cellspacing="1">
	<tr>
		<td width="40%" class="paramlist_key">
			<span class="editlinktip"><label id="paramsenable_comments-lbl" for="paramsenable_comments" class="hasTip" title="Enable Comments::...">Enable Comments</label></span>
		</td>
		<td class="paramlist_value">
			<select name="params[enable_comments]" id="paramsenable_comments" class="inputbox">
				<option value=""<?php echo ($params->get('enable_comments') === null) ? ' selected="selected"' : null; ?>>Inherit</option>
				<option value="0"<?php echo ($params->get('enable_comments') == '0') ? ' selected="selected"' : null; ?>>No</option>
				<option value="1"<?php echo ($params->get('enable_comments') == '1') ? ' selected="selected"' : null; ?>>Yes</option>
			</select>
		</td>
	</tr>
	<tr>
		<td width="40%" class="paramlist_key">
			<span class="editlinktip"><label id="paramsenable_ratings-lbl" for="paramsenable_ratings" class="hasTip" title="Enable Ratings::...">Enable Ratings</label></span>
		</td>
		<td class="paramlist_value">
			<select name="params[enable_ratings]" id="paramsenable_ratings" class="inputbox">
				<option value=""<?php echo ($params->get('enable_ratings') === null) ? ' selected="selected"' : null; ?>>Inherit</option>
				<option value="0"<?php echo ($params->get('enable_ratings') == '0') ? ' selected="selected"' : null; ?>>No</option>
				<option value="1"<?php echo ($params->get('enable_ratings') == '1') ? ' selected="selected"' : null; ?>>Yes</option>
			</select>
		</td>
	</tr>
	<tr>
		<td width="40%" class="paramlist_key">
			<span class="editlinktip"><label id="paramsenable_bookmarks-lbl" for="paramsenable_bookmarks" class="hasTip" title="Enable Bookmarks::...">Enable Bookmarks</label></span>
		</td>
		<td class="paramlist_value">
			<select name="params[enable_bookmarks]" id="paramsenable_bookmarks" class="inputbox">
				<option value=""<?php echo ($params->get('enable_bookmarks') === null) ? ' selected="selected"' : null; ?>>Inherit</option>
				<option value="0"<?php echo ($params->get('enable_bookmarks') == '0') ? ' selected="selected"' : null; ?>>No</option>
				<option value="1"<?php echo ($params->get('enable_bookmarks') == '1') ? ' selected="selected"' : null; ?>>Yes</option>
			</select>
		</td>
	</tr>
</table>
</div></div>