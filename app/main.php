<?php
require_once 'DB/db.php';

class Main {
//    private $addRequest;
//    private $deleteRequest;
//    private $link;

//    public function __construct($addRequest, $deleteRequest, $link){
//        $this->addRequest = $addRequest;
//        $this->deleteRequest = $deleteRequest;
//        $this->link = $link;
//    }

    public function addToTable($id, $parent_id, $text, $link){
        $query = "INSERT INTO tree_table(id, parent_id, text) VALUES ({$id}, {$parent_id}, '{$text}')";
        mysqli_query($link, $query) or die(mysqli_error($link));
    }
    public function deleteFromTable($deleteRequest, $link){
        $query = "DELETE FROM tree_table WHERE id={$deleteRequest}";
        mysqli_query($link, $query) or die(mysqli_error($link));
    }
}

$create = new Main();
if (isset($_POST["textAdd"])){
    $id = $_POST["id"];
    $parent_id = $_POST["parent_id"];
    $text = $_POST["text"];
    $create->addToTable($id, $parent_id, $text, $link);
}
if (isset($_POST["textDel"])){
    $deleteRequest = $_POST["textDel"];
    $create->deleteFromTable($deleteRequest, $link);
}




