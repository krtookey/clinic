<?php
// Assigning form items to PHP variables that we can use 

$pharmacy = $_POST["pharmacy"];
$drugname = $_POST["drugname"];
$dosage = $_POST["dosage_num"] . $_POST["unit"];
$route = $_POST["route"];
$usage_details = $_POST["qtyperdose"] . " " . $_POST["frequency"] . " " . $_POST["duration"];
$quantity = $_POST["quantity"];
$quantity_calc = (floatval($_POST["qtyperdose"])*floatval($_POST["frequency"])*intval($_POST["duration"]));
echo("qtyperdose as int: " . floatval($_POST["qtyperdose"]));
$refills = $_POST["refills"];
$usage_info = $_POST["usage_info"];

echo ("pharmacy " . $pharmacy . "<br>" . "drugname " . $drugname . "<br>" . "dosage " . $dosage . "<br>" . "route " . $route . "<br>" . "usage_details " .$usage_details . "<br>" . "quantity " . $quantity . "<br>" . "quantity_calc " . $quantity_calc . "<br>" . "refills " . $refills . "<br>" . "Usageinfo " . $usage_info);

// Validation of results
if ($quantity != intval($quantity_calc)){
    echo("This is not what is supposed to happen. Oops. <br>");
}

// Getting medication ID for drugname
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

$sql = "SELECT medication_id FROM DrugList WHERE medication_name='" . $drugname . "' OR generic_name='" . $drugname . "';";
$result = $conn->query($sql);

if ($result->num_rows == 1){
    $drug_id = $row["medication_id"];
} else if ($result->num_rows > 1){
    // How will we handle if there is more than 1 drug with a certain name?
    echo("This is not good. There is more than 1 drug with the same name. Consider fixing that!");
} else {
    // If the drug name isn't in the database, how will we add it? Should we prompt the user first? How would we do that?

}
$conn->close();


// Getting everything ready to be sent



// Sending the data to the pharmacy





// Adding the data into the Prescriptions table
$sql_scrip_into_database = "INSERT INTO Prescriptions () VALUES ();";

?>