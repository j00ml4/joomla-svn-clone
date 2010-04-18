<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_comment
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Invalid Request.');

// attach the comments stylesheet to the document head
JHtml::stylesheet('social/comments.css', array(), true);

// Get the route and add the fragment to direct the page to the comments.
$route = &JURI::getInstance($params->get('route'));
$route->setFragment('comments');
$route = $route->toString(array('path', 'query', 'fragment'));
?>

<h4 class="comments"><a href="<?php echo JRoute::_($route); ?>" title="<?php echo JText::_('Comments_View_Full_Page'); ?>">
<?php
	if ($pagination->total == 1) {
		echo JText::sprintf('Comment_Num', (int)$pagination->total);
	} else {
		echo JText::sprintf('Comments_Num', (int)$pagination->total);
	}
?></a></h4>
