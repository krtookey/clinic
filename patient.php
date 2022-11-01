<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Bootstrap CSS -->
        <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
        crossorigin="anonymous"
        />

        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Patient</title>
        <script src="./patient.js" defer></script>
        <link rel="stylesheet" href="./style.css" />
    </head>
    <body>
        <header id='patientHeader'>
            <p>John Smith</p>
            <p>pref: Johnny</p>
            <p>DOB: 7/23/1990</p>
            <p>32y</p>
            <p></p>
            <p>Male</p>
        </header>
        <div class="patientBody">
            <section class="patientSideMenu">
                <!-- Medication List -->
                <button
                class="btn btn-primary patientSideMenuBtn"
                type="button"
                data-button-name='medicationList'
                aria-expanded="false"
                aria-controls="medicationListBox">
                    Medication List
                </button>
                <div
                class="collapse card card-body patientMenuBox hideContent medicationList"
                id="medicationListBox">
                    <div class="medicationList patientMenuItem">
                        Information about patient medication list
                    </div>
                </div>
                <!-- Review Of Systems -->
                <button
                class="btn btn-primary patientSideMenuBtn"
                type="button"
                data-button-name='reviewOfSystems'
                aria-expanded="false"
                aria-controls="reviewOfSystemsBox">
                    Review Of Systems
                </button>
                <div
                class="collapse card card-body patientMenuBox hideContent reviewOfSystems"
                id="reviewOfSystemsBox">
                    <div class="reviewOfSystems patientMenuItem">
                        Copy of review of systems form answers completed earlier
                    </div>
                </div>
                <!-- Lab Results -->
                <button
                class="btn btn-primary patientSideMenuBtn"
                type="button"
                data-button-name='labResults'
                aria-expanded="false"
                aria-controls="labResultsBox">
                    Lab Results
                </button>
                <div class="collapse hideContent patientMenuBox" id="labResultsBox">
                    <div class="labResults card card-body patientMenuItem">
                        Lab Results
                    </div>
                </div>
                <!-- Family History -->
                <button
                class="btn btn-primary patientSideMenuBtn"
                type="button"
                data-button-name='personalHistory'
                aria-expanded="false"
                aria-controls="personalHistoryBox">
                    Family History
                </button>
                <div class="collapse hideContent patientMenuBox" id="personalHistoryBox">
                    <div class="personalHistory card card-body patientMenuItem">
                        Personal History
                    </div>
                </div>

                <!-- OrderPharmacy -->
                <button
                class="btn btn-primary patientSideMenuBtn"
                type="button"
                data-button-name='orderPharmacy'
                aria-expanded="false"
                aria-controls="orderPharmacyBox">
                    Order Pharmacy
                </button>
                <div class="collapse hideContent patientMenuBox" id="orderPharmacyBox">
                    <div class="orderPharmacy card card-body patientMenuItem">

                        <!-- PHARMACY GROUP! Pharmacy order form goes here ---------------------------------------------------->

                    </div>
                </div>

                <!-- Order Lab -->
                <button
                class="btn btn-primary patientSideMenuBtn"
                type="button"
                data-button-name='orderLab'
                aria-expanded="false"
                aria-controls="orderLabBox">
                    Order Lab
                </button>
                <div class="collapse hideContent patientMenuBox" id="orderLabBox">
                    <div class="orderLab card card-body patientMenuItem">

                        <!-- PHARMACY GROUP! Lab order form goes here --------------------------------------------------------->

                    </div>
                </div>
                <!-- Note History -->
                <button
                class="btn btn-primary patientSideMenuBtn"
                type="button"
                data-button-name='noteHistory'
                aria-expanded="false"
                aria-controls="noteHistoryBox">
                    Note History
                </button>
                <div class="collapse hideContent patientMenuBox" id="noteHistoryBox">
                    <div class="noteHistory card card-body patientMenuItem">
                    </div>
                </div>
            </section>

            <!-- Current patient Note -->
            <section class="patientNote">
                <div class="card" id='patientFormCard'>
                    <div class="card-body">
                        <h3 class="card-title">10/27/22 - Today</h3>
                        <form id='patientNoteForm'>
                            <!-- Demographics  -->
                            <div class="mb-3 formField" id="demographicsContainer">
                                <label
                                for="demographics"
                                id="demographicsLabel">Demographics</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="demographics"
                                name="demographics"
                                ></textarea>
                            </div>
                            <!-- Chief Complaint -->
                            <div class="mb-3 formField" id="chiefComplaintContainer">
                                <label
                                for="chiefComplaint"
                                id="chiefComplaintLabel">Chief Complaint</label>
                                <textarea
                                rows="1"
                                class="form-control"
                                id="chiefComplaint"
                                name="chiefComplaint"
                                >*Text from the previous note*  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor inc</textarea>
                            </div>
                            <!-- History Of Illness -->
                            <div class="mb-3 formField" id="histOfIllnessContainer">
                                <label
                                for="histOfIllness"
                                id="histOfIllnessLabel"
                                >History Of Present Illness</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="histOfIllness"
                                name="histOfIllness"
                                >*Text from the previous note*  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. In est ante in nibh mauris cursus. Ac orci phasellus egestas tellus</textarea
                                >
                            </div>
                            <!-- Review Of Symptoms -->
                            <div class="mb-3 formField" id="reviewOfSymptomsContainer">
                                <label
                                for="reviewOfSymptoms"
                                id="reviewOfSymptomsLabel">Reivew Of Symptoms</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="reviewOfSymptoms"
                                name="reviewOfSymptoms"
                                >rci phasellus egestas tellus rutrum tellus pellentesque eu. Pellentesque pulvinar pellentesque habitant</textarea>
                            </div>
                            <!-- Social -->
                            <div class="mb-3 formField" id="socialContainer">
                                <label for="social" id="socialLabel">Social</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="social"
                                name="social">justo eget magna fermentum iaculis eu non. Magna etiam tempor </textarea>
                            </div>
                            <!-- Substance History -->
                            <div class="mb-3 formField" id="substanceHistContainer">
                                <label for="substanceHist" id="substanceHistLabel">Substance History</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="substanceHist"
                                name="substanceHist"> magna fermentum iaculis eu non. Magna etiam tempor </textarea>
                            </div>
                            <!-- Psychological History -->
                            <div class="mb-3 formField" id="psychHistContainer">
                                <label for="social" id="psychHistLabel"
                                >Psychological History</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="psychHist"
                                name="psychHist"
                                >ng elit, sed do eiusmod tempor incidid</textarea>
                            </div>
                            <!-- Medical History -->
                            <div class="mb-3 formField" id="medicalHistContainer">
                                <label for="social" id="medicalHistLabel"
                                >Medical History</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="medicalHist"
                                name="medicalHist"
                                >sellus egestas tellus rutrum tellus pellentesque eu. Pellentesque pulvinar pellentesque habitant morbi</textarea>
                            </div>
                            <!-- Family History -->
                            <div class="mb-3 formField" id="familyHistContainer">
                                <label for="social" id="familyHistLabel"
                                >Family History</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="familyHist"
                                name="familyHist">
                                utrum tellus pellentesque eu.</textarea>
                            </div>
                            <!-- Assessment/Formulation -->
                            <div class="mb-3 formField" id="assessmentContainer">
                                <label for="assessment" id="assessmentLabel"
                                >Assessment/Formulation</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="assessment"
                                name="assessment">estas tellus rutrum tellus pellentesque e</textarea>
                            </div>
                            <!-- Treatment Plan -->
                            <div class="mb-3 formField" id="treatmentPlanContainer">
                                <label
                                for="treatmentPlan"
                                id="treatmentPlanLabel"
                                >Treatment Plan</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="treatmentPlan"
                                name="treatmenPlan">od tempor incididunt ut labore et dolore magna aliqua.</textarea>
                            </div>
                            <!-- General Comments -->
                            <div class="mb-3 formField" id="generalCommentsContainer">
                                <label
                                for="generalComments"
                                id="generalCommentsLabel"
                                >General Comments</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="generalComments"
                                name="generalComments">
                                *Text from the previous note*</textarea>
                            </div>
                            <!-- Topics Discussed -->
                            <div class="mb-3 formField" id="topicsContainer">
                                <label for="topics" id="topicsLabel">Topics Discussed With Patient</label>
                                <textarea
                                rows="1"
                                cols="100"
                                class="form-control"
                                id="topics"
                                name="topics">
                                For insurence record</textarea>
                            </div>
                            <!-- Checkbox -->
                            <div class="mb-3 form-check formField" id="checkboxConatiner">
                                <input
                                type="checkbox"
                                class="form-check-input"
                                id="exampleCheck1"/>
                                <label class="form-check-label" for="exampleCheck1">Incase we need a checkbox</label>
                            </div>
                            <!-- Save Note -->
                            <button type="submit" class="btn btn-primary" id='patientFormSubmit'>Save Note</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>

        <!-- Bootstrap JS -->
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous">
        </script>
    </body>
</html>
