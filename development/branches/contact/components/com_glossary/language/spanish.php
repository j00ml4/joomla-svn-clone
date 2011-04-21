<?php

// Spanish language file for Glossary component
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
if (!defined('_CMSAPI_CPANEL_RETURN')) define ('_CMSAPI_CPANEL_RETURN', 'Panel de Control');
if (!defined('_CMSAPI_YES')) define ('_CMSAPI_YES', 'Si');
if (!defined('_CMSAPI_NO')) define ('_CMSAPI_NO', 'No');
if (!defined('_CMSAPI_CANCEL')) define ('_CMSAPI_CANCEL', 'Cancelar');
if (!defined('_CMSAPI_CONFIG_COMP')) define ('_CMSAPI_CONFIG_COMP', 'Configuracion guardada');
if (!defined('_CMSAPI_CLASS_NOT_PRESENT')) define ('_CMSAPI_CLASS_NOT_PRESENT', 'Component %s error: attempt to use non-existent class %s');
if (!defined('_CMSAPI_METHOD_NOT_PRESENT')) define ('_CMSAPI_METHOD_NOT_PRESENT', 'Component %s error: requested task %s but no suitable method in class %s');
if (!defined('_CMSAPI_DISPLAY_NUMBER')) DEFINE('_CMSAPI_DISPLAY_NUMBER','Display #');
if (!defined('_CMSAPI_PAGE_TEXT')) DEFINE ('_CMSAPI_PAGE_TEXT', 'Pagina');
if (!defined('_CMSAPI_PAGE_SHOW_RESULTS')) DEFINE('_CMSAPI_PAGE_SHOW_RESULTS','Mostrar resultados ');
if (!defined('_CMSAPI_PAGE_SHOW_RANGE')) DEFINE('_CMSAPI_PAGE_SHOW_RANGE','%s to %s of %s');
if (!defined('_CMSAPI_PREVIOUS')) define ('_CMSAPI_PREVIOUS', 'Prev');
if (!defined('_CMSAPI_NEXT')) define ('_CMSAPI_NEXT', 'Sig');
// Component specific definitions follow
if (!defined('_GLOSSARY_GLOSS_LANGUAGE')) define ('_GLOSSARY_GLOSS_LANGUAGE', 'Idioma (dejar en blanco para valor por defecto):');
if (!defined('_GLOSSARY_COMPONENT_TITLE')) define ('_GLOSSARY_COMPONENT_TITLE', 'Glosario');
if (!defined('_GLOSSARY_SELECT')) define ('_GLOSSARY_SELECT', 'Elegir:');
if (!defined('_GLOSSARY_ALL')) define ('_GLOSSARY_ALL', 'Todo');
if (!defined('_GLOSSARY_LIST_ENTRIES')) define ('_GLOSSARY_LIST_ENTRIES', 'Listar Entradas');
if (!defined('_GLOSSARY_LIST_GLOSSARIES')) define ('_GLOSSARY_LIST_GLOSSARIES', 'Listar Glosarios');
if (!defined('_GLOSSARY_EDIT_ENTRIES')) define ('_GLOSSARY_EDIT_ENTRIES', 'Editar Entradas');
if (!defined('_GLOSSARY_EDIT_GLOSSARIES')) define ('_GLOSSARY_EDIT_GLOSSARIES', 'Editar Glosarios');
if (!defined('_GLOSSARY_EDIT_CONFIG')) define ('_GLOSSARY_EDIT_CONFIG', 'Editar Configuracion');
if (!defined('_GLOSSARY_USER_CONFIG')) define ('_GLOSSARY_USER_CONFIG', 'Usuario');
if (!defined('_GLOSSARY_ADMIN_CONFIG')) define ('_GLOSSARY_ADMIN_CONFIG', 'Admin');
if (!defined('_GLOSSARY_ABOUT_HEADING')) define ('_GLOSSARY_ABOUT_HEADING', 'Informacion General');
if (!defined('_GLOSSARY_GLOSSARY_LIST')) define ('_GLOSSARY_GLOSSARY_LIST', 'Otros glosarios disponibles aqui');
if (!defined('_GLOSSARY_FILTER_TERM')) define ('_GLOSSARY_FILTER_TERM', 'Filtrar termino (usar expreisones regulares):');
if (!defined('_GLOSSARY_FILTER_DEFINITION')) define ('_GLOSSARY_FILTER_DEFINITION', 'Filtrar definicion (palabras o boolean opcional):');
if (!defined('_GLOSSARY_ALL_GLOSSARIES')) define ('_GLOSSARY_ALL_GLOSSARIES', 'Todos los glosarios');
if (!defined('_GLOSSARY_REFRESH')) define ('_GLOSSARY_REFRESH', 'Actualizar pagina');
// Glossary control panel terms
if (!defined('_GLOSSARY_CPANEL')) define ('_GLOSSARY_CPANEL', 'Panel de Control');
if (!defined('_GLOSSARY_CPANEL_MANAGE_GLOSSARIES')) define ('_GLOSSARY_CPANEL_MANAGE_GLOSSARIES', 'Administrar Glosarios');
if (!defined('_GLOSSARY_CPANEL_MANAGE_ENTRIES')) define ('_GLOSSARY_CPANEL_MANAGE_ENTRIES', 'Administrar Entradas');
if (!defined('_GLOSSARY_CPANEL_CONFIG')) define ('_GLOSSARY_CPANEL_CONFIG', 'Configuracion');
if (!defined('_GLOSSARY_CPANEL_ABOUT')) define ('_GLOSSARY_CPANEL_ABOUT', 'Acerca de');
// Glossary entry headings
if (!defined('_GLOSSARY_TERM')) define ('_GLOSSARY_TERM', 'Termino');
if (!defined('_GLOSSARY_LETTER')) define ('_GLOSSARY_LETTER', 'Letra (opcional)');
if (!defined('_GLOSSARY_DEFINITION')) define ('_GLOSSARY_DEFINITION', 'Definicion');
if (!defined('_GLOSSARY_DATE')) define ('_GLOSSARY_DATE', 'Fecha');
if (!defined('_GLOSSARY_PUBLISHED')) define ('_GLOSSARY_PUBLISHED', 'Publicado');
if (!defined('_GLOSSARY_NAME')) define ('_GLOSSARY_NAME', 'Nombre');
if (!defined('_GLOSSARY_DESCRIPTION')) define ('_GLOSSARY_DESCRIPTION', 'Descripcion de Glosario');
if (!defined('_GLOSSARY_GLOSSARY_SELECT')) define ('_GLOSSARY_GLOSSARY_SELECT', 'Elegir glosario');
// Glossary entry edit labels - admin side
if (!defined('_GLOSSARY_AUTHOR_NAME')) define ('_GLOSSARY_AUTHOR_NAME', 'Nombre del Autor');
if (!defined('_GLOSSARY_LOCALITY')) define ('_GLOSSARY_LOCALITY', 'Ubicacion del Autor');
if (!defined('_GLOSSARY_MAIL')) define ('_GLOSSARY_MAIL', 'E-Mail del Autor');
if (!defined('_GLOSSARY_PAGE')) define ('_GLOSSARY_PAGE', 'Sitio Web del Autor');
if (!defined('_GLOSSARY_COMMENT')) define ('_GLOSSARY_COMMENT', 'Comentarios');
// Glossary entry edit labels - user side
if (!defined('_GLOSSARY_MARKDOWN_USAGE')) define ('_GLOSSARY_MARKDOWN_USAGE', 'Puedes usar <a href="http://daringfireball.net/projects/markdown/syntax">Markdown codes</a> en la definicion pero HTML no es permitido y sera quitado.');
if (!defined('_GLOSSARY_USER_LOCALITY')) define ('_GLOSSARY_USER_LOCALITY', 'Tu ubicacion');
if (!defined('_GLOSSARY_USER_MAIL')) define ('_GLOSSARY_USER_MAIL', 'Tu E-Mail');
if (!defined('_GLOSSARY_USER_NAME')) define ('_GLOSSARY_USER_NAME', 'Tu nombre');
if (!defined('_GLOSSARY_USER_URI')) define ('_GLOSSARY_USER_URI', 'Tu sitio Web');
if (!defined('_GLOSSARY_USER_SUBMIT')) define ('_GLOSSARY_USER_SUBMIT', 'Enviar');
if (!defined('_GLOSSARY_USER_CLEAR')) define ('_GLOSSARY_USER_CLEAR', 'Limpiar');
if (!defined('_GLOSSARY_APPEARS_AS')) define ('_GLOSSARY_APPEARS_AS', 'La definicion aparecera como se muestra - puedes usar <a href="http://daringfireball.net/projects/markdown/syntax">Markdown codes</a> para darle estilos a las definiciones');
// Glossary item listing headings etc
if (!defined('_GLOSSARY_TERM_HEAD')) define ('_GLOSSARY_TERM_HEAD', 'Termino');
if (!defined('_GLOSSARY_DEFINITION_HEAD')) define ('_GLOSSARY_DEFINITION_HEAD', 'Definicion');
if (!defined('_GLOSSARY_ITEM_COUNT')) define ('_GLOSSARY_ITEM_COUNT', 'Hay %d entradas en este glosario.');
if (!defined('_GLOSSARY_IS_EMPTY')) define ('_GLOSSARY_IS_EMPTY', 'Esta seleccion no tiene entradas');
if (!defined('_GLOSSARY_ADD_ENTRY')) define ('_GLOSSARY_ADD_ENTRY', 'Agregar una nueva entrada');
// Glossary search texts
if (!defined('_GLOSSARY_SEARCH_INTRO')) define ('_GLOSSARY_SEARCH_INTRO', 'Buscar terminos del glosario (expresiones regulares son permitidas)');
if (!defined('_GLOSSARY_BEGINS_WITH')) define ('_GLOSSARY_BEGINS_WITH', 'Empieza con');
if (!defined('_GLOSSARY_TERM_CONTAINS')) define ('_GLOSSARY_TERM_CONTAINS', 'Contiene');
if (!defined('_GLOSSARY_EXACT_TERM')) define ('_GLOSSARY_EXACT_TERM', 'Termino exacto');
if (!defined('_GLOSSARY_SOUNDS_LIKE')) define ('_GLOSSARY_SOUNDS_LIKE', 'Se parece a');
if (!defined('_GLOSSARY_SEARCH_SEARCH')) define ('_GLOSSARY_SEARCH_SEARCH', 'buscar...');
if (!defined('_GLOSSARY_GO')) define ('_GLOSSARY_GO', 'Buscar');
// Definitions of glossary configuration items
if (!defined('_GLOSSARY_UTF8')) define ('_GLOSSARY_UTF8', 'Elegir UTF-8:');
if (!defined('_GLOSSARY_PER_PAGE')) define ('_GLOSSARY_PER_PAGE', 'Entradas por pagina:');
if (!defined('_GLOSSARY_ALLOWENTRY')) define ('_GLOSSARY_ALLOWENTRY', 'Permitir entradas:');
if (!defined('_GLOSSARY_ANONENTRY')) define ('_GLOSSARY_ANONENTRY', 'Entradas Anonimas:');
if (!defined('_GLOSSARY_HIDEAUTHOR')) define ('_GLOSSARY_HIDEAUTHOR', 'Ocultar Autor:');
if (!defined('_GLOSSARY_USEEDITOR')) define ('_GLOSSARY_USEEDITOR', 'Usar Editor por defecto:');
if (!defined('_GLOSSARY_SHOWCATEGORIES')) define ('_GLOSSARY_SHOWCATEGORIES', 'Mostrar Glosarios:');
if (!defined('_GLOSSARY_CATEGORIES_ABOVE')) define ('_GLOSSARY_CATEGORIES_ABOVE', 'Mostrar Glosarios arriba:');
if (!defined('_GLOSSARY_SHOWCATDESCRIPTIONS')) define ('_GLOSSARY_SHOWCATDESCRIPTIONS', 'Mostrar descripciones de Glosarios:');
if (!defined('_GLOSSARY_SHOWNUMBEROFENTRIES')) define ('_GLOSSARY_SHOWNUMBEROFENTRIES', 'Mostrar el conteo de terminos');
if (!defined('_GLOSSARY_UTF8_ADMIN')) define ('_GLOSSARY_UTF8_ADMIN', 'Elegir UTF-8:');
if (!defined('_GLOSSARY_SHOW_LIST')) define ('_GLOSSARY_SHOW_LIST', 'Mostrar un listado de las entradas del glosario:');
if (!defined('_GLOSSARY_SHOW_ALPHABET')) define ('_GLOSSARY_SHOW_ALPHABET', 'Mostrar el alfabeto arriba del listado:');
if (!defined('_GLOSSARY_SHOW_ALPHABET_BELOW')) define ('_GLOSSARY_SHOW_ALPHABET_BELOW', 'Mostrar el alfabeto abajo del listado:');
if (!defined('_GLOSSARY_SHOW_SEARCH')) define ('_GLOSSARY_SHOW_SEARCH', 'Mostrar buscador del glosario:');
if (!defined('_GLOSSARY_SEARCH_ALL')) define ('_GLOSSARY_SEARCH_ALL', 'Buscar en todos los glosarios');
if (!defined('_GLOSSARY_STRIP_ACCENTS')) define ('_GLOSSARY_STRIP_ACCENTS', 'Quitar acentos:');
if (!defined('_GLOSSARY_AUTOPUBLISH')) define ('_GLOSSARY_AUTOPUBLISH', 'Autopublicar entradas:');
if (!defined('_GLOSSARY_NOTIFY')) define ('_GLOSSARY_NOTIFY', 'Avisar al webmaster:');
if (!defined('_GLOSSARY_NOTIFY_EMAIL')) define ('_GLOSSARY_NOTIFY_EMAIL', 'E-Mail del Webmaster:');
if (!defined('_GLOSSARY_THANK_USER')) define ('_GLOSSARY_THANK_USER', 'Agradecer al Usuario:');
if (!defined('_GLOSSARY_PAGE_SPREAD')) define ('_GLOSSARY_PAGE_SPREAD', 'Despliegue de paginas:');
if (!defined('_GLOSSARY_LANGUAGE')) define ('_GLOSSARY_LANGUAGE', 'Sobreescribir el idioma del Glosario:');
if (!defined('_GLOSSARY_CALL_PLUGINS')) define ('_GLOSSARY_CALL_PLUGINS', 'Llamar a los content plugins para usar definiciones');
// Descriptions of glossary configuration items
if (!defined('_GLOSSARY_DESC_UTF8')) define ('_GLOSSARY_DESC_UTF8', 'Hacer que el glosario ordene a la base de datos y al navegador que use UTF-8');
if (!defined('_GLOSSARY_DESC_PER_PAGE')) define ('_GLOSSARY_DESC_PER_PAGE', 'Numero de entradas mostradas por pagina.');
if (!defined('_GLOSSARY_DESC_ALLOWENTRY')) define ('_GLOSSARY_DESC_ALLOWENTRY', 'Perimitir a los usuarios que escriban nuevas entradas. (Editors, Publishers, Admins y Super Admins siempre tienen permitido agregar entradas.)');
if (!defined('_GLOSSARY_DESC_ANONENTRY')) define ('_GLOSSARY_DESC_ANONENTRY', 'Permitir a los usuarios no registrados escribir entradas.');
if (!defined('_GLOSSARY_DESC_HIDEAUTHOR')) define ('_GLOSSARY_DESC_HIDEAUTHOR', 'Ocultar detalles del autor como nombre, ubicacion, etc.');
if (!defined('_GLOSSARY_DESC_USEEDITOR')) define ('_GLOSSARY_DESC_USEEDITOR', 'Si para usar el editor por defecto para agregar entradas o NO para usar solo un area de texto');
if (!defined('_GLOSSARY_DESC_SHOWCATEGORIES')) define ('_GLOSSARY_DESC_SHOWCATEGORIES', 'Si esta puesto en no, el componente solo mostrara el glosario por defecto');
if (!defined('_GLOSSARY_DESC_CATEGORIES_ABOVE')) define ('_GLOSSARY_DESC_CATEGORIES_ABOVE', 'Si esta puesto en no, estara abajo de todo');
if (!defined('_GLOSSARY_DESC_SHOWCATDESCRIPTIONS')) define ('_GLOSSARY_DESC_SHOWCATDESCRIPTIONS', 'Si esta puesto en no, las descripciones del glosario no se mostraran en el frontend');
if (!defined('_GLOSSARY_DESC_SHOWNUMBEROFENTRIES')) define ('_GLOSSARY_DESC_SHOWNUMBEROFENTRIES', 'SI muestra el numero de entradas en la pagina principal.');
if (!defined('_GLOSSARY_DESC_UTF8_ADMIN')) define ('_GLOSSARY_DESC_UTF8_ADMIN', 'Hacer que el glosario ordene a la base de datos y al navegador que use UTF-8');
if (!defined('_GLOSSARY_DESC_SHOW_LIST')) define ('_GLOSSARY_DESC_SHOW_LIST', 'De lo contrario el listado sera suprimido:');
if (!defined('_GLOSSARY_DESC_SHOW_ALPHABET')) define ('_GLOSSARY_DESC_SHOW_ALPHABET', 'La pantalla principal del Glosario mostrara vinculos desde el alfabeto a las entradas arriba del listado');
if (!defined('_GLOSSARY_DESC_SHOW_ALPHABET_BELOW')) define ('_GLOSSARY_DESC_SHOW_ALPHABET_BELOW', 'La pantalla principal del Glosario mostrara vinculos desde el alfabeto a las entradas debajo del listado');
if (!defined('_GLOSSARY_DESC_SHOW_SEARCH')) define ('_GLOSSARY_DESC_SHOW_SEARCH', 'Ofrecer al usuario la posibilidad de buscar:');
if (!defined('_GLOSSARY_DESC_SEARCH_ALL')) define ('_GLOSSARY_DESC_SEARCH_ALL', 'Todos los glosarios o si esta en no, solo el actual:');
if (!defined('_GLOSSARY_DESC_STRIP_ACCENTS')) define ('_GLOSSARY_DESC_STRIP_ACCENTS', 'Ignorar los acentos al construir el alfabeto');
if (!defined('_GLOSSARY_DESC_AUTOPUBLISH')) define ('_GLOSSARY_DESC_AUTOPUBLISH', 'Autopublicar las entradas nuevas del glosario.');
if (!defined('_GLOSSARY_DESC_NOTIFY')) define ('_GLOSSARY_DESC_NOTIFY', 'Avisar al Webmaster cuando haya una nueva entrada.');
if (!defined('_GLOSSARY_DESC_NOTIFY_EMAIL')) define ('_GLOSSARY_DESC_NOTIFY_EMAIL', 'Direccion de Correo donde se envian los avisos.');
if (!defined('_GLOSSARY_DESC_THANKUSER')) define ('_GLOSSARY_DESC_THANKUSER', 'Enviar \'Gracias\' al mail del usuario.'); 
if (!defined('_GLOSSARY_DESC_PAGE_SPREAD')) define ('_GLOSSARY_DESC_PAGE_SPREAD', 'Indicar cuantos numeros de pagina seran mostrados en la navegacion de pagina');
if (!defined('_GLOSSARY_DESC_LANGUAGE')) define ('_GLOSSARY_DESC_LANGUAGE', 'Sobreescribir la seleccion automatica de idioma');
if (!defined('_GLOSSARY_DESC_CALL_PLUGINS')) define ('_GLOSSARY_DESC_CALL_PLUGINS', 'Si esta puesto, las definiciones seran procesadas por cualquier content plugin activo');
// End of Glossary language definitions