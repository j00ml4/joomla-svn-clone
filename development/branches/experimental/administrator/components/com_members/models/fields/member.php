<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @copyright	Copyright (C) 2008 - 2009 JXtended, LLC. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 */

defined('_JEXEC') or die;

jimport('joomla.form.formfield');

/**
 * Form Field Type Class for Members.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_members
 * @since		1.6
 */
class JFormFieldMember extends JFormField
{
	/**
	 * Method to generate the form field markup.
	 *
	 * @param	string	The form field name.
	 * @param	string	The form field value.
	 * @param	object	The JFormField object.
	 * @param	string	The form field control name.
	 * @return	string	Form field markup.
	 */
	protected function _getInput()
	{
		// Load the modal behavior.
		JHtml::_('behavior.modal', 'a.modal');

		// Add the JavaScript select function to the document head.
		$js = '
		function jxSelectMember(id, title, el) {
			console.log(el);
			$(el + \'_id\').value = id;
			$(el + \'_name\').value = title;
			$(\'sbox-window\').close();
		}';
		$document = &JFactory::getDocument();
		$document->addScriptDeclaration($js);

		// Setup variables for display.
		$link = 'index.php?option=com_members&amp;view=members&layout=modal&amp;tmpl=component&amp;field='.$this->inputId;

		// Load the current username if available.
		$table = &JTable::getInstance('user');
		if ($this->value) {
			$table->load($this->value);
		} else {
			$table->username = JText::_('Select a User');
		}
		$title = htmlspecialchars($table->username, ENT_QUOTES, 'UTF-8');

		// The current member display field.
		$html[] = '<div style="float: left;">';
		$html[] = '  <input style="background: #fff;" type="text" id="'.$this->inputId.'_name" value="'.$title.'" disabled="disabled" />';
		$html[] = '</div>';

		// The member select button.
		$html[] = '<div class="button2-left">';
		$html[] = '  <div class="blank">';
		$html[] = '    <a class="modal" title="'.JText::_('Select a User').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 650, y: 375}}">'.JText::_('Select').'</a>';
		$html[] = '  </div>';
		$html[] = '</div>';

		// The active member id field.
		$html[] = '<input type="hidden" id="'.$this->inputId.'_id" name="'.$this->inputName.'" value="'.(int)$this->value.'" />';


		return implode("\n", $html);
	}
}