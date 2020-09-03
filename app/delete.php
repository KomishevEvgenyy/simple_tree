<?php

require 'main.php';

class Delete{

    public function deleteRecord($deleteId, $link){
        $query = "DELETE FROM tree_table WHERE id={$deleteId}";
        mysqli_query($link, $query) or die(mysqli_error($link));
    }
}


