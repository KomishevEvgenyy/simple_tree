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

    public function addToTable($addRequest, $link){
        $query = "INSERT INTO tree_table(id, parent_id, text) VALUES ('id', parent_id, $addRequest)";
        mysqli_query($link, $query) or die(mysqli_error($link));
    }
    public function deleteFromTable($deleteRequest, $link){
        $query = "DELETE FROM tree_table WHERE id=$deleteRequest";
        mysqli_query($link, $query) or die(mysqli_error($link));
    }
}
echo $_POST['textAdd'].$_POST['id'].$_POST['parentId'];
$create = new Main();
//if ($_POST["textAdd"]){
//    $add = $_POST["textAdd"];
//    $create->addToTable($add, $link);
//}
//if ($_POST["textDel"]){
//    $delete = $_POST["textDel"];
//    $create->deleteFromTable($delete, $link);
//}




