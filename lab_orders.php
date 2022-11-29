<?php
// Assigning form items to PHP variables that we can use 
//include 'pharma_lab_forms.php';
$addscript = <<<ADDSCRIPT
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
ADDSCRIPT;
echo($addscript);

include_once 'dbConnection.php';
include_once 'testinput.php';

$patient_id = test_input($_POST["patient_id"]) ?? 1;
$user_id = test_input($_POST["user_id"]) ?? 1;
$labdest = test_input($_POST["labdest"]);
$providers_to_cc = test_input($_POST["providers_to_cc"]);
$diagnosis = test_input($_POST["diagnosis"]);
if (!isset($_POST["labs"])){
    echo("No labs selected. Please select a lab before ordering.");
    exit(1);
}
$all_labs = $_POST["labs"];

// Getting patient_id from Medical Records page
//$patient_id = "1"; // PLACEHOLDER

// Getting patient info for prescription
$pinfo_sql = "SELECT first_name, last_name, middle_name, DOB, address_id, sex FROM Patient WHERE patient_id='" . $patient_id . "';";
$pinfo_result = $conn->query($pinfo_sql);
if ($pinfo_row = $pinfo_result->fetch_assoc()){
    $firstname = $pinfo_row["first_name"];
    $lastname = $pinfo_row["last_name"];
    $middlename = $pinfo_row["middle_name"];
    $DOB = $pinfo_row["DOB"];
    $address_id = $pinfo_row["address_id"];
    $sex = $pinfo_row["sex"];
        // Grabbing patient address data from patient address_id
    $addr_sql = "SELECT street, city, state_abbr, zip FROM Addresses WHERE address_id='" . $address_id . "';";
    $addr_result = $conn->query($addr_sql);
    if ($addr_row = $addr_result->fetch_assoc()){
        $address_street = $addr_row["street"];
        $address_city = $addr_row["city"];
        $address_state = $addr_row["state_abbr"];
        $address_zip = $addr_row["zip"];
    } else {
        echo('Unable to find address info for specified address ID');
    }
} else {
    echo('Unable to find info for specified patient');
    exit(1);
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

// Grabbing lab_id for lab_name
$lab_ids = array();
foreach($all_labs as $x => $val){
    $sql = "SELECT lab_id FROM LabList WHERE lab_name='" . $val . "';";
    $result = $conn->query($sql);
    if ($row = $result->fetch_assoc()){
        $lab_ids[] = $row['lab_id'];
    } else {
        echo("Unable to find lab id for lab name specified. Pleae contact an administrator.");
    }
}

// Getting labdest_id for LabDest
$labdestid_sql = "SELECT labdest_id FROM LabDest WHERE labdest_name='" . $labdest . "';";
//echo('I like to move it move it: ' . $labdestid_sql . " -- ");
$labdestid_result = $conn->query($labdestid_sql);
if ($row = $labdestid_result->fetch_assoc()){
    $labdest_id = $row['labdest_id'];
} else {
    echo("There is no lab destination with that name in our system. Please check the spelling or add the lab destination with the Add Lab Destination form.");
    exit(1);
}

// Getting date of order
$orderdate = date('Y-m-d');

// Getting everything ready to be sent

$lab_order_text1 = <<<PRESCRIPTIONTEXT
<div id="pdf_text">
<h3>Lab Order</h3>
<p>$labdest</p>
<h4>Patient: $firstname $lastname   $DOB  Sex: $sex</h4>
<p>$address_street
$address_city $address_state, $address_zip</p>
<p>Ordering Doctor: $doctor_name</p>
<p>Providers to CC: $providers_to_cc</p> 

PRESCRIPTIONTEXT;

$lab_order_text2 = '<p>Labs: ';
foreach ($all_labs as $x => $val){ 
    if ($x > 0){
        $lab_order_text2 = $lab_order_text2 . ", " . $val;
    } else {
        $lab_order_text2 = $lab_order_text2 . $val;
    }
}
$lab_order_text2 = $lab_order_text2 . "</p>";
 
$lab_order_text3 = <<<LABORDERTEXT
<p>Diagnosis: $diagnosis</p>
</div>
LABORDERTEXT;

$lab_order_text = $lab_order_text1 . $lab_order_text2 . $lab_order_text3;
echo($lab_order_text);

$laborder_pdf_link = <<<PRESCRIPTIONLINK
<script>
function createPDF(){
    var element = document.getElementById('pdf_text');
    var opt = {
        margin: 1,
        filename: 'lab_order.pdf',
        html2canvas:  { scale: 2, height: 500, width: 550, scrollX: 0, scrollY: 0},
        jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
    }
    html2pdf().set(opt).from(element).save();
}
</script>
<button onclick='createPDF()'>Create PDF</button>
PRESCRIPTIONLINK;

//<p>Quantity Per Dose: $qtyperdose -- Frequency: $frequency per day  Duration: $duration days </p>
echo "<br><br>" . $laborder_pdf_link;

// Adding the data into the Prescriptions table
$scrip_database = <<<LABDATABASE
INSERT INTO LabOrders (patient_id, doctor_id, labdest_id, cc_recipients, diagnosis, orderdate)
VALUES ("$patient_id", "$user_id", "$labdest_id", "$providers_to_cc", "$diagnosis", "$orderdate");
LABDATABASE;
//##testdata
//echo("---Here is the laborders statement: " . $scrip_database . "   ----");

if ($conn->query($scrip_database) === TRUE){
    // If lab order data was inserted into LabOrders correctly, grab the laborder_id that the database assigns
    $laborderid_sql1 = <<<SELECTLABORDER
    SELECT laborder_id FROM LabOrders WHERE patient_id='$patient_id' AND doctor_id='$user_id' AND diagnosis='$diagnosis' AND orderdate='$orderdate';
    SELECTLABORDER;
    $laborderid_sql2 = <<<SELECTLABORDER2
    SELECT laborder_id FROM LabOrders WHERE laborder_id = (SELECT MAX(laborder_id) FROM LabOrders WHERE patient_id='$patient_id');
    SELECTLABORDER2;
    $laborderid_result = $conn->query($laborderid_sql2);
    if ($row = $laborderid_result->fetch_assoc()){
        $laborder_id = $row["laborder_id"];
        //echo("laborderid = " . $laborder_id);
        foreach($lab_ids as $x => $val){
            $order_into_table = <<<ORDERINTOTABLE
            INSERT INTO OrderedLabs (laborder_id, lab_id) VALUES ('$laborder_id', '$val');
            ORDERINTOTABLE;
            //##testdata
            //echo(" ordertable statement: " . $order_into_table);
            if ($conn->query($order_into_table) === TRUE){
                // Things went well
                //echo("The data was inserted into the database correctly. All is well!");
            } else {
                // Things broke
                //echo("The data was not inserted into the database correctly. Please contact an administrator.");
            }
        }
    }
}
$conn->close();
?>



