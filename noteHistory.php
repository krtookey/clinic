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
      $sql = "SELECT Patient.first_name, Patient.last_name, Patient.DOB, Patient.sex, Patient.preferred
      FROM Patient
      WHERE Patient.patient_id = ?";
      //Prepare statment
      $stmt = $conn->prepare($sql);
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

  <div class="placeholder"></div>
  <div id="noteHistory">
      <h2>Notes</h2> <br>

<form method='post' action='NoteHistory.php'>
<?php
//Wondering if I need this here or should I only connect when needed.
$db = new mysqli('localhost', 'root', '', 'Clinic');
if ($db->connect_errno > 0){
  echo "<p>Error: Could not connect to database.<br></p>";
  echo "<pre>\nErrno: " . $db->errno . "\n";
  echo "Error: " . $db->error . "\n</pre><br>\n";

   exit;
}
//Need patient Id, appoint_id, cc, hist_illness, ros_id, med_profile_id, social_hist, med_hist, psych_hist, assessment, plan, laborder_id, lab_destid, demographics, comments
//To-do: make table via PHP
//Grab patient data
//order it by most recent
//move the header to top of page
//figure out how to go and edit the selected note
//
?>

      <table id ="noteTable">
        <tr>
          <th>Date: </th>
          <th>Date: </th>
          <th>Date: </th>
          <th>Date: </th>
        </tr>
        <tr>
          <td>Data from patients notes</td>
          <td>Data from patients notes</td>
          <td>Data from patients notes</td>
          <td>Data from patients notes</td>
        </tr>
        <tr>
          <th>Date: </th>
          <th>Date: </th>
          <th>Date: </th>
          <th>Date: </th>
        </tr>
        <tr>
          <td>Data from patients notes</td>
          <td>Data from patients notes</td>
          <td>Data from patients notes</td>
          <td>Data from patients notes</td>
        </tr>
        <tr>
          <th>Date: </th>
          <th>Date: </th>
          <th>Date: </th>
          <th>Date: </th>
        </tr>
        <tr>
          <td>Data from patients notes</td>
          <td>Data from patients notes</td>
          <td>Data from patients notes</td>
          <td>Data from patients notes</td>
        </tr>
      </table>


  </div>
  <footer>
      <div>
          <a href="./index.php">Home</a>
      </div>
  </footer>
  <?php
      $conn->close();
  ?>
</body>
</html>
