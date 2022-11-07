<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Profile</title>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <?php
        //Variables stored in POST buffer.
        $patient_id = $POST['patient_id'] ?? '';
        $preferred = $_POST['preferred'] ?? '';
        $dobirth = $_POST['dob'] ?? '';
        $age = $_POST['age'] ?? '';
        $fname = $_POST['firstname'] ?? '';
        $lname = $_POST['lastname'] ?? '';
        $sex = $_POST['sex'] ?? '';
        $gender = $_POST['gender'] ?? '';
        //$appointment = $_POST['appointment'] ?? '';
        $appointment = 3; //for testing

        //Populate Header.
        echo "  <header>
                <p>$preferred</p>
                <p>$dobirth</p>
                <p>Age: $age</p>
                <p>$fname</p>
                <p>$lname</p>
                <p>$sex</p>
                <p>$gender</p>
                </header>";
    ?>

    <?php

        //Connect to database.

        $dbServerName = "localhost"; 
        $dbUsername = "root"; 
        $dbPassword = ""; 
        $dbName = "Clinic";
                    
        $db = new mysqli($dbServerName, $dbUsername, $dbPassword,$dbName);
                    
        if ($db -> connect_errno > 0){
            echo "<p>Error: could not connect to database. <br> </p>";
            echo "<pre> Error Number: " .$db -> errno. "\n";
            echo "Error: "  .$db -> error. "\n <pre><br>\n";
            exit;
        }

        //Medical Profile.

        if(!isset($_POST['profileSave']) || $_POST['profileSave'] != 'Save'){
            $_POST['profileSave'] = '';
        }
        if(isset($_POST['mpweight']) && $_POST['mpweight'] != ''){
            $weight = $_POST['mpweight'];
        }        
        if(isset($_POST['mpheight']) && $_POST['mpheight'] != ''){
            $height = $_POST['mpheight'];
        }
        if(isset($_POST['mpbmi']) && $_POST['mpbmi'] != ''){
            $bmi = $_POST['mpbmi'];
        }
        if(isset($_POST['mpbloodp']) && $_POST['mpbloodp'] != ''){
            $bloodp = $_POST['mpbloodp'];
        }
        if(isset($_POST['mppulse']) && $_POST['mppulse'] != ''){
            $pulse = $_POST['mppulse'];
        }
        if(isset($_POST['mppulseox']) && $_POST['mppulseox'] != ''){
            $pulseox = $_POST['mppulseox'];
        }

        //See if medical profile form has been submitted.
        if(isset($_POST['profileSave']) || $_POST['profileSave'] == 'Save'){

            $weight = $_POST['mpweight'] ?? '';
            $height = $_POST['mpheight'] ?? '';
            $bmi = $_POST['mpbmi'] ?? '';
            $bloodp = $_POST['mpbloodp'] ?? '';
            $pulse = $_POST['mppulse'] ?? '';
            $pulseox = $_POST['mppulseox'] ?? '';
            $medProfileID = $_POST['medProfileID'] ?? 0;
            $num = 0;   //Counts the return to see if the appointment_id has already been asigned a Medical Profile.
                
            //See if a Medical Profile already exists for a given appointment 
            $qstr = "SELECT appointment_id FROM MedicalProfile WHERE appointment_id = $appointment ";
            $qselect = $db->prepare($qstr);
            $qselect->execute();
            $qselect->bind_result($appoint_id);
            $qselect->store_result();
                
            while($qselect->fetch()){
                $num = $num + 1;
            }
                                
            $qselect->free_result();

            //Insert Medical Profile data, for a new appointment.
            if($num === 0){
                $qstr = "INSERT INTO MedicalProfile (bmi, p_weight, height, blood_pressure, pulse, pulse_ox, appointment_id) VALUES (?, ?, ?, ?, ?, ?, $appointment ) ";
                $qinsert = $db->prepare($qstr);
                if(!$qinsert){
                    echo "<p>Error: could not execute query. <br> </p>";
                    echo "<pre> Error Number: " .$db -> errno. "\n";
                    echo "Error: "  .$db -> error. "\n <pre><br>\n";
                    exit;
                }
                $qinsert->bind_param("dddsii", $bmi, $weight, $height, $bloodp, $pulse, $pulseox);
                $qinsert->execute();
                $qinsert->store_result();
                $qinsert->free_result();

                //Get new med_profile_id.
                $qstr = "SELECT med_profile_id FROM MedicalProfile WHERE appointment_id = $appointment ";
                $qselect = $db->prepare($qstr);
                $qselect->execute();
                $qselect->bind_result($med_profile_id);
                $qselect->store_result();

                while($qselect->fetch()){
                    //Debug: echo "New ID: $med_profile_id";
                    $_POST['medProfileID'] = $med_profile_id;
                }
                
                $qselect->free_result();

                //Debug: echo "<pre>"; print_r($_POST); echo "</pre>";
                
                //Updating an existing Medical Profile data.
                } elseif ($num === 1) {
                    $qstr = "UPDATE MedicalProfile 
                             SET bmi = ?, p_weight = ?, height = ?, blood_pressure = ?, pulse = ?, pulse_ox = ?, appointment_id = $appointment 
                             WHERE appointment_id = $appointment ";
                    $qupdate = $db->prepare($qstr);
                    if(!$qupdate){
                        echo "<p>Error: could not execute query. <br> </p>";
                        echo "<pre> Error Number: " .$db -> errno. "\n";
                        echo "Error: "  .$db -> error. "\n <pre><br>\n";
                        exit;
                    }
                    $qupdate->bind_param("dddsii", $bmi, $weight, $height, $bloodp, $pulse, $pulseox);
                    $qupdate->execute();
                    $qupdate->store_result();
                    $qupdate->free_result();
                }
        } //End of Medical Profile query statement.
            
    ?>
    <div class="placeholder"></div>
    <div class="whiteCard">
        <h2>Medical Profile</h2> <br>
        <form method="post" action="./medicalProfile.php" id="profileForm">
            <div id="profileGrid">
                <div class="profileItem">
                    <label>Weight:</label>
                    <input type="text" name="mpweight" id="mpwight" value=<?php echo $weight; ?>>
                </div>
                <div class="profileItem">
                    <label>Height:</label>
                    <input type="text" name="mpheight" id="mpheight" value=<?php echo $height; ?>>
                </div>
                <div class="profileItem">
                    <label>BMI:</label>
                    <input type="text" name="mpbmi" id="mpbmi" value=<?php echo $bmi; ?>>
                </div>
                <div class="profileItem">
                    <label>Blood Pressure:</label> 
                    <input type="text" name="mpbloodp" id="mpbloodp" value=<?php echo $bloodp; ?>>
                </div>
                <div class="profileItem">
                    <label>Pulse:</label>
                    <input type="text" name="mppulse" id="mppulse" value=<?php echo $pulse; ?>>
                </div>
                <div class="profileItem">
                    <label>Pulse OX:</label>
                    <input type="text" name="mppulseox" id="mppulseox" value=<?php echo $pulseox; ?>>
                </div>
            </div>
            <div class="saveButton">
                <input type="submit" value="Save" name="profileSave" id="profileSave" >
            </div>
                <input type="hidden" name="medProfileID">
                <?php
                    echo "<input type='hidden' name='patient_id' value='$patient_id'>";
                    echo "<input type='hidden' name='preferred' value='$preferred'>";
                    echo "<input type='hidden' name='dob' value='$dobirth'>";
                    echo "<input type='hidden' name='age' value='$age'>";
                    echo "<input type='hidden' name='firstname' value='$fname'>";
                    echo "<input type='hidden' name='lastname' value='$lname'>";
                    echo "<input type='hidden' name='sex' value='$sex'>";
                    echo "<input type='hidden' name='gender' value='$gender'>";
                ?>
        </form>
    </div>
    <div class="whiteCard">
        <h2>Review of Systems</h2> <br>
        <div class="commentBox">
            <label>Comments:</label> <br>
            <textarea></textarea>
        </div> <br> <br>
        <form id="reviewGrid">
            <div class="rosGroup">
                <div class="reviewItem">
                    <input type="checkbox" value="rashes">
                    <label>rashes</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>itching</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>hair/nails</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>headaches</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>head injury</label>
                </div>
                <br>
            </div>
            <div class="rosGroup">
                <p>Nose/Sinus</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>nose bleeds</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>stuffiness</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>frequent colds</label>
                </div>
                <br>
            </div>
            <div class="rosGroup">
                <p>Ears</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>hearing</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>ear pain</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label> ear discharge</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>ringing</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>dizziness</label>
                </div>
                <br>
            </div>
            <div class="rosGroup">
                <p>Eyes</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>glasses</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>change vision</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>eye pain</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>double vision</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>light flashes</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>glaucoma</label>
                </div>
                <div class="reviewItem">
                    <input type="text">
                    <label>date of last eye exam</label>
                </div>
                <br>
            </div>
            <div class="rosGroup">
                <p>Allergies</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>hives</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>swelling of lips or tongue</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>hay fever</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>asthma</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>eczema</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>sensitive to drugs, food, pollen, or dander</label>
                </div>
                <br>
            </div>
            <div class="rosGroup">
                <p>Psychiatric</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>anxiety</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>depression</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>memory</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>unusual problem</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>sleep</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>psychiatrist</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>mood</label>
                </div>
                <br>
            </div>    
            <div class="rosGroup">
                <p>Mouth</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>bleeding gums</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>sore tongue</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>sore throat</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>hoarseness</label>
                </div>
                <br>
            </div>
            <div class="rosGroup">
                <p>Neck</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>lumps</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>swollen glands</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>goiter</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>stiffness</label>
                </div>
                <br>
            </div>
            <div class="rosGroup">
                <p>Breasts</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>lumps</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>breast pain</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>nipple discharge</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>BSE</label>
                </div>
                <br>
            </div>
            <div class="rosGroup">
                <p>Circulation</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>leg cramps</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>varicose veins</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>clots in veins</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>muscle pain</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>muscle swelling</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>muscle stiffness</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>joint motion</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>broken bone</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>sprain</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>arthritis</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>gout</label>
                </div>
                <br>
            </div>
            <div class="rosGroup">
                <p>Neurological</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>seizures</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>fainting</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>paralysis</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>weakness</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>muscle size</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>muscle spasm</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>tremor</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>involuntary movements</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>incoordination</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>numbness</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>pins and needles</label>
                </div>
                <br>
            </div>
            <div class="rosGroup">
                <p>Endocrine</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>growth</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>appetite</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>thirst</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>increased urination</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>thyroid</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>head cold</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>sweating</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>diabetes</label>
                </div>
                <br>
            </div>
            <div class="rosGroup">
                <p>Digestive</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>appetite</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>swallowing</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>nausea</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>heartburn</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>vomiting</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>vomiting blood</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>constipation</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>diarrhea</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>bowels</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>abdominal pain</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>burping</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>farting</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>yellow skin</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>food intolerance</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>rectal bleeding</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>urination</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>urination pain</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>frequent urination</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>urgent urination</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>incontinence</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>dribble</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>urine stream</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>bloody urine</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>uti stones</label>
                </div>
                <br>
            </div>
            <div class="rosGroup">
                <p>Cardiovascular/Respiratory</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>shortness of breath</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>cough</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>phlem</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>wheezing</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>bloody cough</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>chest pain</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>fever</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>night sweats</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>swollen hands</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>blue toes</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>high blood pressure</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>skipping heart</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>heart murmur</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>HX of heart medication</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>bronchitis</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>rheumatic heart disease</label>
                </div>
                <br>
            </div>
            <div class="rosGroup">
                <p>Hematologic</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>anemia</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>easy bruising</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>transfusions</label>
                </div>
                <br>
            </div>
            <div></div>
            <div></div>
            <div class="saveButton">
                <input type="submit" value="Save" id="rosSave">
            </div>
        </form>
    </div>
    <footer>
        <div>
            <a href="./index.php">Home</a>
            <a href="./patient.php">Patient Note</a>
        </div>
    </footer>
    <?php
        $db->close();
    ?>
    <script type="text/javascript">
        //Temporary appointment_id for testing.
        //let appointment = 4;

        //Ajax call for saving the Medical Profile form. Not Working.
        function medProfileSave(){
            let weight = document.getElementById('mpweight').value;
            let height = document.getElementById('mpheight').value;
            let bmi = documnet.getElementById('mpbmi').value;
            let bloodp = document.getElementById('mpbloodp').value;
            let pulse = document.getElementById('mppulse').value;
            let pulseox = document.getElementById('mppulseox').value;

            $.ajax({
                type: 'post',
                url:  './medProfileSave.php',
                data: {
                    'appointment':appointment,
                    'weight' :weight,
                    'height' :height,
                    'bmi' :bmi,
                    'bloodPressure' :bloodp,
                    'pulse' :pulse,
                    'pulseox' :pulseox
                },
                cache: false,
                success: function(msg) {
                    alert('Medical Profile Saved');
                }               
            });
            return false;
        }
    </script>
</body>
</html>
