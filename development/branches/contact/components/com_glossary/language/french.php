<?php

// French language file for Glossary component
// Translated by Philippe Leménager - 2009-02-27
// Model code: if (!defined('')) define ('', '');
// First list standard definitions needed for cmsapi to function properly
if (!defined('_CMSAPI_CPANEL_RETURN')) define ('_CMSAPI_CPANEL_RETURN', 'Panneau de contrôle');
if (!defined('_CMSAPI_YES')) define ('_CMSAPI_YES', 'Oui');
if (!defined('_CMSAPI_NO')) define ('_CMSAPI_NO', 'Non');
if (!defined('_CMSAPI_CANCEL')) define ('_CMSAPI_CANCEL', 'Annuler');
if (!defined('_CMSAPI_CONFIG_COMP')) define ('_CMSAPI_CONFIG_COMP', 'Configuration sauvegardée');
if (!defined('_CMSAPI_CLASS_NOT_PRESENT')) define ('_CMSAPI_CLASS_NOT_PRESENT', 'Composant %s erreur : essaie d\'utiliser la classe inexistante %s');
if (!defined('_CMSAPI_METHOD_NOT_PRESENT')) define ('_CMSAPI_METHOD_NOT_PRESENT', 'Composant %s erreur: tâche requise %s mais méthode inapropriée dans la classe %s');
if (!defined('_CMSAPI_DISPLAY_NUMBER')) DEFINE('_CMSAPI_DISPLAY_NUMBER','Afficher #');
if (!defined('_CMSAPI_PAGE_TEXT')) DEFINE ('_CMSAPI_PAGE_TEXT', 'Page');
if (!defined('_CMSAPI_PAGE_SHOW_RESULTS')) DEFINE('_CMSAPI_PAGE_SHOW_RESULTS','Montrer les résultats ');
if (!defined('_CMSAPI_PAGE_SHOW_RANGE')) DEFINE('_CMSAPI_PAGE_SHOW_RANGE','%s à %s de %s');
if (!defined('_CMSAPI_PREVIOUS')) define ('_CMSAPI_PREVIOUS', 'Préc.');
if (!defined('_CMSAPI_NEXT')) define ('_CMSAPI_NEXT', 'Suiv.');
// Component specific definitions follow
if (!defined('_GLOSSARY_COMPONENT_TITLE')) define ('_GLOSSARY_COMPONENT_TITLE', 'Glossaire');
if (!defined('_GLOSSARY_SELECT')) define ('_GLOSSARY_SELECT', 'Sélectionner :');
if (!defined('_GLOSSARY_ALL')) define ('_GLOSSARY_ALL', 'Tout');
if (!defined('_GLOSSARY_LIST_ENTRIES')) define ('_GLOSSARY_LIST_ENTRIES', 'Liste les entrées');
if (!defined('_GLOSSARY_LIST_GLOSSARIES')) define ('_GLOSSARY_LIST_GLOSSARIES', 'Liste les glossaires');
if (!defined('_GLOSSARY_EDIT_ENTRIES')) define ('_GLOSSARY_EDIT_ENTRIES', 'édite les entrées');
if (!defined('_GLOSSARY_EDIT_GLOSSARIES')) define ('_GLOSSARY_EDIT_GLOSSARIES', 'édite les glossaires');
if (!defined('_GLOSSARY_EDIT_CONFIG')) define ('_GLOSSARY_EDIT_CONFIG', 'édite la configuration');
if (!defined('_GLOSSARY_USER_CONFIG')) define ('_GLOSSARY_USER_CONFIG', 'Utilisateur');
if (!defined('_GLOSSARY_ADMIN_CONFIG')) define ('_GLOSSARY_ADMIN_CONFIG', 'Administrateur');
if (!defined('_GLOSSARY_ABOUT_HEADING')) define ('_GLOSSARY_ABOUT_HEADING', 'Information générale');
if (!defined('_GLOSSARY_GLOSSARY_LIST')) define ('_GLOSSARY_GLOSSARY_LIST', 'D\'autres glossaires sont disponibles ici');
if (!defined('_GLOSSARY_FILTER_TERM')) define ('_GLOSSARY_FILTER_TERM', 'Filtrer les termes (utiliser les expressions régulières):');
if (!defined('_GLOSSARY_FILTER_DEFINITION')) define ('_GLOSSARY_FILTER_DEFINITION', 'Filtrer les définitions (mots, éventuellement séparés par un booléen):');
if (!defined('_GLOSSARY_ALL_GLOSSARIES')) define ('_GLOSSARY_ALL_GLOSSARIES', 'Tous les glossaires');
if (!defined('_GLOSSARY_REFRESH')) define ('_GLOSSARY_REFRESH', 'Rafraîchir la page');
// Glossary control panel terms
if (!defined('_GLOSSARY_CPANEL')) define ('_GLOSSARY_CPANEL', 'Panneau de contrôle');
if (!defined('_GLOSSARY_CPANEL_MANAGE_GLOSSARIES')) define ('_GLOSSARY_CPANEL_MANAGE_GLOSSARIES', 'Gérer les glossaires');
if (!defined('_GLOSSARY_CPANEL_MANAGE_ENTRIES')) define ('_GLOSSARY_CPANEL_MANAGE_ENTRIES', 'Gérer les entrées');
if (!defined('_GLOSSARY_CPANEL_CONFIG')) define ('_GLOSSARY_CPANEL_CONFIG', 'Configuration');
if (!defined('_GLOSSARY_CPANEL_ABOUT')) define ('_GLOSSARY_CPANEL_ABOUT', 'à propos');
// Glossary entry headings
if (!defined('_GLOSSARY_TERM')) define ('_GLOSSARY_TERM', 'Terme');
if (!defined('_GLOSSARY_LETTER')) define ('_GLOSSARY_LETTER', 'Lettre (optionnel)');
if (!defined('_GLOSSARY_DEFINITION')) define ('_GLOSSARY_DEFINITION', 'Définition');
if (!defined('_GLOSSARY_DATE')) define ('_GLOSSARY_DATE', 'Date');
if (!defined('_GLOSSARY_PUBLISHED')) define ('_GLOSSARY_PUBLISHED', 'Publié');
if (!defined('_GLOSSARY_NAME')) define ('_GLOSSARY_NAME', 'Nom');
if (!defined('_GLOSSARY_DESCRIPTION')) define ('_GLOSSARY_DESCRIPTION', 'Description du glossaire');
if (!defined('_GLOSSARY_GLOSSARY_SELECT')) define ('_GLOSSARY_GLOSSARY_SELECT', 'Sélectionner le glossaire');
// Glossary entry edit labels - admin side
if (!defined('_GLOSSARY_AUTHOR_NAME')) define ('_GLOSSARY_AUTHOR_NAME', 'Nom de l\'auteur');
if (!defined('_GLOSSARY_LOCALITY')) define ('_GLOSSARY_LOCALITY', 'Ville de l\'auteur');
if (!defined('_GLOSSARY_MAIL')) define ('_GLOSSARY_MAIL', 'Courriel de l\'auteur');
if (!defined('_GLOSSARY_PAGE')) define ('_GLOSSARY_PAGE', 'Page web de l\'auteur');
if (!defined('_GLOSSARY_COMMENT')) define ('_GLOSSARY_COMMENT', 'Commentaire');
// Glossary entry edit labels - user side
if (!defined('_GLOSSARY_MARKDOWN_USAGE')) define ('_GLOSSARY_MARKDOWN_USAGE', 'Vous pouvez utiliser <a href="http://daringfireball.net/projects/markdown/syntax">les codes Markdown</a> dans la définition mais le code HTML n\est pas autorisé et sera retiré.');
if (!defined('_GLOSSARY_USER_LOCALITY')) define ('_GLOSSARY_USER_LOCALITY', 'Votre ville');
if (!defined('_GLOSSARY_USER_MAIL')) define ('_GLOSSARY_USER_MAIL', 'Votre courriel');
if (!defined('_GLOSSARY_USER_NAME')) define ('_GLOSSARY_USER_NAME', 'Votre nom');
if (!defined('_GLOSSARY_USER_URI')) define ('_GLOSSARY_USER_URI', 'Votre page web');
if (!defined('_GLOSSARY_USER_SUBMIT')) define ('_GLOSSARY_USER_SUBMIT', 'Soumettre');
if (!defined('_GLOSSARY_USER_CLEAR')) define ('_GLOSSARY_USER_CLEAR', 'Effacer');
if (!defined('_GLOSSARY_APPEARS_AS')) define ('_GLOSSARY_APPEARS_AS', 'La définition apparaîtra ainsi - Vous pouvez utiliser <a href="http://daringfireball.net/projects/markdown/syntax">les codes Markdown</a> pour styliser les définitions');
// Glossary item listing headings etc
if (!defined('_GLOSSARY_TERM_HEAD')) define ('_GLOSSARY_TERM_HEAD', 'Terme');
if (!defined('_GLOSSARY_DEFINITION_HEAD')) define ('_GLOSSARY_DEFINITION_HEAD', 'Définition');
if (!defined('_GLOSSARY_ITEM_COUNT')) define ('_GLOSSARY_ITEM_COUNT', 'Il y a %d entrées dans ce glossaire.');
if (!defined('_GLOSSARY_IS_EMPTY')) define ('_GLOSSARY_IS_EMPTY', 'Cette sélection n\'a pas d\'entrées');
if (!defined('_GLOSSARY_ADD_ENTRY')) define ('_GLOSSARY_ADD_ENTRY', 'Ajouter une nouvelle entrée');
// Glossary search texts
if (!defined('_GLOSSARY_SEARCH_INTRO')) define ('_GLOSSARY_SEARCH_INTRO', 'Chercher des termes du glossaire (expression régulière autorisée)');
if (!defined('_GLOSSARY_BEGINS_WITH')) define ('_GLOSSARY_BEGINS_WITH', 'Commence par');
if (!defined('_GLOSSARY_TERM_CONTAINS')) define ('_GLOSSARY_TERM_CONTAINS', 'Contient');
if (!defined('_GLOSSARY_EXACT_TERM')) define ('_GLOSSARY_EXACT_TERM', 'Terme exact');
if (!defined('_GLOSSARY_SOUNDS_LIKE')) define ('_GLOSSARY_SOUNDS_LIKE', 'Se prononce comme');
if (!defined('_GLOSSARY_SEARCH_SEARCH')) define ('_GLOSSARY_SEARCH_SEARCH', 'cherche...');
if (!defined('_GLOSSARY_GO')) define ('_GLOSSARY_GO', 'Valider');
// Definitions of glossary configuration items
if (!defined('_GLOSSARY_UTF8')) define ('_GLOSSARY_UTF8', 'Sélectionner UTF-8 :');
if (!defined('_GLOSSARY_PER_PAGE')) define ('_GLOSSARY_PER_PAGE', 'Entrées par page :');
if (!defined('_GLOSSARY_ALLOWENTRY')) define ('_GLOSSARY_ALLOWENTRY', 'Autorise les entrées :');
if (!defined('_GLOSSARY_ANONENTRY')) define ('_GLOSSARY_ANONENTRY', 'Entrées anonymes :');
if (!defined('_GLOSSARY_HIDEAUTHOR')) define ('_GLOSSARY_HIDEAUTHOR', 'Cacher l\'auteur :');
if (!defined('_GLOSSARY_USEEDITOR')) define ('_GLOSSARY_USEEDITOR', 'Utiliser l\'éditeur par défaut :');
if (!defined('_GLOSSARY_SHOWCATEGORIES')) define ('_GLOSSARY_SHOWCATEGORIES', 'Montrer les glossaires :');
if (!defined('_GLOSSARY_CATEGORIES_ABOVE')) define ('_GLOSSARY_CATEGORIES_ABOVE', 'Montrer les glossaires en haut :');
if (!defined('_GLOSSARY_SHOWCATDESCRIPTIONS')) define ('_GLOSSARY_SHOWCATDESCRIPTIONS', 'Montrer les descriptions des glossaires :');
if (!defined('_GLOSSARY_SHOWNUMBEROFENTRIES')) define ('_GLOSSARY_SHOWNUMBEROFENTRIES', 'Montrer le nombre de termes :');
if (!defined('_GLOSSARY_UTF8_ADMIN')) define ('_GLOSSARY_UTF8_ADMIN', 'Sélectionner UTF-8:');
if (!defined('_GLOSSARY_SHOW_LIST')) define ('_GLOSSARY_SHOW_LIST', 'Montrer une liste des entrées de glossaire :');
if (!defined('_GLOSSARY_SHOW_ALPHABET')) define ('_GLOSSARY_SHOW_ALPHABET', 'Montrer l\'alphabet :');
if (!defined('_GLOSSARY_SHOW_ALPHABET_BELOW')) define ('_GLOSSARY_SHOW_ALPHABET_BELOW', 'Montrer l\'alphabet sous la liste:');
if (!defined('_GLOSSARY_SHOW_SEARCH')) define ('_GLOSSARY_SHOW_SEARCH', 'Montrer la recherche dans le glossaire :');
if (!defined('_GLOSSARY_SEARCH_ALL')) define ('_GLOSSARY_SEARCH_ALL', 'Chercher tous les glossaires :');
if (!defined('_GLOSSARY_STRIP_ACCENTS')) define ('_GLOSSARY_STRIP_ACCENTS', 'Ignorer les accents :');
if (!defined('_GLOSSARY_AUTOPUBLISH')) define ('_GLOSSARY_AUTOPUBLISH', 'Autopublier les entrées :');
if (!defined('_GLOSSARY_NOTIFY')) define ('_GLOSSARY_NOTIFY', 'Notifier le webmestre :');
if (!defined('_GLOSSARY_NOTIFY_EMAIL')) define ('_GLOSSARY_NOTIFY_EMAIL', 'Courriel du webmestre :');
if (!defined('_GLOSSARY_THANK_USER')) define ('_GLOSSARY_THANK_USER', 'Remercier l\'utilisateur :');
if (!defined('_GLOSSARY_PAGE_SPREAD')) define ('_GLOSSARY_PAGE_SPREAD', 'étendue de la page :');
if (!defined('_GLOSSARY_LANGUAGE')) define ('_GLOSSARY_LANGUAGE', 'Ignorer la langue du glossaire');
// Descriptions of glossary configuration items
if (!defined('_GLOSSARY_DESC_UTF8')) define ('_GLOSSARY_DESC_UTF8', 'Spécifier à la base de données et au navigateur que ce glossaire utilise UTF-8');
if (!defined('_GLOSSARY_DESC_PER_PAGE')) define ('_GLOSSARY_DESC_PER_PAGE', 'Nombre d\'entrées affichées par page.');
if (!defined('_GLOSSARY_DESC_ALLOWENTRY')) define ('_GLOSSARY_DESC_ALLOWENTRY', 'Autoriser les utilisateurs à ajouter de nouvelles entrées. (Editeurs, Publieurs, Administrateurs et Super-administrateurs sont toujours autorisés à ajouter des entrées.)');
if (!defined('_GLOSSARY_DESC_ANONENTRY')) define ('_GLOSSARY_DESC_ANONENTRY', 'Autoriser les utilisateurs non enregistrés à ajouter de nouvelles entrées.');
if (!defined('_GLOSSARY_DESC_HIDEAUTHOR')) define ('_GLOSSARY_DESC_HIDEAUTHOR', 'Cacher les détails de l\'auteur tels que nom, ville, etc.');
if (!defined('_GLOSSARY_DESC_USEEDITOR')) define ('_GLOSSARY_DESC_USEEDITOR', 'Oui pour utiliser l\'éditeur par défaut pour ajouter des entrées ou Non pour utiliser une simple zone de texte');
if (!defined('_GLOSSARY_DESC_SHOWCATEGORIES')) define ('_GLOSSARY_DESC_SHOWCATEGORIES', 'Si mis à Non, le composant affichera uniquement le glossaire par défaut');
if (!defined('_GLOSSARY_DESC_CATEGORIES_ABOVE')) define ('_GLOSSARY_DESC_CATEGORIES_ABOVE', 'Si mis à Non, sera mis en bas');
if (!defined('_GLOSSARY_DESC_SHOWCATDESCRIPTIONS')) define ('_GLOSSARY_DESC_SHOWCATDESCRIPTIONS', 'Si mis à Non, les descriptions du glossaire ne seront pas affichées sur le frontend');
if (!defined('_GLOSSARY_DESC_SHOWNUMBEROFENTRIES')) define ('_GLOSSARY_DESC_SHOWNUMBEROFENTRIES', 'OUI montre le nombre d\'entrées sur la frontpage.');
if (!defined('_GLOSSARY_DESC_UTF8_ADMIN')) define ('_GLOSSARY_DESC_UTF8_ADMIN', 'Spécifier à la base de données et au navigateur que ce glossaire utilise UTF-8');
if (!defined('_GLOSSARY_DESC_SHOW_LIST')) define ('_GLOSSARY_DESC_SHOW_LIST', 'Autrement, la liste sera supprimée :');
if (!defined('_GLOSSARY_DESC_SHOW_ALPHABET')) define ('_GLOSSARY_DESC_SHOW_ALPHABET', 'Montrer les liens alphabétiques sur l\'écran principal du glossaire au dessus de la liste');
if (!defined('_GLOSSARY_DESC_SHOW_ALPHABET_BELOW')) define ('_GLOSSARY_DESC_SHOW_ALPHABET_BELOW', 'Montrer les liens alphabétiques sur l\'écran principal du glossaire au dessous de la liste');
if (!defined('_GLOSSARY_DESC_SHOW_SEARCH')) define ('_GLOSSARY_DESC_SHOW_SEARCH', 'Offre à l\utilisateur la possibilité de rechercher :');
if (!defined('_GLOSSARY_DESC_SEARCH_ALL')) define ('_GLOSSARY_DESC_SEARCH_ALL', 'Tous les glossaires, ou si non, seulement le glossaire courant :');
if (!defined('_GLOSSARY_DESC_STRIP_ACCENTS')) define ('_GLOSSARY_DESC_STRIP_ACCENTS', 'Ignorer les accents en construisant l\'alphabet');
if (!defined('_GLOSSARY_DESC_AUTOPUBLISH')) define ('_GLOSSARY_DESC_AUTOPUBLISH', 'Autopublier les nouvelles entrées dans le glossaire.');
if (!defined('_GLOSSARY_DESC_NOTIFY')) define ('_GLOSSARY_DESC_NOTIFY', 'Notifier au webmestre l\'arrivée de nouvelles entrées.');
if (!defined('_GLOSSARY_DESC_NOTIFY_EMAIL')) define ('_GLOSSARY_DESC_NOTIFY_EMAIL', 'Courriel pour l\'envoi des notifications.');
if (!defined('_GLOSSARY_DESC_THANKUSER')) define ('_GLOSSARY_DESC_THANKUSER', 'Envoyer courriel de remerciement à l\'utilisateur.');
if (!defined('_GLOSSARY_DESC_PAGE_SPREAD')) define ('_GLOSSARY_DESC_PAGE_SPREAD', 'Nombre de numéros de page montrés dans le navigateur de pages');
if (!defined('_GLOSSARY_DESC_LANGUAGE')) define ('_GLOSSARY_DESC_LANGUAGE', 'Ignorer la sélection automatique du langage');
