<?php

// French language file for Glossary component
// Translated by Philippe Lem�nager - 2009-02-27
// Model code: if (!defined('')) define ('', '');
// First list standard definitions needed for cmsapi to function properly
if (!defined('_CMSAPI_CPANEL_RETURN')) define ('_CMSAPI_CPANEL_RETURN', 'Panneau de contr�le');
if (!defined('_CMSAPI_YES')) define ('_CMSAPI_YES', 'Oui');
if (!defined('_CMSAPI_NO')) define ('_CMSAPI_NO', 'Non');
if (!defined('_CMSAPI_CANCEL')) define ('_CMSAPI_CANCEL', 'Annuler');
if (!defined('_CMSAPI_CONFIG_COMP')) define ('_CMSAPI_CONFIG_COMP', 'Configuration sauvegard�e');
if (!defined('_CMSAPI_CLASS_NOT_PRESENT')) define ('_CMSAPI_CLASS_NOT_PRESENT', 'Composant %s erreur : essaie d\'utiliser la classe inexistante %s');
if (!defined('_CMSAPI_METHOD_NOT_PRESENT')) define ('_CMSAPI_METHOD_NOT_PRESENT', 'Composant %s erreur: t�che requise %s mais m�thode inapropri�e dans la classe %s');
if (!defined('_CMSAPI_DISPLAY_NUMBER')) DEFINE('_CMSAPI_DISPLAY_NUMBER','Afficher #');
if (!defined('_CMSAPI_PAGE_TEXT')) DEFINE ('_CMSAPI_PAGE_TEXT', 'Page');
if (!defined('_CMSAPI_PAGE_SHOW_RESULTS')) DEFINE('_CMSAPI_PAGE_SHOW_RESULTS','Montrer les r�sultats ');
if (!defined('_CMSAPI_PAGE_SHOW_RANGE')) DEFINE('_CMSAPI_PAGE_SHOW_RANGE','%s � %s de %s');
if (!defined('_CMSAPI_PREVIOUS')) define ('_CMSAPI_PREVIOUS', 'Pr�c.');
if (!defined('_CMSAPI_NEXT')) define ('_CMSAPI_NEXT', 'Suiv.');
// Component specific definitions follow
if (!defined('_GLOSSARY_COMPONENT_TITLE')) define ('_GLOSSARY_COMPONENT_TITLE', 'Glossaire');
if (!defined('_GLOSSARY_SELECT')) define ('_GLOSSARY_SELECT', 'S�lectionner :');
if (!defined('_GLOSSARY_ALL')) define ('_GLOSSARY_ALL', 'Tout');
if (!defined('_GLOSSARY_LIST_ENTRIES')) define ('_GLOSSARY_LIST_ENTRIES', 'Liste les entr�es');
if (!defined('_GLOSSARY_LIST_GLOSSARIES')) define ('_GLOSSARY_LIST_GLOSSARIES', 'Liste les glossaires');
if (!defined('_GLOSSARY_EDIT_ENTRIES')) define ('_GLOSSARY_EDIT_ENTRIES', '�dite les entr�es');
if (!defined('_GLOSSARY_EDIT_GLOSSARIES')) define ('_GLOSSARY_EDIT_GLOSSARIES', '�dite les glossaires');
if (!defined('_GLOSSARY_EDIT_CONFIG')) define ('_GLOSSARY_EDIT_CONFIG', '�dite la configuration');
if (!defined('_GLOSSARY_USER_CONFIG')) define ('_GLOSSARY_USER_CONFIG', 'Utilisateur');
if (!defined('_GLOSSARY_ADMIN_CONFIG')) define ('_GLOSSARY_ADMIN_CONFIG', 'Administrateur');
if (!defined('_GLOSSARY_ABOUT_HEADING')) define ('_GLOSSARY_ABOUT_HEADING', 'Information g�n�rale');
if (!defined('_GLOSSARY_GLOSSARY_LIST')) define ('_GLOSSARY_GLOSSARY_LIST', 'D\'autres glossaires sont disponibles ici');
if (!defined('_GLOSSARY_FILTER_TERM')) define ('_GLOSSARY_FILTER_TERM', 'Filtrer les termes (utiliser les expressions r�guli�res):');
if (!defined('_GLOSSARY_FILTER_DEFINITION')) define ('_GLOSSARY_FILTER_DEFINITION', 'Filtrer les d�finitions (mots, �ventuellement s�par�s par un bool�en):');
if (!defined('_GLOSSARY_ALL_GLOSSARIES')) define ('_GLOSSARY_ALL_GLOSSARIES', 'Tous les glossaires');
if (!defined('_GLOSSARY_REFRESH')) define ('_GLOSSARY_REFRESH', 'Rafra�chir la page');
// Glossary control panel terms
if (!defined('_GLOSSARY_CPANEL')) define ('_GLOSSARY_CPANEL', 'Panneau de contr�le');
if (!defined('_GLOSSARY_CPANEL_MANAGE_GLOSSARIES')) define ('_GLOSSARY_CPANEL_MANAGE_GLOSSARIES', 'G�rer les glossaires');
if (!defined('_GLOSSARY_CPANEL_MANAGE_ENTRIES')) define ('_GLOSSARY_CPANEL_MANAGE_ENTRIES', 'G�rer les entr�es');
if (!defined('_GLOSSARY_CPANEL_CONFIG')) define ('_GLOSSARY_CPANEL_CONFIG', 'Configuration');
if (!defined('_GLOSSARY_CPANEL_ABOUT')) define ('_GLOSSARY_CPANEL_ABOUT', '� propos');
// Glossary entry headings
if (!defined('_GLOSSARY_TERM')) define ('_GLOSSARY_TERM', 'Terme');
if (!defined('_GLOSSARY_LETTER')) define ('_GLOSSARY_LETTER', 'Lettre (optionnel)');
if (!defined('_GLOSSARY_DEFINITION')) define ('_GLOSSARY_DEFINITION', 'D�finition');
if (!defined('_GLOSSARY_DATE')) define ('_GLOSSARY_DATE', 'Date');
if (!defined('_GLOSSARY_PUBLISHED')) define ('_GLOSSARY_PUBLISHED', 'Publi�');
if (!defined('_GLOSSARY_NAME')) define ('_GLOSSARY_NAME', 'Nom');
if (!defined('_GLOSSARY_DESCRIPTION')) define ('_GLOSSARY_DESCRIPTION', 'Description du glossaire');
if (!defined('_GLOSSARY_GLOSSARY_SELECT')) define ('_GLOSSARY_GLOSSARY_SELECT', 'S�lectionner le glossaire');
// Glossary entry edit labels - admin side
if (!defined('_GLOSSARY_AUTHOR_NAME')) define ('_GLOSSARY_AUTHOR_NAME', 'Nom de l\'auteur');
if (!defined('_GLOSSARY_LOCALITY')) define ('_GLOSSARY_LOCALITY', 'Ville de l\'auteur');
if (!defined('_GLOSSARY_MAIL')) define ('_GLOSSARY_MAIL', 'Courriel de l\'auteur');
if (!defined('_GLOSSARY_PAGE')) define ('_GLOSSARY_PAGE', 'Page web de l\'auteur');
if (!defined('_GLOSSARY_COMMENT')) define ('_GLOSSARY_COMMENT', 'Commentaire');
// Glossary entry edit labels - user side
if (!defined('_GLOSSARY_MARKDOWN_USAGE')) define ('_GLOSSARY_MARKDOWN_USAGE', 'Vous pouvez utiliser <a href="http://daringfireball.net/projects/markdown/syntax">les codes Markdown</a> dans la d�finition mais le code HTML n\est pas autoris� et sera retir�.');
if (!defined('_GLOSSARY_USER_LOCALITY')) define ('_GLOSSARY_USER_LOCALITY', 'Votre ville');
if (!defined('_GLOSSARY_USER_MAIL')) define ('_GLOSSARY_USER_MAIL', 'Votre courriel');
if (!defined('_GLOSSARY_USER_NAME')) define ('_GLOSSARY_USER_NAME', 'Votre nom');
if (!defined('_GLOSSARY_USER_URI')) define ('_GLOSSARY_USER_URI', 'Votre page web');
if (!defined('_GLOSSARY_USER_SUBMIT')) define ('_GLOSSARY_USER_SUBMIT', 'Soumettre');
if (!defined('_GLOSSARY_USER_CLEAR')) define ('_GLOSSARY_USER_CLEAR', 'Effacer');
if (!defined('_GLOSSARY_APPEARS_AS')) define ('_GLOSSARY_APPEARS_AS', 'La d�finition appara�tra ainsi - Vous pouvez utiliser <a href="http://daringfireball.net/projects/markdown/syntax">les codes Markdown</a> pour styliser les d�finitions');
// Glossary item listing headings etc
if (!defined('_GLOSSARY_TERM_HEAD')) define ('_GLOSSARY_TERM_HEAD', 'Terme');
if (!defined('_GLOSSARY_DEFINITION_HEAD')) define ('_GLOSSARY_DEFINITION_HEAD', 'D�finition');
if (!defined('_GLOSSARY_ITEM_COUNT')) define ('_GLOSSARY_ITEM_COUNT', 'Il y a %d entr�es dans ce glossaire.');
if (!defined('_GLOSSARY_IS_EMPTY')) define ('_GLOSSARY_IS_EMPTY', 'Cette s�lection n\'a pas d\'entr�es');
if (!defined('_GLOSSARY_ADD_ENTRY')) define ('_GLOSSARY_ADD_ENTRY', 'Ajouter une nouvelle entr�e');
// Glossary search texts
if (!defined('_GLOSSARY_SEARCH_INTRO')) define ('_GLOSSARY_SEARCH_INTRO', 'Chercher des termes du glossaire (expression r�guli�re autoris�e)');
if (!defined('_GLOSSARY_BEGINS_WITH')) define ('_GLOSSARY_BEGINS_WITH', 'Commence par');
if (!defined('_GLOSSARY_TERM_CONTAINS')) define ('_GLOSSARY_TERM_CONTAINS', 'Contient');
if (!defined('_GLOSSARY_EXACT_TERM')) define ('_GLOSSARY_EXACT_TERM', 'Terme exact');
if (!defined('_GLOSSARY_SOUNDS_LIKE')) define ('_GLOSSARY_SOUNDS_LIKE', 'Se prononce comme');
if (!defined('_GLOSSARY_SEARCH_SEARCH')) define ('_GLOSSARY_SEARCH_SEARCH', 'cherche...');
if (!defined('_GLOSSARY_GO')) define ('_GLOSSARY_GO', 'Valider');
// Definitions of glossary configuration items
if (!defined('_GLOSSARY_UTF8')) define ('_GLOSSARY_UTF8', 'S�lectionner UTF-8 :');
if (!defined('_GLOSSARY_PER_PAGE')) define ('_GLOSSARY_PER_PAGE', 'Entr�es par page :');
if (!defined('_GLOSSARY_ALLOWENTRY')) define ('_GLOSSARY_ALLOWENTRY', 'Autorise les entr�es :');
if (!defined('_GLOSSARY_ANONENTRY')) define ('_GLOSSARY_ANONENTRY', 'Entr�es anonymes :');
if (!defined('_GLOSSARY_HIDEAUTHOR')) define ('_GLOSSARY_HIDEAUTHOR', 'Cacher l\'auteur :');
if (!defined('_GLOSSARY_USEEDITOR')) define ('_GLOSSARY_USEEDITOR', 'Utiliser l\'�diteur par d�faut :');
if (!defined('_GLOSSARY_SHOWCATEGORIES')) define ('_GLOSSARY_SHOWCATEGORIES', 'Montrer les glossaires :');
if (!defined('_GLOSSARY_CATEGORIES_ABOVE')) define ('_GLOSSARY_CATEGORIES_ABOVE', 'Montrer les glossaires en haut :');
if (!defined('_GLOSSARY_SHOWCATDESCRIPTIONS')) define ('_GLOSSARY_SHOWCATDESCRIPTIONS', 'Montrer les descriptions des glossaires :');
if (!defined('_GLOSSARY_SHOWNUMBEROFENTRIES')) define ('_GLOSSARY_SHOWNUMBEROFENTRIES', 'Montrer le nombre de termes :');
if (!defined('_GLOSSARY_UTF8_ADMIN')) define ('_GLOSSARY_UTF8_ADMIN', 'S�lectionner UTF-8:');
if (!defined('_GLOSSARY_SHOW_LIST')) define ('_GLOSSARY_SHOW_LIST', 'Montrer une liste des entr�es de glossaire :');
if (!defined('_GLOSSARY_SHOW_ALPHABET')) define ('_GLOSSARY_SHOW_ALPHABET', 'Montrer l\'alphabet :');
if (!defined('_GLOSSARY_SHOW_ALPHABET_BELOW')) define ('_GLOSSARY_SHOW_ALPHABET_BELOW', 'Montrer l\'alphabet sous la liste:');
if (!defined('_GLOSSARY_SHOW_SEARCH')) define ('_GLOSSARY_SHOW_SEARCH', 'Montrer la recherche dans le glossaire :');
if (!defined('_GLOSSARY_SEARCH_ALL')) define ('_GLOSSARY_SEARCH_ALL', 'Chercher tous les glossaires :');
if (!defined('_GLOSSARY_STRIP_ACCENTS')) define ('_GLOSSARY_STRIP_ACCENTS', 'Ignorer les accents :');
if (!defined('_GLOSSARY_AUTOPUBLISH')) define ('_GLOSSARY_AUTOPUBLISH', 'Autopublier les entr�es :');
if (!defined('_GLOSSARY_NOTIFY')) define ('_GLOSSARY_NOTIFY', 'Notifier le webmestre :');
if (!defined('_GLOSSARY_NOTIFY_EMAIL')) define ('_GLOSSARY_NOTIFY_EMAIL', 'Courriel du webmestre :');
if (!defined('_GLOSSARY_THANK_USER')) define ('_GLOSSARY_THANK_USER', 'Remercier l\'utilisateur :');
if (!defined('_GLOSSARY_PAGE_SPREAD')) define ('_GLOSSARY_PAGE_SPREAD', '�tendue de la page :');
if (!defined('_GLOSSARY_LANGUAGE')) define ('_GLOSSARY_LANGUAGE', 'Ignorer la langue du glossaire');
// Descriptions of glossary configuration items
if (!defined('_GLOSSARY_DESC_UTF8')) define ('_GLOSSARY_DESC_UTF8', 'Sp�cifier � la base de donn�es et au navigateur que ce glossaire utilise UTF-8');
if (!defined('_GLOSSARY_DESC_PER_PAGE')) define ('_GLOSSARY_DESC_PER_PAGE', 'Nombre d\'entr�es affich�es par page.');
if (!defined('_GLOSSARY_DESC_ALLOWENTRY')) define ('_GLOSSARY_DESC_ALLOWENTRY', 'Autoriser les utilisateurs � ajouter de nouvelles entr�es. (Editeurs, Publieurs, Administrateurs et Super-administrateurs sont toujours autoris�s � ajouter des entr�es.)');
if (!defined('_GLOSSARY_DESC_ANONENTRY')) define ('_GLOSSARY_DESC_ANONENTRY', 'Autoriser les utilisateurs non enregistr�s � ajouter de nouvelles entr�es.');
if (!defined('_GLOSSARY_DESC_HIDEAUTHOR')) define ('_GLOSSARY_DESC_HIDEAUTHOR', 'Cacher les d�tails de l\'auteur tels que nom, ville, etc.');
if (!defined('_GLOSSARY_DESC_USEEDITOR')) define ('_GLOSSARY_DESC_USEEDITOR', 'Oui pour utiliser l\'�diteur par d�faut pour ajouter des entr�es ou Non pour utiliser une simple zone de texte');
if (!defined('_GLOSSARY_DESC_SHOWCATEGORIES')) define ('_GLOSSARY_DESC_SHOWCATEGORIES', 'Si mis � Non, le composant affichera uniquement le glossaire par d�faut');
if (!defined('_GLOSSARY_DESC_CATEGORIES_ABOVE')) define ('_GLOSSARY_DESC_CATEGORIES_ABOVE', 'Si mis � Non, sera mis en bas');
if (!defined('_GLOSSARY_DESC_SHOWCATDESCRIPTIONS')) define ('_GLOSSARY_DESC_SHOWCATDESCRIPTIONS', 'Si mis � Non, les descriptions du glossaire ne seront pas affich�es sur le frontend');
if (!defined('_GLOSSARY_DESC_SHOWNUMBEROFENTRIES')) define ('_GLOSSARY_DESC_SHOWNUMBEROFENTRIES', 'OUI montre le nombre d\'entr�es sur la frontpage.');
if (!defined('_GLOSSARY_DESC_UTF8_ADMIN')) define ('_GLOSSARY_DESC_UTF8_ADMIN', 'Sp�cifier � la base de donn�es et au navigateur que ce glossaire utilise UTF-8');
if (!defined('_GLOSSARY_DESC_SHOW_LIST')) define ('_GLOSSARY_DESC_SHOW_LIST', 'Autrement, la liste sera supprim�e :');
if (!defined('_GLOSSARY_DESC_SHOW_ALPHABET')) define ('_GLOSSARY_DESC_SHOW_ALPHABET', 'Montrer les liens alphab�tiques sur l\'�cran principal du glossaire au dessus de la liste');
if (!defined('_GLOSSARY_DESC_SHOW_ALPHABET_BELOW')) define ('_GLOSSARY_DESC_SHOW_ALPHABET_BELOW', 'Montrer les liens alphab�tiques sur l\'�cran principal du glossaire au dessous de la liste');
if (!defined('_GLOSSARY_DESC_SHOW_SEARCH')) define ('_GLOSSARY_DESC_SHOW_SEARCH', 'Offre � l\utilisateur la possibilit� de rechercher :');
if (!defined('_GLOSSARY_DESC_SEARCH_ALL')) define ('_GLOSSARY_DESC_SEARCH_ALL', 'Tous les glossaires, ou si non, seulement le glossaire courant :');
if (!defined('_GLOSSARY_DESC_STRIP_ACCENTS')) define ('_GLOSSARY_DESC_STRIP_ACCENTS', 'Ignorer les accents en construisant l\'alphabet');
if (!defined('_GLOSSARY_DESC_AUTOPUBLISH')) define ('_GLOSSARY_DESC_AUTOPUBLISH', 'Autopublier les nouvelles entr�es dans le glossaire.');
if (!defined('_GLOSSARY_DESC_NOTIFY')) define ('_GLOSSARY_DESC_NOTIFY', 'Notifier au webmestre l\'arriv�e de nouvelles entr�es.');
if (!defined('_GLOSSARY_DESC_NOTIFY_EMAIL')) define ('_GLOSSARY_DESC_NOTIFY_EMAIL', 'Courriel pour l\'envoi des notifications.');
if (!defined('_GLOSSARY_DESC_THANKUSER')) define ('_GLOSSARY_DESC_THANKUSER', 'Envoyer courriel de remerciement � l\'utilisateur.');
if (!defined('_GLOSSARY_DESC_PAGE_SPREAD')) define ('_GLOSSARY_DESC_PAGE_SPREAD', 'Nombre de num�ros de page montr�s dans le navigateur de pages');
if (!defined('_GLOSSARY_DESC_LANGUAGE')) define ('_GLOSSARY_DESC_LANGUAGE', 'Ignorer la s�lection automatique du langage');
