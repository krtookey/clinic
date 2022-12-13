<?php
include_once 'dbConnection.php'; 
include_once 'testinput.php';
$patient_id = $_POST['patient_id'] ?? '';
//echo "Patient_id = " . $patient_id;
$user_id = $_POST['user_id'] ?? '';
$appointment_id = $_POST['app_id'] ?? '';

function printappts($conn, $user_id, $patient_id, $startdate, $enddate) {
    $qstr = "SELECT appointment_id, date_time, duration, status
    FROM Appointment 
    WHERE doctor_id = $user_id
    AND patient_id = $patient_id
    AND date_time BETWEEN '$startdate' AND '$enddate' 
    ORDER BY date_time ASC";
    $qselect = $conn->prepare($qstr);
    if(! $qselect){
        echo "<p>Error: could not execute query. <br> </p>";
        echo "<pre> Error Number: " .$conn -> errno. "\n";
        echo "Error: "  .$conn -> error. "\n <pre><br>\n";
        exit;
    }
    $qselect->execute();
    $qselect->bind_result($appointment_id, $date, $duration, $status);
    $qselect->store_result();

    echo "<div id='appointmentstable' style='height:532px;'>
    <table class='tableBill'>
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
    <tbody style='overflow-x: hidden;overflow-y: auto;'>";

    $i = 0;
    while($qselect->fetch()){
        $i = $i + 1;
        $qstr = "SELECT first_name, last_name, dob
                FROM Patient 
                WHERE patient_id = $patient_id ";
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
                    echo "<input type='radio' name='app_id' value='$appointment_id'>         
                </td>";
    }
    echo "</tbody>
        </table></div>";
    if($i === 0){
        echo "<p id='noapp'>You have no appointments scheduled for this year for this patient.</p>";
    }

}

if(isset($_POST['delappt']) && $_POST['delappt'] == 'Delete Appointment' && $patient_id !== ''){
    $qstr = "SELECT status
                FROM Appointment 
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
    $stat = $row[0];
    $result->free_result();
    if ($stat == 1){
        $qstr = "   DELETE FROM Appointment 
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
    } else {
        // What to do if the appt is started?

    }
}

?>


<div id="apptsview">
    <!--
    <div id="controlbuttons">
        <button>Day View</button>
        <button>Week View</button>
        <button>Month View</button>
    </div>
-->
    <div id="allview">
        <form action="./appointments.php" method="post">
            <input type="submit" name="delappt" value="Delete Appointment" style="float:right; margin:0 20px 20px 0;font-size:25px;">
                <?php
                    $patient_id = $_POST['patient_id'] ?? '';
                    //echo "Patient_id = " . $patient_id;
                    $user_id = $_POST['user_id'] ?? '';
                    //echo "User_id = " . $user_id;
                    // Create date variables to handle stuff
                    date_default_timezone_set('America/New_York');
                    $today = date("Y-m-d");
                    $today = date_create($today);
                    $today = date_format($today, "Y/m/d H:i:s");
                    $oneyear = new DateTime('today + 1 year');
                    $oneyear = date_format($oneyear, "Y/m/d H:i:s");
                    printappts($conn, $user_id, $patient_id, $today, $oneyear);              
                ?>
                
                <?php echo "<input type='hidden' name='patient_id' value='$patient_id'>
                            <input type='hidden' name='user_id' value='$user_id'>"; ?>
        </form>
    </div>
    <!--
    <div id="dayview">
        <table id="dayviewtable">
        <?php
        /*
                $patient_id = $_POST['patient_id'] ?? '';
                //echo "Patient_id = " . $patient_id;
                $user_id = $_POST['user_id'] ?? '';
                //echo "User_id = " . $user_id;
                // Create date variables to handle stuff
                date_default_timezone_set('America/New_York');
                $today = date("Y-m-d");
                $today = date_create($today);
                $today = date_format($today, "Y/m/d H:i:s");
                $tomorrow = new DateTime('tomorrow');
                $tomorrow = date_format($tomorrow, "Y/m/d H:i:s");
                printappts($conn, $user_id, $patient_id, $today, $tomorrow);
                */              
            ?>
        </table>
    </div>
    <div id="weekview">
        <table id="weekviewtable">
            <?php
            /*
                $patient_id = $_POST['patient_id'] ?? '';
                //echo "Patient_id = " . $patient_id;
                $user_id = $_POST['user_id'] ?? '';
                //echo "User_id = " . $user_id;
                // Create date variables to handle stuff
                date_default_timezone_set('America/New_York');
                $today = date("Y-m-d");
                $today = date_create($today);
                $today = date_format($today, "Y/m/d H:i:s");
                $aweek = strtotime("+1 week");
                $aweek = date($aweek);
                $aweek = date_create($aweek);
                $aweek = date_format($aweek, "Y/m/d H:i:s");
                printappts($conn, $user_id, $patient_id, $today, $aweek);              
                */
            ?>
        </table>
    </div>
    <div id="monthview">
        <table id="monthviewtable">
            <?php
            /*
                $patient_id = $_POST['patient_id'] ?? '';
                //echo "Patient_id = " . $patient_id;
                $user_id = $_POST['user_id'] ?? '';
                //echo "User_id = " . $user_id;
                // Create date variables to handle stuff
                date_default_timezone_set('America/New_York');
                $today = date("Y-m-d");
                $today = date_create($today);
                $today = date_format($today, "Y/m/d H:i:s");
                $amonth = strtotime('+1 month');
                $amonth = date($amonth);
                $amonth = date_create($aweek);
                $amonth = date_format($amonth, "Y/m/d H:i:s");
                printappts($conn, $user_id, $patient_id, $today, $amonth);              
                */
            ?>
        </table>
    </div>
-->
</div>



