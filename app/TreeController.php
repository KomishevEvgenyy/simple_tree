<?php

namespace app;

require_once 'TreeElement.php';

/*
 * Контроллер осуществляет вызов нужного метода который соответствует типу получаемого запроса. Принимает тело запроса
 * и после передает входные данные в класс TreeElement для выполнения запроса к базе данных.
 * */

class TreeController
{
    public static function GET($data)
    {
        /*
         * Метод для запроса типа GET. Вызывает метод show класса TreeElement для отправки на клиентскую часть
         * данные из БД
         * */
        $show = new TreeElement();
        print $show->show();
    }

    public static function POST($data)
    {
        /*
         * Метод для запроса типа POST. Вызывает метод create класса TreeElement для записи данных полученых с
         * клиентсвкой части в БД
         * */
        $parent_id = $data["parent_id"];
        $text = $data["text"];
        $create = new TreeElement();
        print $create->create($parent_id, $text);
    }

    public static function DELETE($data)
    {
        /*
         * Метод для запроса типа DELETE. Вызывает метод destroy класса TreeElement для удаления данных по полю id.
         * Принимает JSON объект
         * */
        $id = json_decode($data, true);
        // преобразование json в массив
        $del = new TreeElement();
        print $del->destroy($id['id']);
    }

    public static function Put($data)
    {
        //  Метод для запроса типа PUT
        echo 'Put method';
    }
}
