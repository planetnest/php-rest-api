<?php
/**
 * @author
 * Banjo Mofesola Paul
 * Chief Developer, Planet NEST
 * mofesolapaul@planetnest.org
 * 16/05/2017 11:04
 */


// bootstrap
require_once 'consts.php';
require_once 'functions.php';

spl_autoload_register(function($classname) {
    $classname = preg_replace('/\\\/', DIRECTORY_SEPARATOR, $classname);
    $classname = $classname . '.php';
    $classname = __DIR__ . '/../Classes/' . $classname;

    if (file_exists($classname)) require_once $classname;
});