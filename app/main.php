<?php

require '../db/db.php';

if (isset($_POST["create"])){
    $id = $_POST["id"];
    $parent_id = $_POST["parent_id"];
    $text = $_POST["text"];
    $create = new Create();
    $create->createRecord($id, $parent_id, $text, $link);
}
elseif (isset($_POST["delete"])){
    $deleteId = $_POST["id"];
    $delete = new Delete();
    $delete->deleteRecord($deleteId, $link);
}
elseif(isset($_POST["edit"])){
    $editId = $_POST["edit"];
    $edit = new Edit();
    $edit->editRecord($editId, $link);
}
elseif (isset($_POST["show"])){
    $showId = $_POST["show"];
    $show = new Show();
    $show->showRecord($showId, $link);
}