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

@define( 'MARKDOWN_PARSER_CLASS',  'Markdown_Parser' );

cmsapiInterface::getInstance()->addAutoloadClasses('components/com_glossary/', array(
	'Markdown_Parser' => 'markdown/markdown',
	'sef_glossary' => 'sef_ext',
	'cmsapiAdminManager' => 'controller-admin-classes/cmsapiAdmin',
	'glossaryEntry' => 'problem-classes/glossaryEntry',
	'glossaryEntryManager' => 'problem-classes/glossaryEntryManager',
	'glossaryGlossary' => 'problem-classes/glossaryGlossary',
	'glossaryGlossaryManager' => 'problem-classes/glossaryGlossaryManager',
	'glossaryUserControllers' => 'controller-classes/glossaryControllers',
	'glossary_edit_Controller' => 'controller-classes/glossary_edit_Controller',
	'glossary_save_Controller' => 'controller-classes/glossary_save_Controller',
	'glossary_list_Controller' => 'controller-classes/glossary_list_Controller',
	'glossary_view_Controller' => 'controller-classes/glossary_view_Controller',
	'glossaryHTML' => 'view-classes/glossaryHTML',
	'glossaryAlphabetHTML' => 'view-classes/glossaryAlphabetHTML',
	'glossaryEditHTML' => 'view-classes/glossaryEditHTML',
	'glossaryGlossaryHTML' => 'view-classes/glossaryGlossaryHTML',
	'glossaryListHTML' => 'view-classes/glossaryListHTML',
	'glossarySearchHTML' => 'view-classes/glossarySearchHTML',
	'glossaryUserHTML' => 'view-classes/glossaryUserHTML',
	'glossaryAdminConfig' => 'controller-admin-classes/glossaryAdminConfig',
	'glossaryAdminEntries' => 'controller-admin-classes/glossaryAdminEntries',
	'glossaryAdminGlossaries' => 'controller-admin-classes/glossaryAdminGlossaries',
	'basicHTML' => 'view-admin-classes/basicHTML',
	'cmsapiAdminHTML' => 'view-admin-classes/basicHTML',
	'editEntriesHTML' => 'view-admin-classes/editEntriesHTML',
	'editGlossariesHTML' => 'view-admin-classes/editGlossariesHTML',
	'listAboutHTML' => 'view-admin-classes/listAboutHTML',
	'listConfigHTML' => 'view-admin-classes/listConfigHTML',
	'listCpanelHTML' => 'view-admin-classes/listCpanelHTML',
	'listEntriesHTML' => 'view-admin-classes/listEntriesHTML',
	'listGlossariesHTML' => 'view-admin-classes/listGlossariesHTML',
	'glossaryToolbar' => 'view-admin-classes/glossaryToolbar',
	'glossary_plugin_content' => 'plugin-classes/glossary_plugin_content',
	'glossary_plugin_search' => 'plugin-classes/glossary_plugin_search',
	'mod_glossaryBase' => 'module-classes/mod_glossaryBase',
	'mod_glossaryList' => 'module-classes/mod_glossarylist',
	'mod_glossaryNewest' => 'module-classes/mod_glossarynewest'
));