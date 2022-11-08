<?php
// Assigning form items to PHP variables that we can use 
//include 'pharma_lab_forms.php';
$addscript = <<<ADDSCRIPT
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="es6-promise.auto.min.js"></script>
<script src="jspdf.min.js"></script>
<script src="html2canvas.min.js"></script>
<script src="html2pdf.min.js"></script>
ADDSCRIPT;
echo($addscript);


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
$all_labs = array_merge($general_labs, $vitamin_labs, $sti_tests);


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

    // Grabbing patient address data from patient address_id
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

$sql = "SELECT user_name FROM Users WHERE user_id='" . $doctor_id . "';";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$doctor_name = $row["user_name"];

// Grabbing lab_id for lab_name
$lab_ids = array();
foreach($all_labs as $x => $val){
    echo(" Value: " . $val . " ");
    $sql = "SELECT lab_id FROM LabList WHERE lab_name='" . $val . "';";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    echo("Row: ". $row['lab_id'] . "   "); 
    if ($row != null){
        $lab_ids[] = $row['lab_id'];
    }
}

// Getting labdest_id for LabDest

$labdestid_sql = "SELECT labdest_id FROM LabDest WHERE labdest_name='" . $labdest . "';";
echo('I like to move it move it: ' . $labdestid_sql . " -- ");
$labdestid_result = $conn->query($labdestid_sql);
$row = $result->fetch_assoc();

if ($result->num_rows == 1){
    $labdest_id = $row['labdest_id'];
} else if ($result->num_rows > 1){
    // How will we handle if there is more than 1 labdest with the same name?
    echo("More than 1 labdest with the same name. The id of the first row with that name will be used.");
    $labdest_id = $row['labdest_id'];
} else {
    echo("No labdest with the same name");
        // How will we handle if there is not a labdest with the name entered?
}


// Getting everything ready to be sent



// Sending the data to the labdest



$lab_order_text1 = <<<PRESCRIPTIONTEXT
<div id="pdf_text">
<p>$labdest</p>
<p><u>$firstname $lastname   $DOB  Sex: $sex</u></p>
<p>$address_street
$address_city $address_state, $address_zip</p>
<p>Ordering Doctor: $doctor_name</p>
<p>$providers_to_cc</p> 

PRESCRIPTIONTEXT;

$lab_order_text2 = '<p>';
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
    html2pdf(element);
}
</script>
<button onclick='createPDF()'>Create PDF</button>
PRESCRIPTIONLINK;

//<p>Quantity Per Dose: $qtyperdose -- Frequency: $frequency per day  Duration: $duration days </p>
echo "<br><br>" . $laborder_pdf_link;

// Adding the data into the Prescriptions table
$scrip_database = <<<LABDATABASE
INSERT INTO LabOrders (patient_id, doctor_id, labdest_id, cc_recipients, diagnosis)
VALUES ('$patient_id', '$doctor_id', '$labdest_id', '$providers_to_cc', '$diagnosis');
LABDATABASE;

if ($conn->query($scrip_database) === TRUE){
    $laborderid_sql = <<<SELECTLABORDER
    SELECT laborder_id FROM LabOrders WHERE patient_id='$patient_id' AND doctor_id='$doctor_id' AND diagnosis='$diagnosis';
    SELECTLABORDER;
    $laborderid_result = $conn->query($laborderid_sql);
    $row = $laborderid_result->fetch_assoc();
    $laborder_id = $row["laborder_id"];
    foreach($lab_ids as $x => $val){
        $order_into_table = <<<ORDERINTOTABLE
        INSERT INTO OrderedLabs (laborder_id, lab_id) VALUES ('$laborder_id', '$val');
        ORDERINTOTABLE;
        if ($conn->query($order_into_table) === TRUE){
            // Things went well
        } else {
            // Things broke
        }
    }
}
$conn->close();
?>



