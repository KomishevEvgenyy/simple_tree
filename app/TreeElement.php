<?php

namespace app;

require_once 'main.php';
require_once 'DB.php';

/*
 * The Tree Element class has methods for getting all records from the database (show), creating a record
 * in the database (create), deleting records from the database (destroy), making changes to the database (edit).
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
         * Method for creating records in the database, data that is received from the controller. On successful addition of data
         * prints the last added id of the last request to the client side in JSON format.
         * In case of an error, sends the error data to the client side.
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
         * Method for displaying records that are in the database. On successful execution of the request, displays all
         * data to the client side in JSON format. If there are no records in the database, it displays an empty string.
         * In case of an error, sends the error data to the client side.
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
        // Method for making changes to data in database fields

    }

    /**
     * @param $id
     * @return string
     */
    public function destroy($id)
    {
        /*
         * Method for deleting records that are in the database. Upon successful completion of the client-side request,
         * sends a JSON response about the positive execution of the command. In case of an error, sends the error data to
         * client side.
         **/
        try {
            foreach ($id as $value) {
                /*
                 * Iteration of the received data and sending queries to the database to delete a record with its child
                 * records, if any.
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

