<?php
// No direct access to this file
defined('_JEXEC') or die;

jimport('joomla.html.html');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('radio');

/**
 * Select project form field
 */
class JFormFieldTask_Type extends JFormFieldRadio
{
	/**
	 * The field type.
	 * @var         string
	 */
	protected $type = 'task_type';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		// Initialize variables.
		$html = array();

		// Initialize some field attributes.
		$class = $this->element['class'] ? ' class="radio '.(string) $this->element['class'].'"' : ' class="radio"';

		// Get the field options.
		$options = $this->getOptions();

		// Build the radio field output.
		foreach ($options as $i => $option) {

			// Initialize some option attributes.
			$checked	= ((string) $option->value == (string) $this->value) ? ' checked="checked"' : '';
			$class		= !empty($option->class) ? ' class="'.$option->class.'"' : '';
			$disabled	= !empty($option->disable) ? ' disabled="disabled"' : '';

			// Initialize some JavaScript option attributes.
			$onclick	= !empty($option->onclick) ? ' onclick="'.$option->onclick.'"' : '';

			$html[] = '<input type="radio" id="'.$this->id.$i.'" name="'.$this->name.'"' .
					' value="'.htmlspecialchars($option->value, ENT_COMPAT, 'UTF-8').'"'
					.$checked.$class.$onclick.$disabled.'/>';

					$html[] = '<label for="'.$this->id.$i.'"'.$class.'>'.JText::_($option->text).'</label>';
		}

		return implode($html);
	}

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return      array           An array of JHtml options.
	 */
	protected function getOptions(&$config = array())
	{
		$options = array();
		
		$options[] = JHtml::_('select.option', 3, JText::_('COM_PROJECTS_TYPE_TICKET'));
		$options[] = JHtml::_('select.option', 2, JText::_('COM_PROJECTS_TYPE_TASK'));
		$options[] = JHtml::_('select.option', 1, JText::_('COM_PROJECTS_TYPE_MILESTONE')); 
		
		return $options;
	}
}