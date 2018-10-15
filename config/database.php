<?php

    $DB_DSN = 'localhost';
    $DB_USER = 'root';
    $DB_PASSWORD = 'rooting';
    $DB_NAME = 'camagru';


    //connect to the database
    try {
        $db = new PDO("mysql:host=$DB_DSN", $DB_USER, $DB_PASSWORD);
        // set the PDO error mode to exception
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e)
    {
        echo "Connection failed: " . $e->getMessage();
    }

    //create Database
    try{
        $sql = "CREATE DATABASE IF NOT EXISTS ".$DB_NAME;
        if($db->exec($sql)){
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
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }