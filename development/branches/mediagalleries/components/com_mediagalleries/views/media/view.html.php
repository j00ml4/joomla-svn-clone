<?php
/**
 * @version		$Id: view.html.php 18109 2010-07-13 11:21:43Z 3dentech $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
jimport( 'joomla.registry.registry' );


/**
 * HTML View class for the WebLinks component
 *
 * @package		Joomla.Site
 * @subpackage	com_weblinks
 * @since		1.5
 */
class MediagalleriesViewMedia extends JView
{
	protected $state;
	protected $item;

	function display($tpl = null)
	{
		$app		= JFactory::getApplication();
		$this->params=$app->getParams(); 
		
		
		// Get some data from the models
		
		$state		= $this->get('State');
		$this->item		= $this->get('Item');
		$category	= $this->get('Category');
		
		$registry = new JRegistry();
		$registry->loadJSON($this->item->params);
		$registry->toObject();
		$this->item->params= $registry;
		
		if ($this->item->url) {					
			$this->media=plgContentMedia::addMedia($this->item->url,$this->item->params->get('width',425),$this->item->params->get('height'),$this->params->get('autostart',0));
		} else {
			//TODO create proper error handling
			return  JError::raiseError(404, "Media Not Found");
			
		}
		
		
		parent::display();
	}
}