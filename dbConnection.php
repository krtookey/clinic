<?php
    $servername = "localhost";
    $username = "fsehgal";
    $password = "nobdaty56";
    $dbname = "Clinic";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }
?>