<?php
    include_once 'dbConnection.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Bootstrap CSS -->
        <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
        crossorigin="anonymous"
        />

        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Patient</title>
        <script src="./patient.js" defer></script>
        <link rel="stylesheet" href="./style.css" />
    </head>
    <body>
        <header id='patientHeader'>
            <?php
            //SQL
            $sql = "SELECT Patient.first_name, Patient.last_name, Patient.DOB, Patient.sex, Patient.preferred
            FROM Patient
            WHERE Patient.patient_id = ?";
            //Prepare statment
            $stmt = $conn->prepare($sql);
            //Bind ? with the POST variable from the prvious page 
            $patient_id = $POST['patient_id'] ?? 4; //TODO remove after testing
            $stmt->bind_param("i", $patient_id);
            //Execute and get resutls from database
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_row();
            $patient_firstName = $row[0];
            $patient_lastName = $row[1];
            $patient_DOB = date_create($row[2]);
            $now = new DateTime("now");
            $patient_DOBFormatted = date_format($patient_DOB,"m/d/Y");
            $patientYears = $now->diff($patient_DOB);
            $patient_sex = $row[3];
            $patient_preferred = $row[4];
            echo "<p>$patient_firstName</p>
            <p>$patient_lastName</p>
            <p>pref: $patient_preferred</p>
            <p>DOB: $patient_DOBFormatted</p>
            <p>$patientYears->y" . "y</p>
            <p>$patient_sex</p>";
            ?>
        </header>
        <div class="patientBody">
            <section class="patientSideMenu">
                <!-- Medication List -->
                <button
                class="btn btn-primary patientSideMenuBtn"
                type="button"
                data-button-name='medicationList'
                aria-expanded="false"
                aria-controls="medicationListBox">
                    Medication List
                </button>
                <div
                class="collapse card card-body patientMenuBox hideContent medicationList"
                id="medicationListBox">
                    <div class="medicationList patientMenuItem">
                        <?php
                            $patient_id = $_POST['patient_id'] ?? 4;
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
                                        <p>$brand_name -- $generic_name<br>$dosage<br>$status_str</p>
                                        MEDINFO;
                                        echo($medication_info);
                                    }
                                }
                            } else {
                                echo('Unable to retrieve medication list for this user.');
                            }
                        ?>
                    </div>
                </div>
                <!-- Review Of Systems -->
                <button
                class="btn btn-primary patientSideMenuBtn"
                type="button"
                data-button-name='reviewOfSystems'
                aria-expanded="false"
                aria-controls="reviewOfSystemsBox">
                    Review Of Systems
                </button>
                <div
                class="collapse card card-body patientMenuBox hideContent reviewOfSystems"
                id="reviewOfSystemsBox">
                    <div class="reviewOfSystems patientMenuItem">
                        Copy of review of systems form answers completed earlier
                    </div>
                </div>
                <!-- Lab Results -->
                <button
                class="btn btn-primary patientSideMenuBtn"
                type="button"
                data-button-name='labResults'
                aria-expanded="false"
                aria-controls="labResultsBox">
                    Lab Results
                </button>
                <div class="collapse hideContent patientMenuBox" id="labResultsBox">
                    <div class="labResults card card-body patientMenuItem">
                        Lab Results
                        <?php
                            $note_id = $_POST['note_id'] ?? 1;
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
                    </div>
                </div>
                <!-- Family History -->
                <button
                class="btn btn-primary patientSideMenuBtn"
                type="button"
                data-button-name='personalHistory'
                aria-expanded="false"
                aria-controls="personalHistoryBox">
                    Family History
                </button>
                <div class="collapse hideContent patientMenuBox" id="personalHistoryBox">
                    <div class="personalHistory card card-body patientMenuItem">
                        Personal History
                    </div>
                </div>

                <!-- OrderPharmacy -->
                <button
                class="btn btn-primary patientSideMenuBtn"
                type="button"
                data-button-name='orderPharmacy'
                aria-expanded="false"
                aria-controls="orderPharmacyBox">
                    Order Pharmacy
                </button>
                <div class="collapse hideContent patientMenuBox" id="orderPharmacyBox">
                    <div class="orderPharmacy card card-body patientMenuItem">

                        <!-- PHARMACY GROUP! Pharmacy order form goes here ---------------------------------------------------->
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
                                <input type="text" list="dosage_nums" id="dosage_num" name="dosage_num" size="10" required>
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
                                <input type="text" list="dosagetypes" id="dosage_type" value="Tablet" name="dosage_type">
                                <datalist id="dosagetypes">
                                    <option value="Tablet">
                                    <option value="Capsule">
                                    <option value="Chewable"> 
                                    <option value="Liquid">  
                                    <option value="Other">  
                                </datalist>
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
                                <input type="number" id="quantity" name="quantity" max="100"> <!-- Default needs to be calculated from qtyperdose * frequency * duration -->
                                <label for="refills">Refills: </label>
                                <input type="number" id="refills" name="refills" value="1" max="10">
                            </fieldset>

                            <label for="usage_info">Usage Info and General Notes: </label>
                            <br>
                            <textarea rows="10" cols="40" id="usage_info" name="usage_info"></textarea>   
                            <br>
                            <input type="submit" value="Submit"> 
                            <iframe name="prescriptionlinkdisplay"></iframe>
                        </form>
                    </div>
                </div>

                <!-- Order Lab -->
                <button
                class="btn btn-primary patientSideMenuBtn"
                type="button"
                data-button-name='orderLab'
                aria-expanded="false"
                aria-controls="orderLabBox">
                    Order Lab
                </button>
                <div class="collapse hideContent patientMenuBox" id="orderLabBox">
                    <div class="orderLab card card-body patientMenuItem">

                        <!-- PHARMACY GROUP! Lab order form goes here --------------------------------------------------------->
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
                                <input type="checkbox" id="cbc" name="general_labs[]" value="CBC">
                                <label for="cmp">CMP</label>
                                <input type="checkbox" id="cmp" name="general_labs[]" value="CMP">
                                <label for="tsh">TSH</label>
                                <input type="checkbox" id="tsh" name="general_labs[]" value="TSH">
                                <label for="free_t4">Free T4</label>
                                <input type="checkbox" id="free_t4" name="general_labs[]" value="Free T4">
                                <label for="hemoglobin_a1c">Hemoglobin A1C</label>
                                <input type="checkbox" id="hemoglobin_a1c" name="general_labs[]" value="Hemoglobin A1C">
                                <label for="lipids">Lipids</label>
                                <input type="checkbox" id="lipids" name="general_labs[]" value="Lipids">
                                <label for="ferritin">Ferritin</label>
                                <input type="checkbox" id="ferritin" name="general_labs[]" value="Ferritin">
                                <label for="iron_sat">Iron Sat</label>
                                <input type="checkbox" id="iron_sat" name="general_labs[]" value="Iron Sat">
                                <label for="magnesium">Magnesium</label>
                                <input type="checkbox" id="magnesium" name="general_labs[]" value="Magnesium">
                                <label for="crp">CRP</label>
                                <input type="checkbox" id="crp" name="general_labs[]" value="CRP">
                                <label for="prolactin">Prolactin</label>
                                <input type="checkbox" id="prolactin" name="general_labs[]" value="Prolactin">
                                <label for="copper">Copper</label>
                                <input type="checkbox" id="copper" name="general_labs[]" value="Copper">
                                <label for="zinc">Zinc</label>
                                <input type="checkbox" id="zinc" name="general_labs[]" value="Zinc">
                                <label for="ekg">EKG</label>
                                <input type="checkbox" id="ekg" name="general_labs[]" value="EKG">
                                <br>
                                <fieldset id="vitaminlabs">
                                    <legend>Vitamin Labs</legend>
                                    <label for="vitamin_d">Vitamin D</label>
                                    <input type="checkbox" id="vitamin_d" name="vitamin_labs[]" value="Vitamin D">
                                    <label for="vitamin_b12">Vitamin B12</label>
                                    <input type="checkbox" id="vitamin_b12" name="vitamin_labs[]" value="Vitamin B12">
                                    <label for="vitamin_b1">Vitamin B1</label>
                                    <input type="checkbox" id="vitamin_b1" name="vitamin_labs[]" value="Vitamin B1">
                                    <label for="vitamin_b2">Vitamin B2</label>
                                    <input type="checkbox" id="vitamin_b2" name="vitamin_labs[]" value="Vitamin B2">
                                </fieldset>
                                <fieldset>
                                    <legend>STI Tests</legend>
                                    <label for="lab4">Gonorrhea</label>
                                    <input type="checkbox" id="gonorrhea" name="sti_tests[]" value="Gonorrhea">
                                    <label for="lab4">Chlamydia</label>
                                    <input type="checkbox" id="chlamydia" name="sti_tests[]" value="Chlamydia">
                                    <label for="lab4">HIV</label>
                                    <input type="checkbox" id="hiv" name="sti_tests[]" value="HIV">
                                    <label for="lab4">Syphilis</label>
                                    <input type="checkbox" id="syphilis" name="sti_tests[]" value="Syphilis">
                                </fieldset>
                                <label for="pregnancy">Pregnancy</label>
                                <input type="checkbox" id="pregnancy" name="general_labs[]" value="Pregnancy">
                            </fieldset>
                            <label for="diagnosis">Diagnosis:</label>
                            <input type="text" id="diagnosis" name="diagnosis" required>
                            <br>
                            <input type="submit" value="Submit">
                            <iframe name="laborderlinkdisplay"></iframe>
                        </form>
                    </div>
                </div>
                <!-- Note History -->
                <a
                class="btn btn-primary patientSideMenuLink"
                href="noteHistory.php">
                    Note History
                </a>
            </section>

            <!-- Current patient Note -->
            <section class="patientNote">
                <div class="card" id='patientFormCard'>
                    <div class="card-body">
                        <h3 class="card-title">10/27/22 - Today</h3>
                        <form id='patientNoteForm'>
                            <?php
                                $sql = "SELECT Note.appointment_id, Note.cc, Note.hist_illness, Note.ros_id, Note.med_profile_id, Note.social_hist, Note.med_hist, Note.psych_hist, Note.assessment, Note.plan, Note.laborder_id, Note.labdest_id, Note.demographics, Note.comments
                                FROM Note
                                WHERE Note.patient_id = ?";
                                //Prepare statment
                                $stmt = $conn->prepare($sql);
                                //Bind ? with the POST variable from the prvious page 
                                $stmt->bind_param("i", $patient_id); //$_POST['patient_id']
                                //Execute and get resutls from database
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $row = $result->fetch_row();
                            ?>
                            <!-- Demographics  -->
                            <div class="mb-3 formField" id="demographicsContainer">
                                <label
                                for="demographics"
                                id="demographicsLabel">Demographics</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="demographics"
                                name="demographics"
                                ><?php
                                    echo $row['12'];
                                ?>
                                </textarea>
                            </div>
                            <!-- Chief Complaint -->
                            <div class="mb-3 formField" id="chiefComplaintContainer">
                                <label
                                for="chiefComplaint"
                                id="chiefComplaintLabel">Chief Complaint</label>
                                <textarea
                                rows="1"
                                class="form-control"
                                id="chiefComplaint"
                                name="chiefComplaint"
                                ><?php
                                    echo $row['1'];
                                ?></textarea>
                            </div>
                            <!-- History Of Illness -->
                            <div class="mb-3 formField" id="histOfIllnessContainer">
                                <label
                                for="histOfIllness"
                                id="histOfIllnessLabel"
                                >History Of Present Illness</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="histOfIllness"
                                name="histOfIllness"
                                ><?php
                                    echo $row['2'];
                                ?></textarea
                                >
                            </div>
                            <!-- Review Of Symptoms -->
                            <div class="mb-3 formField" id="reviewOfSymptomsContainer">
                                <label
                                for="reviewOfSymptoms"
                                id="reviewOfSymptomsLabel">Reivew Of Symptoms</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="reviewOfSymptoms"
                                name="reviewOfSymptoms"
                                ><?php
                                    echo "How should ROS be displayed?";
                                ?></textarea>
                            </div>
                            <!-- Social -->
                            <div class="mb-3 formField" id="socialContainer">
                                <label for="social" id="socialLabel">Social</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="social"
                                name="social"><?php
                                    echo $row['5'];
                                ?></textarea>
                            </div>
                            <!-- Substance History -->
                            <div class="mb-3 formField" id="substanceHistContainer">
                                <label for="substanceHist" id="substanceHistLabel">Substance History</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="substanceHist"
                                name="substanceHist"><?php
                                    echo "Need to create substance field in database";
                                ?></textarea>
                            </div>
                            <!-- Psychological History -->
                            <div class="mb-3 formField" id="psychHistContainer">
                                <label for="social" id="psychHistLabel"
                                >Psychological History</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="psychHist"
                                name="psychHist"
                                ><?php
                                    echo $row['7'];
                                ?></textarea>
                            </div>
                            <!-- Medical History -->
                            <div class="mb-3 formField" id="medicalHistContainer">
                                <label for="social" id="medicalHistLabel"
                                >Medical History</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="medicalHist"
                                name="medicalHist"
                                ><?php
                                    echo $row['6'];
                                ?></textarea>
                            </div>
                            <!-- Family History -->
                            <div class="mb-3 formField" id="familyHistContainer">
                                <label for="social" id="familyHistLabel"
                                >Family History</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="familyHist"
                                name="familyHist"
                                ><?php
                                    echo $row['12'];
                                ?></textarea>
                            </div>
                            <!-- Assessment/Formulation -->
                            <div class="mb-3 formField" id="assessmentContainer">
                                <label for="assessment" id="assessmentLabel"
                                >Assessment/Formulation</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="assessment"
                                name="assessment"><?php
                                    echo $row['8'];
                                ?></textarea>
                            </div>
                            <!-- Treatment Plan -->
                            <div class="mb-3 formField" id="treatmentPlanContainer">
                                <label
                                for="treatmentPlan"
                                id="treatmentPlanLabel"
                                >Treatment Plan</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="treatmentPlan"
                                name="treatmenPlan"><?php
                                    echo $row['9'];
                                ?></textarea>
                            </div>
                            <!-- General Comments -->
                            <div class="mb-3 formField" id="generalCommentsContainer">
                                <label
                                for="generalComments"
                                id="generalCommentsLabel"
                                >General Comments</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="generalComments"
                                name="generalComments"
                                ><?php
                                    echo $row['13'];
                                ?></textarea>
                            </div>
                            <!-- Topics Discussed -->
                            <div class="mb-3 formField" id="topicsContainer">
                                <label for="topics" id="topicsLabel">Topics Discussed With Patient</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="topics"
                                name="topics">Need to add this to database</textarea>
                            </div>
                            <!-- Save Note -->
                            <button type="submit" class="btn btn-primary" id='patientFormSubmit'>Save Note</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>

        <!-- Bootstrap JS -->
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous">
        </script>
    </body>
</html>
