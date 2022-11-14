<form action="lab_orders.php" method="post" target="laborderlinkdisplay" id="labform">
    <div id="patient_info">
        <?php 
            //#$orderlab
            $user_id = $POST['user_id'] ?? 1;
            $patient_id = $POST['patient_id'] ?? 1;
            $idfields = <<<IDFIELDS
            <input type="text" id="patient_id" name="patient_id" value="$patient_id" hidden>
            <input type="text" id="user_id" name="user_id" value="$user_id" hidden>
            IDFIELDS;
            echo($idfields);
            //Need to get patient name and DOB from medical records views
            //$patient_name = "John Doe"; // PLACEHOLDER
            //$patient_dob = "10/12/1996"; // PLACEHOLDER
            echo("<b>". $patient_name . "</b>  <b>  " . $patient_dob . "</b>");
        ?>
    </div>
    <br>
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
        <label for="cbc">CBC</label>
        <input type="checkbox" id="cbc" name="labs[]" value="CBC">
        <label for="cmp">CMP</label>
        <input type="checkbox" id="cmp" name="labs[]" value="CMP">
        <label for="tsh">TSH</label>
        <input type="checkbox" id="tsh" name="labs[]" value="TSH">
        <label for="free_t4">Free T4</label>
        <input type="checkbox" id="free_t4" name="labs[]" value="Free T4">
        <label for="hemoglobin_a1c">Hemoglobin A1C</label>
        <input type="checkbox" id="hemoglobin_a1c" name="labs[]" value="Hemoglobin A1C">
        <label for="lipids">Lipids</label>
        <input type="checkbox" id="lipids" name="labs[]" value="Lipids">
        <label for="ferritin">Ferritin</label>
        <input type="checkbox" id="ferritin" name="labs[]" value="Ferritin">
        <label for="iron_sat">Iron Sat</label>
        <input type="checkbox" id="iron_sat" name="labs[]" value="Iron Sat">
        <label for="magnesium">Magnesium</label>
        <input type="checkbox" id="magnesium" name="labs[]" value="Magnesium">
        <label for="crp">CRP</label>
        <input type="checkbox" id="crp" name="labs[]" value="CRP">
        <label for="prolactin">Prolactin</label>
        <input type="checkbox" id="prolactin" name="labs[]" value="Prolactin">
        <label for="copper">Copper</label>
        <input type="checkbox" id="copper" name="labs[]" value="Copper">
        <label for="zinc">Zinc</label>
        <input type="checkbox" id="zinc" name="labs[]" value="Zinc">
        <label for="ekg">EKG</label>
        <input type="checkbox" id="ekg" name="labs[]" value="EKG">
        <br>
        <fieldset id="vitaminlabs">
            <legend>Vitamin Labs</legend>
            <label for="vitamin_d">Vitamin D</label>
            <input type="checkbox" id="vitamin_d" name="labs[]" value="Vitamin D">
            <label for="vitamin_b12">Vitamin B12</label>
            <input type="checkbox" id="vitamin_b12" name="labs[]" value="Vitamin B12">
            <label for="vitamin_b1">Vitamin B1</label>
            <input type="checkbox" id="vitamin_b1" name="labs[]" value="Vitamin B1">
            <label for="vitamin_b2">Vitamin B2</label>
            <input type="checkbox" id="vitamin_b2" name="labs[]" value="Vitamin B2">
        </fieldset>
        <fieldset>
            <legend>STI Tests</legend>
            <label for="lab4">Gonorrhea</label>
            <input type="checkbox" id="gonorrhea" name="labs[]" value="Gonorrhea">
            <label for="lab4">Chlamydia</label>
            <input type="checkbox" id="chlamydia" name="labs[]" value="Chlamydia">
            <label for="lab4">HIV</label>
            <input type="checkbox" id="hiv" name="labs[]" value="HIV">
            <label for="lab4">Syphilis</label>
            <input type="checkbox" id="syphilis" name="labs[]" value="Syphilis">
        </fieldset>
        <label for="pregnancy">Pregnancy</label>
        <input type="checkbox" id="pregnancy" name="labs[]" value="Pregnancy">
    </fieldset>
    <label for="diagnosis">Diagnosis:</label>
    <input type="text" id="diagnosis" name="diagnosis" required>
    <br>
    <input type="submit" value="Submit">
    <iframe name="laborderlinkdisplay"></iframe>
</form>