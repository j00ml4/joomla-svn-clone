<?php
/**
 * Document Description
 *
 * Document Long Description
 *
 * PHP5
 *
 * Created on Aug 18, 2011
 *
 * @package package_name
 * @author Your Name <author@example.com>
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @version SVN: $Id$
 * @see http://joomlacode.org/gf/project/	JoomlaCode Project:
 */

class Pkg_EverythingInstallerScript {

	function install($parent) {
		echo '<p>'. JText::_('PKG_EVERYTHING_CUSTOM_INSTALL') . '</p>';
	}

	function uninstall($parent) {
		echo '<p>'. JText::_('PKG_EVERYTHING_CUSTOM_UNINSTALL') .'</p>';
	}

	function update($parent) {
		echo '<p>'. JText::_('PKG_EVERYTHING_CUSTOM_UPDATE') .'</p>';
	}

	function preflight($type, $parent) {
		// In preflight, you can check for a minimum version of Joomla!
		// and abort install if the prerequisite version is not met
		$jversion = new JVersion();
		if (version_compare($jversion->getShortVersion(), '1.7', 'lt')) {
			JError::raiseNotice(null, JText::_(''));
			return false;
		}
	}

	function postflight($type, $parent, $results) {
		// This sample postflight method allows packages to display a list
		// of extensions that were installed once the installation routine
		// is complete
?>
<?php $rows = 0;?>
<table class="adminlist">
	<thead>
		<tr>
			<th class="title"><?php echo JText::_('PKG_EVERYTHING_EXTENSION'); ?></th>
			<th width="30%"><?php echo JText::_('JSTATUS'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="2"></td>
		</tr>
	</tfoot>
	<tbody>
		<?php foreach ($results as $result) : ?>
		<tr class="row<?php echo (++ $rows % 2); ?>">
			<td class="key"><?php echo JText::_(strtoupper($result['name'])); ?></td>
			<td><strong>
				<?php if ($result['result'] == true) {
					echo JText::_('PKG_EVERYTHING_INSTALLED');
				} else {
					JText::_('PKG_EVERYTHING_NOT_INSTALLED');
				} ?></strong></td>
		</tr>
		<?php endforeach;?>
	</tbody>
</table>
<?php }
}
