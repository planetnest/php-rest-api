<?php
/**
 * @author
 * Banjo Mofesola Paul
 * Chief Developer, Planet NEST
 * mofesolapaul@planetnest.org
 * 16/05/2017 10:57
 */

require_once __DIR__ . '/../Helpers/__classloader.php';

if (!isset($_REQUEST['api-version']) || !isset($_REQUEST['api-request'])) die("Invalid request");
switch (strtolower($_REQUEST['api-version'])) {
    default:
        $api = new \Api\v1($_REQUEST['api-request']);
        break;
}

echo $api->execute();