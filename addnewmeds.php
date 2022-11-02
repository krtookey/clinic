<?php
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

$sql = <<<NEWMEDS
INSERT INTO DrugList (medication_name, generic_name) VALUES ('$brandname', '$genericname');
NEWMEDS;
$result = $conn->query($sql);
$response = $result->fetch_assoc();
if (strpos($response) == false){
    // The medication failed to be added to the database.
} else {
    //Medication was added to database successfully.
}
?>