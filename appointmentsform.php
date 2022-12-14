<?php
$patient_id = $_POST['patient_id'] ?? '';
//echo "Patient_id = " . $patient_id;
$user_id = $_POST['user_id'] ?? '';
//echo "User_id = " . $user_id;

function getAge($date){
    $n = 0;
    $birth = new DateTime($date);
    $today = new DateTime(date("Y-m-d"));
    $diff = $today->diff($birth);
    $n = $diff->y;
    return $n;
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


if(isset($_POST['submitbutton']) && ($_POST['submitbutton'] == 'Add Appointment')){
    $_POST['submitbutton'] = '';
    $appt_date_time = $_POST['appt_date_time'];
    $duration = $_POST['duration'];
    //echo ($appt_date_time);
    //echo ($duration);
    // Grabbing doctor name based on doctor_id
    $drname_sql = "SELECT first_name, last_name FROM Users WHERE user_id='" . $user_id . "';";
    $drname_result = $conn->query($drname_sql);
    if ($row = $drname_result->fetch_assoc()){
        $user_first_name = $row["first_name"];
        $user_last_name = $row["last_name"];
    } else {
        echo("There is no user name for the current user. Please contact an administrator.");
    }
    $status = 1;

    $addappt_query = <<<INSERTUSER
    INSERT INTO Appointment (patient_id, date_time, duration, status, doctor_id, doctor_first_name, doctor_last_name) 
    VALUES ('$patient_id', '$appt_date_time','$duration','$status','$user_id','$user_first_name','$user_last_name');
    INSERTUSER;
    if($addappt_result = $conn->query($addappt_query)){
        // This code will bring the user back to index.php on adding an appointment and will keep the current patient selected. 
        // It is commented out for now to make it behave similar to delete appointment, both will keep the user on this page so 
        // user can see results of action in appointment list
        /* 
        echo("
        <form name='homeafterappt' id='homeafterappt' action='./index.php' method='POST'>
            <input type='submit' name='submitI' value='Home' hidden>
            <input type='hidden' name='patient_id' value='$patient_id'>
            <input type='hidden' name='user_id' value='$user_id'>
        </form>
        <script type='text/javascript'>
            document.forms['homeafterappt'].submit();
        </script>
        "); 
        */
    } else {
        echo("Appointment info was not added to database correctly. Please contact an administrator.");
    }
}
?>

<div id="appointmentsformdiv">
    <style>
        #appointmentsformdiv {
            font-size:25px;
            padding: 30px 0 0 30px;
        }

        #appointmentsform{
            margin-top:73px;
        }

        input {
            font-size:25px;
            border: 2px solid var(--orange);
            border-radius: 5px;
            padding: 5px;
        }
    </style>
    <form id="appointmentsform" method="post">
        <label for="appt_date_time">Date and Time</label>
        <input type="datetime-local" id="appt_date_time" name="appt_date_time">
        <br>
        <label for="duration">Duration</label>
        <input type="number" id="duration" name="duration">
        <br>
        <input type="submit" name="submitbutton" value="Add Appointment">
        <?php 
        echo "
        <input type='hidden' name='patient_id' value='$patient_id'>
        <input type='hidden' name='user_id' value='$user_id'>";
        ?>
    </form>
</div>