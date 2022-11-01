<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Patient</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <header>
        <p>New Patient</p>
    </header>
    <div class="placeholder"></div>
    <div class="whiteCard">
        <h2>New Patient</h2>
        <form action="POST" id="newPForm">
        <div id="newPGrid">
            <div class="newPItem">
                <label>First Name:</label>
                <input type="text">
            </div>
            <div class="newPItem">
                <label>Middle Name:</label>
                <input type="text">
            </div>
            <div class="newPItem">
                <label>Last Name:</label>
                <input type="text">
            </div>
            <div class="newPItem">
                <label>Date of Birth:</label>
                <input type="text" value="MM/DD/YYYY">
            </div>
            <div class="newPItem">
                <p>Sex:</p>
                <input type="radio" id="female" name="sex" value="f">
                <label for="female">Female</label>        
                <input type="radio" id="male" name="sex" value="m">
                <label for="male">Male</label>
                <input type="radio" id="other" name="sex" value="o">
                <label for="other">Other</label>
            </div>
            <div class="newPItem">
                <p>Gender:</p>
                <input type="radio" id="sheher" name="gender" value="she">
                <label for="sheher">She/Her</label>        
                <input type="radio" id="hehim" name="gender" value="he">
                <label for="hehim">He/Him</label>
                <input type="radio" id="theythem" name="gender" value="they">
                <label for="theythem">They/Them</label>
            </div>
            <div class="newPItem">
                <label>Primary Phone Number:</label>
                <input type="text" value="000-000-0000">
            </div>
            <div class="newPItem">
                <label>Secondary Phone Number:</label>
                <input type="text" value="000-000-0000">
            </div>
            <div class="newPItem">
                <label>Email:</label>
                <input type="text">
            </div>
            <div class="newPItem">
                <p>Address:</p>
                <label>Street:</label>
                <input type="text">
                <label>City:</label>
                <input type="text">
                <label>State:</label>
                <input type="text" value="VT">
                <label>Zip Code:</label>
                <input type="text" value="00000">
            </div>
            <div class="newPItem">
                <p>Minor:</p>
                <input type="radio" id="yes" name="minor" value="yes">
                <label for="yes">Yes</label>        
                <input type="radio" id="no" name="minor" value="no">
                <label for="no">No</label>
                <label>Gardian:</label>
                <input type="text">
            </div>
            <div class="newPItem">
                <label>Principal Care Provider:</label>
                <input type="text">
            </div>
            <div class="newPItem">
                <p>Emegency Contact 1:</p>
                <label>Name:</label>
                <input type="text">
                <label>Relationship:</label>
                <input type="text">
                <label>Phone Number:</label>
                <input type="text">
            </div>
            <div class="newPItem">
                <p>Emegency Contact 2:</p>
                <label>Name:</label>
                <input type="text">
                <label>Relationship:</label>
                <input type="text">
                <label>Phone Number:</label>
                <input type="text">
            </div>
        </div>
        <div class="saveButton">
            <input type="submit" value="Add Patient">
        </div>
        </form>
    </div>
    <div class="whiteCard">
        <a href="insurence">Insurance Information</a> 
        <a href="billing">Billing Information</a>
        <a href="pharmacy">Pharmacy Information</a>
        <a href="appointments">Appointments</a>
        <a href="./index.php">Home</a>
    </div>
</body>
</html>