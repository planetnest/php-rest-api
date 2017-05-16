<?php

/**
 * @author
 * Banjo Mofesola Paul
 * Chief Developer, Planet NEST
 * mofesolapaul@planetnest.org
 * 16/05/2017 11:59
 */
abstract class Apiable
{

    /**
     * Tells if supplied method name exists
     * @param $method_name
     * @return bool
     */
    public function hasMethod($method_name) {
        return method_exists($this, $method_name);
    }
}