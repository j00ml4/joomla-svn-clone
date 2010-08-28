<?php
// No direct access to this file
defined('_JEXEC') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Select project form field
 */
abstract class JHtmlFilter
{
        /**
         * The field type.
         *
         * @var         string
         */
        protected $type = 'Filter_state';
        
        public function state($type, $selected=null){
        	$options = array();
			
        	$options[] = JHtml::_('select.option', '', JText::_('JOPTION_SELECT_PUBLISHED'));
        	switch ($type){
        		case 'task':
        	     	$options[] = JHtml::_('select.option', '1', JText::_('COM_PROJECTS_STATE_PENDING'));
        			$options[] = JHtml::_('select.option', '2', JText::_('COM_PROJECTS_STATE_FINISHED'));
        			break;
        		case 'ticket':
        			$options[] = JHtml::_('select.option', '0', JText::_('COM_PROJECTS_STATE_DENIED'));
        			$options[] = JHtml::_('select.option', '-3', JText::_('COM_PROJECTS_STATE_REPORTED'));
        			$options[] = JHtml::_('select.option', '1', JText::_('COM_PROJECTS_STATE_APPROVED'));
        			$options[] = JHtml::_('select.option', '2', JText::_('COM_PROJECTS_STATE_FINISHED'));
        			break;	
        	}
		
			return JHtml::_('select.genericlist', 
				$options, 
				'filter_state', 
				array('class' => 'i', "onchange" => "this.form.submit()"), 
				'value', 'text',
				$selected,
				'filter_state');
        }
        
        public function catid($type='', $selected=null){
        	$extension = 'com_projects'.$type;
        	$options = array_merge(
        		array(JHtml::_('select.option', '', JText::_('JOPTION_SELECT_CATEGORY'))), 
        		JHtml::_('category.options', $extension));      	
			
			return JHtml::_('select.genericlist', 
				$options, 
				'filter_catid', 
				array('class' => 'i', "onchange" => "this.form.submit()"), 
				'value', 'text',
				$selected,
				'filter_catid');
        }
        
        public function type($selected=null){
	        	JRegistry::	
			$options = array(
				JHtml::_('select.option', 2, JText::_('COM_PROJECTS_TYPE_TASK')),
				JHtml::_('select.option', 3, JText::_('COM_PROJECTS_TYPE_TICKET'))
			);
			
			
			return JHtml::_('select.radiolist', 
				$options, 
				'type', 
				array("onchange" => "this.form.submit()"), 
				'value', 'text',
				$selected,
				'type');
        }
}