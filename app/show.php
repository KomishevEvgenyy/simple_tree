<?php

require '../db/db.php';

class Show{
    public function showRecords($id, $link){
        $query = "SELECT * FROM tree_table WHERE id=".$id;
        mysqli_query($link, $query) or die(mysqli_error($link));
    }
}

