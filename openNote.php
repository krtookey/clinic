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
    <title>Note</title>
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
      $patient_id = $_POST['patient_id'] ?? 1; //TODO remove after testing
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
      $note_id = $_POST['note_id'] ?? 2;
      ?>
  </header>

<form method='post' action='openNote.php'>
<section class= "openNote">
                        <?php
                        $query = "SELECT Note.Demographics, Note.cc, Note.assessment, Note.plan, Note.comments, Appointment.date_time, Note.med_hist, Note.social_hist, Note.hist_illness, Note.psych_hist, Note.substance_hist, Note.topics
                        FROM Note
                        INNER JOIN Appointment
                        ON Note.appointment_id = Appointment.appointment_id
                        WHERE Note.note_id = $note_id";
                        $stmt = $conn->prepare($query);
                        //Execute and get results from database
                        $stmt->execute();
                        //Store results in values
                        $stmt->store_result();
                        $stmt->bind_result($demographics, $cc, $assessment, $plan, $comments, $date_time, $med_history, $social_hist, $illness_hist, $psych_hist, $substance_hist, $topics);
                        while ($stmt->fetch()){}
                        ?>
</section>
<section class="patientNote">
    <div class="card" id='patientFormCard'>
        <div class="card-body">
            <h3 class="card-title">
                <?php echo "Note from: $date_time"?>
            </h3>
            <form id='patientNoteForm'>
                <!-- Demographics  -->
                <div class="mb-3 formField" id="demographicsContainer">
                    <p> <?php echo "$demographics" ?> </p>
                </div>
                <!-- Chief Complaint -->
                <div class="mb-3 formField" id="chiefComplaintContainer">
                    <p> <?php echo "Chief Complaint: $cc" ?> </p>
                </div>
                <!-- History Of Illness -->
                <div class="mb-3 formField" id="histOfIllnessContainer">
                    <p> <?php echo "Illness History: $illness_hist" ?> </p>
                </div>
                <!-- Social -->
                <div class="mb-3 formField" id="socialContainer">
                    <p> <?php echo "$social_hist" ?> </p>
                </div>
                <!-- Substance History -->
                <div class="mb-3 formField" id="substanceHistContainer">
                    <p> <?php echo "$substance_hist" ?> </p>
                </div>
                <!-- Psychological History -->
                <div class="mb-3 formField" id="psychHistContainer">
                    <p> <?php echo "$psych_hist" ?> </p>
                </div>
                <!-- Medical History -->
                <div class="mb-3 formField" id="medicalHistContainer">
                    <p> <?php echo "$med_history" ?> </p>
                </div>
                <!-- Assessment/Formulation -->
                <div class="mb-3 formField" id="assessmentContainer">
                    <p> <?php echo "$assessment" ?> </p>
                </div>
                <!-- Treatment Plan -->
                <div class="mb-3 formField" id="treatmentPlanContainer">
                    <p> <?php echo "Treatment $plan" ?> </p>
                </div>
                <!-- General Comments -->
                <div class="mb-3 formField" id="generalCommentsContainer">
                    <p> <?php echo "General $comments" ?> </p>
                </div>
                <!-- Topics Discussed -->
                <div class="mb-3 formField" id="topicsContainer">

                    <p> <?php echo "$topics" ?> </p>
                </div>
                <div class="mb-3 formField" id="rosContainer">
                    <label for="ros" id="rosLabel">Review of Symptoms</label>
                    <p>
                    <?php
                        $sql = "SELECT ReviewOfSystem.*
                        FROM ReviewOfSystem
                        INNER JOIN Note ON ReviewOfSystem.ros_id = Note.ros_id
                        WHERE Note.patient_id = $patient_id
                        ORDER BY Note.note_id
                        DESC LIMIT 1";

                        $query = $conn->prepare($sql);
                        $query->execute();
                        $result = $query->get_result();
                        $row = $result -> fetch_row();
                        //Include label array
                        include_once 'rosFields.php';

                        //If results exist
                        if (isset($row[1])) {
                            for ($i = 2; $i < 121; $i++) {
                                if ($rosFieldLabels[$i] == "last eye exam") {
                                    echo "<li>Last eye exam: $row[$i]</li>";
                                }
                                if ($rosFieldLabels[$i] == "comments") {
                                    echo "<li>Comments: $row[$i]</li>";
                                }
                                else if (isset($row[$i])) {
                                    echo "<li>$rosFieldLabels[$i]</li>";
                                }
                            }
                        }
                        else {
                            echo "<p>No results found</p>";
                        }
                    ?>
                  </p>
                </div>
            </form>
        </div>
    </div>
</section>
      <footer>
         <a href="./NoteHistory.php">Back to History</a>
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
