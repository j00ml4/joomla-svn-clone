<?php defined('_JEXEC') or die; ?>
<div class="item">
	<a href="<?php echo JRoute::_('index.php?option=com_media&amp;view=imagesList&amp;tmpl=component&amp;folder=' + $this->_tmp_folder->path_relative); ?>">
		<img src="<?php echo JURI::base() ?>components/com_media/images/folder.gif" width="80" height="80" alt="<?php echo $this->_tmp_folder->name; ?>" />
		<span><?php echo $this->_tmp_folder->name; ?></span></a>
</div>
