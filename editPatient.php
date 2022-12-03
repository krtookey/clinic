<?php
    include_once 'dbConnection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <?php

    //Variables stored in POST buffer.
    if(isset($_POST['patient_id']) && $_POST['patient_id'] !== ''){
        $patient_id = $_POST['patient_id'];
    }
    if(isset($_POST['appointment_id']) && $_POST['appointment_id'] !== ''){
        $appointment_id = $_POST['appointment_id'];
    }
    if(isset($_POST['user_id']) && $_POST['user_id'] !== ''){
        $user_id = $_POST['user_id'];
    }
    $patient_id = $_POST['patient_id'] ?? ''; 
    $appointment_id = $_POST['appointment_id'] ?? '';
    $user_id = $_POST['user_id'] ?? '';
    $userPermission = 0;         //Permission Level of User.

    //Get User's Permission Level.
    if ($user_id !== '' ){
        $qstr = "SELECT DISTINCT permission FROM Users WHERE user_id = $user_id ";
        //Debug: echo $qstr;
        $qselect = $conn->prepare($qstr);
        if(! $qselect){
            echo "<p>Error: could not execute query. <br> </p>";
            echo "<pre> Error Number: " .$conn -> errno. "\n";
            echo "Error: "  .$conn -> error. "\n <pre><br>\n";
            exit;
        }
        $qselect->execute();
        $result = $qselect->get_result();
        $row = $result->fetch_row();
        $userPermission = $row[0];
        $qselect->free_result();
    }

    //Functions.

    function getAge($date){
        $n = 0;
        $birth = new DateTime($date);
        $today = new DateTime(date("Y-m-d"));
        $diff = $today->diff($birth);
        $n = $diff->y;
        return $n;
    }

    //Make sure the submitted dob contains a valid date string.
    //validateDate: makes sure that a date string contains a valid date, for the given format.
    //      $date: date string being checked.
    //      $format: format for the expected date.  Default set to 'Y-m-d'.
    //      return: returns the original date string if valid, else returns a date string for 0001-01-01.
    function validateDate($date, $format = 'Y-m-d'){
        $d = DateTime::createFromFormat($format, $date);
        if ($d && $d->format($format) === $date){
            return $date;
        } else {
            $str = '0001-01-01';
            return $str;
        }
    }

    //Variables.

    $fname = $_POST['fname'] ?? '';
    $mname = $_POST['mname'] ?? '';
    $lname = $_POST['lname'] ?? '';
    $preferred = $_POST['pname'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $sex = $_POST['sex'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $phone1 = $_POST['phone1'] ?? '';
    $phone2 = $_POST['phone2'] ?? '';
    $email = $_POST['email'] ?? '';
    $street = $_POST['street'] ?? '';
    $city = $_POST['city'] ?? '';
    $state = $_POST['state'] ?? '';
    $zip = $_POST['zip'] ?? '';
    $minor = $_POST['minor'] ?? '';
    $guardian = $_POST['guardian'] ?? '';
    $pcp = $_POST['pcp'] ?? '';
    $ecName1 = $_POST['ecName1'] ?? '';
    $ecRelationship1 = $_POST['ecRelationship1'] ?? '';
    $ecPhone1 = $_POST['ecPhone1'] ?? '';
    $ecName2 = $_POST['ecName2'] ?? '';
    $ecRelationship2 = $_POST['ecRelationship2'] ?? '';
    $ecPhone2 = $_POST['ecPhone2'] ?? '';
    $bstreet = $_POST['bstreet'] ?? '';
    $bcity = $_POST['bcity'] ?? '';
    $bstate = $_POST['bstate'] ?? '';
    $bzip = $_POST['bzip'] ?? '';
    $bill = $_POST['bill'] ?? '';
    $pharm = 0;
    $insurance = 0;
    $address = 0;
    $baddress = 0;
    $lab = 0;
    $note = 0;
    $ec1 = 0;
    $ec2 = 0;
    $tsex = '';
    $tgender = '';
    $guard_id = 0;
    $pcp_id = 0;

    //If Editing Patient, get patient information and autofill.
    if($patient_id !== ''){

        $qstr = "SELECT DISTINCT a.gender, a.preferred, a.first_name, a.middle_name, a.last_name, a.DOB, a.sex, a.primary_phone, a.secondary_phone, a.email, a.address_id, a.insurance_id, a.pharmacy_id, a.labdest_id, a.minor, a.guardian, c.user_id, c.first_name, c.last_name, a.prev_note_id, a.emergency_contact1, a.emergency_contact2  
                 FROM Patient AS a 
                 INNER JOIN Users AS c ON a.pcp_id = c.user_id
                 WHERE patient_id = $patient_id ";
        //Debug: echo $qstr;
        $qpatient = $conn->prepare($qstr);
        if(! $qpatient){
            echo "<p>Error: could not execute query. <br> </p>";
            echo "<pre> Error Number: " .$conn -> errno. "\n";
            echo "Error: "  .$conn -> error. "\n <pre><br>\n";
            exit;
        }
        $qpatient->execute();
        $result = $qpatient->get_result();
        $row = $result->fetch_row();

        $gender = $row[0];
        $preferred = $row[1];
        $fname = $row[2];
        $mname = $row[3];
        $lname = $row[4];
        $dob = $row[5];
        $sex = $row[6];
        $phone1 = $row[7];
        $phone2 = $row[8];
        $email = $row[9];
        $address = $row[10];
        $insurance = $row[11];
        $pharm = $row[12];
        $lab = $row[13];
        $minor = $row[14];
        $guard_id = $row[15];
        $pcp_id = $row[16];
        $pcp = $row[17]." ".$row[18];
        $note = $row[19];
        $ec1 = $row[20];
        $ec2 = $row[21];

        echo "<header>";
            echo "  <p>$preferred</p>
                    <p>$dob</p>";

            $age = getAge($dob);

            echo "  <p>Age: $age</p>
                    <p>$fname</p>
                    <p>$lname</p>";

            if ($sex == 'M'){
                $tsex = 'Male';
            } elseif ($sex == 'F') {
                $tsex = 'Female';
            } elseif ($sex == 'O') {
                $tsex = 'Other';
            } 
            echo "<p>$tsex</p>";

            if ($gender == 1){
                $tgender = 'He/Him';
            } elseif ($gender == 2) {
                $tgender = 'She/Her';
            } elseif ($gender == 3) {
                $tgender = 'They/Them';
            }   
            echo "<p>$tgender</p>";

        echo "  </header>
                <div class='placeholder'></div>
                <div class='fullGrid'>
                <div class='whiteCard' class='gridItem1'>
                    <h2>Edit Patient</h2>";

        $result->free_result();

        //Get Address.
        if($address !== 0){
            $qstr = "SELECT DISTINCT street, city, state_abbr, zip 
                     FROM Addresses 
                     WHERE address_id = $address ";
            $qpatient = $conn->prepare($qstr);
            if(! $qpatient){
                echo "<p>Error: could not execute query. <br> </p>";
                echo "<pre> Error Number: " .$conn -> errno. "\n";
                echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                exit;
            }
            $qpatient->execute();
            $result = $qpatient->get_result();
            $row = $result->fetch_row();

            $street = $row[0];
            $city = $row[1];
            $state = $row[2];
            $zip = $row[3];

            $result->free_result();
        }

        //Get Emergency Contacts.
        $qstr = "SELECT contact_name, relationship, phone FROM EmergencyContact WHERE contact_id = $ec1 OR contact_id = $ec2 ";
        $qselect = $conn->prepare($qstr);
        if(! $qselect){
            echo "<p>Error: could not execute query. <br> </p>";
            echo "<pre> Error Number: " .$conn -> errno. "\n";
            echo "Error: "  .$conn -> error. "\n <pre><br>\n";
            exit;
        }
        $qselect->execute();
        $qselect->store_result();
        $qselect->bind_result($name, $relationship, $phone);
        $i = 1;                            
        while($qselect->fetch()){
            if($i === 1){
                $ecName1 = $name;
                $ecRelationship1 = $relationship;
                $ecPhone1 = $phone;
            } elseif ($i === 2){
                $ecName2 = $name;
                $ecRelationship2 = $relationship;
                $ecPhone2 = $phone;
            }
            $i = $i + 1;
        }
        $qselect->free_result();

        //Get Guardian
        if(!$minor){
            $guard_id = 0;
            $guardian = '';
        } else {
            if($guard_id === $ec1){
                $guardian = $ecName1;
            } elseif ($guard_id === $ec2){
                $guardian = $ecName2;
            } else {
                $qstr = "SELECT contact_name FROM EmergencyContact WHERE contact_id = $guard_id ";
                $qselect = $conn->prepare($qstr);
                if(! $qselect){
                    echo "<p>Error: could not execute query. <br> </p>";
                    echo "<pre> Error Number: " .$conn -> errno. "\n";
                    echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                    exit;
                }
                $qselect->execute();
                $result = $qselect->get_result();
                $row = $result->fetch_row();
                $guardian = $row[0];
            }
        }

        //Get billing address
        $qstr = "SELECT bill_address FROM Billing 
                 WHERE patient_id = $patient_id 
                 ORDER BY billing_id DESC
                 LIMIT 1 ";
        $qselect = $conn->prepare($qstr);
        if(! $qselect){
            echo "<p>Error: could not execute query. <br> </p>";
            echo "<pre> Error Number: " .$conn -> errno. "\n";
            echo "Error: "  .$conn -> error. "\n <pre><br>\n";
            exit;
        }
        $qselect->execute();
        $result = $qselect->get_result();
        $row = $result->fetch_row();
        $baddress = $row[0];
        $result->free_result();

        if($baddress === $address){
            $bill = 'true';
        } else {
            $bill = 'false';
        }

        //Get billing address if not the same as mailing address.
        if($bill === 'false' && $baddress !== ''){
            $qstr = "SELECT DISTINCT street, city, state_abbr, zip 
                     FROM Addresses 
                     WHERE address_id = $baddress ";
            $qselect = $conn->prepare($qstr);
            if(! $qselect){
                echo "<p>Error: could not execute query. <br> </p>";
                echo "<pre> Error Number: " .$conn -> errno. "\n";
                echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                exit;
            }
            $qselect->execute();
            $result = $qselect->get_result();
            $row = $result->fetch_row();

            $bstreet = $row[0];
            $bcity = $row[1];
            $bstate = $row[2];
            $bzip = $row[3];

            $result->free_result();
        }    

    } else {
        echo "  <header>Edit Patient</header>
                <div class='placeholder1'></div>
                <div class='fullGrid'>
                <div class='whiteCard' class='gridItem1'>
                <h2>Edit Patient</h2>";
    } 

    //Debug: echo "<br><br><br><br><pre>"; print_r($_POST); echo "</pre>";    

    if(!isset($_POST['npSave']) || $_POST['npSave'] != 'Save'){
        $_POST['npSave'] = '';
    }
    if(isset($_POST['fname']) && $_POST['fname'] !== ''){
        $fname = $_POST['fname'];
    }
    if(isset($_POST['mname']) && $_POST['mname'] !== ''){
        $mname = $_POST['mname'];
    }
    if(isset($_POST['lname']) && $_POST['lname'] !== ''){
        $lname = $_POST['lname'];
    }
    if(isset($_POST['pname']) && $_POST['pname'] !== ''){
        $preferred = $_POST['pname'];
    }
    if(isset($_POST['dob']) && $_POST['dob'] !== ''){
        $dob = $_POST['dob'];
    }
    if(isset($_POST['sex'])){
        $sex = $_POST['sex'];
    }
    if(isset($_POST['gender'])){
        $gender = $_POST['gender'];
    }
    if(isset($_POST['phone1']) && $_POST['phone1'] !== ''){
        $phone1 = $_POST['phone1'];
    }
    if(isset($_POST['phone2']) && $_POST['phone2'] !== ''){
        $phone2 = $_POST['phone2'];
    }
    if(isset($_POST['email']) && $_POST['email'] !== ''){
        $email = $_POST['email'];
    }
    if(isset($_POST['street']) && $_POST['street'] !== ''){
        $street = $_POST['street'];
    }
    if(isset($_POST['city']) && $_POST['city'] !== ''){
        $city = $_POST['city'];
    }
    if(isset($_POST['state']) && $_POST['state'] !== ''){
        $state = $_POST['state'];
    }
    if(isset($_POST['zip']) && $_POST['zip'] !== ''){
        $zip = $_POST['zip'];
    }
    if(isset($_POST['minor']) && $_POST['minor'] !== ''){
        $minor = $_POST['minor'];
    }
    if(isset($_POST['guardian']) && $_POST['guardian'] !== ''){
        $guardian = $_POST['guardian'];
    }
    if(isset($_POST['pcp']) && $_POST['pcp'] !== ''){
        $pcp = $_POST['pcp'];
    }
    if(isset($_POST['ecName1']) && $_POST['ecName1'] !== ''){
        $ecName1 = $_POST['ecName1'];
    }
    if(isset($_POST['ecRelationship1']) && $_POST['ecRelationship1'] !== ''){
        $ecRelationship1 = $_POST['ecRelationship1'];
    }
    if(isset($_POST['ecPhone1']) && $_POST['ecPhone1'] !== ''){
        $ecPhone1 = $_POST['ecPhone1'];
    }
    if(isset($_POST['ecName2']) && $_POST['ecName2'] !== ''){
        $ecName2 = $_POST['ecName2'];
    }
    if(isset($_POST['ecRelationship2']) && $_POST['ecRelationship2'] !== ''){
        $ecRelationship2 = $_POST['ecRelationship2'];
    }
    if(isset($_POST['ecPhone2']) && $_POST['ecPhone2'] !== ''){
        $ecPhone2 = $_POST['ecPhone2'];
    }
    if(isset($_POST['bill']) && $_POST['bill'] !== ''){
        $bill = $_POST['bill'];
    }
    if(isset($_POST['bstreet']) && $_POST['bstreet'] !== ''){
        $bstreet = $_POST['bstreet'];
    }
    if(isset($_POST['bcity']) && $_POST['bcity'] !== ''){
        $bcity = $_POST['bcity'];
    }
    if(isset($_POST['bstate']) && $_POST['bstate'] !== ''){
        $bstate = $_POST['bstate'];
    }
    if(isset($_POST['bzip']) && $_POST['bzip'] !== ''){
        $bzip = $_POST['bzip'];
    }

    //Save data.
    if(isset($_POST['epSave']) && $_POST['epSave'] == 'Save'){
        if($patient_id !== ''){

            //Update Emergency Contacts.
            if($ecName1 !== '' && $ec1 !== 0){
                $qstr = "UPDATE EmergencyContact 
                        SET contact_name = ?, relationship = ?, phone = ?  
                        WHERE contact_id = $ec1 ";
                //Debug: echo $qstr;
                $qupdate = $conn->prepare($qstr);
                if(!$qupdate){
                    echo "<p>Error: could not execute query. <br> </p>";
                    echo "<pre> Error Number: " .$conn -> errno. "\n";
                    echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                    exit;
                }
                $qupdate->bind_param("sss", $ecName1, $ecRelationship1, $ecPhone1);
                $qupdate->execute();
                $qupdate->store_result();
                $qupdate->free_result();
            } else if ($ecName1 !== '' && $ec1 === 0){
                $qstr = "INSERT INTO EmergencyContact (contact_name, relationship, phone) VALUES (?, ?, ?) ";
                $qinsert = $conn->prepare($qstr);
                if(!$qinsert){
                    echo "<p>Error: could not execute query. <br> </p>";
                    echo "<pre> Error Number: " .$conn -> errno. "\n";
                    echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                    exit;
                }
                $qinsert->bind_param("sss", $ecName1, $ecRelationship1, $ecPhone1);
                $qinsert->execute();
                $qinsert->store_result();
                $qinsert->free_result();
                //Get new emergency contact_id.
                $qstr = "SELECT DISTINCT contact_id FROM EmergencyContact WHERE contact_name = '$ecName1' AND relationship = '$ecRelationship1' AND phone = '$ecPhone1' ";
                $qselect = $conn->prepare($qstr);
                if(! $qselect){
                    echo "<p>Error: could not execute query. <br> </p>";
                    echo "<pre> Error Number: " .$conn -> errno. "\n";
                    echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                    exit;
                }
                $qselect->execute();
                $result = $qselect->get_result();
                $row = $result->fetch_row();
                $ec1 = $row[0];
                $result->free_result();
            }

            if($ecName2 !== '' && $ec2 !== 0){
                $qstr = "UPDATE EmergencyContact 
                        SET contact_name = ?, relationship = ?, phone = ? 
                        WHERE contact_id = $ec2 ";
                $qupdate = $conn->prepare($qstr);
                if(!$qupdate){
                    echo "<p>Error: could not execute query. <br> </p>";
                    echo "<pre> Error Number: " .$conn -> errno. "\n";
                    echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                    exit;
                }
                $qupdate->bind_param("sss", $ecName2, $ecRelationship2, $ecPhone2);
                $qupdate->execute();
                $qupdate->store_result();
                $qupdate->free_result();
            } else if ($ecName2 !== '' && $ec2 === 0){ 
                $qstr = "INSERT INTO EmergencyContact (contact_name, relationship, phone) VALUES (?, ?, ?) ";
                $qinsert = $conn->prepare($qstr);
                if(!$qinsert){
                    echo "<p>Error: could not execute query. <br> </p>";
                    echo "<pre> Error Number: " .$conn -> errno. "\n";
                    echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                    exit;
                }
                $qinsert->bind_param("sss", $ecName2, $ecRelationship2, $ecPhone2);
                $qinsert->execute();
                $qinsert->store_result();
                $qinsert->free_result();
                //Get new emergency contact_id.
                $qstr = "SELECT DISTINCT contact_id FROM EmergencyContact WHERE contact_name = '$ecName2' AND relationship = '$ecRelationship2' AND phone = '$ecPhone2' ";
                $qselect = $conn->prepare($qstr);
                if(! $qselect){
                    echo "<p>Error: could not execute query. <br> </p>";
                    echo "<pre> Error Number: " .$conn -> errno. "\n";
                    echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                    exit;
                }
                $qselect->execute();
                $result = $qselect->get_result();
                $row = $result->fetch_row();
                $ec2 = $row[0];
                $result->free_result();
            }

           //Update Guardian.
           if(!$minor){
            $guard_id = 0;
            } else {
                if($guardian === $ecName1){
                    $guard_id = $ec1;
                } elseif ($guardian === $ecName2){
                    $guard_id = $ec2;
                } else {
                    $qstr = "UPDATE EmergencyContact 
                            SET contact_name = ?, phone = ? 
                            WHERE contact_id = $guard_id ";
                    $qupdate = $conn->prepare($qstr);
                    if(!$qupdate){
                        echo "<p>Error: could not execute query. <br> </p>";
                        echo "<pre> Error Number: " .$conn -> errno. "\n";
                        echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                        exit;
                    }
                    $qupdate->bind_param("ss", $guardian, $phone1);
                    $qupdate->execute();
                    $qupdate->store_result();
                    $qupdate->free_result();
                }
            }

            //Update Address.            
            if($street !== '' && $address !== 0){
                $qstr = "UPDATE Addresses 
                        SET street = ?, city = ?, state_abbr = ?, zip = ? 
                        WHERE address_id = $address ";
                //Debug: echo $qstr;
                $qupdate = $conn->prepare($qstr);
                if(!$qupdate){
                    echo "<p>Error: could not execute query. <br> </p>";
                    echo "<pre> Error Number: " .$conn -> errno. "\n";
                    echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                    exit;
                }
                $qupdate->bind_param("ssss", $street, $city, $state, $zip);
                $qupdate->execute();
                $qupdate->store_result();
                $qupdate->free_result();
            } else if ($street !== '' && $address === 0){
                $qstr = "INSERT INTO Addresses (street, city, state_abbr, zip) VALUES (?, ?, ?, ?) ";
                $qinsert = $conn->prepare($qstr);
                if(!$qinsert){
                    echo "<p>Error: could not execute query. <br> </p>";
                    echo "<pre> Error Number: " .$conn -> errno. "\n";
                    echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                    exit;
                }
                $qinsert->bind_param("ssss", $street, $city, $state, $zip);
                $qinsert->execute();
                $qinsert->store_result();
                $qinsert->free_result();
                //Get new address_id.
                $qstr = "SELECT DISTINCT address_id FROM Addresses WHERE street = '$street' AND city = '$city' AND state_abbr = '$state' AND zip = '$zip' ";
                $qselect = $conn->prepare($qstr);
                if(! $qselect){
                    echo "<p>Error: could not execute query. <br> </p>";
                    echo "<pre> Error Number: " .$conn -> errno. "\n";
                    echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                    exit;
                }
                $qselect->execute();
                $result = $qselect->get_result();
                $row = $result->fetch_row();
                $address = $row[0];
                $result->free_result();
            }

            //Update billing address.
            if($bill === 'false'){
                if($baddress !== $address){
                    if($bstreet !== '' && $baddress !== 0){
                        $qstr = "UPDATE Addresses 
                                SET street = ?, city = ?, state_abbr = ?, zip = ? 
                                WHERE address_id = $baddress ";
                        //Debug: echo $qstr;
                        $qupdate = $conn->prepare($qstr);
                        if(!$qupdate){
                            echo "<p>Error: could not execute query. <br> </p>";
                            echo "<pre> Error Number: " .$conn -> errno. "\n";
                            echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                            exit;
                        }
                        $qupdate->bind_param("ssss", $bstreet, $bcity, $bstate, $bzip);
                        $qupdate->execute();
                        $qupdate->store_result();
                        $qupdate->free_result();
                    } else if ($bstreet !== '' && $baddress === 0){
                        $qstr = "INSERT INTO Addresses (street, city, state_abbr, zip) VALUES (?, ?, ?, ?) ";
                        $qinsert = $conn->prepare($qstr);
                        if(!$qinsert){
                            echo "<p>Error: could not execute query. <br> </p>";
                            echo "<pre> Error Number: " .$conn -> errno. "\n";
                            echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                            exit;
                        }
                        $qinsert->bind_param("ssss", $bstreet, $bcity, $bstate, $bzip);
                        $qinsert->execute();
                        $qinsert->store_result();
                        $qinsert->free_result();
                        //Get new address_id.
                        $qstr = "SELECT DISTINCT address_id FROM Addresses WHERE street = '$bstreet' AND city = '$bcity' AND state_abbr = '$bstate' AND zip = '$bzip' ";
                        $qselect = $conn->prepare($qstr);
                        if(! $qselect){
                            echo "<p>Error: could not execute query. <br> </p>";
                            echo "<pre> Error Number: " .$conn -> errno. "\n";
                            echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                            exit;
                        }
                        $qselect->execute();
                        $result = $qselect->get_result();
                        $row = $result->fetch_row();
                        $baddress = $row[0];
                        $result->free_result();
                    } else if ($baddress === $address){
                        $qstr = "INSERT INTO Addresses (street, city, state_abbr, zip) VALUES (?, ?, ?, ?) ";
                        $qinsert = $conn->prepare($qstr);
                        if(!$qinsert){
                            echo "<p>Error: could not execute query. <br> </p>";
                            echo "<pre> Error Number: " .$conn -> errno. "\n";
                            echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                            exit;
                        }
                        $qinsert->bind_param("ssss", $bstreet, $bcity, $bstate, $bzip);
                        $qinsert->execute();
                        $qinsert->store_result();
                        $qinsert->free_result();
                        //Get new address_id.
                        $qstr = "SELECT DISTINCT address_id FROM Addresses WHERE street = '$bstreet' AND city = '$bcity' AND state_abbr = '$bstate' AND zip = '$bzip' ";
                        $qselect = $conn->prepare($qstr);
                        if(! $qselect){
                            echo "<p>Error: could not execute query. <br> </p>";
                            echo "<pre> Error Number: " .$conn -> errno. "\n";
                            echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                            exit;
                        }
                        $qselect->execute();
                        $result = $qselect->get_result();
                        $row = $result->fetch_row();
                        $baddress = $row[0];
                        $result->free_result();
                    }
                }
            } else if ($bill === 'true'){
                $baddress = $address;
            }

            $qstr = "UPDATE Billing 
                     SET bill_address = $baddress 
                     WHERE patient_id = $patient_id ";
            //Debug: echo $qstr;
            $qupdate = $conn->prepare($qstr);
            if(!$qupdate){
                echo "<p>Error: could not execute query. <br> </p>";
                echo "<pre> Error Number: " .$conn -> errno. "\n";
                echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                exit;
            }
            $qupdate->execute();
            $qupdate->store_result();
            $qupdate->free_result();

            //Change Primary Care Provider.
            if($pcp !== ''){
                $tpcp = explode(" ", $pcp, 2); //Problematic if user's first name has any spaces. 
                $pcp_first = $tpcp[0];
                $pcp_last = $tpcp[1];

                //Debug: echo "<br><br><br><br><br> first name = $pcp_first, last name = $pcp_last";
                $qstr = "SELECT DISTINCT user_id FROM Users WHERE first_name = '$pcp_first' AND last_name = '$pcp_last' ";
                $qselect = $conn->prepare($qstr);
                if(! $qselect){
                    echo "<p>Error: could not execute query. <br> </p>";
                    echo "<pre> Error Number: " .$conn -> errno. "\n";
                    echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                    exit;
                }
                $qselect->execute();
                $result = $qselect->get_result();
                $row = $result->fetch_row();
                $pcp_id = $row[0];
                $result->free_result();
            }

            $dob = validateDate($dob);

            //Update Patient.
            $qstr = "UPDATE Patient 
                    SET gender = $gender, preferred = ?, first_name = ?, middle_name = ? , last_name = ? , DOB = '$dob', sex = '$sex', primary_phone = ? , secondary_phone = ? , email = ? , address_id = $address, insurance_id = $insurance, pharmacy_id = $pharm, labdest_id = $lab, pcp_id = $pcp_id, minor = $minor, guardian = $guard_id, emergency_contact1 = $ec1, emergency_contact2 = $ec2
                    WHERE patient_id = $patient_id ";
            //Debug: echo $qstr;
            $qupdate = $conn->prepare($qstr);
            if(!$qupdate){
                echo "<p>Error: could not execute query. <br> </p>";
                echo "<pre> Error Number: " .$conn -> errno. "\n";
                echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                exit;
            }
            $qupdate->bind_param("sssssss", $preferred, $fname, $mname, $lname, $phone1, $phone2, $email);
            $qupdate->execute();
            $qupdate->store_result();
            $qupdate->free_result();
        }
    }

    ?>                    

    <form action="./editPatient.php" method="post" id="editForm">
        <div class="newPGrid">
            <div class="newPItem">
                <div class="newItem">
                <label>First Name:</label>
                    <input type="text" name="fname" maxlength="30" value="<?php echo $fname; ?>">
                </div>
                <div class="newItem">
                    <label>Middle Name:</label>
                    <input type="text" name="mname" maxlength="30" value="<?php echo $mname; ?>">
                </div>
                <div class="newItem">
                <label>Last Name:</label>
                    <input type="text" name="lname" maxlength="30" value="<?php echo $lname; ?>">
                </div>
                <div class="newItem">
                    <label>Preferred Name:</label>
                    <input type="text" name="pname" maxlength="30" value="<?php echo $preferred; ?>">
                </div>
                <div class="newItem">
                <label>Date of Birth:</label>
                    <input type="text" placeholder="YYYY-MM-DD" name="dob" value="<?php echo $dob; ?>">
                </div>
            </div>
            <div class="newPItem">
                <h3>Address</h3>
                <div class="newItem">
                    <label>Street:</label>
                    <input type="text" maxlength="40" name="street" value="<?php echo $street ; ?>">
                </div>
                <div class="newItem">
                    <label>City:</label>
                    <input type="text" maxlength="30" name="city" value="<?php echo $city ; ?>">
                </div>
                <div class="newItem">
                    <label>State:</label>
                    <input type="text" maxlength="2" placeholder="VT" name="state" value="<?php echo $state ; ?>">
                </div>
                <div class="newItem">
                    <label>Zip Code:</label>
                    <input type="text" maxlength="5" placeholder="00000" name="zip" value="<?php echo $zip ; ?>">
                    </div>
            </div>
            <div class="newPItem">
                <fieldset>
                    <legend>Sex:</legend>
                    <input type="radio" id="female" name="sex" value="F" 
                        <?php if($sex == 'F') echo "checked"; ?>>
                    <label for="female">Female</label>        
                    <input type="radio" id="male" name="sex" value="M"
                        <?php if($sex == 'M') echo "checked"; ?>>
                    <label for="male">Male</label>
                    <input type="radio" id="other" name="sex" value="O"
                        <?php if($sex == 'O') echo "checked"; ?>>
                    <label for="other">Other</label>
                </fieldset>
            </div>
            <div class="newPItem">
                <fieldset>
                    <legend>Gender:</legend>
                    <input type="radio" id="sheher" name="gender" value="2"
                        <?php if($gender == '2') echo "checked"; ?>>
                    <label for="sheher">She/Her</label>        
                    <input type="radio" id="hehim" name="gender" value="1"
                        <?php if($gender == '1') echo "checked"; ?>>
                    <label for="hehim">He/Him</label>
                    <input type="radio" id="theythem" name="gender" value="3"
                        <?php if($gender == '3') echo "checked"; ?>>
                    <label for="theythem">They/Them</label>
                </fieldset>
            </div>
            <div class="newPItem">
                <fieldset>
                    <legend>Minor:</legend>
                    <input type="radio" id="yes" name="minor" value="true"
                        <?php if($minor == 'true') echo "checked"; ?>>
                    <label for="yes">Yes</label>        
                    <input type="radio" id="no" name="minor" value="false"
                        <?php if($minor == 'false') echo "checked"; ?>>
                    <label for="no">No</label>
                </fieldset>
                <div class="newItem">
                    <label>Guardian:</label>
                    <input type="text" maxlength="50" name="guardian" value="<?php if($minor == 'true'){ echo $guardian; } ?>">
                </div>
            </div>
            <div class="newPItem">
                <label>Principal Care Provider:</label>
                <input list="pcplist" maxlength="50" name="pcp" value="<?php echo $pcp ?>">
                <datalist id="pcplist" name="pcp">
                    <?php
                        //Get List of Primary Care Providers.
                        $qstr = "SELECT first_name, last_name FROM Users WHERE permission = 2 ";
                        $qselect = $conn->prepare($qstr);
                        if(! $qselect){
                            echo "<p>Error: could not execute query. <br> </p>";
                            echo "<pre> Error Number: " .$conn -> errno. "\n";
                            echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                            exit;
                        }
                        $qselect->execute();
                        $qselect->store_result();
                        $qselect->bind_result($first, $last);

                        while($qselect->fetch()){
                            echo "<option value='$first $last'>$first $last<option>";
                        }

                        $qselect->free_result();
                    ?>
                </datalist>
            </div>
            <div class="newPItem">
                <div class="newItem">
                    <label>Primary Phone Number:</label>
                    <input type="text" placeholder="000-000-0000" maxlength="20" name="phone1" value="<?php echo $phone1; ?>">
                </div>
                <div class="newItem">
                    <label>Secondary Phone Number:</label>
                    <input type="text" placeholder="000-000-0000" maxlength="20" name="phone2" value="<?php echo $phone2; ?>">
                </div>
                <div class="newItem">
                    <label>Email:</label>
                    <input type="text" maxlength="40" name="email" value="<?php echo $email; ?>">
                </div>
            </div>
            <div class="newPItem"></div>
            <div class="newPItem">
                <h3>Emegency Contact 1</h3>
                <div class="newItem">
                    <label>Name:</label>
                    <input type="text" maxlength="50" name="ecName1" value="<?php echo $ecName1; ?>">
                </div>
                <div class="newItem">
                    <label>Relationship:</label>
                    <input type="text" maxlength="50" name="ecRelationship1" value="<?php echo $ecRelationship1; ?>">
                </div>
                <div class="newItem">
                    <label>Phone Number:</label>
                    <input type="text" placeholder="000-000-0000" maxlength="20" name="ecPhone1" value="<?php echo $ecPhone1; ?>">
                </div>
            </div>
            <div class="newPItem">
                <h3>Emegency Contact 2</h3>
                <div class="newItem">
                    <label>Name:</label>
                    <input type="text" maxlength="50" name="ecName2" value="<?php echo $ecName2; ?>">
                </div>
                <div class="newItem">
                    <label>Relationship:</label>
                    <input type="text" maxlength="50" name="ecRelationship2" value="<?php echo $ecRelationship2; ?>">
                </div>
                <div class="newItem">
                    <label>Phone Number:</label>
                    <input type="text" placeholder="000-000-0000" maxlength="20" name="ecPhone2" value="<?php echo $ecPhone2; ?>">
                </div>
            </div>
            <div class="newPItem">
                <h3>Billing Address</h3>
                <div class="newItem">
                    <fieldset>
                        <legend>Same as Home Address:</legend>
                        <input type="radio" id="yes" name="bill" value="true"
                            <?php if($bill == 'true') echo "checked"; ?>>
                        <label for="yes">Yes</label>        
                        <input type="radio" id="no" name="bill" value="false"
                            <?php if($bill == 'false') echo "checked"; ?>>
                        <label for="no">No</label>
                    </fieldset>
                </div>
                <p>If not home address, please, enter the billing address below:</p>
                <div class="newItem">
                    <label>Street:</label>
                    <input type='text' maxlength='40' name='bstreet' value="<?php if($bill === 'false'){ echo $bstreet; } else { echo $street; } ?>" >
                </div>
                <div class="newItem">
                    <label>City:</label>
                    <input type='text' maxlength='30' name='bcity' value="<?php if($bill === 'false'){ echo $bcity; } else { echo $city; } ?>">
                </div>
                <div class="newItem">
                    <label>State:</label>
                    <input type='text' maxlength='2' placeholder='VT' name='bstate' value="<?php if($bill === 'false'){ echo $bstate; } else { echo $state; } ?>">
                </div>
                <div class="newItem">
                    <label>Zip Code:</label>
                    <input type='text' maxlength='5' placeholder='00000' name='bzip' value="<?php if($bill === 'false'){ echo $bzip; } else { echo $zip; } ?>">
                </div>
            </div>
        </div>
        <div class="saveButton">
            <input type="submit" name="epSave" value="Save">
        </div>
            <?php echo "<input type='hidden' name='patient_id' value='$patient_id'>
                        <input type='hidden' name='appointment_id' value='$appointment_id'>
                        <input type='hidden' name='user_id' value='$user_id'>"; ?>
        </form>
    </div>
    <div class="whiteCard" class='gridItem2'>
        <h2>Insurance Information</h2>
        <?php
            if(!isset($_POST['iSave']) || $_POST['iSave'] != 'Save'){
                $_POST['iSave'] = '';
            }
            if(isset($_POST['iName']) && $_POST['iName'] !== ''){
                $iName = $_POST['iName'];
            }
            if(isset($_POST['policyName']) && $_POST['policyName'] !== ''){
                $policyName = $_POST['policyName'];
            }
            if(isset($_POST['policyNum']) && $_POST['policyNum'] !== ''){
                $policyNum = $_POST['policyNum'];
            }
            $iName = $_POST['iName'] ?? 'Blue Cross Blue Shield';
            $policyName = $_POST['policyName'] ?? $fname.' '.$lname;
            $policyNum = $_POST['policyNum'] ?? '880-65-9090-444';
        ?>
        <iframe style="display: none; " name='insuranceForm'></iframe>
        <form action="./editPatient.php" method="post" target='insuranceForm'>
        <div class="npItem">
             <p>Insurance Company Name:</p>
             <input type="text" maxlength="50" name="iName" value="<?php echo $iName; ?>">
             <p>Policy Holder's Name:</p>
             <input type="text" maxlength="50" name="policyName" value="<?php echo $policyName; ?>">
             <p>Policy Number:</p>
             <input type="text" maxlength="50" name="policyNum" value="<?php echo $policyNum; ?>">    
        </div>
        <div class="saveButton">
            <input type="submit" name="iSave" value="Save">
        </div>
            <?php echo "<input type='hidden' name='patient_id' value='$patient_id'>
                        <input type='hidden' name='appointment_id' value='$appointment_id'>
                        <input type='hidden' name='user_id' value='$user_id'>"; ?>
        </form>
    </div>
    <div class="whiteCard" class='gridItem3'>
        <?php
            $paid_count = 0;            
            if(!isset($_POST['bSave']) || $_POST['bSave'] != 'Update'){
                $_POST['bSave'] = '';
            }
            if(isset($_POST['paid'])){
                $paid = $_POST['paid'];
                $paid_count = count($paid);
            }
            $paid = $_POST['paid'] ?? '';

            if(isset($_POST['bSave']) && $_POST['bSave'] == 'Update'){
                $qstr = "UPDATE Billing 
                         SET paid = 0
                         WHERE patient_id = $patient_id ";
                //Debug: echo $qstr;
                $qupdate = $conn->prepare($qstr);
                if(!$qupdate){
                    echo "<p>Error: could not execute query. <br> </p>";
                    echo "<pre> Error Number: " .$conn -> errno. "\n";
                    echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                    exit;
                }
                $qupdate->execute();
                $qupdate->store_result();
                $qupdate->free_result();

                for($i = 0; $i < $paid_count; $i++){
                    $qstr = "UPDATE Billing 
                             SET paid = 1
                             WHERE appointment_id = $paid[$i] ";
                    //Debug: echo $qstr;
                    $qupdate = $conn->prepare($qstr);
                    if(!$qupdate){
                        echo "<p>Error: could not execute query. <br> </p>";
                        echo "<pre> Error Number: " .$conn -> errno. "\n";
                        echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                        exit;
                    }
                    $qupdate->execute();
                    $qupdate->store_result();
                    $qupdate->free_result();
                }
            }
        ?> 
        <h2>Billing Information</h2>
        <iframe style="display: none; " name='billForm'></iframe>
        <form action="./editPatient.php" method="post">
        <div>
        <?php
            $fp = fopen("./bills/".$patient_id.".txt", 'w') or die("Unable to open billing file!");
            flock($fp, LOCK_SH);
            $now = date("Y/m/d h:i a");
            $txt="Clinic Bill for ".$fname." ".$lname." as of ".$now."\n \n";
            fwrite($fp, $txt);
            $total = 0;

            $qstr = "SELECT a.bill_statement, a.amount_due, a.paid, a.appointment_id
                     FROM Billing AS a 
                     WHERE patient_id = $patient_id AND appointment_id != 0";
            $qselect = $conn->prepare($qstr);
            if(! $qselect){
                echo "<p>Error: could not execute query. <br> </p>";
                echo "<pre> Error Number: " .$conn -> errno. "\n";
                echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                exit;
            }
            $qselect->execute();
            $qselect->bind_result($bill_statement, $amount_due, $spaid, $app_id);
            $qselect->store_result();

            echo "<table class='tableBill' >
                    <thead>
                        <tr>
                            <th scope='col'>Date</th>
                            <th scope='col'>Duration</th>
                            <th scope='col'>Reason For Visit</th>
                            <th scope='col'>Doctor</th>
                            <th scope='col'>Amount Due</th>
                            <th scope='col'>Paid</th>
                        </tr>
                    </thead>
                    <tbody>";
            $i = 0;
            while($qselect->fetch()){

                $qstr = "SELECT date_time, duration, doctor_first_name, doctor_last_name
                         FROM Appointment 
                         WHERE appointment_id = $app_id ";
                $q = $conn->prepare($qstr);
                if(! $q){
                    echo "<p>Error: could not execute query. <br> </p>";
                    echo "<pre> Error Number: " .$conn -> errno. "\n";
                    echo "Error: "  .$conn -> error. "\n <pre><br>\n";
                    exit;
                }
                $q->execute();
                $result = $q->get_result();
                $row = $result->fetch_row();
                $date = $row[0];
                $duration = $row[1]." minute";
                $doctor = $row[2]." ".$row[3];
                $result->free_result();

                $str = strtotime($date);
                $date = date("Y/m/d h:i A", $str);

                echo "<tr>
                        <td>";
                            echo $date;
                echo "  </td>
                        <td>";
                            echo $duration;
                echo "  </td>
                        <td>";
                            echo $bill_statement;
                echo "  </td>
                        <td>";
                            echo $doctor;
                echo "  </td>
                        <td>";
                            echo "\$ ".$amount_due;
                echo "  </td>
                        <td>
                            <input type='checkbox' name='paid[]' value={$app_id} ";
                            if($spaid === 1){
                                echo "checked";
                                $n = '';
                            } else {
                                $total = $total + $amount_due;
                                $n = " NOT";
                            }
                echo        "> 
                        </td>
                    </tr>";
                $i = $i + 1;
                $txt = "On ".$date.", ".$fname." was seen by ".$doctor." for a ".$duration." appointment concerning: ".$bill_statement.". \n \nThe final bill came to \$ ".$amount_due.", and has".$n." been paid. \n \n \n";
                fwrite($fp, $txt);
            }
            $qselect->free_result();
            echo "  </tbody>
                    </table>";
            $txt = "Total amount due: \$ ".$total."\n \n";
            fwrite($fp, $txt);
            flock($fp, LOCK_UN); 
            fclose($fp)
        ?>
            </div>
            <div id="totalStr">
                <h3>Total Due: $ <?php echo $total; ?></h3>
            </div>
            <div class="saveButton">
                <input type="submit" name="bSave" value="Update">
                <a href="./bills/<?php echo $patient_id; ?>.txt" download>Download</a>
            </div>

            <?php echo "<input type='hidden' name='patient_id' value='$patient_id'>
                        <input type='hidden' name='appointment_id' value='$appointment_id'>
                        <input type='hidden' name='user_id' value='$user_id'> ";
            ?>
        </div>
        </form>
    </div>
    <footer>
        <div>
            <form action="./index.php" method="POST">
                <input type="submit" name="submitI" value="Home">
                <?php
                    //Store patient_id, appointment_id, and user_id in POST buffer.
                    echo "  <input type='hidden' name='patient_id' value='$patient_id'>
                            <input type='hidden' name='appointment_id' value='$appointment_id'>
                            <input type='hidden' name='user_id' value='$user_id'>";
                ?>
            </form>
        </div>
    </footer>
    <?php
        $conn->close();
    ?>
</body>
</html>