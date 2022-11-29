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
        $appointment_id = $_POST['appointment_id'] ?? '1'; //For Testing
        if(isset($_POST['user_id']) && $_POST['user_id'] !== ''){
            $user_id = $_POST['user_id'];
        }
        $user_id = $_POST['user_id'] ?? '2';    //For Testing.
        $user_id = 4;                           //For Testing.
        $managmentPermission = 3;               // Top Permission Level for adding users and managing system.
        $doctorPermission = 2;                  // Permission Level for doctor and NPs - access to patient infomation.
        $nursePermission = 1;                   // Permission Level for nurses - access to limited patient information.
        $userPermission = 0;
    
        //Get User's Permission Level.
        if ($user_id !== '' ){
            $qstr = "SELECT DISTINCT permission FROM Users WHERE user_id = '" . $user_id . "';";
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

        //Patient Search. 

        //Debug:
            //$patient_id = 1;
            //echo "<pre>"; print_r($_POST); echo "</pre>";

        //Variables from Search form.
        $fname = $_POST['firstname'] ?? '';
        $lname = $_POST['lastname'] ?? '';
        $dob = $_POST['dob'] ?? '';
        if(!isset($_POST['search']) || $_POST['search'] != 'Search'){
            $_POST['search'] = '';
        }

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

        //Debug: echo "Print Age: $age";

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
        if(isset($_POST['search']) || $_POST['search'] == 'Search'){
            
            $qstr = "SELECT DISTINCT gender, preferred, patient_id, sex FROM Patient WHERE first_name = ? && last_name = ? && DOB = ? ";
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
                <input type="submit" name="search" value="Search" id="searchButton">
            </div>
        </form>
    </div>

    <footer>
        <div>
            <?php
                if($userPermission >= $managmentPermission){
                echo "  <form action='new user' method='POST'>
                            <input type='submit' name='submitNU' value='New User'>
                            <input type='hidden' name='user_id' value='$user_id'>
                        </form>";
                }
            ?>
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
        <div>
            <form action="./editPatient.php" method="POST">
                <input type="submit" name="submitEP" value="Edit Patient">
                <?php
                    //Store patient_id, appointment_id, user_id in POST buffer.
                    echo "  <input type='hidden' name='patient_id' value='$patient_id'>
                            <input type='hidden' name='appointment_id' value='$appointment_id'>
                            <input type='hidden' name='user_id' value='$user_id'>";
                ?>
            </form>
        </div>
        <div>
            <a href="appointments">Appointments</a> 
        </div>
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
        
            if($userPermission >= $doctorPermission){
            echo "  <div>
                        <form action='./patient.php' method='POST'>
                            <input type='submit' name='submitP' value='Note'>
                            <input type='hidden' name='patient_id' value='$patient_id'>
                            <input type='hidden' name='appointment_id' value='$appointment_id'>
                            <input type='hidden' name='user_id' value='$user_id'>
                        </form>
                    </div>";
                }
        
            if($userPermission >= $doctorPermission){
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
