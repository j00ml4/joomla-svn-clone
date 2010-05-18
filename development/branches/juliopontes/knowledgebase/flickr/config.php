<?php
$connectorType = 'yql';
$connectorParams = array(
	'protocol' => 'http',
	'url' => 'query.yahooapis.com/v1/public/yql',
	'params' => array(
		'q' => trim(rawurlencode((string)$this->getDSL()->JDatabaseQuery))
	)
);