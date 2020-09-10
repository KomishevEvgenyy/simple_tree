<?php

namespace app;

require_once 'main.php';
require_once 'DB.php';

/*
 * Класс TreeElement которая имеет метода для получения всех записе из БД (show), создания записи в БД (create),
 * удаление записей из БД (destroy), внесение изменений в БД (edit).
 * */

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
        /*
         * Метод для создания запсей в базе данных, данные которое получены c контроллера. При успешном добавлении данных
         * выводит последний добавленный id последнего запроса на клиентскую часть в формате JSON.
         * В случае ошибки отправляет данные ошибки на клиентскую часть.
         **/
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
        /*
         * Метод для вывода записей которые находятся в БД. При успешном выполнении запроса выводит все данные
         * на клиентскую часть в формате JSON. При отсутствии записей в БД выводит пустую строку.
         * В случае ошибки отправляет данные ошибки на клиентскую часть.
         **/
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
        // Метод для внесения изминений в полях БД

    }

    /**
     * @param $id
     * @return string
     */
    public function destroy($id)
    {
        /*
         * Метод для удаления записей которые находятся в БД. При успешном выполнении запроса на клиентскую часть,
         *  отправляет JSON ответ о положительном выполнении команды. В случае ошибки отправляет данные ошибки на
         * клиентскую часть.
         **/
        try {
            foreach ($id as $value) {
                /*
                 * перебор полученных данных и отправка в БД запросов для удаления записи с его дочерними записями,
                 * если  таковы имеются.
                 **/
                $this->db->query("DELETE FROM tree_table WHERE id={$value}");
            }
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

