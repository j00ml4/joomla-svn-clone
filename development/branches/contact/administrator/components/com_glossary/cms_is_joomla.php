<?php

if (!defined('_CMSAPI_CMS_BASE')) {
	define ('_CMSAPI_CMS_BASE', 'Joomla');
	if (!defined('_CMSAPI_ABSOLUTE_PATH')) define ('_CMSAPI_ABSOLUTE_PATH', JPATH_SITE);

	call_user_func(array('JPluginHelper', 'importPlugin'), 'cmsapi');
	foreach (JDispatcher::getInstance()->trigger('getCMSAPI') as $cmsapiversion) if ($cmsapiversion) {
		if (!defined('_CMSAPI_VERSION')) define('_CMSAPI_VERSION', $cmsapiversion);
		break;
	}

	if (!defined('_CMSAPI_CMS_BASE')) die ('Configuration error, base CMS unknown');
}
