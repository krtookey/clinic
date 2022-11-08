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
$labdest_name = $_POST["labdest_name"];
$labdest_street = $_POST["labdest_street"];
$labdest_city = $_POST["labdest_city"];
$labdest_state = $_POST["labdest_state"];
$labdest_zip = $_POST["labdest_zip"];
$labdest_phone = $_POST["labdest_phone"];
$labdest_email = $_POST["labdest_email"];


$labdest_addr_sql = <<<PH_ADDR
INSERT INTO Addresses (street, city, state, zip) Values ('$labdest_street', '$labdest_city', '$labdest_state', '$labdest_zip');
PH_ADDR;
// Insert address data into Addresses
if ($conn->query($labdest_addr_sql) === TRUE){
    $addr_id_sql = <<<SELECTLABORDER
    SELECT address_id FROM Addresses WHERE street='$labdest_street' AND city='$labdest_city' AND state='$labdest_state';
    SELECTLABORDER;
    // Get address id to put into LabDest
    $addrid_result = $conn->query($addr_id_sql);
    $row = $addrid_result->fetch_assoc();
    $address_id = $row["address_id"];
    $sql = <<<NEWMEDS
    INSERT INTO LabDest (labdest_name, address_id, phone, email) VALUES ('$labdest_name', '$address_id', '$labdest_phone', '$labdest_email');
    NEWMEDS;
    // Insert LabDest info into table
    if($conn->query($sql) === TRUE){
        // The pharmacy failed to be added to the database.
    } else {
        //Pharmacy was added to database successfully.
    }
}

?>