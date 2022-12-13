<?php
$patient_id = $_POST['patient_id'] ?? '';
//echo "Patient_id = " . $patient_id;
$user_id = $_POST['user_id'] ?? '';
//echo "User_id = " . $user_id;



if(isset($_POST['submitbutton']) && ($_POST['submitbutton'] == 'Add Appointment')){
    $_POST['submitbutton'] = '';
    $appt_date_time = $_POST['appt_date_time'];
    $duration = $_POST['duration'];
    echo ($appt_date_time);
    echo ($duration);
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
        header("Location: ./index.php?user_id=" . $user_id);
        exit();
    } else {
        echo("Appointment info was not added to database correctly. Please contact an administrator.");
    }
}
?>

<style>
    * {
        font-size:25px;
    }
    input {
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
    <input type='hidden' name='user_id' value='$user_id'>
    "?>
</form>
