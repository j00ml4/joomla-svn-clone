<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// Define CMS environment
if (!defined('_CMSAPI_ADMIN_SIDE')) define('_CMSAPI_ADMIN_SIDE', 1);
foreach (array('aliro','mambo','joomla') as $cmsapi_cms) @include(dirname(dirname(__FILE__))."/cms_is_$cmsapi_cms.php");
require(_CMSAPI_ABSOLUTE_PATH.'/components/com_glossary/glossary.autoload.php');
// End of CMS environment definition

class JElementGlossaries extends JElement {

   /**
    * Element type
    *
    * @access      public
    * @var         string
    */
    public $_name = 'glossaries';
    /**
      * Gets an HTML rendered string of the element
      *
      * @param string Name of the form element
      * @param string Value
      * @param JSimpleXMLElement XML node in which the element is defined
      * @param string Control set name, normally params
      */
	public function fetchElement ($name, $value, $node, $control_name) {
		// get the CSS Style from the XML node class attribute
		// $class = empty($node->attributes('class')) ? 'class="inputbox"' : 'class="'.$node->attributes('class').'"';
		$class = 'class="inputbox"';
		// prepare an array for the options
		$glossaries = glossaryGlossaryManager::getInstance()->getAll();
		foreach ($glossaries as $glossary) {
			$choices[] = JHTMLSelect::option($glossary->id, $glossary->name);
		}
		// create the HTML list and return it (this sorts out the
		// selected option for us)
		return isset($choices) ? JHTMLSelect::genericList($choices, $control_name.'['.$name.']', $class, 'value', 'text', $value, $control_name.$name) : '';
  }
}