<?php
// Grab data from form
$brandname = $_POST["brandname"];
$genericname = $_POST["genericname"];

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

// Insert drug info into DrugList
$sql = <<<NEWMEDS
INSERT INTO DrugList (medication_name, generic_name) VALUES ('$brandname', '$genericname');
NEWMEDS;
if($conn->query($sql) === TRUE){
    // The medication failed to be added to the database.
} else {
    //Medication was added to database successfully.
}
?>