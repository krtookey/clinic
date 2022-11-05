<!DOCTYPE html>
<html>
    <head>
        <style>
            body {
                max-width: 45%;
                margin: 2%;
            }
            form input, label {
                margin:0.2em;
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
            #patient_info b{
                margin:1.5em; 
                font-size:1.2em;
            }
        </style>
        <script type="text/javascript">
        function calcquantity(){
            qtyperdose = eval(document.getElementById("qtyperdose").value);
            frequency = eval(document.getElementById("frequency").value);
            duration = document.getElementById("duration").value;
            total = qtyperdose * frequency * duration;
            //total = math.ceil(total);
            console.log(total);
            document.getElementById("quantity").value = total;
        }
        /*
        function submitPrescription(){
            var pharmacy=document.getElementByID('pharmacy').value
            var drugname=document.getElementByID('drugname').value
            var dosage_num=document.getElementByID('dosage_num').value
            var unit=document.getElementByID('unit').value
            var dosage_type=document.getElementByID('dosage_type').value
            var route=document.getElementByID('route').value
            var qtyperdose=document.getElementByID('qtyperdose').value
            var frequency=document.getElementByID('frequency').value
            var duration=document.getElementByID('duration').value
            var quantity=document.getElementByID('quantity').value
            $.ajax({
                type: "post",
                url: "prescription_orders.php",
                data: {
                    'pharmacy' :pharmacy,
                    'drugname' :drugname,
                    'dosage_num' :dosage_num,
                    'unit' :unit,
                    'dosage_type' :dosage_type,
                    'route' :route, 
                    'qtyperdose' :qtyperdose,
                    'frequency' :frequency,
                    'duration' :duration,
                    'quantity' :quantity
                },
                cache:false,
                success: function(html){
                    alert('Data Send');
                }
            });
            return false;
            */
        }
        </script>
        <script>src="./jquery-3.6.1.js"</script>
    </head>
    <body>
        <form action="prescription_orders.php" method="post" target="prescriptionlinkdisplay" id="prescriptionform">
            <div id="patient_info">
                <?php 
                    //Need to get patient name and DOB from medical records views
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
                
                    $sql = "SELECT first_name, last_name, DOB FROM Patient WHERE patient_id ='" . $patient_id . "';";
                    $result = $conn->query($sql);
                
                    if ($result->num_rows > 0){
                        $row = $result->fetch_assoc()
                        $patient_name = $row["first_name"] . ' ' . $row["last_name"];
                        $patient_dob = $row["DOB"];
                    } else {
                        echo "0 results";
                    }
                    $conn->close();
                    */
                    $patient_name = "John Doe"; // PLACEHOLDER
                    $patient_dob = "10/12/1996"; // PLACEHOLDER
                    
                    echo("<b>". $patient_name . "</b>  <b>  " . $patient_dob . "</b>");
                    echo("<br><br>");
                    echo('<label for="pharmacy">Pharmacy: </label>');
                    /*
                    // Get pharmacy_id for patient
                    $sql = "SELECT pharmacy_id FROM Patient where patient_id = '" . $patient_id . "';";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $patient_pharmacy_id = $row["pharmacy_id"]; 

                    // Get pharmacy_id for pharmacy_id
                    $sql = "SELECT pharmacy_name FROM Pharmacy where pharmacy_id = '" . $patient_pharmacy_id . "';";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $pharmacy_name = $row["pharmacy_name"]; 

                    //$conn->close();
                    */
                    $patient_pharmacy = "Nick's Funky Pharmacy"; // PLACEHOLDER
                    $pharmacyinput = <<<PHARM_INPUT
                    <input type="text" id="pharmacy" name="pharmacy" list="pharmacy_list" default="$patient_pharmacy" required>
                    PHARM_INPUT;
                    echo $pharmacyinput;
                    

                ?>
            </div>
            <datalist id="pharmacy_list">
                <?php  
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
                
                    $sql = "SELECT pharmacy_name FROM Pharmacy";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            $name = $row["pharmacy_name"];
                            $text = <<<TEXT
                            <option value="$name">
                            TEXT;
                            echo $text;
                        }
                    } else {
                        echo "0 results";
                    }
                    //$conn->close();
                ?> <!-- This will be populated by the items in the SQL table Pharmacy, pharmacy_name-->
            </datalist>
            <fieldset id="prescription_inputs">
                <legend>Prescription:</legend>
                <label for="drugname">Drug Name: </label>
                <input type="text" list="druglist" id="drugname" name="drugname" required>
                <datalist id="druglist">
                    <?php 
                    
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
                    
                        $sql = "SELECT medication_name, generic_name FROM DrugList;";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){
                                //This is what will create the checkboxes and labels, when it is set up correctly-->
                                $gen_name = $row["generic_name"];
                                $brand_name = $row["medication_name"];
                                $text = <<<TEXT
                                <option value="$brand_name">
                                <option value="$gen_name">
                                TEXT;
                                echo $text;
                            }
                        } else {
                            echo "0 results";
                        }
                        //$conn->close();
                    ?>
                </datalist>
                <label for="dosage">Dosage:</label>
                <input type="text" list="dosage_nums" id="dosage_num" name="dosage_num" size="10" required>
                <datalist id="dosage_nums">
                    <option value="1">   
                    <option value="2"> 
                    <option value="5">
                    <option value="10">   
                    <option value="20">   
                    <option value="30">  
                    <option value="50">  
                    <option value="100">  
                    <option value="200">  
                </datalist>
                <label for="unit">Unit:</label>
                <select id="unit" name="unit">
                    <option value="mg" selected>mg</option>   
                    <option value="ml">ml</option>   
                    <option value="cc">cc</option>
                </select>      
                <br>
                <label for="dosage_type">Type:</label>
                <input type="text" list="dosagetypes" id="dosage_type" value="Tablet" name="dosage_type">
                <datalist id="dosagetypes">
                    <option value="Tablet">
                    <option value="Capsule">
                    <option value="Chewable"> 
                    <option value="Liquid">  
                    <option value="Other">  
                </datalist>
                <label for="route">Route:</label>
                <select id="route" name="route">
                    <option value="Oral" selected>Oral</option>
                    <option value="Topical">Topical</option>
                    <option value="IM">IM</option>
                    <option value="IV">IV</option>
                    <option value="SubQ">Sub Q</option>
                </select>
                <br><br>
                <label for="qtyperdose">Qty per Dose</label>
                <select id="qtyperdose" name="qtyperdose" onchange="calcquantity()">
                    <option value="0.25">1/4</option>
                    <option value="0.5">1/2</option>
                    <option value="1" selected>1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                </select>
                <label for="frequency">Frequency Of Dose</label>
                <select id="frequency" name="frequency" onchange="calcquantity()">
                    <option value="2">twice per day</option>
                    <option value="3">3 times per day</option>
                    <option value="1" selected>once per day</option>
                    <option value="1/7">once per week</option>
                    <option value="1/2">every other day</option>
                    <option value="1/30">per 30 days</option>
                </select>
                <label for="duration">Duration</label>
                <select id="duration" name="duration" onchange="calcquantity()">
                    <option value="7">7 days</option>
                    <option value="14" selected>14 days</option>
                    <option value="21">21 days</option>
                    <option value="28">28 days</option>
                    <option value="30">30 days</option>
                </select>
                <label for="total_quantity">Quantity: </label> 
                <input type="number" id="quantity" name="quantity" max="100"> <!-- Default needs to be calculated from qtyperdose * frequency * duration -->
                <label for="refills">Refills: </label>
                <input type="number" id="refills" name="refills" value="1" max="10">
            </fieldset>

            <label for="usage_info">Usage Info and General Notes: </label>
            <br>
            <textarea rows="10" cols="40" id="usage_info" name="usage_info"></textarea>   
            <br>
            <input type="submit" value="Submit"> 
            <iframe name="prescriptionlinkdisplay"></iframe>
        </form>

        <br>
        <br>

        <form action="lab_orders.php" method="post" target="laborderlinkdisplay" id="labform">
            <div id="patient_info">
                <?php 
                    //Need to get patient name and DOB from medical records views
                    $patient_name = "John Doe"; // PLACEHOLDER
                    $patient_dob = "10/12/1996"; // PLACEHOLDER
                    echo("<b>". $patient_name . "</b>  <b>  " . $patient_dob . "</b>");
                ?>
            </div>
            <br>
            <!--<label for="doctorname">Doctor Name:</label> -->
            <input type="text" id="doctorname" required> <!--Doctor name needs to be automatically grabbed from who is logged in, we can probably get rid of this field -->
            <label for="labdest">Lab Destination:</label> <!-- Should automatically be filled by patient default lab dest-->
            <input type="text" id="labdest" name="labdest" list="labdestlist" required> <!-- This will be populated by the items in the SQL table LabDest, labdest_name-->
            <datalist id="labdestlist">
                <?php 
                //--Look to this for help: https://www.w3schools.com/php/php_mysql_select.asp
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
                <input type="checkbox" id="cbc" name="general_labs[]" value="cbc">
                <label for="cmp">CMP</label>
                <input type="checkbox" id="cmp" name="general_labs[]" value="cmp">
                <label for="tsh">TSH</label>
                <input type="checkbox" id="tsh" name="general_labs[]" value="tsh">
                <label for="free_t4">Free T4</label>
                <input type="checkbox" id="free_t4" name="general_labs[]" value="free_t4">
                <label for="hemoglobin_a1c">Hemoglobin A1C</label>
                <input type="checkbox" id="hemoglobin_a1c" name="general_labs[]" value="hemoglobin_a1c">
                <label for="lipids">Lipids</label>
                <input type="checkbox" id="lipids" name="general_labs[]" value="lipids">
                <label for="ferritin">Ferritin</label>
                <input type="checkbox" id="ferritin" name="general_labs[]" value="ferritin">
                <label for="iron_sat">Iron Sat</label>
                <input type="checkbox" id="iron_sat" name="general_labs[]" value="iron_sat">
                <label for="magnesium">Magnesium</label>
                <input type="checkbox" id="magnesium" name="general_labs[]" value="magnesium">
                <label for="crp">CRP</label>
                <input type="checkbox" id="crp" name="general_labs[]" value="crp">
                <label for="prolactin">Prolactin</label>
                <input type="checkbox" id="prolactin" name="general_labs[]" value="prolactin">
                <label for="copper">Copper</label>
                <input type="checkbox" id="copper" name="general_labs[]" value="copper">
                <label for="zinc">Zinc</label>
                <input type="checkbox" id="zinc" name="general_labs[]" value="zinc">
                <label for="ekg">EKG</label>
                <input type="checkbox" id="ekg" name="general_labs[]" value="ekg">
                <br>
                <fieldset id="vitaminlabs">
                    <legend>Vitamin Labs</legend>
                    <label for="vitamin_d">Vitamin D</label>
                    <input type="checkbox" id="vitamin_d" name="vitamin_labs[]" value="vitamin_d">
                    <label for="vitamin_b12">Vitamin B12</label>
                    <input type="checkbox" id="vitamin_b12" name="vitamin_labs[]" value="vitamin_b12">
                    <label for="vitamin_b1">Vitamin B1</label>
                    <input type="checkbox" id="vitamin_b1" name="vitamin_labs[]" value="vitamin_b1">
                    <label for="vitamin_b2">Vitamin B2</label>
                    <input type="checkbox" id="vitamin_b2" name="vitamin_labs[]" value="vitamin_b2">
                </fieldset>
                <fieldset>
                    <legend>STI Tests</legend>
                    <label for="lab4">Gonorrhea</label>
                    <input type="checkbox" id="gonorrhea" name="sti_tests[]" value="gonorrhea">
                    <label for="lab4">Chlamydia</label>
                    <input type="checkbox" id="chlamydia" name="sti_tests[]" value="chlamydia">
                    <label for="lab4">HIV</label>
                    <input type="checkbox" id="hiv" name="sti_tests[]" value="hiv">
                    <label for="lab4">Syphilis</label>
                    <input type="checkbox" id="syphilis" name="sti_tests[]" value="syphilis">
                </fieldset>
                <label for="pregnancy">Pregnancy</label>
                <input type="checkbox" id="pregnancy" name="general_labs[]" value="pregnancy">
            </fieldset>
            <label for="diagnosis">Diagnosis:</label>
            <input type="text" id="diagnosis" name="diagnosis" required>
            <br>
            <input type="submit" value="Submit">
            <iframe name="laborderlinkdisplay"></iframe>
        </form>


        <form action="addnewmeds.php" method="post" id="adddrugtodatabase">
            <b>Add Drug to Database</b> 
            <br>
            <label for="brandname">Brand Name:</label>
            <input type="text" id="brandname" name="brandname">
            <label for="genericname">Generic Name:</label>
            <input type="text" id="genericname" name="genericname">
            <input type="submit" value="Submit">
        </form>
    </body>
</html>