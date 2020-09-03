<?php

require 'main.php';

class Show{
    public function showRecords($id, $link){
        $query = "SELECT * FROM tree_table WHERE id=".$id;
        $result = mysqli_query($link, $query) or die(mysqli_error($link));
        for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
        // в переменной data находится массив с данными из базы
        //return $data;
    }
}

