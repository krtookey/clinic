<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <header>
        <p> John Smith </p>
        <p> DOB:  7/23/1990 </p> 
        <p> 32y <p>
        <p> Male </p>
    </header>
    <div class="placeholder"></div>
    <div class="whiteCard">
        <h2>Patient Search</h2>
        <form id="searchForm">
            <div id="searchGrid">
                <div class="searchItem">
                    <label>First Name:</label>
                    <input type="text">
                </div>
                <div class="searchItem">
                    <label>Last Name:</label>
                    <input type="text">
                </div>
                <div class="searchItem">
                    <label>Date of Birth:</label>
                    <input type="text" value="MM/DD/YYYY">
                </div>
            </div>
            <div class="saveButton">
                <input type="submit" value="Search" id="searchButton">
            </div>
        </form>
    </div>
    <div class="whiteCard">
        <a href="appointments">Appointments</a> 
        <a href="./medicalProfile.php">Medical Profile</a>
        <a href="./patient.php">Patient Note</a>
        <a href="./noteHistory.php">Note History</a>
    </div>
    <footer>
        <a href="new user">New User</a>
        <a href="./newPatient.php">New Patient</a>
    </footer>
</body>
</html>
