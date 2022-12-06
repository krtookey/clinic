<form action="lab_orders.php" method="post" target="laborderlinkdisplay" id="labform" onSubmit='confirmOverwrite();'>
    <script src="labformfunctions.js"></script>
    <div id="patient_info">
        <?php 
            //#$orderlab
            $user_id = $POST['user_id'] ?? 1;
            $patient_id = $POST['patient_id'] ?? 1;
            $appointment_id = $POST['appointment_id'] ?? 3;
            $idfields = <<<IDFIELDS
            <input type="number" id="confirm_failed" name="confirm_failed" value="0" hidden>
            <input type="text" id="patient_id" name="patient_id" value="$patient_id" hidden>
            <input type="text" id="user_id" name="user_id" value="$user_id" hidden>
            <input type="text" id="appointment_id" name="appointment_id" value="$appointment_id" hidden>
            IDFIELDS;
            echo($idfields);
            //Need to get patient name and DOB from medical records views
            //$patient_name = "John Doe"; // PLACEHOLDER
            //$patient_dob = "10/12/1996"; // PLACEHOLDER
            echo("<b>". $patient_name . "</b>  <b>  " . $patient_dob . "</b>");

            // Checking if a lab order has already been submitted as part of this note, and notifying the user if that is the case
            $checkingorderid_sql = <<<ORDERIDSQL
            SELECT laborder_id FROM Note WHERE patient_id = '$patient_id' AND appointment_id = '$appointment_id';
            ORDERIDSQL;
            $checkingorderid_result = $conn->query($checkingorderid_sql);
            if ($result->num_rows > 0){
                $orderid_row = $checkingorderid_result->fetch_assoc();
                $note_laborder_id = $orderid_row['laborder_id'];
                if ($note_laborder_id != 0){
                    echo("<p id='overwritetext' style='color:blue;'>A lab order has already been submitted for this note. Submitting another lab order will replace the current lab order within this note, but the other lab order will remain untouched in the database.</p>");
                }
            }
        ?>
    </div>
    <label for="labdest">Lab Destination:</label> <!-- Should automatically be filled by patient default lab dest-->
    <?php
        // Get labdest_id for patient
        $sql = "SELECT labdest_id FROM Patient where patient_id = '" . $patient_id . "';";
        $result = $conn->query($sql);
        if ($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $patient_labdest_id = $row["labdest_id"]; 
            // Get labdest_name for labdest_id
            $sql = "SELECT labdest_name FROM LabDest where labdest_id = '" . $patient_labdest_id . "';";
            $result = $conn->query($sql);
            if ($result->num_rows > 0){
                $row = $result->fetch_assoc();
                $labdest_name = $row["labdest_name"]; 
                $labdestinput = <<<LABDEST_INPUT
                <input type="text" id="labdest" name="labdest" list="labdestlist" value="$labdest_name" required>
                LABDEST_INPUT;
                echo $labdestinput;
            }
        }
    ?>
    <datalist id="labdestlist">
        <?php 
        //--Look to this for help: https://www.w3schools.com/php/php_mysql_select.asp
            /*
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "clinic";
            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            }
            */

            $sql = "SELECT labdest_name FROM LabDest";
            $result = $conn->query($sql);

            if ($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $name = $row["labdest_name"];
                    $text = <<<TEXT
                    <option value="$name">
                    TEXT;
                    echo $text;
                }
            } else {
                echo "0 results";
            }
            //$conn->close();
        ?>
    </datalist>
    <br>
    <label for="providers_to_cc">Providers to CC:</label>
    <input type="text" id="providers_to_cc" name="providers_to_cc">
    <fieldset id="lab_checkboxes"> <!-- Figure out how to automatically generate this based upon LabList, with the value being the lab_id and id, name, and the label being the lab_name-->
        <legend>Labs</legend>
        <label for="toggleAllCheckboxes">Toggle All</label>
        <input type="checkbox" onclick="toggle(this);">
        <br>
        <label for="cbc">CBC</label>
        <input type="checkbox" id="cbc" name="labs[]" value="CBC" class="labcheckbox">
        <label for="cmp">CMP</label>
        <input type="checkbox" id="cmp" name="labs[]" value="CMP" class="labcheckbox">
        <label for="tsh">TSH</label>
        <input type="checkbox" id="tsh" name="labs[]" value="TSH" class="labcheckbox">
        <label for="free_t4">Free T4</label>
        <input type="checkbox" id="free_t4" name="labs[]" value="Free T4" class="labcheckbox">
        <label for="hemoglobin_a1c">Hemoglobin A1C</label>
        <input type="checkbox" id="hemoglobin_a1c" name="labs[]" value="Hemoglobin A1C" class="labcheckbox">
        <label for="lipids">Lipids</label>
        <input type="checkbox" id="lipids" name="labs[]" value="Lipids" class="labcheckbox">
        <label for="ferritin">Ferritin</label>
        <input type="checkbox" id="ferritin" name="labs[]" value="Ferritin" class="labcheckbox">
        <label for="iron_sat">Iron Sat</label>
        <input type="checkbox" id="iron_sat" name="labs[]" value="Iron Sat" class="labcheckbox">
        <label for="magnesium">Magnesium</label>
        <input type="checkbox" id="magnesium" name="labs[]" value="Magnesium" class="labcheckbox">
        <label for="crp">CRP</label>
        <input type="checkbox" id="crp" name="labs[]" value="CRP" class="labcheckbox">
        <label for="prolactin">Prolactin</label>
        <input type="checkbox" id="prolactin" name="labs[]" value="Prolactin" class="labcheckbox">
        <label for="copper">Copper</label>
        <input type="checkbox" id="copper" name="labs[]" value="Copper" class="labcheckbox">
        <label for="zinc">Zinc</label>
        <input type="checkbox" id="zinc" name="labs[]" value="Zinc" class="labcheckbox">
        <label for="ekg">EKG</label>
        <input type="checkbox" id="ekg" name="labs[]" value="EKG" class="labcheckbox">
        <label for="pregnancy">Pregnancy</label>
        <input type="checkbox" id="pregnancy" name="labs[]" value="Pregnancy" class="labcheckbox">
        <br>
        <fieldset id="vitaminlabs">
            <legend>Vitamin Labs</legend>
            <label for="vitamin_d">Vitamin D</label>
            <input type="checkbox" id="vitamin_d" name="labs[]" value="Vitamin D" class="labcheckbox">
            <label for="vitamin_b12">Vitamin B12</label>
            <input type="checkbox" id="vitamin_b12" name="labs[]" value="Vitamin B12" class="labcheckbox">
            <label for="vitamin_b1">Vitamin B1</label>
            <input type="checkbox" id="vitamin_b1" name="labs[]" value="Vitamin B1" class="labcheckbox">
            <label for="vitamin_b2">Vitamin B2</label>
            <input type="checkbox" id="vitamin_b2" name="labs[]" value="Vitamin B2" class="labcheckbox">
        </fieldset>
        <fieldset>
            <legend>STI Tests</legend>
            <label for="gonorrhea">Gonorrhea</label>
            <input type="checkbox" id="gonorrhea" name="labs[]" value="Gonorrhea" class="labcheckbox">
            <label for="chlamydia">Chlamydia</label>
            <input type="checkbox" id="chlamydia" name="labs[]" value="Chlamydia" class="labcheckbox">
            <label for="lab4">HIV</label>
            <input type="checkbox" id="hiv" name="labs[]" value="HIV" class="labcheckbox">
            <label for="syphilis">Syphilis</label>
            <input type="checkbox" id="syphilis" name="labs[]" value="Syphilis" class="labcheckbox">
        </fieldset>
    </fieldset>
    <label for="diagnosis">Diagnosis:</label>
    <input type="text" id="diagnosis" name="diagnosis" required>
    <br>
    <input type="submit" value="Submit">
    <iframe name="laborderlinkdisplay" class="results_iframe"></iframe>
</form>