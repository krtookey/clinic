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
            <p>John Smith</p>
            <p>pref: Johnny</p>
            <p>DOB: 7/23/1990</p>
            <p>32y</p>
            <p></p>
            <p>Male</p>
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
                        Information about patient medication list
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
                        <script>
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
                                    //Need to get patient name and DOB from medical records views
                                /*
                                    $servername = "localhost";
                                    $username = "username";
                                    $password = "password";
                                    $dbname = "myDB";
                                    // Create connection
                                    $conn = new mysqli($servername, $username, $password, $dbname);
                                    // Check connection
                                    if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                    }
                                
                                    $sql = "SELECT first_name, last_name, DOB FROM Patient WHERE patient_id ='" . $patient_id . "';";
                                    $result = $conn->query($sql);
                                
                                    if ($result->num_rows > 0){
                                        $row = $result->fetch_assoc()
                                        $patient_name = $row["first_name"] . ' ' . $row["last_name"];
                                        $patient_dob = $row["DOB"];
                                    } else {
                                        echo "0 results";
                                    }
                                    $conn->close();
                                    */
                                    $patient_name = "John Doe"; // PLACEHOLDER
                                    $patient_dob = "10/12/1996"; // PLACEHOLDER
                                    
                                    echo("<b>". $patient_name . "</b>  <b>  " . $patient_dob . "</b>");
                                    echo("<br><br>");
                                    echo('<label for="pharmacy">Pharmacy: </label>');
                                    /*
                                    // Get pharmacy_id for patient
                                    $sql = "SELECT pharmacy_id FROM Patient where patient_id = '" . $patient_id . "';";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0){
                                    $row = $result->fetch_assoc();
                                    $patient_pharmacy_id = $row["pharmacy_id"]; 

                                    // Get pharmacy_id for pharmacy_id
                                    $sql = "SELECT pharmacy_name FROM Pharmacy where pharmacy_id = '" . $patient_pharmacy_id . "';";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0){
                                    $row = $result->fetch_assoc();
                                    $pharmacy_name = $row["pharmacy_name"]; 

                                    //$conn->close();
                                    */
                                    $patient_pharmacy = "Nick's Funky Pharmacy"; // PLACEHOLDER
                                    $pharmacyinput = <<<PHARM_INPUT
                                    <input type="text" id="pharmacy" name="pharmacy" list="pharmacy_list" default="$patient_pharmacy" required>
                                    PHARM_INPUT;
                                    echo $pharmacyinput;
                                    

                                ?>
                            </div>
                            <datalist id="pharmacy_list">
                                <?php  
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
                                    //Need to get patient name and DOB from medical records views
                                    $patient_name = "John Doe"; // PLACEHOLDER
                                    $patient_dob = "10/12/1996"; // PLACEHOLDER
                                    echo("<b>". $patient_name . "</b>  <b>  " . $patient_dob . "</b>");
                                ?>
                            </div>
                            <br>
                            <!--<label for="doctorname">Doctor Name:</label> 
                            <input type="text" id="doctorname" required> <!-- Doctor name needs to be automatically grabbed from who is logged in, we can probably get rid of this field -->
                            <label for="labdest">Lab Destination:</label> <!-- Should automatically be filled by patient default lab dest-->
                            <input type="text" id="labdest" name="labdest" list="labdestlist" required> <!-- This will be populated by the items in the SQL table LabDest, labdest_name-->
                            <datalist id="labdestlist">
                                <?php 
                                //--Look to this for help: https://www.w3schools.com/php/php_mysql_select.asp
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
                                <input type="checkbox" id="cbc" name="cbc" value="cbc">
                                <label for="cmp">CMP</label>
                                <input type="checkbox" id="cmp" name="cmp" value="cmp">
                                <label for="tsh">TSH</label>
                                <input type="checkbox" id="tsh" name="tsh" value="tsh">
                                <label for="free_t4">Free T4</label>
                                <input type="checkbox" id="free_t4" name="free_t4" value="free_t4">
                                <label for="hemoglobin_a1c">Hemoglobin A1C</label>
                                <input type="checkbox" id="hemoglobin_a1c" name="hemoglobin_a1c" value="hemoglobin_a1c">
                                <label for="lipids">Lipids</label>
                                <input type="checkbox" id="lipids" name="lipids" value="lipids">
                                <label for="ferritin">Ferritin</label>
                                <input type="checkbox" id="ferritin" name="ferritin" value="ferritin">
                                <label for="iron_sat">Iron Sat</label>
                                <input type="checkbox" id="iron_sat" name="iron_sat" value="iron_sat">
                                <label for="magnesium">Magnesium</label>
                                <input type="checkbox" id="magnesium" name="magnesium" value="magnesium">
                                <label for="crp">CRP</label>
                                <input type="checkbox" id="crp" name="crp" value="crp">
                                <label for="prolactin">Prolactin</label>
                                <input type="checkbox" id="prolactin" name="prolactin" value="prolactin">
                                <label for="copper">Copper</label>
                                <input type="checkbox" id="copper" name="copper" value="copper">
                                <label for="zinc">Zinc</label>
                                <input type="checkbox" id="zinc" name="zinc" value="zinc">
                                <label for="ekg">EKG</label>
                                <input type="checkbox" id="ekg" name="ekg" value="ekg">
                                <br>
                                <fieldset id="vitaminlabs">
                                    <legend>Vitamin Labs</legend>
                                    <label for="vitamin_d">Vitamin D</label>
                                    <input type="checkbox" id="vitamin_d" name="vitamin_d" value="vitamin_d">
                                    <label for="vitamin_b12">Vitamin B12</label>
                                    <input type="checkbox" id="vitamin_b12" name="vitamin_b12" value="vitamin_b12">
                                    <label for="vitamin_b1">Vitamin B1</label>
                                    <input type="checkbox" id="vitamin_b1" name="vitamin_b1" value="vitamin_b1">
                                    <label for="vitamin_b2">Vitamin B2</label>
                                    <input type="checkbox" id="vitamin_b2" name="vitamin_b2" value="vitamin_b2">
                                </fieldset>
                                <fieldset>
                                    <legend>STI Tests</legend>
                                    <label for="lab4">Gonorrhea</label>
                                    <input type="checkbox" id="gonorrhea" name="gonorrhea" value="gonorrhea">
                                    <label for="lab4">Chlamydia</label>
                                    <input type="checkbox" id="chlamydia" name="chlamydia" value="chlamydia">
                                    <label for="lab4">HIV</label>
                                    <input type="checkbox" id="hiv" name="hiv" value="hiv">
                                    <label for="lab4">Syphilis</label>
                                    <input type="checkbox" id="syphilis" name="syphilis" value="syphilis">
                                </fieldset>
                                <label for="pregnancy">Pregnancy</label>
                                <input type="checkbox" id="pregnancy" name="pregnancy" value="pregnancy">
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
                <button
                class="btn btn-primary patientSideMenuBtn"
                type="button"
                data-button-name='noteHistory'
                aria-expanded="false"
                aria-controls="noteHistoryBox">
                    Note History
                </button>
                <div class="collapse hideContent patientMenuBox" id="noteHistoryBox">
                    <div class="noteHistory card card-body patientMenuItem">
                    </div>
                </div>
            </section>

            <!-- Current patient Note -->
            <section class="patientNote">
                <div class="card" id='patientFormCard'>
                    <div class="card-body">
                        <h3 class="card-title">10/27/22 - Today</h3>
                        <form id='patientNoteForm'>
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
                                ></textarea>
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
                                >*Text from the previous note*  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor inc</textarea>
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
                                >*Text from the previous note*  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. In est ante in nibh mauris cursus. Ac orci phasellus egestas tellus</textarea
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
                                >rci phasellus egestas tellus rutrum tellus pellentesque eu. Pellentesque pulvinar pellentesque habitant</textarea>
                            </div>
                            <!-- Social -->
                            <div class="mb-3 formField" id="socialContainer">
                                <label for="social" id="socialLabel">Social</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="social"
                                name="social">justo eget magna fermentum iaculis eu non. Magna etiam tempor </textarea>
                            </div>
                            <!-- Substance History -->
                            <div class="mb-3 formField" id="substanceHistContainer">
                                <label for="substanceHist" id="substanceHistLabel">Substance History</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="substanceHist"
                                name="substanceHist"> magna fermentum iaculis eu non. Magna etiam tempor </textarea>
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
                                >ng elit, sed do eiusmod tempor incidid</textarea>
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
                                >sellus egestas tellus rutrum tellus pellentesque eu. Pellentesque pulvinar pellentesque habitant morbi</textarea>
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
                                name="familyHist">
                                utrum tellus pellentesque eu.</textarea>
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
                                name="assessment">estas tellus rutrum tellus pellentesque e</textarea>
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
                                name="treatmenPlan">od tempor incididunt ut labore et dolore magna aliqua.</textarea>
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
                                name="generalComments">
                                *Text from the previous note*</textarea>
                            </div>
                            <!-- Topics Discussed -->
                            <div class="mb-3 formField" id="topicsContainer">
                                <label for="topics" id="topicsLabel">Topics Discussed With Patient</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="topics"
                                name="topics">
                                For insurence record</textarea>
                            </div>
                            <!-- Checkbox -->
                            <div class="mb-3 form-check formField" id="checkboxConatiner">
                                <input
                                type="checkbox"
                                class="form-check-input"
                                id="exampleCheck1"/>
                                <label class="form-check-label" for="exampleCheck1">Incase we need a checkbox</label>
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
