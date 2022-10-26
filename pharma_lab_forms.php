<!DOCTYPE html>
<html>
    <head>
        <style>
            body {
                max-width: 40%;
                margin: 2%;
            }
            #prescriptionform input, label {
                margin:0.2em;
            }
            #labform input, label {
                margin: 0.2em;
            }
            form {
                margin: 2%;
                border-style:solid;
                border-radius:25px;
                padding:1em;
            }
            #lab_checkboxes input{
                padding-right: 0.2em;
            }
            #lab_checkboxes label{
                padding-left: 0.2em;
            }
            fieldset{
                margin: 
            }
        </style>
    </head>
    <body>
        <form action="orders.php" method="post" id="prescriptionform">
            <b>Prescription Order Form</b>
            <br>
            <label for="doctorname">Doctor Name: </label>
            <input type="text" id="doctorname" required> 
            <label for="pharmacy">Pharmacy: </label>
            <input type="text" id="pharmacy" list="pharmacy_list" required>
            <datalist id="pharmacy_list">
                <?php 
                /*
                    $servername = "localhost";
                    $username = "username";
                    $password = "password";
                    $dbname = "myDB";
                    // Create connection
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    // Check connection
                    if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                    }
                
                    $sql = "SELECT pharmacy_name FROM Pharmacy";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            $name = $row[pharmacy_name];
                            $text = <<<TEXT
                            <option value="$name">
                            TEXT;
                            echo $text;
                        }
                    } else {
                        echo "0 results";
                    }
                    $conn->close();
                    */
                ?>
                <option value="Rite Aid Randolph"> <!-- This will be populated by the items in the SQL table Pharmacy, pharmacy_name-->
            </datalist>

            <fieldset id="prescription_inputs">
                <legend>Prescription:</legend>
                <label for="drugname">Drug Name: </label>
                <input type="text" list="druglist" id="drugname">
                <datalist id="druglist">
                    <?php 
                    /*
                        $servername = "localhost";
                        $username = "username";
                        $password = "password";
                        $dbname = "myDB";
                        // Create connection
                        $conn = new mysqli($servername, $username, $password, $dbname);
                        // Check connection
                        if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                        }
                    
                        $sql = "SELECT medication_name, generic_name FROM Pharmacy";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){
                                //This is what will create the checkboxes and labels, when it is set up correctly-->
                                $gen_name = $row[generic_name];
                                $brand_name = $row[medication_name];
                                $text = <<<TEXT
                                <option value="$brand_name ($generic_name)">
                                TEXT;
                                echo $text;
                            }
                        } else {
                            echo "0 results";
                        }
                        $conn->close();
                        */
                    ?>
                    <option value="Valium (Diazepam)"> <!-- This will be populated by the items in the SQL table DrugList, both medication_name and generic_name-->
                </datalist>
                <label for="dosage">Dosage: </label>
                <input type="text" id="dosage">
                <br>
                <label for="total_quantity">Quantity (Total): </label>
                <input type="number" id="total_quantity">
                <label for="days_quantity">Quantity (Days): </label>
                <input type="number" id="days_quantity">
                <label for="refills">Refills: </label>
                <input type="number" id="refills">
            </fieldset>

            <label for="usage_info">Usage Info and General Notes: </label>
            <br>
            <textarea rows="10" cols="40" id="usage_info"></textarea>   
            <br>
            <input type="submit" value="Submit">
        </form>

        <br>
        <br>

        <form action="orders.php" method="post" id="labform">
            <b>Lab Order Form</b>
            <br>
            <label for="doctorname">Doctor Name:</label>
            <input type="text" id="doctorname" required>
            <br>
            <label for="labdest">Lab Destination:</label>
            <input type="text" id="labdest" list="labdestlist" required> <!-- This will be populated by the items in the SQL table LabDest, labdest_name-->
            <datalist id="labdestlist">
                <?php 
                /*
                //--Look to this for help: https://www.w3schools.com/php/php_mysql_select.asp

                    $servername = "localhost";
                    $username = "username";
                    $password = "password";
                    $dbname = "myDB";
                    // Create connection
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    // Check connection
                    if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                    }
                
                    $sql = "SELECT labdest_name FROM LabDest";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            $name = $row[labdest_name];
                            $text = <<<TEXT
                            <option value="$name">
                            TEXT;
                            echo $text
                        }
                    } else {
                        echo "0 results";
                    }
                    $conn->close();
                    */
                ?>
                <option value="Gifford">
            </datalist>
            <br>
            <label for="providers_to_cc">Providers to CC:</label>
            <input type="text" id="providers_to_cc">
            <fieldset id="lab_checkboxes"> <!-- Figure out how to automatically generate this based upon LabList, with the value being the lab_id and id, name, and the label being the lab_name-->
                <legend>Labs</legend>
                <?php 
                /*
                //--Look to this for help: https://www.w3schools.com/php/php_mysql_select.asp

                    $servername = "localhost";
                    $username = "username";
                    $password = "password";
                    $dbname = "myDB";
                    // Create connection
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    // Check connection
                    if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                    }
                
                    $sql = "SELECT lab_id, lab_name FROM LabList";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            //This is what will create the checkboxes and labels, when it is set up correctly-->
                            $id = $row["lab_id"];
                            $name = $row["lab_name"];
                            $namelower = strtolower($name);
                            $text = <<<TEXT
                            <label for="$namelower">$name</label>
                            <input type="checkbox" id="$namelower" name="$namelower" value="$id">
                            TEXT;
                            echo $text;
                        }
                    } else {
                        echo "0 results";
                    }
                    $conn->close();
                    */
                ?>
                <label for="lab1">CBC</label>
                <input type="checkbox" id="cbc" name="cbc" value="cbc">
                <label for="lab2">CMP</label>
                <input type="checkbox" id="cmp" name="cmp" value="cmp">
                <label for="lab3">TSH</label>
                <input type="checkbox" id="tsh" name="tsh" value="tsh">
                <label for="lab4">Free T4</label>
                <input type="checkbox" id="free_t4" name="free_t4" value="free_t4">
                <label for="lab4">Hemoglobin A1C</label>
                <input type="checkbox" id="hemoglobin_a1c" name="hemoglobin_a1c" value="hemoglobin_a1c">
                <label for="lab4">Lipids</label>
                <input type="checkbox" id="lipids" name="lipids" value="lipids">
                <label for="lab4">Ferritin</label>
                <input type="checkbox" id="ferritin" name="ferritin" value="ferritin">
                <label for="lab4">Iron Sat</label>
                <input type="checkbox" id="iron_sat" name="iron_sat" value="iron_sat">
                <label for="lab4">CRP</label>
                <input type="checkbox" id="crp" name="crp" value="crp">
                <label for="lab4">EKG</label>
                <input type="checkbox" id="ekg" name="ekg" value="ekg">
                <br>
                <fieldset id="vitaminlabs">
                    <legend>Vitamin Labs</legend>
                    <label for="lab4">Vitamin D</label>
                    <input type="checkbox" id="vitamin_d" name="vitamin_d" value="vitamin_d">
                    <label for="lab4">Vitamin B12</label>
                    <input type="checkbox" id="vitamin_b12" name="vitamin_b12" value="vitamin_b12">
                    <label for="lab4">Vitamin B1</label>
                    <input type="checkbox" id="vitamin_b1" name="vitamin_b1" value="vitamin_b1">
                    <label for="lab4">Vitamin B2</label>
                    <input type="checkbox" id="vitamin_b2" name="vitamin_b2" value="vitamin_b2">
                </fieldset>
                <fieldset>
                    <legend>STI Tests</legend>
                    <label for="lab4">Gonorrhea</label>
                    <input type="checkbox" id="gonorrhea" name="gonorrhea" value="gonorrhea">
                    <label for="lab4">Chlamydia</label>
                    <input type="checkbox" id="chlamydia" name="chlamydia" value="chlamydia">
                    <label for="lab4">HIV</label>
                    <input type="checkbox" id="hiv" name="hiv" value="hiv">
                    <label for="lab4">Syphilis</label>
                    <input type="checkbox" id="syphilis" name="syphilis" value="syphilis">
                </fieldset>
            </fieldset>
            <input type="submit" value="Submit">
        </form>
    </body>
</html>