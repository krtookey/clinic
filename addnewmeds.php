<?php
// Grab data from form
$brandname = $_POST["brandname"];
$genericname = $_POST["genericname"];

include_once 'dbConnection.php';

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