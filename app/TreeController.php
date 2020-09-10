<?php

namespace app;

require_once 'TreeElement.php';

class TreeController
{
    public static function GET($data)
    {
        $show = new TreeElement();
        print $show->show();
    }

    public static function POST($data)
    {
        $parent_id = $data["parent_id"];
        $text = $data["text"];
        $create = new TreeElement();
        print $create->create($parent_id, $text);
    }

    public static function DELETE($data)
    {
        //$result = file_get_contents('php://input');
        // получения тела запроса так как метод HTTP DELETE не передается по $_REQUEST
        $id = json_decode($data, true);
        // переводим json в массив
        $del = new TreeElement();
        print $del->destroy($id['id']);
    }

    public static function Put($data)
    {
        var_dump($_SERVER['REQUEST_METHOD']);
        var_dump(file_get_contents('php://input'));
        echo 'Put method';
    }
}
