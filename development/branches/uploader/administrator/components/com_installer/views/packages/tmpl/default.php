<?php
/**
 * @version		$Id: default.php 12344 2009-06-24 11:28:31Z eddieajau $
 * @package		Joomla.Administrator
 * @subpackage	com_installer
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<form action="index.php" method="post" name="adminForm">
<div id="editcell">
	<table class="adminform">
		<tbody>
			<tr>
				<td width="100%"><?php echo JText::_('DESCPACKAGES'); ?></td>
			</tr>
		</tbody>
	</table>

    <table class="adminlist">
    <thead>
        <tr>
        	<th class="title" width="10px"><?php echo JText::_('Num'); ?></th>
            <th class="title">
                <?php echo JText::_('Package'); ?>
            </th>
            <th class="title">
            	<?php echo JText::_('Version'); ?>
            </th>
            <th class="title">
            	<?php echo JText::_('Author'); ?>
            </th>
            <th class="title">
            	<?php echo JText::_('Packager'); ?>
            </th>
        </tr>
    </thead>
	<tfoot>
		<tr>
			<td colspan="6"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>
	<tbody>
    <?php
    $k = 0;
     for ($i=0, $n=count($this->items), $rc=0; $i < $n; $i++, $rc = 1 - $rc)
    {
        $this->loadItem($i);
		echo $this->loadTemplate('item');
    }
    ?>
    </tbody>
    </table>
</div>

	<input type="hidden" name="task" value="manage" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_installer" />
	<input type="hidden" name="type" value="packages" />
	<?php echo JHTML::_('form.token'); ?>
</form>
