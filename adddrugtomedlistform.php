<form action="addmedtolist.php" method="post" target="adddrugtomedlistframe" id="adddrugtomedlistform">
    <b>Add Med To Medlist</b>
    <br>
    <?php
        $patient_id = $POST['patient_id'] ?? 1;
        $idfields = <<<IDFIELDS
        <input type="text" id="patient_id" name="patient_id" value="$patient_id" hidden>
        IDFIELDS;
        echo($idfields);
    ?>
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
    <br>
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
    <label for="taking">Taking?</label>
    <input type="checkbox" id="taking" name="taking" value="1">
    <br>
    <input type="submit" value="Submit" onclick="refreshElement('labResultsBox');">
    <iframe name="adddrugtomedlistframe" class="results_iframe"></iframe>
</form>