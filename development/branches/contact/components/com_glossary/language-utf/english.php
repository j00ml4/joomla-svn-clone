<?php

// English language file for Glossary component
// To customise any of these settings, DO NOT change the official list below.
// Instead, ADD your own definition immediately below, using the simple form of "define"
// For example:
// define ('_GLOSSARY_COMPONENT_TITLE', 'My Glossary');
// The standard definitions are applied conditionally, so your customized definitions
// will be effective, and the corresponding standard definition will be ignored.
// This way, it is easier to maintain your customization and apply it to new versions.
// Add custom definitions HERE=>

// The following are the standard definitions for the Glossary component
// Please DO NOT alter them for your own ease of maintenance.
// Model code: if (!defined('')) define ('', '');
//
// New items added for version 2.7

if (!defined('_GLOSSARY_CANCEL_SEARCH')) define ('_GLOSSARY_CANCEL_SEARCH', 'Clear Search Results');
if (!defined('_GLOSSARY_LINK')) define ('_GLOSSARY_LINK', 'Glossary Link');
if (!defined('_GLOSSARY_SEARCH_MULTIPLE')) define ('_GLOSSARY_SEARCH_MULTIPLE', 'Tick to search all glossaries');
if (!defined('_GLOSSARY_MUST_HAVE_NAME')) define ('_GLOSSARY_MUST_HAVE_NAME', 'Glossary not stored, it must have a name');
if (!defined('_GLOSSARY_CHOOSE_WHICH_ONE')) define ('_GLOSSARY_CHOOSE_WHICH_ONE', 'Select the Glossary for your term:');
if (!defined('_GLOSSARY_GLOSSARY_CAT')) define ('_GLOSSARY_GLOSSARY_CAT', 'Glossary');
if (!defined('_GLOSSARY_ALIASES')) define ('_GLOSSARY_ALIASES', 'Aliases (separate with |)');
if (!defined('_GLOSSARY_RELATED_TERMS')) define ('_GLOSSARY_RELATED_TERMS', 'Related Terms');
if (!defined('_GLOSSARY_SHOW_AUTHOR')) define ('_GLOSSARY_SHOW_AUTHOR', 'Show Author details:');
if (!defined('_GLOSSARY_DESC_SHOW_AUTHOR')) define ('_GLOSSARY_DESC_SHOW_AUTHOR', 'When showing a single term, include author details');
if (!defined('_GLOSSARY_SHOW_SOUNDEX')) define ('_GLOSSARY_SHOW_SOUNDEX', 'Allow "sounds like" for searches');
if (!defined('_GLOSSARY_DESC_SHOW_SOUNDEX')) define ('_GLOSSARY_DESC_SHOW_SOUNDEX', 'Include an option for soundex in searches');
if (!defined('_GLOSSARY_MAIL_THANKS')) define ('_GLOSSARY_MAIL_THANKS', 'Thank you message for new entries');
if (!defined('_GLOSSARY_DESC_MAIL_THANKS')) define ('_GLOSSARY_DESC_MAIL_THANKS', 'Text that will be sent to a user who adds a new term');
if (!defined('_GLOSSARY_DEFAULT_THANKS')) define ('_GLOSSARY_DEFAULT_THANKS', 'Thank you for contributing a new term to the glossary.');
if (!defined('_GLOSSARY_MAIL_ALERT')) define ('_GLOSSARY_MAIL_ALERT', 'User %s has added term %s to the glossary %s.');
if (!defined('_GLOSSARY_FROM_EMAIL')) define ('_GLOSSARY_FROM_EMAIL', 'From address for emails:');
if (!defined('_GLOSSARY_DESC_FROM_EMAIL')) define ('_GLOSSARY_DESC_FROM_EMAIL', 'Address from which thank you emails will be sent');
// First list standard definitions needed for cmsapi to function properly
if (!defined('_CMSAPI_CPANEL_RETURN')) define ('_CMSAPI_CPANEL_RETURN', 'Control Panel');
if (!defined('_CMSAPI_YES')) define ('_CMSAPI_YES', 'Yes');
if (!defined('_CMSAPI_NO')) define ('_CMSAPI_NO', 'No');
if (!defined('_CMSAPI_CANCEL')) define ('_CMSAPI_CANCEL', 'Cancel');
if (!defined('_CMSAPI_CONFIG_COMP')) define ('_CMSAPI_CONFIG_COMP', 'Configuration saved');
if (!defined('_CMSAPI_CLASS_NOT_PRESENT')) define ('_CMSAPI_CLASS_NOT_PRESENT', 'Component %s error: attempt to use non-existent class %s');
if (!defined('_CMSAPI_METHOD_NOT_PRESENT')) define ('_CMSAPI_METHOD_NOT_PRESENT', 'Component %s error: requested task %s but no suitable method in class %s');
if (!defined('_CMSAPI_DISPLAY_NUMBER')) DEFINE('_CMSAPI_DISPLAY_NUMBER','Display #');
if (!defined('_CMSAPI_PAGE_TEXT')) DEFINE ('_CMSAPI_PAGE_TEXT', 'Page');
if (!defined('_CMSAPI_PAGE_SHOW_RESULTS')) DEFINE('_CMSAPI_PAGE_SHOW_RESULTS','Show results ');
if (!defined('_CMSAPI_PAGE_SHOW_RANGE')) DEFINE('_CMSAPI_PAGE_SHOW_RANGE','%s to %s of %s');
if (!defined('_CMSAPI_PREVIOUS')) define ('_CMSAPI_PREVIOUS', 'Prev');
if (!defined('_CMSAPI_NEXT')) define ('_CMSAPI_NEXT', 'Next');
// Component specific definitions follow
if (!defined('_GLOSSARY_ID')) define ('_GLOSSARY_ID', 'ID');
if (!defined('_GLOSSARY_GLOSS_LANGUAGE')) define ('_GLOSSARY_GLOSS_LANGUAGE', 'Language (leave blank for default):');
if (!defined('_GLOSSARY_COMPONENT_TITLE')) define ('_GLOSSARY_COMPONENT_TITLE', 'Glossary');
if (!defined('_GLOSSARY_SELECT')) define ('_GLOSSARY_SELECT', 'Select:');
if (!defined('_GLOSSARY_ALL')) define ('_GLOSSARY_ALL', 'All');
if (!defined('_GLOSSARY_LIST_ENTRIES')) define ('_GLOSSARY_LIST_ENTRIES', 'List Entries');
if (!defined('_GLOSSARY_LIST_GLOSSARIES')) define ('_GLOSSARY_LIST_GLOSSARIES', 'List Glossaries');
if (!defined('_GLOSSARY_EDIT_ENTRIES')) define ('_GLOSSARY_EDIT_ENTRIES', 'Edit Entries');
if (!defined('_GLOSSARY_EDIT_GLOSSARIES')) define ('_GLOSSARY_EDIT_GLOSSARIES', 'Edit Glossaries');
if (!defined('_GLOSSARY_EDIT_CONFIG')) define ('_GLOSSARY_EDIT_CONFIG', 'Edit Configuration');
if (!defined('_GLOSSARY_USER_CONFIG')) define ('_GLOSSARY_USER_CONFIG', 'User');
if (!defined('_GLOSSARY_ADMIN_CONFIG')) define ('_GLOSSARY_ADMIN_CONFIG', 'Admin');
if (!defined('_GLOSSARY_ABOUT_HEADING')) define ('_GLOSSARY_ABOUT_HEADING', 'General Information');
if (!defined('_GLOSSARY_GLOSSARY_LIST')) define ('_GLOSSARY_GLOSSARY_LIST', 'Other glossaries available here');
if (!defined('_GLOSSARY_FILTER_TERM')) define ('_GLOSSARY_FILTER_TERM', 'Filter term (use regular expressions):');
if (!defined('_GLOSSARY_FILTER_DEFINITION')) define ('_GLOSSARY_FILTER_DEFINITION', 'Filter definition (words, optional boolean):');
if (!defined('_GLOSSARY_ALL_GLOSSARIES')) define ('_GLOSSARY_ALL_GLOSSARIES', 'All glossaries');
if (!defined('_GLOSSARY_REFRESH')) define ('_GLOSSARY_REFRESH', 'Refresh page');
// Glossary control panel terms
if (!defined('_GLOSSARY_CPANEL')) define ('_GLOSSARY_CPANEL', 'Control Panel');
if (!defined('_GLOSSARY_CPANEL_MANAGE_GLOSSARIES')) define ('_GLOSSARY_CPANEL_MANAGE_GLOSSARIES', 'Manage Glossaries');
if (!defined('_GLOSSARY_CPANEL_MANAGE_ENTRIES')) define ('_GLOSSARY_CPANEL_MANAGE_ENTRIES', 'Manage Entries');
if (!defined('_GLOSSARY_CPANEL_CONFIG')) define ('_GLOSSARY_CPANEL_CONFIG', 'Configuration');
if (!defined('_GLOSSARY_CPANEL_ABOUT')) define ('_GLOSSARY_CPANEL_ABOUT', 'About');
// Glossary entry headings
if (!defined('_GLOSSARY_TERM')) define ('_GLOSSARY_TERM', 'Term');
if (!defined('_GLOSSARY_LETTER')) define ('_GLOSSARY_LETTER', 'Letter (optional)');
if (!defined('_GLOSSARY_DEFINITION')) define ('_GLOSSARY_DEFINITION', 'Definition');
if (!defined('_GLOSSARY_DATE')) define ('_GLOSSARY_DATE', 'Date');
if (!defined('_GLOSSARY_PUBLISHED')) define ('_GLOSSARY_PUBLISHED', 'Published');
if (!defined('_GLOSSARY_NAME')) define ('_GLOSSARY_NAME', 'Name');
if (!defined('_GLOSSARY_DESCRIPTION')) define ('_GLOSSARY_DESCRIPTION', 'Glossary Description');
if (!defined('_GLOSSARY_GLOSSARY_SELECT')) define ('_GLOSSARY_GLOSSARY_SELECT', 'Select the glossary');
// Glossary entry edit labels - admin side
if (!defined('_GLOSSARY_AUTHOR_NAME')) define ('_GLOSSARY_AUTHOR_NAME', 'Author Name');
if (!defined('_GLOSSARY_LOCALITY')) define ('_GLOSSARY_LOCALITY', 'Author Locality');
if (!defined('_GLOSSARY_MAIL')) define ('_GLOSSARY_MAIL', 'Author eMail Address');
if (!defined('_GLOSSARY_PAGE')) define ('_GLOSSARY_PAGE', 'Author Home Page');
if (!defined('_GLOSSARY_COMMENT')) define ('_GLOSSARY_COMMENT', 'Comment');
// Glossary entry edit labels - user side
if (!defined('_GLOSSARY_MARKDOWN_USAGE')) define ('_GLOSSARY_MARKDOWN_USAGE', 'You can use <a href="http://daringfireball.net/projects/markdown/syntax">Markdown codes</a> in the definition but HTML is not permitted and will be removed.');
if (!defined('_GLOSSARY_USER_LOCALITY')) define ('_GLOSSARY_USER_LOCALITY', 'Your Locality');
if (!defined('_GLOSSARY_USER_MAIL')) define ('_GLOSSARY_USER_MAIL', 'Your eMail');
if (!defined('_GLOSSARY_USER_NAME')) define ('_GLOSSARY_USER_NAME', 'Your name');
if (!defined('_GLOSSARY_USER_URI')) define ('_GLOSSARY_USER_URI', 'Your homepage');
if (!defined('_GLOSSARY_USER_SUBMIT')) define ('_GLOSSARY_USER_SUBMIT', 'Submit');
if (!defined('_GLOSSARY_USER_CLEAR')) define ('_GLOSSARY_USER_CLEAR', 'Clear');
if (!defined('_GLOSSARY_APPEARS_AS')) define ('_GLOSSARY_APPEARS_AS', 'Definition will appear as shown - you can use <a href="http://daringfireball.net/projects/markdown/syntax">Markdown codes</a> to style definitions');
// Glossary item listing headings etc
if (!defined('_GLOSSARY_TERM_HEAD')) define ('_GLOSSARY_TERM_HEAD', 'Term');
if (!defined('_GLOSSARY_DEFINITION_HEAD')) define ('_GLOSSARY_DEFINITION_HEAD', 'Definition');
if (!defined('_GLOSSARY_ITEM_COUNT')) define ('_GLOSSARY_ITEM_COUNT', 'There are %d entries in this glossary.');
if (!defined('_GLOSSARY_IS_EMPTY')) define ('_GLOSSARY_IS_EMPTY', 'This selection has no entries');
if (!defined('_GLOSSARY_ADD_ENTRY')) define ('_GLOSSARY_ADD_ENTRY', 'Add a new entry');
// Glossary search texts
if (!defined('_GLOSSARY_SEARCH_INTRO')) define ('_GLOSSARY_SEARCH_INTRO', 'Search for glossary terms (regular expression allowed)');
if (!defined('_GLOSSARY_BEGINS_WITH')) define ('_GLOSSARY_BEGINS_WITH', 'Begins with');
if (!defined('_GLOSSARY_TERM_CONTAINS')) define ('_GLOSSARY_TERM_CONTAINS', 'Contains');
if (!defined('_GLOSSARY_EXACT_TERM')) define ('_GLOSSARY_EXACT_TERM', 'Exact term');
if (!defined('_GLOSSARY_SOUNDS_LIKE')) define ('_GLOSSARY_SOUNDS_LIKE', 'Sounds like');
if (!defined('_GLOSSARY_SEARCH_SEARCH')) define ('_GLOSSARY_SEARCH_SEARCH', 'search...');
if (!defined('_GLOSSARY_GO')) define ('_GLOSSARY_GO', 'Go');
// Definitions of glossary configuration items
if (!defined('_GLOSSARY_UTF8')) define ('_GLOSSARY_UTF8', 'Select UTF-8:');
if (!defined('_GLOSSARY_PER_PAGE')) define ('_GLOSSARY_PER_PAGE', 'Entries per Page:');
if (!defined('_GLOSSARY_ALLOWENTRY')) define ('_GLOSSARY_ALLOWENTRY', 'Allow Entries:');
if (!defined('_GLOSSARY_ANONENTRY')) define ('_GLOSSARY_ANONENTRY', 'Anonymous Entries:');
if (!defined('_GLOSSARY_HIDEAUTHOR')) define ('_GLOSSARY_HIDEAUTHOR', 'Hide Author:');
if (!defined('_GLOSSARY_USEEDITOR')) define ('_GLOSSARY_USEEDITOR', 'Use default Editor:');
if (!defined('_GLOSSARY_SHOWCATEGORIES')) define ('_GLOSSARY_SHOWCATEGORIES', 'Show Glossaries:');
if (!defined('_GLOSSARY_CATEGORIES_ABOVE')) define ('_GLOSSARY_CATEGORIES_ABOVE', 'Display Glossaries at top:');
if (!defined('_GLOSSARY_SHOWCATDESCRIPTIONS')) define ('_GLOSSARY_SHOWCATDESCRIPTIONS', 'Show Glossary Descriptions:');
if (!defined('_GLOSSARY_SHOWNUMBEROFENTRIES')) define ('_GLOSSARY_SHOWNUMBEROFENTRIES', 'Show count of terms');
if (!defined('_GLOSSARY_UTF8_ADMIN')) define ('_GLOSSARY_UTF8_ADMIN', 'Select UTF-8:');
if (!defined('_GLOSSARY_SHOW_LIST')) define ('_GLOSSARY_SHOW_LIST', 'Show a list of glossary entries:');
if (!defined('_GLOSSARY_TERMS_ONLY')) define ('_GLOSSARY_TERMS_ONLY', 'Only show terms:');
if (!defined('_GLOSSARY_SHOW_ALPHABET')) define ('_GLOSSARY_SHOW_ALPHABET', 'Show the alphabet above the list:');
if (!defined('_GLOSSARY_SHOW_ALPHABET_BELOW')) define ('_GLOSSARY_SHOW_ALPHABET_BELOW', 'Show the alphabet below list:');
if (!defined('_GLOSSARY_SHOW_SEARCH')) define ('_GLOSSARY_SHOW_SEARCH', 'Show glossary search:');
if (!defined('_GLOSSARY_SEARCH_ALL')) define ('_GLOSSARY_SEARCH_ALL', 'Search all glossaries:');
if (!defined('_GLOSSARY_STRIP_ACCENTS')) define ('_GLOSSARY_STRIP_ACCENTS', 'Strip accents:');
if (!defined('_GLOSSARY_AUTOPUBLISH')) define ('_GLOSSARY_AUTOPUBLISH', 'Autopublish entries:');
if (!defined('_GLOSSARY_NOTIFY')) define ('_GLOSSARY_NOTIFY', 'Notify webmaster:');
if (!defined('_GLOSSARY_NOTIFY_EMAIL')) define ('_GLOSSARY_NOTIFY_EMAIL', 'Webmaster\'s email:');
if (!defined('_GLOSSARY_THANK_USER')) define ('_GLOSSARY_THANK_USER', 'Thank user:');
if (!defined('_GLOSSARY_PAGE_SPREAD')) define ('_GLOSSARY_PAGE_SPREAD', 'Page spread:');
if (!defined('_GLOSSARY_LANGUAGE')) define ('_GLOSSARY_LANGUAGE', 'Glossary language override:');
if (!defined('_GLOSSARY_CALL_PLUGINS')) define ('_GLOSSARY_CALL_PLUGINS', 'Call content plugins for definitions');
// Descriptions of glossary configuration items
if (!defined('_GLOSSARY_DESC_UTF8')) define ('_GLOSSARY_DESC_UTF8', 'Glossary to tell database and browser to use UTF-8');
if (!defined('_GLOSSARY_DESC_PER_PAGE')) define ('_GLOSSARY_DESC_PER_PAGE', 'Number of entries shown per page.');
if (!defined('_GLOSSARY_DESC_ALLOWENTRY')) define ('_GLOSSARY_DESC_ALLOWENTRY', 'Allow the users to write new entries. (Editors, Publishers, Admins and Super Admins are allways allowed to add entries.)');
if (!defined('_GLOSSARY_DESC_ANONENTRY')) define ('_GLOSSARY_DESC_ANONENTRY', 'Allow unregistered users to write entries.');
if (!defined('_GLOSSARY_DESC_HIDEAUTHOR')) define ('_GLOSSARY_DESC_HIDEAUTHOR', 'Hide author details like name, location etc.');
if (!defined('_GLOSSARY_DESC_USEEDITOR')) define ('_GLOSSARY_DESC_USEEDITOR', 'Yes to use the default editor to add entries or No to use simple textarea');
if (!defined('_GLOSSARY_DESC_SHOWCATEGORIES')) define ('_GLOSSARY_DESC_SHOWCATEGORIES', 'If set to no, the component will only show the default glossary');
if (!defined('_GLOSSARY_DESC_CATEGORIES_ABOVE')) define ('_GLOSSARY_DESC_CATEGORIES_ABOVE', 'If set to no, will be at bottom');
if (!defined('_GLOSSARY_DESC_SHOWCATDESCRIPTIONS')) define ('_GLOSSARY_DESC_SHOWCATDESCRIPTIONS', 'If set to no, the glossary descriptions will not be display on frontend');
if (!defined('_GLOSSARY_DESC_SHOWNUMBEROFENTRIES')) define ('_GLOSSARY_DESC_SHOWNUMBEROFENTRIES', 'YES shows the number of entries on the frontpage.');
if (!defined('_GLOSSARY_DESC_UTF8_ADMIN')) define ('_GLOSSARY_DESC_UTF8_ADMIN', 'Glossary to tell database and browser to use UTF-8');
if (!defined('_GLOSSARY_DESC_SHOW_LIST')) define ('_GLOSSARY_DESC_SHOW_LIST', 'Otherwise, list will be suppressed.');
if (!defined('_GLOSSARY_DESC_TERMS_ONLY')) define ('_GLOSSARY_DESC_TERMS_ONLY', 'Descriptions will show only if this is No.');
if (!defined('_GLOSSARY_DESC_SHOW_ALPHABET')) define ('_GLOSSARY_DESC_SHOW_ALPHABET', 'Main glossary screen to show alphabet linking entries above the list');
if (!defined('_GLOSSARY_DESC_SHOW_ALPHABET_BELOW')) define ('_GLOSSARY_DESC_SHOW_ALPHABET_BELOW', 'Main glossary screen to show alphabet linking entries below the list');
if (!defined('_GLOSSARY_DESC_SHOW_SEARCH')) define ('_GLOSSARY_DESC_SHOW_SEARCH', 'Offer the user the ability to search:');
if (!defined('_GLOSSARY_DESC_SEARCH_ALL')) define ('_GLOSSARY_DESC_SEARCH_ALL', 'All glossaries, or if no, only the current one:');
if (!defined('_GLOSSARY_DESC_STRIP_ACCENTS')) define ('_GLOSSARY_DESC_STRIP_ACCENTS', 'Ignore accents when building the alphabet');
if (!defined('_GLOSSARY_DESC_AUTOPUBLISH')) define ('_GLOSSARY_DESC_AUTOPUBLISH', 'Autopublish new entries to the glossary.');
if (!defined('_GLOSSARY_DESC_NOTIFY')) define ('_GLOSSARY_DESC_NOTIFY', 'Notify webmaster when new entries arrive.');
if (!defined('_GLOSSARY_DESC_NOTIFY_EMAIL')) define ('_GLOSSARY_DESC_NOTIFY_EMAIL', 'Email address, where notifications are sent.');
if (!defined('_GLOSSARY_DESC_THANKUSER')) define ('_GLOSSARY_DESC_THANKUSER', 'Send \'Thank You\' mail to the user.');
if (!defined('_GLOSSARY_DESC_PAGE_SPREAD')) define ('_GLOSSARY_DESC_PAGE_SPREAD', 'How many other page numbers to show in page navigation');
if (!defined('_GLOSSARY_DESC_LANGUAGE')) define ('_GLOSSARY_DESC_LANGUAGE', 'Override automatic language selection');
if (!defined('_GLOSSARY_DESC_CALL_PLUGINS')) define ('_GLOSSARY_DESC_CALL_PLUGINS', 'If set, definition will be processed by any active content plugins');
// End of Glossary language definitions