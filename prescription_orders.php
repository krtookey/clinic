<?php
// Assigning form items to PHP variables that we can use 
//include 'pharma_lab_forms.php';
$addscript = <<<ADDSCRIPT
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
ADDSCRIPT;
echo($addscript);

include_once 'dbConnection.php';
include_once 'testinput.php';

$status = 0;
//echo ("post patient id: " . $_POST['patient_id']);
$patient_id = test_input($_POST["patient_id"]) ?? 1;
$user_id = test_input($_POST["user_id"]) ?? 1;
$pharmacy = test_input($_POST["pharmacy"]) ?? '';
$drugname = test_input($_POST["drugname"]) ?? '';
$dosage_num = filter_var(test_input($_POST["dosage_num"]), FILTER_SANITIZE_NUMBER_INT);
$dosage = $dosage_num . test_input($_POST["unit"]) . " " . test_input($_POST["dosage_type"]) ?? '';
$route = test_input($_POST["route"]) ?? '';
$qtyperdose = test_input($_POST["qtyperdose"]) ?? 1;
$frequency = test_input($_POST["frequency"]) ?? 1;
$duration = test_input($_POST["duration"]) ?? 1;
$usage_details = test_input($_POST["qtyperdose"]) . " " . test_input($_POST["frequency"]) . " " . test_input($_POST["duration"]);
$quantity = test_input($_POST["quantity"]) ?? 1;
$quantity_calc = (floatval($_POST["qtyperdose"])*floatval($_POST["frequency"])*intval($_POST["duration"]));

//echo("qtyperdose as int: " . floatval($_POST["qtyperdose"]));

$refills = filter_var($_POST["refills"], FILTER_SANITIZE_NUMBER_INT) ?? 1;
$usage_info = test_input($_POST["usage_info"]) ?? '';


//echo ("<br>pharmacy " . $pharmacy . "<br>" . "drugname " . $drugname . "<br>" . "dosage " . $dosage . "<br>" . "route " . $route . "<br>" . "usage_details " .$usage_details . "<br>" . "quantity " . $quantity . "<br>" . "quantity_calc " . $quantity_calc . "<br>" . "refills " . $refills . "<br>" . "Usageinfo " . $usage_info);

// Validation of results
if ($quantity != intval($quantity_calc)){
    echo("This is not what is supposed to happen. Oops. <br>");
}

// Getting patient_id from Medical Records page
//$patient_id = "1"; // PLACEHOLDER

// Getting patient info for prescription
//$patient_id = 1;
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
if ($row = $drname_result->fetch_assoc()){
    $doctor_name = $row["user_name"];
} else {
    echo("There is no user name for the current user. Please contact an administrator.");
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


// Getting pharmacy_id for pharmacy
$pharmacy_id = 0;
$pharmaid_sql = 'SELECT pharmacy_id FROM pharmacy WHERE pharmacy_name="' . $pharmacy . '";';
$pharmaid_result = $conn->query($pharmaid_sql);
if ($row = $pharmaid_result->fetch_assoc()){
    $pharmacy_id = $row["pharmacy_id"];
} else {
    echo("There is no pharmacy with that name in our system. Please check the spelling or add the pharmacy with the Add Pharmacy form.");
    exit(1);
}


// Getting date of order
date_default_timezone_set("America/New_York");
$orderdate = date('Y-m-d');
echo($orderdate);

// Getting everything ready to be sent

// Sending the data to the pharmacy

$prescription_text = <<<PRESCRIPTIONTEXT
<style>
    #pdf_text{
        border-style: 2px solid #262626;
        border-radius:25px;
        background: EEE1C7;
    }
</style>
<div id="pdf_text">
<h3>Prescription</h3>
<p>Date: $orderdate</p> 
<p>$pharmacy</p>
<h4>Patient: $firstname $lastname   $DOB  Sex: $sex</h4>
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
    var element = document.getElementById('pdf_text');
    var opt = {
        margin: 1,
        filename: 'prescription_order.pdf',
        html2canvas:  { scale: 2, height: 500, width: 550, scrollX: 0, scrollY: 0},
        jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
    }
    html2pdf().set(opt).from(element).save();
}
</script>
<button onclick='createPDF()'>Create PDF</button>
PRESCRIPTIONLINK;

$status = 1;
//<p>Quantity Per Dose: $qtyperdose -- Frequency: $frequency per day  Duration: $duration days </p>
echo "<br><br>" . $prescription_pdf_link;

// Adding the data into the Prescriptions table
$scrip_database = <<<PRESCRIPTIONDATABASE
INSERT INTO Prescriptions (patient_id, doctor_id, pharmacy_id, medication_id, dosage, route, usage_details, quantity, refills, general_notes, orderdate, status) 
VALUES ("$patient_id", "$user_id", "$pharmacy_id", "$drug_id", "$dosage", "$route", "$usage_details", "$quantity", "$refills", "$usage_info", "$orderdate", "$status");
PRESCRIPTIONDATABASE;
//##testdata
//echo("Here is the sql insert statement: " . $scrip_database);
echo("<br>");
if($conn->query($scrip_database) === TRUE){
    echo("The data was inserted into the database correctly. All is well!");
} else {
    echo("The data was not inserted into the database correctly. Please contact an administrator.");
}
$conn->close();
?>



