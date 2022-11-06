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
    
        //Debug:
            //$patient_id = 1;
            //echo "<pre>"; print_r($_POST); echo "</pre>";

        //Variables that may have been posted to the POST buffer.
        $patient_id = $POST['patient_id'] ?? "0";
        $fname = $_POST['firstname'] ?? '';
        $lname = $_POST['lastname'] ?? '';
        $dobirth = $_POST['dob'] ?? '';

        if($dobirth !== ''){
            $age = getAge($dobirth);
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

        if(!isset($_POST['submit']) || $_POST['submit'] != 'Search'){
            $_POST['submit'] = '';
        }

        //See if patient search form has been submitted, and populate header accordingly.
        if(isset($_POST['submit']) || $_POST['submit'] == 'Search'){
            
            $qstr = "SELECT DISTINCT gender, preferred, patient_id, sex FROM Patient WHERE first_name = ? && last_name = ? && DOB = ? ";
            $qsearch = $db->prepare($qstr);
            if(!$qsearch){
                echo "<p>Error: could not execute query. <br> </p>";
                echo "<pre> Error Number: " .$db -> errno. "\n";
                echo "Error: "  .$db -> error. "\n <pre><br>\n";
                exit;
            }
            $qsearch->bind_param("sss", $fname, $lname, $dobirth);
            $qsearch->execute();
            $qsearch->store_result();
            $qsearch->bind_result($gender, $preferred, $patient_id, $sex);

            echo "<header>";
            while($qsearch->fetch()){
                echo "  <p>$preferred</p>
                        <p>$dobirth</p>
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
        //If Patient_Id is present and search form has not been submitted, populate header.
        } elseif ($patient_id !== 0 && !isset($_POST['submit'])) {

            $qstr = "SELECT DISTINCT gender, preferred, first_name, last_name, DOB, sex FROM Patient WHERE patient_id = $patient_id ";
            $qpatient = $db->prepare($qstr);
            if(! $qpatient){
                echo "<p>Error: could not execute query. <br> </p>";
                echo "<pre> Error Number: " .$db -> errno. "\n";
                echo "Error: "  .$db -> error. "\n <pre><br>\n";
                exit;
            }
            $qpatient->execute();
            $qpatient->store_result();
            $qpatient->bind_result($gender, $preferred, $fname, $lname, $dobirth, $sex);
            
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
                    <input type="text" name="dob" value="YYYY-MM-DD">
                </div>
            </div>
            <div class="saveButton">
                <input type="submit" name="submit" value="Search" id="searchButton">
            </div>
        </form>
    </div>
    <div class="whiteCard">
        <a href="appointments">Appointments</a> 
        <form action="./medicalProfile.php" method="POST">
            <input type="submit" name="submitMR" value="Medical Profile">
            <?php
                //Store patient header information in POST buffer.
                if($patient_id != 0){
                    echo "<input type='hidden' name='patient_id' value='$patient_id'>";
                    echo "<input type='hidden' name='preferred' value='$preferred'>";
                    echo "<input type='hidden' name='dob' value='$dobirth'>";
                    echo "<input type='hidden' name='age' value='$age'>";
                    echo "<input type='hidden' name='firstname' value='$fname'>";
                    echo "<input type='hidden' name='lastname' value='$lname'>";
                    echo "<input type='hidden' name='sex' value='$sex'>";
                    echo "<input type='hidden' name='gender' value='$gender'>";
                }
            ?>
        </form>
        <form action="./patient.php" method="POST">
            <input type="submit" name="submitP" value="Note">
            <?php
                //Store patient header information in POST buffer.
                if($patient_id != 0){
                    echo "<input type='hidden' name='patient_id' value='$patient_id'>";
                    echo "<input type='hidden' name='preferred' value='$preferred'>";
                    echo "<input type='hidden' name='dob' value='$dobirth'>";
                    echo "<input type='hidden' name='age' value='$age'>";
                    echo "<input type='hidden' name='firstname' value='$fname'>";
                    echo "<input type='hidden' name='lastname' value='$lname'>";
                    echo "<input type='hidden' name='sex' value='$sex'>";
                    echo "<input type='hidden' name='gender' value='$gender'>";
                }
            ?>
        </form>
        <form action="./noteHistory.php" method="POST">
            <input type="submit" name="submitP" value="Note History">
            <?php
                //Store patient header information in POST buffer.
                if($patient_id != 0){
                    echo "<input type='hidden' name='patient_id' value='$patient_id'>";
                    echo "<input type='hidden' name='preferred' value='$preferred'>";
                    echo "<input type='hidden' name='dob' value='$dobirth'>";
                    echo "<input type='hidden' name='age' value='$age'>";
                    echo "<input type='hidden' name='firstname' value='$fname'>";
                    echo "<input type='hidden' name='lastname' value='$lname'>";
                    echo "<input type='hidden' name='sex' value='$sex'>";
                    echo "<input type='hidden' name='gender' value='$gender'>";
                }
            ?>
        </form>
    </div>
    <footer>
        <a href="new user">New User</a>
        <a href="./newPatient.php">New Patient</a>
    </footer>
    <?php
        $db->close();
    ?>
</body>
</html>
