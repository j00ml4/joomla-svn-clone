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
<?php

if (!count($this->messages)) {
	echo '<p>'. JText::_('No warnings detected').'</p>';
} else {
	jimport('joomla.html.pane');
	$pane =& JPane::getInstance('sliders');
	echo $pane->startPane("warning-pane");
	foreach($this->messages as $message) {
		echo $pane->startPanel($message['message'], str_replace(' ','', $message['message']));
		echo '<div style="padding: 5px;" >'.$message['description'].'</div>';
		echo $pane->endPanel();
	}
	echo $pane->startPanel(JText::_('WARNINGFURTHERINFO'),'furtherinfo-pane');
	echo '<div style="padding: 5px;" >'. JText::_('WARNINGFURTHERINFODESC') .'</div>';
	echo $pane->endPanel();
	echo $pane->endPane();
}
// the below br fixes a formatting issue in ff3 on mac
?><br />
<input type="hidden" name="task" value="manage" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="option" value="com_installer" />
<input type="hidden" name="type" value="warnings" />
<?php echo JHTML::_('form.token'); ?>