<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once str_replace('/public', '', __DIR__) . '/app/listConstants.php';
require_once ROUTER;

$router = new Router();
$router->run();