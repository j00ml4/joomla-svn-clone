<?php
/**
* FileName: mod_glossaryBase.php
* Date: April 2009
* License: GNU General Public License
* Script Version #: 2.6
* Glossary Version #: 2.6 or above
* Author: Martin Brampton - martin@remository.com (http://remository.com)
* Copyright: Martin Brampton 2006-9
**/

class cmsapiItemidSorter {
    private $_object_array = array();
	private static $types = array (
	'mainmenu' => 1,
	'topmenu' => 2,
	'othermenu' => 3
	);
	private $component = '';
	private $paramname = '';
	private $paramvalue = 0;

    public function __construct ($a, $component, $paramname='', $paramvalue=0) {
		foreach ($a as $menu) if (false !== strpos($menu->link, 'index.php?option='.$component)) $this->_object_array[] = $menu;
		$this->component = $component;
		$this->paramname = $paramname;
		$this->paramvalue = $paramvalue;
        $this->sort();
    }

	public function getItemid () {
		$selected = array_shift($this->_object_array);
		return is_object($selected) ? $selected->id : 999999;
	}

    // This is not genuinely public, but has to be declared so for the callback
    public function menuCompare (&$a, &$b) {
		if ($this->paramname) {
			$amatch = strpos($a->params, $this->paramname.'='.$this->paramvalue);
			$bmatch = strpos($b->params, $this->paramname.'='.$this->paramvalue);
			if (false !== $amatch) {
				if (false === $bmatch) return -1;
			}
			elseif (false !== $bmatch) return 1;
		}
		$atype = isset(self::$types[$a->menutype]) ? self::$types[$a->menutype] : 99;
		$btype = isset(self::$types[$b->menutype]) ? self::$types[$b->menutype] : 99;
        if ($atype > $btype) return 1;
        if ($atype < $btype) return -1;
        return 0;
    }

    private function sort () {
        usort($this->_object_array, array($this,'menuCompare'));
    }


}

abstract class mod_glossaryBase {
	private static $cssdone = false;
	protected $live_site = '';

	public function __construct () {
		$interface = cmsapiInterface::getInstance();
		$this->live_site = $interface->getCfg('live_site');
	}

	protected function cmsapi_get_module_parm ($params, $name, $default) {
		$value =  method_exists($params,'get') ? $params->get($name,$default) : (isset($params->$name) ? $params->$name : $default);
		$isnumeric = is_numeric($default);
		if ($isnumeric AND !is_numeric($value)) return $default;
		if ($isnumeric) return intval($value);
		return $value;
	}

	// Limited use - older systems have already created header before module code is run
	protected function cmsapi_module_CSS () {
		if (self::$cssdone) return;
		self::$cssdone = true;
		$interface = cmsapiInterface::getInstance();
		$module_css = <<<MODULE_CSS

<link href="{$interface->getCfg('live_site')}/components/com_glossary/glossary.module.css" rel="stylesheet" type="text/css" />

MODULE_CSS;

		$interface->addCustomHeadTag($module_css);
	}

	protected function cmsapi_getItemID ($component, $paramname='', $paramvalue=0) {
		if (isset($GLOBALS['remosef_itemids'][$component][$paramname][$paramvalue])) $Itemid = $GLOBALS['remosef_itemids'][$component][$paramname][$paramvalue];
		elseif ('Joomla' == _CMSAPI_CMS_BASE) {
			$menus = JSite::getMenu()->getMenu();
			$sorter = new cmsapiItemidSorter($menus, $component, $paramname, $paramvalue);
			$Itemid = $sorter->getItemid();
		}
		else {
			$database = cmsapiInterface::getInstance()->getDB();
			$sql = "SELECT id FROM #__menu WHERE link LIKE 'index.php?option=$component%'"
			." AND published !=0 ORDER BY ";
			if ($paramname) $sql .= " IF( params LIKE '%$paramname=$paramvalue%', 0, 1 ),";
			$sql .= " CASE menutype WHEN 'mainmenu' THEN 1 WHEN 'topmenu' THEN 2 WHEN 'othermenu' THEN 3 ELSE 99 END";
			$sql .= " LIMIT 1";
			$database->setQuery($sql);
			$GLOBALS['remosef_itemids'][$component][$paramname][$paramvalue] = $Itemid = $database->loadResult();
		}
		return $Itemid;
	}

	protected function removeFormatCharacters ($string) {
		return preg_replace('/[\n\r\t\v]/', '', $string);
	}
}
