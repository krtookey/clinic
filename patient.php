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
    <link rel="stylesheet" href="./style.css" />
  </head>
  <body>
    <header>
      <p>John Smith</p>
      <p>DOB: 7/23/1990</p>
      <p>32y</p>
      <p></p>
      <p>Male</p>
    </header>
    <div class="patientBody">
      <section class="patientNote">
        <div class="card">
          <div class="card-body">
            <h3 class="card-title">10/27/22 - Today</h3>
            <form>
              <!-- Chief Complaint -->
              <div class="mb-3" id="chiefComplaintContainer">
                <label
                  for="chiefComplaint"
                  class="form-label"
                  id="chiefComplaintLabel"
                  >Chief Complaint</label
                >
                <textarea
                  rows="6"
                  class="form-control"
                  id="chiefComplaint"
                  name="chiefComplaint"
                  aria-describedby="chiefComplaintHelp"
                >
    *Text from the previous note*  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor inc                        </textarea
                >
                <div id="chiefComplaintHelp" class="form-text">
                  Text describing what this input box is for
                </div>
              </div>
              <!-- History Of Illness -->
              <div class="mb-3" id="histOfIllnessContainer">
                <label
                  for="histOfIllness"
                  class="form-label"
                  id="histOfIllnessLabel"
                  >History Of Present Illness</label
                >
                <textarea
                  rows="6"
                  cols="100"
                  class="form-control"
                  id="histOfIllness"
                  name="histOfIllness"
                  aria-describedby="histOfIllnessHelp"
                >
    *Text from the previous note*  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. In est ante in nibh mauris cursus. Ac orci phasellus egestas tellus</textarea
                >
                <div id="histOfIllnessHelp" class="form-text">
                  Text describing what this input box is for
                </div>
              </div>
              <!-- Review Of Symptoms -->
              <div class="mb-3" id="reviewOfSymptomsContainer">
                <label
                  for="reviewOfSymptoms"
                  class="form-label"
                  id="reviewOfSymptomsLabel"
                  >Reivew Of Symptoms</label
                >
                <textarea
                  rows="6"
                  cols="100"
                  class="form-control"
                  id="reviewOfSymptoms"
                  name="reviewOfSymptoms"
                >
    *Text from the previous note*  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. In est ante in nibh mauris cursus. Ac orci phasellus egestas tellus rutrum tellus pellentesque eu. Pellentesque pulvinar pellentesque habitant </textarea
                >
              </div>
              <!-- Social -->
              <div class="mb-3" id="socialContainer">
                <label for="social" class="form-label" id="socialLabel"
                  >Social</label
                >
                <textarea
                  rows="6"
                  cols="100"
                  class="form-control"
                  id="social"
                  name="social"
                  aria-describedby="socialHelp"
                >
    *Text from the previous note*  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt                           Mattis ullamcorper velit sed ullamcorper. Ipsum consequat nisl vel pretium lectus quam id leo in. Vitae elementum curabitur vitae nunc. Integer vitae justo eget magna fermentum iaculis eu non. Magna etiam tempor </textarea
                >
                <div id="socialHelp" class="form-text">
                  Who is with them, guardians, who do they live with, substance
                  use, etc.
                </div>
              </div>
              <!-- Medical History -->
              <div class="mb-3" id="medicalHistContainer">
                <label for="social" class="form-label" id="medicalHistLabel"
                  >Medical History</label
                >
                <textarea
                  rows="6"
                  cols="100"
                  class="form-control"
                  id="medicalHist"
                  name="medicalHist"
                >
    *Text from the previous note*  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. In est ante in nibh mauris cursus. Ac orci phasellus egestas tellus rutrum tellus pellentesque eu. Pellentesque pulvinar pellentesque habitant morbi</textarea
                >
              </div>
              <!-- Psychological History -->
              <div class="mb-3" id="psychHistContainer">
                <label for="social" class="form-label" id="psychHistLabel"
                  >Psycological History</label
                >
                <textarea
                  rows="6"
                  cols="100"
                  class="form-control"
                  id="psychHist"
                  name="psychHist"
                >
    *Text from the previous note*  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incidid           </textarea
                >
              </div>
              <!-- Family History -->
              <div class="mb-3" id="familyHistContainer">
                <label for="social" class="form-label" id="familyHistLabel"
                  >Family History</label
                >
                <textarea
                  rows="6"
                  cols="100"
                  class="form-control"
                  id="familyHist"
                  name="familyHist"
                >
    *Text from the previous note*  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. In est ante in nibh mauris cursus. Ac orci phasellus egestas tellus rutrum tellus pellentesque eu. </textarea
                >
              </div>
              <!-- Assessment -->
              <div class="mb-3" id="assessmentContainer">
                <label for="assessment" class="form-label" id="assessmentLabel"
                  >Assessment</label
                >
                <textarea
                  rows="6"
                  cols="100"
                  class="form-control"
                  id="assessment"
                  name="assessment"
                  aria-describedby="assessmentHelp"
                >
    *Text from the previous note*  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. In est ante in nibh mauris cursus. Ac orci phasellus egestas tellus rutrum tellus pellentesque e    </textarea
                >
                <div id="assessmentHelp" class="form-text">
                  Text describing what this input box is for
                </div>
              </div>
              <!-- Treatment Plan -->
              <div class="mb-3" id="treatmentPlanContainer">
                <label
                  for="treatmentPlan"
                  class="form-label"
                  id="treatmentPlanLabel"
                  >Treatment Plan</label
                >
                <textarea
                  rows="6"
                  cols="100"
                  class="form-control"
                  id="treatmentPlan"
                  name="treatmenPlan"
                  aria-describedby="treatmentPlanHelp"
                >
    *Text from the previous note*  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </textarea
                >
                <div id="treatmentPlanHelp" class="form-text">
                  Text describing what this input box is for
                </div>
              </div>
              <!-- Demographics  -->
              <div class="mb-3" id="demographicsContainer">
                <label
                  for="demographics"
                  class="form-label"
                  id="demographicsLabel"
                  >Demographics</label
                >
                <textarea
                  rows="6"
                  cols="100"
                  class="form-control"
                  id="demographics"
                  name="demographics"
                >
                </textarea>
                <div id="demographicsHelp" class="form-text">
                  Emergency contacts, Parents demographics, Custody issues, etc.
                </div>
              </div>
              <!-- General Comments -->
              <div class="mb-3" id="generalCommentsContainer">
                <label
                  for="generalComments"
                  class="form-label"
                  id="generalCommentsLabel"
                  >General Comments</label
                >
                <textarea
                  rows="6"
                  cols="100"
                  class="form-control"
                  id="generalComments"
                  name="generalComments"
                >
    *Text from the previous note*</textarea
                >
              </div>
              <!-- Topics Discussed -->
              <div class="mb-3" id="topicsContainer">
                <label for="topics" class="form-label" id="topicsLabel"
                  >Topics Discussed With Patient</label
                >
                <textarea
                  rows="6"
                  cols="100"
                  class="form-control"
                  id="topics"
                  name="topics"
                >
    For insurence record</textarea
                >
              </div>
              <!-- Checkbox -->
              <div class="mb-3 form-check" id="checkboxConatiner">
                <input
                  type="checkbox"
                  class="form-check-input"
                  id="exampleCheck1"
                />
                <label class="form-check-label" for="exampleCheck1"
                  >Incase we need a checkbox</label
                >
              </div>
              <!-- Save Note -->
              <button type="submit" class="btn btn-primary">Save Note</button>
            </form>
          </div>
        </div>
      </section>
      <section class="patientSideMenu">
        <button
          class="btn btn-primary patientSideMenuBtn"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#medicationListBox"
          aria-expanded="false"
          aria-controls="medicationListBox"
        >
          Medication List
        </button>
        <div
          class="collapse card card-body patientMenuItem"
          id="medicationListBox"
        >
          <div class="medicationList">
            Information about patient medication list
          </div>
        </div>
        <button
          class="btn btn-primary patientSideMenuBtn"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#labResultsBox"
          aria-expanded="false"
          aria-controls="labResultsBox"
        >
          Lab Results
        </button>
        <div class="collapse" id="labResultsBox">
          <div class="labResults card card-body patientMenuItem">
            Lab Results
          </div>
        </div>
        <button
          class="btn btn-primary patientSideMenuBtn"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#personalHistoryBox"
          aria-expanded="false"
          aria-controls="personalHistoryBox"
        >
          Personal History
        </button>
        <div class="collapse" id="personalHistoryBox">
          <div class="personalHistory card card-body patientMenuItem">
            Personal History
          </div>
        </div>
      </section>
    </div>

    <!-- Bootrap JS -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
