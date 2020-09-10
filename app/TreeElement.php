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

            'id' => $data->lastInsertID()
        ]);
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
    }

    public function edit($id, $parentId, $text)
    {
        // метод для изменения

    }

    /**
     * @param $id
     * @return string
     */
    public function destroy($id)
    {
        // метод для удаления файла
        try {
            foreach ($id as $value) {
                $this->db->query("DELETE FROM tree_table WHERE id={$value}"); // XOR parent_id={$id}
            }
            //$this->db->query("DELETE FROM tree_table WHERE id={$result}"); // XOR parent_id={$id}
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

