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
        <form action="prescription_orders.php" method="post" target="prescriptionlinkdisplay" id="prescriptionform">
            <script type="text/javascript">
                function calcquantity(){
                    qtyperdose = eval(document.getElementById("qtyperdose").value);
                    frequency = eval(document.getElementById("frequency").value);
                    duration = document.getElementById("duration").value;
                    total = qtyperdose * frequency * duration;
                    //total = math.ceil(total);
                    console.log(total);
                    document.getElementById("quantity").value = total;
                }
            </script>
            <div id="patient_info">
                <?php 
                    $user_id = $POST['user_id'] ?? 1;
                    $patient_id = $POST['patient_id'] ?? 1;
                    $idfields = <<<IDFIELDS
                    <input type="text" id="patient_id" name="patient_id" value="$patient_id" hidden>
                    <input type="text" id="user_id" name="user_id" value="$user_id" hidden>
                    IDFIELDS;
                    echo($idfields);
                    //Need to get patient name and DOB from medical records views
                
                    $sql = "SELECT first_name, last_name, DOB FROM Patient WHERE patient_id ='" . $patient_id . "';";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0){
                        $row = $result->fetch_assoc();
                        $patient_name = $row["first_name"] . ' ' . $row["last_name"];
                        $patient_dob = $row["DOB"];
                    } else {
                        echo "0 results";
                    }
                    
                    //$patient_name = "John Doe"; // PLACEHOLDER
                    //$patient_dob = "10/12/1996"; // PLACEHOLDER
                    
                    echo("<b>". $patient_name . "</b>  <b>  " . $patient_dob . "</b>");
                    echo("<br><br>");
                    echo('<label for="pharmacy">Pharmacy: </label>');
                    
                    // Get pharmacy_id for patient
                    $sql = "SELECT pharmacy_id FROM Patient where patient_id = '" . $patient_id . "';";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0){
                        $row = $result->fetch_assoc();
                        $patient_pharmacy_id = $row["pharmacy_id"]; 
                        // Get pharmacy_name for pharmacy_id
                        $sql = "SELECT pharmacy_name FROM Pharmacy where pharmacy_id = '" . $patient_pharmacy_id . "';";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0){
                            $row = $result->fetch_assoc();
                            $pharmacy_name = $row["pharmacy_name"]; 
                            $pharmacyinput = <<<PHARM_INPUT
                            <input type="text" id="pharmacy" name="pharmacy" list="pharmacy_list" value="$pharmacy_name" required>
                            PHARM_INPUT;
                            echo $pharmacyinput;
                        }
                    }

                ?>
            </div>
            <datalist id="pharmacy_list">
                <?php  
                    $sql = "SELECT pharmacy_name FROM Pharmacy";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            $name = $row["pharmacy_name"];
                            $text = <<<TEXT
                            <option value="$name">
                            TEXT;
                            echo $text;
                        }
                    } else {
                        echo "0 results";
                    }
                    //$conn->close();
                ?> <!-- This will be populated by the items in the SQL table Pharmacy, pharmacy_name-->
            </datalist>
            <fieldset id="prescription_inputs">
                <legend>Prescription:</legend>
                <label for="drugname">Drug Name: </label>
                <input type="text" list="druglist" id="drugname" name="drugname" required>
                <datalist id="druglist">
                    <?php 
                        $sql = "SELECT medication_name, generic_name FROM DrugList;";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){
                                //This is what will create the checkboxes and labels, when it is set up correctly-->
                                $gen_name = $row["generic_name"];
                                $brand_name = $row["medication_name"];
                                $text = <<<TEXT
                                <option value="$brand_name">
                                <option value="$gen_name">
                                TEXT;
                                echo $text;
                            }
                        } else {
                            echo "0 results";
                        }
                        //$conn->close();
                    ?>
                </datalist>
                <label for="dosage">Dosage:</label>
                <input type="number" list="dosage_nums" id="dosage_num" name="dosage_num" size="10" max='10000' required>
                <datalist id="dosage_nums">
                    <option value="1">   
                    <option value="2"> 
                    <option value="5">
                    <option value="10">   
                    <option value="20">   
                    <option value="30">  
                    <option value="50">  
                    <option value="100">  
                    <option value="200">  
                </datalist>
                <label for="unit">Unit:</label>
                <select id="unit" name="unit">
                    <option value="mg" selected>mg</option>   
                    <option value="ml">ml</option>   
                    <option value="cc">cc</option>
                </select>      
                <br>
                <label for="dosage_type">Type:</label>
                <select type="text" list="dosagetypes" id="dosage_type" name="dosage_type">
                    <option value="Tablet" selected>Tablet</option>
                    <option value="Capsule">Capsule</option>
                    <option value="Chewable">Chewable</option>
                    <option value="Liquid">Liquid</option>
                    <option value="Other">Other</option>
                </select>
                <label for="route">Route:</label>
                <select id="route" name="route">
                    <option value="Oral" selected>Oral</option>
                    <option value="Topical">Topical</option>
                    <option value="IM">IM</option>
                    <option value="IV">IV</option>
                    <option value="SubQ">Sub Q</option>
                </select>
                <br><br>
                <label for="qtyperdose">Qty per Dose</label>
                <select id="qtyperdose" name="qtyperdose" onchange="calcquantity()">
                    <option value="0.25">1/4</option>
                    <option value="0.5">1/2</option>
                    <option value="1" selected>1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                </select>
                <label for="frequency">Frequency Of Dose</label>
                <select id="frequency" name="frequency" onchange="calcquantity()">
                    <option value="2">twice per day</option>
                    <option value="3">3 times per day</option>
                    <option value="1" selected>once per day</option>
                    <option value="1/7">once per week</option>
                    <option value="1/2">every other day</option>
                    <option value="1/30">per 30 days</option>
                </select>
                <label for="duration">Duration</label>
                <select id="duration" name="duration" onchange="calcquantity()">
                    <option value="7">7 days</option>
                    <option value="14" selected>14 days</option>
                    <option value="21">21 days</option>
                    <option value="28">28 days</option>
                    <option value="30">30 days</option>
                </select>
                <label for="total_quantity">Quantity: </label> 
                <input type="number" id="quantity" name="quantity" value="14" max="100"> <!-- Default needs to be calculated from qtyperdose * frequency * duration -->
                <label for="refills">Refills: </label>
                <input type="number" id="refills" name="refills" value="1" max="10">
            </fieldset>

            <label for="usage_info">Usage Info and General Notes: </label>
            <br>
            <textarea rows="10" cols="40" id="usage_info" name="usage_info" maxlength="4900"></textarea>   
            <br>
            <input type="submit" value="Submit"> 
            <iframe name="prescriptionlinkdisplay"></iframe>
        </form>

        <br>
        <br>

        <form action="lab_orders.php" method="post" target="laborderlinkdisplay" id="labform">
            <div id="patient_info">
                <?php 
                    $user_id = $POST['user_id'] ?? 1;
                    $patient_id = $POST['patient_id'] ?? 1;
                    $idfields = <<<IDFIELDS
                    <input type="text" id="patient_id" name="patient_id" value="$patient_id" hidden>
                    <input type="text" id="user_id" name="user_id" value="$user_id" hidden>
                    IDFIELDS;
                    echo($idfields);
                    //Need to get patient name and DOB from medical records views
                    //$patient_name = "John Doe"; // PLACEHOLDER
                    //$patient_dob = "10/12/1996"; // PLACEHOLDER
                    echo("<b>". $patient_name . "</b>  <b>  " . $patient_dob . "</b>");
                ?>
            </div>
            <br>
            <label for="labdest">Lab Destination:</label> <!-- Should automatically be filled by patient default lab dest-->
            <?php
                // Get labdest_id for patient
                $sql = "SELECT labdest_id FROM Patient where patient_id = '" . $patient_id . "';";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $patient_labdest_id = $row["labdest_id"]; 
                    // Get labdest_name for labdest_id
                    $sql = "SELECT labdest_name FROM LabDest where labdest_id = '" . $patient_labdest_id . "';";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0){
                        $row = $result->fetch_assoc();
                        $labdest_name = $row["labdest_name"]; 
                        $labdestinput = <<<LABDEST_INPUT
                        <input type="text" id="labdest" name="labdest" list="labdestlist" value="$labdest_name" required>
                        LABDEST_INPUT;
                        echo $labdestinput;
                    }
                }
            ?>
            <datalist id="labdestlist">
                <?php 
                //--Look to this for help: https://www.w3schools.com/php/php_mysql_select.asp
                    /*
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
                    */

                    $sql = "SELECT labdest_name FROM LabDest";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            $name = $row["labdest_name"];
                            $text = <<<TEXT
                            <option value="$name">
                            TEXT;
                            echo $text;
                        }
                    } else {
                        echo "0 results";
                    }
                    //$conn->close();
                ?>
            </datalist>
            <br>
            <label for="providers_to_cc">Providers to CC:</label>
            <input type="text" id="providers_to_cc" name="providers_to_cc">
            <fieldset id="lab_checkboxes"> <!-- Figure out how to automatically generate this based upon LabList, with the value being the lab_id and id, name, and the label being the lab_name-->
                <legend>Labs</legend>
                <label for="cbc">CBC</label>
                <input type="checkbox" id="cbc" name="labs[]" value="CBC">
                <label for="cmp">CMP</label>
                <input type="checkbox" id="cmp" name="labs[]" value="CMP">
                <label for="tsh">TSH</label>
                <input type="checkbox" id="tsh" name="labs[]" value="TSH">
                <label for="free_t4">Free T4</label>
                <input type="checkbox" id="free_t4" name="labs[]" value="Free T4">
                <label for="hemoglobin_a1c">Hemoglobin A1C</label>
                <input type="checkbox" id="hemoglobin_a1c" name="labs[]" value="Hemoglobin A1C">
                <label for="lipids">Lipids</label>
                <input type="checkbox" id="lipids" name="labs[]" value="Lipids">
                <label for="ferritin">Ferritin</label>
                <input type="checkbox" id="ferritin" name="labs[]" value="Ferritin">
                <label for="iron_sat">Iron Sat</label>
                <input type="checkbox" id="iron_sat" name="labs[]" value="Iron Sat">
                <label for="magnesium">Magnesium</label>
                <input type="checkbox" id="magnesium" name="labs[]" value="Magnesium">
                <label for="crp">CRP</label>
                <input type="checkbox" id="crp" name="labs[]" value="CRP">
                <label for="prolactin">Prolactin</label>
                <input type="checkbox" id="prolactin" name="labs[]" value="Prolactin">
                <label for="copper">Copper</label>
                <input type="checkbox" id="copper" name="labs[]" value="Copper">
                <label for="zinc">Zinc</label>
                <input type="checkbox" id="zinc" name="labs[]" value="Zinc">
                <label for="ekg">EKG</label>
                <input type="checkbox" id="ekg" name="labs[]" value="EKG">
                <br>
                <fieldset id="vitaminlabs">
                    <legend>Vitamin Labs</legend>
                    <label for="vitamin_d">Vitamin D</label>
                    <input type="checkbox" id="vitamin_d" name="labs[]" value="Vitamin D">
                    <label for="vitamin_b12">Vitamin B12</label>
                    <input type="checkbox" id="vitamin_b12" name="labs[]" value="Vitamin B12">
                    <label for="vitamin_b1">Vitamin B1</label>
                    <input type="checkbox" id="vitamin_b1" name="labs[]" value="Vitamin B1">
                    <label for="vitamin_b2">Vitamin B2</label>
                    <input type="checkbox" id="vitamin_b2" name="labs[]" value="Vitamin B2">
                </fieldset>
                <fieldset>
                    <legend>STI Tests</legend>
                    <label for="lab4">Gonorrhea</label>
                    <input type="checkbox" id="gonorrhea" name="labs[]" value="Gonorrhea">
                    <label for="lab4">Chlamydia</label>
                    <input type="checkbox" id="chlamydia" name="labs[]" value="Chlamydia">
                    <label for="lab4">HIV</label>
                    <input type="checkbox" id="hiv" name="labs[]" value="HIV">
                    <label for="lab4">Syphilis</label>
                    <input type="checkbox" id="syphilis" name="labs[]" value="Syphilis">
                </fieldset>
                <label for="pregnancy">Pregnancy</label>
                <input type="checkbox" id="pregnancy" name="labs[]" value="Pregnancy">
            </fieldset>
            <label for="diagnosis">Diagnosis:</label>
            <input type="text" id="diagnosis" name="diagnosis" required>
            <br>
            <input type="submit" value="Submit">
            <iframe name="laborderlinkdisplay"></iframe>
        </form>


        <form action="addnewmeds.php" method="post" id="adddrugtodatabase">
            <b>Add Drug to Database</b> 
            <br>
            <label for="brandname">Brand Name:</label>
            <input type="text" id="brandname" name="brandname">
            <label for="genericname">Generic Name:</label>
            <input type="text" id="genericname" name="genericname">
            <input type="submit" value="Submit">
        </form>
        
        <form action="addnewpharmacy.php" method="post" target="" id="addpharmacyform">
            <b>Add Pharmacy</b>
            <br>
            <label for="pharmacy_name">Pharmacy Name:</label>
            <input type="text" id="pharmacy_name" name="pharmacy_name">
            <fieldset id="pharmacy_address">
                <legend>Address</legend>
                <label for="pharmacy_street">Street:</label>
                <input type="text" id="pharmacy_street" name="pharmacy_street">
                <label for="pharmacy_city">City:</label>
                <input type="text" id="pharmacy_city" name="pharmacy_city">
                <label for="pharmacy_state">State:</label>
                <input type="text" id="pharmacy_state" name="pharmacy_state">
                <label for="pharmacy_zip">ZIP:</label>
                <input type="number" id="pharmacy_zip" name="pharmacy_zip">
            </fieldset>    
            <label for="pharmacy_phone">Phone #:</label>
            <input type="tel" id="pharmacy_phone" name="pharmacy_phone">
            <br>
            <label for="pharmacy_email">Email:</label>
            <input type="email" id="pharmacy_email" name="pharmacy_email">
            <input type="submit" value="Submit">
        </form>


        <form action="addnewlabdest.php" method="post" target="" id="addlabdestform">
            <b>Add Lab Destination</b>
            <br>
            <label for="labdest_name">Lab Name:</label>
            <input type="text" id="labdest_name" name="labdest_name">
            <fieldset id="labdest_address">
                <legend>Address</legend>
                <label for="labdest_street">Street:</label>
                <input type="text" id="labdest_street" name="labdest_street">
                <label for="labdest_city">City:</label>
                <input type="text" id="labdest_city" name="labdest_city">
                <label for="labdest_state">State:</label>
                <input type="text" id="labdest_state" name="labdest_state">
                <label for="labdest_zip">ZIP:</label>
                <input type="number" id="labdest_zip" name="labdest_zip">
            </fieldset>    
            <label for="labdest_phone">Phone #:</label>
            <input type="tel" id="labdest_phone" name="labdest_phone">
            <br>
            <label for="labdest_email">Email:</label>
            <input type="email" id="labdest_email" name="labdest_email">
            <input type="submit" value="Submit">
        </form>


        <form action="insertlabresults.php" method="post" target="" id="insertlabresultsform">
            <b>Insert Lab Results</b>
            <br>
            <label for="">Lab </label>

            <?php
                $patient_id = $_POST['patient_id'] ?? 1;
                $sql = <<<LAB_IDS_FOR_PATIENT
                SELECT laborder_id FROM Note WHERE note_id = '$note_id';
                LAB_IDS_FOR_PATIENT;
                // Grabbing laborder_id and lab_id from current note
                $result = $conn->query($sql);
                if ($row = $result->fetch_assoc()){
                    $laborder_id = $row['laborder_id']; 
                    //echo("Laborder_id: " . $laborder_id);
                    $results_sql = <<<RESULTS
                    SELECT lab_id, results FROM OrderedLabs WHERE laborder_id = '$laborder_id';
                    RESULTS;
                    // Grabbing results for specific lab based on laborder_id and lab_id of current note
                    $result = $conn->query($results_sql);
                    if ($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            $lab_id = $row['lab_id'];
                            $results = $row['results'];
                            $lab_name_sql = <<<LABNAME
                            SELECT lab_name from LabList WHERE lab_id = '$lab_id';
                            LABNAME;  
                            $result = $conn->query($lab_name_sql);
                            if ($row = $result->fetch_assoc()){
                                if ($results == null){
                                    $results = "No results.";
                                }
                                $lab_name = $row['lab_name']; 
                                $results_info = <<<RESULTSINFO
                                <br><br><p><u>$lab_name</u><br>$results</p>
                                RESULTSINFO;
                                echo($results_info);
                            }   
                        }
                        
                            
                    }
                } else {
                    echo('Unable to retrieve medication list for this user.');
                }
            ?>

        </form>

        <?php // This is the code for viewing prescription and lab orders in patient.php. Copy this into there when it is ready.
            echo('Here is where the code to show prescriptions starts.');
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
                $row = $drname_result->fetch_assoc();
                $doctor_name = $row["user_name"];

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
                <div class="prescription_detail_box" style="margin:2%; border-style:solid; border-radius:25px; padding:1em; width:40%;">
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
            if ($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $laborder_id = $row['laborder_id']; 
                    $labs_ordered = array();
                    $results_sql = <<<RESULTS
                    SELECT lab_id, results FROM OrderedLabs WHERE laborder_id = '$laborder_id';
                    RESULTS;
                    // Grabbing results for specific lab based on laborder_id and lab_id of current note
                    $result = $conn->query($results_sql);
                    if ($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            $lab_id = $row['lab_id'];
                            $results = $row['results'];
                            $lab_name_sql = <<<LABNAME
                            SELECT lab_name from LabList WHERE lab_id = '$lab_id';
                            LABNAME;  
                            $result = $conn->query($lab_name_sql);
                            if ($row = $result->fetch_assoc()){
                                if ($results == null){
                                    $results = "No results.";
                                }
                                $lab_name = $row['lab_name']; 
                                $labs_ordered[] = $labname;
                                $results_info = <<<RESULTSINFO
                                <br><br><p><u>$lab_name</u><br>$results</p>
                                RESULTSINFO;
                                echo($results_info);
                            }   
                        }        
                    }
                    $lab_order_text1 = <<<PRESCRIPTIONTEXT
                    <div id="pdf_text">
                    <h3>Lab Order</h3>
                    <p>$labdest</p>
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
                }
            }

            


        ?>

    </body>
</html>