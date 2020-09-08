<?php

namespace app;

require_once 'main.php';
require_once 'DB.php';

class TreeElement
{
    public $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    /**
     * @param $parentId
     * @param $text
     * @return false|string
     */
    public function create($parentId, $text)
    {
        // метод для добавления
        try {
            $data = $this->db->query("INSERT INTO tree_table(parent_id, text) VALUES ({$parentId}, '{$text}')");
        } catch (\Exception $e) {
            print json_encode(
                ['success' => false, 'error' => $e->getMessage()]
            );
        }
        print json_encode([
            'success' => true,
            'id' => $data->lastInsertID()
        ]);
//        $error = 'connection error';
//        $data = $this->db->query("INSERT INTO tree_table(parent_id, text) VALUES ({$parentId}, '{$text}')");
//        //var_dump($data->fetchArray());
//        if (isset($data)) {
//            //$this->show();
//            //return $data;
//        } else {
//            return ['success' => false, 'error' => $error];
//        }
    }

    /**
     * @param $id
     * @return string
     */
    public function show()
    {
        // метод для удаления
        try {
            $data = $this->db->query("SELECT * FROM tree_table ");
        } catch (\Exception $e) {
            print json_encode(
                ['success' => false, 'error' => $e->getMessage()]
            );
        }
        print json_encode($data->fetchAll());
//        $error = 'connection error';
//        $data = $this->db->query("SELECT * FROM tree_table");//("SELECT * FROM tree_table WHERE id=?", [$id]);
//        $result = json_encode($data->fetchAll());
//       //var_dump($result);
//        if (isset($result)) {
//            print $result;
//        } else {
//            return ['success' => false, 'error' => $error];
//        }
    }

    public function edit($id, $parentId, $text)
    {
        // метод для изменения

    }

    public function delete($id)
    {
        // привызове метода не получает id селектора
        // метод для удаления файла
        try {
            $this->db->query("DELETE FROM tree_table WHERE id={$id}, parent_id={$id}");
        } catch (\Exception $e) {
            print json_encode(
                ['success' => false, 'error' => $e->getMessage()]
            );
        }
        print json_encode([
            'success' => true
        ]);
    }
}

