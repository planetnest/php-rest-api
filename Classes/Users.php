<?php

/**
 * @author
 * Banjo Mofesola Paul
 * Chief Developer, Planet NEST
 * mofesolapaul@planetnest.org
 * 16/05/2017 12:02
 */
class Users extends Apiable
{

    /**
     * @param null|\Api\API $api
     * @return string
     */
    function all($api = null) {
        if ($api->method !== 'GET') return "Users: Invalid invocation";
        return "All users";
    }

    /**
     * @param null|\Api\API $api
     * @internal param $args
     * @return string
     */
    function one($api = null) {
        if ($api->method !== 'POST') return "Users: Invalid invocation";
        return "One user";
    }
}