<?php

require 'main.php';

class Create {

    public function createRecord($id, $parent_id, $text, $link){
        $query = "INSERT INTO tree_table(id, parent_id, text) VALUES ({$id}, {$parent_id}, '{$text}')";
        mysqli_query($link, $query) or die(mysqli_error($link));
    }
}







