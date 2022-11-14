<?php
    include_once 'dbConnection.php';
?>
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
        if(isset($_POST['patient_id']) && $_POST['patient_id'] !== ''){
            $patient_id = $_POST['patient_id'];
        }
        $patient_id = $_POST['patient_id'] ?? ''; 
        if(isset($_POST['appointment_id']) && $_POST['appointment_id'] !== ''){
            $appointment_id = $_POST['appointment_id'];
        }
        $appointment_id = $_POST['appointment_id'] ?? '';
        if(isset($_POST['user_id']) && $_POST['user_id'] !== ''){
            $user_id = $_POST['user_id'];
        }
        $user_id = $_POST['user_id'] ?? '';
        $doctorPermission = 2;       //Permission Level needed to access Family History and Patient Note.
        $userPermission = 0;         //Permission Level of User.
        
        //Header.

        function getAge($date){
            $n = 0;
            $birth = new DateTime($date);
            $today = new DateTime(date("Y-m-d"));
            $diff = $today->diff($birth);
            $n = $diff->y;
            return $n;
        }

        if($patient_id !== ''){

            $qstr = "SELECT DISTINCT gender, preferred, first_name, last_name, DOB, sex FROM Patient WHERE patient_id = $patient_id ";
            $qpatient = $conn->prepare($qstr);
            if(! $qpatient){
                echo "<p>Error: could not execute query. <br> </p>";
                echo "<pre> Error Number: " .$conn -> errno. "\n";
                echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                exit;
            }
            $qpatient->execute();
            $qpatient->store_result();
            $qpatient->bind_result($gender, $preferred, $fname, $lname, $dob, $sex);
            
            echo "<header>";

            while($qpatient->fetch()){
                echo "  <p>$preferred</p>
                        <p>$dob</p>";
                
                $age = getAge($dob);

                echo "  <p>Age: $age</p>
                        <p>$fname</p>
                        <p>$lname</p>";

                if ($sex == 'M'){
                    $sex = 'Male';
                } elseif ($sex == 'F') {
                    $sex = 'Female';
                } elseif ($sex == 'O') {
                    $sex = 'Other';
                } 
                echo "<p>$sex</p>";

                if ($gender == 1){
                    $gender = 'He/Him';
                } elseif ($gender == 2) {
                    $gender = 'She/Her';
                } elseif ($gender == 3) {
                    $gender = 'They/Them';
                }   
                echo "<p>$gender</p>";
            }
            echo "</header>";
            $qpatient->free_result();
        } else {
            echo "<header></header>";
        }

        //Debug: echo "<br><br><br><br><pre>"; print_r($_POST); echo "</pre>";    

        //Get User's Permission Level.
        if ($user_id !== '' ){
            $qstr = "SELECT DISTINCT permission FROM Users WHERE user_id = $user_id ";
            //Debug: echo $qstr;
            $qselect = $conn->prepare($qstr);
            if(! $qselect){
                echo "<p>Error: could not execute query. <br> </p>";
                echo "<pre> Error Number: " .$conn -> errno. "\n";
                echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                exit;
            }
            $qselect->execute();
            $result = $qselect->get_result();
            $row = $result->fetch_row();
            $userPermission = $row[0];
            $qselect->free_result();
        }

    ?>
    <div class="placeholder"></div>
    <div class="whiteCard">
        <h2>Medical Profile</h2> <br>
        <iframe style="display: none; " name="profileFrame">
            <?php
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
                if(isset($_POST['weight_meas']) && $_POST['weight_meas'] != ''){
                    $weight_meas = $_POST['weight_meas'];
                } 
                $weight = $_POST['mpweight'] ?? '';
                $weight_meas = $_POST['weight_meas'] ?? '';
                $height = $_POST['mpheight'] ?? '';
                $bmi = $_POST['mpbmi'] ?? '';   
                $bloodp = $_POST['mpbloodp'] ?? '';
                $pulse = $_POST['mppulse'] ?? '';
                $pulseox = $_POST['mppulseox'] ?? '';  
                $medProfileID = $_POST['medProfileID'] ?? '';
                $note_id = $_POST['note'] ?? '';

                //See if medical profile form has been submitted.
                if(isset($_POST['profileSave']) && $_POST['profileSave'] == 'Save'){

                    $num = 0;   //Counts the return to see if the appointment_id has already been assigned a Medical Profile.
                        
                    //See if a Medical Profile already exists for a given appointment 
                    $qstr = "SELECT appointment_id FROM MedicalProfile WHERE appointment_id = $appointment_id ";
                    $qselect = $conn->prepare($qstr);
                    if(!$qselect){
                        echo "<p>Error: could not execute query. <br> </p>";
                        echo "<pre> Error Number: " .$conn -> errno. "\n";
                        echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                        exit;
                    }
                    $qselect->execute();
                    $qselect->store_result();
                        
                    while($qselect->fetch()){
                        $num = $num + 1;
                    }
                                        
                    $qselect->free_result();

                    //Insert Medical Profile data, for a new appointment.
                    if($num === 0){
                        $qstr = "INSERT INTO MedicalProfile (bmi, p_weight, height, blood_pressure, pulse, pulse_ox, appointment_id, weight_meas) VALUES (?, ?, ?, ?, ?, ?, $appointment_id, '$weight_meas' ) ";
                        $qinsert = $conn->prepare($qstr);
                        if(!$qinsert){
                            echo "<p>Error: could not execute query. <br> </p>";
                            echo "<pre> Error Number: " .$conn -> errno. "\n";
                            echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                            exit;
                        }
                        $qinsert->bind_param("dddsii", $bmi, $weight, $height, $bloodp, $pulse, $pulseox);
                        $qinsert->execute();
                        $qinsert->store_result();
                        $qinsert->free_result();

                        //Get new med_profile_id.
                        $qstr = "SELECT med_profile_id FROM MedicalProfile WHERE appointment_id = $appointment_id ";
                        $qselect = $conn->prepare($qstr);
                        $qselect->execute();
                        $qselect->bind_result($med_profile_id);
                        $qselect->store_result();

                        while($qselect->fetch()){
                            //Debug: echo "New ID: $med_profile_id";
                            $_POST['medProfileID'] = $med_profile_id;
                        }
                        
                        $qselect->free_result();

                        //Add med_profile_id to the Note.

                        $num_note = 0;
                        //See if a Note already exists for a given appointment. 
                        $qstr = "SELECT note_id FROM Note WHERE appointment_id = $appointment_id ";
                        $qselect = $conn->prepare($qstr);
                        if(!$qselect){
                            echo "<p>Error: could not execute query. <br> </p>";
                            echo "<pre> Error Number: " .$conn -> errno. "\n";
                            echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                            exit;
                        }
                        $qselect->execute();
                        $qselect->bind_result($note_id);
                        $qselect->store_result();
                            
                        while($qselect->fetch()){
                            $num_note = $num_note + 1;
                            $_POST['note'] = $note_id; 
                        }
                                            
                        $qselect->free_result();
                        //If Note exists, update it's med_profile_id.
                        if($num_note === 1){
                            $qstr = "UPDATE Note 
                                    SET med_profile_id = $med_profile_id 
                                    WHERE note_id = $note_id ";
                            $qupdate = $conn->prepare($qstr);
                            if(!$qupdate){
                                echo "<p>Error: could not execute query. <br> </p>";
                                echo "<pre> Error Number: " .$conn -> errno. "\n";
                                echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                                exit;
                            }
                            $qupdate->execute();
                            $qupdate->store_result();
                            $qupdate->free_result();
                        }

                    //Debug: echo "<pre>"; print_r($_POST); echo "</pre>";
                        
                    //Updating an existing Medical Profile data.
                    } elseif ($num === 1) {
                        $qstr = "UPDATE MedicalProfile 
                                SET bmi = ?, p_weight = ?, height = ?, blood_pressure = ?, pulse = ?, pulse_ox = ?, weight_meas = '$weight_meas' 
                                WHERE appointment_id = $appointment_id ";
                        $qupdate = $conn->prepare($qstr);
                        if(!$qupdate){
                        echo "<p>Error: could not execute query. <br> </p>";
                            echo "<pre> Error Number: " .$conn -> errno. "\n";
                            echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                            exit;
                        }
                        $qupdate->bind_param("dddsii", $bmi, $weight, $height, $bloodp, $pulse, $pulseox);
                        $qupdate->execute();
                        $qupdate->store_result();
                        $qupdate->free_result();
                    }
                }            
            ?>
        </iframe>
        <form method="post" action="./medicalProfile.php" id="profileForm" target="profileFrame">
            <div id="profileGrid">
                <div class="profileItem">
                    <label>Weight:</label>
                    <input type="text" name="mpweight" id="mpwight" value=<?php echo $weight; ?>>
                </div>
                <div class="profileItem">
                    <label>Weight In:</label>
                    <input type="radio" name="weight_meas" id="lb" value="lb">
                    <label for="lb">lb </label>
                    <input type="radio" name="weight_meas" id="kg" value="kg">
                    <label for="kg" id="kglabel">kg </label>
                </div>
                <div class="profileItem">
                    <label>Height:</label>
                    <input type="text" name="mpheight" id="mpheight" maxlength="5" value=<?php echo $height; ?>>
                </div>
                <div class="profileItem">
                    <label>BMI:</label>
                    <input type="text" name="mpbmi" id="mpbmi" value=<?php echo $bmi; ?>>
                </div>
                <div class="profileItem">
                    <label>Blood Pressure:</label> 
                    <input type="text" name="mpbloodp" id="mpbloodp" maxlength="10" value=<?php echo $bloodp; ?>>
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
                <input type="hidden" name="note">
                <?php
                    echo "<input type='hidden' name='patient_id' value='$patient_id'>";
                    echo "<input type='hidden' name='appointment_id' value='$appointment_id'>";
                    echo "<input type='hidden' name='user_id' value='$user_id'>";
                ?>
        </form>
    </div>
    <div class="whiteCard">
        <h2>Review of Systems</h2> <br>
        <iframe style="display: none; " name="rosFrame">
            <?php
            //Review of Systems.

            //Make sure the submitted eye exam date contains a valid date string.
            //validateDate: makes sure that a date string contains a valid date, for the given format.
            //      $date: date string being checked.
            //      $format: format for the expected date.  Default set to 'Y-m-d'.
            //      return: returns the original date string if valid, else returns a date string for 0001-01-01.
            function validateDate($date, $format = 'Y-m-d'){
                $d = DateTime::createFromFormat($format, $date);
                if ($d && $d->format($format) === $date){
                    return $date;
                } else {
                    $str = '0001-01-01';
                    return $str;
                }
            }

            $ros_count = 0;     //Number of values in $ros array.
            if(!isset($_POST['rosSave']) || $_POST['rosSave'] != 'Save'){
                $_POST['rosSave'] = '';
            }
            //Array of checked ros values.
            if(isset($_POST['ros'])){
                $ros = $_POST['ros'];
                $ros_count = count($ros);
            }
            if(isset($_POST['exam_date']) && $_POST['exam_date'] != ''){
                $exam = $_POST['exam_date'];
            }
            if(isset($_POST['ros_comment']) && $_POST['ros_comment'] != ''){
                $comment = $_POST['ros_comment'];
            }
            $ros = $_POST['ros'] ?? '';
            $comment = $_POST['ros_comment'] ?? '';
            $exam = $_POST['exam_date'] ?? '';
            $exam = validateDate($exam);

            //Debug: echo "<br><br><br><br><br> Comment: $comment";

            //See if ROS form has been submitted.
            if(isset($_POST['rosSave']) && $_POST['rosSave'] == 'Save'){

                $ros_id = $_POST['ros_id'] ?? '';
                $note_id = $_POST['note'] ?? '';
                $num = 0;  
                    
                //See if a ROS already exists for a given appointment 
                $qstr = "SELECT appointment_id FROM ReviewOfSystem WHERE appointment_id = $appointment_id ";
                $qselect = $conn->prepare($qstr);
                if(!$qselect){
                    echo "<p>Error: could not execute query. <br> </p>";
                    echo "<pre> Error Number: " .$conn -> errno. "\n";
                    echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                    exit;
                }
                $qselect->execute();
                $qselect->store_result();
                    
                while($qselect->fetch()){
                    $num = $num + 1;
                }

                $qselect->free_result();
                
                //Debug: echo "<br><br><br><br><br><br>$num";
                
                //If no previous ros, insert a new one. 
                if($num === 0){

                    $ros_str = '';
                    $ros_true = '';
                    for($i = 0; $i < $ros_count; $i++){
                        $ros_str = $ros_str.$ros[$i].', ';
                        $ros_true = $ros_true.'true, ';
                    }

                    //Debug: echo "<br><br><br><br>String: $ros_str"; echo "<br>String: $ros_true"; echo "<br>String: $exam";

                    $qstr = "INSERT INTO ReviewOfSystem ($ros_str last_eye, comments, appointment_id) VALUES ($ros_true ?, ?, $appointment_id ) ";
                    //Debug: echo "<br>$qstr";
                    $qinsert = $conn->prepare($qstr);
                    if(!$qinsert){
                        echo "<p>Error: could not execute query. <br> </p>";
                        echo "<pre> Error Number: " .$conn -> errno. "\n";
                        echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                        exit;
                    }
                    $qinsert->bind_param("ss", $exam, $comment);
                    $qinsert->execute();
                    $qinsert->store_result();
                    $qinsert->free_result();

                    //Get new ros_id.
                    $qstr = "SELECT ros_id FROM ReviewOfSystem WHERE appointment_id = $appointment_id ";
                    $qselect = $conn->prepare($qstr);
                    $qselect->execute();
                    $qselect->bind_result($ros_id);
                    $qselect->store_result();

                    while($qselect->fetch()){
                        $_POST['ros_id'] = $ros_id;
                    }
                    
                    $qselect->free_result();

                    //Add ros_id to the Note.
                    $num_note = 0;
                    //See if a Note already exists for a given appointment. 
                    $qstr = "SELECT note_id FROM Note WHERE appointment_id = $appointment_id ";
                    $qselect = $conn->prepare($qstr);
                    if(!$qselect){
                        echo "<p>Error: could not execute query. <br> </p>";
                        echo "<pre> Error Number: " .$conn -> errno. "\n";
                        echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                        exit;
                    }
                    $qselect->execute();
                    $qselect->bind_result($note_id);
                    $qselect->store_result();
                            
                    while($qselect->fetch()){
                        $num_note = $num_note + 1;
                        $_POST['note'] = $note_id; 
                    }
                                        
                    $qselect->free_result();
                    
                    //If Note exists, update it's ros_id.
                    if($num_note === 1){
                        $qstr = "UPDATE Note 
                                SET ros_id = $ros_id 
                                WHERE note_id = $note_id ";
                        $qupdate = $conn->prepare($qstr);
                        if(!$qupdate){
                            echo "<p>Error: could not execute query. <br> </p>";
                            echo "<pre> Error Number: " .$conn -> errno. "\n";
                            echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                            exit;
                        }
                        $qupdate->execute();
                        $qupdate->store_result();
                        $qupdate->free_result();
                    }


                //Debug: echo "<pre>"; print_r($_POST); echo "</pre>";
                    
                //Updating an existing ROS data.
                } elseif ($num === 1) {

                    $ros_str = '';
                    $ros_true = '';
                    for($i = 0; $i < $ros_count; $i++){
                        $ros_str = $ros_str.$ros[$i].', ';
                        $ros_true = $ros_true.'true, ';
                    }

                    $qstr = "DELETE FROM ReviewOfSystem WHERE appointment_id = $appointment_id" ;
                    $qupdate = $conn->prepare($qstr);
                    if(!$qupdate){
                    echo "<p>Error: could not execute query. <br> </p>";
                        echo "<pre> Error Number: " .$conn -> errno. "\n";
                        echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                        exit;
                    }
                    $qupdate->execute();
                    $qupdate->store_result();
                    $qupdate->free_result();
                    
                    $qstr = "INSERT INTO ReviewOfSystem ($ros_str last_eye, comments, appointment_id) VALUES ($ros_true ?, ?, $appointment_id ) ";
                    //Debug: echo "<br>$qstr";
                    $qinsert = $conn->prepare($qstr);
                    if(!$qinsert){
                        echo "<p>Error: could not execute query. <br> </p>";
                        echo "<pre> Error Number: " .$conn -> errno. "\n";
                        echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                        exit;
                    }
                    $qinsert->bind_param("ss", $exam, $comment);
                    $qinsert->execute();
                    $qinsert->store_result();
                    $qinsert->free_result();
                } 

            }   
            ?>
        </iframe>
        <form action="./medicalProfile.php" method="post" target="rosFrame">
            <div class="commentBox">
                <label>Comments:</label> <br>
                <textarea name="ros_comment" maxlength="16777214"></textarea>
            </div> <br> <br>
            <div id="reviewGrid">
                <div class="rosGroup">
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="rashes">
                        <label>rashes</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="itching">
                        <label>itching</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="hair_nails">
                        <label>hair/nails</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="headaches">
                        <label>headaches</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="head_injury">
                        <label>head injury</label>
                    </div>
                    <br>
                </div>
                <div class="rosGroup">
                    <p>Nose/Sinus</p> <br>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="nose_bld">
                        <label>nose bleeds</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="stuffiness">
                        <label>stuffiness</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="freq_colds">
                        <label>frequent colds</label>
                    </div>
                    <br>
                </div>
                <div class="rosGroup">
                    <p>Ears</p> <br>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="hearing">
                        <label>hearing</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="ear_pain">
                        <label>ear pain</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="ear_disch">
                        <label> ear discharge</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="ringing">
                        <label>ringing</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="dizziness">
                        <label>dizziness</label>
                    </div>
                    <br>
                </div>
                <div class="rosGroup">
                    <p>Eyes</p> <br>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="glasses">
                        <label>glasses</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="change_vision">
                        <label>change vision</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="eye_pain">
                        <label>eye pain</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="double_vision">
                        <label>double vision</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="flash_lgt">
                        <label>light flashes</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="glaucoma">
                        <label>glaucoma</label>
                    </div>
                    <div class="reviewItem">
                        <input type="text" name="exam_date" placeholder="YYYY-MM-DD">
                        <label>date of last eye exam</label>
                    </div>
                    <br>
                </div>
                <div class="rosGroup">
                    <p>Allergies</p> <br>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="hives">
                        <label>hives</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="swell_lip">
                        <label>swelling of lips or tongue</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="hay_fever">
                        <label>hay fever</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="asthma">
                        <label>asthma</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="eczema">
                        <label>eczema</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="sens_drg_food">
                        <label>sensitive to drugs, food, pollen, or dander</label>
                    </div>
                    <br>
                </div>
                <div class="rosGroup">
                    <p>Psychiatric</p> <br>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="anxiety">
                        <label>anxiety</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="depression">
                        <label>depression</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="memory">
                        <label>memory</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="unusual_prob">
                        <label>unusual problem</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="sleep">
                        <label>sleep</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="psychiatrist">
                        <label>psychiatrist</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="mood">
                        <label>mood</label>
                    </div>
                    <br>
                </div>    
                <div class="rosGroup">
                    <p>Mouth</p> <br>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="bld_gums">
                        <label>bleeding gums</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="sore_tongue">
                        <label>sore tongue</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="sore_throat">
                        <label>sore throat</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="hoarseness">
                        <label>hoarseness</label>
                    </div>
                    <br>
                </div>
                <div class="rosGroup">
                    <p>Neck</p> <br>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="lumps">
                        <label>lumps</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="swoll_glands">
                        <label>swollen glands</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="goiter">
                        <label>goiter</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="neck_stiffness">
                        <label>neck stiffness</label>
                    </div>
                    <br>
                </div>
                <div class="rosGroup">
                    <p>Breasts</p> <br>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="breast_lumps">
                        <label>lumps</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="breast_pain">
                        <label>breast pain</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="nipple_discharge">
                        <label>nipple discharge</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="bse">
                        <label>BSE</label>
                    </div>
                    <br>
                </div>
                <div class="rosGroup">
                    <p>Circulation</p> <br>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="leg_cramp">
                        <label>leg cramps</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="varicose_vein">
                        <label>varicose veins</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="clot_vein">
                        <label>clots in veins</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="musc_pain">
                        <label>muscle pain</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="musc_swelling">
                        <label>muscle swelling</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="musc_stiffness">
                        <label>muscle stiffness</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="joint_motion">
                        <label>joint motion</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="broken_bone">
                        <label>broken bone</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="sprains">
                        <label>sprains</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="arthritis">
                        <label>arthritis</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="gout">
                        <label>gout</label>
                    </div>
                    <br>
                </div>
                <div class="rosGroup">
                    <p>Neurological</p> <br>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="seizures">
                        <label>seizures</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="fainting">
                        <label>fainting</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="paralysis">
                        <label>paralysis</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="weakness">
                        <label>weakness</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="muscle_size">
                        <label>muscle size</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="muscle_spasm">
                        <label>muscle spasm</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="tremor">
                        <label>tremor</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="invol_move">
                        <label>involuntary movements</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="incoordination">
                        <label>incoordination</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="numbness">
                        <label>numbness</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="pins_needles">
                        <label>pins and needles</label>
                    </div>
                    <br>
                </div>
                <div class="rosGroup">
                    <p>Endocrine</p> <br>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="growth">
                        <label>growth</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="appetite">
                        <label>appetite</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="thirst">
                        <label>thirst</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="incre_urin">
                        <label>increased urination</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="thyroid">
                        <label>thyroid</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="head_cold">
                        <label>head cold</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="sweating">
                        <label>sweating</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="diabetes">
                        <label>diabetes</label>
                    </div>
                    <br>
                </div>
                <div class="rosGroup">
                    <p>Digestive</p> <br>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="appetite1">
                        <label>appetite</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="swallowing">
                        <label>swallowing</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="nausea">
                        <label>nausea</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="heartburn">
                        <label>heartburn</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="vomiting">
                        <label>vomiting</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="vomit_blood">
                        <label>vomiting blood</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="constipation">
                        <label>constipation</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="diarrhea">
                        <label>diarrhea</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="bowels">
                        <label>bowels</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="abdominal_pain">
                        <label>abdominal pain</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="burping">
                        <label>burping</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="farting">
                        <label>farting</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="yellow_skin">
                        <label>yellow skin</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="food_intol">
                        <label>food intolerance</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="rectal_bleed">
                        <label>rectal bleeding</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="unination">
                        <label>urination</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="urin_pain">
                        <label>urination pain</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="freq_urin">
                        <label>frequent urination</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="urgent_urin">
                        <label>urgent urination</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="incontinence_urin">
                        <label>incontinence</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="dribble">
                        <label>dribble</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="urin_stream">
                        <label>urine stream</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="urin_blood">
                        <label>bloody urine</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="uti_stones">
                        <label>uti stones</label>
                    </div>
                    <br>
                </div>
                <div class="rosGroup">
                    <p>Cardiovascular/Respiratory</p> <br>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="short_of_brth">
                        <label>shortness of breath</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="cough">
                        <label>cough</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="phlem">
                        <label>phlem</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="wheezing">
                        <label>wheezing</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="chough_bld">
                        <label>bloody cough</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="chest_pain">
                        <label>chest pain</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="fever">
                        <label>fever</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="night_sweats">
                        <label>night sweats</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="swell_hands">
                        <label>swollen hands</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="blue_toes">
                        <label>blue toes</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="high_blood">
                        <label>high blood pressure</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="skipping_heart">
                        <label>skipping heart</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="heart_murmur">
                        <label>heart murmur</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="hx_of_heart_med">
                        <label>HX of heart medication</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="bronchitis">
                        <label>bronchitis</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="rheumatic_heart_dis">
                        <label>rheumatic heart disease</label>
                    </div>
                    <br>
                </div>
                <div class="rosGroup">
                    <p>Hematologic</p> <br>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="anemia">
                        <label>anemia</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="bruising_bleed">
                        <label>easy bruising</label>
                    </div>
                    <div class="reviewItem">
                        <input type="checkbox" name="ros[]" value="transfusions">
                        <label>transfusions</label>
                    </div>
                    <br>
                </div>
                <div></div>
                <div></div>
                <div class="saveButton">
                    <input type="submit" value="Save" name="rosSave" id="rosSave">
                </div>
                <input type="hidden" name="ros_id">
                <input type="hidden" name="note">
                <?php
                    echo "<input type='hidden' name='patient_id' value='$patient_id'>";
                    echo "<input type='hidden' name='appointment_id' value='$appointment_id'>";
                    echo "<input type='hidden' name='user_id' value='$user_id'>";
                ?>
            </div>
        </form>
    </div>
    <?php

        //Family History.
    
        // Set user permissions to limit access to the family history.
        // Debug: echo "User Permission: $userPermission";
        if($userPermission >= $doctorPermission){
        echo "<div class='whiteCard'>
                <h2>Family History</h2> <br> 
                    <div id='familyHistory'>";
                        //fetch family history.
                        if(!isset($_POST['histEdit']) || $_POST['histEdit'] != 'Edit'){
                            $_POST['histEdit'] = '';
                        }
                        if(!isset($_POST['histSave']) || $_POST['histSave'] != 'Save'){
                            $_POST['histSave'] = '';
                        }
                        if(!isset($_POST['memberDelete']) || $_POST['memberDelete'] != 'Delete'){
                            $_POST['memberDelete'] = '';
                        }
                        if(isset($_POST['relationship']) && $_POST['relationship'] != ''){
                            $family = $_POST['relationship'];
                        }
                        if(isset($_POST['conditionBox']) && $_POST['conditionBox'] != ''){
                            $conditionBox = $_POST['conditionBox'];
                        }
                        $family = $_POST['relationship'] ?? '';
                        $conditionBox = $_POST['conditionBox'] ?? '';
                        $num = 0;                                   //Array index for $members and $hists.
                        $members = [];                              //Array of family members.
                        $hists = [];                                //Array of conditions.
                        $present = 'false';                         //If family member is already part of the family history, present == true.

                        if(isset($_POST['patient_id']) && $patient_id !== ''){

                            $qstr = "SELECT relationship, condit FROM FamilyHistory WHERE patient_id = $patient_id ";
                            $qselect = $conn->prepare($qstr);
                            if(!$qselect){
                                echo "<p>Error: could not execute query. <br> </p>";
                                echo "<pre> Error Number: " .$conn -> errno. "\n";
                                echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                                exit;
                            }
                            $qselect->execute();
                            $qselect->store_result();
                            $qselect->bind_result($relationship, $condition);
                            
                            while($qselect->fetch()){
                                echo    "<div class='familyMember'>
                                            <h3>$relationship</h3>
                                            <p>$condition</p><br>
                                        </div>";
                                $members[$num] = $relationship;
                                $hists[$num] = $condition;
                                $num = $num + 1;   
                            }
                            if(isset($_POST['histEdit']) && $_POST['histEdit'] == 'Edit'){
                            
                                $i = 0;
                                while($i < $num){
                                    if($members[$i] == $family){
                                        $conditionBox = $hists[$i];
                                    }
                                    $i = $i + 1;
                                } 
                            }
                            $qselect->free_result();

                            if(isset($_POST['histSave']) && $_POST['histSave'] == 'Save'){

                                $i = 0;
                                while($i < $num && $present != 'true'){
                                    if($members[$i] == $family){
                                        $present = 'true';
                                    } else {
                                        $present = 'false';
                                    }
                                    $i = $i + 1;
                                } 

                                if($present == 'false'){
                                    $qstr = "INSERT INTO FamilyHistory (patient_id, relationship, condit) VALUES ($patient_id, ?, ? ) ";
                                    //Debug: echo "<br>$qstr";
                                    $qinsert = $conn->prepare($qstr);
                                    if(!$qinsert){
                                        echo "<p>Error: could not execute query. <br> </p>";
                                        echo "<pre> Error Number: " .$conn -> errno. "\n";
                                        echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                                        exit;
                                    }
                                    $qinsert->bind_param("ss", $family, $conditionBox);
                                    $qinsert->execute();
                                    $qinsert->store_result();
                                    $qinsert->free_result();
                                } else {

                                    $qstr = "UPDATE FamilyHistory 
                                            SET condit = ? 
                                            WHERE relationship = '$family' AND patient_id = $patient_id ";
                                    $qupdate = $conn->prepare($qstr);
                                    if(!$qupdate){
                                        echo "<p>Error: could not execute query. <br> </p>";
                                        echo "<pre> Error Number: " .$conn -> errno. "\n";
                                        echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                                        exit;
                                    }
                                    $qupdate->bind_param("s", $conditionBox);
                                    $qupdate->execute();
                                    $qupdate->store_result();
                                    $qupdate->free_result();

                                } 
                            }
                            if(isset($_POST['memberDelete']) && $_POST['memberDelete'] == 'Delete'){
                                $present = 'false';
                                $i = 0;
                                while($i < $num && $present != 'true'){
                                    if($members[$i] == $family){
                                        $present = 'true';
                                    } else {
                                        $present = 'false';
                                    }
                                    $i = $i + 1;
                                }
                                if($present == 'true'){
                                    $qstr = "DELETE FROM FamilyHistory WHERE patient_id = $patient_id AND relationship = ? ";
                                    $qdelete = $conn->prepare($qstr);
                                    if(!$qdelete){
                                        echo "<p>Error: could not execute query. <br> </p>";
                                        echo "<pre> Error Number: " .$conn -> errno. "\n";
                                        echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                                        exit;
                                    }
                                    $qdelete->bind_param("s", $family);
                                    $qdelete->execute();
                                    $qdelete->store_result();
                                    $qdelete->free_result();
                                }
                            }     
                        }
        echo "      </div>
             <iframe style='display: none; ' name='historyFrame'>";

        echo "</iframe>";
        //Debug: echo "<pre>"; print_r($_POST); echo" </pre>";
        
        echo"
            <form action='./medicalProfile.php' method='post' >
                <div class='dropBox'>
                    <label for='famliy'>Relationship:</label>
                    <input list='family' maxlength='29' name='relationship' value='$family'>
                    <datalist name='relationship' id='family' >
                        <option value='Mother'>Mother</option>
                        <option value='Father'>Father</option>
                        <option value='Sister'>Sister</option>
                        <option value='Brother'>Brother</option>
                        <option value='Maternal Half-Sister'>Maternal Half-Sister</option>
                        <option value='Maternal Half-Brother'>Maternal Half-Brother</option>
                        <option value='Paternal Half-Sister'>Paternal Half-Sister</option>
                        <option value='Paternal Half-Brother'>Paternal Half-Brother</option>
                        <option value='Maternal Grandfather'>Maternal Grandfather</option>
                        <option value='Maternal Grandmother'>Maternal Grandmother</option>
                        <option value='Paternal Grandfather'>Paternal Grandfather</option>
                        <option value='Paternal Grandmother'>Paternal Grandmother</option>
                        <option value='Maternal Aunt'>Maternal Aunt</option>
                        <option value='Paternal Aunt'>Paternal Aunt</option>
                        <option value='Maternal Uncle'>Maternal Uncle</option>
                        <option value='Paternal Uncle'>Paternal Uncle</option>
                    </datalist>
                </div><br>
                <div class='commentBox'>
                    <label>Conditions: </label> <br>
                    <textarea name='conditionBox' maxlength='5999'> $conditionBox </textarea>
                    </div> <br>
                <div class='saveButton'> 
                    <input type='submit' value='Edit' name='histEdit' id='histEdit'>
                    <input type='submit' value='Save' name='histSave' id='histSave'>
                    <input type='submit' value='Delete' name='memberDelete' id='memberDelete'>
                </div>
                <input type='hidden' name='patient_id' value='$patient_id'>
                <input type='hidden' name='appointment_id' value='$appointment_id'>
                <input type='hidden' name='user_id' value='$user_id'>
            </form> 
        </div>";
        }
    ?>
    <footer>
        <div>
            <form action="./index.php" method="POST">
                <input type="submit" name="submitI" value="Home">
                <?php
                    //Store patient_id, appointment_id, and user_id in POST buffer.
                    echo "  <input type='hidden' name='patient_id' value='$patient_id'>
                            <input type='hidden' name='appointment_id' value='$appointment_id'>
                            <input type='hidden' name='user_id' value='$user_id'>";
                ?>
            </form>
        </div>
        <div>
            <?php
            if($userPermission >= $doctorPermission){
            echo "  <form action='./patient.php' method='POST'>
                        <input type='submit' name='submitP' value='Note'>
                        <input type='hidden' name='patient_id' value='$patient_id'>
                        <input type='hidden' name='appointment_id' value='$appointment_id'>
                        <input type='hidden' name='user_id' value='$user_id'>
                    </form>";
                }
            ?>
        </div>
    </footer>
    <?php
        $conn->close();
    ?>
</body>
</html>
