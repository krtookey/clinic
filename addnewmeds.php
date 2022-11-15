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
    echo("The data was inserted into the database correctly. All is well!");
} else {
    //Medication was added to database successfully.
    echo("The data was not inserted into the database correctly. Please contact an administrator.");
}
?>