<?php

    $serverName  = $_ENV["DB_HOST"] ?? "localhost";
    $userName    = $_ENV["DB_USER"] ?? "root";
    $password    = $_ENV["DB_PASSWORD"] ?? "";
    $databaseName = $_ENV["DB_NAME"] ?? "";
    $portNumber  = $_ENV["DB_PORT"] ?? "3306";

    try {

        $conn = new PDO(
            "mysql:host=" . $serverName . 
            ";dbname=" . $databaseName . 
            ";port=" . $portNumber,
            $userName,
            $password
        );

        // Set PDO error mode
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        echo "Database connected successfully";

    } catch (PDOException $e) {

        echo "Connection failed: " . $e->getMessage();

    }

?>