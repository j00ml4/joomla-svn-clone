<?php // no direct access
defined('_JEXEC') or die; ?>
<div align="center">
<?php if ($link) : ?>
<a href="<?php echo $link; ?>" target="_self">
<?php endif; ?>
	<?php echo JHtml::_('image', $image->folder.'/'.$image->name, $image->name, array('width' => $image->width, 'height' => $image->height)); ?>
<?php if ($link) : ?>
</a>
<?php endif; ?>
</div>