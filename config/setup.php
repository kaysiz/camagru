<?php 

    include "./database.php";
    
    $DB_DSN = 'localhost';
    $DB_USER = 'root';
    $DB_PASSWORD = 'rooting';
    $DB_NAME = 'camagru';
    //connect to the newly created database
    try {
        $conn = new PDO("mysql:host=$DB_DSN;dbname=$DB_NAME", $DB_USER, $DB_PASSWORD);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e)
    {
        echo "Connection failed: " . $e->getMessage();
    }

    //create tables for users
    $user = "CREATE TABLE IF NOT EXISTS users ("
    . "id int NOT NULL AUTO_INCREMENT,"
    . "username varchar(100) NOT NULL UNIQUE,"
    . "email varchar(100) NOT NULL UNIQUE,"
    . "password varchar(1000) NOT NULL,"
    . "token varchar(1000) NOT NULL,"
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
    . "imgName varchar(100) NOT NULL,"
    . "imgId varchar(100) NOT NULL UNIQUE,"
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

    //create tables for images
    $comments = "CREATE TABLE IF NOT EXISTS comments ("
    . "id int NOT NULL AUTO_INCREMENT,"
    . "imgId varchar(100) NOT NULL,"
    . "userId varchar(100) NOT NULL,"
    . "comment text NOT NULL,"
    . "dateCreated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,"
    . "PRIMARY KEY (id));";
    try {
        $conn->exec($comments);
        echo "Comments table created successfully <br>";
    } catch (PDOException $e) {
        echo "error: " . $comments . "<br>" . $e->getMessage();
    }

    //create tables for images
    $pwdreset = "CREATE TABLE IF NOT EXISTS pwdreset ("
    . "id int NOT NULL AUTO_INCREMENT,"
    . "email varchar(100) NOT NULL,"
    . "token varchar(100) NOT NULL,"
    . "dateCreated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,"
    . "PRIMARY KEY (id));";
    try {
        $conn->exec($pwdreset);
        echo "Password reset table created successfully <br>";
    } catch (PDOException $e) {
        echo "error: " . $pwdreset . "<br>" . $e->getMessage();
    }
    $conn = null;