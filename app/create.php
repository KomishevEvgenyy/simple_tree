<?php

require "../db/db.php";

class Create {

    public function createRecord($id, $parent_id, $text, $link){
        $query = "INSERT INTO tree_table(id, parent_id, text) VALUES ({$id}, {$parent_id}, '{$text}')";
        mysqli_query($link, $query) or die(mysqli_error($link));

    }
}


if (isset($_POST["create"])){
    $id = $_POST["id"];
    $parent_id = $_POST["parent_id"];
    $text = $_POST["text"];
    $create = new Create();
    $create->createRecord($id, $parent_id, $text, $link);
}




