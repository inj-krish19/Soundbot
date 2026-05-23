<?php

    $serverName = $_ENV["DB_HOST"] ?? null;
    $userName = $_ENV["DB_USER"] ?? null;
    $password = $_ENV["DB_PASSWORD"] ?? null;
    $databaseName = $_ENV["DB_NAME"] ?? null;
    $portNumber = $_ENV['DB_PORT'] ?? null;


    $conn = mysqli_connect($serverName,$userName,$password, $databaseName, $portNumber);
    
    // $database = "create database  if not exists $databaseName";
    
    // mysqli_query($conn,$database);
    
    // mysqli_select_db($conn,$databaseName);

    if($conn){
        // echo "<h1>Connection Established</h1>";
    }else{
        echo "<h1>Unable To Establish Connection</h1>";
    }

?>