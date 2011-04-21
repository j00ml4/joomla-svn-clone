<?php

// German language file for Glossary component by Klaus Panzlaff
// Corrections or suggestions please to tgw@kreis-re.net or ICQ 488558762

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
// First list standard definitions needed for cmsapi to function properly
if (!defined('_CMSAPI_CPANEL_RETURN')) define ('_CMSAPI_CPANEL_RETURN', 'Kontrollzentrum');
if (!defined('_CMSAPI_YES')) define ('_CMSAPI_YES', 'Ja');
if (!defined('_CMSAPI_NO')) define ('_CMSAPI_NO', 'Nein');
if (!defined('_CMSAPI_CANCEL')) define ('_CMSAPI_CANCEL', 'Abbruch');
if (!defined('_CMSAPI_CONFIG_COMP')) define ('_CMSAPI_CONFIG_COMP', 'Konfiguration gespeichert');
if (!defined('_CMSAPI_CLASS_NOT_PRESENT')) define ('_CMSAPI_CLASS_NOT_PRESENT', 'Component %s error: attempt to use non-existent class %s');
if (!defined('_CMSAPI_METHOD_NOT_PRESENT')) define ('_CMSAPI_METHOD_NOT_PRESENT', 'Component %s error: requested task %s but no suitable method in class %s');
if (!defined('_CMSAPI_DISPLAY_NUMBER')) DEFINE('_CMSAPI_DISPLAY_NUMBER','Zeige #');
if (!defined('_CMSAPI_PAGE_TEXT')) DEFINE ('_CMSAPI_PAGE_TEXT', 'Seite');
if (!defined('_CMSAPI_PAGE_SHOW_RESULTS')) DEFINE('_CMSAPI_PAGE_SHOW_RESULTS','Zeige Ergebnisse ');
if (!defined('_CMSAPI_PAGE_SHOW_RANGE')) DEFINE('_CMSAPI_PAGE_SHOW_RANGE','%s bis %s von %s');
if (!defined('_CMSAPI_PREVIOUS')) define ('_CMSAPI_PREVIOUS', 'Zurück');
if (!defined('_CMSAPI_NEXT')) define ('_CMSAPI_NEXT', 'Nächstes');
// Component specific definitions follow
if (!defined('_GLOSSARY_COMPONENT_TITLE')) define ('_GLOSSARY_COMPONENT_TITLE', 'Lexikon');
if (!defined('_GLOSSARY_SELECT')) define ('_GLOSSARY_SELECT', 'Bitte wählen:');
if (!defined('_GLOSSARY_ALL')) define ('_GLOSSARY_ALL', 'Alle');
if (!defined('_GLOSSARY_LIST_ENTRIES')) define ('_GLOSSARY_LIST_ENTRIES', 'Einträge auflisten');
if (!defined('_GLOSSARY_LIST_GLOSSARIES')) define ('_GLOSSARY_LIST_GLOSSARIES', 'Lexika auflisten');
if (!defined('_GLOSSARY_EDIT_ENTRIES')) define ('_GLOSSARY_EDIT_ENTRIES', 'Einträge editieren');
if (!defined('_GLOSSARY_EDIT_GLOSSARIES')) define ('_GLOSSARY_EDIT_GLOSSARIES', 'Lexika editieren');
if (!defined('_GLOSSARY_EDIT_CONFIG')) define ('_GLOSSARY_EDIT_CONFIG', 'Konfiguration editieren');
if (!defined('_GLOSSARY_USER_CONFIG')) define ('_GLOSSARY_USER_CONFIG', 'Benutzer');
if (!defined('_GLOSSARY_ADMIN_CONFIG')) define ('_GLOSSARY_ADMIN_CONFIG', 'Admin');
if (!defined('_GLOSSARY_ABOUT_HEADING')) define ('_GLOSSARY_ABOUT_HEADING', 'Allgemeine Info');
if (!defined('_GLOSSARY_GLOSSARY_LIST')) define ('_GLOSSARY_GLOSSARY_LIST', 'Andere Lexika sind hier verfügbar');
if (!defined('_GLOSSARY_FILTER_TERM')) define ('_GLOSSARY_FILTER_TERM', 'Filter (Benutzen Sie normale Begriffe):');
if (!defined('_GLOSSARY_FILTER_DEFINITION')) define ('_GLOSSARY_FILTER_DEFINITION', 'Filterdefinition (Worte, obtional Verknüpfungen):');
if (!defined('_GLOSSARY_ALL_GLOSSARIES')) define ('_GLOSSARY_ALL_GLOSSARIES', 'Alle Lexika');
if (!defined('_GLOSSARY_REFRESH')) define ('_GLOSSARY_REFRESH', 'Seite neu laden');
// Glossary control panel terms
if (!defined('_GLOSSARY_CPANEL')) define ('_GLOSSARY_CPANEL', 'Kontrollmenü');
if (!defined('_GLOSSARY_CPANEL_MANAGE_GLOSSARIES')) define ('_GLOSSARY_CPANEL_MANAGE_GLOSSARIES', 'Lexika verwalten');
if (!defined('_GLOSSARY_CPANEL_MANAGE_ENTRIES')) define ('_GLOSSARY_CPANEL_MANAGE_ENTRIES', 'Einträge verwalten');
if (!defined('_GLOSSARY_CPANEL_CONFIG')) define ('_GLOSSARY_CPANEL_CONFIG', 'Konfiguration');
if (!defined('_GLOSSARY_CPANEL_ABOUT')) define ('_GLOSSARY_CPANEL_ABOUT', 'Über');
// Glossary entry headings
if (!defined('_GLOSSARY_TERM')) define ('_GLOSSARY_TERM', 'Begriff');
if (!defined('_GLOSSARY_LETTER')) define ('_GLOSSARY_LETTER', 'Buchstabe (optional)');
if (!defined('_GLOSSARY_DEFINITION')) define ('_GLOSSARY_DEFINITION', 'Definition');
if (!defined('_GLOSSARY_DATE')) define ('_GLOSSARY_DATE', 'Datum');
if (!defined('_GLOSSARY_PUBLISHED')) define ('_GLOSSARY_PUBLISHED', 'Veröffentlicht');
if (!defined('_GLOSSARY_NAME')) define ('_GLOSSARY_NAME', 'Name');
if (!defined('_GLOSSARY_DESCRIPTION')) define ('_GLOSSARY_DESCRIPTION', 'Lexikonname');
if (!defined('_GLOSSARY_GLOSSARY_SELECT')) define ('_GLOSSARY_GLOSSARY_SELECT', 'Wähle das Lexikon');
// Glossary entry edit labels - admin side
if (!defined('_GLOSSARY_AUTHOR_NAME')) define ('_GLOSSARY_AUTHOR_NAME', 'Name Verfasser');
if (!defined('_GLOSSARY_LOCALITY')) define ('_GLOSSARY_LOCALITY', 'Ort Verfasser');
if (!defined('_GLOSSARY_MAIL')) define ('_GLOSSARY_MAIL', 'Email Adresse Verfasser');
if (!defined('_GLOSSARY_PAGE')) define ('_GLOSSARY_PAGE', 'Webseite Verfasser');
if (!defined('_GLOSSARY_COMMENT')) define ('_GLOSSARY_COMMENT', 'Kommentar');
// Glossary entry edit labels - user side
if (!defined('_GLOSSARY_MARKDOWN_USAGE')) define ('_GLOSSARY_MARKDOWN_USAGE', 'Du kannst <a href="http://daringfireball.net/projects/markdown/syntax">Markdown Codes</a> in der Definition verwenden aber HTML ist nicht erlaubt und wird entfernt.');
if (!defined('_GLOSSARY_USER_LOCALITY')) define ('_GLOSSARY_USER_LOCALITY', 'Dein Wohnort');
if (!defined('_GLOSSARY_USER_MAIL')) define ('_GLOSSARY_USER_MAIL', 'Deine Email');
if (!defined('_GLOSSARY_USER_NAME')) define ('_GLOSSARY_USER_NAME', 'Dein Name');
if (!defined('_GLOSSARY_USER_URI')) define ('_GLOSSARY_USER_URI', 'Deine Webseite');
if (!defined('_GLOSSARY_USER_SUBMIT')) define ('_GLOSSARY_USER_SUBMIT', 'Abschicken');
if (!defined('_GLOSSARY_USER_CLEAR')) define ('_GLOSSARY_USER_CLEAR', 'Löschen');
if (!defined('_GLOSSARY_APPEARS_AS')) define ('_GLOSSARY_APPEARS_AS', 'Die Definition erscheint so wie angezeigt - Du kannst <a href="http://daringfireball.net/projects/markdown/syntax">Markdown Codes</a> benutzen um Deine Definitionen zu sytlen');
// Glossary item listing headings etc
if (!defined('_GLOSSARY_TERM_HEAD')) define ('_GLOSSARY_TERM_HEAD', 'Begriff');
if (!defined('_GLOSSARY_DEFINITION_HEAD')) define ('_GLOSSARY_DEFINITION_HEAD', 'Definition');
if (!defined('_GLOSSARY_ITEM_COUNT')) define ('_GLOSSARY_ITEM_COUNT', 'Es sind %d Einträge im Lexikon.');
if (!defined('_GLOSSARY_IS_EMPTY')) define ('_GLOSSARY_IS_EMPTY', 'Diese Auswahl hat keine Einträge');
if (!defined('_GLOSSARY_ADD_ENTRY')) define ('_GLOSSARY_ADD_ENTRY', 'Einen neune Eintrag hinzufügen');
// Glossary search texts
if (!defined('_GLOSSARY_SEARCH_INTRO')) define ('_GLOSSARY_SEARCH_INTRO', 'Nach Lexikon-Einträgen suchen (Nur normale Begriffe sind erlaubt)');
if (!defined('_GLOSSARY_BEGINS_WITH')) define ('_GLOSSARY_BEGINS_WITH', 'Beginnt mit');
if (!defined('_GLOSSARY_TERM_CONTAINS')) define ('_GLOSSARY_TERM_CONTAINS', 'Enthält');
if (!defined('_GLOSSARY_EXACT_TERM')) define ('_GLOSSARY_EXACT_TERM', 'Exakter Begriff');
if (!defined('_GLOSSARY_SOUNDS_LIKE')) define ('_GLOSSARY_SOUNDS_LIKE', 'Klingt wie');
if (!defined('_GLOSSARY_SEARCH_SEARCH')) define ('_GLOSSARY_SEARCH_SEARCH', 'suche...');
if (!defined('_GLOSSARY_GO')) define ('_GLOSSARY_GO', 'Start');
// Definitions of glossary configuration items
if (!defined('_GLOSSARY_UTF8')) define ('_GLOSSARY_UTF8', 'Select UTF-8:');
if (!defined('_GLOSSARY_PER_PAGE')) define ('_GLOSSARY_PER_PAGE', 'Einträge pro Seite:');
if (!defined('_GLOSSARY_ALLOWENTRY')) define ('_GLOSSARY_ALLOWENTRY', 'Erlaube Einträge:');
if (!defined('_GLOSSARY_ANONENTRY')) define ('_GLOSSARY_ANONENTRY', 'Anonyme Beiträge:');
if (!defined('_GLOSSARY_HIDEAUTHOR')) define ('_GLOSSARY_HIDEAUTHOR', 'Verberge Author:');
if (!defined('_GLOSSARY_USEEDITOR')) define ('_GLOSSARY_USEEDITOR', 'Benutze Standardeditor:');
if (!defined('_GLOSSARY_SHOWCATEGORIES')) define ('_GLOSSARY_SHOWCATEGORIES', 'Zeige Lexikon:');
if (!defined('_GLOSSARY_CATEGORIES_ABOVE')) define ('_GLOSSARY_CATEGORIES_ABOVE', 'Zeige Lexikon oben an:');
if (!defined('_GLOSSARY_SHOWCATDESCRIPTIONS')) define ('_GLOSSARY_SHOWCATDESCRIPTIONS', 'Zeige Lexikonbeschreibungen:');
if (!defined('_GLOSSARY_SHOWNUMBEROFENTRIES')) define ('_GLOSSARY_SHOWNUMBEROFENTRIES', 'Zeige Anzahl der Begriffe');
if (!defined('_GLOSSARY_UTF8_ADMIN')) define ('_GLOSSARY_UTF8_ADMIN', 'Wähle UTF-8:');
if (!defined('_GLOSSARY_SHOW_LIST')) define ('_GLOSSARY_SHOW_LIST', 'Zeige eine Liste von Lexikon-Einträgen:');
if (!defined('_GLOSSARY_SHOW_ALPHABET')) define ('_GLOSSARY_SHOW_ALPHABET', 'Zeige Alphabet über der Liste:');
if (!defined('_GLOSSARY_SHOW_ALPHABET_BELOW')) define ('_GLOSSARY_SHOW_ALPHABET_BELOW', 'Zeige Alphabet unter der Liste');
if (!defined('_GLOSSARY_SHOW_SEARCH')) define ('_GLOSSARY_SHOW_SEARCH', 'Zeige Lexikon-Suche:');
if (!defined('_GLOSSARY_SEARCH_ALL')) define ('_GLOSSARY_SEARCH_ALL', 'Durchsuche alle Lexika:');
if (!defined('_GLOSSARY_STRIP_ACCENTS')) define ('_GLOSSARY_STRIP_ACCENTS', 'Entferne Akzente:');
if (!defined('_GLOSSARY_AUTOPUBLISH')) define ('_GLOSSARY_AUTOPUBLISH', 'Veröffentliche Beiträge automatisch:');
if (!defined('_GLOSSARY_NOTIFY')) define ('_GLOSSARY_NOTIFY', 'Benachrichtige Webmaster:');
if (!defined('_GLOSSARY_NOTIFY_EMAIL')) define ('_GLOSSARY_NOTIFY_EMAIL', 'Webmaster\'s Email:');
if (!defined('_GLOSSARY_THANK_USER')) define ('_GLOSSARY_THANK_USER', 'Dem Benutzer danken:');
if (!defined('_GLOSSARY_PAGE_SPREAD')) define ('_GLOSSARY_PAGE_SPREAD', 'Seitenbreite:');
if (!defined('_GLOSSARY_LANGUAGE')) define ('_GLOSSARY_LANGUAGE', 'Lexikonsprache überspringen');
// Descriptions of glossary configuration items
if (!defined('_GLOSSARY_DESC_UTF8')) define ('_GLOSSARY_DESC_UTF8', 'Das Lexikon soll Datenbank und Browser mit UTF-8 ansprechen');
if (!defined('_GLOSSARY_DESC_PER_PAGE')) define ('_GLOSSARY_DESC_PER_PAGE', 'Anzahl der Einträge pro Seite.');
if (!defined('_GLOSSARY_DESC_ALLOWENTRY')) define ('_GLOSSARY_DESC_ALLOWENTRY', 'Benutzern erlauben, neue Einträge zu schreiben. (Editors, Publishers, Admins and Super Admins haben immer Schreiberlaubnis.)');
if (!defined('_GLOSSARY_DESC_ANONENTRY')) define ('_GLOSSARY_DESC_ANONENTRY', 'Unregistrierten Benutzern erlauben, neue Einträge zu schreiben.');
if (!defined('_GLOSSARY_DESC_HIDEAUTHOR')) define ('_GLOSSARY_DESC_HIDEAUTHOR', 'Details der Authoren verbergen (Name, Ort etc.)');
if (!defined('_GLOSSARY_DESC_USEEDITOR')) define ('_GLOSSARY_DESC_USEEDITOR', 'JA um den Standardeditor zu benutzen, NEIN für einfaches Textfeld');
if (!defined('_GLOSSARY_DESC_SHOWCATEGORIES')) define ('_GLOSSARY_DESC_SHOWCATEGORIES', 'Bei NEIN wird nur das Standardlexikon angezeigt');
if (!defined('_GLOSSARY_DESC_CATEGORIES_ABOVE')) define ('_GLOSSARY_DESC_CATEGORIES_ABOVE', 'Bei NEIN erscheint es unten');
if (!defined('_GLOSSARY_DESC_SHOWCATDESCRIPTIONS')) define ('_GLOSSARY_DESC_SHOWCATDESCRIPTIONS', 'Bei NEIN wird die Lexikonbeschreibung nicht im Frontend angezeigt');
if (!defined('_GLOSSARY_DESC_SHOWNUMBEROFENTRIES')) define ('_GLOSSARY_DESC_SHOWNUMBEROFENTRIES', 'JA zeigt die Anzahl der Einträge im Frontend an.');
if (!defined('_GLOSSARY_DESC_UTF8_ADMIN')) define ('_GLOSSARY_DESC_UTF8_ADMIN', 'Das Lexikon soll Datenbank und Browser mit UTF-8 ansprechen');
if (!defined('_GLOSSARY_DESC_SHOW_LIST')) define ('_GLOSSARY_DESC_SHOW_LIST', 'Ansonsten wird die Liste unterdrückt:');
if (!defined('_GLOSSARY_DESC_SHOW_ALPHABET')) define ('_GLOSSARY_DESC_SHOW_ALPHABET', 'Lexikon Hauptbildschirm soll Alphabetverlinkungen über der Liste anzeigen');
if (!defined('_GLOSSARY_DESC_SHOW_ALPHABET_BELOW')) define ('_GLOSSARY_DESC_SHOW_ALPHABET_BELOW', 'Lexikon Hauptbildschirm soll Alphabetverlinkungen unter der Liste anzeigen');
if (!defined('_GLOSSARY_DESC_SHOW_SEARCH')) define ('_GLOSSARY_DESC_SHOW_SEARCH', 'Dem Benutzer die Suchoption anzeigen:');
if (!defined('_GLOSSARY_DESC_SEARCH_ALL')) define ('_GLOSSARY_DESC_SEARCH_ALL', 'Alle Lexika und bei NEIN nur das Standardlexikon:');
if (!defined('_GLOSSARY_DESC_STRIP_ACCENTS')) define ('_GLOSSARY_DESC_STRIP_ACCENTS', 'Akzente bei der Erstellung des lexikons ignorieren');
if (!defined('_GLOSSARY_DESC_AUTOPUBLISH')) define ('_GLOSSARY_DESC_AUTOPUBLISH', 'Neue Einträge automatisch im Lexikon veröffentlichen.');
if (!defined('_GLOSSARY_DESC_NOTIFY')) define ('_GLOSSARY_DESC_NOTIFY', 'Den Webmaster bei neuen Einträgen benachrichtigen.');
if (!defined('_GLOSSARY_DESC_NOTIFY_EMAIL')) define ('_GLOSSARY_DESC_NOTIFY_EMAIL', 'Emailadresse, an die die Benachrichtigungen gesendet werden.');
if (!defined('_GLOSSARY_DESC_THANKUSER')) define ('_GLOSSARY_DESC_THANKUSER', 'Sende \'Danke\' Mail an den User.'); 
if (!defined('_GLOSSARY_DESC_PAGE_SPREAD')) define ('_GLOSSARY_DESC_PAGE_SPREAD', 'Wie viele andere Seitenzahlen in der Seitennavigation anzeigen');
if (!defined('_GLOSSARY_DESC_LANGUAGE')) define ('_GLOSSARY_DESC_LANGUAGE', 'Überspringe automatische Sprachauswahl');