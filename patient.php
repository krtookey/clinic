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
        <title>Patient</title>
        <script src="./patient.js" defer></script>
        <link rel="stylesheet" href="./style.css" />
        <script src="./refreshelement.js"></script>
    </head>
    <body>
        <header id='patientHeader'>
            <?php
            //Store globals in POST buffer.
            if(isset($_POST['patient_id']) && $_POST['patient_id'] !== ''){
                $patient_id = $_POST['patient_id'];
              }
            $patient_id = $_POST['patient_id'] ?? '1';
            if(isset($_POST['appointment_id']) && $_POST['appointment_id'] !== ''){
                $appointment_id = $_POST['appointment_id'];
              }
            $appointment_id = $_POST['appointment_id'] ?? '1';
            if(isset($_POST['user_id']) && $_POST['user_id'] !== ''){
                $user_id = $_POST['user_id'];
              }
            $user_id = $_POST['user_id'] ?? '2';
            $userPermission = 0;
            $managmentPermission = 3;               // Top Permission Level for adding users and managing system.
            $doctorPermission = 2;                  // Permission Level for doctor and NPs - access to patient infomation.
            $nursePermission = 1;                   // Permission Level for nurses - access to limited patient information.

            //SQL
            $sql = "SELECT Patient.first_name, Patient.last_name, Patient.DOB, Patient.sex, Patient.preferred
            FROM Patient
            WHERE Patient.patient_id = ?";

            //Prepare statment
            $stmt = $conn->prepare($sql);

            //Bind ? with the POST variable from the prvious page 
            $stmt->bind_param("i", $patient_id);

            //Execute and get results from database
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
        <?php
            //Save data
            $sql = "UPDATE Note 
            SET demographics = ?, cc = ?, hist_illness = ?, social_hist = ?, substance_hist = ?, psych_hist = ?, med_hist = ?, assessment = ?, plan = ?, comments = ?
            WHERE Note.patient_id = $patient_id AND Note.appointment_id = $appointment_id";
            echo $_POST['demographics']. $_POST['chiefComplaint']. $_POST['histOfIllness'].$_POST['social'].$_POST['substanceHist'].$_POST['psychHist'].$_POST['medicalHist'].$_POST['assessment'].$_POST['treatmentPlan'].$_POST['generalComments'];
            echo "patient id: " . $patient_id . " appointment id: " . $appointment_id;
            $stmt = $conn->prepare($sql);
            if(!$stmt){
                echo "<p>Error: could not execute query. <br> </p>";
                echo "<pre> Error Number: " .$conn -> errno. "\n";
                echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                exit;
            }
            $stmt->bind_param("ssssssssss", $_POST['demographics'], $_POST['chiefComplaint'], $_POST['histOfIllness'],$_POST['social'],$_POST['substanceHist'],$_POST['psychHist'],$_POST['medicalHist'],$_POST['assessment'],$_POST['treatmentPlan'],$_POST['generalComments']);
            $stmt->execute();
        ?>
        <div class="patientBody">
            <section class="patientSideMenu">
                <!-- Medication List -->
                <button
                class="btn btn-primary patientSideMenuBtn"
                type="button"
                data-button-name='medicationList'
                aria-expanded="false"
                aria-controls="medicationListBox">
                    Medication List
                </button>
                <div
                class="collapse card card-body patientMenuBox hideContent medicationList"
                id="medicationListBox">
                    <div class="medicationList patientMenuItem">
                        <div id="medicationListItems">
                            <?php
                            include_once "showmedicationlist.php";
                            ?>
                        </div>
                        <div id="medicationListAddForm">
                            <?php
                            include_once "adddrugtomedlistform.php";
                            ?>
                        </div>
                    </div>
                </div>
                <!-- Review Of Systems -->
                <button
                class="btn btn-primary patientSideMenuBtn"
                type="button"
                data-button-name='reviewOfSystems'
                aria-expanded="false"
                aria-controls="reviewOfSystemsBox">
                    Review Of Systems
                </button>
                <div
                class="collapse card card-body patientMenuBox hideContent"
                id="reviewOfSystemsBox">
                    <div class="reviewOfSystems patientMenuItem">
                    <ul>
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
                    </ul>
                    </div>
                </div>
                <!-- Lab Results -->
                <button
                class="btn btn-primary patientSideMenuBtn"
                type="button"
                data-button-name='labResults'
                aria-expanded="false"
                aria-controls="labResultsBox">
                    Lab Results
                </button>
                <div class="collapse hideContent patientMenuBox" id="labResultsBox">
                    <div class="labResults card card-body patientMenuItem">
                        <div id="labResultsItems">
                            <?php
                            include_once "showlabresults.php";
                            ?>
                        </div>
                        <div id="labResultsInsertForm">
                            <?php
                            include_once "insertlabresultsform.php";
                            ?>
                        </div>
                    </div>
                </div>
                <!-- Family History -->
                <button
                class="btn btn-primary patientSideMenuBtn"
                type="button"
                data-button-name='personalHistory'
                aria-expanded="false"
                aria-controls="personalHistoryBox">
                    Family History
                </button>
                <div class="collapse hideContent patientMenuBox" id="personalHistoryBox">
                    <div class="personalHistory card card-body patientMenuItem">
                        <?php
                            include_once 'familyHistory.php';
                        ?>
                    </div>
                </div>

                <!-- OrderPharmacy -->
                <button
                class="btn btn-primary patientSideMenuBtn"
                type="button"
                data-button-name='orderPharmacy'
                aria-expanded="false"
                aria-controls="orderPharmacyBox">
                    Order Pharmacy
                </button>
                <div class="collapse hideContent patientMenuBox" id="orderPharmacyBox">
                    <div class="orderPharmacy card card-body patientMenuItem">

                        <!-- PHARMACY GROUP! Pharmacy order form goes here ---------------------------------------------------->
                        <?php
                        include_once "prescriptionform.php";
                        ?>
                    </div>
                </div>

                <!-- Order Lab -->
                <button
                class="btn btn-primary patientSideMenuBtn"
                type="button"
                data-button-name='orderLab'
                aria-expanded="false"
                aria-controls="orderLabBox">
                    Order Lab
                </button>
                <div class="collapse hideContent patientMenuBox" id="orderLabBox">
                    <div class="orderLab card card-body patientMenuItem">
                        <!-- PHARMACY GROUP! Lab order form goes here --------------------------------------------------------->
                        <?php
                        include_once "laborderform.php"
                        ?>
                    </div>
                </div>
                <!-- Lab Order History -->
                <button
                class="btn btn-primary patientSideMenuBtn"
                type="button"
                data-button-name='labOrderHistory'
                aria-expanded="false"
                aria-controls="labOrderHistoryBox">
                    Lab Order History
                </button>
                <div
                class="collapse card card-body patientMenuBox hideContent"
                id="labOrderHistoryBox">
                    <div class="labOrderHistory patientMenuItem">
                        <!-- Put code here -->
                        <?php
                        include_once 'viewlaborders.php'
                        ?>
                    </div>
                </div>
                <!-- Prescriptions -->
                <button
                class="btn btn-primary patientSideMenuBtn"
                type="button"
                data-button-name='prescriptions'
                aria-expanded="false"
                aria-controls="prescriptionsBox">
                    Prescriptions
                </button>
                <div
                class="collapse card card-body patientMenuBox hideContent"
                id="prescriptionsBox">
                    <div class="prescriptions patientMenuItem">
                        <!-- Put code here -->
                        <?php
                        include_once 'viewprescriptions.php'
                        ?>
                    </div>
                </div>            
                <!-- Add Pharmacy Form -->
                <button
                class="btn btn-primary patientSideMenuBtn"
                type="button"
                data-button-name='addPharmacy'
                aria-expanded="false"
                aria-controls="addPharmacyBox">
                    Add Pharmacy
                </button>
                <div
                class="collapse card card-body patientMenuBox hideContent"
                id="addPharmacyBox">
                    <div class="addPharmacy patientMenuItem">
                        <!-- Put code here -->
                        <?php
                        include_once 'addpharmacyform.php'
                        ?>
                    </div>
                </div>
                <!-- Add LabDest Form -->
                <button
                class="btn btn-primary patientSideMenuBtn"
                type="button"
                data-button-name='addLabDest'
                aria-expanded="false"
                aria-controls="addLabDestBox">
                    Add Lab Destination
                </button>
                <div
                class="collapse card card-body patientMenuBox hideContent"
                id="addLabDestBox">
                    <div class="addLabDest patientMenuItem">
                        <!-- Put code here -->
                        <?php
                        include_once 'addlabdestform.php'
                        ?>
                    </div>
                </div>
                <!-- Add Drug Form -->
                <button
                class="btn btn-primary patientSideMenuBtn"
                type="button"
                data-button-name='addDrug'
                aria-expanded="false"
                aria-controls="addDrugBox">
                    Add Drug To List
                </button>
                <div
                class="collapse card card-body patientMenuBox hideContent"
                id="addDrugBox">
                    <div class="addDrug patientMenuItem">
                        <!-- Put code here -->
                        <?php
                        include_once 'adddrugform.php'
                        ?>
                    </div>
                </div>
                <!-- Links -->
                <hr>
                <!-- Note History -->
                <a
                class="btn btn-primary patientSideMenuLink"
                href="noteHistory.php">
                    Note History
                </a>
            </section>

            <!-------------------------- Current patient Note ------------------------------>
            <?php
                                $sql = "SELECT Note.appointment_id, Note.cc, Note.hist_illness, Note.ros_id, Note.med_profile_id, Note.social_hist, Note.med_hist, Note.psych_hist, Note.assessment, Note.plan, Note.laborder_id, Note.labdest_id, Note.demographics, Note.comments, Patient.prev_note_id, Note.substance_hist
                                FROM Note
                                INNER JOIN Patient
                                ON Patient.patient_id = Note.patient_id
                                WHERE Patient.patient_id = ?
                                ORDER BY Note.note_id
                                DESC LIMIT 1";
                                //Prepare statment
                                $stmt = $conn->prepare($sql);
                                //Bind ? with the POST variable from the prvious page 
                                $stmt->bind_param("i", $patient_id); //$_POST['patient_id']
                                //Execute and get resutls from database
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $row = $result->fetch_row();
                            ?>
            <section class="patientNote">
                <div class="card" id='patientFormCard'>
                    <div class="card-body">
                        <h3 class="card-title">
                            Date from appointment table
                        </h3>
                        <form id="patientNoteForm" action="./patient.php" method="post">
                            
                            <!-- Demographics  -->
                            <div class="mb-3 formField" id="demographicsContainer">
                                <label
                                for="demographics"
                                id="demographicsLabel">Demographics</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="demographics"
                                name="demographics"
                                ><?php
                                    echo $row['12'];
                                ?>
                                </textarea>
                            </div>
                            <!-- Chief Complaint -->
                            <div class="mb-3 formField" id="chiefComplaintContainer">
                                <label
                                for="chiefComplaint"
                                id="chiefComplaintLabel">Chief Complaint</label>
                                <textarea
                                rows="1"
                                class="form-control"
                                id="chiefComplaint"
                                name="chiefComplaint"
                                ><?php
                                    echo $row['1'];
                                ?></textarea>
                            </div>
                            <!-- History Of Illness -->
                            <div class="mb-3 formField" id="histOfIllnessContainer">
                                <label
                                for="histOfIllness"
                                id="histOfIllnessLabel"
                                >History Of Present Illness</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="histOfIllness"
                                name="histOfIllness"
                                ><?php
                                    echo $row['2'];
                                ?></textarea
                                >
                            </div>
                            <!-- Social -->
                            <div class="mb-3 formField" id="socialContainer">
                                <label for="social" id="socialLabel">Social</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="social"
                                name="social"><?php
                                    echo $row['5'];
                                ?></textarea>
                            </div>
                            <!-- Substance History -->
                            <div class="mb-3 formField" id="substanceHistContainer">
                                <label for="substanceHist" id="substanceHistLabel">Substance History</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="substanceHist"
                                name="substanceHist"><?php
                                    echo $row['15'];
                                ?></textarea>
                            </div>
                            <!-- Psychological History -->
                            <div class="mb-3 formField" id="psychHistContainer">
                                <label for="social" id="psychHistLabel"
                                >Psychological History</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="psychHist"
                                name="psychHist"
                                ><?php
                                    echo $row['7'];
                                ?></textarea>
                            </div>
                            <!-- Medical History -->
                            <div class="mb-3 formField" id="medicalHistContainer">
                                <label for="social" id="medicalHistLabel"
                                >Medical History</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="medicalHist"
                                name="medicalHist"
                                ><?php
                                    echo $row['6'];
                                ?></textarea>
                            </div>
                            <!-- Assessment/Formulation -->
                            <div class="mb-3 formField" id="assessmentContainer">
                                <label for="assessment" id="assessmentLabel"
                                >Assessment/Formulation</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="assessment"
                                name="assessment"><?php
                                    echo $row['8'];
                                ?></textarea>
                            </div>
                            <!-- Treatment Plan -->
                            <div class="mb-3 formField" id="treatmentPlanContainer">
                                <label
                                for="treatmentPlan"
                                id="treatmentPlanLabel"
                                >Treatment Plan</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="treatmentPlan"
                                name="treatmentPlan"><?php
                                    echo $row['9'];
                                ?></textarea>
                            </div>
                            <!-- General Comments -->
                            <div class="mb-3 formField" id="generalCommentsContainer">
                                <label
                                for="generalComments"
                                id="generalCommentsLabel"
                                >General Comments</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="generalComments"
                                name="generalComments"
                                ><?php
                                    echo $row['13'];
                                ?></textarea>
                            </div>
                            <!-- Topics Discussed -->
                            <div class="mb-3 formField" id="topicsContainer">
                                <label for="topics" id="topicsLabel">Topics Discussed With Patient</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="topics"
                                name="topics">Need to add this to database</textarea>
                            </div>
                            <!-- Save Note -->
                            <input type="submit" value="Save Note" name="noteSave" class="btn btn-primary" id='patientFormSubmit'>
                        </form>
                    </div>
                </div>
            </section>
        </div>
        <footer>
        <div>
            <form action="./index.php" method="POST">
                <input type="submit" name="submitI" value="Home">
                <?php
                    
                    echo "  <input type='hidden' name='patient_id' value='$patient_id'>
                            <input type='hidden' name='appointment_id' value='$appointment_id'>
                            <input type='hidden' name='user_id' value='$user_id'>";
                ?>
            </form>
        </div>
    </footer>

        <!-- Bootstrap JS -->
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous">
        </script>
    </body>
</html>
