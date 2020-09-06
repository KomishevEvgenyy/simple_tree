<?php

namespace app;

require_once 'main.php';
require_once 'DB.php';

class TreeElement
{

    public function create($id, $parentId, $text)
    {
        // метод для добавления
        $db = new DB();
        $error = 'connection error';
        $data = $db->run("INSERT INTO tree_table(id, parent_id, text) VALUES ({$id}, {$parentId}, '{$text}')");
        if (isset($data)) {
            return ['success' => 'true'];
        } else {
            return ['success' => false, 'error' => $error];
        }
    }

    public function show($id)
    {
        // метод для удаления
        $error = 'connection error';
        $db = new DB();
        $data = $db->run("SELECT * FROM tree_table WHERE parent_id=?",[$id])->fetchAll();
        if (isset($data)) {
            return $data;
        } else {
            return ['success' => false, 'error' => $error];
        }
    }

    public function edit($id, $parentId, $text)
    {
        // метод для изменения

    }

    public function delete($id, $parentId)
    {
        // метод для удаления файла
        $error = 'connection error';
        $db = new DB();
        $data = $db->run("DELETE FROM tree_table WHERE id={$id}, parent_id={$parentId}");
        if ($data) {
            return ['success' => true];
        } else {
            return ['success' => false, 'error' => $error];
        }
    }
}

