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

	public function getData()
	{
		return $this->data;
	}

	public static function addNode(SimpleXMLElement $source, SimpleXMLElement $new)
	{
		return parent::addNode($source, $new);
	}

	public static function mergeNode(SimpleXMLElement $source, SimpleXMLElement $new)
	{
		return parent::mergeNode($source, $new);
	}

	public static function mergeNodes(SimpleXMLElement $source, SimpleXMLElement $new)
	{
		return parent::mergeNodes($source, $new);
	}

	public function loadFieldType($type, $new = true)
	{
		return parent::loadFieldType($type, $new);
	}

	public function findField($name, $group)
	{
		return parent::findField($name, $group);
	}
}