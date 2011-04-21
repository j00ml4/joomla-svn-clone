<?php

/**************************************************************
* This file is part of Glossary
* Copyright (c) 2008-9 Martin Brampton
* Issued as open source under GNU/GPL
* For support and other information, visit http://remository.com
* To contact Martin Brampton, write to martin@remository.com
*
* Please see glossary.php for more details
*/

// Load constants and problem domain classes

// Don't allow direct linking
if (!defined( '_VALID_MOS' ) AND !defined('_JEXEC')) die( 'Direct Access to this location is not allowed.' );

class glossaryHTML {
	protected $gconfig = null;
	protected $interface = null;
	
	public function __construct () {
		$this->gconfig = cmsapiConfiguration::getInstance('com_glossary');
		$this->interface = cmsapiInterface::getInstance();
	}

	protected function show ($string) {
		return (!function_exists('version_compare') OR version_compare(PHP_VERSION, '5.2.3') < 0) ? htmlspecialchars($string, ENT_QUOTES, _CMSAPI_CHARSET) : htmlspecialchars($string, ENT_QUOTES, _CMSAPI_CHARSET, true);
	}

	protected function showInDiv ($string) {
		if ($string) return <<<IN_DIV

					<div>{$this->show($string)}</div>

IN_DIV;

	}

	protected function showHTMLInDiv ($string) {
		if ($string) return <<<IN_DIV

					<div>{$this->showHTML($string)}</div>

IN_DIV;

	}

	protected function showHTML ($string) {
		$ampencode = '/(&(?!(#[0-9]{1,5};))(?!([0-9a-zA-Z]{1,10};)))/';
		return preg_replace($ampencode, '&amp;', $string);
	}
	
}
