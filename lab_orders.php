<?php
// Assigning form items to PHP variables that we can use 
//include 'pharma_lab_forms.php';
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

$labdest = $_POST["labdest"];
$providers_to_cc = $_POST["providers_to_cc"];
$diagnosis = $_POST["diagnosis"];
if (isset($_POST["general_labs"])){
    $general_labs = $_POST["general_labs"];
}
if (isset($_POST["vitamin_labs"])){
    $vitamin_labs = $_POST["vitamin_labs"];
}
if (isset($_POST["sti_tests"])){
    $sti_tests = $_POST["sti_tests"];
}


//echo("qtyperdose as int: " . floatval($_POST["qtyperdose"]));

$refills = $_POST["refills"];
$usage_info = $_POST["usage_info"];

//echo ("<br>pharmacy " . $pharmacy . "<br>" . "drugname " . $drugname . "<br>" . "dosage " . $dosage . "<br>" . "route " . $route . "<br>" . "usage_details " .$usage_details . "<br>" . "quantity " . $quantity . "<br>" . "quantity_calc " . $quantity_calc . "<br>" . "refills " . $refills . "<br>" . "Usageinfo " . $usage_info);

// Validation of results
if ($quantity != intval($quantity_calc)){
    echo("This is not what is supposed to happen. Oops. <br>");
}

// Getting patient_id from Medical Records page
$patient_id = "1"; // PLACEHOLDER

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
$doctor_id = "1"; // PLACEHOLDER

// Grabbing doctor name based on doctor_id
/*
$sql = "SELECT doctor_name FROM Users WHERE user_id='" . $doctor_id . "';";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$doctor_name = $row["user_name"];
*/
$doctor_name = "Joey Danger"; // PLACEHOLDER

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
/*
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

*/
// Getting everything ready to be sent

$conn->close();

// Sending the data to the pharmacy
$prescription_pdf_link = <<<PRESCRIPTIONLINK
<a href="javascript:htmlToPdf()">Prescription PDF</a>
<script src="<https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.debug.js>"></script>
<script src="prescriptionpdf.js"></script>
PRESCRIPTIONLINK;

$prescription_text = <<<PRESCRIPTIONTEXT
<div id="pdf_text">
<p>$pharmacy</p>
<p><u>$firstname $lastname   $DOB  Sex: $sex</u></p>
<p>$address_street
$address_city $address_state, $address_zip</p>
<p>Prescribing Doctor: $doctor_name</p>
<p>$drugname $dosage - $route</p>
<p>Quantity: $quantity -- Refills: $refills</p>
<p>$qtyperdose per dose, $frequency times per day, for $duration days</p>
<p>Usage Info: $usage_info</p>
</div>
PRESCRIPTIONTEXT;

//<p>Quantity Per Dose: $qtyperdose -- Frequency: $frequency per day  Duration: $duration days </p>
echo "<br><br>" . $prescription_pdf_link;

// Adding the data into the Prescriptions table
/*
$scrip_database = <<<PRESCRIPTIONDATABASE
INSERT INTO Prescriptions (patient_id, doctor_id, pharmacy_id, medication_id, dosage, route, usage_details, quantity, refills, general_notes, status) 
VALUES ($patient_id, $doctor_id, $pharmacy_id, $drug_id, $dosage, $route, $usage_details, $quantity, $refills, $general_notes, $status);";
PRESCRIPTIONDATABASE;
*/
?>



