<?php
    $servername = "localhost";
    $username = "root";
    $password = '<password>';
    $dbname = "Clinic";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }
    // Make sure that user has permissions that are required for the rest of the system to work
    $sql = "SELECT Patient.first_name, Patient.last_name, Patient.DOB, Patient.sex, Patient.preferred
            FROM Patient
            WHERE Patient.patient_id = 1";
    try {    
        $result = $conn->query($sql);   
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }
?>