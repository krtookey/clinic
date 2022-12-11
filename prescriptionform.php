<form action="prescription_orders.php" method="post" target="prescriptionlinkdisplay" id="prescriptionform">
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
    </script>
        <div id="patient_info">
            <?php 
                //#$orderprescription
                $user_id = $_POST['user_id'] ?? 1;
                $patient_id = $_POST['patient_id'] ?? 1;
                $idfields = <<<IDFIELDS
                <input type="text" id="patient_id" name="patient_id" value="$patient_id" hidden>
                <input type="text" id="user_id" name="user_id" value="$user_id" hidden>
                IDFIELDS;
                echo($idfields);
                //Need to get patient name and DOB from medical records views
            
                $sql = "SELECT first_name, last_name, DOB FROM Patient WHERE patient_id ='" . $patient_id . "';";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $patient_name = $row["first_name"] . ' ' . $row["last_name"];
                    $patient_dob = $row["DOB"];
                } else {
                    echo "0 results";
                }
                
                //$patient_name = "John Doe"; // PLACEHOLDER
                //$patient_dob = "10/12/1996"; // PLACEHOLDER
                
                echo("<b>". $patient_name . "</b>  <b>  " . $patient_dob . "</b>");
                echo("<br>");
                echo('<label for="pharmacy">Pharmacy: </label>');
                
                // Get pharmacy_id for patient
                $sql = "SELECT pharmacy_id FROM Patient where patient_id = '" . $patient_id . "';";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $patient_pharmacy_id = $row["pharmacy_id"]; 
                    // Get pharmacy_name for pharmacy_id
                    $sql = "SELECT pharmacy_name FROM Pharmacy where pharmacy_id = '" . $patient_pharmacy_id . "';";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0){
                        $row = $result->fetch_assoc();
                        $pharmacy_name = $row["pharmacy_name"]; 
                        $pharmacyinput = <<<PHARM_INPUT
                        <input type="text" id="pharmacy" name="pharmacy" list="pharmacy_list" value="$pharmacy_name" required>
                        PHARM_INPUT;
                        echo $pharmacyinput;
                    }
                }

            ?>
        </div>
        <datalist id="pharmacy_list">
            <?php  
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
            <input type="number" list="dosage_nums" id="dosage_num" name="dosage_num" size="10" max="10000" required>
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
            <select type="text" list="dosagetypes" id="dosage_type" name="dosage_type">
                <option value="Tablet" selected>Tablet</option>
                <option value="Capsule">Capsule</option>
                <option value="Chewable">Chewable</option>
                <option value="Liquid">Liquid</option>
                <option value="Other">Other</option>
            </select>
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
            <input type="number" id="quantity" name="quantity" value="14" max="700"> <!-- Default needs to be calculated from qtyperdose * frequency * duration -->
            <label for="refills">Refills: </label>
            <input type="number" id="refills" name="refills" value="1" max="10">
        </fieldset>

        <label for="usage_info">Usage Info and General Notes: </label>
        <br>
        <textarea rows="10" cols="40" id="usage_info" name="usage_info" maxlength="4900"></textarea>     
        <br>
        <input type="submit" value="Submit" onclick="refreshElement('prescriptionsBox');"> 
        <iframe name="prescriptionlinkdisplay" class="results_iframe"></iframe>
    </form>