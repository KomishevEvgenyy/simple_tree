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
    // If the passed method exists in this class, then the condition is met.
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
        $_REQUEST = file_get_contents('php://input');
        // Receiving the request body since the HTTP DELETE, PUT method is not passed by $ _REQUEST
        return call_user_func('app\TreeController::' . $_SERVER['REQUEST_METHOD'], $_REQUEST);
        // With the callback method, the first parameter takes the controller method we need (POST, GET, DELETE, PUT),
        // as the second parameter, pass the request body to the specified method
    } else return call_user_func('app\TreeController::' . $_SERVER['REQUEST_METHOD'], $_REQUEST);

}
http_response_code(405);
exit;
