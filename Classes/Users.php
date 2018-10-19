<?php

/**
 * @author
 * Banjo Mofesola Paul
 * Chief Developer, Planet NEST
 * mofesolapaul@planetnest.org
 * 16/05/2017 12:02
 */
class Users
{

    use \Traits\HttpMethodCheck;
    use \Traits\DbTransaction;

    function index() {
        return 'Welcome to Users resource';
    }

    function all($request) {
        $this->_checkMethod("GET", $request->method);
        return "All users";
    }

    function one($request) {
        $this->_checkMethod("POST", $request->method);
        return "One user";
    }

    function db($request) {
        
    }
}