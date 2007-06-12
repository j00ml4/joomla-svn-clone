<?php return; ?>

; well, apparently this is a true .ini file renamed to .php to prevent access.
; parse_ini_file() doesn't bother with the above line.

; -- JApiHelperDoc is the primary client for this --
[schema]
pack = "classtrees_%pack%.html"
cls  = "%pack%/%sub%/%cls%.html"
meth = "%pack%/%sub%/%cls%.html#%meth%"
func = "%pack%/_%filename%.html#function%func%"

[cls]
; @abstract classes
JButton = "button.php /joomla/html/toolbar"
JCache = "cache.php /joomla/cache"
JController = "controller.php /joomla/application/component"
JDatabase = "database.php /joomla/database"
JDocument = "document.php /joomla/document"
JElement = "element.php /joomla/html/parameter"
JFormat = "format.php /joomla/registry"
JModel = "model.php /joomla/application/component"
JObserver = "observer.php /joomla/base"
JPane = "pane.php /joomla/html"
JPlugin = "plugin.php /joomla/event"
JRenderer = "renderer.php /joomla/document"
JSession = "session.php /joomla/session"
JCachestorage = "storage.php /joomla/cache"
JSessionStorage = "storage.php /joomla/session"
JTable = "table.php /joomla/database"
JView = "view.php /joomla/application/component"

[func]
; functions hanging in globals scope giving no clue where they belong to
jimport = "loader.php /"
