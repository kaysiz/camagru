<?php 

    include "./database.php";

    //Delete Database
    $user = "DROP DATABASE IF EXISTS ". $DB_NAME;
    try {
        $conn->exec($user);
        echo "Deleted database successfully <br>";
    } catch (PDOException $e) {
        echo "error: " . $user . "<br>" . $e->getMessage();
    }
    $conn = null;