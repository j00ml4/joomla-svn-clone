<?php
// No direct access to this file
defined('_JEXEC') or die;
// import the list field type
jimport('joomla.html.html.list');

/**
 * Select project form field
 */
class JFormFieldSelect_Project extends JFormFieldList
{
        /**
         * The field type.
         *
         * @var         string
         */
        protected $type = 'select_project';
        
        /**
         * Method to get a list of options for a list input.
         *
         * @return      array           An array of JHtml options.
         */
        protected function getOptions() 
        {
                $db = JFactory::getDBO();
                $query = new JDatabaseQuery;
                $query->select('id AS value,title AS text');
                $query->from('#__projects');
                $query->order('text ASC,ordering');
                $db->setQuery($query);
                $rows = $db->loadObjectList();
                $options = array();
                $options[] = JHtml::_('select.option', 0, JText::_(JNONE));
                foreach($rows as $row) 
                {
                        $options[] = JHtml::_('select.option', $row->value, $row->text);
                }
                $options = array_merge(parent::getOptions() , $options);
                return $options;
        }
}