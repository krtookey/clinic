<?php
// Assigning form items to PHP variables that we can use 
//include 'pharma_lab_forms.php';
$addscript = <<<ADDSCRIPT
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
ADDSCRIPT;
echo($addscript);

include_once 'dbConnection.php';

$status = 0;

$patient_id = $_POST["patient_id"];
$user_id = $_POST["user_id"];
$pharmacy = $_POST["pharmacy"] ?? '';
$drugname = $_POST["drugname"] ?? '';
$dosage = $_POST["dosage_num"] . $_POST["unit"] . " " .$_POST["dosage_type"] ?? '';
$route = $_POST["route"] ?? '';
$qtyperdose = $_POST["qtyperdose"] ?? "1";
$frequency = $_POST["frequency"] ?? "1";
$duration = $_POST["duration"] ?? "1";
$usage_details = $_POST["qtyperdose"] . " " . $_POST["frequency"] . " " . $_POST["duration"];
$quantity = $_POST["quantity"] ?? "1";
$quantity_calc = (floatval($_POST["qtyperdose"])*floatval($_POST["frequency"])*intval($_POST["duration"]));

//echo("qtyperdose as int: " . floatval($_POST["qtyperdose"]));

$refills = $_POST["refills"];
$usage_info = $_POST["usage_info"];

//echo ("<br>pharmacy " . $pharmacy . "<br>" . "drugname " . $drugname . "<br>" . "dosage " . $dosage . "<br>" . "route " . $route . "<br>" . "usage_details " .$usage_details . "<br>" . "quantity " . $quantity . "<br>" . "quantity_calc " . $quantity_calc . "<br>" . "refills " . $refills . "<br>" . "Usageinfo " . $usage_info);

// Validation of results
if ($quantity != intval($quantity_calc)){
    echo("This is not what is supposed to happen. Oops. <br>");
}

// Getting patient_id from Medical Records page
//$patient_id = "1"; // PLACEHOLDER

// Getting patient info for prescription
$sql = "SELECT first_name, last_name, middle_name, DOB, address_id, sex FROM Patient WHERE patient_id='" . $patient_id . "';";
$result = $conn->query($sql);
if ($row = $result->fetch_assoc()){
    $firstname = $row["first_name"];
    $lastname = $row["last_name"];
    $middlename = $row["middle_name"];
    $DOB = $row["DOB"];
    $address_id = $row["address_id"];
    $sex = $row["sex"];
        // Grabbing patient address data from patient address_id
    $sql = "SELECT street, city, state_abbr, zip FROM Addresses WHERE address_id='" . $address_id . "';";
    $result = $conn->query($sql);
    if ($row = $result->fetch_assoc()){
        $address_street = $row["street"];
        $address_city = $row["city"];
        $address_state = $row["state_abbr"];
        $address_zip = $row["zip"];
    } else {
        echo('Unable to find address info for specified address ID');
    }
} else {
    echo('Unable to find info for specified patient');
}
// Grabbing doctor_id from logged in user
//$doctor_id = "1"; // PLACEHOLDER

// Grabbing doctor name based on doctor_id

$drname_sql = "SELECT user_name FROM Users WHERE user_id='" . $user_id . "';";
$drname_result = $conn->query($drname_sql);
$row = $drname_result->fetch_assoc();
$doctor_name = $row["user_name"];

// Getting medication ID for drugname
$drugid_sql = "SELECT medication_id FROM DrugList WHERE medication_name='" . $drugname . "' OR generic_name='" . $drugname . "';";
$drugid_result = $conn->query($drugid_sql);
$row = $drugid_result->fetch_assoc();
    
if ($drugid_result->num_rows == 1){
    $drug_id = $row["medication_id"];
} else if ($drugid_result->num_rows > 1){
    // How will we handle if there is more than 1 drug with a certain name?
    $drug_id = $row["medication_id"];
} else {
    // Reject form, tell user to enter another drug name, have a form to add new drug
    echo("<br>There is no drug with that name in our system. Please add the drug to the system using the form on this page: <a href='patient.php'>Patient</a><br><br>");
    exit(1);
}


// Getting pharmacy_id for pharmacy
$pharmacy_id = 0;
$pharmaid_sql = 'SELECT pharmacy_id FROM pharmacy WHERE pharmacy_name="' . $pharmacy . '";';
$pharmaid_result = $conn->query($pharmaid_sql);
$row = $pharmaid_result->fetch_assoc();
    
if ($pharmaid_result->num_rows == 1){
    $pharmacy_id = $row["pharmacy_id"];
} else if ($pharmaid_result->num_rows > 1){
    // How will we handle if there is more than 1 pharmacy with a certain name?
    echo("More than 1 pharmacy with the same name. The id of the first row with that name will be used.");
    $pharmacy_id = $row["pharmacy_id"];
} else {
        // Reject form, tell user to enter another pharmacy name, have a form to add new pharmacy
}

// Getting everything ready to be sent

// Sending the data to the pharmacy

$prescription_text = <<<PRESCRIPTIONTEXT
<div id="pdf_text">
<h3>Prescription</h3>
<p>$pharmacy</p>
<h4>$firstname $lastname   $DOB  Sex: $sex</h4>
<p>$address_street $address_city $address_state, $address_zip</p>
<p>Prescribing Doctor: $doctor_name</p>
<p>$drugname $dosage - $route</p>
<p>Quantity: $quantity -- Refills: $refills</p>
<p>$qtyperdose per dose, $frequency times per day, for $duration days</p>
<p>Usage Info: $usage_info</p>
</div>
PRESCRIPTIONTEXT;
echo ($prescription_text);

$prescription_pdf_link = <<<PRESCRIPTIONLINK
<script>
function createPDF(){
    const element = document.getElementById('pdf_text');
    html2pdf().from(element).save();
    console.log(element);
}
</script>
<button onclick='createPDF()'>Create PDF</button>
PRESCRIPTIONLINK;

$status = 1;
//<p>Quantity Per Dose: $qtyperdose -- Frequency: $frequency per day  Duration: $duration days </p>
echo "<br><br>" . $prescription_pdf_link;

// Adding the data into the Prescriptions table
$scrip_database = <<<PRESCRIPTIONDATABASE
INSERT INTO Prescriptions (patient_id, doctor_id, pharmacy_id, medication_id, dosage, route, usage_details, quantity, refills, general_notes, status) 
VALUES ('$patient_id', '$user_id', '$pharmacy_id', '$drug_id', '$dosage', '$route', '$usage_details', '$quantity', '$refills', '$usage_info', '$status');
PRESCRIPTIONDATABASE;

if($conn->query($scrip_database) === TRUE){
    echo("The data was inserted into the database correctly. All is well!");
}
$conn->close();
?>



