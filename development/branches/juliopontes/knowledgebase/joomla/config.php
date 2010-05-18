<?php
$jconfig = new JConfig();
$connectorType = 'database';
$connectorParams = array(
	'driver' => $jconfig->dbtype,
	'host' => $jconfig->host,
	'username' => $jconfig->user,
	'password' => $jconfig->password,
	'database' => $jconfig->db,
	'table_prefix' => $jconfig->dbprefix
);