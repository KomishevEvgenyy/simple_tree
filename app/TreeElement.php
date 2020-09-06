<?php

namespace app;

use app\DB;

class TreeElement extends \app\DB
{
//    public $id;
//    public $parentId;
//    public $text;

//    public function __construct($id, $parentId, $text)
//    {
////        $this->id = $id;
////        $this->parentId = $parentId;
////        $this->text = $text;
//
//    }

    public function create($id, $parentId, $text)
    {
        // метод для добавления
        $data = $db->run("INSERT INTO tree_table(id, parent_id, text) VALUES ({$id}, {$parentId}, '{$text}')");
    }

    public function show($id)
    {
        // метод для удаления
        $data = $db->run("SELECT * FROM tree_table WHERE parent_id=?",[$id])->fetchAll();
    }

    public function edit($id, $parentId, $text)
    {
        // метод для изменения

    }

    public function delete($id, $parentId)
    {
        // метод для удаления файла
        $data = $db->run("DELETE FROM tree_table WHERE id={$id}, parent_id={$parentId}");
    }
}