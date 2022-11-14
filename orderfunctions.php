<?php
function getAddressInfo($address_id){
    $addr_sql = "SELECT street, city, state_abbr, zip FROM Addresses WHERE address_id='" . $address_id . "';";
    $addr_result = $conn->query($addr_sql);
    $row = $addr_result->fetch_assoc();
    
    $address_street = $row["street"];
    $address_city = $row["city"];
    $address_state = $row["state_abbr"];
    $address_zip = $row["zip"];
}

function getPatientInfo($patient_id){
    $pinfo_sql = "SELECT first_name, last_name, middle_name, DOB, address_id, sex FROM Patient WHERE patient_id='" . $patient_id . "';";
    $pinfo_result = $conn->query($pinfo_sql);
    $row = $pinfo_result->fetch_assoc();
    
    $firstname = $row["first_name"];
    $lastname = $row["last_name"];
    $middlename = $row["middle_name"];
    $DOB = $row["DOB"];
    $addressid = $row["address_id"];
    $sex = $row["sex"];
}

function getAllPatientInfo($patient_id){
    $patient_info_arr = array();
    $sql = "SELECT first_name, last_name, middle_name, DOB, address_id, sex FROM Patient WHERE patient_id='" . $patient_id . "';";
    $result = $conn->query($sql);
    if ($row = $result->fetch_assoc()){
        $patient_info_arr["first_name"] = $row["first_name"];
        $patient_info_arr["last_name"] = $row["last_name"];
        $patient_info_arr["middle_name"] = $row["middle_name"];
        $patient_info_arr["DOB"] = $row["DOB"];
        $patient_info_arr["first_name"] = $row["address_id"];
        $patient_info_arr["sex"] = $row["sex"];
        // Grabbing patient address data from patient address_id
        $sql = "SELECT street, city, state_abbr, zip FROM Addresses WHERE address_id='" . $address_id . "';";
        $result = $conn->query($sql);
        if ($row = $result->fetch_assoc()){
            $patient_info_arr["street"] = $row["street"];
            $patient_info_arr["city"] = $row["city"];
            $patient_info_arr["state_abbr"] = $row["state_abbr"];
            $patient_info_arr["zip"] = $row["zip"];
            
            return $patient_info_arr;
        } else {
            echo('Unable to find address info for specified address ID');
        }
    } else {
        echo('Unable to find info for specified patient');
    }
}
?>