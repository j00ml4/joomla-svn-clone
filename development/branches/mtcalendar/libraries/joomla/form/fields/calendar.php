<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldCalendar extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $type = 'Calendar';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string   The field input markup.
	 * @since   11.1
	 */
	protected function getInput()
	{
		// Initialize some field attributes.
		$format = $this->element['format'] ? (string) $this->element['format'] : '%Y-%m-%d';

		$readonly	= ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled	= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$size		= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$maxLength	= $this->element['maxlength'] ? ' maxlength="'.(int) $this->element['maxlength'].'"' : '';
		$classes	= (string) $this->element['class'];
		
		if ((!$readonly) && (!$disabled)) {
			// Load the calendar behavior
			JHtml::_('behavior.calendar');
			JHtml::_('behavior.tooltip');
			
			if (JFactory::getDocument()->getDirection() == 'rtl') {
				$options['rtl'] = true;
			}

			$options['format'] = $format;
			$options['pickerClass'] = 'datepicker_dashboard';
			$options['startDay'] = JFactory::getLanguage()->getFirstDay();
			
			$options['blockKeydown'] = false;

			$optionsText = JHtmlBehavior::_getJSObject($options);

			JFactory::getDocument()->addScriptDeclaration('
					window.addEvent(\'domready\', function() {
						new Picker.Date(document.id(\''.$this->id.'\'), '.$optionsText.');
					});');
			$classes .= 'calendar-custom';
		}

		// Handle the special case for "now".
		if (strtoupper($this->value) == 'NOW') {
			$this->value = strftime($format);
		}

		// Get some system objects.
		$config = JFactory::getConfig();
		$user	= JFactory::getUser();

		// If a known filter is given use it.
		switch (strtoupper((string) $this->element['filter']))
		{
			case 'SERVER_UTC':
				// Convert a date to UTC based on the server timezone.
				if (intval($this->value)) {
					// Get a date object based on the correct timezone.
					$date = JFactory::getDate($this->value, 'UTC');
					$date->setTimezone(new DateTimeZone($config->get('offset')));

					// Transform the date string.
					$this->value = $date->toMySQL(true);
				}
				break;

			case 'USER_UTC':
				// Convert a date to UTC based on the user timezone.
				if (intval($this->value)) {
					// Get a date object based on the correct timezone.
					$date = JFactory::getDate($this->value, 'UTC');
					$date->setTimezone(new DateTimeZone($user->getParam('timezone', $config->get('offset'))));

					// Transform the date string.
					$this->value = $date->toMySQL(true);
				}
				break;
		}

		$class		= $classes ? ' class="'.trim($classes).'"' : '';

		//return JHtml::_('calendar', $this->value, $this->name, $this->id, $format, $attributes);
		return '<input type="text" value="'.htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8').'" name="'.$this->name.'" id="'.$this->id.'"'.$size.$maxLength.$class.' />';
	}
}
