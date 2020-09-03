<?php

class Main {

    public function addToTable($id, $parent_id, $text, $link){
        $query = "INSERT INTO tree_table(id, parent_id, text) VALUES ({$id}, {$parent_id}, '{$text}')";
        mysqli_query($link, $query) or die(mysqli_error($link));

    }
    public function deleteFromTable($deleteId, $link){
        $query = "DELETE FROM tree_table WHERE id={$deleteId}";
        mysqli_query($link, $query) or die(mysqli_error($link));
    }
}
require "../db/db.php";
$create = new Main();
if (isset($_POST["id"])){
    $id = $_POST["id"];
    $parent_id = $_POST["parent_id"];
    $text = $_POST["text"];
    $create->addToTable($id, $parent_id, $text, $link);
}
//if (isset($_POST["id"])){
//    $deleteId = $_POST["id"];
//    $create->deleteFromTable($deleteId, $link);
//}




