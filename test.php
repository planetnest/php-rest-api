<?php
/**
 * @author
 * Banjo Mofesola Paul
 * Chief Developer, Planet NEST
 * mofesolapaul@planetnest.org
 * 16/05/2017 11:02
 */

require_once __DIR__ . '/Helpers/__classloader.php';

define('API', 'http://localhost/devcrib/week12/php-rest-api/api/v1');

$curl = curl_init(API . "/users/one");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
echo curl_exec($curl);