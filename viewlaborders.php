<?php
$patient_id = $_POST['patient_id'] ?? 1;
$getlabids = <<<GETLABIDS
SELECT laborder_id FROM LabOrders WHERE patient_id = '$patient_id';
GETLABIDS;
$getlabids_result = $conn->query($getlabids);
if ($getlabids_result->num_rows > 0){
    while($getlabids_row = $getlabids_result->fetch_assoc()){
        //echo("This is a thing for the things.");
        $laborder_id = $getlabids_row['laborder_id']; 
        $labs_ordered = array();
        $results_sql = <<<RESULTS
        SELECT lab_id, results FROM OrderedLabs WHERE laborder_id = '$laborder_id';
        RESULTS;
        // Grabbing results for specific lab based on laborder_id and lab_id of current note
        $labid_result = $conn->query($results_sql);
        if ($labid_result->num_rows > 0){
            while($labid_row = $labid_result->fetch_assoc()){
                $lab_id = $labid_row['lab_id'];
                $results = $labid_row['results'];
                $lab_name_sql = <<<LABNAME
                SELECT lab_name from LabList WHERE lab_id = '$lab_id';
                LABNAME;  
                $labname_result = $conn->query($lab_name_sql);
                if ($labname_row = $labname_result->fetch_assoc()){
                    $lab_name = $labname_row['lab_name']; 
                    $labs_ordered[] = $lab_name;
                    $results_info = <<<RESULTSINFO
                    <br><br><p><u>$lab_name</u><br>$results</p>
                    RESULTSINFO;
                    //echo($results_info);
                }   
            }        
        } else {
            echo('Unable to find lab ids and results of labs for this lab order. Please contact an administrator');
        }

        // Grab all info about laborder from laborder_id
        $laborderinfo_sql = <<<LABORDERINFO
        SELECT labdest_id, doctor_id, cc_recipients, diagnosis, orderdate FROM LabOrders WHERE laborder_id = '$laborder_id'; 
        LABORDERINFO;
        $laborderinfo_results = $conn->query($laborderinfo_sql);
        $laborderinfo_row = $laborderinfo_results->fetch_assoc(); 
        // Assign variables from laborderinfo
        $labdest_id = $laborderinfo_row['labdest_id'];
        $doctor_id = $laborderinfo_row['doctor_id'];
        $cc_recipients = $laborderinfo_row['cc_recipients'];
        $diagnosis = $laborderinfo_row['diagnosis'];
        $orderdate = $laborderinfo_row['orderdate'];


        // Get labdest_name from labdest_id
        $labdestname_sql = <<<LABDESTNAME
        SELECT labdest_name from LabDest WHERE labdest_id = '$labdest_id';
        LABDESTNAME;
        $labdestname_results = $conn->query($labdestname_sql);
        $labdestname_row = $labdestname_results->fetch_assoc(); 
        $labdest_name = $labdestname_row['labdest_name'];


        // Get doctor name from doctor_id
        $doctorname_sql = <<<DOCTORNAME
        SELECT user_name from Users WHERE user_id = '$doctor_id';
        DOCTORNAME;
        $doctorname_results = $conn->query($doctorname_sql);
        $doctorname_row = $doctorname_results->fetch_assoc(); 
        $doctor_name = $doctorname_row['user_name'];


        $lab_order_text1 = <<<PRESCRIPTIONTEXT
        <div class="laborder_detail_box" style="border-style:solid; border-radius:25px; padding:1em;">
        <p>$labdest_name</p>
        <p>$orderdate</p>
        <p>Ordering Doctor: $doctor_name</p>
        <p>$cc_recipients</p> 

        PRESCRIPTIONTEXT;

        $lab_order_text2 = '<p>';
        foreach ($labs_ordered as $x => $val){ 
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
    }
} else {
    echo('Unable to find lab orders for this patient. Please contact an administrator');
    exit(1);
}
?>