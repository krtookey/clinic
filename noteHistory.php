<?php
    include_once 'dbConnection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Note History</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
  <header id='patientHeader'>
      <?php
      //SQL
      $query = "SELECT Patient.first_name, Patient.last_name, Patient.DOB, Patient.sex, Patient.preferred
      FROM Patient
      WHERE Patient.patient_id = ?";
      //Prepare statment
      $stmt = $conn->prepare($query);
      //Bind ? with the POST variable from the prvious page
      $patient_id = $POST['patient_id'] ?? 1; //TODO remove after testing
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

<form method='post' action='NoteHistory.php'>
<section class= "noteHistory">
  <div id="noteHistory">
          <div class="card" id='patientFormCard'>
              <div class="card-body">
                  <h3 class="card-title">
                      Notes
                  </h3>
                  <form id='patientNoteForm'>
                      <div class="mb-3 formField" id="noteContainer">
                        <?php
                        $query = "SELECT Note.cc, Note.assessment, Note.plan, Note.comments, Appointment.date_time
                        FROM Note
                        INNER JOIN Appointment
                        ON Note.appointment_id = Appointment.appointment_id
                        WHERE Note.patient_id = ?";
                        $stmt = $conn->prepare($query);
                        $patient_id = $POST['patient_id'] ?? 1; //TODO remove after testing
                        $stmt->bind_param("i", $patient_id);
                        //Execute and get results from database
                        $stmt->execute();
                        //Store results in values
                        $stmt->store_result();
                        $stmt->bind_result($cc, $assessment, $plan, $comments, $date_time);
                        while ($stmt->fetch()){
                          echo "<table border='1'>";
                          //Needs formatting for each table
                          echo "<tr><td>$date_time</td>
                          <td>$cc</td
                          ><td>$assessment</td>
                          <td>$plan</td>
                          <td>$comments</td>
                          <td>
                          <form method=GET action=openNote.php>
                          <input type=submit value ='Open'>
                          </td>
                          </tr>";
                          echo "</table>";
                          echo "</br>";
                          $note_id = $POST['note_id'] ?? 2; //TODO Remove after testing and change to autoset to value
                        }
                        ?>
                      </div>
                  </form>
              </div>
          </div>
  </div>
</section>
  <footer>
      <div>
          <a href="./patient.php">Back to Patient Note</a>
      </div>
  </footer>
  <?php
      $conn->close();
  ?>
</body>
</html>
