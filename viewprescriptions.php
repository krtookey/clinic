<?php
//#$viewprescriptions
$patient_id = $_POST['patient_id'] ?? 1;
// Grabbing all prescriptions and their data for patient
$get_prescriptions = <<<GETPRESCRIPTIONS
SELECT * FROM Prescriptions WHERE patient_id = '$patient_id';
GETPRESCRIPTIONS;
$prescriptions_result = $conn->query($get_prescriptions);
while ($prescriptions_row = $prescriptions_result->fetch_assoc()){
// Prescription result variables
$user_id = $prescriptions_row['doctor_id'];

$pharmacy_id = $prescriptions_row['pharmacy_id'];
$pharmaid_sql = 'SELECT pharmacy_name FROM pharmacy WHERE pharmacy_id="' . $pharmacy_id . '";';
$pharmaid_result = $conn->query($pharmaid_sql);
$row = $pharmaid_result->fetch_assoc();
if ($pharmaid_result->num_rows == 1){
    $pharmacy_name = $row["pharmacy_name"];
} else {
        // Reject form, tell user to enter another pharmacy name, have a form to add new pharmacy
}

$drug_id = $prescriptions_row['medication_id'];
$drugname_sql = "SELECT medication_name, generic_name FROM DrugList WHERE medication_id ='" . $drug_id . "';";
$drugname_result = $conn->query($drugname_sql);
$row = $drugname_result->fetch_assoc();
$brand_name = $row['medication_name'];
$generic_name = $row['generic_name'];

// Select doctor name from user id
$drname_sql = "SELECT user_name FROM Users WHERE user_id='" . $user_id . "';";
$drname_result = $conn->query($drname_sql);
if ($row = $drname_result->fetch_assoc()){
    $doctor_name = $row["user_name"];
} else {
    echo("There is no name associated with the ID on this prescription. Please contact an admin.");
    $doctor_name = "Unknown";
}

$dosage = $prescriptions_row['dosage'];
$route = $prescriptions_row['route'];
$usage_details = $prescriptions_row['usage_details'];
$usage_arr = explode(' ', $usage_details);
$qtyperdose = $usage_arr[0];
$frequency = $usage_arr[1];
$duration = $usage_arr[2];
$quantity = $prescriptions_row['quantity'];
$refills = $prescriptions_row['refills'];
$general_notes = $prescriptions_row['general_notes'];
$orderdate = $prescriptions_row['orderdate'];
$status = $prescriptions_row['status'];

$prescription_details_print = <<<PRESCIPTIONS_PRINT
<div class="prescription_detail_box" style="border-style:solid; border-radius:25px; padding:1em;">
<p>$pharmacy_name</p>
<p>$orderdate</p>
<p>Prescribing Doctor: $doctor_name</p>
<p>$brand_name ($generic_name) $dosage - $route</p>
<p>Quantity: $quantity -- Refills: $refills</p>
<p>$qtyperdose per dose, $frequency times per day, for $duration days</p>
<p>Usage Info: $general_notes</p>
</div>
PRESCIPTIONS_PRINT;
echo($prescription_details_print); 
}
?>