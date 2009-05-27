<?php defined('_JEXEC') or die;

jimport('joomla.filesystem.folder');
$cparams = JComponentHelper::getParams ('com_media');
$config = &JFactory::getConfig();
?>
<fieldset class="adminform">
	<legend><?php echo JText::_('Directory Permissions'); ?></legend>
		<table class="adminlist">
		<thead>
			<tr>
				<th width="650">
					<?php echo JText::_('Directory'); ?>
				</th>
				<th>
					<?php echo JText::_('Status'); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="2">
					&nbsp;
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php
			AdminViewSysinfo::writableRow('administrator/backups');
			AdminViewSysinfo::writableRow('administrator/components');
			AdminViewSysinfo::writableRow('administrator/language');

			// List all admin languages
			$admin_langs = JFolder::folders(JPATH_ADMINISTRATOR.DS.'language');
			foreach ($admin_langs as $alang)
			{
				AdminViewSysinfo::writableRow('administrator/language/'.$alang);
			}

			AdminViewSysinfo::writableRow('administrator/modules');
			AdminViewSysinfo::writableRow('administrator/templates');
			AdminViewSysinfo::writableRow('components');
			AdminViewSysinfo::writableRow('images');
			AdminViewSysinfo::writableRow('images/banners');
			AdminViewSysinfo::writableRow($cparams->get('image_path'));
			AdminViewSysinfo::writableRow('language');

			// List all site languages
			$site_langs	= JFolder::folders(JPATH_SITE.DS.'language');
			foreach ($site_langs as $slang)
			{
				AdminViewSysinfo::writableRow('language/'.$slang);
			}

			AdminViewSysinfo::writableRow('media');
			AdminViewSysinfo::writableRow('modules');
			AdminViewSysinfo::writableRow('plugins');
			AdminViewSysinfo::writableRow('plugins/content');
			AdminViewSysinfo::writableRow('plugins/editors');
			AdminViewSysinfo::writableRow('plugins/editors-xtd');
			AdminViewSysinfo::writableRow('plugins/search');
			AdminViewSysinfo::writableRow('plugins/system');
			AdminViewSysinfo::writableRow('plugins/user');
			AdminViewSysinfo::writableRow('plugins/xmlrpc');
			AdminViewSysinfo::writableRow('templates');
			AdminViewSysinfo::writableRow(JPATH_SITE.DS.'cache', 0, '<strong>'. JText::_('Cache Directory') .'</strong> ');
			AdminViewSysinfo::writableRow(JPATH_ADMINISTRATOR.DS.'cache', 0, '<strong>'. JText::_('Cache Directory') .'</strong> ');
			AdminViewSysinfo::writableRow($config->getValue('config.log_path', JPATH_ROOT.DS.'log'),0, '<strong>'. JText::_('Log Directory') . ' ($log_path)</strong> ');
			AdminViewSysinfo::writableRow($config->getValue('config.tmp_path', JPATH_ROOT.DS.'tmp'),0, '<strong>'. JText::_('Temp Directory') . ' ($tmp_path)</strong> ');
			?>
		</tbody>
		</table>
</fieldset>