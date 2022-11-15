<!DOCTYPE html>
<html>
    <head>
    <?php 
    include_once 'dbConnection.php';
    ?>
        <style>
            body {
                max-width: 45%;
                margin: 2%;
            }
            form input, label {
                margin:0.2em;
            }
            form {
                margin: 2%;
                border-style:solid;
                border-radius:25px;
                padding:1em;
            }
            #lab_checkboxes input{
                padding-right: 0.2em;
            }
            #lab_checkboxes label{
                padding-left: 0.2em;
            }
            fieldset{
                margin: 
            }
            #patient_info b{
                margin:1.5em; 
                font-size:1.2em;
            }
        </style>
        <script>src="./jquery-3.6.1.js"</script>
    </head>
    <body>
        <?php
        include_once "prescriptionform.php"
        ?>

        <br>
        <br>

        <?php
        include_once "laborderform.php"
        ?>

        <?php
        include_once "adddrugform.php"
        ?>

        <?php
        include_once "addpharmacyform.php"
        ?>


        <?php
        include_once "addlabdestform.php"
        ?>
        

        <form action="insertlabresults.php" method="post" target="insertlabresultsframe" id="insertlabresultsform">
            <b>Insert Lab Results</b>
            <br>
            <label for="lab_name">Lab Name</label> 
            <?php
                $patient_id = $_POST['patient_id'] ?? 1;
                $note_id = $_POST['note_id'] ?? 1;
                $sql = <<<LAB_IDS_FOR_PATIENT
                SELECT laborder_id FROM Note WHERE note_id = '$note_id';
                LAB_IDS_FOR_PATIENT;
                // Grabbing laborder_id and lab_id from current note
                $laborderid_result = $conn->query($sql);
                if ($laborderid_row = $laborderid_result->fetch_assoc()){
                    $laborder_id = $laborderid_row['laborder_id']; 
                    $laborder_id_field = <<<LABORDERIDFIELD
                    <input type="text" id="laborder_id" name="laborder_id" value="$laborder_id" hidden>
                    <select id="lab_name" name="lab_name">
                    LABORDERIDFIELD;
                    echo($laborder_id_field);
                    //echo("Laborder_id: " . $laborder_id);
                    $results_sql = <<<RESULTS
                    SELECT lab_id, results FROM OrderedLabs WHERE laborder_id = '$laborder_id';
                    RESULTS;
                    // Grabbing results for specific lab based on laborder_id and lab_id of current note
                    $labid_result = $conn->query($results_sql);
                    if ($labid_result->num_rows > 0){
                        while($labid_row = $labid_result->fetch_assoc()){
                            $lab_id = $labid_row['lab_id'];
                            //$results = $row['results'];
                            //echo("Looking at " . $lab_id . " --- ");
                            $lab_name_sql = <<<LABNAME
                            SELECT lab_name from LabList WHERE lab_id = '$lab_id';
                            LABNAME;  
                            $labname_result = $conn->query($lab_name_sql);
                            if ($labname_row = $labname_result->fetch_assoc()){
                                $lab_name = $labname_row['lab_name']; 
                                $lab_name_option = <<<LABNAMEOPTION
                                <option value="$lab_name">$lab_name</option>
                                LABNAMEOPTION;
                                echo ($lab_name_option);
                                //echo($results_info);
                            }   
                        }
                        
                            
                    }
                } else {
                    echo('Unable to retrieve medication list for this user.');
                }
            ?>
            </select>
            <br>
            <label for="enter_results">Enter Results</label>
            <br>
            <textarea rows="6" cols="30" id="enter_results" name="enter_results" maxlength="4900"></textarea>   
            <input type="submit" value="Submit">
            <iframe name="insertlabresultsframe"></iframe>
        </form>

        <?php // This is the code for viewing prescription and lab orders in patient.php. Copy this into there when it is ready.
        // This code is now in patient.php
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
        
            
            
            // Grabbing laborder_ids for all lab orders made by patient
            echo('Here is where the code to show lab orders starts.');
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
            }

            


        ?>

    </body>
</html>