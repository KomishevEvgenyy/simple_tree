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

    public static function Delete($data)
    {
        $id = $data['id'];
        $delete = new TreeElement();
        print $delete->delete($id);
    }

    public static function Put($data)
    {
        print 'put';
    }
}