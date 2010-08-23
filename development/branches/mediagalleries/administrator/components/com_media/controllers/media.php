<?php
/**
 * @version		$Id: media.php 16403 2010-04-24 00:35:09Z eddieajau $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Mediagallery controller class.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_media
 * @since		1.6
 */

class MediaControllerMedia extends JControllerForm
{
	 protected $view_list = 'gallery';
	 protected $view_item = 'media';
	 
	 
	 	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param	string	The model name. Optional.
	 * @param	string	The class prefix. Optional.
	 * @param	array	Configuration array for model. Optional.
	 *
	 * @return	object	The model.
	 * @since	1.5
	 */
	public function &getModel($name = 'form', $prefix = '', $config = array())
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
	/**
	 * Save the record
	 */
	public function save()
	{
		if( parent::save() === true ) {
			$data = JRequest::getVar( 'jform' );
			$cid = ( int ) $data[ 'catid' ];
			$link = JRoute::_('index.php?option=com_media&view=gallery&id='.$cid);
			$this->setRedirect($link);
		}
		$this->setMessage(JText::_('COM_WEBLINK_SUBMIT_SAVE_SUCCESS'));
	}
	/**
	 * Method to cancel an edit
	 *
	 * Checks the item in, sets item ID in the session to null, and then redirects to the list page.
	 *
	 * @access	public
	 * @return	void
	 */
	public function cancel()
	{
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		parent::cancel();

		// Redirect to the list screen.
		$link = JRoute::_('index.php?option=com_media');
		$this->setRedirect($link);
	}
	 
	
}
