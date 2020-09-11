<?php

ini_set('display_errors', true);
require_once 'TreeController.php';

use app\TreeController;

/*
 * Хелппер который определяет тип принимаемого запроса (GET, POST, DELETE, PUT), проверяет существует ли
 * метод в контроллере для обработки даного запроса и в случае если данный метод существует методлом обратного вызова
 * вызывакет метод в контроллере который соответствует типу запроса, а так же передает тело запроса в указанный метод.
 * */
if (method_exists('app\TreeController', $_SERVER['REQUEST_METHOD'])) {
    // если передаваемый метод существует в данном классе то выполняем условие.
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
        $_REQUEST = file_get_contents('php://input');
        // получения тела запроса так как метод HTTP DELETE, PUT не передается по $_REQUEST
        return call_user_func('app\TreeController::' . $_SERVER['REQUEST_METHOD'], $_REQUEST);
        // методом обратного вызова первым параментром принимает нужный нам метод контроллра (POST, GET, DELETE, PUT),
        // вторым параметром передается тело зароса в указанный метод
    } else return call_user_func('app\TreeController::' . $_SERVER['REQUEST_METHOD'], $_REQUEST);

}
http_response_code(405);
exit;
