<?php
/**
 * @version     $Id$
 * @package     Joomla.Administrator
 * @subpackage	Projects
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class ProjectsViewConfig extends JView
{
       /**
         * HelloWorldList view display method
         * @return void
         */
        function display($tpl = null)
        {
			// Set the toolbar
            $this->setToolBar();
            
            // Set the document
            $this->setDocument();
            
            // Display the template
            parent::display($tpl);
        }
        /**
         * Setting the toolbar
         */
        protected function setToolBar()
        {
            JToolBarHelper::title(JText::_('COM_PROJECTS'));
            JToolBarHelper::preferences('com_projects');
        }
        /**
         * Method to set up the document properties
         *
         * @return void
         */
        protected function setDocument() 
        {
        	
        }
}