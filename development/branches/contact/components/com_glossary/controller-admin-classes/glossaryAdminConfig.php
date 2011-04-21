<?php

class glossaryAdminConfig extends cmsapiAdminControllers {
	protected $spec = array();
	
	protected function makeSpec () {
		$this->spec[0][0] = _GLOSSARY_USER_CONFIG;
		$this->spec[0][1] = array (
		// 'utf8' => array (_GLOSSARY_UTF8, _GLOSSARY_DESC_UTF8, 'yesno', null),
		'language' => array(_GLOSSARY_LANGUAGE, _GLOSSARY_DESC_LANGUAGE, 'input', ''),
		'show_list' => array(_GLOSSARY_SHOW_LIST, _GLOSSARY_DESC_SHOW_LIST, 'yesno', null),
		'termsonly' => array(_GLOSSARY_TERMS_ONLY, _GLOSSARY_DESC_TERMS_ONLY, 'yesno', null),
		'show_alphabet' => array(_GLOSSARY_SHOW_ALPHABET, _GLOSSARY_DESC_SHOW_ALPHABET, 'yesno', null),
		'show_alphabet_below' => array(_GLOSSARY_SHOW_ALPHABET_BELOW, _GLOSSARY_DESC_SHOW_ALPHABET_BELOW, 'yesno', null),
		'show_search' => array(_GLOSSARY_SHOW_SEARCH, _GLOSSARY_DESC_SHOW_SEARCH, 'yesno', null),
		'show_author' => array(_GLOSSARY_SHOW_AUTHOR, _GLOSSARY_DESC_SHOW_AUTHOR, 'yesno', null),
		'show_soundex' => array(_GLOSSARY_SHOW_SOUNDEX, _GLOSSARY_DESC_SHOW_SOUNDEX, 'yesno', null),
		'search_all' => array(_GLOSSARY_SEARCH_ALL, _GLOSSARY_SEARCH_ALL, 'yesno', null),
		'perpage' => array(_GLOSSARY_PER_PAGE, _GLOSSARY_DESC_PER_PAGE, 'input', 10),
		'pagespread' => array(_GLOSSARY_PAGE_SPREAD, _GLOSSARY_DESC_PAGE_SPREAD, 'input', 4),
		'allowentry' => array(_GLOSSARY_ALLOWENTRY, _GLOSSARY_DESC_ALLOWENTRY, 'yesno', null),
		'anonentry' => array(_GLOSSARY_ANONENTRY, _GLOSSARY_DESC_ANONENTRY, 'yesno', null),
		'autopublish' => array(_GLOSSARY_AUTOPUBLISH, _GLOSSARY_DESC_AUTOPUBLISH, 'yesno', null),
		'hideauthor' => array(_GLOSSARY_HIDEAUTHOR, _GLOSSARY_DESC_HIDEAUTHOR, 'yesno', null),
		'useeditor' => array(_GLOSSARY_USEEDITOR, _GLOSSARY_DESC_USEEDITOR, 'yesno', null),
		'showcategories' => array(_GLOSSARY_SHOWCATEGORIES, _GLOSSARY_DESC_SHOWCATEGORIES, 'yesno', null),
		'categoriesabove' => array(_GLOSSARY_CATEGORIES_ABOVE, _GLOSSARY_DESC_CATEGORIES_ABOVE, 'yesno', null),
		'showcatdescriptions' => array(_GLOSSARY_SHOWCATDESCRIPTIONS, _GLOSSARY_DESC_SHOWCATDESCRIPTIONS, 'yesno', null),
		'shownumberofentries' => array(_GLOSSARY_SHOWNUMBEROFENTRIES, _GLOSSARY_DESC_SHOWNUMBEROFENTRIES, 'yesno', null),
		'triggerplugins' => array (_GLOSSARY_CALL_PLUGINS, _GLOSSARY_DESC_CALL_PLUGINS, 'yesno', null)
		);		
		$this->spec[1][0] = _GLOSSARY_ADMIN_CONFIG;
		$this->spec[1][1] = array (
		// 'utf8-admin' => array(_GLOSSARY_UTF8_ADMIN, _GLOSSARY_DESC_UTF8_ADMIN, 'yesno', null),
		'strip_accents' => array(_GLOSSARY_STRIP_ACCENTS, _GLOSSARY_DESC_STRIP_ACCENTS, 'yesno', null),
		'notify' => array(_GLOSSARY_NOTIFY, _GLOSSARY_DESC_NOTIFY, 'yesno', null),
		'notify_email' => array(_GLOSSARY_NOTIFY_EMAIL, _GLOSSARY_DESC_NOTIFY_EMAIL, 'input', 40),
		'thankuser' => array(_GLOSSARY_THANK_USER, _GLOSSARY_DESC_THANKUSER, 'yesno', null),
		'mail_thanks' => array(_GLOSSARY_MAIL_THANKS, _GLOSSARY_DESC_MAIL_THANKS, 'input', 60),
		'from_email' =>array(_GLOSSARY_FROM_EMAIL, _GLOSSARY_DESC_FROM_EMAIL, 'input', 40)
		);		
	}

	public function listTask () {
		$this->makeSpec();
		$config = cmsapiConfiguration::getInstance('com_glossary');
		$view = $this->admin->newHTMLClassCheck ('listConfigHTML', $this, 0, '');
		if ($view AND $this->admin->checkCallable($view, 'view')) $view->view($this->spec, $config);
	}
	
	public function saveTask () {
		$this->makeSpec();
		$config = cmsapiConfiguration::getInstance('com_glossary');
		// Set yes/no values to zero - no response from browser unless 1 selected
		foreach ($this->spec as $subspec) {
			foreach ($subspec[1] as $fieldname=>$info) {
				if ('yesno' == $info[2]) $config->$fieldname = empty($_POST[$fieldname]) ? 0 : 1;
				elseif (isset($_POST[$fieldname])) $config->$fieldname = $_POST[$fieldname];
			}
		}
		$config->save();
		$this->interface->redirect( "index2.php?option=com_glossary&act=cpanel", _CMSAPI_CONFIG_COMP );
	}
	
}