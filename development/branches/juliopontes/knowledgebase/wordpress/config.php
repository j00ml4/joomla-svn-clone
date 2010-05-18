<?php
$jconfig = new JConfig();
$connectorType = 'database';
$connectorParams = array(
	'driver' => $jconfig->dbtype,
	'host' => $jconfig->host,
	'username' => $jconfig->user,
	'password' => $jconfig->password,
	'database' => 'wordpress',
	'table_prefix' => 'wp_'
);