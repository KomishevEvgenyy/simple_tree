<?php

namespace app;

require_once 'TreeElement.php';

/*
 * The controller calls the required method that matches the type of the received request. Accepts the request body
 * and then passes the input data to the Tree Element class to execute a database query.
 * */

class TreeController
{
    public static function GET($data)
    {
        /*
         * Method for a GET request. Calls the show method of the Tree Element class to send data from the database
         * to the client side
         * */
        $show = new TreeElement();
        print $show->show();
    }

    public static function POST($data)
    {
        /*
         * Method for a POST request. Calls the create method of the TreeElement class to write data received
         * from the client side to the database
         * */
        $parent_id = $data["parent_id"];
        $text = $data["text"];
        $create = new TreeElement();
        print $create->create($parent_id, $text);
    }

    public static function DELETE($data)
    {
        /*
         * Method for a DELETE request. Calls the destroy method of the Tree Element class to remove data
         * from the id field. Accepts a JSON object
         * */
        $id = json_decode($data, true);
        // Json to array conversion
        $del = new TreeElement();
        print $del->destroy($id['id']);
    }

    public static function Put($data)
    {
        //  Method for PUT request
        echo 'Put method';
    }
}
