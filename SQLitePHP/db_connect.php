<?php

// $query = "CREATE TABLE IF NOT EXISTS students (name STRING, email STRING)";
// $db->exec($query);

function getConnection(){
    $database_name = "E:\questionare.db";
    $db = new SQLite3($database_name);
    return $db;
}

$db = getConnection();
// $query = "CREATE TABLE IF NOT EXISTS students (name STRING, email STRING)";
// $db->exec($query);

