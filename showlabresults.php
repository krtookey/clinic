<?php
//#$labresults
//$note_id = $_POST['note_id'] ?? 1;
$patient_id = $_POST['patient_id'] ?? 1;
/*$laborderid_sql = <<<LAB_IDS_FOR_PATIENT
SELECT laborder_id FROM Note WHERE note_id = '$note_id';
LAB_IDS_FOR_PATIENT;*/
$laborderid_sql2 = <<<LAB_IDS_FOR_PATIENT
SELECT laborder_id FROM Note WHERE patient_id = '$patient_id' AND appointment_id = '$appointment_id';
LAB_IDS_FOR_PATIENT;
// Grabbing laborder_id and lab_id from current note
$laborderid_result = $conn->query($laborderid_sql2);
if ($laborderid_row = $laborderid_result->fetch_assoc()){
    $laborder_id = $laborderid_row['laborder_id']; 
    //echo("Laborder_id: " . $laborder_id);
    $labdetails_sql = <<<RESULTS
    SELECT lab_id, results, urgent FROM OrderedLabs WHERE laborder_id = '$laborder_id';
    RESULTS;
    // Grabbing lab id and results for specific labs based on laborder_id of current note
    $labdetails_result = $conn->query($labdetails_sql);
    if ($labdetails_result->num_rows > 0){
        while($labdetails_row = $labdetails_result->fetch_assoc()){
            $lab_id = $labdetails_row['lab_id'];
            $results = $labdetails_row['results'] ?? "No Results";
            $urgent = $labdetails_row['urgent'] ?? 0;
            $lab_name_sql = <<<LABNAME
            SELECT lab_name from LabList WHERE lab_id = '$lab_id';
            LABNAME;  
            $labname_result = $conn->query($lab_name_sql);
            if ($labname_row = $labname_result->fetch_assoc()){
                /*
                if ($results == null){
                    $results = "No results.";
                }
                */
                $lab_name = $labname_row['lab_name'] ?? "Unknown Name";
                $results_info = <<<RESULTSINFO
                <p><u>$lab_name</u><br>$results</p>
                RESULTSINFO;
                if ($urgent == 1){
                    echo("<span style='color:red;'>" . $results_info . "</span>");
                } else {
                echo($results_info);
                }
            } 
        }
        
            
    } else {
        echo('Unable to retrieve lab results for this user.');
    }
} else {
    echo('Unable to retrieve lab results for this user.');
}
?>