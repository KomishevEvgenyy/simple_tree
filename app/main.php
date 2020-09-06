<?php

namespace app;

if (isset($_POST['id'])) {
    $id = $_POST["id"];
    $parent_id = $_POST["parent_id"];
    $text = $_POST["text"];
    $create = new TreeElement();
    $create->create($id, $parent_id, $text);
} elseif (isset($_DELETE["delete"])) {
    $deleteId = $_DELETE["id"];
    $deleteParentId = $_DELETE["parent_id"];
    $delete = new TreeElement();
    $delete->delete($deleteId, $deleteParentId);
} elseif (isset($_PUT["edit"])) {
    $editId = $_PUT["edit"];
    $editParentId = $_PUT["parent_id"];
    $editText = $_PUT["text"];
    $edit = new TreeElement();
    $edit->edit($editId, $editParentId, $editText);
} elseif (isset($_GET["show"])) {
    $showId = $_GET["show"];
    $show = new TreeElement();
    $show->show($showId);
}