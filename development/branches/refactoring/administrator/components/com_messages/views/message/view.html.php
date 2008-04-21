<?php
/**
* @version		$Id$
* @package		Joomla
* @subpackage	Message
* @copyright	Copyright (C) 2005 - 2007 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Message component
 *
 * @static
 * @package		Joomla
 * @subpackage	Message
 * @since 1.0
 */
class MessagesViewMessage extends JView
{
	function display($tpl = null)
	{
		$db		=& JFactory::getDBO();
		$user	=& JFactory::getUser();
		$acl	=& JFactory::getACL();

		JRequest::setVar( 'hidemainmenu', 1 );

		$edit		= JRequest::getVar('edit',true);
		if ($edit) {
			// Set toolbar items for the page
			JToolBarHelper::title(  JText::_( 'Write Private Message' ), 'inbox.png' );
			JToolBarHelper::save( 'save', 'Send' );
			JToolBarHelper::cancel();
			JToolBarHelper::help( 'screen.messages.edit' );

			$reply_user = null;
			$subject = null;
			if (JRequest::getVar('reply',false)) {
				$reply_user = JRequest::getVar( 'userid', 0, '', 'int' );
				$subject = JRequest::getString( 'subject' );
			}
	
			// get available backend user groups
			$gid 	= $acl->get_group_id( 'Public Backend', 'ARO' );
			$gids 	= $acl->get_group_children( $gid, 'ARO', 'RECURSE' );
			JArrayHelper::toInteger($gids, array(0));
			$gids 	= implode( ',', $gids );
		
			// get list of usernames
			$recipients = array( JHTML::_('select.option',  '0', '- '. JText::_( 'Select User' ) .' -' ) );
			$query = 'SELECT id AS value, username AS text FROM #__users'
					. ' WHERE gid IN ( '.$gids.' )'
					. ' ORDER BY name'
			;
			$db->setQuery( $query );
			$recipients = array_merge( $recipients, $db->loadObjectList() );
		
			$this->assignRef('recipients',		$recipients);
			$this->assignRef('user',			$user);
			$this->assignRef('reply_user',		$reply_user);
			$this->assignRef('subject',			$subject);

			$tpl = 'form';
		}
		else
		{
			// Set toolbar items for the page
			JToolBarHelper::title(  JText::_( 'View Private Message' ), 'inbox.png' );
			JToolBarHelper::customX('reply', 'restore.png', 'restore_f2.png', 'Reply', false );
			JToolBarHelper::deleteList();
			JToolBarHelper::cancel();

			$model = $this->getModel();
			$model->markAsRead();

			$row =& $this->get('data');
			$this->assignRef('row',		$row);
		}

		parent::display($tpl);
	}
}