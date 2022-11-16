<?php
    include_once 'dbConnection.php';
    
        //Variables stored in POST buffer.
        if(isset($_POST['patient_id']) && $_POST['patient_id'] !== ''){
            $patient_id = $_POST['patient_id'];
        }
        $patient_id = $_POST['patient_id'] ?? ''; 
    
        echo "<div class='whiteCard'>
                <h2>Family History</h2> <br> 
  
                    <div id='familyHistory'>";
                        //fetch family history.
                        if(!isset($_POST['histEdit']) || $_POST['histEdit'] != 'Add'){
                            $_POST['histEdit'] = '';
                        }
                        if(!isset($_POST['histSave']) || $_POST['histSave'] != 'Save'){
                            $_POST['histSave'] = '';
                        }
                        if(!isset($_POST['memberDelete']) || $_POST['memberDelete'] != 'Delete'){
                            $_POST['memberDelete'] = '';
                        }
                        if(isset($_POST['relationship']) && $_POST['relationship'] != ''){
                            $family = $_POST['relationship'];
                        }
                        if(isset($_POST['conditionBox']) && $_POST['conditionBox'] != ''){
                            $conditionBox = $_POST['conditionBox'];
                        }
                        $family = $_POST['relationship'] ?? '';
                        $conditionBox = $_POST['conditionBox'] ?? '';
                        $num = 0;                                   //Array index for $members and $hists.
                        $members = [];                              //Array of family members.
                        $hists = [];                                //Array of conditions.
                        $present = 'false';                         //If family member is already part of the family history, present == true.

                        if(isset($_POST['patient_id']) && $patient_id !== ''){

                            $qstr = "SELECT relationship, condit FROM FamilyHistory WHERE patient_id = $patient_id ";
                            $qselect = $conn->prepare($qstr);
                            if(!$qselect){
                                echo "<p>Error: could not execute query. <br> </p>";
                                echo "<pre> Error Number: " .$conn -> errno. "\n";
                                echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                                exit;
                            }
                            $qselect->execute();
                            $qselect->store_result();
                            $qselect->bind_result($relationship, $condition);
                            
                            while($qselect->fetch()){
                                $members[$num] = $relationship;
                                $hists[$num] = $condition;
                                $num = $num + 1;   
                            }

                            $i = 0;
                            while($i < $num){
                                echo    "<div class='familyMember'>
                                            <h4>$members[$i]:</h4>
                                            <p>$hists[$i]</p>
                                        </div>";
                                $i = $i + 1;
                            } 

                            $qselect->free_result();

                            if(isset($_POST['histEdit']) && $_POST['histEdit'] == 'Add'){                           
                                $original = '';
                                $i = 0;
                                while($i < $num){
                                    if($members[$i] == $family){
                                        $original = $hists[$i];
                                    }
                                    $i = $i + 1;
                                }
                                $original = $original." ".$conditionBox;
                                $qstr = "   UPDATE FamilyHistory 
                                            SET condit = ? 
                                            WHERE relationship = '$family' AND patient_id = $patient_id ";
                                $qupdate = $conn->prepare($qstr);
                                if(!$qupdate){
                                    echo "<p>Error: could not execute query. <br> </p>";
                                    echo "<pre> Error Number: " .$conn -> errno. "\n";
                                    echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                                    exit;
                                }
                                $qupdate->bind_param("s", $original);
                                $qupdate->execute();
                                $qupdate->store_result();
                                $qupdate->free_result();
                            }


                            if(isset($_POST['histSave']) && $_POST['histSave'] == 'Save'){

                                $i = 0;
                                while($i < $num && $present != 'true'){
                                    if($members[$i] == $family){
                                        $present = 'true';
                                    } else {
                                        $present = 'false';
                                    }
                                    $i = $i + 1;
                                } 

                                if($present == 'false'){
                                    $qstr = "INSERT INTO FamilyHistory (patient_id, relationship, condit) VALUES ($patient_id, ?, ? ) ";
                                    //Debug: echo "<br>$qstr";
                                    $qinsert = $conn->prepare($qstr);
                                    if(!$qinsert){
                                        echo "<p>Error: could not execute query. <br> </p>";
                                        echo "<pre> Error Number: " .$conn -> errno. "\n";
                                        echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                                        exit;
                                    }
                                    $qinsert->bind_param("ss", $family, $conditionBox);
                                    $qinsert->execute();
                                    $qinsert->store_result();
                                    $qinsert->free_result();
                                } else {

                                    $qstr = "UPDATE FamilyHistory 
                                             SET condit = ? 
                                             WHERE relationship = '$family' AND patient_id = $patient_id ";
                                    $qupdate = $conn->prepare($qstr);
                                    if(!$qupdate){
                                        echo "<p>Error: could not execute query. <br> </p>";
                                        echo "<pre> Error Number: " .$conn -> errno. "\n";
                                        echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                                        exit;
                                    }
                                    $qupdate->bind_param("s", $conditionBox);
                                    $qupdate->execute();
                                    $qupdate->store_result();
                                    $qupdate->free_result();
                                } 
                            }
                            if(isset($_POST['memberDelete']) && $_POST['memberDelete'] == 'Delete'){
                                $present = 'false';
                                $i = 0;
                                while($i < $num && $present != 'true'){
                                    if($members[$i] == $family){
                                        $present = 'true';
                                    } else {
                                        $present = 'false';
                                    }
                                    $i = $i + 1;
                                }
                                if($present == 'true'){
                                    $qstr = "DELETE FROM FamilyHistory WHERE patient_id = $patient_id AND relationship = ? ";
                                    $qdelete = $conn->prepare($qstr);
                                    if(!$qdelete){
                                        echo "<p>Error: could not execute query. <br> </p>";
                                        echo "<pre> Error Number: " .$conn -> errno. "\n";
                                        echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                                        exit;
                                    }
                                    $qdelete->bind_param("s", $family);
                                    $qdelete->execute();
                                    $qdelete->store_result();
                                    $qdelete->free_result();
                                }
                            }     
                        }

            //Debug: echo "<pre>"; print_r($_POST); echo" </pre>";
        echo "      </div>
            <form action='./familyHistory.php' method='post' target='historyFrame'>
               <div class='dropBox'>
                    <label for='famliy'>Relationship:</label>
                    <input list='family' maxlength='29' name='relationship' value='$family'>
                    <datalist name='relationship' id='family' >
                        <option value='Mother'>Mother</option>
                        <option value='Father'>Father</option>
                        <option value='Sister'>Sister</option>
                        <option value='Brother'>Brother</option>
                        <option value='Maternal Half-Sister'>Maternal Half-Sister</option>
                        <option value='Maternal Half-Brother'>Maternal Half-Brother</option>
                        <option value='Paternal Half-Sister'>Paternal Half-Sister</option>
                        <option value='Paternal Half-Brother'>Paternal Half-Brother</option>
                        <option value='Maternal Grandfather'>Maternal Grandfather</option>
                        <option value='Maternal Grandmother'>Maternal Grandmother</option>
                        <option value='Paternal Grandfather'>Paternal Grandfather</option>
                        <option value='Paternal Grandmother'>Paternal Grandmother</option>
                        <option value='Maternal Aunt'>Maternal Aunt</option>
                        <option value='Paternal Aunt'>Paternal Aunt</option>
                        <option value='Maternal Uncle'>Maternal Uncle</option>
                        <option value='Paternal Uncle'>Paternal Uncle</option>
                    </datalist>
                </div><br>
                <div class='commentBox'>
                    <label>Conditions: </label> <br>
                    <textarea name='conditionBox' maxlength='5999'> $conditionBox </textarea>
                    </div> <br>
                <div class='saveButton'> 
                    <input type='submit' value='Add' name='histEdit' id='histEdit'>
                    <input type='submit' value='Save' name='histSave' id='histSave' >
                    <input type='submit' value='Delete' name='memberDelete' id='memberDelete' >
                </div>
                <input type='hidden' name='patient_id' value='$patient_id'>
            </form> 
            <iframe style='display: none;' name='historyFrame'></iframe>
        </div>";
    ?>
    <?php
        //$conn->close();
    ?>

</body>
</html>
