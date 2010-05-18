<?php
$connectorType = 'yql';
$connectorParams = array(
	'protocol' => 'http',
	'url' => 'query.yahooapis.com/v1/public/yql',
	'params' => array(
		'env' => 'store://datatables.org/alltableswithkeys',
		'q' => trim(rawurlencode($this->getDSL()->JDatabaseQuery->__toString()))
	)
);
