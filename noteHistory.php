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

  <div id="noteHistory">
      <h2>Notes</h2> <br>

<form method='post' action='NoteHistory.php'>
<?php
//grabbing row of patient data
$query = "SELECT *
FROM Note
WHERE Note.patient_id = ?";
$stmt = $conn->prepare($query);

$patient_id = $POST['patient_id'] ?? 1; //TODO remove after testing
$stmt->bind_param("i", $patient_id);

//Execute and get results from database
$stmt->execute();

$stmt->store_result();
$stmt->bind_result($note_id, $patient_id, $appoint_id, $cc, $hist_illness, $ros_id, $med_profile_id, $social_hist, $med_hist, $psych_hist, $assessment, $plan, $laborder_id, $lab_destid, $demographics, $comments);

//be able to swap notes
//show only certain parts of a note
//order it by most recent
//figure out how to go and edit the selected note

//Attempts on trying to get multiple column data
while ($stmt->fetch()){
  echo "<table border='1'>";

//debugging for right now, need to remove ids
  echo "<tr><td>$note_id</td> <td>$patient_id</td><td>$appoint_id</td><td>$cc</td><td>$hist_illness</td><td>$ros_id</td><td>$med_profile_id</td><td>$social_hist</td><td>$med_hist</td><td>$psych_hist</td><td>$assessment</td><td>$plan</td><td>$laborder_id</td><td>$lab_destid</td><td>$demographics</td><td>$comments</td> </tr>";
  echo "<table></br>";

}

?>

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
