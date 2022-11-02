<?php
// Assigning form items to PHP variables that we can use 
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

// Getting patient_id from Medical Records page
$patient_id = "1";

// Getting patient info for prescription
$sql = "SELECT first_name, last_name, middle_name, DOB, address_id, sex FROM Patient WHERE patient_id='" . $patient_id . "';";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$firstname = $row["first_name"];
$lastname = $row["last_name"];
$middlename = $row["middle_name"];
$DOB = $row["DOB"];
$addressid = $row["address_id"];
$sex = $row["sex"];

$sql = "SELECT street, city, state_abbr, zip FROM Addresses WHERE address_id='" . $addressid . "';";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$address_street = $row["street"];
$address_city = $row["city"];
$address_state = $row["state_abbr"];
$address_zip = $row["zip"];

// Grabbing doctor_id from logged in user
$doctor_id = "1";


// Getting medication ID for drugname
$sql = "SELECT medication_id FROM DrugList WHERE medication_name='" . $drugname . "' OR generic_name='" . $drugname . "';";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
    
if ($result->num_rows == 1){
    $drug_id = $row["medication_id"];
} else if ($result->num_rows > 1){
    // How will we handle if there is more than 1 drug with a certain name?
} else {
        // Reject form, tell user to enter another drug name, have a form to add new drug
}


// Getting pharmacy_id for pharmacy
$sql = "SELECT pharmacy_id FROM pharmacy WHERE pharmacy_name='" . $pharmacy . "';";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
    
if ($result->num_rows == 1){
    $pharmacy_id = $row["pharmacy_id"];
} else if ($result->num_rows > 1){
    // How will we handle if there is more than 1 drug with a certain name?
} else {
        // Reject form, tell user to enter another drug name, have a form to add new drug
}


// Getting everything ready to be sent

$conn->close();

// Sending the data to the pharmacy
$prescription_text = <<<PRESCRIPTIONTEXT
<p>$firstname $lastname   $DOB</p><br>
<p>$address_street</p><br>
<p>$address_city $address_state $address_zip</p><br>
<p>$drugname</p><br>


PRESCRIPTIONTEXT;

// Adding the data into the Prescriptions table
$sql_scrip_into_database = <<<SCRIP_INTO_DATABASE
INSERT INTO Prescriptions (patient_id, doctor_id, pharmacy_id, medication_id, dosage, route, usage_details, quantity, refills, general_notes, status) 
VALUES ($patient_id, $doctor_id, $pharmacy_id, $drug_id, $dosage, $route, $usage_details, $quantity, $refills, $general_notes, $status);";
SCRIP_INTO_DATABASE;
?>



