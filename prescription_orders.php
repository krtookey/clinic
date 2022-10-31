<?php
// Assigning form items to PHP variables that we can use 
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}


$pharmacy = $_POST["pharmacy"];
$drugname = $_POST["drugname"];
$dosage = $_POST["dosage_num"] . $_POST["unit"];
$route = $_POST["route"];
$usage_details = $_POST["qtyperdose"] . " " . $_POST["frequency"] . " " . $_POST["duration"];
$quantity = $_POST["quantity"];
$quantity_calc = (floatval($_POST["qtyperdose"])*floatval($_POST["frequency"])*intval($_POST["duration"]));

echo("qtyperdose as int: " . floatval($_POST["qtyperdose"]));

$refills = $_POST["refills"];
$usage_info = $_POST["usage_info"];

echo ("pharmacy " . $pharmacy . "<br>" . "drugname " . $drugname . "<br>" . "dosage " . $dosage . "<br>" . "route " . $route . "<br>" . "usage_details " .$usage_details . "<br>" . "quantity " . $quantity . "<br>" . "quantity_calc " . $quantity_calc . "<br>" . "refills " . $refills . "<br>" . "Usageinfo " . $usage_info);

// Validation of results
if ($quantity != intval($quantity_calc)){
    echo("This is not what is supposed to happen. Oops. <br>");
}



// Getting patient info for prescription
$sql = "SELECT first_name, last_name, middle_name, DOB, address_id, sex FROM Patient WHERE patient_id='" . $patientid . "';";
$result = $conn->query($sql);

$firstname = $row["first_name"];
$lastname = $row["last_name"];
$middlename = $row["middle_name"];
$DOB = $row["DOB"];
$addressid = $row["address_id"];
$sex = $row["sex"];

$sql = "SELECT street, city, state_abbr, zip FROM Addresses WHERE address_id='" . $addressid . "';";
$result = $conn->query($sql);

$address_street = $row["street"];
$address_city = $row["city"];
$address_state = $row["state_abbr"];
$address_zip = $row["zip"];


// Getting medication ID for drugname


$sql = "SELECT medication_id FROM DrugList WHERE medication_name='" . $drugname . "' OR generic_name='" . $drugname . "';";
$result = $conn->query($sql);

if ($result->num_rows == 1){
    $drug_id = $row["medication_id"];
} else if ($result->num_rows > 1){
    // How will we handle if there is more than 1 drug with a certain name?
} else {
    // Popup window that allows user to enter other name for drug, 
}
$conn->close();


// Getting everything ready to be sent



// Sending the data to the pharmacy





// Adding the data into the Prescriptions table

?>
