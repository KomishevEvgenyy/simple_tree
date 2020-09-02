<?php

$createDB = " CREATE DATABASE tree";

$createTable = "CREATE TABLE tree_table (
    id INTEGER NOT NULL,
    parent_id INTEGER NOT NULL,
    text VARCHAR(32) NOT NULL,
    PRIMARY KEY(id),
)";


$host = 'localhost';
$user = 'root';
$password = 'root';
$dbName = 'tree';

$link = mysqli_connect($host, $user, $password, $dbName);

if(mysqli_connect_errno()){
    die("Database connection failed: ". mysqli_connect_error()." (".mysqli_connect_errno().")");
}

mysqli_query($link, "SET NAME utf8");

