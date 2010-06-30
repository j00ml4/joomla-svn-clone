<?php
/**
 * @version		$Id: default_articles.php 17873 2010-06-25 18:24:21Z 3dentech $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<td colspan="<?php echo $this->canDo->get('project.edit') ? 5 : 3; ?>">
	<?php
		echo $this->escape($this->article->title).' : '.JText::_( 'COM_PROJECTS_DOCUMENTS_NO_RIGHT_SEE' );
	?>
</td>				