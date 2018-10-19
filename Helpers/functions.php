<?php
// contains helper functions available throughout the project

/**
 * Error disambiguation
 */
function statusCode($code)
{
    $status = array(
        200 => 'OK',
        400 => 'Bad Request',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error'
    );
    return $status[$code] ?? $status[500];
}

function response($data, $code = 200) {
    header("HTTP/1.1 {$code} " . statusCode($code));
    $data = [
        'status' => $code == 200? true:false,
        'data' => $data
    ];
    echo json_encode($data);
    exit(0);
}

function determineHttpMethod() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
        switch ($_SERVER['HTTP_X_HTTP_METHOD']) {
            case 'PUT':
                return 'PUT';
            case 'DELETE':
                return 'DELETE';
            case 'PATCH':
                return 'PATCH';
            default:
                response("Invalid request method", 404);
        }
    } else return $_SERVER['REQUEST_METHOD'];
}

function sanitize($data) {
    $cleaned = Array();
    if (is_array($data))
        foreach ($data as $k => $v)
            $cleaned[$k] = sanitize($v);
    else
        $cleaned = trim(strip_tags($data));
    return $cleaned;
}

function generateAuthTokens() {
    $token = hash('sha256', sha1(microtime()) . md5(rand()));
    $digest = hash_hmac("ripemd160", $token, AUTH_SECRET);
    return [
        'auth_token' => $token,
        'auth_digest' => $digest
    ];
}

function tokensMatch($token, $digest) {
    return hash_hmac("ripemd160", $token, AUTH_SECRET) == $digest;
}

function copyValues($array) {

}