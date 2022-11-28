<?php
include_once 'dbConnection.php';
include_once 'testinput.php';
// Grabbing variables from form

$patient_id = test_input($_POST["patient_id"]) ?? 1;
//echo("patient_id: " . $patient_id);
//$patient_id = 1;
$drugname = test_input($_POST["drugname"]) ?? '';
$dosage = test_input($_POST["dosage_num"]) . " " . test_input($_POST["unit"]) ?? '';
if (isset($_POST["taking"])){
    $status = 1;
} else {
    $status = 0;
}

// Getting medication ID for drugname
$drugid_sql = "SELECT medication_id FROM DrugList WHERE medication_name='" . $drugname . "' OR generic_name='" . $drugname . "';";
$drugid_result = $conn->query($drugid_sql);
if ($row = $drugid_result->fetch_assoc()){
    $drug_id = $row["medication_id"];
} else {
    echo("<br>There is no drug with that name in our system. Please add the drug to the system using the Add Drug To List form");
    exit(1);
}


$addmedtolist_sql = <<<ADDMEDTOLIST
INSERT INTO MedicationList (patient_id, medication_id, dosage, status) VALUES ('$patient_id', '$drug_id', '$dosage', '$status');
ADDMEDTOLIST;
if($conn->query($addmedtolist_sql) === TRUE){
    // The medication failed to be added to the database.
    echo("The data was inserted into the database correctly. All is well!");
} else {
    //Medication was added to database successfully.
    echo("The data was not inserted into the database correctly. Please contact an administrator.");
}



?>