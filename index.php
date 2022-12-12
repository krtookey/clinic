<?php
    include_once 'dbConnection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
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
        if(isset($_GET['user_id']) && $_GET['user_id'] !== ''){
            $user_id = $_GET['user_id'] ?? '';
            $_POST['user_id'] = $_GET['user_id'] ?? '';
        }
        if(isset($_POST['user_id']) && $_POST['user_id'] !== ''){
            $user_id = $_POST['user_id'] ?? '';
        }
        $user_id = $_POST['user_id'] ?? '';
        //$user_id = 1;                           //For Testing.
        $managmentPermission = 3;               // Top Permission Level for adding users and managing system.
        $doctorPermission = 2;                  // Permission Level for doctor and NPs - access to patient infomation.
        $nursePermission = 1;                   // Permission Level for nurses - access to limited patient information.
        $userPermission = 0;
    
        //Get User's Permission Level.
        if ($user_id !== '' ){
            $qstr = "SELECT DISTINCT permission FROM Users WHERE user_id = '" . $user_id . "';";
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
        } else {
            echo "<p>User not found.  Return to Login.</p>
                    <a href="./login.php">Login</a>";
        }

        //Variables.
        $fname = $_POST['firstname'] ?? '';
        $lname = $_POST['lastname'] ?? '';
        $dob = $_POST['dob'] ?? '';
        $id = $_POST['pid'] ?? '';
        //Get Today's and Tomorrow's dates. Each with time set to 00:00:00 and formatted for mySQL queries.
        date_default_timezone_set('America/New_York');
        $today = date("Y-m-d");
        $today = date_create($today);
        $today = date_format($today, "Y/m/d H:i:s");
        $tomorrow = new DateTime('tomorrow');
        $tomorrow = date_format($tomorrow, "Y/m/d H:i:s");

        //Maintain form POST variables.
        if(!isset($_POST['search']) || $_POST['search'] != 'Search'){
            $_POST['search'] = '';
        }
        if(!isset($_POST['select']) || $_POST['select'] != 'Select'){
            $_POST['select'] = '';
        }
        if(!isset($_POST['start']) || $_POST['start'] != 'Start Appointment'){
            $_POST['start'] = '';
        }
        if(!isset($_POST['end']) || $_POST['end'] != 'End Appointment'){
            $_POST['end'] = '';
        }
        if(isset($_POST['pid']) && $_POST['pid'] !== ''){
            $id = $_POST['pid'];
        }

        //If Select button is clicked reset patient_id.
        if(isset($_POST['select']) && $_POST['select'] == 'Select'){
            $patient_id = $id;
        }

        //If Start Appointment button is clicked create a new note and billing statement tied to the patient's appointment.
        if(isset($_POST['start']) && $_POST['start'] == 'Start Appointment' && $id !== ''){
            $patient_id = $id;
            //Patients must only have one appointment pre day.
            $qstr = "SELECT appointment_id, status
                     FROM Appointment 
                     WHERE patient_id = $patient_id 
                     AND date_time BETWEEN '$today' AND '$tomorrow' 
                     ORDER BY date_time ASC
                     LIMIT 1";
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
            $app = $row[0];
            $stat = $row[1];
            $result->free_result();
            $appointment_id = $app;
            
            //Start new note.
            if($stat === 1){
                $qstr = "SELECT prev_note_id FROM Patient 
                        WHERE patient_id = $patient_id ";
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
                $prev_note_id = $row[0];
                $result->free_result();

                $cc = '';
                $hist_ill = '';
                $hist_social = '';
                $hist_med = '';
                $hist_psych = '';
                $assess = '';
                $plan = '';
                $lab_order = 0;
                $lab_dest = 0;
                $demo = '';
                $comment = '';
                $hist_sub = '';
                $topics = '';

                //Get previous note's fields.
                if($prev_note_id !== 0){
                    $sql = "SELECT Note.cc, Note.hist_illness, Note.social_hist, Note.med_hist, Note.psych_hist, Note.assessment, Note.plan, Note.laborder_id, Note.labdest_id, Note.demographics, Note.comments, Note.substance_hist, Note.topics
                            FROM Note
                            WHERE note_id = $prev_note_id ";
                    $qselect = $conn->prepare($sql);
                    if(! $qselect){
                        echo "<p>Error: could not execute query. <br> </p>";
                        echo "<pre> Error Number: " .$conn -> errno. "\n";
                        echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                        exit;
                    }
                    $qselect->execute();
                    $result = $qselect->get_result();
                    $row = $result->fetch_row();
                    $cc = $row[0];
                    $hist_ill = $row[1];
                    $hist_social = $row[2];
                    $hist_med = $row[3];
                    $hist_psych = $row[4];
                    $assess = $row[5];
                    $plan = $row[6];
                    $lab_order = $row[7];
                    $lab_dest = $row[8];
                    $demo = $row[9];
                    $comment = $row[10];
                    $hist_sub = $row[11];
                    $topics = $row[12];
                    $result->free_result();
                } 

                //Create new note.
                $qstr = "INSERT INTO Note (patient_id, appointment_id, cc, hist_illness, ros_id, med_profile_id, social_hist, med_hist, psych_hist, assessment, plan, laborder_id, labdest_id, demographics, comments, substance_hist) 
                        VALUES ('$patient_id', '$appointment_id', '$cc', '$hist_ill', '0', '0', '$hist_social', '$hist_med', '$hist_psych', '$assess', '$plan', '$lab_order', '$lab_dest', '$demo', '$comment', '$hist_sub') ";
                $qinsert = $conn->prepare($qstr);
                if(!$qinsert){
                    echo "<p>Error: could not execute query. <br> </p>";
                    echo "<pre> Error Number: " .$conn -> errno. "\n";
                    echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                    exit;
                }
                $qinsert->execute();
                $qinsert->store_result();
                $qinsert->free_result();

                //Get new note_id.
                $qstr = "SELECT note_id FROM Note 
                        WHERE patient_id = $patient_id 
                        ORDER BY note_id DESC
                        LIMIT 1";
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
                $note_id = $row[0];
                $result->free_result();

                //Update new note_id as prev_note_id.
                $qstr = "UPDATE Patient 
                        SET prev_note_id = $note_id  
                        WHERE patient_id = $patient_id ";
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

                //Start new bill.
                $qstr = "SELECT bill_address FROM Billing 
                        WHERE patient_id = $patient_id 
                        ORDER BY billing_id DESC
                        LIMIT 1 ";
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
                $baddress = $row[0];
                $result->free_result();
                $amount_due = 250.00;

                $qstr = "INSERT INTO Billing (patient_id, appointment_id, note_id, bill_statement, amount_due, paid, bill_address) 
                        VALUES ('$patient_id', '$appointment_id', '$note_id', 'new visit', '$amount_due', '0', '$baddress') ";
                $qinsert = $conn->prepare($qstr);
                if(!$qinsert){
                    echo "<p>Error: could not execute query. <br> </p>";
                    echo "<pre> Error Number: " .$conn -> errno. "\n";
                    echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                    exit;
                }
                $qinsert->execute();
                $qinsert->store_result();
                $qinsert->free_result();

                //Update Appointment status.
                $qstr = "UPDATE Appointment 
                         SET status = 2  
                         WHERE appointment_id = $appointment_id ";
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
        }
        //End of Start Appointment.

        if(isset($_POST['end']) && $_POST['end'] == 'End Appointment' && $id !== ''){
            if($appointment_id !== 0){
                $qstr = "SELECT cc FROM Note 
                         WHERE appointment_id = $appointment_id "; 
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
                $cc = $row[0];
                $result->free_result();

                $qstr = "   UPDATE Billing 
                            SET bill_statement = '$cc'  
                            WHERE appointment_id = $appointment_id ";
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

                $qstr = "   UPDATE Appointment 
                            SET status = 3  
                            WHERE appointment_id = $appointment_id ";
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
        }
        //End of End Appointment.

        //Start of Patient Search.
    
        //Make sure the submitted dob contains a valid date string.
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

        $dob = validateDate($dob);

        if($dob !== ''){
            $age = getAge($dob);
        }

        //getAge: gets a patient's age
        //      $date = patient's birth year.
        function getAge($date){
            $n = 0;
            $birth = new DateTime($date);
            $today = new DateTime(date("Y-m-d"));
            $diff = $today->diff($birth);
            $n = $diff->y;
            return $n;
        }

        //See if patient search form has been submitted, and populate header accordingly.
        if(isset($_POST['search']) && $_POST['search'] == 'Search'){
            
            $qstr = "SELECT DISTINCT gender, preferred, patient_id, sex FROM Patient WHERE first_name = ? && last_name = ? && DOB = ? ";
            //echo $qstr;
            $qsearch = $conn->prepare($qstr);
            if(!$qsearch){
                echo "<p>Error: could not execute query. <br> </p>";
                echo "<pre> Error Number: " .$conn -> errno. "\n";
                echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                exit;
            }
            $qsearch->bind_param("sss", $fname, $lname, $dob);
            $qsearch->execute();
            $qsearch->store_result();
            $qsearch->bind_result($gender, $preferred, $patient_id, $sex);

            echo "<header>";
            while($qsearch->fetch()){
                echo "  <p>$preferred</p>
                        <p>$dob</p>
                        <p>Age: $age</p>
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
            echo " </header>";
            $qsearch->free_result();
        }

        //If Patient_Id is present and search form has not been submitted, populate header.        
        if ($patient_id !== '') {

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

        //End Patient Search.
    ?>
    <div class="placeholder"></div>
    <div class="whiteCard">
        <h2>Today's Appointments</h2>
        <form action="./index.php" method="post">
            <div>
            <?php
                $qstr = "SELECT patient_id, date_time, duration, status
                         FROM Appointment 
                         WHERE doctor_id = $user_id 
                         AND date_time BETWEEN '$today' AND '$tomorrow' 
                         ORDER BY date_time ASC";
                $qselect = $conn->prepare($qstr);
                if(! $qselect){
                    echo "<p>Error: could not execute query. <br> </p>";
                    echo "<pre> Error Number: " .$conn -> errno. "\n";
                    echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                    exit;
                }
                $qselect->execute();
                $qselect->bind_result($id, $date, $duration, $status);
                $qselect->store_result();

                echo "<table class='tableBill' >
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Duration</th>
                                <th>Patient Name</th>
                                <th>DOB</th>
                                <th>Status</th>
                                <th>Select</th>
                            </tr>
                        </thead>
                        <tbody>";
                $i = 0;
                while($qselect->fetch()){
                    $i = $i + 1;
                    $qstr = "SELECT first_name, last_name, dob
                             FROM Patient 
                             WHERE patient_id = $id ";
                    $q = $conn->prepare($qstr);
                    if(! $q){
                        echo "<p>Error: could not execute query. <br> </p>";
                        echo "<pre> Error Number: " .$conn -> errno. "\n";
                        echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                        exit;
                    }
                    $q->execute();
                    $result = $q->get_result();
                    $row = $result->fetch_row();
                    $name = $row[0]." ".$row[1];
                    $dob = $row[2];
                    $result->free_result();

                    $str = strtotime($date);
                    $date = date("Y/m/d h:i A", $str);

                    echo "<tr>
                            <td>";
                                echo $date;
                    echo "  </td>
                            <td>";
                                echo $duration." minutes";
                    echo "  </td>
                            <td>";
                                echo $name;
                    echo "  </td>
                            <td>";
                                echo $dob;
                    echo "  </td>
                            <td>";
                            if($status == 1){
                                echo "Upcoming";
                            } else if ($status == 2){
                                echo "Started";
                            } else if ($status == 3){
                                echo "Ended";
                            }
                    echo "  </td>        
                            <td>";
                                echo "<input type='radio' name='pid' value='{$id}'>         
                            </td>";
                }
                echo "</tbody>
                    </table>";
                if($i === 0){
                    echo "<p id='noapp'>You have no appointments scheduled for today.</p>";
                }                    
            ?>
            </div>
            <div class="saveButton">
                <input type="submit" name="select" value="Select" >
                <input type="submit" name="start" value="Start Appointment" >
                <input type="submit" name="end" value="End Appointment" >
            </div>
            <?php echo "<input type='hidden' name='patient_id' value='$patient_id'>
                        <input type='hidden' name='appointment_id' value='$appointment_id'>
                        <input type='hidden' name='user_id' value='$user_id'>"; ?>
        </form>
    </div>
    <div class="whiteCard">
        <h2>Patient Search</h2>
        <form id="searchForm" action="index.php" method="POST">
            <div id="searchGrid">
                <div class="searchItem">
                    <label>First Name:</label>
                    <input type="text" name="firstname">
                </div>
                <div class="searchItem">
                    <label>Last Name:</label>
                    <input type="text" name="lastname">
                </div>
                <div class="searchItem">
                    <label>Date of Birth:</label>
                    <input type="text" name="dob" placeholder="YYYY-MM-DD">
                </div>
            </div>
            <div class="saveButton">
                <input type="submit" name="search" value="Search" >
            </div>
        </form>
    </div>

    <footer>
        <div>
            <?php
                if($userPermission >= $managmentPermission){
                echo "  <form action='./register.php' method='POST'>
                            <input type='submit' name='submitNU' value='New User'>
                            <input type='hidden' name='user_id' value='$user_id'>
                        </form>";
                }
            ?>
        </div>
        <div>
            <form action="./logout.php" method="POST">
                <input type="submit" name="submitLO" value="Sign Out">
                <?php
                    echo " <input type='hidden' name='user_id' value='$user_id'>";
                ?>
            </form>
        </div>
        <div>
            <a href="appointments">Appointments</a> 
        </div>
        <div>
            <form action="./newPatient.php" method="POST">
                <input type="submit" name="submitNP" value="New Patient">
                <?php
                    //Store patient_id, appointment_id, user_id in POST buffer.
                    echo "  <input type='hidden' name='patient_id' value=''>
                            <input type='hidden' name='appointment_id' value='$appointment_id'>
                            <input type='hidden' name='user_id' value='$user_id'>";
                ?>
            </form>
        </div> 
        <?php
            if($patient_id !== ''){
            echo "  <div>
                        <form action='./editPatient.php' method='POST'>
                            <input type='submit' name='submitEP' value='Edit Patient'>
                            <input type='hidden' name='patient_id' value='$patient_id'>
                            <input type='hidden' name='appointment_id' value='$appointment_id'>
                            <input type='hidden' name='user_id' value='$user_id'>    
                        </form>
                    </div>";
            }
        ?>
        <?php
            if($userPermission >= $nursePermission){
            echo "  <div>
                        <form action='./medicalProfile.php' method='POST'>
                            <input type='submit' name='submitMP' value='Medical Profile'>
                            <input type='hidden' name='patient_id' value='$patient_id'>
                            <input type='hidden' name='appointment_id' value='$appointment_id'>
                            <input type='hidden' name='user_id' value='$user_id'>
                        </form>
                    </div>";
                }                
        
            if($userPermission >= $doctorPermission && $patient_id !== ''){
            echo "  <div>
                        <form action='./patient.php' method='POST'>
                            <input type='submit' name='submitP' value='Note'>
                            <input type='hidden' name='patient_id' value='$patient_id'>
                            <input type='hidden' name='appointment_id' value='$appointment_id'>
                            <input type='hidden' name='user_id' value='$user_id'>
                        </form>
                    </div>";
                }
        
            if($userPermission >= $doctorPermission && $patient_id !== ''){
            echo "  
                    <div>
                        <form action='./noteHistory.php' method='POST'>
                            <input type='submit' name='submitNH' value='Note History'>
                            <input type='hidden' name='patient_id' value='$patient_id'>
                            <input type='hidden' name='appointment_id' value='$appointment_id'>
                            <input type='hidden' name='user_id' value='$user_id'>
                        </form>
                    </div>";
                    }
        ?>
    </footer>
    <?php
        $conn->close();
    ?>
</body>
</html>
