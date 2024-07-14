<?php

    $serverName = "localhost";
    $userName = "root";
    $password = "";
    $databaseName = "SoundBot";


    $conn = mysqli_connect($serverName,$userName,$password);
    
    $database = "create database  if not exists $databaseName";
    
    mysqli_query($conn,$database);
    
    mysqli_select_db($conn,$databaseName);

    if($conn){
        // echo "<h1>Connection Established</h1>";
    }else{
        echo "<h1>Unable To Establish Connection</h1>";
    }

?>