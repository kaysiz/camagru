<?php 

    include "./database.php";

    //create tables for users
    $user = "CREATE TABLE IF NOT EXISTS users ("
    . "id int NOT NULL AUTO_INCREMENT,"
    . "username varchar(100) UNIQUE,"
    . "email varchar(100) UNIQUE,"
    . "role varchar(100) DEFAULT 'general',"
    . "password varchar(1000),"
    . "isActive varchar(1000) NOT NULL DEFAULT 0,"
    . "dateCreated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,"
    . "PRIMARY KEY (id));";
    try {
        $conn->exec($user);
        echo "Users table created successfully <br>";
    } catch (PDOException $e) {
        echo "error: " . $user . "<br>" . $e->getMessage();
    }

    //create tables for images
    $images = "CREATE TABLE IF NOT EXISTS images ("
    . "id int NOT NULL AUTO_INCREMENT,"
    . "name varchar(100) NOT NULL,"
    . "imgKey varchar(100) NOT NULL UNIQUE,"
    . "userId varchar(100) NOT NULL,"
    . "likes int NOT NULL DEFAULT 0,"
    . "dateCreated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,"
    . "PRIMARY KEY (id));";
    try {
        $conn->exec($images);
        echo "Images table created successfully <br>";
    } catch (PDOException $e) {
        echo "error: " . $images . "<br>" . $e->getMessage();
    }
    $conn = null;