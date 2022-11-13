<?php 
include_once 'dbConnection.php';

$lab_name = $_POST['lab_name'];
$enter_results = $_POST['enter_results'];
$laborder_id = $_POST['laborder_id'];

// Get lab id for lab name 
$labidforname_sql = <<<LABIDFORNAME
SELECT lab_id from LabList WHERE lab_name = "$lab_name";
LABIDFORNAME;
$labidforname_result = $conn->query($labidforname_sql);
if ($labidforname_row = $labidforname_result->fetch_assoc()){
    $lab_id = $labidforname_row['lab_id'];
    $insert_lab_results_sql = <<<INS_LAB_RES
    UPDATE OrderedLabs SET results = "$enter_results" WHERE laborder_id = '$laborder_id' AND lab_id = '$lab_id';
    INS_LAB_RES;
    if($conn->query($insert_lab_results_sql) === TRUE){
        echo("The lab results were updated successfully.");
    } else {
        echo("The lab results were not updated successfully");
    }
}
?>

