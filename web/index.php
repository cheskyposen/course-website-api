<?php
    // sets return type in header
    header('Content-type: application/json');
    // gets an associative array of all headers in the http request
    $GLOBALS['headers'] = apache_request_headers();
    // includes all required libraries for the api
    require_once '../app/bootstrap.php';
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // should do a check here to match $_SERVER['HTTP_ORIGIN'] to a
        // whitelist of safe domains
        if ($_SERVER['HTTP_ORIGIN'] == "https://cheskyposen.github.io" || $_SERVER['HTTP_ORIGIN'] == "https://hiya5150.github.io" || $_SERVER['HTTP_ORIGIN'] == "http://localhost:4200") {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
            // Access-Control headers are received during OPTIONS requests
            if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

                if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

                if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                    header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

            }
        }
    }
    // Init Core Library
    $init = new Core;
    // un sets global vars
    unset($GLOBALS);