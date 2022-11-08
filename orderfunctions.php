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
?>