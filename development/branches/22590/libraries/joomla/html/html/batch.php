<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/**
 * Extended Utility class for batch processing widgets.
 *
 * @package		Joomla.Framework
 * @subpackage	HTML
 * @since		1.6
 */
abstract class JHtmlBatch
{
	/**
	 * @var		array	An array javascript instructions to cancel the selection.
	 * @since	1.6
	 */
	protected static $cancel = array();

	/**
	 * Display a batch widget for the access level selector.
	 *
	 * @param	boolean	$autoCancel	Track the code to clear the selected value for this element.
	 *
	 * @return	string	The necessary HTML for the widget.
	 * @since	1.6
	 */
	public static function accessLevel($autoCancel = true)
	{
		if ($autoCancel) {
			self::$cancel[] = "document.id('batch-access').value=''";
		}

		// Create the batch selector to change an access level on a selection list.
		$lines = array(
			'<label id="batch-access-lbl" for="batch-access">',
			JText::_('JGLOBAL_BATCH_ACCESS_LABEL'),
			JHtml::_('access.assetgrouplist', 'batch[assetgroup_id]', '', 'class="inputbox"', array('title' => '', 'id' => 'batch-access')),
			'</label>'
		);

		return implode("\n", $lines);
	}

	/**
	 * Displays the process button for the batch widget.
	 *
	 * @param	mixed	$cancel	If true, then the cancel instruction will automatically be worked out, otherwise a string can be passed manually.
	 *
	 * @return	string	The necessary HTML for the widget.
	 * @since	1.6
	 */
	public static function cancelButton($cancel = true)
	{
		if (is_bool($cancel)) {
			// Compute the cancel instructions automatically.
			$cancel = implode(';', self::$cancel);
		}
		else {
			// Manual cancel instructions provided.
			$cancel = trim($cancel);
		}

		$lines = array(
			'<button type="button"'.($cancel ? ' onclick="'.str_replace('"', '\"', $cancel).'"' : '').'>',
				JText::_('JSEARCH_FILTER_CLEAR'),
			'</button>'
		);

		return implode("\n", $lines);
	}

	/**
	 * Displays a batch widget for moving between or copying to categories.
	 *
	 * @param	string	$extension	The extension that owns the category.
	 * @param	string	$published	The published state of categories to be shown in the list.
	 * @param	boolean	$autoCancel	Track the code to clear the selected value for this element.
	 *
	 * @return	string	The necessary HTML for the widget.
	 * @since	1.6
	 */
	public static function moveCopyCategory($extension, $published = 0, $autoCancel = true)
	{
		if ($autoCancel) {
			self::$cancel[] = "document.id('batch-category-id').value=''";
		}

		// Create the copy/move options.
		$options = array(
			JHtml::_('select.option', 'c', JText::_('JGLOBAL_BATCH_COPY')),
			JHtml::_('select.option', 'm', JText::_('JGLOBAL_BATCH_MOVE'))
		);

		// Create the batch selector to change select the category by which to move or copy.
		$lines = array(
			'<label id="batch-choose-action-lbl" for="batch-choose-action">',
			JText::_('COM_CATEGORIES_BATCH_CATEGORY_LABEL'),
			'</label>',
			'<fieldset id="batch-choose-action" class="combo">',
				'<select name="batch[category_id]" class="inputbox" id="batch-category-id">',
					'<option value=""></option>',
					JHtml::_('select.options',
						JHtml::_('category.categories',
							// Sanitise inputs.
							htmlspecialchars($extension, ENT_COMPAT, 'UTF-8'),
							array('published' => (int) $published)
						)
					),
				'</select>',
				JHTML::_( 'select.radiolist', $options, 'batch[move_copy]', '', 'value', 'text', 'm'),
			'</fieldset>'
		);

		return implode("\n", $lines);
	}

	/**
	 * Displays the process button for the batch widget.
	 *
	 * @param	string	$actionContext	The context of the batch action to be performed.
	 *
	 * @return	string	The necessary HTML for the widget.
	 * @since	1.6
	 */
	public static function processButton($actionContext = null)
	{
		$action = $actionContext ? $actionContext.'.batch' : 'batch';

		$lines = array(
			'<button type="submit" onclick="submitbutton(\''.$action.'\');">',
				JText::_('JGLOBAL_BATCH_PROCESS'),
			'</button>'
		);

		return implode("\n", $lines);
	}
}