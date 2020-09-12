<?php

ini_set('display_errors', true);
require_once 'TreeController.php';

use app\TreeController;

/*
 * Helper that determines the type of request received (GET, POST, DELETE, PUT), checks if it exists
 * a method in the controller to handle this request and if this method exists as a callback method
 * calls a method in the controller that matches the type of request, and also passes the request body
 * to the specified method.
 * */
if (method_exists('app\TreeController', $_SERVER['REQUEST_METHOD'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
        $_REQUEST = file_get_contents('php://input');
        return call_user_func('app\TreeController::' . $_SERVER['REQUEST_METHOD'], $_REQUEST);
    } else return call_user_func('app\TreeController::' . $_SERVER['REQUEST_METHOD'], $_REQUEST);

}
http_response_code(405);
exit;
