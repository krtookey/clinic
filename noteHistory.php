<?php
    include_once 'dbConnection.php';
?>
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
    <title>Notes</title>
    <script src="./patient.js" defer></script>
    <link rel="stylesheet" href="./style.css" />
    <script src="./refreshelement.js"></script>
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
      if(isset($_POST['note_id']) && $_POST['note_id'] !== ''){
          $note_id = $_POST['note_id'];
      }
      $note_id = $POST['note_id'] ?? 2; //TODO remove after testing
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
                        $query = "SELECT Note.cc, Note.assessment, Note.plan, Note.comments, Appointment.date_time, Note.note_id
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
                        $stmt->bind_result($cc, $assessment, $plan, $comments, $date_time, $note_id);
                        echo "<table class='tableBill' >";
                        ini_set('display_errors', 1);
                        ini_set('display_startup_errors', 1);
                        error_reporting(E_ALL);
                        while ($stmt->fetch()){
                          $_POST['note_id'] = $note_id;
                          echo "<tr>
                                  <td>$date_time</td>
                                  <td>$cc</td>
                                  <td>$assessment</td>
                                  <td>$plan</td>
                                  <td>$comments</td>
                                  <td><a href='openNote.php?note_id=$note_id'>Open</a></td>
                          </tr>";
                        }
                        echo "</table>";
                        ?>
                      </div>
                  </form>
              </div>
          </div>
  </div>
</section>
<footer id="footer">
          <a href="./patient.php">Back to Patient Note</a>
</footer>
  <?php
      $conn->close();
  ?>
  <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
      crossorigin="anonymous">
  </script>
</body>
</html>
