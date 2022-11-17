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
    <iframe name="insertlabresultsframe" class="results_iframe"></iframe>
</form>