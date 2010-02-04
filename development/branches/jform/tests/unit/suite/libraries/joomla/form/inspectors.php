<?php
/**
 * Inspector classes for the forms library.
 */
require_once JPATH_BASE.'/libraries/joomla/form/form.php';

class JFormInspector extends JForm
{
	/**
	 * @return	array	Return the protected options array.
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * @return	array	Return the protected data object.
	 */
	public function getXML()
	{
		return $this->xml;
	}

	public function getFieldsByGroup($name)
	{
		return parent::getFieldsByGroup($name);
	}

	public function getFieldsByFieldset($name)
	{
		return parent::getFieldsByFieldset($name);
	}
}