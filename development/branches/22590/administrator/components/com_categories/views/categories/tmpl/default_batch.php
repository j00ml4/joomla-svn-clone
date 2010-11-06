<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	com_categories
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

$published	= $this->state->get('filter.published');
?>
<fieldset class="batch">
	<?php echo JHtml::_('batch.accessLevel'); ?>
	<?php if ($published >= 0) : ?>
		<?php echo JHtml::_('batch.moveCopyCategory',
			$this->state->get('filter.extension'),
			$published
		); ?>
	<?php endif; ?>
	<?php echo JHtml::_('batch.processButton', 'categories'); ?>
	<?php echo JHtml::_('batch.cancelButton'); ?>
</fieldset>