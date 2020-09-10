<?php

ini_set('display_errors', true);
require_once 'TreeController.php';

use app\TreeController;

if (method_exists('app\TreeController', $_SERVER['REQUEST_METHOD'])) {
    // если передаваемый метод существует в данном классе то выполняем условие.
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
        $_REQUEST = file_get_contents('php://input');
        return call_user_func('app\TreeController::' . $_SERVER['REQUEST_METHOD'], $_REQUEST);
    } else return call_user_func('app\TreeController::' . $_SERVER['REQUEST_METHOD'], $_REQUEST);
    // метод вызывает функцию переданую первым параметром, а вторым перематром передается значени в функцию.
}
http_response_code(405);
exit;
