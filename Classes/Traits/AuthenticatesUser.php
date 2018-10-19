<?php
/**
 * @author
 * Banjo Mofesola Paul
 * Chief Developer, Planet NEST
 * mofesolapaul@planetnest.org
 * 24/03/2018 10:21
 */

namespace Traits;


trait AuthenticatesUser
{
    public function _auth($request) {
        return true; // disable it
        if (!isset($request['auth_token'])) response("Unauthorized", 401);
        $token = $request['auth_token'];
        $digest = $_COOKIE[COOKIE_AUTH_DIGEST] ?? "";
        if (!tokensMatch($token, $digest))  response("Unauthorized", 401);
    }
}