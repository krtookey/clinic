<?php 
include_once 'dbConnection.php';

$lab_name = $_POST['lab_name'] ?? "Unknown";
if (isset($_POST["urgent"])){
    $urgent = 1;
} else {
    $urgent = 0;
}
$enter_results = $_POST['enter_results'] ?? "None Entered";
if($enter_results == "None Entered"){
    echo("Results to enter is empty. Please enter text of results.");
    exit(1);
}
$laborder_id = $_POST['laborder_id'] ?? 1;

// Get lab id for lab name 
$labidforname_sql = <<<LABIDFORNAME
SELECT lab_id from LabList WHERE lab_name = "$lab_name";
LABIDFORNAME;
$labidforname_result = $conn->query($labidforname_sql);
if ($labidforname_row = $labidforname_result->fetch_assoc()){
    $lab_id = $labidforname_row['lab_id'];
    $insert_lab_results_sql = <<<INS_LAB_RES
    UPDATE OrderedLabs SET results = "$enter_results", urgent = "$urgent" WHERE laborder_id = '$laborder_id' AND lab_id = '$lab_id';
    INS_LAB_RES;
    if($conn->query($insert_lab_results_sql) === TRUE){
        echo("The lab results were updated successfully.");
    } else {
        echo("The lab results were not updated successfully");
    }
} else {
    echo("The lab name selected has no valid lab id. Please contact an administrator.");
}
?>

