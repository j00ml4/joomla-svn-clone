<?php
/**
 * @version		$Id$
 * @package		Repair
 * @subpackage	com_repair
 * @copyright	Copyright 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;
?>

<p>Utility Component to repair things in the database.</p>
<p>Work in progress.</p>

<a href="<?php echo JRoute::_('index.php?option=com_repair&view=tests&tmpl=component');?>" target="output">
	<?php echo JText::_('COM_REPAIR_RUN_TESTS'); ?></a>

<iframe name="output" width="100%" height="400px" border="1></iframe>
