<?php
//#$medicationlist

$patient_id = $_POST['patient_id'] ?? 1;
//$patient_id = 1;
$sql = <<<SCRIP_LIST_FOR_PATIENT
SELECT medication_id, dosage, status FROM MedicationList WHERE patient_id = '$patient_id';
SCRIP_LIST_FOR_PATIENT;
// Grabbing patient med info from medication list
$medlist_result = $conn->query($sql);
if ($medlist_result->num_rows > 0){
    while($row = $medlist_result->fetch_assoc()){
        $medication_id = $row['medication_id']; 
        $dosage = $row['dosage'];
        $status = $row['status'];
        //echo("medication_id: " . $medication_id . " dosage: " . $dosage);
        $status_str = '';
        if ($status == 1){
            $status_str = 'Taking';
        } else if ($status == 0){
            $status_str = 'Not Taking';
        }
        $medname_sql = <<<MEDNAME
        SELECT medication_name, generic_name FROM DrugList WHERE medication_id = '$medication_id';
        MEDNAME;
        // Grabbing medication name from DrugList
        $medname_result = $conn->query($medname_sql);
        if ($row = $medname_result->fetch_assoc()){
            $brand_name = $row['medication_name'];
            $generic_name = $row['generic_name'];
            //echo("<br>brand_name: " . $brand_name . " generic_name: " . $generic_name);
            $medication_info = <<<MEDINFO
            <p class="medListItem">$brand_name -- $generic_name<br>$dosage -- $status_str</p>
            MEDINFO;
            echo($medication_info);
        } else {
            echo ('Unable to grab medication name from DrugList for specified medication ID.');
        }
    }
} else {
    echo("This user's medication list is empty.");
}
?>