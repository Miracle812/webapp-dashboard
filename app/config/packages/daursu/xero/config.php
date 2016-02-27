<?php

return array(


	'application_type'  => 'Private',
	'useragent' => 'NavitasApp',
	'callback' => 'oob',
	'consumer_key'    => 'OXQVJVLB1NFOHWUSAAHT8NOVUIJNBX',
	'shared_secret'   => 'WVWGP0KW8JY0FR86AKNAY5WT7FOBQV',
	'core_version'    => '2.0',
	'payroll_version' => '1.0',

	'rsa_private_key' => dirname(__FILE__) . '/certs/privatekey.pem',
	'rsa_public_key'  => dirname(__FILE__) . '/certs/publickey.cer',
	//'curl_ssl_cert'     => '/certs/entrust-cert-RQ3.pem',
	//'curl_ssl_password' => '1234',
	//'curl_ssl_key'      => '/certs/entrust-private-RQ3.pem',

);