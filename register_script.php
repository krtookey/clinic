<?php
include_once "dbConnection.php";
include_once 'testinput.php';
//echo("We are in the fun zone");
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$uname = $_POST['uname'];
$title = $_POST['title'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$permlevel = '3';
$password=$_POST['password'];
$query = <<<INSERTUSER
INSERT INTO users (user_name, permission, job_title, phone, email, first_name, last_name, pwd) 
VALUES ("$uname", "$permlevel", "$title", "$phone", "$email", "$fname", "$lname", "$password");
INSERTUSER;
if($conn->query($query) === TRUE){
    echo("User successfully created");
} else {
    echo("The data was not inserted into the database correctly. Please contact an administrator.");
}
?>