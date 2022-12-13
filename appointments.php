<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="./style.css">
</head>

<?php 
include_once 'dbConnection.php'; 
include_once 'testinput.php';
include "appointmentsform.php";
//include "appointmentsview.php";
?>

<footer>
    <div>
        <form name="appthome" action="./index.php" method="POST">
            <input type="submit" name="submitI" value="Home">
            <?php
                echo "  <input type='hidden' name='patient_id' value='$patient_id'>
                        <input type='hidden' name='user_id' value='$user_id'>";
            ?>
        </form>
    </div>
</footer>