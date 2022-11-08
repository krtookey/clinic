<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clinic";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Grabbing data from form
$pharmacy_name = $_POST["pharmacy_name"];
$pharmacy_street = $_POST["pharmacy_street"];
$pharmacy_city = $_POST["pharmacy_city"];
$pharmacy_state = $_POST["pharmacy_state"];
$pharmacy_zip = $_POST["pharmacy_zip"];
$pharmacy_phone = $_POST["pharmacy_phone"];
$pharmacy_email = $_POST["pharmacy_email"];


$pharm_addr_sql = <<<PH_ADDR
INSERT INTO Addresses (street, city, state, zip) Values ('$pharmacy_street', '$pharmacy_city', '$pharmacy_state', '$pharmacy_zip');
PH_ADDR;
// Insert address data into Addresses
if ($conn->query($pharm_addr_sql) === TRUE){
    $addr_id_sql = <<<SELECTLABORDER
    SELECT address_id FROM Addresses WHERE street='$pharmacy_street' AND city='$pharmacy_city' AND state='$pharmacy_state';
    SELECTLABORDER;
    // Get address id to put into Pharmacy
    $addrid_result = $conn->query($addr_id_sql);
    $row = $addrid_result->fetch_assoc();
    $address_id = $row["address_id"];
    $sql = <<<NEWMEDS
    INSERT INTO Pharmacy (pharmacy_name, address_id, phone, email) VALUES ('$pharmacy_name', '$address_id', '$pharmacy_phone', '$pharmacy_email');
    NEWMEDS;
    // Insert Pharmacy info into table
    if($conn->query($sql) === TRUE){
        // The pharmacy failed to be added to the database.
    } else {
        //Pharmacy was added to database successfully.
    }
}


?>