<?php

namespace Traits;

/**
 * @author
 * Banjo Mofesola Paul
 * Chief Developer, Planet NEST
 * mofesolapaul@planetnest.org
 * 24/03/2018 09:48
 */
trait HttpMethodCheck
{

    public function _checkMethod($methods, string $method)
    {
        if (!is_array($methods)) $methods = explode(',', $methods);
        foreach ($methods as $m)
            if (strtolower($m) == strtolower($method))
                return true;
        response("Method not allowed", 400);
    }
}