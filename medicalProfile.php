<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Profile</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <?php
        //Variables stored in POST buffer.
        $patient_id = $_POST['patient_id'] ?? "0";
        $preferred = $_POST['preferred'] ?? '';
        $dobirth = $_POST['dob'] ?? '';
        $age = $_POST['age'] ?? '';
        $fname = $_POST['firstname'] ?? '';
        $lname = $_POST['lastname'] ?? '';
        $sex = $_POST['sex'] ?? '';
        $gender = $_POST['gender'] ?? '';

        //Populate Header
        echo "  <header>
                <p>$preferred</p>
                <p>$dobirth</p>
                <p>Age: $age</p>
                <p>$fname</p>
                <p>$lname</p>
                <p>$sex</p>
                <p>$gender</p>
                </header>";
    ?>
    <div class="placeholder"></div>
    <div class="whiteCard">
        <h2>Medical Profile</h2> <br>
        <form id="profileForm">
            <div id="profileGrid">
                <div class="profileItem">
                    <label>Weight:</label>
                    <input type="text">
                </div>
                <div class="profileItem">
                    <label>Height:</label>
                    <input type="text">
                </div>
                <div class="profileItem">
                    <label>BMI:</label>
                    <input type="text">
                </div>
                <div class="profileItem">
                    <label>Blood Pressure:</label> 
                    <input type="text">
                </div>
                <div class="profileItem">
                    <label>Pulse:</label>
                    <input type="text">
                </div>
                <div class="profileItem">
                    <label>Pulse OX:</label>
                    <input type="text">
                </div>
            </div>
            <div class="saveButton">
                <input type="submit" value="Save" id="ProfileSave">
            </div>
        </form>
    </div>
    <div class="whiteCard">
        <h2>Review of Systems</h2> <br>
        <div class="commentBox">
            <label>Comments:</label> <br>
            <textarea></textarea>
        </div> <br> <br>
        <form id="reviewGrid">
            <div class="rosGroup">
                <div class="reviewItem">
                    <input type="checkbox" value="rashes">
                    <label>rashes</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>itching</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>hair/nails</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>headaches</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>head injury</label>
                </div>
                <br>
            </div>
            <div class="rosGroup">
                <p>Nose/Sinus</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>nose bleeds</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>stuffiness</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>frequent colds</label>
                </div>
                <br>
            </div>
            <div class="rosGroup">
                <p>Ears</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>hearing</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>ear pain</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label> ear discharge</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>ringing</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>dizziness</label>
                </div>
                <br>
            </div>
            <div class="rosGroup">
                <p>Eyes</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>glasses</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>change vision</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>eye pain</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>double vision</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>light flashes</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>glaucoma</label>
                </div>
                <div class="reviewItem">
                    <input type="text">
                    <label>date of last eye exam</label>
                </div>
                <br>
            </div>
            <div class="rosGroup">
                <p>Allergies</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>hives</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>swelling of lips or tongue</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>hay fever</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>asthma</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>eczema</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>sensitive to drugs, food, pollen, or dander</label>
                </div>
                <br>
            </div>
            <div class="rosGroup">
                <p>Psychiatric</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>anxiety</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>depression</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>memory</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>unusual problem</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>sleep</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>psychiatrist</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>mood</label>
                </div>
                <br>
            </div>    
            <div class="rosGroup">
                <p>Mouth</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>bleeding gums</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>sore tongue</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>sore throat</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>hoarseness</label>
                </div>
                <br>
            </div>
            <div class="rosGroup">
                <p>Neck</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>lumps</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>swollen glands</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>goiter</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>stiffness</label>
                </div>
                <br>
            </div>
            <div class="rosGroup">
                <p>Breasts</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>lumps</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>breast pain</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>nipple discharge</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>BSE</label>
                </div>
                <br>
            </div>
            <div class="rosGroup">
                <p>Circulation</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>leg cramps</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>varicose veins</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>clots in veins</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>muscle pain</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>muscle swelling</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>muscle stiffness</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>joint motion</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>broken bone</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>sprain</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>arthritis</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>gout</label>
                </div>
                <br>
            </div>
            <div class="rosGroup">
                <p>Neurological</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>seizures</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>fainting</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>paralysis</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>weakness</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>muscle size</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>muscle spasm</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>tremor</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>involuntary movements</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>incoordination</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>numbness</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>pins and needles</label>
                </div>
                <br>
            </div>
            <div class="rosGroup">
                <p>Endocrine</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>growth</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>appetite</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>thirst</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>increased urination</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>thyroid</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>head cold</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>sweating</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>diabetes</label>
                </div>
                <br>
            </div>
            <div class="rosGroup">
                <p>Digestive</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>appetite</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>swallowing</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>nausea</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>heartburn</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>vomiting</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>vomiting blood</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>constipation</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>diarrhea</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>bowels</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>abdominal pain</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>burping</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>farting</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>yellow skin</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>food intolerance</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>rectal bleeding</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>urination</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>urination pain</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>frequent urination</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>urgent urination</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>incontinence</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>dribble</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>urine stream</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>bloody urine</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>uti stones</label>
                </div>
                <br>
            </div>
            <div class="rosGroup">
                <p>Cardiovascular/Respiratory</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>shortness of breath</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>cough</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>phlem</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>wheezing</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>bloody cough</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>chest pain</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>fever</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>night sweats</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>swollen hands</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>blue toes</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>high blood pressure</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>skipping heart</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>heart murmur</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>HX of heart medication</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>bronchitis</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>rheumatic heart disease</label>
                </div>
                <br>
            </div>
            <div class="rosGroup">
                <p>Hematologic</p> <br>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>anemia</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>easy bruising</label>
                </div>
                <div class="reviewItem">
                    <input type="checkbox">
                    <label>transfusions</label>
                </div>
                <br>
            </div>
            <div></div>
            <div></div>
            <div class="saveButton">
                <input type="submit" value="Save" id="ProfileSave">
            </div>
        </form>
    </div>
    <footer>
        <div>
            <a href="./index.php">Home</a>
            <a href="./patient.php">Patient Note</a>
        </div>
    </footer>
</body>
</html>
